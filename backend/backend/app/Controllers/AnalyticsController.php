<?php

namespace App\Controllers;
use App\Models\CottageBookingModel;
use App\Models\OrderListModel;
use App\Models\OrdersModel;

use App\Models\CottageModel;
use App\Models\BookingModel;
use App\Models\RoomModel;
use CodeIgniter\RESTful\ResourceController;

class AnalyticsController extends ResourceController
{

    protected $cottageBookingModel;
    protected $cottageModel;
    protected $bookingModel;
    protected $roomModel;
    protected $orderListModel;
    protected $orders;


    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->roomModel = new RoomModel();
        $this->cottageBookingModel = new CottageBookingModel();
        $this->cottageModel = new CottageModel();
        $this->orderListModel = new OrderListModel();
        $this->orders = new OrdersModel();
    }
    public function getMostUsedCottages()
    {
        $query = $this->cottageBookingModel->select('cottage_id, COUNT(cottage_id) as total_bookings')
                                           ->where('cottagebooking_status', 'paid') // Filter only paid bookings
                                           ->groupBy('cottage_id')
                                           ->orderBy('total_bookings', 'DESC')
                                           ->get()
                                           ->getResultArray();
    
        foreach ($query as &$row) {
            $cottage = $this->cottageModel->find($row['cottage_id']);
            $row['cottage_name'] = $cottage['cottage_name'];
        }
    
        return $this->response->setJSON($query);
    }
    

    // 2. Get monthly bookings count (Peak Season)
    public function getMonthlyCottageBookings()
    {
        $query = $this->cottageBookingModel->select("MONTH(created_at) as month, COUNT(cottagebooking_id) as bookings_count")
                                           ->where('cottagebooking_status', 'paid')
                                           ->groupBy('month')
                                           ->orderBy('bookings_count', 'DESC')
                                           ->get()
                                           ->getResultArray();

        return $this->response->setJSON($query);
    }

    // 1. Get most used rooms
    public function getMostUsedRooms()
    {
        $query = $this->bookingModel->select('room_id, COUNT(room_id) as total_bookings')
        ->where('booking_status', 'paid') // Filter only paid bookings

                                    ->groupBy('room_id')
                                    ->orderBy('total_bookings', 'DESC')
                                    ->get()
                                    ->getResultArray();

        foreach ($query as &$row) {
            $room = $this->roomModel->find($row['room_id']);
            $row['room_name'] = $room['room_name'];
        }

        return $this->response->setJSON($query);
    }

    // 2. Get peak season (monthly booking count)
    public function getMonthlyBookings()
    {
        $query = $this->bookingModel->select("MONTH(checkin) as month, COUNT(book_id) as bookings_count")
                                    ->where('booking_status', 'paid')
                                    ->groupBy('month')
                                    ->orderBy('bookings_count', 'DESC')
                                    ->get()
                                    ->getResultArray();

        return $this->response->setJSON($query);
    }

    // 3. Calculate total revenue
public function getTotalRevenue()
{
    // Sum of downpayments for paid bookings in the bookings table
    $bookingRevenue = $this->bookingModel->select("SUM(downpayment) as total_booking_revenue")
                                         ->where('booking_status', 'paid')
                                         ->get()
                                         ->getRowArray()['total_booking_revenue'] ?? 0;
    
    // Sum of downpayments for paid cottage bookings in the cottage bookings table
    $cottageBookingRevenue = $this->cottageBookingModel->select("SUM(downpayment) as total_cottage_revenue")
                                                       ->where('cottagebooking_status', 'paid')
                                                       ->get()
                                                       ->getRowArray()['total_cottage_revenue'] ?? 0;
    
    // Sum of final prices for paid order list items associated with paid orders
    // Note: We are not summing the `total_price` from `orders` to avoid double-counting
    $orderListRevenue = $this->orderListModel->select("SUM(final_price) as total_order_list_revenue")
                                              ->join('orders', 'orders.order_id = order_list.order_id') // Join orders table
                                              ->where('orders.order_status', 'paid') // Only include items from paid orders
                                              ->get()
                                              ->getRowArray()['total_order_list_revenue'] ?? 0;

    // Calculate the total revenue from all sources
    $totalRevenue = $bookingRevenue + $cottageBookingRevenue + $orderListRevenue;

    // Return total revenue as a JSON response
    return $this->response->setJSON(['total_revenue' => $totalRevenue]);
}

}
