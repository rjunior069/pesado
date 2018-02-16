<?php

namespace Drupal\ldap_user\Helper;

/**
 *
 */
class LdapConfiguration {

  const PROVISION_TO_DRUPAL = 'drupal';
  const PROVISION_TO_LDAP = 'ldap';
  const PROVISION_TO_NONE = 'none';
  const PROVISION_TO_ALL = 'all';

  const PROVISION_DRUPAL_USER_ON_USER_UPDATE_CREATE = 'drupal_on_update_create';
  const PROVISION_DRUPAL_USER_ON_USER_AUTHENTICATION = 'drupal_on_login';
  const PROVISION_DRUPAL_USER_ON_USER_ON_MANUAL_CREATION = 'drupal_on_manual_creation';
  const PROVISION_LDAP_ENTRY_ON_USER_ON_USER_UPDATE_CREATE = 'ldap_on_update_create';
  const PROVISION_LDAP_ENTRY_ON_USER_ON_USER_AUTHENTICATION = 'ldap_on_login';
  const PROVISION_LDAP_ENTRY_ON_USER_ON_USER_DELETE = 'ldap_on_delete';

  /**
   * Provisioning events (events are triggered by triggers).
   *
   * @TODO Convert to string and save in configuration.
   * @TODO Write update hook.
   */
  public static $eventCreateDrupalUser = 1;
  public static $eventSyncToDrupalUser = 2;
  public static $eventCreateLdapEntry = 3;
  public static $eventSyncToLdapEntry = 4;
  public static $eventLdapAssociateDrupalAccount = 5;

  /**
   * Options for account creation behavior.
   *
   * @TODO Convert to string and save in configuration.
   * @TODO Write update hook.
   */
  public static $accountCreationLdapBehaviour = 4;
  public static $accountCreationUserSettingsForLdap = 1;
  public static $userConflictLog = 1;
  public static $userConflictAttemptResolve = 2;
  public static $manualAccountConflictReject = 1;
  public static $manualAccountConflictLdapAssociate = 2;
  public static $manualAccountConflictShowOptionOnForm = 3;
  public static $manualAccountConflictNoLdapAssociate = 4;

  /**
   *
   */
  public static function getAllEvents() {
    return [
      self::$eventSyncToLdapEntry,
      self::$eventCreateDrupalUser,
      self::$eventSyncToLdapEntry,
      self::$eventCreateLdapEntry,
      self::$eventLdapAssociateDrupalAccount,
    ];
  }

  /**
   *
   */
  public static function createLDAPAccounts() {
    if (\Drupal::config('ldap_user.settings')->get('acctCreation') == self::$accountCreationLdapBehaviour ||
      \Drupal::config('user.settings')->get('register_no_approval_required') == USER_REGISTER_VISITORS) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public static function createLDAPAccountsAdminApproval() {
    if (\Drupal::config('user.settings')->get('register_no_approval_required') == USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public static function provisionsLdapEvents() {
    return [
      self::$eventCreateLdapEntry => t('On LDAP Entry Creation'),
      self::$eventSyncToLdapEntry => t('On Sync to LDAP Entry'),
    ];
  }

  /**
   *
   */
  public static function provisionsDrupalEvents() {
    return [
      self::$eventCreateDrupalUser => t('On Drupal User Creation'),
      self::$eventSyncToDrupalUser => t('On Sync to Drupal User'),
    ];
  }

  /**
   *
   */
  public static function provisionsDrupalAccountsFromLdap() {
    if (\Drupal::config('ldap_user.settings')->get('drupalAcctProvisionServer') &&
      count(array_filter(array_values(\Drupal::config('ldap_user.settings')->get('drupalAcctProvisionTriggers')))) > 0) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public static function provisionsLdapEntriesFromDrupalUsers() {
    if (\Drupal::config('ldap_user.settings')->get('ldapEntryProvisionServer') &&
      count(array_filter(array_values(\Drupal::config('ldap_user.settings')->get('ldapEntryProvisionTriggers')))) > 0) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Converts the more general ldap_context string to its associated ldap user event.
   *
   * @param string|null $ldapContext
   *
   * @return array
   */
  public static function ldapContextToProvEvents($ldapContext = NULL) {

    switch ($ldapContext) {
      case 'ldap_user_prov_to_drupal':
        $result = [
          self::$eventSyncToDrupalUser,
          self::$eventCreateDrupalUser,
          self::$eventLdapAssociateDrupalAccount,
        ];
        break;

      case 'ldap_user_prov_to_ldap':
        $result = [
          self::$eventSyncToLdapEntry,
          self::$eventCreateLdapEntry,
        ];
        break;

      default:
        $result = LdapConfiguration::getAllEvents();
        break;
    }
    return $result;
  }

  /**
   * Converts the more general ldap_context string to its associated ldap user prov direction.
   *
   * @param string|null $ldapContext
   *
   * @return int
   */
  public static function ldapContextToProvDirection($ldapContext = NULL) {

    switch ($ldapContext) {
      case 'ldap_user_prov_to_drupal':
        $result = LdapConfiguration::PROVISION_TO_DRUPAL;
        break;

      case 'ldap_user_prov_to_ldap':
      case 'ldap_user_delete_drupal_user':
        $result = LdapConfiguration::PROVISION_TO_LDAP;
        break;

      // Provisioning is can happen in both directions in most contexts.
      case 'ldap_user_insert_drupal_user':
      case 'ldap_user_update_drupal_user':
      case 'ldap_authentication_authenticate':
      case 'ldap_user_disable_drupal_user':
        $result = LdapConfiguration::PROVISION_TO_ALL;
        break;

      default:
        $result = LdapConfiguration::PROVISION_TO_ALL;
        break;
    }
    return $result;
  }

  /**
   * Given a $prov_event determine if ldap user configuration supports it.
   *   this is overall, not per field syncing configuration.
   *
   * @param int $direction
   *   LdapConfiguration::PROVISION_TO_DRUPAL or LdapConfiguration::PROVISION_TO_LDAP.
   *
   * @param int $provision_trigger
   *   see events above.
   *   or
   *   'sync', 'provision', 'delete_ldap_entry', 'delete_drupal_entry', 'cancel_drupal_entry'.
   *
   * @deprecated
   *
   * @return bool
   */
  public static function provisionEnabled($direction, $provision_trigger) {
    $result = FALSE;

    if ($direction == LdapConfiguration::PROVISION_TO_LDAP) {
      $result = self::provisionAvailableToLDAP($provision_trigger);

    }
    elseif ($direction == LdapConfiguration::PROVISION_TO_DRUPAL) {
      $result = self::provisionAvailableToDrupal($provision_trigger);
    }

    return $result;
  }

  /**
   *
   */
  public static function provisionAvailableToLDAP($trigger) {
    if (\Drupal::config('ldap_user.settings')->get('ldapEntryProvisionTriggers')) {
      return in_array($trigger, \Drupal::config('ldap_user.settings')->get('ldapEntryProvisionTriggers'));
    }
    else {
      return FALSE;
    }
  }

  /**
   *
   */
  public static function provisionAvailableToDrupal($trigger) {
    if (\Drupal::config('ldap_user.settings')->get('drupalAcctProvisionTriggers')) {
      return in_array($trigger, \Drupal::config('ldap_user.settings')->get('drupalAcctProvisionTriggers'));
    }
    else {
      return FALSE;
    }
  }

}
