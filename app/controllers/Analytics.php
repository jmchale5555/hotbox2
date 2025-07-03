<?php

namespace Controller;

use Model\Booking;
use Model\Room;
use Model\User;
use Core\Session;
use Carbon\Carbon;

defined('ROOTPATH') or exit('Access Denied');

/**
 * Analytics class for booking statistics
 */
class Analytics
{
    use MainController;

    public function index()
    {
        $ses = new Session;
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $data['name'] = empty($_SESSION['USER']) ? 'guest user' : $_SESSION['USER']->name;
        $this->view('dashboard', $data);
    }

    // Get booking statistics by date range
    public function getBookingsByDateRange($days = 30)
    {
        $booking = new Booking();
        $startDate = time() - ($days * 86400); // X days ago
        $endDate = time();
        
        $bookings = $booking->where([], [], ['res_date' => $startDate]);
        
        // Group bookings by date
        $bookingsByDate = [];
        foreach ($bookings as $booking) {
            $date = date('Y-m-d', $booking->res_date);
            if (!isset($bookingsByDate[$date])) {
                $bookingsByDate[$date] = 0;
            }
            $bookingsByDate[$date]++;
        }
        
        // Fill in missing dates with 0
        for ($i = $days; $i >= 0; $i--) {
            $date = date('Y-m-d', time() - ($i * 86400));
            if (!isset($bookingsByDate[$date])) {
                $bookingsByDate[$date] = 0;
            }
        }
        
        ksort($bookingsByDate);
        echo json_encode($bookingsByDate);
    }

    // Get booking statistics by room
    public function getBookingsByRoom($days = 30)
    {
        $booking = new Booking();
        $room = new Room();
        
        $startDate = time() - ($days * 86400);
        $bookings = $booking->where([], [], ['res_date' => $startDate]);
        $rooms = $room->all();
        
        // Group bookings by room
        $bookingsByRoom = [];
        foreach ($rooms as $room) {
            $bookingsByRoom[$room->room_name] = 0;
        }
        
        foreach ($bookings as $booking) {
            $roomName = $this->getRoomName($booking->room_id);
            if ($roomName && isset($bookingsByRoom[$roomName])) {
                $bookingsByRoom[$roomName]++;
            }
        }
        
        echo json_encode($bookingsByRoom);
    }

    // Get booking statistics by user
    public function getBookingsByUser($days = 30)
    {
        $booking = new Booking();
        $startDate = time() - ($days * 86400);
        
        $bookings = $booking->where([], [], ['res_date' => $startDate]);
        
        // Group bookings by user
        $bookingsByUser = [];
        foreach ($bookings as $booking) {
            $userName = $booking->user_name;
            if (!isset($bookingsByUser[$userName])) {
                $bookingsByUser[$userName] = 0;
            }
            $bookingsByUser[$userName]++;
        }
        
        // Sort by booking count
        arsort($bookingsByUser);
        
        // Get top 10 users
        $topUsers = array_slice($bookingsByUser, 0, 10, true);
        
        echo json_encode($topUsers);
    }

    // Get booking statistics by day of week
    public function getBookingsByDayOfWeek($days = 30)
    {
        $booking = new Booking();
        $startDate = time() - ($days * 86400);
        
        $bookings = $booking->where([], [], ['res_date' => $startDate]);
        
        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $bookingsByDay = array_fill_keys($dayNames, 0);
        
        foreach ($bookings as $booking) {
            $dayName = date('l', $booking->res_date);
            if (isset($bookingsByDay[$dayName])) {
                $bookingsByDay[$dayName]++;
            }
        }
        
        echo json_encode($bookingsByDay);
    }

    // Get occupancy rate by room
    public function getOccupancyRate($days = 30)
    {
        $booking = new Booking();
        $room = new Room();
        
        $startDate = time() - ($days * 86400);
        $bookings = $booking->where([], [], ['res_date' => $startDate]);
        $rooms = $room->all();
        
        $occupancyData = [];
        
        foreach ($rooms as $room) {
            $totalSlots = $room->desk_total * $days; // Total available slots
            $bookedSlots = 0;
            
            foreach ($bookings as $booking) {
                if ($booking->room_id == $room->id) {
                    $bookedSlots++;
                }
            }
            
            $occupancyRate = $totalSlots > 0 ? ($bookedSlots / $totalSlots) * 100 : 0;
            $occupancyData[$room->room_name] = round($occupancyRate, 2);
        }
        
        echo json_encode($occupancyData);
    }

    private function getRoomName($roomId)
    {
        $room = new Room();
        $roomData = $room->where(['id' => $roomId]);
        return $roomData ? $roomData[0]->room_name : null;
    }
}