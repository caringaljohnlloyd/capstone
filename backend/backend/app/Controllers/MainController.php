<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CottageBookingModel;
use App\Models\RoomModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\FeedbackModel;
use App\Models\BookingModel;
use App\Models\ShopModel;
use App\Models\PoolModel;
use App\Models\CartModel;
use App\Models\ManifestModel;
use App\Models\InvoiceModel;
use App\Models\OrderListModel;
use App\Models\OrdersModel;
use App\Models\AuditModel;
use App\Models\StaffModel;
use App\Models\DateModel;
use App\Models\NotificationModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\EnrollmentModel;
use App\Models\EventModel;
use App\Models\TableModel;
use App\Models\MenuModel;
use App\Models\CottageModel;
use App\Models\ReservationModel;
use App\Models\TableOrdersModel;
use App\Models\OrderItemModel;
use App\Models\AmenitiesModel;
use App\Models\RoomAmenitiesModel;
use App\Models\AmenitiesAuditModel;
use App\Models\BookingGroupBookings;


class MainController extends ResourceController
{

    use ResponseTrait;
    protected $notificationModel;

    protected $room;
    protected $orders;
    protected $orderitems;
    protected $invoice;
    protected $cartModel;
    protected $cart;
    protected $table;
    protected $cottage;
    protected $ordersModel;
    protected $db;
    protected $menuModel;
    protected $reservationModel;
    protected $tableorderModel;
    protected $orderItemModel;
    protected $tableModel;
    protected $userModel;
    protected $amenitiesModel;
    protected $roomAmenitiesModel;
    protected $cottageModel;
    protected $bookingModel;
    protected $cottageBookingModel;


    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->reservationModel = new ReservationModel();
        $this->userModel = new UserModel();
        $this->tableModel = new TableModel();
        $this->orderItemModel = new OrderItemModel();
        $this->amenitiesModel = new AmenitiesModel();
        $this->roomAmenitiesModel = new RoomAmenitiesModel();
        $this->cottageModel = new CottageModel();
        $this->notificationModel = new NotificationModel();


    }
    public function markAsRead()
    {
        // Get the 'ids' from the JSON body
        $ids = $this->request->getJSON(true)['ids'];  // Using getJSON() to decode the JSON body
    
        // Validate that IDs are provided
        if (empty($ids) || !is_array($ids)) {
            return $this->fail('Notification IDs are required and must be an array');
        }
    
        // Log the received IDs for debugging
        log_message('debug', 'Received IDs to mark as read: ' . implode(',', $ids));
    
        // Update the notifications' status to 'read' where the current status is 'unread'
        $builder = $this->db->table('notifications');
    
        // Check if there are any unread notifications to update
        $builder->whereIn('notification_id', $ids);
        $builder->where('notifstatus', 'unread');
        $notifications = $builder->get()->getResultArray();
    
        // Log the notifications that are found for update
        log_message('debug', 'Notifications fetched for update: ' . print_r($notifications, true));
    
        // If no notifications match the criteria, return a failure message
        if (empty($notifications)) {
            log_message('error', 'No unread notifications found with the given IDs');
            return $this->fail('No unread notifications found with the given IDs');
        }
    
        // Perform the update: Change the 'notifstatus' to 'read'
        $builder->whereIn('notification_id', $ids)
                ->where('notifstatus', 'unread')
                ->update(['notifstatus' => 'read']);
    
        // Check the number of affected rows
        $affectedRows = $this->db->affectedRows();
        log_message('debug', 'Number of affected rows: ' . $affectedRows);
    
        // If the update is successful, return a success response
        if ($affectedRows > 0) {
            return $this->respond(['status' => 'success', 'message' => 'Notifications marked as read'], 200);
        } else {
            // If no rows were affected, return an error response
            log_message('error', 'No notifications were marked as read or an error occurred');
            return $this->fail('No notifications were marked as read or an error occurred');
        }
    }
    
    
    
    
    


    
    
    
    
  public function getPendingAndConfirmedCottageBookings()
{
    $cottageBookingModel = new CottageBookingModel();

    $bookings = $cottageBookingModel->whereIn('cottagebooking_status', ['pending', 'confirmed'])
                                    ->select('cottage_id, selectedTime, selectedTimeout')
                                    ->findAll();

    return $this->response->setJSON($bookings);
}



    public function getPendingAndConfirmedBookings()
    {
        $bookingModel = new BookingModel();
    
        // Fetch bookings with "pending" or "confirmed" status
        $bookings = $bookingModel->whereIn('booking_status', ['pending', 'confirmed'])
                                 ->select('room_id, checkin, checkout')
                                 ->findAll();
    
        return $this->response->setJSON($bookings);
    }
    
  // Get a list of all cottages or a single cottage by ID
  public function getCottages($cottage_id = null)
  {
      if ($cottage_id) {
          $cottage = $this->cottageModel->find($cottage_id);

          if (!$cottage) {
              return $this->respond(["message" => "Cottage not found"], 404);
          }

          return $this->respond($cottage, 200);
      }

      $cottages = $this->cottageModel->findAll();
      return $this->respond($cottages, 200);
  }

  // Save a new cottage
  public function saveCottage()
  {
      $request = $this->request;
  
      $data = [
          'cottage_name' => $request->getPost('cottage_name'),
          'cottage_description' => $request->getPost('cottage_description'),
          'cottage_price' => $request->getPost('cottage_price'),
      ];
  
      // Check if image is uploaded
      if ($request->getFile('cottage_image') && $request->getFile('cottage_image')->isValid()) {
          $image = $request->getFile('cottage_image');
          $imageName = $this->generateUniqueCottageFileName($image->getName());
  
          // Move the uploaded file to the desired directory
          $data['cottage_image'] = $this->handleCottageImageUpload($image, $imageName);
      }
  
      try {
          $this->cottageModel->insert($data);  // Insert data without created_at
          return $this->respond(["message" => "Cottage added successfully"], 200);
      } catch (\Exception $e) {
          return $this->respond(["message" => "Failed to add cottage: " . $e->getMessage()], 500);
      }
  }
  
  

  // Update an existing cottage
  public function updateCottage($cottage_id = null)
  {
      $request = $this->request;
      $existingData = $this->cottageModel->find($cottage_id);
  
      if (empty($existingData)) {
          return $this->respond(["message" => "Cottage not found"], 404);
      }
  
      $data = [
          'cottage_name' => $request->getVar('cottage_name') ?? $existingData['cottage_name'],
          'cottage_description' => $request->getVar('cottage_description') ?? $existingData['cottage_description'],
          'cottage_price' => $request->getVar('cottage_price') ?? $existingData['cottage_price'],
      ];
  
      // Check if new image is uploaded
      $image = $request->getFile('cottage_image');
      if ($image && $image->isValid()) {
          // Generate a new unique name for the image
          $imageName = $this->generateUniqueCottageFileName($image->getName());
          
          // Upload the image
          $this->handleCottageImageUpload($image, $imageName);
          
          // Set the new image name in the data array
          $data['cottage_image'] = $imageName;
  
          // Optionally delete the old image from the server if needed
          $this->deleteOldImage($existingData['cottage_image']);
      }
  
      try {
          // Update the cottage with new data
          $this->cottageModel->update($cottage_id, $data);
          return $this->respond(["message" => "Cottage updated successfully"], 200);
      } catch (\Exception $e) {
          return $this->respond(["message" => "Failed to update cottage: " . $e->getMessage()], 500);
      }
  }
  
  private function deleteOldImage($imageName)
  {
      $filePath = 'uploads/' . $imageName;
      if (is_file($filePath)) {
          unlink($filePath);  // Delete the file
      }
  }
  
  // Handle image upload
  private function handleCottageImageUpload($image, $imageName)
  {
      $uploadPath = 'backend/public/uploads';
      $image->move($uploadPath, $imageName);
      return $imageName;
  }

  // Generate a unique file name for the uploaded image
  private function generateUniqueCottageFileName($fileName)
  {
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
      $uniqueName = uniqid() . '.' . $fileExtension;
      return $uniqueName;
  }


    public function fetchTables()
    {
        $tableModel = new TableModel();
        $data = $tableModel->findAll();
        
        return $this->respond($data, 200); // Return data with a success response code (200 OK)
    }
    public function addTable()
{
    $json = $this->request->getJSON();

    // Validate the received data
    if (!isset($json->table_name) || !isset($json->table_description)) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Missing required fields'
        ], 400); // Return error for missing fields
    }

    $data = [
        'table_name' => $json->table_name,
        'table_description' => $json->table_description,
    ];

    try {
        $tableModel = new TableModel();
        
        if (!$tableModel->save($data)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to add table',
                'errors' => $tableModel->errors()
            ], 400); // Return error response if save fails
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Table added successfully!'
        ], 200); // Success response

    } catch (\Exception $e) {
        log_message('error', 'Failed to add table: ' . $e->getMessage());
        
        return $this->respond([
            'status' => 'error',
            'message' => 'Failed to add table: ' . $e->getMessage()
        ], 500); // Return error if exception occurs
    }
}
public function updateTable($id = null)
{
    $request = $this->request;
    $tableModel = new TableModel();

    // Find the existing data by ID
    $existingData = $tableModel->find($id);

    if (empty($existingData)) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Record not found'
        ], 404); // Return error if record is not found
    }

    // Prepare new data, using existing values if no new values are provided
    $data = [
        'table_id' => $id,
        'table_name' => $request->getVar('table_name') ?? $existingData['table_name'],
        'table_description' => $request->getVar('table_description') ?? $existingData['table_description'],
    ];

    // Check if any data has actually changed
    if ($data === array_intersect_key($existingData, $data)) {
        return $this->respond([
            'status' => 'success',
            'message' => 'No changes detected, data not updated'
        ], 200); // Return message if no changes were made
    }

    try {
        // Update the table record
        if ($tableModel->update($id, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Table updated successfully!'
            ], 200); // Success response
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update table'
            ], 500); // Return error if update fails
        }
    } catch (\Exception $e) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Failed to update table: ' . $e->getMessage()
        ], 500); // Return error if exception occurs
    }
}

public function confirmCottageBooking($id = null)
{
    try {
        if ($id === null) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
        }

        $bookingModel = new CottageBookingModel();
        $notificationModel = new NotificationModel();

        // Find the booking
        $booking = $bookingModel->find($id);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Booking not found.']);
        }

        if ($booking['cottagebooking_status'] === 'pending') {
            $bookingModel->update($id, ['cottagebooking_status' => 'confirmed']);
            $notificationData = [
                'id' => $booking['user_id'],
                'message' => 'Your cottage booking has been confirmed.'
            ];
            $notificationModel->insert($notificationData);
            return $this->response->setJSON(['message' => 'Cottage booking confirmed successfully']);
        }

        return $this->response->setJSON(['message' => 'Cottage booking is not pending or already confirmed'], 400);
    } catch (\Exception $e) {
        log_message('error', 'Error in confirmCottageBooking: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
    }
}

