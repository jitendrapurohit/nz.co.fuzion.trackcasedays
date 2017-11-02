<?php

require_once 'trackcasedays.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function trackcasedays_civicrm_config(&$config) {
  _trackcasedays_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function trackcasedays_civicrm_xmlMenu(&$files) {
  _trackcasedays_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function trackcasedays_civicrm_install() {
  //Create custom group for Case.
  civicrm_api3('CustomGroup', 'create', array(
    'title' => "Track Case Days",
    'extends' => "Case",
  ));
  //Custom field to keep track of the days open.
  civicrm_api3('CustomField', 'create', array(
    'sequential' => 1,
    'custom_group_id' => "Track_Case_Days",
    'label' => "Inactive Days",
    'data_type' => "String",
    'html_type' => "Text",
    'is_view' => 1,
    'weight' => 1,
  ));
  //Custom field to keep track of the days open.
  civicrm_api3('CustomField', 'create', array(
    'sequential' => 1,
    'custom_group_id' => "Track_Case_Days",
    'label' => "Days Open",
    'data_type' => "String",
    'html_type' => "Text",
    'is_view' => 1,
    'weight' => 2,
  ));
  //Custom field to keep track of the days open.
  civicrm_api3('CustomField', 'create', array(
    'sequential' => 1,
    'custom_group_id' => "Track_Case_Days",
    'label' => "Reopened Days",
    'data_type' => "String",
    'html_type' => "Text",
    'is_view' => 1,
    'weight' => 2,
  ));

  //Modify length of case subject to 380.
  CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_case MODIFY subject VARCHAR(380)");

  _trackcasedays_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function trackcasedays_civicrm_postInstall() {
  _trackcasedays_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function trackcasedays_civicrm_uninstall() {
  $caseGid = civicrm_api3('CustomGroup', 'getsingle', array(
    'return' => array("id"),
    'name' => "Track_Case_Days",
  ));
  $caseFid = civicrm_api3('CustomField', 'getsingle', array(
    'return' => array("id"),
    'custom_group_id' => "Track_Case_Days",
    'name' => "Days_Open",
  ));
  $caseFid2 = civicrm_api3('CustomField', 'getsingle', array(
    'return' => array("id"),
    'custom_group_id' => "Track_Case_Days",
    'name' => "Inactive_Days",
  ));
  civicrm_api3('CustomField', 'delete', array(
    'id' => $caseFid['id'],
  ));
  civicrm_api3('CustomField', 'delete', array(
    'id' => $caseFid2['id'],
  ));
  civicrm_api3('CustomGroup', 'delete', array(
    'id' => $caseGid['id'],
  ));
  _trackcasedays_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function trackcasedays_civicrm_enable() {
  _trackcasedays_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function trackcasedays_civicrm_disable() {
  _trackcasedays_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function trackcasedays_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _trackcasedays_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function trackcasedays_civicrm_managed(&$entities) {
  _trackcasedays_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function trackcasedays_civicrm_caseTypes(&$caseTypes) {
  _trackcasedays_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function trackcasedays_civicrm_angularModules(&$angularModules) {
  _trackcasedays_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function trackcasedays_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _trackcasedays_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function trackcasedays_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function trackcasedays_civicrm_navigationMenu(&$menu) {
  _trackcasedays_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'nz.co.fuzion.trackcasedays')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _trackcasedays_civix_navigationMenu($menu);
} // */
