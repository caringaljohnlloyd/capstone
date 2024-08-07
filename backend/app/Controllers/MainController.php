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
    public function __construct()
    {
        $this->db = \Config\Database::connect();
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
        $json = $this->request->getJSON();

        if (!$json || !isset($json->cottage_id) || !isset($json->selectedTime) || !isset($json->selectedTimeout) || !isset($json->id)) {
            return $this->fail('Missing required parameters', 400);
        }

        $bookingModel = new CottageBookingModel();

        $cottageModel = new CottageModel();
        $cottage = $cottageModel->find($json->cottage_id);
        if (!$cottage) {
            return $this->failNotFound('Cottage not found');
        }

        $data = [
            'id' => $json->id,
            'selectedTime' => $json->selectedTime,
            'selectedTimeout' => $json->selectedTimeout,
            'cottage_id' => $json->cottage_id,
        ];

        if ($bookingModel->save($data) === false) {
            return $this->respond(['message' => 'Booking failed'], 500);
        }

        return $this->respond(['message' => 'Booked successfully'], 200);
    }

    public function saveDate()
    {
        try {
            $request = $this->request;
            $data = [
                'swimming_date' => $request->getPost('swimming_date'),
            ];

            $dateModel = new DateModel();
            $dateModel->insert($data);

            return $this->respond(["message" => "Date added successfully"], 200);
        } catch (\Exception $e) {
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
    // public function pack3()
    // {
    //     $f = new RoomModel();

    //     $data = $f->where('packs', 3)->findAll();

    //     return $this->respond($data, 200);
    // }
    // public function pack4()
    // {
    //     $f = new RoomModel();

    //     $data = $f->where('packs', 4)->findAll();

    //     return $this->respond($data, 200);
    // }
    // public function pack6()
    // {
    //     $f = new RoomModel();

    //     $data = $f->where('packs', 6)->findAll();

    //     return $this->respond($data, 200);
    // }



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



    // public function getRoomByTotalPax($packs)
    // {
    //     $roomModel = new RoomModel();

    //     $filteredRooms = $roomModel->where('packs', $packs)->findAll();

    //     if (!empty($filteredRooms)) {
    //         return $this->response->setStatusCode(200)->setJSON($filteredRooms);
    //     } else {

    // }


    // public function getRoomByTotalPax($packs)
// {
//     $room = new RoomModel();
//     $allRooms = $room->findAll();
//     $filteredRooms = [];

    //     $index = 0;

    //     do {
//         $currentRoom = $allRooms[$index];

    //         if ($currentRoom->packs == $packs) {
//             $filteredRooms[] = $currentRoom;
//         }

    //         $index++;

    //     } while ($index < count($allRooms));

    //     return $this->respond($filteredRooms, 200);
// }

    // public function getRoomByTotalPax($packs)
// {
//     $room = new RoomModel();
//     $allRooms = $room->findAll();
//     var_dump($allRooms); // Debugging output
//     $filteredRooms = $this->findComb($allRooms, $packs);
//     var_dump($filteredRooms); // Debugging output
//     return $this->respond($filteredRooms, 200);
// }

    // // Define the findComb method
// protected function findComb($rooms, $packs)
// {
//     $filteredRooms = [];

    //     foreach ($rooms as $room) {
//         // Check if $room is an object before accessing its properties
//         if (is_object($room) && property_exists($room, 'packs') && $room->packs == $packs) {
//             $filteredRooms[] = $room;
//         }
//     }

    //     return $filteredRooms;
// }



    //pag di gumana, kaya yan next sem hahaha
    // 😭😭😭😭
    //try nga kung eerror

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
        $dateSchedModel = new DateModel();
        $dateModel = $dateSchedModel->select('swimming_date')->findAll();

        // Convert dates to proper format
        $dates = array_map(function ($date) {
            return date('Y-m-d', strtotime($date['swimming_date']));
        }, $dateModel);

        // Log the dates before returning
        log_message('debug', 'Dates fetched from database: ' . print_r($dates, true));

        return $this->respond($dates, 200);
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
        $json = $this->request->getJSON();
        $room_id = $json->room_id;
        $this->room = new RoomModel();
        $booked = $this->room->where(['room_id' => $room_id])->first();

        $expiration = $json->checkout;

        $qrCodeData = $json->id;

        $data = [
            'id' => $json->id,
            'checkin' => $json->checkin,
            'checkout' => $json->checkout,
            'specialRequest' => $json->specialRequest,
            'room_id' => $json->room_id,
            'booking_status' => 'pending',
            'payment_method' => $json->payment_method,
            'downpayment' => $json->downpayment,
            'booking_qr' => $qrCodeData,
            'expiration' => $expiration
        ];

        $booking = new BookingModel();
        $r = $booking->save($data);

        if ($r) {
            $bookedr = $this->room->update($booked['room_id'], ['room_status' => 'pending']);

            if ($bookedr) {
                return $this->respond(['message' => 'Booked successfully', 'booking' => $r, 'expiration' => $expiration, 'booking_qr' => $qrCodeData], 200);
            } else {
                return $this->respond(['message' => 'Failed to update room status'], 500);
            }
        } else {
            return $this->respond(['message' => 'Booking failed'], 500);
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

    public function checkout()
    {
        $this->invoice = new InvoiceModel();
        $this->orderitems = new OrderListModel();
        $this->orders = new OrderSModel();

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
            'order_status' => 'pending',
            'total_price' => $json->total_price,
            'order_payment_method' => $json->order_payment_method
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

        $data = [
            'prod_name' => $request->getPost('prod_name'),
            'prod_quantity' => $request->getPost('prod_quantity'),
            'prod_desc' => $request->getPost('prod_desc'),
            'prod_price' => $request->getPost('prod_price'),
        ];

        if ($request->getFile('prod_image')->isValid()) {
            $image = $request->getFile('prod_image');

            $imageName = $image->getName();

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
        $uploadPath = 'C:/laragon/www/capstone/frontend/src/assets/img';

        $image->move($uploadPath, $imageName);
        return $imageName;
    }





    public function saveRoom()
    {
        $request = $this->request;

        $data = [
            'room_name' => $request->getPost('room_name'),
            'price' => $request->getPost('price'),
            'bed' => $request->getPost('bed'),
            'bath' => $request->getPost('bath'),
            'packs' => $request->getPost('packs'),
            'downpayment' => $request->getPost('downpayment'),
            'description' => $request->getPost('description'),
        ];

        if ($request->getFile('image')->isValid()) {
            $image = $request->getFile('image');

            $imageName = $image->getName();

            $data['image'] = $this->handleRoomImageUpload($image, $imageName);
        }

        $roomModel = new RoomModel();

        try {
            $roomModel->insert($data);
            return $this->respond(["message" => "Room data saved successfully"], 200);
        } catch (\Exception $e) {
            return $this->respond(["message" => "Failed to save room data: " . $e->getMessage()], 500);
        }
    }
    public function handleRoomImageUpload($image, $imageName)
    {
        $uploadPath = 'C:/laragon/www/capstone/frontend/src/assets/img';

        $image->move($uploadPath, $imageName);
        return $imageName;
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
            'downpayment' => $request->getVar('downpayment') ?? $existingData['downpayment'],
            'bed' => $request->getVar('bed') ?? $existingData['bed'],
            'bath' => $request->getVar('bath') ?? $existingData['bath'],
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

    public function handleEditImageUpload($image, $imageName)
    {
        $uploadPath = 'C:/laragon/www/capstone/frontend/src/assets/img';

        $image->move($uploadPath, $imageName);
        return $imageName;
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
        $uploadPath = 'C:/laragon/www/capstone/frontend/src/assets/img';

        $image->move($uploadPath, $imageName);
        return $imageName;
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
    // public function confirmRes($id = null)
    // {
    //     $model = new BookingModel();
    //     $notifModel = new NotificationModel();

    //     try {
    //         $booking = $model->find($id);

    //         if (!$booking) {
    //             return $this->fail('No Data Found for Update', 404);
    //         }

    //         if ($booking['status'] === 'booked') {
    //             return $this->respond(['message' => 'Reservation is already confirmed']);
    //         }

    //         $model->update($id, ['status' => 'booked']);

    //         $id = $booking['id'];
    //         $notificationData = [
    //             'id' => $id,
    //             'message' => 'Your reservation has been confirmed.',
    //         ];

    //         $notifModel->insert($notificationData);

    //         return $this->respond(['message' => 'Reservation confirmed successfully']);
    //     } catch (\Exception $e) {
    //         return $this->fail('Internal Server Error: ' . $e->getMessage(), 500);
    //     }
    // }
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

