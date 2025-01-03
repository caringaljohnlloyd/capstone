<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/api/bookings/pending-confirmed', 'MainController::getPendingAndConfirmedBookings');

$routes->get('api/cottage/pending-confirmed', 'MainController::getPendingAndConfirmedCottageBookings');

$routes->get('/', 'Home::index');
$routes->get('/getData','MainController::getData');
$routes->get('/fetchUser/(:num)', 'MainController::fetchUser/$1'); // Ensure your route allows for an ID parameter
$routes->get('/getDataShop','MainController::getDataShop');
$routes->post('/saveShop','MainController::saveShop');
$routes->post('/register','MainController::register');
$routes->post('/del','MainController::del');
$routes->post('update/(:num)', 'MainController::update/$1');
$routes->match(['post','get'],'/login', 'MainController::login');
$routes->post('/submit-feedback', 'MainController::submitFeedback');
$routes->post('save', 'MainController::save');
$routes->post('/logout', 'MainController::logout');
$routes->post('/getCart', 'MainController::Cart');
$routes->get('/getProd/(:num)', 'MainController::getCart/$1');
$routes->get('/getRoom', 'MainController::getRoom'); // Fetch all rooms
$routes->get('/getAllAmenities', 'MainController::getAllAmenities'); // Fetch all amenities
$routes->post('/addAmenity', 'MainController::addAmenity'); // Add a new amenity
$routes->post('/assignAmenitiesToRoom/(:num)', 'MainController::assignAmenitiesToRoom/$1'); // Assign amenities to a room
$routes->get('/getAmenitiesForRoom/(:num)', 'MainController::getAmenitiesForRoom/$1'); // Get amenities for a specific room

$routes->get('/getShop','MainController::getShop');
$routes->get('/getPool','MainController::getPool');
$routes->get('/getManifest','MainController::getManifest');
$routes->post('manifest','MainController::manifest');
$routes->post('updateCartQuantity', 'MainController::updateCartQuantity');
$routes->get('/getFeedback','MainController::getFeedback');
$routes->post('booking','MainController::booking');
$routes->get('/getbook','MainController::getbook');
$routes->get('/getBooking/(:num)', 'MainController::getBooking/$1');
$routes->post('/notifications/mark-read', 'MainController::markAsRead');
    