public function declineCottageBooking($id = null)
{
    try {
        if ($id === null) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
        }

        $bookingModel = new CottageBookingModel();
        $notificationModel = new NotificationModel();

        // Find the booking
        $booking = $bookingModel->find($id);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Booking not found.']);
        }

        if ($booking['cottagebooking_status'] === 'confirmed') {
            return $this->response->setJSON(['message' => 'Cottage booking cannot be declined because it is already confirmed.'], 400);
        }

        if ($booking['cottagebooking_status'] === 'pending') {
            $bookingModel->update($id, ['cottagebooking_status' => 'declined']);
            $notificationData = [
                'id' => $booking['user_id'],
                'message' => 'Your cottage booking has been declined.'
            ];
            $notificationModel->insert($notificationData);
            return $this->response->setJSON(['message' => 'Cottage booking declined successfully']);
        }

        return $this->response->setJSON(['message' => 'Cottage booking is not pending or already declined'], 400);
    } catch (\Exception $e) {
        log_message('error', 'Error in declineCottageBooking: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
    }
}

    public function markCottageBookingAsPaid($id = null)
    {
        try {
            if ($id === null) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
            }

            $bookingModel = new CottageBookingModel();

            // Find the booking
            $booking = $bookingModel->find($id);

            if (!$booking) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Booking not found.']);
            }

            if ($booking['cottagebooking_status'] === 'confirmed') {
                $bookingModel->update($id, ['cottagebooking_status' => 'paid']);
                return $this->response->setJSON(['message' => 'Cottage booking marked as paid successfully']);
            }

            return $this->response->setJSON(['message' => 'Cottage booking is not confirmed or already paid'], 400);
        } catch (\Exception $e) {
            log_message('error', 'Error in markCottageBookingAsPaid: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }
    public function getAllCottageBookings()
    {
        try {
            $bookingModel = new CottageBookingModel();
            
            // Get all bookings
            $bookings = $bookingModel->findAll();
    
            // Filter out bookings with status 'paid' or 'declined'
            $filteredBookings = array_filter($bookings, function($booking) {
                return !in_array($booking['cottagebooking_status'], ['paid', 'declined']);
            });
            
            if (empty($filteredBookings)) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'No cottage bookings found.']);
            }
    
            return $this->response->setJSON(array_values($filteredBookings)); // Re-index the array
        } catch (\Exception $e) {
            log_message('error', 'Error in getAllCottageBookings: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }

    public function getTableReservation()
    {
        try {
            // Fetch reservations
            $reservations = $this->reservationModel->findAll();
    
            // Enrich the reservation data
            $filteredReservations = []; // Array to hold reservations that are not paid or declined
            foreach ($reservations as &$reservation) {
                // Only include reservations that are not paid or declined
                if ($reservation['status'] !== 'paid' && $reservation['status'] !== 'declined') {
                    $reservation['user'] = $this->userModel->find($reservation['user_id']);
                    $reservation['table'] = $this->tableModel->find($reservation['table_id']);
                    
                    // Fetch order items with item details
                    $orderItems = $this->orderItemModel
                        ->select('order_items.*, menu.item_name') // Select item_name from items table
                        ->join('menu', 'order_items.menu_item_id = menu.menu_id') // Join items table to get item details
                        ->where('order_items.reservation_id', $reservation['reservation_id'])
                        ->findAll();
                        
                    $reservation['order_items'] = $orderItems;
                    
                    // Add the reservation to the filtered list
                    $filteredReservations[] = $reservation;
                }
            }
    
            return $this->response->setJSON($filteredReservations);
        } catch (\Exception $e) {
            log_message('error', 'Error in getTableReservation: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }
    public function confirmReservation($id = null)
    {
        try {
            if ($id === null) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
            }
    
            $reservationModel = new ReservationModel();
            $notificationModel = new NotificationModel();
    
            // Find the reservation
            $reservation = $reservationModel->find($id);
    
            if (!$reservation) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Reservation not found.']);
            }
    
            if ($reservation['status'] === 'pending') {
                $reservationModel->update($id, ['status' => 'confirmed']);
                $notificationData = [
                    'id' => $reservation['user_id'], // Assuming user_id is available in the reservation
                    'message' => 'Your reservation has been confirmed.'
                ];
                $notificationModel->insert($notificationData);
                return $this->response->setJSON(['message' => 'Reservation confirmed successfully']);
            }
    
            return $this->response->setJSON(['message' => 'Reservation is not pending or already confirmed'], 400);
        } catch (\Exception $e) {
            log_message('error', 'Error in confirmReservation: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }
    
    public function declineReservation($id = null)
    {
        try {
            if ($id === null) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
            }
    
            $reservationModel = new ReservationModel();
            $notificationModel = new NotificationModel();
    
            // Find the reservation
            $reservation = $reservationModel->find($id);
    
            if (!$reservation) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Reservation not found.']);
            }
    
            if ($reservation['status'] === 'pending') {
                $reservationModel->update($id, ['status' => 'declined']);
                $notificationData = [
                    'id' => $reservation['user_id'], // Assuming user_id is available in the reservation
                    'message' => 'Your reservation has been declined.'
                ];
                $notificationModel->insert($notificationData);
                return $this->response->setJSON(['message' => 'Reservation declined successfully']);
            }
    
            return $this->response->setJSON(['message' => 'Reservation is not pending or already declined'], 400);
        } catch (\Exception $e) {
            log_message('error', 'Error in declineReservation: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }  
public function markResrvationAsPaid($id = null)
{
    try {
        if ($id === null) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
        }

        $reservationModel = new ReservationModel();

        // Find the reservation
        $reservation = $reservationModel->find($id);

        if (!$reservation) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Reservation not found.']);
        }

        if ($reservation['status'] === 'confirmed') {
            $reservationModel->update($id, ['status' => 'paid']);
            return $this->response->setJSON(['message' => 'Reservation marked as paid successfully']);
        }

        return $this->response->setJSON(['message' => 'Reservation is not confirmed or already paid'], 400);
    } catch (\Exception $e) {
        log_message('error', 'Error in markAsPaid: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
    }
}  
    public function show($id = null)
    {
        try {
            $reservation = $this->reservationModel->find($id);
    
            if (!$reservation) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Reservation not found.']);
            }
    
            $reservation['user'] = $this->userModel->find($reservation['user_id']);
            $reservation['table'] = $this->tableModel->find($reservation['table_id']);
            
            // Fetch order items with item details
            $orderItems = $this->orderItemModel
                ->select('order_items.*, items.item_name') // Select item_name from items table
                ->join('items', 'order_items.menu_item_id = items.id') // Join items table to get item details
                ->where('order_items.reservation_id', $reservation['reservation_id'])
                ->findAll();
    
            $reservation['order_items'] = $orderItems;
    
            return $this->response->setJSON($reservation);
        } catch (\Exception $e) {
            log_message('error', 'Error in show: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }    

    public function createReservation()
    {
        $data = $this->request->getPost();
        log_message('debug', 'Received reservation data: ' . print_r($data, true));
    
        $errors = [];
    
        // Validate required fields
        if (empty($data['user_id'])) {
            $errors['user_id'] = 'User ID is required.';
        } elseif (!is_numeric($data['user_id'])) {
            $errors['user_id'] = 'User ID must be a numeric value.';
        }
    
        if (empty($data['table_id'])) {
            $errors['table_id'] = 'Table ID is required.';
        } elseif (!is_numeric($data['table_id'])) {
            $errors['table_id'] = 'Table ID must be a numeric value.';
        }
    
         if (empty($data['reservation_time'])) {
        $errors['reservation_time'] = 'Reservation time is required.';
    } elseif (!$this->isValidISO8601($data['reservation_time'])) {
        $errors['reservation_time'] = 'Reservation time must be a valid ISO 8601 datetime.';
    } else {
        // Convert to appropriate format or handle time zone if necessary
        $data['reservation_time'] = date('Y-m-d H:i:s', strtotime($data['reservation_time'])); // Adjust based on your timezone needs
    }
    
        if (empty($data['payment_amount'])) {
            $errors['payment_amount'] = 'Payment amount is required.';
        } elseif (!is_numeric($data['payment_amount']) || $data['payment_amount'] <= 0) {
            $errors['payment_amount'] = 'Payment amount must be a positive numeric value.';
        }
    
        if (empty($data['payment_method'])) {
            $errors['payment_method'] = 'Payment method is required.';
        }
    
        if (empty($data['order_items']) || !is_array($data['order_items'])) {
            $errors['order_items'] = 'Order items are required and must be a non-empty array.';
        }
    
        if (!empty($errors)) {
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Validation errors', 'errors' => $errors]);
        }
    
        // Handle proof of payment file upload
        if ($file = $this->request->getFile('proof_of_payment')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'backend/public/uploads/', $newName); // Change to FCPATH
                $data['proof_of_payment'] = $newName; // Save file name to database
            } else {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'Proof of payment file upload failed.']);
            }
        }
    
        // Load models
        $reservationModel = model(ReservationModel::class);
        $orderItemModel = model(OrderItemModel::class);
        $tableOrderModel = model(TableOrdersModel::class);
    
        if (!$reservationModel || !$orderItemModel || !$tableOrderModel) {
            log_message('error', 'One or more models are not loaded.');
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal server error']);
        }
    
        // Start a transaction
        $this->db->transStart();
    
        // Prepare reservation data
        $reservationData = [
            'user_id' => $data['user_id'],
            'table_id' => $data['table_id'],
            'reservation_time' => $data['reservation_time'],
            'status' => 'pending', // Combined status
            'payment_amount' => $data['payment_amount'],
            'payment_method' => $data['payment_method'],
            'proof_of_payment' => $data['proof_of_payment'] // Save the file name here
        ];
    
        // Save reservation data
        if (!$reservationModel->save($reservationData)) {
            $errors = $reservationModel->errors();
            log_message('error', 'Reservation validation errors: ' . print_r($errors, true));
            $this->db->transRollback();
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Validation errors', 'errors' => $errors]);
        }
    
        $reservationId = $reservationModel->getInsertID();
    
        // Save order items
        foreach ($data['order_items'] as $item) {
            if (!$orderItemModel->save([
                'reservation_id' => $reservationId,
                'menu_item_id' => $item['menu_id'],
                'quantity' => $item['quantity']
            ])) {
                $errors = $orderItemModel->errors();
                log_message('error', 'Order item validation errors: ' . print_r($errors, true));
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON(['message' => 'Validation errors', 'errors' => $errors]);
            }
        }
    
        // Create table order entry
        if (!$tableOrderModel->save([
            'reservation_id' => $reservationId,
            'order_time' => $data['reservation_time']
        ])) {
            $errors = $tableOrderModel->errors();
            log_message('error', 'Table order validation errors: ' . print_r($errors, true));
            $this->db->transRollback();
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Validation errors', 'errors' => $errors]);
        }
    
        // Commit transaction
        $this->db->transComplete();
    
        if ($this->db->transStatus() === FALSE) {
            log_message('error', 'Transaction failed.');
            return $this->response->setStatusCode(500)->setJSON(['message' => 'An error occurred while creating the reservation.']);
        }
    
        return $this->response->setStatusCode(200)->setJSON(['message' => 'Reservation created successfully', 'reservation_id' => $reservationId]);
    }
    
    private function isValidISO8601($datetime): bool
    {
        // Regular expression for ISO 8601 format
        $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{1,3})?(?:Z|[\+\-]\d{2}:\d{2})$/';
        return preg_match($pattern, $datetime);
    }

    public function getReservation($id)
    {
        $reservation = $this->reservationModel->find($id);

        if (!$reservation) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Reservation not found']);
        }

        return $this->response->setStatusCode(200)->setJSON($reservation);
    }
    public function deleteReservation($id)
    {
        if ($this->reservationModel->delete($id)) {
            return $this->response->setStatusCode(200)->setJSON(['success' => 'Reservation deleted successfully']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete reservation']);
    }
    public function addOrderItem()
    {
        $reservationId = $this->request->getPost('reservation_id');
        $menuItemId = $this->request->getPost('menu_item_id');
        $quantity = $this->request->getPost('quantity');

        if (!$reservationId || !$menuItemId || !$quantity) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required parameters']);
        }

        // Validate that the menu item exists
        $menuModel = model('App\Models\MenuModel');
        $menuItem = $menuModel->find($menuItemId);

        if (!$menuItem) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Menu item not found']);
        }

        $data = [
            'reservation_id' => $reservationId,
            'menu_item_id' => $menuItemId,
            'quantity' => $quantity,
        ];

        if ($this->orderItemModel->save($data)) {
            return $this->response->setStatusCode(201)->setJSON(['success' => 'Order item added successfully']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to add order item']);
        }
    }
    public function getOrderItem($id)
    {
        $orderItem = $this->orderItemModel->find($id);

        if (!$orderItem) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Order item not found']);
        }

        return $this->response->setStatusCode(200)->setJSON($orderItem);
    }
    public function deleteOrderItem($id)
    {
        if ($this->orderItemModel->delete($id)) {
            return $this->response->setStatusCode(200)->setJSON(['success' => 'Order item deleted successfully']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete order item']);
    }
    public function fetchMenuItems()
{
    $menu = new MenuModel();
    $data = $menu->findAll();
    return $this->respond($data, 200);
}
public function addMenuItem()
{
    // Check if the request contains a file upload
    $file = $this->request->getFile('item_image');

    // Use getPost() to retrieve form data when using 'multipart/form-data'
    $itemName = $this->request->getPost('item_name');
    $itemCategory = $this->request->getPost('item_category');
    $itemPrice = $this->request->getPost('item_price');

    // Validate the received data
    if (empty($itemName) || empty($itemCategory) || empty($itemPrice)) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Missing required fields: item_name, item_category, or item_price.'
        ], 400);
    }

    $data = [
        'item_name' => $itemName,
        'item_category' => $itemCategory,
        'item_price' => $itemPrice
    ];

    // Check if a file is uploaded and handle file upload
    if ($file && $file->isValid() && !$file->hasMoved()) {
        // Validate the file type (e.g., only allow images)
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'
            ], 400);
        }

        // Define the upload directory outside the writable path
        $uploadDir = FCPATH . 'backend/public/uploads/'; // Adjust the path as needed

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the file to the uploads directory and generate a unique name
        $newName = $file->getRandomName();
        if (!$file->move($uploadDir, $newName)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to move uploaded file.'
            ], 500);
        }

        // Store only the file name in the data array to be saved in the database
        $data['item_image'] = $newName; // Save only the file name
    }

    try {
        $menuModel = new MenuModel();
        if (!$menuModel->save($data)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to add menu item',
                'errors' => $menuModel->errors()
            ], 400);
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Item added successfully!'
        ], 200);
    } catch (\Exception $e) {
        log_message('error', 'Failed to add menu item: ' . $e->getMessage());
        return $this->respond([
            'status' => 'error',
            'message' => 'Failed to add item: ' . $e->getMessage()
        ], 500);
    }
}

