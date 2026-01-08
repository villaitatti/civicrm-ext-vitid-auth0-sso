<?php

use CRM_VitidAuth0_ExtensionUtil as E;

/**
 * Page for displaying VIT ID authentication errors.
 */
class CRM_VitidAuth0_Page_Error extends CRM_Core_Page {

  public function run() {
    // Get error message from session
    $session = CRM_Core_Session::singleton();
    $errorMessage = $session->get('vitid_auth0_error');
    
    // Clear the error from session
    $session->set('vitid_auth0_error', NULL);

    // Set default error if none provided
    if (empty($errorMessage)) {
      $errorMessage = E::ts('An error occurred during VIT ID authentication. Please try again.');
    }

    // Display error on login page instead of using template
    CRM_Core_Session::setStatus($errorMessage, E::ts('VIT ID Authentication Error'), 'error');
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/login', 'reset=1', TRUE, NULL, FALSE));
  }
}
