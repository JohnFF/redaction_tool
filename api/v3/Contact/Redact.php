<?php
use CRM_Redactiontool_ExtensionUtil as E;

/**
 * Contact.Redact API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_contact_Redact_spec(&$spec) {
  $spec['id']['api.required'] = 1;
}

/**
 * Contact.Redact API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_contact_Redact($params) {

}
