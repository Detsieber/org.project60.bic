<?php

require_once 'bic.civix.php';

use \Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Implements hook_civicrm_container()
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_container/
 */
function bic_civicrm_container(ContainerBuilder $container) {
    if (class_exists('\Civi\Bic\ContainerSpecs')) {
        $container->addCompilerPass(new \Civi\Bic\ContainerSpecs());
    }
}

/**
 * Implementation of hook_civicrm_config
 */
function bic_civicrm_config(&$config) {
  _bic_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function bic_civicrm_xmlMenu(&$files) {
  _bic_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function bic_civicrm_install() {
  return _bic_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function bic_civicrm_uninstall() {
  return _bic_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function bic_civicrm_enable() {
  return _bic_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function bic_civicrm_disable() {
  return _bic_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function bic_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _bic_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function bic_civicrm_managed(&$entities) {
  return _bic_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 */
function bic_civicrm_caseTypes(&$caseTypes) {
  _bic_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Set permissions for runner/engine API call
 */
function bic_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  // TODO: adjust to correct permission
  $permissions['bic']['getfromiban'] = array('access CiviCRM');
  $permissions['bic']['findbyiban']  = array('access AJAX API');
  $permissions['bic']['get']         = array('access CiviCRM');
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * Inject the 'civicrm/bicList' item unter the 'Search' top menu, unless it's already in there...
 *
 */
function bic_civicrm_navigationMenu(&$menu) {
  _bic_civix_insert_navigation_menu($menu, 'Search', array(
    'label' => ts('Find Banks', array('domain' => 'org.project60.bic')),
    'name' => 'BankLists',
    'url' => 'civicrm/bicList',
    'permission' => 'access CiviContribute',
    'operator' => NULL,
    'separator' => 2,
    'active' => 1,
  ));

  _bic_civix_navigationMenu($menu);
}