public function updateMenuItem($menu_id = null)
{
    $request = $this->request;

    // Load the menu model and find the existing item by ID
    $menuModel = new MenuModel();
    $existingData = $menuModel->find($menu_id);

    if (empty($existingData)) {
        return $this->respond([
            "status" => "error",
            "message" => "Menu item not found"
        ], 404);
    }

    // Gather data
    $data = [
        'item_name' => $request->getPost('item_name') ?? $existingData['item_name'],
        'item_category' => $request->getPost('item_category') ?? $existingData['item_category'],
        'item_price' => $request->getPost('item_price') ?? $existingData['item_price'],
    ];

    // Check if a new image is uploaded
    $image = $request->getFile('item_image');
    if ($image && $image->isValid()) {
        // Generate a new unique name for the image
        $imageName = $this->generateUniqueMenuFileName($image->getName());
        
        // Handle the image upload
        $this->handleItemImageUpload($image, $imageName);
        
        // Set the new image name in the data array
        $data['item_image'] = $imageName;

        // Optionally delete the old image from the server
        $this->deleteItemOldImage($existingData['item_image']);
    }

    try {
        // Update the menu item with the new data
        $menuModel->update($menu_id, $data);
        return $this->respond([
            "status" => "success",
            "message" => "Menu item updated successfully"
        ], 200);
    } catch (\Exception $e) {
        return $this->respond([
            "status" => "error",
            "message" => "Failed to update menu item: " . $e->getMessage()
        ], 500);
    }
}

// Function to delete the old image from the server
private function deleteItemOldImage($imageName)
{
    $filePath = 'uploads/' . $imageName;
    if (is_file($filePath)) {
        unlink($filePath);  // Delete the file
    }
}

// Function to handle image upload and saving
private function handleItemImageUpload($image, $newFileName)
{
    // Define the upload directory
    $uploadDir = FCPATH . 'backend/public/uploads/';

    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move the file to the upload directory with the new unique name
    if (!$image->move($uploadDir, $newFileName)) {
        throw new \RuntimeException('Failed to move uploaded file.');
    }
}

// Function to generate a unique filename for the uploaded image
private function generateUniqueMenuFileName($originalName)
{
    // Use a unique identifier (e.g., a timestamp and random string) for the file name
    return uniqid() . '_' . $originalName;
}




public function deleteMenuItem($id)
{
    if ($this->menuModel->delete($id)) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Item deleted successfully!']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete item']);
    }
}

    public function generateAllReports()
    {
        $dailyReports = $this->showDailyReport();
        $monthlyReports = $this->monthlySalesReport();
        $yearlyReports = $this->yearlySalesReport();

        $allReports = [
            'dailySales' => $dailyReports,
            'monthlySales' => $monthlyReports,
            'yearlySales' => $yearlyReports
        ];

        return $this->response->setJSON($allReports);
    }



    public function showDailyReport()
    {
        $builder = $this->db->table('orders');
        $builder->select('DATE(orders.created_at) as date, SUM(orders.total_price) as total_sales');
        $builder->where('orders.order_status', 'paid');
        $builder->groupBy('DATE(orders.created_at)');
        $builder->orderBy('DATE(orders.created_at)', 'DESC');
        $query = $builder->get();

        log_message('info', 'Executed Query: ' . $this->db->getLastQuery());

        if (!$query) {
            return ['error' => 'Failed to fetch data.'];
        }

        $result = $query->getResultArray();
        return $result ? $result : ['error' => 'No sales data found.'];
    }



    public function monthlySalesReport()
    {
        $builder = $this->db->table('orders');
        $builder->select('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as total_sales');
        $builder->where('order_status', 'paid');
        $builder->groupBy('YEAR(created_at), MONTH(created_at)');
        $builder->orderBy('YEAR(created_at) DESC, MONTH(created_at) DESC');

        $query = $builder->get();

        if (!$query) {
            return ['error' => 'Database error.'];
        }

        $result = $query->getResultArray();
        return $result ?: ['error' => 'No data found.'];
    }


    public function yearlySalesReport() {
        $builder = $this->db->table('orders');
        $builder->select('YEAR(created_at) as year, SUM(total_price) as total_sales');
        $builder->where('order_status', 'paid');
        $builder->groupBy('YEAR(created_at)');
        $query = $builder->get();
    
        if (!$query) {
            return ['error' => 'Failed to fetch data.'];
        }
    
        return $query->getResultArray();
    }
    
    

    public function getOrders()
    {
        $orders = new OrdersModel();
        $data = $orders->findAll();
        return $this->respond($data, 200);
    }
