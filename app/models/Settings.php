<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Settings Model
 */
class Settings
{
    use Model;

    protected $table = 'settings';
    protected $allowedColumns = [
        'setting_key',
        'setting_value',
        'setting_type',
        'updated_at'
    ];

    public function validate($data)
    {
        $this->errors = [];

        if(empty($data['setting_key'])) {
            $this->errors['setting_key'] = "Setting key is required";
        }

        if(!isset($data['setting_value'])) {
            $this->errors['setting_value'] = "Setting value is required";
        }

        return empty($this->errors);
    }

    /**
     * Get a setting by key
     */
    public function getSetting($key, $default = null)
    {
        $result = $this->first(['setting_key' => $key]);
        if($result) {
            // Handle different data types
            switch($result->setting_type) {
                case 'boolean':
                    return filter_var($result->setting_value, FILTER_VALIDATE_BOOLEAN);
                case 'integer':
                    return (int)$result->setting_value;
                case 'array':
                    return json_decode($result->setting_value, true);
                default:
                    return $result->setting_value;
            }
        }
        return $default;
    }

    /**
     * Set a setting
     */
    public function setSetting($key, $value, $type = 'string')
    {
        // Convert value based on type
        $stored_value = $value;
        if($type === 'boolean') {
            $stored_value = $value ? '1' : '0';
        } elseif($type === 'array') {
            $stored_value = json_encode($value);
        }

        $existing = $this->first(['setting_key' => $key]);
        
        $data = [
            'setting_key' => $key,
            'setting_value' => $stored_value,
            'setting_type' => $type,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($existing) {
            return $this->update($existing->id, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->insert($data);
        }
    }

    /**
     * Get all LDAP settings
     */
    public function getLdapSettings()
    {
        return [
            'ldap_enabled' => $this->getSetting('ldap_enabled', false),
            'ldap_host' => $this->getSetting('ldap_host', 'AD-DC-SL02.ad.contoso.co.uk'),
            'ldap_port' => $this->getSetting('ldap_port', 389),
            'ldap_base_dn' => $this->getSetting('ldap_base_dn', 'DC=ad,DC=contoso,DC=co,DC=uk'),
            'ldap_user_dn' => $this->getSetting('ldap_user_dn', 'OU=FIN,OU=PS,OU=Accounts,DC=ad,DC=contoso,DC=co,DC=uk'),
            'ldap_use_ssl' => $this->getSetting('ldap_use_ssl', false),
            'ldap_use_tls' => $this->getSetting('ldap_use_tls', false),
            'ldap_protocol' => $this->getSetting('ldap_protocol', 'ldap://'),
            'ldap_version' => $this->getSetting('ldap_version', 3),
            'ldap_timeout' => $this->getSetting('ldap_timeout', 5)
        ];
    }

    /**
     * Update LDAP settings
     */
    public function updateLdapSettings($data)
    {
        $this->errors = [];

        // Validate LDAP settings
        if(isset($data['ldap_enabled'])) {
            $this->setSetting('ldap_enabled', (bool)$data['ldap_enabled'], 'boolean');
        }

        if(!empty($data['ldap_host'])) {
            $this->setSetting('ldap_host', $data['ldap_host']);
        } else {
            $this->errors['ldap_host'] = "LDAP Host is required";
        }

        if(isset($data['ldap_port']) && is_numeric($data['ldap_port'])) {
            $this->setSetting('ldap_port', (int)$data['ldap_port'], 'integer');
        } else {
            $this->errors['ldap_port'] = "LDAP Port must be a valid number";
        }

        if(!empty($data['ldap_base_dn'])) {
            $this->setSetting('ldap_base_dn', $data['ldap_base_dn']);
        } else {
            $this->errors['ldap_base_dn'] = "LDAP Base DN is required";
        }

        if(!empty($data['ldap_user_dn'])) {
            $this->setSetting('ldap_user_dn', $data['ldap_user_dn']);
        } else {
            $this->errors['ldap_user_dn'] = "LDAP User DN is required";
        }

        if(isset($data['ldap_use_ssl'])) {
            $this->setSetting('ldap_use_ssl', (bool)$data['ldap_use_ssl'], 'boolean');
        }

        if(isset($data['ldap_use_tls'])) {
            $this->setSetting('ldap_use_tls', (bool)$data['ldap_use_tls'], 'boolean');
        }

        if(!empty($data['ldap_protocol'])) {
            $this->setSetting('ldap_protocol', $data['ldap_protocol']);
        }

        if(isset($data['ldap_version']) && is_numeric($data['ldap_version'])) {
            $this->setSetting('ldap_version', (int)$data['ldap_version'], 'integer');
        }

        if(isset($data['ldap_timeout']) && is_numeric($data['ldap_timeout'])) {
            $this->setSetting('ldap_timeout', (int)$data['ldap_timeout'], 'integer');
        }

        return empty($this->errors);
    }
}
