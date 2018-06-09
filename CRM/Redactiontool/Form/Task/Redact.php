<?php

use CRM_Redactiontool_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Redactiontool_Form_Task_Redact extends CRM_Contact_Form_Task {
  public function buildQuickForm() {

    $displayNames = array();

    foreach($this->_contactIds as $eachContactId) {
      $displayNames[] = civicrm_api3('Contact', 'getvalue', array('id' => $eachContactId, 'return' => 'display_name'));
    }

    // Display "for the following characters".
    $displayNameString = implode(', ', $displayNames);

    $this->addCheckBox('redaction_types', ts('Redaction Types'), $this->getRedactionTypes());
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess() {

    foreach($this->_contactIds as $eachContactId) {
      civicrm_api3('Contact', 'Redact', array('contact_id' => $eachContactId));
    }

    $values = $this->exportValues();

    $options = $this->getRedactionTypes();
    CRM_Core_Session::setStatus(E::ts('You picked color "%1"', array(
      1 => $options[$values['favorite_color']],
    )));
    parent::postProcess();
  }

  public function getRedactionTypes() {
    return array_flip(array('redact_name' => 'Permanently redact name', 'redact_dob' => 'Permanently redact date of birth', 'redact_activities' => 'Permanently redact activities'));
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
