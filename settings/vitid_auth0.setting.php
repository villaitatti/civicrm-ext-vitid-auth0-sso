<?php

use CRM_VitidAuth0_ExtensionUtil as E;

return [
  'vitid_auth0_domain' => [
    'name' => 'vitid_auth0_domain',
    'type' => 'String',
    'html_type' => 'text',
    'default' => '',
    'add' => '5.0',
    'title' => E::ts('Auth0 Domain'),
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('Your Auth0 domain (e.g., your-tenant.auth0.com)'),
    'help_text' => E::ts('Enter your Auth0 tenant domain without https://'),
  ],
  'vitid_auth0_client_id' => [
    'name' => 'vitid_auth0_client_id',
    'type' => 'String',
    'html_type' => 'text',
    'default' => '',
    'add' => '5.0',
    'title' => E::ts('Auth0 Client ID'),
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('Your Auth0 application client ID'),
    'help_text' => E::ts('Found in your Auth0 application settings'),
  ],
  'vitid_auth0_client_secret' => [
    'name' => 'vitid_auth0_client_secret',
    'type' => 'String',
    'html_type' => 'text',
    'default' => '',
    'add' => '5.0',
    'title' => E::ts('Auth0 Client Secret'),
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('Your Auth0 application client secret'),
    'help_text' => E::ts('Found in your Auth0 application settings. Keep this secure!'),
  ],
  'vitid_auth0_debug' => [
    'name' => 'vitid_auth0_debug',
    'type' => 'Boolean',
    'html_type' => 'checkbox',
    'default' => FALSE,
    'add' => '5.0',
    'title' => E::ts('Enable Debug Logging'),
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('Enable detailed debug logging for VIT ID authentication (for troubleshooting only)'),
    'help_text' => E::ts('When enabled, detailed debug information will be logged. Disable in production for performance.'),
  ],
];
