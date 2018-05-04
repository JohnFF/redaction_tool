<?php

use CRM_Redactiontool_ExtensionUtil as E;
use Civi\Test\EndToEndInterface;

/**
 * Contact.Redact API Test Case
 * This is a generic test class implemented with PHPUnit.
 * @group e2e
 */
class api_v3_Contact_RedactTest extends \PHPUnit_Framework_TestCase implements EndToEndInterface {

  /**
   * Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
   * See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
   */
  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  /**
   * The setup() method is executed before the test is executed (optional).
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * The tearDown() method is executed after the test was executed (optional)
   * This can be used for cleanup.
   */
  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Simple example test case.
   *
   * Note how the function name begins with the word "test".
   */
  public function testApiExample() {
    // TODO: Create test contact results in source_contact_id not being a valid integer.
    // $contactId = CRM_RedactionToolTest::createTestContact();
    // civicrm_api3('Contact', 'Redact', array('contact_id' => $contactId));
  }

}
