<?php

namespace Model;

class Room
{

    use Model;

    // protected $limit         = 10;
    // protected $offset         = 0;
    // protected $order_type     = "desc";
    // protected $order_column = "id";
    // public $errors         = [];

    protected $table = 'rooms';

    protected $allowedColumns = [
        'id',
        'room_name',
        'desk_total',

    ];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['id']))
        {
            $this->errors['id'] = "ID is required but was not recieved";
        }
        else
        if (empty($data['room_name']))
        {
            $this->errors['room_name'] = "Room name is required";
        }
        else
        if (empty($data['desk_total']))
        {
            $this->errors['desk_total'] = "Desk total is required";
        }


        if (empty($this->errors))
        {
            return true;
        }

        return false;
    }


}
