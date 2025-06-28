<?php

namespace Controller;

use Model\LocalUser;
use Model\Settings;
use Core\Session;
use Core\Request;
use LdapRecord\Connection;
use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User;

defined('ROOTPATH') or exit('Access Denied');

/**
 * Login class
 */
class Login
{
    use MainController;

    public function index()
    {
        $data = [];
        $settings = new Settings;
        $ldapSettings = $settings->getLdapSettings();

        // Check if LDAP is enabled
        if($ldapSettings['ldap_enabled']) {
            // Build LDAP credentials from settings
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

            //establish ldap connection
            try {
                $connection = new Connection($ldapCreds);
                Container::addConnection($connection);
                $connection->connect();

                // echo "Successfully connected!";
            } catch (\LdapRecord\Auth\BindException $e) {
                $error = $e->getDetailedError();

                echo ' ' . $error->getErrorCode() . ' ';
                echo ' ' . $error->getErrorMessage() . ' ';
                echo ' ' . $error->getDiagnosticMessage() . ' ';
            }
        }

        $req = new Request;
        if ($req->posted())
        {
            //check if the entered credentials are already in the users table
            $user = new LocalUser;
            $arr['username'] = $_POST['username'];
            $row = $user->first($arr);

            // if the user is found, verify the password and add them to the session
            if ($row)
            {
                if (password_verify($_POST['password'], $row->password))
                {
                    $session = new Session;
                    $session->auth($row);
                    redirect('book');
                }
            }

            if($ldapSettings['ldap_enabled']) {
            //otherwise check if the credentials can authenticate against LDAP
                if ($connection->auth()->attempt("CN=".$_POST['username'].",".$ldapSettings["ldap_user_dn"], $_POST['password'], $stayAuthenticated = true) == true)
                {
                    //if authentication suceeded, pull the user's name out of LDAP and create a row in the users table for them
                    $ldapUser = User::whereStartsWith('CN', $_POST['username'])->select('displayname')->first();
                    // dd($ldapUser);
                    $hashed_password = password_hash($req->post('password'), PASSWORD_BCRYPT);
                    $req->set('password', $hashed_password);
                    $req->set('created_at', date("Y-m-d H:i:s"));
                    $req->set('is_admin', 0);
                    $req->set('name', $ldapUser->displayname[0]);
                    $req->set('domain', 'LDAP');
                    if($row)
                    {
                    $user->update($row->id, $req->post());
                    } else 
                    {
                    $user->insert($req->post());
                    }

                $data['errors'] = $user->errors;
                
                //now we'll need to re-check the users table and put the user in to the session
                $newLdaprow = $user->first($arr);

                if($newLdaprow) {
                    $session = new Session;
                    $session->auth($newLdaprow);
                    redirect('book');
                    }
                }
            }
            
            $user->errors['username'] = "Wrong username or password";

            $data['errors'] = $user->errors;
            
        }
    //display the login view with associated data
    $this->view('login', $data);
    }
}
