<?php

require_once 'redactiontool.civix.php';
use CRM_Redactiontool_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function redactiontool_civicrm_config(&$config) {
  _redactiontool_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function redactiontool_civicrm_xmlMenu(&$files) {
  _redactiontool_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function redactiontool_civicrm_install() {
  civicrm_api3('OptionValue', 'get', array(
    'option_group_id' => 'activity_type',
    'value' => 'redacted_data',
    'label' => 'Redacted Data',
    'is_active' => 1,
  ));

  _redactiontool_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function redactiontool_civicrm_postInstall() {
  _redactiontool_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function redactiontool_civicrm_uninstall() {
  _redactiontool_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function redactiontool_civicrm_enable() {
  _redactiontool_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function redactiontool_civicrm_disable() {
  _redactiontool_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function redactiontool_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _redactiontool_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function redactiontool_civicrm_managed(&$entities) {
  _redactiontool_civix_civicrm_managed($entities);
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
function redactiontool_civicrm_caseTypes(&$caseTypes) {
  _redactiontool_civix_civicrm_caseTypes($caseTypes);
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
function redactiontool_civicrm_angularModules(&$angularModules) {
  _redactiontool_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function redactiontool_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _redactiontool_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function redactiontool_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function redactiontool_civicrm_navigationMenu(&$menu) {
  _redactiontool_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _redactiontool_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_civicrm_searchTasks().
 */
function redactiontool_civicrm_searchTasks($objectType, &$tasks) {
  if ($objectType == 'contact') {
    $tasks[] = array(
      'title'  => ts('Redact All Data'),
      'class'  => 'CRM_Redactiontool_Form_Task_Redact',
      'url' => 'civicrm/redactiontool/redact',
    );
  }
}