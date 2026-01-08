<?php

use Auth0\SDK\Contract\StoreInterface;

/**
 * Database storage adapter for Auth0 SDK.
 * Stores state/nonce/pkce in civicrm_vitid_auth0_state table.
 */
class CRM_VitidAuth0_Utils_DatabaseStore implements StoreInterface {

  /**
   * @var bool
   */
  private $deferring = false;

  /**
   * @var array
   */
  private $deferred = [];

  /**
   * Defer saving state changes to destination to improve performance during blocks of changes.
   *
   * @param bool $deferring
   */
  public function defer(bool $deferring): void {
    $this->deferring = $deferring;

    if (!$deferring && !empty($this->deferred)) {
      foreach ($this->deferred as $key => $value) {
        $this->persist($key, $value);
      }
      $this->deferred = [];
    }
  }

  /**
   * Remove a value from the store.
   *
   * @param string $key key to delete
   */
  public function delete(string $key): void {
    if ($this->deferring) {
      unset($this->deferred[$key]);
      return;
    }

    try {
      CRM_Core_DAO::executeQuery(
        "DELETE FROM civicrm_vitid_auth0_state WHERE `key` = %1",
        [1 => [$key, 'String']]
      );
    } catch (Exception $e) {
      Civi::log()->error('VIT ID DatabaseStore: Error deleting key ' . $key . ': ' . $e->getMessage());
    }
  }

  /**
   * Get a value from the store by a given key.
   *
   * @param string $key     key to get
   * @param mixed  $default return value if key not found
   *
   * @return mixed
   */
  public function get(string $key, $default = null) {
    if ($this->deferring && isset($this->deferred[$key])) {
      return $this->deferred[$key];
    }

    try {
      // Clean up expired entries opportunistically
      // This is a bit of a hack to avoid a separate cron job, but effective enough
      // Only run it occasionally (e.g. 1% of reads) to avoid performance hit
      if (rand(1, 100) === 1) {
        $this->cleanup();
      }

      $result = CRM_Core_DAO::executeQuery(
        "SELECT `value` FROM civicrm_vitid_auth0_state WHERE `key` = %1",
        [1 => [$key, 'String']]
      );

      if ($result->fetch()) {
        return unserialize($result->value);
      }
    } catch (Exception $e) {
      Civi::log()->error('VIT ID DatabaseStore: Error getting key ' . $key . ': ' . $e->getMessage());
    }

    return $default;
  }

  /**
   * Remove all stored values.
   */
  public function purge(): void {
    $this->deferred = [];
    try {
      CRM_Core_DAO::executeQuery("TRUNCATE TABLE civicrm_vitid_auth0_state");
    } catch (Exception $e) {
      Civi::log()->error('VIT ID DatabaseStore: Error purging table: ' . $e->getMessage());
    }
  }

  /**
   * Set a value on the store.
   *
   * @param string $key   key to set
   * @param mixed  $value value to set
   */
  public function set(string $key, $value): void {
    if ($this->deferring) {
      $this->deferred[$key] = $value;
      return;
    }

    $this->persist($key, $value);
  }

  /**
   * Persist a value to the database.
   *
   * @param string $key
   * @param mixed $value
   */
  private function persist(string $key, $value): void {
    try {
      $serialized = serialize($value);
      // Default expiry: 1 hour from now (Auth0 states are transient)
      $expire = time() + 3600;

      CRM_Core_DAO::executeQuery(
        "INSERT INTO civicrm_vitid_auth0_state (`key`, `value`, `expire`) VALUES (%1, %2, %3)
         ON DUPLICATE KEY UPDATE `value` = %2, `expire` = %3",
        [
          1 => [$key, 'String'],
          2 => [$serialized, 'String'],
          3 => [$expire, 'Integer'],
        ]
      );
    } catch (Exception $e) {
      Civi::log()->error('VIT ID DatabaseStore: Error setting key ' . $key . ': ' . $e->getMessage());
    }
  }

  /**
   * Clean up expired entries.
   */
  private function cleanup(): void {
    try {
      CRM_Core_DAO::executeQuery(
        "DELETE FROM civicrm_vitid_auth0_state WHERE `expire` < %1",
        [1 => [time(), 'Integer']]
      );
    } catch (Exception $e) {
      // Ignore cleanup errors
    }
  }
}
