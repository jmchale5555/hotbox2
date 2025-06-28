<?php

namespace Controller;

use Model\Settings;
use Core\Request;

defined('ROOTPATH') or exit('Access Denied');

class Config
{
    use MainController;

    public function index()
    {
        // Check if user is logged in and is admin
        if(!isset($_SESSION['USER']) || !$_SESSION['USER']->is_admin)
        {
            message("Access denied. Admin privileges required.");
            redirect('login');
        }

        $data = [];
        $settings = new \Model\Settings;
        
        // Get current LDAP settings
        $data['ldap_settings'] = $settings->getLdapSettings();
        
        $this->view('config', $data);
    }
    
    public function update()
    {
        // Check if user is logged in and is admin
        if(!isset($_SESSION['USER']) || !$_SESSION['USER']->is_admin)
        {
            echo json_encode(['success' => false, 'message' => 'Access denied. Admin privileges required.']);
            return;
        }
        
        // Get request data
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        if(!$data) {
            echo json_encode(['success' => false, 'errors' => ['Invalid request format']]);
            return;
        }
        
        $settings = new \Model\Settings;
        $response = ['success' => false];
        
        if($settings->updateLdapSettings($data))
        {
            $response = [
                'success' => true,
                'message' => 'Settings updated successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'errors' => $settings->errors
            ];
        }
        
        echo json_encode($response);
    }

    public function test()
    {
        // Check if user is logged in and is admin
        if(!isset($_SESSION['USER']) || !$_SESSION['USER']->is_admin)
        {
            echo json_encode(['success' => false, 'message' => 'Access denied. Admin privileges required.']);
            return;
        }

        $settings = new \Model\Settings;
        $ldapSettings = $settings->getLdapSettings();
        
        if(!$ldapSettings['ldap_enabled']) {
            echo json_encode(['success' => false, 'message' => 'LDAP is not enabled']);
            return;
        }

        try {
            // Build LDAP connection array
            $ldapCreds = [
                'hosts' => [$ldapSettings['ldap_host']],
                'base_dn' => $ldapSettings['ldap_base_dn'],
                'port' => $ldapSettings['ldap_port'],
                'protocol' => $ldapSettings['ldap_protocol'],
                'use_ssl' => $ldapSettings['ldap_use_ssl'],
                'use_tls' => $ldapSettings['ldap_use_tls'],
                'use_sasl' => false,
                'version' => $ldapSettings['ldap_version'],
                'timeout' => $ldapSettings['ldap_timeout'],
                'follow_referrals' => false,
                'options' => [
                    LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_HARD
                ],
                'sasl_options' => [
                    'mech' => null,
                    'realm' => null,
                    'authc_id' => null,
                    'authz_id' => null,
                    'props' => null,
                ],
            ];

            // Test LDAP connection
            $connection = new \LdapRecord\Connection($ldapCreds);
            \LdapRecord\Container::addConnection($connection);
            $connection->connect();

            echo json_encode([
                'success' => true,
                'message' => 'LDAP connection test successful'
            ]);

        } catch (\LdapRecord\Auth\BindException $e) {
            $error = $e->getDetailedError();
            echo json_encode([
                'success' => false,
                'message' => 'LDAP connection failed: ' . $error->getErrorMessage()
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'LDAP connection failed: ' . $e->getMessage()
            ]);
        }
    }
}