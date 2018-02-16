<?php

namespace Drupal\ldap_authentication\Helper;

/**
 *
 */
class LdapAuthenticationConfiguration {

  const MODE_MIXED = 1;
  const MODE_EXCLUSIVE = 2;

  public static $emailUpdateOnLdapChangeEnableNotify = 1;
  public static $emailUpdateOnLdapChangeEnable = 2;
  public static $emailUpdateOnLdapChangeDisable = 3;
  /**
   * Remove default later if possible, see also $emailUpdate.
   */
  public static $emailUpdateOnLdapChangeDefault = 1;

  public static $passwordFieldShowDisabled = 2;
  public static $passwordFieldHide = 3;
  public static $passwordFieldAllow = 4;
  /**
   * Remove default later if possible, see also $passwordOption.
   */
  public static $passwordFieldDefault = 2;

  public static $emailFieldRemove = 2;
  public static $emailFieldDisable = 3;
  public static $emailFieldAllow = 4;
  /**
   * Remove default later if possible, see also $emailOption.
   */
  public static $emailFieldDefault = 3;

  /**
   *
   */
  public static function hasEnabledAuthenticationServers() {
    return (count(self::getEnabledAuthenticationServers()) > 0) ? TRUE : FALSE;
  }

  /**
   *
   */
  public static function getEnabledAuthenticationServers() {
    $servers = \Drupal::config('ldap_authentication.settings')->get('sids');
    /** @var \Drupal\ldap_servers\ServerFactory $factory */
    $factory = \Drupal::service('ldap.servers');
    $result = [];
    foreach ($servers as $server) {
      if ($factory->getServerByIdEnabled($server)) {
        $result[] = $server;
      }
    }
    return $result;
  }

  /**
   *
   */
  public static function arrayToLines($array) {
    $lines = "";
    if (is_array($array)) {
      $lines = join("\n", $array);
    }
    elseif (is_array(@unserialize($array))) {
      $lines = join("\n", unserialize($array));
    }
    return $lines;
  }

  /**
   *
   */
  public static function linesToArray($lines) {
    $lines = trim($lines);

    if ($lines) {
      $array = preg_split('/[\n\r]+/', $lines);
      foreach ($array as $i => $value) {
        $array[$i] = trim($value);
      }
    }
    else {
      $array = [];
    }
    return $array;
  }

  /**
   * @param \Drupal\user\Entity\User $user
   * @return bool
   */
  public static function showPasswordField($user = NULL) {

    if (!$user) {
      $user = \Drupal::currentUser();
    }

    if ($user->id() == 1) {
      return TRUE;
    }

    /**
     * Hide if LDAP authenticated and updating password is not allowed, otherwise
     * show.
     */
    if (ldap_authentication_ldap_authenticated($user)) {
      if (\Drupal::config('ldap_authentication.settings')->get('passwordOption') == LdapAuthenticationConfiguration::$passwordFieldAllow) {
        return TRUE;
      }
      return FALSE;
    }
    return TRUE;

  }

}
