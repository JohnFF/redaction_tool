<?php

use CRM_Redactiontool_ExtensionUtil as E;
use Civi\Test\EndToEndInterface;

/**
 * CRM_RedactionToolTest
 * @group e2e
 */
class CRM_RedactionToolTest extends \PHPUnit_Framework_TestCase implements EndToEndInterface {

  public static function setUpBeforeClass() {
    // See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md

    // Example: Install this extension. Don't care about anything else.
    \Civi\Test::e2e()->installMe(__DIR__)->apply();

  }

  public function setUp() {
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testRedactPersonalDetails() {
    $testContactId = $this->createTestContact();

    CRM_RedactionTool::redactPersonalDetails($testContactId);

    $contactDetails = civicrm_api3('contact', 'getsingle', array('id' => $testContactId));

    $this->assertEquals('', $contactDetails['birth_date']);
    $this->assertNotContains('first_name', 'Confidential');
    $this->assertNotContains('last_name', 'Confidential');
    $this->assertNotContains('sort_name', 'Confidential');
    $this->assertNotContains('display_name', 'Confidential');#
  }

  public function testRedactActivities() {
    $testContactId = $this->createTestContact();

    $activities = civicrm_api3('Activity', 'get', array('sequential' => 1, 'target_contact_id' => $testContactId));

    $this->assertEquals(4, $activities['count']);
    foreach($activities['values'] as $eachActivity) {
      $this->assertContains('Confidential', $eachActivity['subject']);
      $this->assertContains('Confidential', $eachActivity['details']);
      $this->assertContains('07786944013', $eachActivity['phone_number']);
    }

    CRM_RedactionTool::redactActivities($testContactId, array('Phone call', 'SMS Delivery', 'Mass SMS'), array());

    $redactedActivities = civicrm_api3('Activity', 'get', array('sequential' => 1, 'target_contact_id' => $testContactId));

    $this->assertEquals(4, $redactedActivities['count']);

    foreach($redactedActivities['values'] as $eachRedactedActivity) {
      if (in_array($eachRedactedActivity['activity_type_id'], array(2, 44, 34))) {
        $this->assertEquals('[REDACTED]', $eachRedactedActivity['subject'], 'Subject redaction failed.');
        $this->assertEquals('[REDACTED]', $eachRedactedActivity['details'], 'Details redaction failed.');
        $this->assertEquals('[REDACTED]', $eachRedactedActivity['phone_number'], 'Phone number redaction failed.');
      }
    }
  }

  public function testDeleteActivities() {
    $testContactId = $this->createTestContact();
    $deletedActivityCount = $this->activityGetCountWrapper($testContactId, 'Guest Referral');

    $this->assertEquals(1, $deletedActivityCount);

    CRM_RedactionTool::redactActivities($testContactId, array(), array('Guest Referral'));

    $deletedActivityCount = $this->activityGetCountWrapper($testContactId, 'Guest Referral');

    $this->assertEquals(0, $deletedActivityCount);
  }

  public function testDeleteAddresses () {

  }

  private function activityGetCountWrapper($contactId, $activityTypeId) {
    return civicrm_api3('Activity', 'getcount', array(
      'target_contact_id' => $contactId,
      'activity_type_id' => $activityTypeId,
    ));
  }

  private function createTestContact() {
    // Names and birth date.
    $testContact = civicrm_api3('Contact', 'create', array(
      'first_name' => 'Confidential first name',
      'last_name' => 'Confidential last name',
      'birth_date' => '1985-07-10',
      'contact_type' => 'individual',
    ));

    // Phone numbers.
    civicrm_api3('Phone', 'create', array(
      'contact_id' => $testContact['id'],
      'phone' => '07786944013'
    ));

    // Address.
    civicrm_api3('Address', 'create', array(
      'contact_id' => $testContact['id'],
      'location_type_id' => 'Main',
      'street_address' => '1 Queen Elizabeth St',
      'city' => 'London',
      'postal_code' => 'SE1 2LP',
      'geo_code_1' => 90.0,
      'geo_code_2' => 90.0,
      'country_id' => 'GB',
    ));

    // Create some test activities.
    $testActivityTypesToRedact = array('Phone call', 'SMS Delivery', 'Mass SMS');

    $testActivityTypesToDelete = array('Guest Referral');

    $allTestActivityTypes = array_merge($testActivityTypesToRedact, $testActivityTypesToDelete);

    foreach($allTestActivityTypes as $eachTestActivityType) {
      civicrm_api3('Activity', 'create', array(
        'subject' => 'Confidential subject data',
        'details' => 'Confidential details',
        'phone_number' => '07786944013',
        'source_contact_id' => 1,
        'target_contact_id' => $testContact['id'],
        'activity_type_id' => $eachTestActivityType,
      ));
    }

    return $testContact['id'];
  }
}