$routes->get('/getenroll','MainController::getenroll');
$routes->get('/getDate','MainController::getDate');
$routes->post('/submit-rating','MainController::submitRating');
$routes->post('/checkout', 'MainController::checkout');
$routes->get('/search/(:any)', 'MainController::search/$1');
$routes->get('/getInvoice','MainController::getInvoice');
$routes->put('updateQuantity/(:num)', 'MainController::updateQuantity/$1');
$routes->post('updateQuantity', 'MainController::updateQuantity');
$routes->get('api/auditHistory/(:num)', 'MainController::getAuditHistory/$1');
$routes->post('/api/feedback/delete/(:num)', 'MainController::deleteFeedback/$1');
$routes->post('api/mark-as-paid-group/(:any)', 'MainController::markAsPaidByGroup/$1');
$routes->post('api/accept-booking-group/(:any)', 'MainController::acceptBookingByGroup/$1');
$routes->post('api/decline-booking-group/(:any)', 'MainController::declineBookingByGroup/$1');
$routes->post('/accept-enrolling/(:num)', 'MainController::acceptEnrolling/$1');
$routes->post('/mark-as-paid/(:num)', 'MainController::markAsPaid/$1');
$routes->post('/mark-this-paid/(:num)', 'MainController::markthisPaid/$1');
$routes->get('/getInvoices/(:num)', 'MainController::getInvoices/$1');
$routes->get('/getImage/(:segment)', 'MainController::getImage/$1');
$routes->post('/saveShop', 'MainController::saveShop');
$routes->post('/saveDate', 'MainController::saveDate');
$routes->post('/updateShop/(:num)', 'MainController::updateShop/$1');
$routes->put('/updateRoom/(:num)', 'MainController::updateRoom/$1');
$routes->get('/getStaff','MainController::getStaff');
$routes->post('/saveStaff', 'MainController::saveStaff');
$routes->put('/updateStaff/(:num)', 'MainController::updateStaff/$1');
$routes->delete('/deleteStaff/(:num)', 'MainController::deleteStaff/$1');
$routes->delete('/deleteShop/(:num)', 'MainController::deleteShop/$1');
$routes->delete('/deleteRoom/(:num)', 'MainController::deleteRoom/$1');
$routes->post('/api/feedback/hide/(:num)', 'MainController::deleteFeedback/$1');
$routes->post('/api/staffback/hide/(:num)', 'MainController::hideStaff/$1');
$routes->post('/checkoutpos', 'MainController::checkoutpos');
$routes->post('/saveRoom', 'MainController::saveRoom');
$routes->get('/api/orders', 'MainController::getUserOrders');
$routes->post('/api/mark-order-paid/(:num)', 'MainController::markOrderPaid/$1');
$routes->post('/api/confirm-order/(:num)', 'MainController::confirmOrder/$1');
$routes->post('/api/decline-booking/(:num)', 'MainController::declineBooking/$1');
$routes->post('/api/decline-order/(:num)', 'MainController::declineOrder/$1');
$routes->post('/api/decline-enroll/(:num)', 'MainController::declineEnroll/$1');
$routes->get('getNotif', 'MainController::getNotifications');
$routes->post( '/confirmRes/(:num)', 'MainController::confirmRes/$1');
$routes->get('/getBookingHistory', 'MainController::getBookingHistory');
$routes->get('/getRoomBookingHistory', 'MainController::getRoomBookingHistory');
$routes->get('/getCottageHistory', 'MainController::getCottageHistory');
$routes->get('/getReservationHistory', 'MainController::getReservationHistory');
$routes->get('getNotif/(:num)', 'MainController::notif/$1');
$routes->get('/pack3','MainController::pack3');
$routes->get('/pack4','MainController::pack4');
$routes->get('/pack6','MainController::pack6');
$routes->match(['post','get'],'/sendVerificationEmail', 'EmailController::sendVerificationEmail');
$routes->match(['post', 'get'], '/resendVerificationEmail', 'EmailController::resendVerificationEmail');
$routes->get('/verify','EmailController::verify_email');
$routes->get('/fetchEmail/(:segment)', 'EmailController::fetchEmail/$1'); // Fetch email by a segment (e.g., email string)

$routes->get('/rooms/total_pax/(:num)', 'MainController::getRoomByTotalPax/$1');
$routes->post('enroll', 'MainController::enroll');
$routes->get('/getAvailableDates','MainController::getAvailableDates');
$routes->post('/api/event/bookings', 'MainController::store');
$routes->get('/getTable', 'MainController::getTable');
$routes->get('/getItem', 'MainController::getItem');
$routes->get('/getCottage', 'MainController::getCottage');
$routes->post('cottageBooking','MainController::cottageBooking');
$routes->get('/getOrders', 'MainController::getOrders');
$routes->get('sales-report/daily', 'MainController::showDailyReport');
$routes->get('sales-report/monthly', 'MainController::monthlySalesReport');
$routes->get('sales-report/yearly', 'MainController::yearlySalesReport');
$routes->get('generateAllReports', 'MainController::generateAllReports');
$routes->get('user/(:num)', 'MainController::getUserWithRelatedData/$1');

$routes->get('/report/combined-sales', 'MainController::combinedSalesReport');
$routes->post('addMenuItem', 'MainController::addMenuItem');


$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('reset-password', 'MainController::resetPassword');
});
$routes->group('api', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('update-password', 'MainController::updatePassword');
});
$routes->post('api/menu/(:num)', 'MainController::updateMenuItem/$1');


$routes->group('api', function($routes) {
    // Fetch all menu items
    $routes->get('menu', 'MainController::fetchMenuItems');
    
    // Fetch a single menu item by ID
    $routes->get('menu/(:num)', 'MainController::fetchMenuItem/$1');
    
    // Create a new menu item
    $routes->post('menu', 'MainController::addMenuItem');
    
    // Update a menu item by ID
    
    // Delete a menu item by ID
    $routes->delete('menu/(:num)', 'MainController::deleteMenuItem/$1');
});
$routes->post('api/send-email', 'EmailController::sendEmail');


