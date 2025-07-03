<?php

namespace Model;

class Booking
{

    use Model;

    protected $table = 'bookings';

    protected $allowedColumns = [
        'user_id',
        'desk_id',
        'room_id',
        'res_date',
        'user_name'
    ];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['user_id']))
        {
            $this->errors['user_id'] = "User ID is required";
        }
        else
        if (empty($data['desk_id']))
        {
            $this->errors['desk_id'] = "Desk ID is required";
        }
        else
        if (empty($data['room_id']))
        {
            $this->errors['room_id'] = "Room ID is required";
        }
        else
        if (empty($data['res_date']))
        {
            $this->errors['res_date'] = "Booking date is required";
        }

        if (empty($this->errors))
        {
            return true;
        }

        return false;
    }

    // advanced query methods for analytics

    public function getBookingStats($startDate, $endDate = null) {
    $endDate = $endDate ?: time();
    $query = "SELECT * FROM $this->table WHERE res_date >= :start_date AND res_date <= :end_date";
    return $this->query($query, ['start_date' => $startDate, 'end_date' => $endDate]);
    }

    public function getBookingCountByRoom($startDate, $roomId = null) {
        $query = "SELECT COUNT(*) as count FROM $this->table WHERE res_date >= :start_date";
        $data = ['start_date' => $startDate];
        
        if ($roomId) {
            $query .= " AND room_id = :room_id";
            $data['room_id'] = $roomId;
        }
        
        return $this->query($query, $data);
    }
}
