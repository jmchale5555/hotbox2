<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Model\Room;
//use \Core\Pager;
//use \Model\User;
use \Core\Session;

/**
 * admin class
 */
class Admin
{
    use MainController;

    public function index()
    {

        $ses = new Session;

        if (!$ses->is_logged_in())
        {
            redirect('login');
        }

        $rooms = new Room;
        $avilableRooms = $rooms->all();
        $data['rooms'] = json_encode($avilableRooms);
        $data['name'] = empty($_SESSION['USER']) ? 'guest user' : $_SESSION['USER']->name;
        //$user = new user;
        //$data['row'] = $row = $user->first(['id' => $_SESSION["USER"]->id]);

        $this->view('admin', $data);
    }

  public function deleteRoomById($roomId)
  {
    // Add validation for roomId
    if(!$roomId || !is_numeric($roomId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid room ID']);
        return;
    }
    
    $room = new Room;
    $room->delete($roomId);
    
    // Return a proper response
    if(empty($room->errors)) {
        echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'errors' => $room->errors]);
    }
  }

  public function amendDesks()
  {
    // Get JSON input
    $raw_data = file_get_contents('php://input');
    $data = json_decode($raw_data, true);
    
    // Debug logging
    error_log("Received data in amendDesks: " . print_r($data, true));
    
    // Response array
    $response = ['success' => false];
    
    // Check if data is valid
    if(!is_array($data) || empty($data)) {
        $response['message'] = 'No data received or invalid format';
        echo json_encode($response);
        return;
    }
    
    // Check for required ID
    if(!isset($data['id']) || empty($data['id'])) {
        $response['message'] = 'Room ID is required';
        echo json_encode($response);
        return;
    }
    
    $room = new Room;
    
    // Validate the data
    if($room->validate($data)) {
        // Update using the framework's method
        $room->update($data['id'], $data);
        
        // Check for errors
        if(empty($room->errors)) {
            $response = [
                'success' => true,
                'message' => 'Room updated successfully'
            ];
        } else {
            $response['errors'] = $room->errors;
            $response['message'] = 'Validation failed';
        }
    } else {
        $response['errors'] = $room->errors;
        $response['message'] = 'Validation failed';
    }
    
    echo json_encode($response);
  }

    public function addRoom()
  {
    // Get JSON input
    $raw_data = file_get_contents('php://input');
    $data = json_decode($raw_data, true);
    
    // Debug logging
    error_log("Received data in addRoom: " . print_r($data, true));
    
    // Response array
    $response = ['success' => false];
    
    // Check if data is valid
    if(!is_array($data) || empty($data)) {
        $response['message'] = 'No data received or invalid format';
        echo json_encode($response);
        return;
    }
    
    // Check for required ID
    if(!isset($data['id']) || empty($data['id'])) {
        $response['message'] = 'Room ID is required';
        echo json_encode($response);
        return;
    }
    
    $room = new Room;
    
    // Validate the data
    if($room->validate($data)) {
        // Update using the framework's method
        $room->insert($data);
        
        // Check for errors
        if(empty($room->errors)) {
            $response = [
                'success' => true,
                'message' => 'Room updated successfully'
            ];
        } else {
            $response['errors'] = $room->errors;
            $response['message'] = 'Validation failed';
        }
    } else {
        $response['errors'] = $room->errors;
        $response['message'] = 'Validation failed';
    }
    
    echo json_encode($response);
  }

}
