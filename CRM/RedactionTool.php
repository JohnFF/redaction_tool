<?php

class CRM_RedactionTool {

  /**
   *
   * @param int $contactId
   */
  public static function redact($contactId) {

    $activityTypesToRedact = array('Phone call', 'SMS Delivery', 'Outbound SMS',
      'Mass SMS');

    $activityTypesToDelete = array('Guest Referral');

    self::redactPersonalDetails($contactId);
    self::redactActivities($contactId, $activityTypesToRedact, $activityTypesToDelete);
    self::redactPhoneNumbers($contactId);
    self::redactAddresses($contactId);
    
    civicrm_api3('Activity', 'create', array('target_id' => $contactId, 'activity_type_id' => 'Redacted Data'));
  }

  /**
   *
   * @param int $contactId
   */
  public static function redactPersonalDetails($contactId) {
    $contactTypes = civicrm_api3('Contact', 'getvalue', array(
      'return' => "contact_sub_type",
      'id' => $contactId,
    ));

    unset($contactTypes['is_error']);

    $firstNameString = implode(" & ", $contactTypes) . " Id " . $contactId;

    civicrm_api3('Contact', 'create', array(
      'id' => $contactId,
      'first_name' => $firstNameString,
      'last_name' => '[REDACTED]',
      'birth_date' => '',
    ));
  }

  /**
   *
   * @param int $contactId
   */
  public static function redactAddresses($contactId) {
    $addresses = civicrm_api3('Address', 'get', array('contact_id' => $contactId));

    foreach ($addresses['values'] as $eachAddress) {
      civicrm_api3('Address', 'delete', array('id' => $eachAddress['id']));
    }
  }

  /**
   *
   * @param int $contactId
   */
  public static function redactPhoneNumbers($contactId) {
    $phoneNumbers = civicrm_api3('Phone', 'get', array());

    foreach ($phoneNumbers['values'] as $eachPhoneNumber) {
      civicrm_api3('Phone', 'delete', array('id' => $eachPhoneNumber['id']));
    }
  }

  /**
   *
   * @param int $contactId
   * @param array of strings $activityTypesToRedact
   * @param array of strings $activityTypesToDelete
   */
  public static function redactActivities($contactId, $activityTypesToRedact, $activityTypesToDelete) {

    // Activity Types to Redact
    if (!empty($activityTypesToRedact)) {
      $activitiesToRedact = civicrm_api3('Activity', 'get', array(
        'target_contact_id' => $contactId,
        'activity_type_id' => array('IN' => $activityTypesToRedact),
        'options' => array('limit' => 0),
      ));

      foreach ($activitiesToRedact['values'] as $eachActivityToRedact) {        
        civicrm_api3('Activity', 'create', array(
          'id' => $eachActivityToRedact['id'],
          'subject' => '[REDACTED]',
          'details' => '[REDACTED]',
          'phone_id' => '',
          'phone_number' => '[REDACTED]',
        ));
      }
    }

    // Activity Types To Delete
    if (!empty($activityTypesToDelete)) {
      $activitiesToDelete = civicrm_api3('Activity', 'get', array(
        'target_contact_id' => $contactId,
        'activity_type_id' => array('IN' => $activityTypesToDelete),
        'options' => array('limit' => 0),
      ));
    
      foreach ($activitiesToDelete['values'] as $eachActivityToDelete) {
        civicrm_api3('Activity', 'delete', array('id' => $eachActivityToDelete['id']));
      }
    }

  }
}