<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Model\Room;
use \Core\Session;

/**
 * admin class
 */
class Admin
{
    use MainController;

    // Define directory for room images
    private $upload_dir = 'assets/uploads/rooms/';

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

        $this->view('admin', $data);
    }

    public function deleteRoomById($roomId)
    {
        // Add validation for roomId
        if(!$roomId || !is_numeric($roomId)) {
            echo json_encode(['success' => false, 'message' => 'Invalid room ID']);
            return;
        }
        
        // Get room data to delete image file if exists
        $room = new Room;
        $room_data = $room->first(['id' => $roomId]);
        
        // Delete the room from database
        $room->delete($roomId);
        
        // If successful and room had an image, delete the image file
        if(empty($room->errors) && $room_data && !empty($room_data->room_image)) {
            $image_path = ROOTPATH . '/' . $room_data->room_image;
            if(file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Return a proper response
        if(empty($room->errors)) {
            echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'errors' => $room->errors]);
        }
    }

    public function amendDesks()
    {
        // Response array
        $response = ['success' => false];
        
        // Check if ID is provided
        if(!isset($_POST['id']) || empty($_POST['id'])) {
            $response['message'] = 'Room ID is required';
            echo json_encode($response);
            return;
        }

        $room = new Room;
        $room_id = $_POST['id'];
        
        // Get current room data
        $current_room = $room->first(['id' => $room_id]);
        if(!$current_room) {
            $response['message'] = 'Room not found';
            echo json_encode($response);
            return;
        }
        
        // Prepare data for update
        $data = [
            'room_name' => $_POST['room_name'],
            'desk_total' => $_POST['desk_total'],
            'room_image' => $current_room->room_image // Keep existing image by default
        ];
        
        // Handle image upload if provided
        if(isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
            $result = $this->handleImageUpload($_FILES['room_image'], $current_room->room_image);
            
            if($result['success']) {
                $data['room_image'] = $result['path'];
            } else {
                $response['errors']['room_image'] = $result['message'];
                echo json_encode($response);
                return;
            }
        }
        
        // Validate the data
        if($room->validate($data)) {
            // Update using the framework's method
            $room->update($room_id, $data);
            
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
        // Response array
        $response = ['success' => false];
        
        // Prepare data for insertion
        $data = [
            'room_name' => $_POST['room_name'],
            'desk_total' => $_POST['desk_total'],
            'room_image' => null // Default to null
        ];
        
        // Handle image upload if provided
        if(isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
            $result = $this->handleImageUpload($_FILES['room_image']);
            
            if($result['success']) {
                $data['room_image'] = $result['path'];
            } else {
                $response['errors']['room_image'] = $result['message'];
                echo json_encode($response);
                return;
            }
        }
        
        $room = new Room;
        
        // Validate the data
        if($room->validate($data)) {
            // Insert using the framework's method
            $room->insert($data);
            
            // Check for errors
            if(empty($room->errors)) {
                $response = [
                    'success' => true,
                    'message' => 'Room added successfully'
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
    
    /**
     * Handle image upload and processing
     * 
     * @param array $file The uploaded file data
     * @param string $old_image Path to the old image to delete
     * @return array Result of the upload operation
     */
    private function handleImageUpload($file, $old_image = null)
    {
        // Create upload directory if it doesn't exist
        $upload_path = ROOTPATH . '/' . $this->upload_dir;
        if(!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
        
        // Check file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if(!in_array($file['type'], $allowed_types)) {
            return [
                'success' => false,
                'message' => 'Only JPG, PNG, GIF, and WEBP files are allowed'
            ];
        }
        
        // Check file size (limit to 2MB)
        if($file['size'] > 2 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'Image size should be less than 2MB'
            ];
        }
        
        // Generate unique filename
        $filename = uniqid() . '_' . time() . '_' . $file['name'];
        $upload_file = $upload_path . $filename;
        
        // Move the uploaded file
        if(move_uploaded_file($file['tmp_name'], $upload_file)) {
            // If updating and old image exists, delete it
            if($old_image && file_exists(ROOTPATH . '/' . $old_image)) {
                unlink(ROOTPATH . '/' . $old_image);
            }
            
            return [
                'success' => true,
                'path' => $this->upload_dir . $filename
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to upload image. Please try again.'
        ];
    }

    public function getRooms()
    {
        $freshRooms = new Room();
        $rooms = $freshRooms->all();
        echo json_encode($rooms);
    }
}
