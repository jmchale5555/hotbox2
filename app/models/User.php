<?php

namespace Model;

class User
{

    use Model;

    protected $table = 'users';

    protected $allowedColumns = [
        'name',
        'email',
        'password',
    ];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['name']))
        {
            $this->errors['name'] = "Name is required";
        }
        else
        if (empty($data['email']))
        {
            $this->errors['email'] = "Email is required";
        }
        else
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email'] = "Enter a valid email address";
        }

        if (empty($data['password']))
        {
            $this->errors['password'] = "Password is required";
        }

        if ($data['confirm'])
        {
            if ($data['confirm'] !== $data['password'])
            {
                $this->errors['confirm'] = "Passwords do not match";
            }
            else
                unset($data[2]);
        }

        if (empty($this->errors))
        {
            return true;
        }

        return false;
    }
    public function rpwvalidate($data)
    {
        $this->errors = [];
        
        // Check if the new password is at least 8 characters long
        if(strlen($data['new_password']) < 8) 
        {

            $this->errors[] = "Password must be at least 8 characters long";
        }
        
        // Check if the new password and confirmation match
        if($data['new_password'] !== $data['confirm_password']) 
        {
            $this->errors[] = "Password confirmation doesn't match";
        }
        
        // Check if at least one uppercase letter, one lowercase letter, and one number
        if(!preg_match('/[A-Z]/', $data['new_password']) || 
          !preg_match('/[a-z]/', $data['new_password']) || 
          !preg_match('/[0-9]/', $data['new_password'])) 
        {
            $this->errors[] = "Password must include at least one uppercase letter, one lowercase letter, and one number";
        }
        
        return empty($this->errors);
    }

}