public function cottageBooking()
{
    // Log incoming request for debugging
    log_message('debug', 'Request body: ' . json_encode($this->request->getPost()));

    $json = $this->request->getPost(); // Use getPost() for form data

    // Validate parameters
    if (!$json || !isset($json['cottage_id'], $json['selectedTime'], $json['selectedTimeout'], $json['user_id'], $json['downpayment'])) {
        return $this->fail('Missing required parameters', 400);
    }

    // Ensure times are stored as UTC
    $selectedTime = date('Y-m-d H:i:s', strtotime($json['selectedTime']));
    $selectedTimeout = date('Y-m-d H:i:s', strtotime($json['selectedTimeout']));

    // Handle proof of downpayment file
    $file = $this->request->getFile('proofOfDownpayment');
    if (!$file || !$file->isValid()) {
        return $this->fail('File upload error', 400);
    }

    // Move file to public/uploads directory
    $fileName = $file->getRandomName();
    $file->move(FCPATH . 'backend/public/uploads/', $fileName); // Change to FCPATH

    $bookingModel = new CottageBookingModel();
    $cottageModel = new CottageModel();

    $cottage = $cottageModel->find($json['cottage_id']);
    if (!$cottage) {
        return $this->failNotFound('Cottage not found');
    }

    // Prepare data for saving
    $data = [
        'user_id' => $json['user_id'],
        'selectedTime' => $selectedTime, // Ensure UTC time is saved
        'selectedTimeout' => $selectedTimeout, // Ensure UTC time is saved
        'cottage_id' => $json['cottage_id'],
        'downpayment' => $json['downpayment'],
        'proofOfDownpayment' => $fileName,  // Save file name in DB
    ];

    if ($bookingModel->save($data) === false) {
        return $this->respond(['message' => 'Booking failed', 'errors' => $bookingModel->errors()], 500);
    }

    return $this->respond(['message' => 'Booked successfully'], 200);
}

    
    
    
    
    public function saveDate()
    {
        try {
            // Get the request data as an array
            $request = $this->request->getJSON(true);
    
            // Check if swimming_date is provided
            if (!isset($request['swimming_date'])) {
                return $this->respond(["message" => "swimming_date is required"], 400);
            }
    
            // Prepare the data for insertion
            $data = [
                'swimming_date' => $request['swimming_date'],
            ];
    
            // Log the data to see what is being inserted
            log_message('debug', 'Data to insert: ' . print_r($data, true));
    
            // Create a new instance of the DateModel
            $dateModel = new DateModel();
    
            // Attempt to insert the data
            if ($dateModel->insert($data)) {
                return $this->respond(["message" => "Date added successfully"], 200);
            } else {
                // Log validation errors if the insert fails
                log_message('error', 'Failed to insert data: ' . print_r($dateModel->errors(), true));
                return $this->respond(["message" => "Failed to add date: " . json_encode($dateModel->errors())], 500);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception occurred: ' . $e->getMessage());
            return $this->respond(["message" => "Failed to add date: " . $e->getMessage()], 500);
        }
    }
    
    
    
    
    
    
    public function store()
    {
        $json = $this->request->getJSON();

        $data = [
            'id' => $json->id,
            'eventName' => $json->eventName,
            'eventTheme' => $json->eventTheme,
            'eventDate' => $json->eventDate,
            'eventStatus' => 'pending',

        ];

        $event = new EventModel();
        $r = $event->save($data);

        if ($r) {
            // Enrollment successful, return a success response with a message
            return $this->respond(['message' => 'Event Booking successful'], 200);
        } else {
            // Enrollment failed, return an error response with a message
            return $this->respond(['message' => 'Failed to book'], 500);
        }
    }
    public function enroll()
    {
        $json = $this->request->getJSON();

        $data = [
            'id' => $json->id,
            'fullname' => $json->fullName,
            'contact_number' => $json->contact_number,
            'age' => $json->age,
            'enrollment_status' => 'pending',
            'experience' => $json->experience,
            'lesson_date' => $json->lesson_date,
        ];

        $enrollment = new EnrollmentModel();
        $r = $enrollment->save($data);

        if ($r) {
            // Enrollment successful, return a success response with a message
            return $this->respond(['message' => 'Enrollment successful'], 200);
        } else {
            // Enrollment failed, return an error response with a message
            return $this->respond(['message' => 'Failed to enroll'], 500);
        }
    }









    public function index()
    {
        //
    }

    public function deleteFeedback($feedId)
    {
        $feedbackModel = new FeedbackModel();

        try {
            $feedbackModel->update($feedId, ['is_hidden' => 1]);
            return $this->respond(['message' => 'Feedback hidden successfully'], 200);
        } catch (\Exception $e) {
            return $this->respond(['error' => 'Failed to hide feedback: ' . $e->getMessage()], 500);
        }
    }
    public function hideStaff($staffId)
    {
        $staffbackModel = new StaffModel();

        try {
            $staffbackModel->update($staffId, ['hide_staff' => 1]);
            return $this->respond(['message' => 'Staff hidden successfully'], 200);
        } catch (\Exception $e) {
            return $this->respond(['error' => 'Failed to hide staff: ' . $e->getMessage()], 500);
        }
    }
    public function getAuditHistory($shopId)
    {
        $shopModel = new ShopModel();

        $shopData = $shopModel->find($shopId);

        if (!$shopData) {
            return $this->respond(['message' => 'Shop not found'], 404);
        }

        $auditModel = new AuditModel();

        // Get the 'type' query parameter from the request
        $type = $this->request->getGet('type');

        // Define a condition based on the 'type' parameter
        $condition = [];
        if ($type && $type !== 'all') {
            $condition['type'] = $type;
        }

        $auditHistory = $auditModel
            ->where('shop_id', $shopId)
            ->where($condition)
            ->findAll();

        return $this->respond($auditHistory, 200);
    }

    public function updateProduct($id)
    {
        $data = $this->request->getJSON();
        $model = new ShopModel();
        $model->update($id, $data);

        return $this->respond(['status' => 'success', 'message' => 'Product updated successfully']);
    }
    public function updateQuantity($id)
    {
        $quantity = (int) $this->request->getVar('quantity');

        $shopModel = new ShopModel();
        $product = $shopModel->find($id);

        if (!$product) {
            return $this->failNotFound('Product not found');
        }

        $newQuantity = $product['prod_quantity'] + $quantity;

        $shopModel->update($id, ['prod_quantity' => $newQuantity]);

        $auditModel = new AuditModel();
        $data = [
            'shop_id' => $product['shop_id'],
            'old_quantity' => $product['prod_quantity'],
            'new_quantity' => $newQuantity,
            'type' => 'inbound',
        ];
        $auditModel->save($data);

        return $this->respond(['new_quantity' => $newQuantity, 'message' => 'Quantity updated successfully']);
    }

    public function getStaff()
    {
        $staff = new StaffModel();
        $data = $staff->where('hide_staff', 0)->findAll();

        return $this->respond($data, 200);
    }
    public function getInvoice()
    {
        $invoice = new InvoiceModel();
        $data = $invoice->findAll();
        return $this->respond($data, 200);
    }
    public function getAvailableDates()
    {
        $date = new DateModel();
        $data = $date->findAll();
        return $this->respond($data, 200);
    }
    public function getManifest()
    {
        $manifest = new ManifestModel();
        $data = $manifest->findAll();
        return $this->respond($data, 200);
    }
    public function getCottageHistory()
    {
        $booking = new CottageBookingModel();
        $data = $booking->select('cottagebooking.*, user.name AS user_name, user.id AS user_id')
                        ->join('user', 'user.id = cottagebooking.user_id')
                        ->findAll();
        
        return $this->respond($data, 200);
    }
    
    public function getReservationHistory()
    {
        $booking = new ReservationModel();
        // Join with the 'users' table to replace user IDs with names
        $data = $booking->select('reservations.*, user.name AS user_name')
                        ->join('user', 'user.id = reservations.user_id')
                        ->findAll();
        
        return $this->respond($data, 200);
    }
    
    public function getRoomBookingHistory()
    {
        $booking = new BookingModel();
        $data = $booking->select('booking.*, user.name AS user_name, user.id AS user_id')
                        ->join('user', 'user.id = booking.user_id') // Ensure 'user_id' matches actual field name
                        ->findAll();
    
        return $this->respond($data, 200);
    }
    
    
    public function getBookingHistory()
    {
        $booking = new BookingModel();
        $data = $booking->findAll();
        return $this->respond($data, 200);
    }
    public function getCart($id)
    {
        $cart = new CartModel();
        $data = $cart->where('id', $id)->findAll();
        return $this->respond($data, 200);
    }
    public function getTable()
    {
        $table = new TableModel();
        $data = $table->findAll();
        return $this->respond($data, 200);
    }
    public function getItem()
    {
        $item = new MenuModel();
        $data = $item->findAll();
        return $this->respond($data, 200);
    }
    public function getCottage()
    {
        $cottage = new CottageModel();
        $data = $cottage->findAll();
        return $this->respond($data, 200);
    }
    public function getPool()
    {
        $pool = new PoolModel();
        $data = $pool->findAll();
        return $this->respond($data, 200);
    }
    public function getShop()
    {
        $shop = new ShopModel();
        $data = $shop->where('prod_quantity >', 0)->findAll();
        return $this->respond($data, 200);
    }

    public function getFeedback()
    {
        $f = new FeedbackModel();

        $data = $f->where('is_hidden', 0)->findAll();

        return $this->respond($data, 200);
    }
  

    public function getRoomByTotalPax($targetPacks)
    {
        $roomModel = new RoomModel();
        $allRooms = $roomModel->findAll();
    
        $totalPacks = array_sum(array_column($allRooms, 'packs'));
    
        if ($targetPacks > $totalPacks) {
            return $this->respond([], 200);
        }
    
        $combination = [];
        $this->findRoomCombination($allRooms, $targetPacks, 0, 0, $totalPacks, $combination);
    
        if (empty($combination)) {
            return $this->respond([], 200);
        }
    
        $roomIds = [];
        foreach ($combination as $packs) {
            $rooms = $roomModel->where('packs', $packs)
                ->findAll();
            foreach ($rooms as $room) {
                $roomIds[] = $room['room_id'];
            }
        }
    
        $filteredRooms = $roomModel->whereIn('room_id', $roomIds)->findAll();
    
        return $this->respond($filteredRooms, 200);
    }
    


    private function findRoomCombination($rooms, $targetPacks, $currentIndex, $currentPacks, $totalPacks, &$combination)
    {
        if ($currentPacks == $targetPacks) {
            return true;
        }

        if ($currentIndex >= count($rooms) || $currentPacks > $targetPacks || $currentPacks > $totalPacks) {
            return false;
        }

        for ($i = $currentIndex; $i < count($rooms); $i++) {
            $newPacks = $currentPacks + $rooms[$i]['packs'];
            if ($newPacks <= $targetPacks && $this->findRoomCombination($rooms, $targetPacks, $i + 1, $newPacks, $totalPacks, $combination)) {
                $combination[] = $rooms[$i]['packs'];
                return true;
            }
        }

        return false;
    }
    public function getAmenitiesAuditHistory($amenityId)
    {
        $amenitiesModel = new AmenitiesModel();
    
        // Check if the amenity exists
        $amenityData = $amenitiesModel->find($amenityId);
        if (!$amenityData) {
            return $this->respond(['message' => 'Amenity not found'], 404);
        }
    
        $auditModel = new AmenitiesAuditModel(); // Use the AmenitiesAuditModel
    
        // Get the 'type' query parameter from the request
        $type = $this->request->getGet('type');
    
        // Define a condition based on the 'type' parameter
        $condition = [];
        if ($type && $type !== 'all') {
            $condition['type'] = $type;
        }
    
        // Fetch audit history for the specific amenity
        $auditHistory = $auditModel
            ->where('amenity_id', $amenityId) // Adjust to filter by amenity_id
            ->where($condition)
            ->findAll();
    
        return $this->respond($auditHistory, 200);
    }
    
    public function modifyStock($id = null)
    {
        $json = $this->request->getJSON();
        
        // Validate the received data
        if (!isset($json->amount)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Missing required fields'
            ], 400);
        }
    
        $amount = $json->amount;
    
        try {
            $amenitiesModel = new AmenitiesModel();
            $existingAmenity = $amenitiesModel->find($id);
    
            if (empty($existingAmenity)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Amenity not found'
                ], 404);
            }
    
            // Update the stock amount
            $newStock = $existingAmenity['stock'] + $amount;
            $amenitiesModel->update($id, ['stock' => $newStock]);
    
            // Create audit entry
            $auditModel = new AmenitiesAuditModel();
            $auditData = [
                'amenity_id' => $id,
                'old_quantity' => $existingAmenity['stock'],
                'new_quantity' => $newStock,
                'type' => $amount > 0 ? 'inbound' : 'used', // Determine the type based on the amount
            ];
            $auditModel->save($auditData);
    
            return $this->respond([
                'status' => 'success',
                'message' => 'Stock modified successfully!',
                'new_stock' => $newStock
            ], 200);
        } catch (\Exception $e) {
            log_message('error', 'Failed to modify stock: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to modify stock: ' . $e->getMessage()
            ], 500);
        }
    }
    


    public function getAllAmenities()
    {
        $amenities = $this->amenitiesModel->findAll();
        return $this->respond($amenities, 200);
    }

    public function updateAmenity($id = null)
    {
        $request = $this->request;
        $amenitiesModel = new AmenitiesModel();
    
        // Find the existing data by ID
        $existingData = $amenitiesModel->find($id);
    
        if (empty($existingData)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Amenity not found'
            ], 404); // Return error if amenity is not found
        }
    
        // Prepare new data, using existing values if no new values are provided
        $data = [
            'amenity_id' => $id,
            'amenity_name' => $request->getVar('amenity_name') ?? $existingData['amenity_name'],
            'description' => $request->getVar('description') ?? $existingData['description'],
        ];
    
        // Check if any data has actually changed
        if ($data === array_intersect_key($existingData, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'No changes detected, data not updated'
            ], 200); // Return message if no changes were made
        }
    
        try {
            // Update the amenity record
            if ($amenitiesModel->update($id, $data)) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Amenity updated successfully!'
                ], 200); // Success response
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Failed to update amenity'
                ], 500); // Return error if update fails
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update amenity: ' . $e->getMessage()
            ], 500); // Return error if exception occurs
        }
    }
        


    public function addAmenity()
{
    $json = $this->request->getJSON();

    // Validate the received data
    if (!isset($json->amenity_name) || !isset($json->description)) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Missing required fields'
        ], 400);
    }

    $data = [
        'amenity_name' => $json->amenity_name,
        'description' => $json->description,
    ];

    try {
        $amenitiesModel = new AmenitiesModel(); // Ensure this model is correctly initialized
        if (!$amenitiesModel->save($data)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to add amenity',
                'errors' => $amenitiesModel->errors()
            ], 400);
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Amenity added successfully!'
        ], 200);
    } catch (\Exception $e) {
        log_message('error', 'Failed to add amenity: ' . $e->getMessage());
        return $this->respond([
            'status' => 'error',
            'message' => 'Failed to add amenity: ' . $e->getMessage()
        ], 500);
    }
}
public function deleteAmenity($id = null)
{
    if ($id === null) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Amenity ID is required'
        ], 400); // Return error if ID is not provided
    }

    $amenitiesModel = new AmenitiesModel();

    // Check if the amenity exists
    $existingAmenity = $amenitiesModel->find($id);
    if (empty($existingAmenity)) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Amenity not found'
        ], 404); // Return error if amenity is not found
    }

    try {
        // Attempt to delete the amenity
        if ($amenitiesModel->delete($id)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Amenity deleted successfully!'
            ], 200); // Success response
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to delete amenity'
            ], 500); // Return error if delete fails
        }
    } catch (\Exception $e) {
        log_message('error', 'Failed to delete amenity: ' . $e->getMessage());
        return $this->respond([
            'status' => 'error',
            'message' => 'Failed to delete amenity: ' . $e->getMessage()
        ], 500); // Return error if exception occurs
    }
}



  // Cancel Cottage Booking
  public function cancelCottageBooking($id)
  {
      $model = new CottageBookingModel();
      $booking = $model->find($id);
      
      if ($booking) {
          $model->update($id, ['cottagebooking_status' => 'cancelled']);
          return $this->respond(['message' => 'Cottage booking status updated to cancelled'], 200);
      }
      return $this->failNotFound('Cottage booking not found');
  }

  // Cancel Reservation
  public function cancelReservation($id)
  {
      $model = new ReservationModel();
      $reservation = $model->find($id);
      
      if ($reservation) {
          $model->update($id, ['status' => 'cancelled']);
          return $this->respond(['message' => 'Reservation status updated to cancelled'], 200);
      }
      return $this->failNotFound('Reservation not found');
  }

  // Cancel Order
  public function cancelOrder($id)
  {
      $model = new OrdersModel();
      $order = $model->find($id);
      
      if ($order) {
          $model->update($id, ['order_status' => 'cancelled']);
          return $this->respond(['message' => 'Order status updated to cancelled'], 200);
      }
      return $this->failNotFound('Order not found');
  }
            

    public function getRoom()
    {
        $room = new RoomModel();
        $data = $room->findAll();
        return $this->respond($data, 200);
    }


    public function del()
    {
        $json = $this->request->getJSON();
        $cart_id = $json->cart_id;
        $cart = new CartModel();
        $r = $cart->delete($cart_id);
        return $this->respond($r, 200);
    }
    public function getUserWithRelatedData($id)
    {
        $userModel = new UserModel();
        $cottageBookingModel = new CottageBookingModel();
        $reservationModel = new ReservationModel();
        $ordersModel = new OrdersModel();
        $bookingModel = new BookingModel();
        $roomModel = new RoomModel(); // Include RoomModel
        $shopModel = new ShopModel(); // Include ShopModel
    
        // Fetch user data
        $userData = $userModel->find($id);
        if (!$userData) {
            return $this->failNotFound('User not found');
        }
    
        // Fetch pending cottage bookings with room details
        $cottageBookings = $cottageBookingModel->where('user_id', $id)
            ->where('cottagebooking_status', 'pending')
            ->findAll();
    
        foreach ($cottageBookings as &$booking) {
            $booking['cottage_name'] = $this->getCottageName($booking['cottage_id']); // Assuming a method to fetch cottage name
        }
    
        // Fetch pending reservations with table details
        $reservations = $reservationModel->where('user_id', $id)
            ->where('status', 'pending')
            ->findAll();
    
        foreach ($reservations as &$reservation) {
            $reservation['table_name'] = $this->getTableName($reservation['table_id']); // Assuming a method to fetch table name
        }
    
        // Fetch pending orders with product details
        $orders = $ordersModel->where('id', $id)
            ->where('order_status', 'pending')
            ->findAll();
    
        foreach ($orders as &$order) {
            $order['products'] = $this->getOrderProducts($order['order_id']); // Assuming a method to fetch order products
        }
    
        // Fetch bookings with room details
        $bookings = $bookingModel->where('id', $id) // Assuming `id` refers to user_id
            ->where('booking_status', 'pending')
            ->findAll();
    
        foreach ($bookings as &$booking) {
            $booking['room_name'] = $this->getRoomName($booking['room_id']); // Assuming a method to fetch room name
        }
    
        // Combine the fetched data
        $combinedData = [
            'user' => $userData,
            'cottageBookings' => $cottageBookings,
            'reservations' => $reservations,
            'orders' => $orders,
            'bookings' => $bookings,
        ];
    
        // Return combined data as a response
        return $this->respond($combinedData, 200);
    }
    
    
    private function getCottageName($cottageId)
    {
        $cottageModel = new CottageModel(); // Assuming you have a CottageModel
        $cottage = $cottageModel->find($cottageId);
        return $cottage ? $cottage['cottage_name'] : 'Unknown Cottage'; // Adjust field name as necessary
    }
    
    private function getTableName($tableId)
    {
        $tableModel = new TableModel(); // Assuming you have a TableModel
        $table = $tableModel->find($tableId);
        return $table ? $table['table_name'] : 'Unknown Table'; // Adjust field name as necessary
    }
    
    private function getOrderProducts($orderId)
    {
        $orderListModel = new OrderListModel(); // Assuming you have an OrderListModel
        $products = $orderListModel->where('order_id', $orderId)->findAll();
        
        // Fetch product details for each product in the order
        $productDetails = [];
        $shopModel = new ShopModel(); // Assuming you have a ShopModel
    
        foreach ($products as $product) {
            $shopProduct = $shopModel->find($product['shop_id']);
            if ($shopProduct) {
                $productDetails[] = [
                    'prod_name' => $shopProduct['prod_name'],
                    'quantity' => $product['quantity'],
                    'final_price' => $product['final_price'],
                ];
            }
        }
    
        return $productDetails;
    }
    
    private function getRoomName($roomId)
    {
        $roomModel = new RoomModel(); // Assuming you have a RoomModel
        $room = $roomModel->find($roomId);
        return $room ? $room['room_name'] : 'Unknown Room'; // Adjust field name as necessary
    }
    public function getAmenities()
{
    $amenitiesModel = new AmenitiesModel();  // Load the AmenitiesModel
    $data = $amenitiesModel->findAll();                  // Fetch all amenities
    return $this->respond($data, 200);                   // Return the data as JSON with HTTP status 200
}

    public function getData()
    {
        $main = new UserModel();
        $data = $main->findAll();
        return $this->respond($data, 200);
    }
    public function fetchUser($id)
{
    $main = new UserModel();
    
    // Find user by ID
    $user = $main->find($id); // Fetch user data by ID

    if ($user) {
        return $this->respond($user, 200); // Respond with user data
    } else {
        return $this->respond(['message' => 'User not found'], 404); // Handle user not found
    }
}

    public function getBooking($id)
    {
        $main = new BookingModel();
        $data = $main->find($id);

        if ($data) {
            return $this->respond($data, 200);
        } else {
            return $this->failNotFound('Booking not found.');
        }
    }


    public function getBook()
    {
        $db = \Config\Database::connect();
        
        // Query to group bookings by group_id and concatenate room names
        $query = $db->table('booking')
            ->select('booking_group.group_id, user.name, GROUP_CONCAT(room.room_name SEPARATOR ", ") as room_names, booking.checkin, booking.checkout, booking.specialRequest, booking.booking_status, booking.payment_method, booking.downpayment, booking.downpaymentProof')
            ->join('booking_group_bookings', 'booking_group_bookings.book_id = booking.book_id') // Link booking with the group
            ->join('booking_group', 'booking_group_bookings.group_id = booking_group.group_id') // Link group with the user
            ->join('user', 'user.id = booking_group.user_id') // Use the user_id from booking_group
            ->join('room', 'room.room_id = booking.room_id') // Join room table to get room_name
            ->where('booking.booking_status !=', 'paid') // Exclude paid bookings
            ->where('booking.booking_status !=', 'declined') // Exclude declined bookings
            ->groupBy('booking_group.group_id, booking.checkin, booking.checkout, booking.specialRequest, booking.booking_status, booking.payment_method, booking.downpayment, booking.downpaymentProof, user.name') // Group by relevant fields
            ->get()
            ->getResultArray(); // Fetch results as an array
    
        return $this->respond($query, 200);
    }
    
    
    public function getenroll()
    {
        $enrollmentModel = new EnrollmentModel();

        $result = $enrollmentModel
            ->select('enrollment.*, user.*')
            ->join('user', 'user.id = enrollment.id') // Assuming 'id' is the foreign key in the 'enrollment' table 
            ->where('enrollment.enrollment_status !=', 'paid')
            ->where('enrollment.enrollment_status !=', 'declined')
            ->findAll();

        return $this->respond($result, 200);
    }
    public function getDate()
    {
        try {
            $dateSchedModel = new DateModel();
            $dateModel = $dateSchedModel->select('swimming_date')->findAll();
    
            // Convert dates to proper format
            $dates = array_map(function ($date) {
                return date('Y-m-d', strtotime($date['swimming_date']));
            }, $dateModel);
    
            // Log the dates before returning
            log_message('debug', 'Dates fetched from database: ' . print_r($dates, true));
    
            return $this->respond($dates, 200);
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch dates: ' . $e->getMessage());
            return $this->respond(["message" => "Failed to fetch dates"], 500);
        }
    }



    public function markAsPaidByGroup($groupId)
    {
        $roomModel = new RoomModel();
        $bookingModel = new BookingModel();
        $bookingGroupBookingsModel = new BookingGroupBookings();
    
        // Fetch all booking IDs for the given group_id
        $groupBookings = $bookingGroupBookingsModel->where('group_id', $groupId)->findAll();
    
        if (count($groupBookings) > 0) {
            $bookingIds = [];
            $roomIds = [];
    
            foreach ($groupBookings as $groupBooking) {
                $booking = $bookingModel->find($groupBooking['book_id']);
                if ($booking) {
                    $bookingIds[] = $booking['book_id'];
                    $roomIds[] = $booking['room_id'];
                }
            }
    
            // Update bookings and rooms
            $bookingModel->update($bookingIds, ['booking_status' => 'paid']);
            $roomModel->update($roomIds, ['room_status' => 'Available']);
    
            return $this->respond(['message' => 'All bookings in group ' . $groupId . ' updated to "Paid" and rooms status updated to "Available"'], 200);
        } else {
            return $this->respond(['message' => 'No bookings found for group ' . $groupId], 404);
        }
    }
    
    
    public function markthisPaid($enroll_id)
    {
        $enrollmentModel = new EnrollmentModel();

        $enroll = $enrollmentModel->find($enroll_id);

        if ($enroll) {
            // Update the enrollment status to 'paid'
            $updatedEnroll = $enrollmentModel->update($enroll_id, ['enrollment_status' => 'paid']);

            if ($updatedEnroll) {
                // Return the response
                return $this->respond(['message' => 'Enrollment marked as paid successfully', 'enroll_id' => $enroll_id], 200);
            } else {
                return $this->respond(['message' => 'Failed to update enrollment status'], 500);
            }
        } else {
            return $this->respond(['message' => 'Enrollment not found'], 404);
        }
    }
    public function booking()
    {
        $json = $this->request->getPost();
    
        // Check if 'id' is present in the request
        if (!isset($json['id']) || empty($json['id'])) {
            return $this->respond(['message' => 'User ID is required'], 400);
        }
    
        $userId = $json['id'];
    
        // Log the received User ID for debugging
        log_message('info', 'Received User ID: ' . $userId);
    
        // Check if the user exists
        $userExists = $this->db->table('user')->where('id', $userId)->countAllResults();
        if ($userExists == 0) {
            log_message('error', 'Invalid User ID: ' . $userId);
            return $this->respond(['message' => 'Invalid User ID'], 400);
        }
    
        // Start transaction
        $this->db->transBegin();
    
        try {
            // Insert into booking_group table to create a new group for the booking
            $bookingGroupData = [
                'user_id' => $userId
            ];
            $this->db->table('booking_group')->insert($bookingGroupData);
            $group_id = $this->db->insertID(); // Get the newly created group_id
    
            // Log the creation of the booking group
            log_message('info', 'Created booking group with group_id: ' . $group_id);
    
            // Proceed to handle booking details
            $room_ids = $this->request->getPost('room_ids'); // Get room IDs as an array
            foreach ($room_ids as $room_id) {
                $this->room = new RoomModel();
                $booked = $this->room->where(['room_id' => $room_id])->first();
    
                if (!$booked) {
                    // Rollback and return error if any room is not found
                    $this->db->transRollback();
                    return $this->respond(['message' => 'Room not found'], 404);
                }
    
                // Define the upload path relative to the public directory
                $uploadPath = FCPATH . 'backend/public/uploads/';
    
                // Handle file upload
                $file = $this->request->getFile('downpaymentProof');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $filePath = $uploadPath . $newName;
                    $file->move($uploadPath, $newName);
                }
    
                // Prepare booking data
                $bookingData = [
                    'id' => $userId,
                    'checkin' => $json['checkin'],
                    'checkout' => $json['checkout'],
                    'specialRequest' => $json['specialRequest'],
                    'room_id' => $room_id,
                    'booking_status' => 'pending', // Set initial status
                    'payment_method' => $json['payment_method'],
                    'downpayment' => $json['downpayment'],
                    'downpaymentProof' => $newName ?? null, // If file uploaded
                ];
    
                // Insert booking record into booking table
                $this->db->table('booking')->insert($bookingData);
                $book_id = $this->db->insertID(); // Get the inserted book_id
    
                // Insert into booking_group_bookings to link book_id with the group
                $bookingGroupBookingsData = [
                    'group_id' => $group_id,
                    'book_id' => $book_id
                ];
                $this->db->table('booking_group_bookings')->insert($bookingGroupBookingsData);
    
                // Log the successful booking
                log_message('info', 'Inserted booking with book_id: ' . $book_id . ' into group_id: ' . $group_id);
            }
    
            // Commit transaction if all is good
            $this->db->transCommit();
    
            return $this->respond(['message' => 'Booking successful', 'group_id' => $group_id], 200);
        } catch (\Exception $e) {
            // Rollback transaction if any error occurs
            $this->db->transRollback();
            log_message('error', 'Error in booking process: ' . $e->getMessage());
            return $this->respond(['message' => 'Booking failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    
    
    
    

    public function getDataShop()
    {
        $main = new ShopModel();
        $data = $main->findAll();
        return $this->respond($data, 200);
    }

    public function save()
    {
        $json = $this->request->getJSON();
        $data = [
            'feedback' => $json->feedback,
            'id' => $json->id,
            'is_hidden' => 0,

        ];

        $feedback = new FeedbackModel();
        $result = $feedback->save($data);

        return $this->respond(['message' => 'Feedback submitted successfully', $result], 200);
    }

    public function manifest()
    {
        $json = $this->request->getJSON();
        $data = [
            'manifest' => $json->manifest,
            'id' => $json->id,
        ];

        $manifest = new ManifestModel();
        $result = $manifest->save($data);

        return $this->respond(['message' => 'Manifest submitted successfully', $result], 200);
    }
    public function register() 
    { 
        $json = $this->request->getJSON(); 
        $email = $json->email; 
    
        // Validate email format 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
            return $this->respond(["error" => "Invalid email format"], 400); 
        } 
    
        // Check if email already exists 
        $userModel = new UserModel(); 
        $exUser = $userModel->where('email', $email)->first(); 
        if ($exUser) { 
            return $this->respond(["error" => "Email already exists"], 400); 
        } 
    
        // Validate password format 
        $password = $json->password; 
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) { 
            return $this->respond(["error" => "Password must contain at least one letter, one number, and one special character"], 400); 
        } 
    
        // Generate verification token and expiry 
        $verificationToken = bin2hex(random_bytes(32)); 
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour')); 
    
        // Prepare data to save 
        $data = [ 
            'name' => $json->name, 
            'email' => $email, 
            'password' => password_hash($password, PASSWORD_BCRYPT), 
            'address' => $json->address, // Add address field 
            'number' => $json->number,   // Add number field 
            'token' => $this->verification(50), // Generate the token 
            'status' => 'inactive', 
            'role' => 'user', 
            'ver_token' => $verificationToken, 
            'expiry' => $tokenExpiry, 
        ]; 
    
        // Save user data 
        $u = $userModel->save($data); 
    
        // Send verification email 
        $emailController = new \App\Controllers\EmailController(); 
        $emailController->sendVerificationEmail($data); 
    
        // Respond based on the result of user registration 
        if ($u) { 
            return $this->respond(['msg' => 'Registered Successfully', 'token' => $data['token']]); 
        } else { 
            return $this->respond(['msg' => 'Error occurred']); 
        } 
    }
    
    

    public function verification($length)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_-/*$#@.,<>=%!()?{}[]';
        return substr(str_shuffle($str_result), 0, $length);
    }

    public function login()
    {
        $json = $this->request->getJSON();
    
        if (isset($json->email) && isset($json->password)) {
            $email = $json->email;
            $password = $json->password;
    
            $userModel = new UserModel();
            $data = $userModel->where('email', $email)->first();
    
            if ($data) {
                // Check if the user is active
                if ($data['status'] !== 'active') {
                    return $this->respond(['message' => 'Your account is not active. Please contact support.'], 403);
                }
    
                $pass = $data['password'];
                $auth = password_verify($password, $pass);
    
                if ($auth) {
                    return $this->respond([
                        'message' => 'Login successful',
                        'token' => $data['token'],
                        'id' => $data['id'],
                        'role' => $data['role'],
                    ], 200);
                } else {
                    return $this->respond(['message' => 'Invalid email or password'], 401);
                }
            } else {
                return $this->respond(['message' => 'Invalid email or password'], 401);
            }
        } else {
            return $this->respond(['message' => 'Invalid JSON data'], 400);
        }
    }
    

    public function submitRating()
    {
        $json = $this->request->getJSON();
        $shop_id = $json->shop_id;
        $rating = $json->rating;

        $shopModel = new ShopModel();

        $shopData = $shopModel->find($shop_id);

        if (!$shopData) {
            return $this->respond(['error' => 'Shop not found'], 404);
        }

        $newRatingsCount = $shopData['ratings_count'] + 1;
        $newTotalRatings = $shopData['total_ratings'] + $rating;

        $newRating = $newTotalRatings / $newRatingsCount;

        $updateResult = $shopModel->update($shop_id, [
            'rating' => $newRating,
            'ratings_count' => $newRatingsCount,
            'total_ratings' => $newTotalRatings,
        ]);

        if ($updateResult) {
            return $this->respond(['success' => true, 'newRating' => $newRating]);
        } else {
            return $this->respond(['error' => 'Failed to update rating'], 500);
        }
    }

    public function logout()
    {
        session()->destroy();
        return $this->response->setStatusCode(200)->setJSON(['message' => 'Logout successful']);
    }
    public function Cart()
    {
        $cart = new CartModel();
        $json = $this->request->getJSON();

        $shop_id = $json->shop_id;
        $user = $json->id;
        $quantity = $json->quantity;
        $shopModel = new ShopModel();
        $product = $shopModel->find($shop_id);

        if (!$product) {
            return $this->respond(['message' => 'Product not found'], 404);
        }

        if ($product['prod_quantity'] < $quantity) {
            return $this->respond(['message' => 'Insufficient stock quantity'], 400);
        }

        $existing = $cart->where(['id' => $user, 'shop_id' => $shop_id])->first();

        if ($existing) {
            $existing['quantity'] += $quantity;
            $updateResult = $cart->update($existing['cart_id'], $existing);

            if ($updateResult) {
                // $newQuantity = $product['prod_quantity'] - $quantity;
                // $shopModel->update($shop_id, ['prod_quantity' => $newQuantity]);

                return $this->respond(['message' => 'Item quantity updated in the cart'], 200);
            } else {
                return $this->respond(['message' => 'Failed to update item quantity in the cart'], 500);
            }
        } else {
            $data = [
                'id' => $user,
                'shop_id' => $shop_id,
                'quantity' => $quantity,
            ];

            $addcart = $cart->save($data);

            if ($addcart) {
                // $newQuantity = $product['prod_quantity'] - $quantity;
                // $shopModel->update($shop_id, ['prod_quantity' => $newQuantity]);

                return $this->respond(['message' => 'Item added to cart successfully'], 200);
            } else {
                return $this->respond(['message' => 'Failed to add item to cart'], 500);
            }
        }
    }

    public function updateCartQuantity()
    {
        $json = $this->request->getJSON();

        $cart_id = $json->cart_id;
        $quantity = $json->quantity;

        $cart = new CartModel();
        $existing = $cart->find($cart_id);

        if ($existing) {
            $existing['quantity'] = $quantity;
            $updateResult = $cart->update($cart_id, $existing);

            if ($updateResult) {
                return $this->respond(['message' => 'Quantity updated successfully'], 200);
            } else {
                return $this->respond(['message' => 'Failed to update quantity'], 500);
            }
        } else {
            return $this->respond(['message' => 'Cart item not found'], 404);
        }
    }

    public function checkout() {
        $this->invoice = new InvoiceModel();
        $this->orderitems = new OrderListModel();
        $this->orders = new OrderSModel();
    
        // Accessing the uploaded file
        $downpaymentProof = $this->request->getFile('downpayment_proof');
        $downpaymentProofPath = null;
    
        if ($downpaymentProof && $downpaymentProof->isValid() && !$downpaymentProof->hasMoved()) {
            // Generate a unique filename to avoid overwriting existing files
            $newFileName = $downpaymentProof->getRandomName(); // Generate a random name for the file
            $downpaymentProof->move('backend/public/uploads/', $newFileName); // Move the file to the uploads directory
    
            // Store only the file name in the variable
            $downpaymentProofPath = $newFileName; // Store only the filename for the database
        }
    
        // Accessing the JSON data
        $orderData = $this->request->getPost('orderData');
        if ($orderData) {
            $json = json_decode($orderData);
            $id = $json->id;
    
            // Validate items
            foreach ($json->items as $item) {
                $shopModel = new ShopModel();
                $product = $shopModel->find($item->shop_id);
    
                if (!$product || $product['prod_quantity'] < $item->quantity) {
                    return $this->respond(['message' => 'Insufficient stock for one or more items'], 400);
                }
            }
    
            // Process the order
            $order = [
                'id' => $id,
                'order_status' => 'pending',
                'total_price' => $json->total_price,
                'order_payment_method' => $json->order_payment_method,
                'downpayment_proof' => $downpaymentProofPath, // Store only the filename in the database
            ];
    
            // Save the order
            $this->orders->save($order);
            $order_id = $this->orders->insertID();
    
            foreach ($json->items as $item) {
                $orderitem = [
                    'id' => $id,
                    'shop_id' => $item->shop_id,
                    'quantity' => $item->quantity,
                    'final_price' => $item->total_price,
                    'order_id' => $order_id,
                ];
    
                $this->orderitems->save($orderitem);
            }
    
            $inv = [
                'id' => $id,
                'order_id' => $order_id,
            ];
    
            $this->invoice->save($inv);
    
            // Check if the operations were successful
            if ($this->orders->affectedRows() > 0 && $this->orderitems->affectedRows() > 0 && $this->invoice->affectedRows() > 0) {
                return $this->respond(['message' => 'Checkout successful'], 200);
            } else {
                return $this->respond(['message' => 'Checkout failed'], 500);
            }
        } else {
            return $this->respond(['message' => 'Invalid order data'], 400);
        }
    }
    
    
    

    public function markOrderPaid($orderId)
    {
        // Ensure the model is properly loaded
        $ordersModel = new OrdersModel();
        $shopModel = new ShopModel();
        $this->orderitems = new OrderListModel();
    
        // Fetch the order by ID
        $order = $ordersModel->find($orderId);
    
        if ($order && $order['order_status'] === 'confirmed') {
            $ordersModel->update($orderId, ['order_status' => 'paid']);
    
            // Fetch items related to the order
            $orderItems = $this->orderitems->where('order_id', $orderId)->findAll();
    
            foreach ($orderItems as $item) {
                $product = $shopModel->find($item['shop_id']);
    
                if ($product) {
                    $newQuantity = $product['prod_quantity'] - $item['quantity'];
                    $shopModel->update($item['shop_id'], ['prod_quantity' => $newQuantity]);
    
                    // Log the change in the audit table
                    $auditModel = new AuditModel();
                    $auditData = [
                        'shop_id' => $product['shop_id'],
                        'old_quantity' => $product['prod_quantity'],
                        'new_quantity' => $newQuantity,
                        'type' => 'sold',
                    ];
                    $auditModel->save($auditData);
                }
            }
    
            // Return a success message with a 200 status
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Order marked as paid successfully']);
        } else {
            // Return an error message with a 400 status
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid order or order is not confirmed']);
        }
    }
    
    public function declineOrder($orderId)
    {
        $ordersModel = new OrdersModel();
        $notificationModel = new NotificationModel();
    
        // Find the order
        $order = $ordersModel->find($orderId);
    
        if ($order && $order['order_status'] === 'pending') {
            // Update order status
            $ordersModel->update($orderId, ['order_status' => 'declined']);
    
            // Add a decline notification
            $notificationData = [
                'id' => $order['id'],
                'message' => 'Your order has been declined.'
            ];
            $notificationModel->insert($notificationData);
    
            // Return a success response
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Order declined successfully']);
        } else {
            // Return an error response
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid order or order is not pending']);
        }
    }
    
    public function confirmOrder($orderId)
    {
        $ordersModel = new OrdersModel();
        $notificationModel = new NotificationModel();
    
        // Find the order
        $order = $ordersModel->find($orderId);
    
        if ($order && $order['order_status'] === 'pending') {
            // Update order status to confirmed
            $ordersModel->update($orderId, ['order_status' => 'confirmed']);
    
            // Add a confirmation notification
            $notificationData = [
                'id' => $order['id'],
                'message' => 'Your order has been accepted.'
            ];
            $notificationModel->insert($notificationData);
    
            // Return a success response
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Order accepted successfully']);
        } else {
            // Return an error response
            return $this->response->setStatusCode(400)->setJSON(['message' => 'Invalid order or order is not pending']);
        }
    }


    public function saveShop()
    {
        $request = $this->request;
    
        // Extract data from the request
        $data = [
            'prod_name' => $request->getPost('prod_name'),
            'prod_quantity' => $request->getPost('prod_quantity'),
            'prod_desc' => $request->getPost('prod_desc'),
            'prod_price' => $request->getPost('prod_price'),
        ];
    
        // Check if the file is uploaded and is valid
        if ($request->getFile('prod_image') && $request->getFile('prod_image')->isValid()) {
            $image = $request->getFile('prod_image');
            $imageName = $this->generateUniqueFileName($image->getName());
            
            // Move the uploaded file to the desired directory
            $data['prod_img'] = $this->handleImageUpload($image, $imageName);
        }
    
        $shopModel = new ShopModel();
    
        try {
            $shopModel->insert($data);
            return $this->respond(["message" => "Data saved successfully"], 200);
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to save data: " . $e->getMessage()], 500);
        }
    }
    
    public function handleImageUpload($image, $imageName)
    {
        $uploadPath = 'backend/public/uploads';
        $image->move($uploadPath, $imageName);
        return $imageName;
    }
    
    public function generateUniqueFileName($fileName)
    {
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '.' . $fileExtension;
        return $uniqueName;
    }
    
        public function saveRoom()
{
    $request = $this->request;

    $data = [
        'room_name' => $request->getPost('room_name'),
        'price' => $request->getPost('price'),
        'bed' => $request->getPost('bed'),
        'bath' => $request->getPost('bath'),
        'description' => $request->getPost('description'),
    ];

    if ($request->getFile('image')->isValid()) {
        $image = $request->getFile('image');

        $imageName = $image->getName();

        $data['image'] = $this->handleRoomImageUpload($image, $imageName);
    }

    $roomModel = new RoomModel(); // Assuming you have a RoomModel

    try {
        $roomModel->insert($data);
        return $this->respond(["message" => "Room data saved successfully"], 200);
    } catch (\Exception $e) {
        return $this->respond(["message" => "Failed to save room data: " . $e->getMessage()], 500);
    }
}
public function handleRoomImageUpload($image, $imageName)
{
    $uploadPath = 'uploads';

    $image->move($uploadPath, $imageName);
                return  $imageName;
}
public function updateShop($shop_id = null)
{
    $request = $this->request;

    $shopModel = new ShopModel();
    $existingData = $shopModel->find($shop_id);

    if (empty($existingData)) {
        return $this->respond(["message" => "Record not found"], 404);
    }

    $data = [
        'prod_name' => $request->getPost('prod_name') ?? $existingData['prod_name'],
        'prod_desc' => $request->getPost('prod_desc') ?? $existingData['prod_desc'],
        'prod_price' => $request->getPost('prod_price') ?? $existingData['prod_price'],
    ];

    // Check for uploaded image
    $image = $request->getFile('prod_img');
    if ($image && $image->isValid()) {
        // Generate a unique name for the image
        $imageName = $this->generateUniqueImageName($image->getName());
        
        // Move the uploaded file to the desired directory
        $image->move(FCPATH . 'backend/public/uploads/', $imageName);
        
        // Add the new image name to the data array
        $data['prod_img'] = $imageName;

        // Optionally delete the old image from the server
        $this->deleteOldShopImage($existingData['prod_img']);
    }

    try {
        // Update the shop information
        if ($data !== array_intersect_key($existingData, $data)) {
            $shopModel->update($shop_id, $data);
            return $this->respond(["message" => "Data updated successfully"], 200);
        } else {
            return $this->respond(["message" => "No changes detected, data not updated"], 200);
        }
    } catch (\Exception $e) {
        return $this->respond(["message" => "Failed to update data: " . $e->getMessage()], 500);
    }
}

// Function to generate a unique name for the uploaded image
private function generateUniqueImageName($originalName)
{
    return uniqid() . '_' . pathinfo($originalName, PATHINFO_FILENAME) . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
}

// Function to delete the old image from the server
private function deleteOldShopImage($imageName)
{
    $filePath = FCPATH . 'uploads/' . $imageName;
    if (is_file($filePath)) {
        unlink($filePath); // Delete the file
    }
}

    public function updateRoom($room_id = null)
    {
        $request = $this->request;

        $roomModel = new RoomModel();
        $existingData = $roomModel->find($room_id);

        if (empty($existingData)) {
            return $this->respond(["message" => "Record not found"], 404);
        }

        $data = [
            'room_name' => $request->getVar('room_name') ?? $existingData['room_name'],
            'price' => $request->getVar('price') ?? $existingData['price'],
            // 'downpayment' => $request->getVar('downpayment') ?? $existingData['downpayment'],
            'description' => $request->getVar('description') ?? $existingData['description'],
            'room_status' => $request->getVar('room_status') ?? $existingData['room_status'],
        ];

        try {
            if ($data !== array_intersect_key($existingData, $data)) {
                $roomModel->update($room_id, $data);
                return $this->respond(["message" => "Data updated successfully"], 200);
            } else {
                return $this->respond(["message" => "No changes detected, data not updated"], 200);
            }
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to update data: " . $e->getMessage()], 500);
        }
    }

    public function saveStaff()
    {
        $request = $this->request;
    
        $data = [
            'staff_name' => $request->getPost('staff_name'),
            'staff_email' => $request->getPost('staff_email'),
            'contactNum' => $request->getPost('contactNum'),
            'hide_staff' => 0,
    
        ];
    
        if ($request->getFile('staff_image')->isValid()) {
            $image = $request->getFile('staff_image');
    
            $imageName = $image->getName();
    
            $data['staff_image'] = $this->handleStaffImageUpload($image, $imageName);
        }
    
        $staff = new StaffModel();
    
        try {
            $staff->insert($data);
            return $this->respond(["message" => "Data saved successfully"], 200);
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to save data: " . $e->getMessage()], 500);
        }
    }
    
    public function handleStaffImageUpload($image, $imageName)
    {
        $uploadPath = 'backend/public/uploads';
    
        $image->move($uploadPath, $imageName);
                    return  $imageName;
    }



    public function updateStaff($staff_id = null)
    {
        $request = $this->request;

        $staffModel = new StaffModel();
        $existingData = $staffModel->find($staff_id);

        if (empty($existingData)) {
            return $this->respond(["message" => "Record not found"], 404);
        }

        $data = [
            'staff_name' => $request->getVar('staff_name') ?? $existingData['staff_name'],
            'staff_email' => $request->getVar('staff_email') ?? $existingData['staff_email'],
            'contactNum' => $request->getVar('contactNum') ?? $existingData['contactNum'],
        ];

        try {
            if ($data !== array_intersect_key($existingData, $data)) {
                $staffModel->update($staff_id, $data);
                return $this->respond(["message" => "Data updated successfully"], 200);
            } else {
                return $this->respond(["message" => "No changes detected, data not updated"], 200);
            }
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to update data: " . $e->getMessage()], 500);
        }
    }


    public function deleteStaff($staff_id = null)
    {
        $staffModel = new StaffModel();
        $existingData = $staffModel->find($staff_id);

        if (empty($existingData)) {
            return $this->respond(["message" => "Record not found"], 404);
        }

        try {
            $staffModel->delete($staff_id);
            return $this->respond(["message" => "Data deleted successfully"], 200);
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to delete data: " . $e->getMessage()], 500);
        }
    }

    public function deleteRoom($room_id = null)
    {
        $RoomModel = new RoomModel();
        $existingData = $RoomModel->find($room_id);

        if (empty($existingData)) {
            return $this->respond(["message" => "Record not found"], 404);
        }

        try {
            $RoomModel->delete($room_id);
            return $this->respond(["message" => "Data deleted successfully"], 200);
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to delete data: " . $e->getMessage()], 500);
        }
    }
    public function deleteShop($shop_id = null)
    {
        $ShopModel = new ShopModel();
        $existingData = $ShopModel->find($shop_id);

        if (empty($existingData)) {
            return $this->respond(["message" => "Record not found"], 404);
        }

        try {
            $ShopModel->delete($shop_id);
            return $this->respond(["message" => "Data deleted successfully"], 200);
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to delete data: " . $e->getMessage()], 500);
        }
    }

    public function resetPassword()
    {
        $email = $this->request->getJSON()->email;

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->respond(['message' => 'Invalid email'], 404);
        }

        $newPassword = bin2hex(random_bytes(8));

        $userModel->set('password', password_hash($newPassword, PASSWORD_DEFAULT));
        $userModel->where('email', $email);
        $userModel->update();


        return $this->respond(['message' => 'Password reset successful', 'newPassword' => $newPassword]);
    }

    public function updatePassword()
    {
        $jsonData = $this->request->getJSON(true);
        $email = $jsonData['email'];
        $newPassword = $jsonData['newPassword'];

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->respond(['message' => 'Invalid email'], 404);
        }

        // Use the set method to set the password column
        $userModel->set('password', password_hash($newPassword, PASSWORD_DEFAULT));

        $userModel->where('email', $email);

        $userModel->update();

        return $this->respond(['message' => 'Password updated successfully']);
    }



    public function search($query)
    {
        $roomModel = new RoomModel();

        $filteredData = $roomModel->searchInRoom($query);


        return json_encode($filteredData);
    }

    public function getInvoices($invoice_id)
    {
        $shopModel = new ShopModel();
        $orderListModel = new OrderListModel();
        $ordersModel = new OrdersModel();
        $invoiceModel = new InvoiceModel();
        $userModel = new UserModel();

        $result = $shopModel
            ->select('shop.*, order_list.*, orders.*, invoices.*, user.*')
            ->join('order_list', 'order_list.shop_id = shop.shop_id')
            ->join('orders', 'orders.order_id = order_list.order_id')
            ->join('invoices', 'invoices.order_id = orders.order_id')
            ->join('user', 'user.id = orders.id')
            ->where('invoices.invoice_id', $invoice_id)
            ->findAll();

        return $this->respond($result, 200);
    }
    public function getUserOrders()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
    
        // Select additional field downpayment_proof
        $builder->select('orders.order_id, user.name AS user_name, orders.total_price, orders.order_status, orders.downpayment_proof, GROUP_CONCAT(CONCAT(shop.prod_name, " (x", order_list.quantity, ")") SEPARATOR ", ") AS items');
    
        $builder->join('user', 'user.id = orders.id', 'left');
        $builder->join('order_list', 'order_list.order_id = orders.order_id', 'left');
        $builder->join('shop', 'shop.shop_id = order_list.shop_id', 'left');
    
        $builder->whereNotIn('orders.order_status', ['paid', 'declined']);
    
        $builder->groupBy('orders.order_id');
        $builder->orderBy('orders.created_at', 'DESC');
    
        $query = $builder->get();
        $data['orders'] = $query->getResultArray();
    
        return $this->response->setJSON($data);
    }
    
    
    
    
    
    



    public function checkoutpos()
    {
        $this->invoice = new InvoiceModel();
        $this->orderitems = new OrderListModel();
        $this->orders = new OrderSModel();
        $this->cartModel = new CartModel(); // Add CartModel

        $json = $this->request->getJSON();
        $id = $json->id;

        foreach ($json->items as $item) {
            $shopModel = new ShopModel();
            $product = $shopModel->find($item->shop_id);

            if (!$product || $product['prod_quantity'] < $item->quantity) {
                return $this->respond(['message' => 'Insufficient stock for one or more items'], 400);
            }
        }

        $order = [
            'id' => $id,
            'order_status' => 'paid',
            'total_price' => $json->total_price,
        ];

        $this->orders->save($order);

        $order_id = $this->orders->insertID();

        foreach ($json->items as $item) {
            $orderitem = [
                'id' => $id,
                'shop_id' => $item->shop_id,
                'quantity' => $item->quantity,
                'total_price' => $item->total_price,
                'order_id' => $order_id,
            ];

            $this->orderitems->save($orderitem);

            $product = $shopModel->find($item->shop_id);

            if ($product) {
                $newQuantity = $product['prod_quantity'] - $item->quantity;
                $shopModel->update($item->shop_id, ['prod_quantity' => $newQuantity]);

                $auditModel = new AuditModel();
                $auditData = [
                    'shop_id' => $product['shop_id'],
                    'old_quantity' => $product['prod_quantity'],
                    'new_quantity' => $newQuantity,
                    'type' => 'sold',
                ];
                $auditModel->save($auditData);
            }
        }

        $inv = [
            'id' => $id,
            'order_id' => $order_id,
        ];

        $this->invoice->save($inv);
        if ($this->orders->affectedRows() > 0 && $this->orderitems->affectedRows() > 0 && $this->invoice->affectedRows() > 0) {
            return $this->respond(['message' => 'Checkout successful'], 200);
        } else {
            return $this->respond(['message' => 'Checkout failed'], 500);
        }
    }

    public function notif($id)
    {
        $notif = new NotificationModel();
        $data = $notif->where('id', $id)->findAll();

        return $this->respond($data, 200);
    }
    public function acceptBookingByGroup($groupId)
    {
        $roomModel = new RoomModel();
        $bookingModel = new BookingModel();
        $notificationModel = new NotificationModel();
        $bookingGroupBookingsModel = new BookingGroupBookings();
    
        // Fetch all booking IDs for the given group_id
        $groupBookings = $bookingGroupBookingsModel->where('group_id', $groupId)->findAll();
    
        if (count($groupBookings) > 0) {
            $bookingIds = [];
            $roomIds = [];
    
            foreach ($groupBookings as $groupBooking) {
                $booking = $bookingModel->find($groupBooking['book_id']);
                if ($booking) {
                    $bookingIds[] = $booking['book_id'];
                    $roomIds[] = $booking['room_id'];
    
                    // Insert notification for each booking
                    $notificationData = [
                        'id' => $booking['id'],
                        'message' => 'Your booking for room ' . $booking['room_id'] . ' has been accepted.',
                    ];
                    $notificationModel->insert($notificationData);
                }
            }
    
            // Update bookings and rooms
            $bookingModel->update($bookingIds, ['booking_status' => 'confirmed']);
    
            return $this->respond(['message' => 'All bookings in group ' . $groupId . ' accepted successfully'], 200);
        } else {
            return $this->respond(['message' => 'No bookings found for group ' . $groupId], 404);
        }
    }
    
    
    
    public function assignAmenitiesToRoom($roomId)
    {
        $data = $this->request->getJSON(true);
        
        // Validate if amenities are provided
        if (!isset($data['amenities']) || !is_array($data['amenities'])) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Invalid or missing amenities data'
            ], 400);
        }
    
        $amenities = $data['amenities'];
    
        // Validate if room ID is valid
        if (empty($roomId) || !is_numeric($roomId)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Invalid room ID'
            ], 400);
        }
    
        $db = \Config\Database::connect();
        $builder = $db->table('room_amenities'); // Ensure table name is correct
    
        $db->transBegin();
    
        try {
            // Delete existing amenities for the room
            $builder->where('room_id', $roomId)->delete();
    
            // Prepare data for insertion
            $insertData = [];
            foreach ($amenities as $amenityId) {
                if (is_numeric($amenityId)) {
                    $insertData[] = [
                        'room_id' => $roomId,
                        'amenity_id' => $amenityId,
                        'stock' => 1 // Set default stock to 1
                    ];
                } else {
                    throw new \Exception('Invalid amenity ID: ' . $amenityId);
                }
            }
    
            // Insert new amenities
            if (!empty($insertData)) {
                $builder->insertBatch($insertData);
            }
    
            $db->transCommit();
    
            return $this->respond([
                'status' => 'success',
                'message' => 'Amenities assigned successfully'
            ], 200);
    
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Failed to assign amenities to room: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to assign amenities: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getAmenitiesForRoom($roomId)
    {
        $amenities = $this->roomAmenitiesModel
            ->select('amenities.*')
            ->join('amenities', 'room_amenities.amenity_id = amenities.amenity_id')
            ->where('room_amenities.room_id', $roomId)
            ->findAll();
    
        return $this->respond($amenities, 200);
    }
    
    public function declineBookingByGroup($groupId)
    {
        $roomModel = new RoomModel();
        $bookingModel = new BookingModel();
        $notificationModel = new NotificationModel();
        $bookingGroupBookingsModel = new BookingGroupBookings();
    
        // Fetch all booking IDs for the given group_id
        $groupBookings = $bookingGroupBookingsModel->where('group_id', $groupId)->findAll();
    
        if (count($groupBookings) > 0) {
            foreach ($groupBookings as $groupBooking) {
                $booking = $bookingModel->find($groupBooking['book_id']);
                if ($booking && $booking['booking_status'] === 'pending') {
                    // Update each booking's status to 'declined'
                    $bookingModel->update($booking['book_id'], [
                        'booking_status' => 'declined',
                        'message' => 'Booking declined.'
                    ]);
    
                    // Insert decline notification into the database
                    $notificationData = [
                        'id' => $booking['id'],
                        'message' => 'Your booking has been declined.'
                    ];
                    $notificationModel->insert($notificationData);
    
                    // Update room status to 'Available'
                    $roomModel->update($booking['room_id'], ['room_status' => 'Available']);
                }
            }
    
            return $this->respond(['message' => 'All bookings in group ' . $groupId . ' declined successfully'], 200);
        } else {
            return $this->respond(['message' => 'No bookings found for group ' . $groupId], 404);
        }
    }
    
    

    public function acceptEnrolling($enroll_id)
    {
        $enrollmentModel = new EnrollmentModel();
        $notificationModel = new NotificationModel();

        $enroll = $enrollmentModel->find($enroll_id);

        if ($enroll) {
            // Check if the enrollment status is 'pending'
            if ($enroll['enrollment_status'] === 'pending') {
                // Update the enrollment status to 'accepted'
                $updatedEnroll = $enrollmentModel->update($enroll_id, [
                    'enrollment_status' => 'accepted',
                    'message' => 'Enrollment accepted.'
                ]);

                if ($updatedEnroll) {
                    // Insert acceptance notification into the database
                    $notificationData = [
                        'id' => $enroll['id'],
                        'message' => 'Your enrollment has been accepted.'
                    ];
                    $notificationModel->insert($notificationData);

                    // Return the response
                    return $this->respond(['message' => 'Enrollment accepted successfully', 'enroll_id' => $enroll_id], 200);
                } else {
                    return $this->respond(['message' => 'Failed to accept enrollment'], 500);
                }
            } else {
                return $this->respond(['message' => 'Cannot accept enrollment with the current status'], 400);
            }
        } else {
            return $this->respond(['message' => 'Enrollment not found'], 404);
        }
    }
    public function declineEnroll($enroll_id)
    {
        $enrollmentModel = new EnrollmentModel();
        $notificationModel = new NotificationModel();

        $enroll = $enrollmentModel->find($enroll_id);

        if ($enroll) {
            // Check if the enrollment status is 'pending'
            if ($enroll['enrollment_status'] === 'pending') {
                // Update the enrollment status to 'declined' and add a message
                $updatedEnroll = $enrollmentModel->update($enroll_id, [
                    'enrollment_status' => 'declined',
                    'message' => 'Enrollment declined.'
                ]);

                if ($updatedEnroll) {
                    // Insert decline notification into the database
                    $notificationData = [
                        'id' => $enroll['id'],
                        'message' => 'Your enrollment has been declined.'
                    ];
                    $notificationModel->insert($notificationData);

                    // Return the response
                    return $this->respond(['message' => 'Enrollment declined successfully', 'enroll_id' => $enroll_id], 200);
                } else {
                    return $this->respond(['message' => 'Failed to decline enrollment'], 500);
                }
            } else {
                return $this->respond(['message' => 'Cannot decline enrollment with the current status'], 400);
            }
        } else {
            return $this->respond(['message' => 'Enrollment not found'], 404);
        }
    }

}

