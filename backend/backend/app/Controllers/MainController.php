<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CottageBookingModel;
use App\Models\RoomModel;
use CodeIgniter\RestFul\ResourceController;
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

class MainController extends ResourceController
{

    use ResponseTrait;
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

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->reservationModel = new ReservationModel();
        $this->userModel = new UserModel();
        $this->tableModel = new TableModel();
        $this->orderItemModel = new OrderItemModel();

    }
    public function getTableReservation()
    {
        try {
            // Fetch reservations
            $reservations = $this->reservationModel->findAll();
    
            // Enrich the reservation data
            foreach ($reservations as &$reservation) {
                $reservation['user'] = $this->userModel->find($reservation['user_id']);
                $reservation['table'] = $this->tableModel->find($reservation['table_id']);
                
                // Fetch order items with item details
                $orderItems = $this->orderItemModel
                    ->select('order_items.*, menu.item_name') // Select item_name from items table
                    ->join('menu', 'order_items.menu_item_id = menu.menu_id') // Join items table to get item details
                    ->where('order_items.reservation_id', $reservation['reservation_id'])
                    ->findAll();
                    
                $reservation['order_items'] = $orderItems;
            }
    
            return $this->response->setJSON($reservations);
        } catch (\Exception $e) {
            log_message('error', 'Error in getTableReservation: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Internal Server Error']);
        }
    }
    
    public function updateStatus($id = null)
    {
        try {
            if ($id === null) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'No ID provided.']);
            }
    
            $status = $this->request->getPost('status');
    
            if (empty($status) || !in_array($status, ['pending', 'confirmed', 'declined', 'paid'])) {
                return $this->response->setJSON(['message' => 'Invalid status provided.'], 400);
            }
    
            $reservationModel = new ReservationModel();
            $notificationModel = new NotificationModel();
    
            // Find the reservation
            $reservation = $reservationModel->find($id);
    
            if (!$reservation) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Reservation not found.']);
            }
    
            // Determine the current status and update accordingly
            switch ($status) {
                case 'confirmed':
                    if ($reservation['status'] === 'pending') {
                        $reservationModel->update($id, ['status' => 'confirmed']);
                        $notificationData = [
                            'reservation_id' => $id,
                            'message' => 'Your reservation has been confirmed.'
                        ];
                        $notificationModel->insert($notificationData);
                        return $this->response->setJSON(['message' => 'Reservation confirmed successfully']);
                    }
                    return $this->response->setJSON(['message' => 'Reservation is not pending or already confirmed'], 400);
    
                case 'declined':
                    if ($reservation['status'] === 'pending') {
                        $reservationModel->update($id, ['status' => 'declined']);
                        $notificationData = [
                            'reservation_id' => $id,
                            'message' => 'Your reservation has been declined.'
                        ];
                        $notificationModel->insert($notificationData);
                        return $this->response->setJSON(['message' => 'Reservation declined successfully']);
                    }
                    return $this->response->setJSON(['message' => 'Reservation is not pending or already declined'], 400);
    
                case 'paid':
                    if ($reservation['status'] === 'confirmed') {
                        $reservationModel->update($id, ['status' => 'paid']);
                        return $this->response->setJSON(['message' => 'Reservation marked as paid successfully']);
                    }
                    return $this->response->setJSON(['message' => 'Reservation is not confirmed or already paid'], 400);
    
                case 'pending':
                    if ($reservation['status'] === 'confirmed' || $reservation['status'] === 'declined' || $reservation['status'] === 'paid') {
                        $reservationModel->update($id, ['status' => 'pending']);
                        return $this->response->setJSON(['message' => 'Reservation status reverted to pending successfully']);
                    }
                    return $this->response->setJSON(['message' => 'Reservation is not confirmed, declined, or paid'], 400);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in updateStatus: ' . $e->getMessage());
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
                $file->move(WRITEPATH . 'uploads', $newName);
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

    // Delete a reservation by ID
    public function deleteReservation($id)
    {
        if ($this->reservationModel->delete($id)) {
            return $this->response->setStatusCode(200)->setJSON(['success' => 'Reservation deleted successfully']);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete reservation']);
    }

    // Add an item to an existing reservation
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

    // Retrieve an order item by ID
    public function getOrderItem($id)
    {
        $orderItem = $this->orderItemModel->find($id);

        if (!$orderItem) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Order item not found']);
        }

        return $this->response->setStatusCode(200)->setJSON($orderItem);
    }

    // Delete an order item by ID
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
    $json = $this->request->getJSON();

    // Validate the received data
    if (!isset($json->item_name) || !isset($json->item_category) || !isset($json->item_price)) {
        return $this->respond([
            'status' => 'error',
            'message' => 'Missing required fields'
        ], 400);
    }

    $data = [
        'item_name' => $json->item_name,
        'item_category' => $json->item_category,
        'item_price' => $json->item_price,
    ];

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




public function updateMenuItem($id = null)
{
    // Get the request instance
    $request = $this->request;

    // Initialize the model
    $menuModel = new MenuModel();

    // Find the existing data by menu_id
    $existingData = $menuModel->find($id);

    // Check if the record exists
    if (empty($existingData)) {
        return $this->respond([
            "status" => "error",
            "message" => "Record not found"
        ], 404);
    }

    // Gather input data, using existing data if new data is not provided
    $data = [
        'menu_id' => $id,
        'item_name' => $request->getVar('item_name') ?? $existingData['item_name'],
        'item_category' => $request->getVar('item_category') ?? $existingData['item_category'],
        'item_price' => $request->getVar('item_price') ?? $existingData['item_price'],
    ];

    // Check if there are any changes to update
    if ($data === array_intersect_key($existingData, $data)) {
        return $this->respond([
            "status" => "success",
            "message" => "No changes detected, data not updated"
        ], 200);
    }

    // Try to update the record
    try {
        if ($menuModel->update($id, $data)) {
            return $this->respond([
                "status" => "success",
                "message" => "Item updated successfully!"
            ], 200);
        } else {
            return $this->respond([
                "status" => "error",
                "message" => "Failed to update item"
            ], 500);
        }
    } catch (\Exception $e) {
        return $this->respond([
            "status" => "error",
            "message" => "Failed to update data: " . $e->getMessage()
        ], 500);
    }
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
        
        if (!$json || !isset($json['cottage_id']) || !isset($json['selectedTime']) || !isset($json['selectedTimeout']) || !isset($json['user_id']) || !isset($json['downpayment'])) {
            return $this->fail('Missing required parameters', 400);
        }
    
        // Handle proof of downpayment file
        $file = $this->request->getFile('proofOfDownpayment');
        if (!$file || !$file->isValid()) {
            return $this->fail('File upload error', 400);
        }
    
        // Move file to desired location
        $fileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $fileName);
    
        $bookingModel = new CottageBookingModel();
        $cottageModel = new CottageModel();
    
        $cottage = $cottageModel->find($json['cottage_id']);
        if (!$cottage) {
            return $this->failNotFound('Cottage not found');
        }
    
        $data = [
            'user_id' => $json['user_id'],
            'selectedTime' => $json['selectedTime'],
            'selectedTimeout' => $json['selectedTimeout'],
            'cottage_id' => $json['cottage_id'],
            'downpayment' => $json['downpayment'],
            'proof_of_downpayment' => $fileName,  // Save file name in DB
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
            $rooms = $roomModel->where('packs', $packs)->findAll();
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
    public function getData()
    {
        $main = new UserModel();
        $data = $main->findAll();
        return $this->respond($data, 200);
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
        $bookingModel = new BookingModel();

        $result = $bookingModel
            ->select('booking.*, user.*, room.*')
            ->join('user', 'user.id = booking.id') // Assuming 'id' is the foreign key in the 'booking' table
            ->join('room', 'room.room_id = booking.room_id')
            ->where('booking.booking_status !=', 'paid')
            ->where('booking.booking_status !=', 'declined')
            ->findAll();

        return $this->respond($result, 200);
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






    public function markAsPaid($booking_id)
    {
        $roomModel = new RoomModel();
        $bookingModel = new BookingModel();

        $booking = $bookingModel->find($booking_id);

        if ($booking) {
            $updatedBooking = $bookingModel->update($booking['book_id'], ['booking_status' => 'paid']);

            if ($updatedBooking) {
                $updatedRoom = $roomModel->update($booking['room_id'], ['room_status' => 'Available']);

                if ($updatedRoom) {
                    return $this->respond(['message' => 'Booking status updated to "Paid" and room status updated to "Available"', 'booking_id' => $booking['book_id']], 200);
                } else {
                    $bookingModel->update($booking['book_id'], ['booking_status' => 'confirmed']);
                    return $this->respond(['message' => 'Failed to update room status'], 500);
                }
            } else {
                return $this->respond(['message' => 'Failed to update booking status'], 500);
            }
        } else {
            return $this->respond(['message' => 'Booking not found'], 404);
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
        $json = $this->request->getPost(); // Use getPost for form data
        $room_id = $json['room_id'];
        $this->room = new RoomModel();
        $booked = $this->room->where(['room_id' => $room_id])->first();
        
        if (!$booked) {
            return $this->respond(['message' => 'Room not found'], 404);
        }
        
        $expiration = $json['checkout'];
        $qrCodeData = $json['id'];
        
        // Define the upload path relative to the public directory
        $uploadPath = FCPATH . 'uploads/';
        
        // Handle file upload
        $file = $this->request->getFile('downpaymentProof');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $filePath = 'uploads/' . $newName; // Relative path for database storage
            
            // Ensure the directory exists
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    return $this->respond(['message' => 'Failed to create upload directory'], 500);
                }
            }
            
            // Try to move the file
            if ($file->move($uploadPath, $newName)) {
                // File moved successfully
            } else {
                return $this->respond(['message' => 'Failed to move uploaded file'], 500);
            }
        } else {
            return $this->respond(['message' => 'Invalid file upload'], 400);
        }
        
        $data = [
            'id' => $json['id'],
            'checkin' => $json['checkin'],
            'checkout' => $json['checkout'],
            'specialRequest' => $json['specialRequest'],
            'room_id' => $json['room_id'],
            'booking_status' => 'pending',
            'payment_method' => $json['payment_method'],
            'downpayment' => $json['downpayment'],
            'booking_qr' => $qrCodeData,
            'expiration' => $expiration,
            'downpaymentProof' => $filePath, // Save relative file path or filename in database
        ];
        
        $booking = new BookingModel();
        if ($booking->save($data)) {
            if ($this->room->update($booked['room_id'], ['room_status' => 'pending'])) {
                return $this->respond([
                    'message' => 'Booked successfully',
                    'booking' => $data, // Include booking data in the response
                    'expiration' => $expiration,
                    'booking_qr' => $qrCodeData
                ], 200);
            } else {
                return $this->respond(['message' => 'Failed to update room status'], 500);
            }
        } else {
            return $this->respond(['message' => 'Failed to save booking'], 500);
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
        $token = $this->verification(50);
        $verificationToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        // Prepare data to save
        $data = [
            'name' => $json->name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'address' => $json->address, // Add address field
            'number' => $json->number,   // Add number field
            'token' => $token,
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
            return $this->respond(['msg' => 'Registered Successfully', 'token' => $token]);
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
            // Move the file to the desired location
            $downpaymentProofPath = 'path/to/uploads/' . $downpaymentProof->getName();
            $downpaymentProof->move('path/to/uploads/'); // Ensure this directory exists
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
                'downpayment_proof' => $downpaymentProofPath, // Store the path in the database
            ];
    
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
        $ordersModel = new OrdersModel();
        $shopModel = new ShopModel();

        $this->orderitems = new OrderListModel();

        $order = $ordersModel->find($orderId);

        if ($order && $order['order_status'] === 'confirmed') {
            $ordersModel->update($orderId, ['order_status' => 'paid']);

            $orderItems = $this->orderitems->where('order_id', $orderId)->findAll();

            foreach ($orderItems as $item) {
                $product = $shopModel->find($item['shop_id']);

                if ($product) {
                    $newQuantity = $product['prod_quantity'] - $item['quantity'];
                    $shopModel->update($item['shop_id'], ['prod_quantity' => $newQuantity]);

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

            return $this->response->setJSON(['message' => 'Order marked as paid successfully']);
        } else {
            return $this->response->setJSON(['message' => 'Invalid order or order is not confirmed'], 400);
        }
    }
    public function declineOrder($orderId)
    {
        $ordersModel = new OrdersModel();
        $notificationModel = new NotificationModel();

        $order = $ordersModel->find($orderId);

        if ($order && $order['order_status'] === 'pending') {
            // Update the order status to 'declined'
            $ordersModel->update($orderId, ['order_status' => 'declined']);

            // Insert decline notification into the database
            $notificationData = [
                'id' => $order['id'],
                'message' => 'Your order has been declined.'
            ];
            $notificationModel->insert($notificationData);

            // Optional: You can perform additional actions specific to declining orders here

            return $this->response->setJSON(['message' => 'Order declined successfully']);
        } else {
            return $this->response->setJSON(['message' => 'Invalid order or order is not pending'], 400);
        }
    }



    public function confirmOrder($orderId)
    {
        $ordersModel = new OrdersModel();
        $notificationModel = new NotificationModel();

        // Find the order
        $order = $ordersModel->find($orderId);

        if ($order && $order['order_status'] === 'pending') {
            // Update the order status to 'accepted'
            $ordersModel->update($orderId, ['order_status' => 'confirmed']);

            // Insert accept notification into the database
            $notificationData = [
                'id' => $order['id'],
                'message' => 'Your order has been accepted.'
            ];
            $notificationModel->insert($notificationData);

            return $this->response->setJSON(['message' => 'Order accepted successfully']);
        } else {
            return $this->response->setJSON(['message' => 'Invalid order or order is not pending'], 400);
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
        $uploadPath = 'uploads';
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
            'prod_name' => $request->getVar('prod_name') ?? $existingData['prod_name'],
            'prod_desc' => $request->getVar('prod_desc') ?? $existingData['prod_desc'],
            'prod_price' => $request->getVar('prod_price') ?? $existingData['prod_price'],
        ];

        try {
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
        $uploadPath = 'uploads';
    
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
        $userModel = new UserModel();
        $ordersModel = new OrdersModel();
        $shopModel = new ShopModel();

        $orders = $ordersModel->select('orders.*, order_list.*, shop.*, user.name as user_name')
            ->join('order_list', 'order_list.order_id = orders.order_id')
            ->join('shop', 'shop.shop_id = order_list.shop_id')
            ->join('user', 'user.id = orders.id')
            ->where('orders.order_status !=', 'paid')
            ->where('orders.order_status !=', 'declined')

            ->findAll();

        return $this->response->setJSON(['orders' => $orders]);
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
   
    public function acceptBooking($booking_id)
    {
        $roomModel = new RoomModel();
        $bookingModel = new BookingModel();
        $notificationModel = new NotificationModel();

        $booking = $bookingModel->find($booking_id);

        if ($booking) {
            // Retrieve the room details associated with the booking
            $room = $roomModel->find($booking['room_id']);

            if ($room) {
                $updatedBooking = $bookingModel->update($booking['book_id'], ['booking_status' => 'confirmed']);

                if ($updatedBooking) {
                    $updatedRoom = $roomModel->update($booking['room_id'], ['room_status' => 'booked']);

                    if ($updatedRoom) {
                        // Insert accept notification into the database
                        $notificationData = [
                            'id' => $booking['id'],
                            'message' => 'Your booking for ' . $room['room_name'] . ' has been accepted.',
                        ];
                        $notificationModel->insert($notificationData);

                        return $this->respond(['message' => 'Booking and room status updated successfully', 'booking_id' => $booking['book_id']], 200);
                    } else {
                        $bookingModel->update($booking['book_id'], ['booking_status' => 'pending']);
                        return $this->respond(['message' => 'Failed to update room status'], 500);
                    }
                } else {
                    return $this->respond(['message' => 'Failed to update booking status'], 500);
                }
            } else {
                return $this->respond(['message' => 'Room not found'], 404);
            }
        } else {
            return $this->respond(['message' => 'Booking not found'], 404);
        }
    }
    public function declineBooking($booking_id)
    {
        $roomModel = new RoomModel();
        $bookingModel = new BookingModel();
        $notificationModel = new NotificationModel();

        $booking = $bookingModel->find($booking_id);

        if ($booking) {
            // Check if the booking status is 'pending'
            if ($booking['booking_status'] === 'pending') {
                // Update the booking status to 'declined' and add a message
                $updatedBooking = $bookingModel->update($booking['book_id'], [
                    'booking_status' => 'declined',
                    'message' => 'Booking declined.'
                ]);

                if ($updatedBooking) {
                    // Insert decline notification into the database
                    $notificationData = [
                        'id' => $booking['id'],
                        'message' => 'Your booking has been declined.'
                    ];
                    $notificationModel->insert($notificationData);

                    // Optional: Update the room status or perform any other necessary actions
                    $updatedRoom = $roomModel->update($booking['room_id'], ['room_status' => 'Available']);

                    // Return the response
                    return $this->respond(['message' => 'Booking declined successfully', 'booking_id' => $booking['book_id']], 200);
                } else {
                    return $this->respond(['message' => 'Failed to decline booking'], 500);
                }
            } else {
                return $this->respond(['message' => 'Cannot decline booking with the current status'], 400);
            }
        } else {
            return $this->respond(['message' => 'Booking not found'], 404);
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

