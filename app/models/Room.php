<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Room Model
 */
class Room
{
    use Model;

    protected $table = 'rooms';
    protected $allowedColumns = [
        'room_name',
        'desk_total',
        // Add any other columns that should be updatable
    ];

    // Validation rules
    public function validate($data)
    {
        $this->errors = [];

        // Validate room_name
        if(empty($data['room_name'])) {
            $this->errors['room_name'] = "Room name is required";
        }

        // Validate desk_total
        if(isset($data['desk_total'])) {
            if(!is_numeric($data['desk_total'])) {
                $this->errors['desk_total'] = "Number of desks must be a number";
            }
            
            if($data['desk_total'] < 0) {
                $this->errors['desk_total'] = "Number of desks cannot be negative";
            }
        }

        // Return true if no errors
        return empty($this->errors);
    }
}
