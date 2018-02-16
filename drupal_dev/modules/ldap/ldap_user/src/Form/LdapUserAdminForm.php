<?php

namespace Drupal\ldap_user\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;

use Drupal\Core\Url;
use Drupal\ldap_servers\Processor\TokenProcessor;
use Drupal\ldap_user\Helper\LdapConfiguration;
use Drupal\ldap_user\Helper\SemaphoreStorage;
use Drupal\ldap_user\Helper\SyncMappingHelper;

/**
 *
 */
class LdapUserAdminForm extends ConfigFormBase {

  protected $drupalAcctProvisionServerOptions;
  protected $ldapEntryProvisionServerOptions;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);

    $factory = \Drupal::service('ldap.servers');
    $ldap_servers = $factory->getEnabledServers();
    if ($ldap_servers) {
      foreach ($ldap_servers as $sid => $ldap_server) {
        /** @var \Drupal\ldap_servers\Entity\Server $ldap_server */
        $enabled = ($ldap_server->get('status')) ? 'Enabled' : 'Disabled';
        $this->drupalAcctProvisionServerOptions[$sid] = $ldap_server->label() . ' (' . $ldap_server->get('address') . ') Status: ' . $enabled;
        $this->ldapEntryProvisionServerOptions[$sid] = $ldap_server->label() . ' (' . $ldap_server->get('address') . ') Status: ' . $enabled;
      }
    }
    $this->drupalAcctProvisionServerOptions['none'] = t('None');
    $this->ldapEntryProvisionServerOptions['none'] = t('None');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ldap_user_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['ldap_user.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ldap_user.settings');

    if (count($this->drupalAcctProvisionServerOptions) == 0) {
      $url = Url::fromRoute('entity.ldap_server.collection');
      $edit_server_link = \Drupal::l(t('@path', ['@path' => 'LDAP Servers']), $url);
      $message = t('At least one LDAP server must configured and <em>enabled</em> before configuring LDAP user. Please go to @link to configure an LDAP server.',
        ['@link' => $edit_server_link]
      );
      $form['intro'] = [
        '#type' => 'item',
        '#markup' => t('<h1>LDAP User Settings</h1>') . $message,
      ];
      return $form;
    }
    $form['#storage'] = [];

    $form['intro'] = [
      '#type' => 'item',
      '#markup' => t('<h1>LDAP User Settings</h1>'),
    ];


    $form['server_mapping_preamble'] = [
      '#type' => 'markup',
      '#markup' => t('The relationship between a Drupal user and an LDAP entry is defined within the LDAP server configurations. The mappings below are for user fields, properties and data that are not automatically mapped elsewhere. <br>Read-only mappings are generally configured on the server configuration page and shown here as a convenience to you.'),
    ];

    $form['manual_drupal_account_editing'] = [
      '#type' => 'fieldset',
      '#title' => t('Manual Drupal Account Creation'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['manual_drupal_account_editing']['manualAccountConflict'] = [
      '#type' => 'radios',
      '#options' => [
        LdapConfiguration::$manualAccountConflictLdapAssociate => t('Associate accounts, if available.'),
        LdapConfiguration::$manualAccountConflictNoLdapAssociate => t('Do not associate accounts, allow conflicting accounts.'),
        LdapConfiguration::$manualAccountConflictReject => t('Do not associate accounts, reject conflicting accounts.'),
        LdapConfiguration::$manualAccountConflictShowOptionOnForm => t('Show option on user create form to associate or not.'),
      ],
      '#title' => t('How to resolve LDAP conflicts with manually created user accounts.'),
      '#description' => t('This applies only to accounts created manually through admin/people/create for which an LDAP entry can be found on the LDAP server selected in "LDAP Servers Providing Provisioning Data"'),
      '#default_value' => $config->get('manualAccountConflict'),
    ];

    $form['basic_to_drupal'] = [
      '#type' => 'fieldset',
      '#title' => t('Basic Provisioning to Drupal Account Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => !($config->get('drupalAcctProvisionServer')),
    ];

    $form['basic_to_drupal']['drupalAcctProvisionServer'] = [
      '#type' => 'radios',
      '#title' => t('LDAP Servers Providing Provisioning Data'),
      '#required' => 1,
      '#default_value' => $config->get('drupalAcctProvisionServer') ? $config->get('drupalAcctProvisionServer') : 'none',
      '#options' => $this->drupalAcctProvisionServerOptions,
      '#description' => t('Choose the LDAP server configuration to use in provisioning Drupal users and their user fields.'),
      '#states' => [
        // Action to take.
        'enabled' => [
          ':input[name=drupalAcctProvisionTriggers]' => ['value' => LdapConfiguration::PROVISION_DRUPAL_USER_ON_USER_AUTHENTICATION],
        ],
      ],
    ];

    $form['basic_to_drupal']['drupalAcctProvisionTriggers'] = [
      '#type' => 'checkboxes',
      '#title' => t('Drupal Account Provisioning Events'),
      '#required' => FALSE,
      '#default_value' => $config->get('drupalAcctProvisionTriggers'),
      '#options' => [
        LdapConfiguration::PROVISION_DRUPAL_USER_ON_USER_AUTHENTICATION => t('Create or Sync to Drupal user on successful authentication with LDAP credentials. (Requires LDAP Authentication module).'),
        LdapConfiguration::PROVISION_DRUPAL_USER_ON_USER_UPDATE_CREATE => t('Create or Sync to Drupal user anytime a Drupal user account is created or updated. Requires a server with binding method of "Service Account Bind" or "Anonymous Bind".'),
      ],
      '#description' => t('Which user fields and properties are synced on create or sync is determined in the "Provisioning from LDAP to Drupal mappings" table below in the right two columns.'),
    ];

    $form['basic_to_drupal']['userConflictResolve'] = [
      '#type' => 'radios',
      '#title' => t('Existing Drupal User Account Conflict'),
      '#required' => 1,
      '#default_value' => $config->get('userConflictResolve'),
      '#options' => [
        LdapConfiguration::$userConflictLog => t('Don\'t associate Drupal account with LDAP.  Require user to use Drupal password. Log the conflict'),
        LdapConfiguration::$userConflictAttemptResolve => t('Associate Drupal account with the LDAP entry.  This option is useful for creating accounts and assigning roles before an LDAP user authenticates.'),
      ],
      '#description' => t('What should be done if a local Drupal or other external user account already exists with the same login name.'),
    ];

    $form['basic_to_drupal']['acctCreation'] = [
      '#type' => 'radios',
      '#title' => t('Application of Drupal Account settings to LDAP Authenticated Users'),
      '#required' => 1,
      '#default_value' => $config->get('acctCreation'),
      '#options' => [
        LdapConfiguration::$accountCreationLdapBehaviour => t('Account creation settings at /admin/config/people/accounts/settings do not affect "LDAP Associated" Drupal accounts.'),
        LdapConfiguration::$accountCreationUserSettingsForLdap => t('Account creation policy at /admin/config/people/accounts/settings applies to both Drupal and LDAP Authenticated users. "Visitors" option automatically creates and account when they successfully LDAP authenticate. "Admin" and "Admin with approval" do not allow user to authenticate until the account is approved.'),

      ],
    ];

    $account_options = [];
    $account_options['ldap_user_orphan_do_not_check'] = t('Do not check for orphaned Drupal accounts.');
    $account_options['ldap_user_orphan_email'] = t('Perform no action, but email list of orphaned accounts. (All the other options will send email summaries also.)');
    foreach (user_cancel_methods()['#options'] as $option_name => $option_title) {
      $account_options[$option_name] = $option_title;
    }

    $form['basic_to_drupal']['orphanedAccounts'] = [
      '#type' => 'fieldset',
      '#title' => 'Orphaned account cron job',
      '#description' => $this->t('<strong>Warning: Use this feature at your own risk!</strong>'),
    ];

    $form['basic_to_drupal']['orphanedAccounts']['orphanedDrupalAcctBehavior'] = [
      '#type' => 'radios',
      '#title' => t('Action to perform on Drupal accounts that no longer have
        corresponding LDAP entries'),
      '#required' => 0,
      '#default_value' => $config->get('orphanedDrupalAcctBehavior'),
      '#options' => $account_options,
      '#description' => t('It is highly recommended to fetch an email report first before attempting to disable or even delete users.'),
    ];

    $form['basic_to_drupal']['orphanedAccounts']['orphanedCheckQty'] = [
      '#type' => 'textfield',
      '#size' => 10,
      '#title' => t('Number of users to check each cron run.'),
      '#description' => t(''),
      '#default_value' => $config->get('orphanedCheckQty'),
      '#required' => FALSE,
    ];

    $form['basic_to_drupal']['orphanedAccounts']['orphanedAccountCheckInterval'] = [
      '#type' => 'select',
      '#title' => t('How often should each user be checked again?'),
      '#default_value' => $config->get('orphanedAccountCheckInterval'),
      '#options' => [
        'always' => $this->t('On every cron run'),
        'daily' => $this->t('Daily'),
        'weekly' => $this->t('Weekly'),
        'monthly' => $this->t('Monthly'),
      ],
      '#required' => FALSE,
    ];

    $form['basic_to_ldap'] = [
      '#type' => 'fieldset',
      '#title' => t('Basic Provisioning to LDAP Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => !($config->get('ldapEntryProvisionServer')),
    ];

    $form['basic_to_ldap']['ldapEntryProvisionServer'] = [
      '#type' => 'radios',
      '#title' => t('LDAP Servers to Provision LDAP Entries on'),
      '#required' => 1,
      '#default_value' => $config->get('ldapEntryProvisionServer') ? $config->get('ldapEntryProvisionServer') : 'none',
      '#options' => $this->ldapEntryProvisionServerOptions,
      '#description' => t('Check ONE LDAP server configuration to create LDAP entries on.'),
    ];

    $form['basic_to_ldap']['ldapEntryProvisionTriggers'] = [
      '#type' => 'checkboxes',
      '#title' => t('LDAP Entry Provisioning Events'),
      '#required' => FALSE,
      '#default_value' => $config->get('ldapEntryProvisionTriggers'),
      '#options' => [
        LdapConfiguration::PROVISION_LDAP_ENTRY_ON_USER_ON_USER_UPDATE_CREATE => t('Create or Sync to LDAP entry when a Drupal account is created or updated.
        Only applied to accounts with a status of approved.'),
        LdapConfiguration::PROVISION_LDAP_ENTRY_ON_USER_ON_USER_AUTHENTICATION => t('Create or Sync to LDAP entry when a user authenticates.'),
        LdapConfiguration::PROVISION_LDAP_ENTRY_ON_USER_ON_USER_DELETE => t('Delete LDAP entry when the corresponding Drupal Account is deleted.  This only applies when the LDAP entry was provisioned by Drupal by the LDAP User module.'),
        LdapConfiguration::PROVISION_DRUPAL_USER_ON_USER_ON_MANUAL_CREATION => t('Provide option on admin/people/create to create corresponding LDAP Entry.'),

      ],
      '#description' => t('Which LDAP attributes are synced on create or sync is determined in the
      "Provisioning from Drupal to LDAP mappings" table below in the right two columns.'),
    ];

    foreach ([LdapConfiguration::PROVISION_TO_DRUPAL, LdapConfiguration::PROVISION_TO_LDAP] as $direction) {

      if ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) {
        $parent_fieldset = 'basic_to_drupal';
        $description = t('Provisioning from LDAP to Drupal Mappings:');
      }
      else {
        $parent_fieldset = 'basic_to_ldap';
        $description = t('Provisioning from Drupal to LDAP Mappings:');
      }

      $mapping_id = 'mappings__' . $direction;
      $table_id = $mapping_id . '__table';

      $form[$parent_fieldset][$mapping_id] = [
        '#type' => 'fieldset',
        '#title' => $description,
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
        '#description' => t('See also the <a href="@wiki_link">Drupal.org wiki page</a> for further information on using LDAP tokens.',
          ['@wiki_link' => 'http://drupal.org/node/1245736']),
      ];

      $form[$parent_fieldset][$mapping_id][$table_id] = [
        '#type' => 'table',
        '#header' => [t('Label'), t('Machine name'), t('Weight'), t('Operations')],
        '#attributes' => ['class' => ['mappings-table']],
      ];

      $headers = $this->getServerMappingHeader($direction);
      $form[$parent_fieldset][$mapping_id][$table_id]['#header'] = $headers['header'];
      // Add in the second header as the first row.
      $form[$parent_fieldset][$mapping_id][$table_id]['second-header'] = [
        '#attributes' => ['class' => 'header'],
      ];
      // Second header uses the same format as header.
      foreach ($headers['second_header'] as $cell) {
        $form[$parent_fieldset][$mapping_id][$table_id]['second-header'][] = [
          '#title' => $cell['data'],
          '#type' => 'item',
        ];
        if (isset($cell['class'])) {
          $form[$parent_fieldset][$mapping_id][$table_id]['second-header']['#attributes'] = ['class' => [$cell['class']]];
        }
        if (isset($cell['rowspan'])) {
          $form[$parent_fieldset][$mapping_id][$table_id]['second-header']['#rowspan'] = $cell['rowspan'];
        }
        if (isset($cell['colspan'])) {
          $form[$parent_fieldset][$mapping_id][$table_id]['second-header']['#colspan'] = $cell['colspan'];
        }
      }

      $form[$parent_fieldset][$mapping_id][$table_id] += $this->getServerMappingFields($direction);

      $password_notes = '<h3>' . t('Password Tokens') . '</h3><ul>' .
        '<li>' . t('Pwd: Random -- Uses a random Drupal generated password') . '</li>' .
        '<li>' . t('Pwd: User or Random -- Uses password supplied on user forms.
  If none available uses random password.') . '</li></ul>' .
        '<h3>' . t('Password Concerns') . '</h3>' .
        '<ul>' .
        '<li>' . t('Provisioning passwords to LDAP means passwords must meet the LDAP\'s
password requirements.  Password Policy module can be used to add requirements.') . '</li>' .
        '<li>' . t('Some LDAPs require a user to reset their password if it has been changed
by someone other that user.  Consider this when provisioning LDAP passwords.') . '</li>' .
        '</ul></p>';

      $source_drupal_token_notes = <<<EOT
<p>Examples in form: Source Drupal User token => Target LDAP Token (notes)</p>
<ul>
<li>Source Drupal User token => Target LDAP Token</li>
<li>cn=[property.name],ou=test,dc=ad,dc=mycollege,dc=edu => [dn] (example of token and constants)</li>
<li>top => [objectclass:0] (example of constants mapped to multivalued attribute)</li>
<li>person => [objectclass:1] (example of constants mapped to multivalued attribute)</li>
<li>organizationalPerson => [objectclass:2] (example of constants mapped to multivalued attribute)</li>
<li>user => [objectclass:3] (example of constants mapped to multivalued attribute)</li>
<li>Drupal Provisioned LDAP Account => [description] (example of constant)</li>
<li>[field.field_lname] => [sn]</li>

</ul>
EOT;

      // Add some password notes.
      if ($direction == LdapConfiguration::PROVISION_TO_LDAP) {
        $form[$parent_fieldset]['password_notes'] = [
          '#type' => 'fieldset',
          '#title' => t('Password Notes'),
          '#collapsible' => TRUE,
          '#collapsed' => TRUE,
          'directions' => [
            '#type' => 'markup',
            '#markup' => $password_notes,
          ],
        ];
        $form[$parent_fieldset]['source_drupal_token_notes'] = [
          '#type' => 'fieldset',
          '#title' => t('Source Drupal User Tokens and Corresponding Target LDAP Tokens'),
          '#collapsible' => TRUE,
          '#collapsed' => TRUE,
          'directions' => [
            '#type' => 'markup',
            '#markup' => $source_drupal_token_notes,
          ],
        ];
      }
    }

    foreach (['acctCreation', 'userConflictResolve', 'drupalAcctProvisionTriggers', 'mappings__' . LdapConfiguration::PROVISION_TO_DRUPAL] as $input_name) {
      $form['basic_to_drupal'][$input_name]['#states']['invisible'] =
        [
          ':input[name=drupalAcctProvisionServer]' => ['value' => 'none'],
        ];
    }

    $form['basic_to_drupal']['orphanedAccounts']['#states']['invisible'] =
      [
        ':input[name=drupalAcctProvisionServer]' => ['value' => 'none'],
      ];

    foreach (['orphanedCheckQty', 'orphanedAccountCheckInterval'] as $input_name) {
      $form['basic_to_drupal']['orphanedAccounts'][$input_name]['#states']['invisible'] =
        [
          ':input[name=orphanedDrupalAcctBehavior]' => ['value' => 'ldap_user_orphan_do_not_check'],
        ];
    }

    foreach (['ldapEntryProvisionTriggers', 'password_notes', 'source_drupal_token_notes', 'mappings__' . LdapConfiguration::PROVISION_TO_LDAP] as $input_name) {
      $form['basic_to_ldap'][$input_name]['#states']['invisible'] =
        [
          ':input[name=ldapEntryProvisionServer]' => ['value' => 'none'],
        ];
    }

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save',
    ];

    $this->notifyMissingSyncServerCombination($config);

    return $form;

  }

  /**
   * Check if the user starts with an an invalid configuration.
   *
   * @param \Drupal\Core\Config\Config $config
   */
  private function notifyMissingSyncServerCombination(Config $config) {

    $hasDrupalAcctProvServers = $config->get('drupalAcctProvisionServer');
    $hasDrupalAcctProvSettingsOptions = (count(array_filter($config->get('drupalAcctProvisionTriggers'))) > 0);
    if (!$config->get('drupalAcctProvisionServer') && $hasDrupalAcctProvSettingsOptions) {
      drupal_set_message(t('No servers are enabled to provide provisioning to Drupal, but Drupal account provisioning options are selected.'), 'warning');
    }
    else if ($hasDrupalAcctProvServers && !$hasDrupalAcctProvSettingsOptions) {
      drupal_set_message(t('Servers are enabled to provide provisioning to Drupal, but no Drupal account provisioning options are selected. This will result in no syncing happening.'), 'warning');
    }

    $has_ldap_prov_servers = $config->get('ldapEntryProvisionServer');
    $has_ldap_prov_settings_options = (count(array_filter($config->get('ldapEntryProvisionTriggers'))) > 0);
    if (!$has_ldap_prov_servers && $has_ldap_prov_settings_options) {
      drupal_set_message(t('No servers are enabled to provide provisioning to LDAP, but LDAP entry options are selected.'), 'warning');
    }
    if ($has_ldap_prov_servers && !$has_ldap_prov_settings_options) {
      drupal_set_message(t('Servers are enabled to provide provisioning to LDAP, but no LDAP entry options are selected. This will result in no syncing happening.'), 'warning');
    }
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $drupalMapKey = 'mappings__' . LdapConfiguration::PROVISION_TO_DRUPAL . '__table';
    $ldapMapKey = 'mappings__' . LdapConfiguration::PROVISION_TO_LDAP . '__table';

    if ($values['drupalAcctProvisionServer'] != 'none') {
      foreach ($values[$drupalMapKey] as $key => $mapping) {
        if (isset($mapping['configurable_to_drupal']) && $mapping['configurable_to_drupal'] == 1) {

          // Check that source is not empty for selected field to sync to Drupal.
          if ($mapping['user_attr'] !== '0') {
            if ($mapping['ldap_attr'] == NULL) {
              $formElement = $form['basic_to_drupal']['mappings__' . LdapConfiguration::PROVISION_TO_DRUPAL][$drupalMapKey][$key];
              $form_state->setError($formElement, t('Missing LDAP attribute'));
            }
          }
        }
      }
    }

    if ($values['ldapEntryProvisionServer'] != 'none') {
      foreach ($values[$ldapMapKey] as $key => $mapping) {
        if (isset($mapping['configurable_to_drupal']) && $mapping['configurable_to_drupal'] == 1) {
          // Check that the token is not empty if a user token is in use.
          if (isset($mapping['user_attr']) && $mapping['user_attr'] == 'user_tokens') {
            if (isset($mapping['user_tokens']) && empty(trim($mapping['user_tokens']))) {
              $formElement = $form['basic_to_ldap']['mappings__' . LdapConfiguration::PROVISION_TO_LDAP][$ldapMapKey][$key];
              $form_state->setError($formElement, t('Missing user token.'));
            }
          }

          // Check that a target attribute is set.
          if ($mapping['user_attr'] !== '0') {
            if ($mapping['ldap_attr'] == NULL) {
              $formElement = $form['basic_to_ldap']['mappings__' . LdapConfiguration::PROVISION_TO_LDAP][$ldapMapKey][$key];
              $form_state->setError($formElement, t('Missing LDAP attribute'));
            }
          }
        }
      }
    }

    $processedLdapSyncMappings = $this->syncMappingsFromForm($form_state->getValues(), LdapConfiguration::PROVISION_TO_LDAP);
    $processedDrupalSyncMappings = $this->syncMappingsFromForm($form_state->getValues(), LdapConfiguration::PROVISION_TO_DRUPAL);

    // Set error for entire table if [dn] is missing.
    if ($values['ldapEntryProvisionServer'] != 'none' && !isset($processedLdapSyncMappings['dn'])) {
      $form_state->setErrorByName($ldapMapKey,
        t('Mapping rows exist for provisioning to LDAP, but no LDAP attribute is targeted for [dn]. One row must map to [dn]. This row will have a user token like cn=[property.name],ou=users,dc=ldap,dc=mycompany,dc=com')
      );
    }

    // Make sure only one attribute column is present.
    $tokenHelper = new TokenProcessor();
    foreach ($processedLdapSyncMappings as $key => $mapping) {
      $maps = $tokenHelper->getTokenAttributes($mapping['ldap_attr']);
      if (count(array_keys($maps)) > 1) {
        // TODO: Move this check out of processed mappings to be able to set the error by field.
        $form_state->setErrorByName($ldapMapKey,
          t('When provisioning to ldap, ldap attribute column must be singular token such as [cn]. %ldap_attr is not. Do not use compound tokens such as "[displayName] [sn]" or literals such as "physics".',
            ['%ldap_attr' => $mapping['ldap_attr']]
          )
        );
      }
    }

    // Notify the user if no actual synchronization event is active for a field.
    $this->checkEmptyEvents($processedLdapSyncMappings);
    $this->checkEmptyEvents($processedDrupalSyncMappings);

  }

  private function checkEmptyEvents($mappings) {
    foreach ($mappings as $mapping) {
      if (empty($mapping['prov_events'])) {
        drupal_set_message(t('No synchronization events checked in %item. This field will not be synchronized until some are checked.',
          ['%item' => $mapping['ldap_attr']]
        ), 'warning');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $drupalAcctProvisionServer = ($form_state->getValue('drupalAcctProvisionServer') == 'none') ? NULL : $form_state->getValue('drupalAcctProvisionServer');
    $ldapEntryProvisionServer = ($form_state->getValue('ldapEntryProvisionServer') == 'none') ? NULL : $form_state->getValue('ldapEntryProvisionServer');

    $processedSyncMappings[LdapConfiguration::PROVISION_TO_DRUPAL] = $this->syncMappingsFromForm($form_state->getValues(), LdapConfiguration::PROVISION_TO_DRUPAL);
    $processedSyncMappings[LdapConfiguration::PROVISION_TO_LDAP] = $this->syncMappingsFromForm($form_state->getValues(), LdapConfiguration::PROVISION_TO_LDAP);

    $this->config('ldap_user.settings')
      ->set('drupalAcctProvisionServer', $drupalAcctProvisionServer)
      ->set('ldapEntryProvisionServer', $ldapEntryProvisionServer)
      ->set('drupalAcctProvisionTriggers', $form_state->getValue('drupalAcctProvisionTriggers'))
      ->set('ldapEntryProvisionTriggers', $form_state->getValue('ldapEntryProvisionTriggers'))
      ->set('orphanedDrupalAcctBehavior', $form_state->getValue('orphanedDrupalAcctBehavior'))
      ->set('orphanedCheckQty', $form_state->getValue('orphanedCheckQty'))
      ->set('orphanedAccountCheckInterval', $form_state->getValue('orphanedAccountCheckInterval'))
      ->set('userConflictResolve', $form_state->getValue('userConflictResolve'))
      ->set('manualAccountConflict', $form_state->getValue('manualAccountConflict'))
      ->set('acctCreation', $form_state->getValue('acctCreation'))
      ->set('ldapUserSyncMappings', $processedSyncMappings)
      ->save();
    $form_state->getValues();

    SemaphoreStorage::flushAllValues();
    \Drupal::cache()->invalidate('ldap_user_sync_mapping');
    drupal_set_message(t('User synchronization configuration updated.'));
  }

  /**
   * Migrated from ldap_user.theme.inc .
   */
  private function getServerMappingHeader($direction) {

    if ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) {
      $header = [
        [
          'data' => t('Source LDAP tokens') ,
          'rowspan' => 1,
          'colspan' => 2,
        ],
        [
          'data' => t('Target Drupal attribute'),
          'rowspan' => 1,
        ],
        [
          'data' => t('Synchronization event'),
          'colspan' => count(LdapConfiguration::provisionsDrupalEvents()),
          'rowspan' => 1,
        ],

      ];

      $second_header = [
        [
          'data' => t('Examples:<ul><li>[sn]</li><li>[mail:0]</li><li>[ou:last]</li><li>[sn], [givenName]</li></ul>
                Constants such as <em>17</em> or <em>imported</em> should not be enclosed in [].'),
          'header' => TRUE,
        ],
        [
          'data' => t('Convert from binary'),
          'header' => TRUE,
        ],
        [
          'data' => '',
          'header' => TRUE,
        ],
      ];

      foreach (LdapConfiguration::provisionsDrupalEvents() as $col_id => $col_name) {
        $second_header[] = ['data' => $col_name, 'header' => TRUE, 'class' => 'header-provisioning'];
      }
    }
    // To ldap.
    else {
      $header = [
        [
          'data' => t('Source Drupal user attribute') ,
          'rowspan' => 1,
          'colspan' => 3,
        ],
        [
          'data' => t('Target LDAP token'),
          'rowspan' => 1,
        ],
        [
          'data' => t('Synchronization event'),
          'colspan' => count(LdapConfiguration::provisionsLdapEvents()),
          'rowspan' => 1,
        ],
      ];

      $second_header = [
        [
          'data' => t('Note: Select <em>user tokens</em> to use token field.'),
          'header' => TRUE,
        ],
        [
          'data' => t('Source Drupal user tokens such as: <ul><li>[property.name]</li><li>[field.field_fname]</li><li>[field.field_lname]</li></ul> Constants such as <em>from_drupal</em> or <em>18</em> should not be enclosed in [].'),
          'header' => TRUE,
        ],
        [
          'data' => t('Convert From binary'),
          'header' => TRUE,
        ],
        [
          'data' => t('Use singular token format such as: <ul><li>[sn]</li><li>[givenName]</li></ul>'),
          'header' => TRUE,
        ],
      ];
      foreach (LdapConfiguration::provisionsLdapEvents() as $col_id => $col_name) {
        $second_header[] = ['data' => $col_name, 'header' => TRUE, 'class' => 'header-provisioning'];
      }
    }
    return ['header' => $header, 'second_header' => $second_header];
  }

  /**
   * @param $direction
   * @return array
   */
  private function getServerMappingFields($direction) {
    if ($direction == LdapConfiguration::PROVISION_TO_NONE) {
      return;
    }

    $rows = [];

    $text = ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) ? 'target' : 'source';
    $user_attr_options = ['0' => t('Select') . ' ' . $text];
    $syncMappings = new SyncMappingHelper();
    if (!empty($syncMappings->syncMapping[$direction])) {
      foreach ($syncMappings->syncMapping[$direction] as $target_id => $mapping) {

        if (!isset($mapping['name']) || isset($mapping['exclude_from_mapping_ui']) && $mapping['exclude_from_mapping_ui']) {
          continue;
        }
        if (
          (isset($mapping['configurable_to_drupal']) && $mapping['configurable_to_drupal'] && $direction == LdapConfiguration::PROVISION_TO_DRUPAL)
          ||
          (isset($mapping['configurable_to_ldap']) && $mapping['configurable_to_ldap']  && $direction == LdapConfiguration::PROVISION_TO_LDAP)
        ) {
          $user_attr_options[$target_id] = $mapping['name'];
        }
      }
    }

    if ($direction != LdapConfiguration::PROVISION_TO_DRUPAL) {
      $user_attr_options['user_tokens'] = '-- user tokens --';
    }

    $row = 0;

    // 1. non configurable mapping rows.
    foreach ($syncMappings->syncMapping[$direction] as $target_id => $mapping) {
      $row_id = $this->sanitise_machine_name($target_id);
      if (isset($mapping['exclude_from_mapping_ui']) && $mapping['exclude_from_mapping_ui']) {
        continue;
      }
      // Is configurable by ldap_user module (not direction to ldap_user)
      if (!$this->isMappingConfigurable($mapping, 'ldap_user') && ($mapping['direction'] == $direction || $mapping['direction'] == LdapConfiguration::PROVISION_TO_ALL)) {
        $rows[$row_id] = $this->getSyncFormRow('nonconfigurable', $direction, $mapping, $user_attr_options, $row_id);
        $row++;
      }
    }
    $config = $this->config('ldap_user.settings');

    // 2. existing configurable mappings rows.
    if (!empty($config->get('ldapUserSyncMappings')[$direction])) {
      // Key could be ldap attribute name or user attribute name.
      foreach ($config->get('ldapUserSyncMappings')[$direction] as $target_attr_token => $mapping) {
        if ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) {
          $mapping_key = $mapping['user_attr'];
        }
        else {
          $mapping_key = $mapping['ldap_attr'];
        }
        if (isset($mapping['enabled']) && $mapping['enabled'] && $this->isMappingConfigurable($syncMappings->syncMapping[$direction][$mapping_key], 'ldap_user')) {
          $row_id = 'row-' . $row;
          $rows[$row_id] = $this->getSyncFormRow('update', $direction, $mapping, $user_attr_options, $row_id);
          $row++;
        }
      }
    }

    // 3. leave 4 rows for adding more mappings.
    for ($i = 0; $i < 4; $i++) {
      $row_id = 'custom-' . $i;
      $rows[$row_id] = $this->getSyncFormRow('add', $direction, NULL, $user_attr_options, $row_id);
      $row++;
    }

    return $rows;
  }

  /**
   * Get mapping form row to ldap user provisioning mapping admin form table.
   *
   * @param string $action
   *   is 'add', 'update', or 'nonconfigurable'.
   * @param string $direction
   *   LdapConfiguration::PROVISION_TO_DRUPAL or LdapConfiguration::PROVISION_TO_LDAP.
   * @param array $mapping
   *   is current setting for updates or nonconfigurable items.
   * @param array $user_attr_options
   *   of drupal user target options.
   * @param $row_id
   *   is current row in table.
   *
   * @return array A single row
   *   A single row
   */
  private function getSyncFormRow($action, $direction, $mapping, $user_attr_options, $row_id) {

    $result = [];
    $id_prefix = 'mappings__' . $direction . '__table';
    $user_attr_input_id = $id_prefix . "[$row_id][user_attr]";

    if ($action == 'nonconfigurable') {
      $ldap_attr = [
        '#type' => 'item',
        '#default_value' => isset($mapping['ldap_attr']) ? $mapping['ldap_attr'] : '',
        '#markup' => isset($mapping['source']) ? $mapping['source'] : '?',
        '#attributes' => ['class' => ['source']],
      ];
    }
    else {
      $ldap_attr = [
        '#type' => 'textfield',
        '#title' => 'LDAP attribute',
        '#title_display' => 'invisible',
        '#default_value' => isset($mapping['ldap_attr']) ? $mapping['ldap_attr'] : '',
        '#size' => 20,
        '#maxlength' => 255,
        '#attributes' => ['class' => ['ldap-attr']],
      ];
      // Change the visibility rules for LdapConfiguration::PROVISION_TO_LDAP.
      if ($direction == LdapConfiguration::PROVISION_TO_LDAP) {
        $user_tokens = [
          '#type' => 'textfield',
          '#title' => 'User tokens',
          '#title_display' => 'invisible',
          '#default_value' => isset($mapping['user_tokens']) ? $mapping['user_tokens'] : '',
          '#size' => 20,
          '#maxlength' => 255,
          '#disabled' => ($action == 'nonconfigurable'),
          '#attributes' => ['class' => ['tokens']],
        ];

        $user_tokens['#states'] = [
          'visible' => [
            'select[name="' . $user_attr_input_id . '"]' => ['value' => 'user_tokens'],
          ],
        ];
      }
    }

    $convert = [
      '#type' => 'checkbox',
      '#title' => 'Convert from binary',
      '#title_display' => 'invisible',
      '#default_value' => isset($mapping['convert']) ? $mapping['convert'] : '',
      '#disabled' => ($action == 'nonconfigurable'),
      '#attributes' => ['class' => ['convert']],
    ];

    if ($action == 'nonconfigurable') {
      $user_attr = [
        '#type' => 'item',
        '#markup' => isset($mapping['name']) ? $mapping['name'] : '?',
      ];
    }
    else {
      $user_attr = [
        '#type' => 'select',
        '#title' => 'User attribute',
        '#title_display' => 'invisible',
        '#default_value' => isset($mapping['user_attr']) ? $mapping['user_attr'] : '',
        '#options' => $user_attr_options,
      ];
    }

    // Get the order of the columns correctly.
    if ($direction == LdapConfiguration::PROVISION_TO_LDAP) {
      $result['user_attr'] = $user_attr;
      $result['user_tokens'] = $user_tokens;
      $result['convert'] = $convert;
      $result['ldap_attr'] = $ldap_attr;
    }
    else {
      $result['ldap_attr'] = $ldap_attr;
      $result['convert'] = $convert;
      $result['user_attr'] = $user_attr;
    }

    $result['#storage']['sync_mapping_fields'][$direction] = [
      'action' => $action,
      'direction' => $direction,
    ];
    // FIXME: Add table selection / ordering back:
    // $col and $row used to be paremeters to $result[$prov_event]. ID possible
    // not need needed anymore. Row used to be a parameter to this function.
    // $col = ($direction == LdapConfiguration::PROVISION_TO_LDAP) ? 5 : 4;.
    if (($direction == LdapConfiguration::PROVISION_TO_DRUPAL)) {
      $syncEvents = LdapConfiguration::provisionsDrupalEvents();
    }
    else {
      $syncEvents = LdapConfiguration::provisionsLdapEvents();
    }

    foreach ($syncEvents as $prov_event => $prov_event_name) {
      // See above.
      // $col++;
      // $id = $id_prefix . join('__', array('sm', $prov_event, $row));.
      $result[$prov_event] = [
        '#type' => 'checkbox',
        '#title' => $prov_event,
        '#title_display' => 'invisible',
        '#default_value' => isset($mapping['prov_events']) ? (int) (in_array($prov_event, $mapping['prov_events'])) : '',
        '#disabled' => (!$this->provisionEventConfigurable($prov_event, $mapping) || ($action == 'nonconfigurable')),
        '#attributes' => ['class' => ['sync-method']],
      ];
    }

    // This one causes the extra column.
    $result['configurable_to_drupal'] = [
      '#type' => 'hidden',
      '#default_value' => ($action != 'nonconfigurable' ? 1 : 0),
      '#class' => '',
    ];

    return $result;
  }

  /**
   * Is a mapping configurable by a given module?
   *
   * @param array $mapping
   *   as mapping configuration for field, attribute, property, etc.
   * @param string $module
   *   machine name such as ldap_user.
   *
   * @return bool
   */
  private function isMappingConfigurable($mapping = NULL, $module = 'ldap_user') {
    $configurable = (
      (
        (!isset($mapping['configurable_to_drupal']) && !isset($mapping['configurable_to_ldap'])) ||
        (isset($mapping['configurable_to_drupal']) && $mapping['configurable_to_drupal']) ||
        (isset($mapping['configurable_to_ldap']) && $mapping['configurable_to_ldap'])
      )
      &&
      (
        !isset($mapping['config_module']) ||
        (isset($mapping['config_module']) && $mapping['config_module'] == $module)
      )
    );
    return $configurable;
  }

  /**
   * Is a particular sync method viable for a given mapping?
   * That is, Can it be enabled in the UI by admins?
   *
   * @param int $prov_event
   * @param array $mapping
   *   is array of mapping configuration.
   *
   * @return bool
   */
  private function provisionEventConfigurable($prov_event, $mapping = NULL) {

    $configurable = FALSE;

    if ($mapping) {
      if ($prov_event == LdapConfiguration::$eventCreateLdapEntry || $prov_event == LdapConfiguration::$eventSyncToLdapEntry) {
        $configurable = (boolean) (!isset($mapping['configurable_to_ldap']) || $mapping['configurable_to_ldap']);
      }
      elseif ($prov_event == LdapConfiguration::$eventCreateDrupalUser || $prov_event == LdapConfiguration::$eventSyncToDrupalUser) {
        $configurable = (boolean) (!isset($mapping['configurable_to_drupal']) || $mapping['configurable_to_drupal']);
      }
    }
    else {
      $configurable = TRUE;
    }

    return $configurable;
  }

  /**
   * Returns a config compatible machine name.
   */
  private function sanitise_machine_name($string) {
    // Replace dots
    // Replace square brackets.
    return str_replace(['.', '[', ']'], ['-', '', ''], $string);
  }


  /**
   * Extract sync mappings array from mapping table in admin form.
   *
   * @param array $values
   *   as $form_state['values'] from drupal form api.
   * @param $direction
   *
   * @return array Returns the relevant mappings.
   * Returns the relevant mappings.
   */
  private function syncMappingsFromForm($values, $direction) {
    $mappings = [];
    foreach ($values as $field_name => $value) {

      $parts = explode('__', $field_name);
      if ($parts[0] != 'mappings' || !isset($parts[1]) || $parts[1] != $direction) {
        continue;
      }

      // These are our rows.
      foreach ($value as $row_descriptor => $columns) {
        if ($row_descriptor == 'second-header') {
          continue;
        }

        $key = ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) ? $this->sanitise_machine_name($columns['user_attr']) : $this->sanitise_machine_name($columns['ldap_attr']);
        // Only save if its configurable and has an ldap and drupal attributes. The others are optional.
        if ($columns['configurable_to_drupal'] && $columns['ldap_attr'] && $columns['user_attr']) {
          $mappings[$key] = [
            'ldap_attr'   => $columns['ldap_attr'],
            'user_attr'   => $columns['user_attr'],
            'convert'     => $columns['convert'],
            'direction'   => $direction,
            'user_tokens' => isset($columns['user_tokens']) ? $columns['user_tokens'] : '',
            'config_module' => 'ldap_user',
            'prov_module' => 'ldap_user',
            'enabled'     => 1,
          ];

          $syncEvents = ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) ? LdapConfiguration::provisionsDrupalEvents() : LdapConfiguration::provisionsLdapEvents();
          foreach ($syncEvents as $prov_event => $discard) {
            if (isset($columns[$prov_event]) && $columns[$prov_event]) {
              $mappings[$key]['prov_events'][] = $prov_event;
            }
          }
        }
      }
    }
    return $mappings;
  }

}
