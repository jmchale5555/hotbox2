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
        $room = new Room;
        $room->delete($roomId);
        echo json_encode($room->errors);
  }

  public function amendDesks()
  {
    $room = new Room;
    $data = [];
    $data = json_decode(file_get_contents('php://input'), true);
    if($room->validate($data))
    {
      $room->update($data->id, $data);
    }

    echo json_encode($room->errors);
  }
}
