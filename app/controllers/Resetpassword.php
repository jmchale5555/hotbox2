<?php

namespace Controller;

use Model\User;
use Core\Request;

defined('ROOTPATH') or exit('Access Denied');

class ResetPassword
{
    use MainController;

    public function index()
    {
        // Check if user is logged in
        if(!isset($_SESSION['USER']))
        {
            message("Please login to access this page");
            redirect('login');
        }

        $data = [];
        $this->view('reset_password', $data);
    }
    
    public function process()
    {
        // Check if user is logged in
        if(!isset($_SESSION['USER']))
        {
            echo json_encode(['success' => false, 'message' => 'Please login to access this page']);
            return;
        }
        
        // Get request data
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        
        if(!$data) {
            echo json_encode(['success' => false, 'errors' => ['Invalid request format']]);
            return;
        }
        
        $user = new User;
        $response = ['success' => false];
        
        if($user->rpwvalidate($data))
        {
            // Verify current password
            $current_user = $user->first(['id' => $_SESSION['USER']->id]);
            if(password_verify($data['current_password'], $current_user->password))
            {
                // Update password
                $hashed_password = password_hash($data['new_password'], PASSWORD_BCRYPT);
                $user->update($current_user->id, ['password' => $hashed_password]);
                
                $response = [
                    'success' => true,
                    'message' => 'Password updated successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'errors' => ['Current password is incorrect']
                ];
            }
        } else {
            $response = [
                'success' => false,
                'errors' => $user->errors
            ];
        }
        
        echo json_encode($response);
    }
}
