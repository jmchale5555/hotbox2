<?php

namespace Model;

class Desk
{

    use Model;

    protected $table = 'desks';

    protected $allowedColumns = [
        'room_id',
        'desk_number',

  ];

  public function validate($data)
  {
        $this->errors = [];

        if (!is_numeric($data['req_total']))
        {
            $this->errors['req_total'] = "Must be a number";
        }
  }
}
