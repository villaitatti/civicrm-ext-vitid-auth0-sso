<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2024
 *
 * Generated from vitid_auth0_role_mapping
 * DO NOT modify this file. This will be overwritten by CiviCRM's API code.
 */

class CRM_VitidAuth0_DAO_RoleMapping extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name
   *
   * @var string
   */
  public static $_tableName = 'civicrm_vitid_role_mapping';

  /**
   * Should & how should this entity be cached.
   *
   * @var string
   */
  public static $_log = TRUE;

  /**
   * Mapping ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Auth0 Role Name
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $auth0_role_name;

  /**
   * CiviCRM Role ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $civicrm_role_id;

  /**
   * Is Active
   *
   * @var bool|string|null
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * Created At
   *
   * @var string|null
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_at;

  /**
   * Updated At
   *
   * @var string|null
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $updated_at;

  /**
   * Class constructor.
   */
  public function __construct() {
    parent::__construct();
  }

}