$routes->post('/reservations', 'MainController::createReservation'); // Create a new reservation
$routes->get('/reservations/(:num)', 'MainController::getReservation/$1'); // Retrieve a reservation by ID
$routes->delete('/reservations/(:num)', 'MainController::deleteReservation/$1'); // Delete a reservation by ID

$routes->post('/reservations/addOrderItem', 'MainController::addOrderItem'); // Add an item to an existing reservation
$routes->get('/order-items/(:num)', 'MainController::getOrderItem/$1'); // Retrieve an order item by ID
$routes->delete('/order-items/(:num)', 'MainController::deleteOrderItem/$1'); // Delete an order item by ID

$routes->post('reservation/confirm/(:num)', 'MainController::confirmReservation/$1');
$routes->post('reservation/decline/(:num)', 'MainController::declineReservation/$1');
$routes->post('reservation/paid/(:num)', 'MainController::markResrvationAsPaid/$1');

$routes->group('admin', function ($routes) {
    $routes->get('reservations', 'MainController::getTableReservation');
    $routes->put('reservations/updateStatus/(:num)', 'MainController::updateStatus/$1');
    $routes->get('reservations/(:num)', 'MainController::show/$1');
});
$routes->group('api', function($routes) {
    // Get all cottage bookings
    $routes->get('cottage-bookings', 'MainController::getAllCottageBookings');

    // Confirm a cottage booking
    $routes->post('cottage-bookings/confirm/(:num)', 'MainController::confirmCottageBooking/$1');

    // Decline a cottage booking
    $routes->post('cottage-bookings/decline/(:num)', 'MainController::declineCottageBooking/$1');

    // Mark a cottage booking as paid
    $routes->post('cottage-bookings/mark-paid/(:num)', 'MainController::markCottageBookingAsPaid/$1');
});
$routes->get('/getTables', 'MainController::fetchTables');
$routes->post('/addTable', 'MainController::addTable');
$routes->post('/updateTable/(:num)', 'MainController::updateTable/$1');

$routes->get('/cottages', 'MainController::getCottages');
$routes->get('/cottages/(:num)', 'MainController::getCottages/$1');
$routes->post('/cottages', 'MainController::saveCottage');
$routes->post('/cottages/update/(:num)', 'MainController::updateCottage/$1');

$routes->group('api', function($routes) {
    $routes->post('cottageBookings/(:num)', 'MainController::cancelCottageBooking/$1');
    $routes->post('reservations/(:num)', 'MainController::cancelReservation/$1');
    $routes->post('orders/(:num)', 'MainController::cancelOrder/$1');
});
$routes->group('api', function($routes) {
    $routes->get('amenities', 'MainController::getAllAmenities'); // Get all amenities
    $routes->post('amenities', 'MainController::addAmenity'); // Add a new amenity
    $routes->get('amenities/(:num)', 'MainController::getAmenity/$1'); // Get a specific amenity
    $routes->put('amenities/(:num)', 'MainController::updateAmenity/$1'); // Update a specific amenity
    $routes->delete('amenities/(:num)', 'MainController::deleteAmenity/$1'); // Delete a specific amenity
    $routes->post('amenities/(:num)/stock', 'MainController::modifyStock/$1');

});
// Fetch audit history for a specific amenity
$routes->get('api/amenitiesAuditHistory/(:num)', 'MainController::getAmenitiesAuditHistory/$1');

$routes->get('/api/analytics/getMostUsedRooms', 'AnalyticsController::getMostUsedRooms');
$routes->get('/api/analytics/getMonthlyBookings', 'AnalyticsController::getMonthlyBookings');
$routes->get('/api/analytics/getTotalRevenue', 'AnalyticsController::getTotalRevenue');
$routes->get('analytics/most-used-cottages', 'AnalyticsController::getMostUsedCottages');
$routes->get('analytics/monthly-bookings', 'AnalyticsController::getMonthlyCottageBookings');


// Define API routes for Inventory

$routes->get('/inventory', 'InventoryController::index');         // List all inventory items
$routes->post('/addinventory', 'InventoryController::store');        // Create a new inventory item
$routes->post('/updateinventory/(:num)', 'InventoryController::update/$1'); // Update an existing inventory item
$routes->delete('/deleteinventory/(:num)', 'InventoryController::delete/$1'); // Delete an inventory item

$routes->post('/updateTable/(:num)', 'MainController::updateTable/$1');

