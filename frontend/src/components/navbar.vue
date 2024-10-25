<template>
  <Top />
  <div class="container-xxl bg-white p-0">
    <!-- Header Start -->
    <div class="container-fluid bg-dark px-0">
      <div class="row gx-0">
        <div class="col-lg-3 bg-dark d-none d-lg-block">
          <a
            href="/user"
            class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center"
          >
            <div class="image-container">
              <img
                :src="require('../assets/img/logo.png')"
                class="logo-image"
                alt="Eduardo's Logo"
              />
            </div>
          </a>
        </div>
        <div class="col-lg-9">
          <div class="row gx-0 bg-white d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
              <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                <i class="fa fa-envelope text-primary me-2"></i>
                <p class="mb-0">info@example.com</p>
              </div>
              <div class="h-100 d-inline-flex align-items-center py-2">
                <i class="fa fa-phone-alt text-primary me-2"></i>
                <p class="mb-0">Contact Number: (043) 288-7153</p>
              </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
              <div class="d-inline-flex align-items-center py-2">
                <button type="button" class="btn btn-link btn-floating mx-1">
                  <div class="icon-group">
                    <font-awesome-icon :icon="['fab', 'google']" />
                    <font-awesome-icon :icon="['fab', 'facebook']" />
                    <font-awesome-icon :icon="['fab', 'twitter']" />
                  </div>
                </button>
              </div>
            </div>
          </div>
          
          <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
  <a href="/user" class="navbar-brand d-block d-lg-none">
    <h1 class="m-0 text-primary text-uppercase">Eduardo's</h1>
  </a>
  <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
    <!-- Left side of navbar (links) -->
    <div class="navbar-nav mr-auto py-0">
      <router-link to="/user" class="nav-item nav-link" :class="{ active: $route.path === '/user' }">Home</router-link>
      <router-link to="/about" class="nav-item nav-link" :class="{ active: $route.path === '/about' }">About</router-link>
      <router-link to="/service" class="nav-item nav-link" :class="{ active: $route.path === '/service' }">Services</router-link>
      <router-link to="/shop" class="nav-item nav-link" :class="{ active: $route.path === '/shop' }">Shop</router-link>

      <!-- Dropdown for Reservations -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="reservationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Make a Reservation
        </a>
        <div class="dropdown-menu" aria-labelledby="reservationDropdown">
          <a class="dropdown-item" href="#" @click="showPackSelectionModal">Select Packs</a>
          <a class="dropdown-item" href="#" @click="showCottageModal">Cottage Reservation</a>
          <a class="dropdown-item" href="#" @click="showTableModal">Table Reservation</a>
        </div>
      </div>

      <!-- Dropdown for Pages -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Pages
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <router-link to="/team" class="dropdown-item">Our Team</router-link>
          <router-link to="/testimonial" class="dropdown-item">Testimonial</router-link>
        </div>
      </div>

      <router-link to="/contact" class="nav-item nav-link" :class="{ active: $route.path === '/contact' }">Contact</router-link>
    </div>

    <!-- Right side of navbar (notifications, cart, profile, auth) -->
    <div class="d-flex align-items-center">
      <!-- Notifications -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell"></i>
          <span class="badge rounded-pill badge-notification bg-danger small-badge">{{ notifications.length }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end bg-light m-0" aria-labelledby="notificationDropdown">
          <template v-if="notifications.length">
            <a v-for="(notification, index) in notifications" :key="index" class="dropdown-item" :href="notification.link">{{ notification.message }}</a>
          </template>
          <template v-else>
            <p class="dropdown-item">No new notifications</p>
          </template>
        </div>
      </div>

      <!-- Shopping Cart -->
      <router-link to="/shopcart" class="nav-link text-primary me-3">
        <i class="fa fa-shopping-cart"></i>
      </router-link>

      <!-- Profile and Auth Dropdown -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user"></i> 
        </a>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="#" @click="showProfileModal" data-bs-toggle="modal" data-bs-target="#profileModal">Profile</a>
          <div class="dropdown-divider"></div>
          <button @click="authAction" class="dropdown-item">
            <i :class="authIcon"></i> {{ authButtonText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div v-if="user">
          <h6>User Details</h6>
          <table class="table">
            <tbody>
              <tr>
                <th>Name</th>
                <td>{{ user.name }}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{{ user.email }}</td>
              </tr>
              <tr>
                <th>Address</th>
                <td>{{ user.address || 'N/A' }}</td>
              </tr>
              <tr>
                <th>Phone Number</th>
                <td>{{ user.number || 'N/A' }}</td>
              </tr>
            </tbody>
          </table>

          <h6>Cottage Bookings</h6>
          <table class="table">
            <thead>
              <tr>
                <th>Cottage Name</th>
                <th>Status</th>
                <th>Time</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="cottageBookings.length === 0">
                <td colspan="4">No cottage bookings found.</td>
              </tr>
              <tr v-for="booking in cottageBookings" :key="booking.cottagebooking_id">
                <td>{{ booking.cottage_name }}</td>
                <td>{{ booking.cottagebooking_status }}</td>
                <td>{{ booking.selectedTime }} - {{ booking.selectedTimeout }}</td>
                <td v-if="booking.cottagebooking_status === 'pending'">
                  <button class="btn btn-danger btn-sm" @click="cancelCottageBooking(booking.cottagebooking_id)">Cancel</button>
                </td>
              </tr>
            </tbody>
          </table>

          <h6>Reservations</h6>
          <table class="table">
            <thead>
              <tr>
                <th>Table Name</th>
                <th>Status</th>
                <th>Time</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="reservations.length === 0">
                <td colspan="4">No reservations found.</td>
              </tr>
              <tr v-for="reservation in reservations" :key="reservation.reservation_id">
                <td>{{ reservation.table_name }}</td>
                <td>{{ reservation.status }}</td>
                <td>{{ reservation.reservation_time }}</td>
                <td v-if="reservation.status === 'pending'">
                  <button class="btn btn-danger btn-sm" @click="cancelReservation(reservation.reservation_id)">Cancel</button>
                </td>
              </tr>
            </tbody>
          </table>

          <h6>Orders</h6>
          <table class="table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="orders.length === 0">
                <td colspan="4">No orders found.</td>
              </tr>
              <tr v-for="order in orders" :key="order.order_id">
                <td>{{ order.order_id }}</td>
                <td>{{ order.total_price }}</td>
                <td>{{ order.order_status }}</td>
                <td>
                  <!-- Products List -->
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Final Price</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-if="order.products && order.products.length > 0" v-for="product in order.products" :key="product.id">
                        <td>{{ product.prod_name }}</td>
                        <td>{{ product.quantity }}</td>
                        <td>{{ product.final_price }}</td>
                      </tr>
                      <tr v-else>
                        <td colspan="3">No products available for this order.</td>
                      </tr>
                    </tbody>
                  </table>

                  <!-- Cancel Button -->
                  <button v-if="order.order_status === 'pending'" 
                          class="btn btn-danger btn-sm" 
                          @click="cancelOrder(order.order_id)">
                    Cancel
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <h6>Bookings</h6>
          <table class="table">
            <thead>
              <tr>
                <th>Room Name</th>
                <th>Status</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="bookings.length === 0">
                <td colspan="5">No bookings found.</td>
              </tr>
              <tr v-for="booking in bookings" :key="booking.book_id">
                <td>{{ booking.room_name }}</td>
                <td>{{ booking.booking_status }}</td>
                <td>{{ booking.checkin }}</td>
                <td>{{ booking.checkout }}</td>
                <td v-if="booking.booking_status === 'pending'">
                  <button class="btn btn-danger btn-sm" @click="cancelBooking(booking.book_id)">Cancel</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else>
          <p>Loading user info...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" @click="editUser">Edit Profile</button>
      </div>
    </div>
  </div>
</div>











<!-- Modals -->
<!-- Pack Selection Modal -->
<div v-if="packSelectionModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Number of Packs</h5>
        <button type="button" class="close" @click="closePackSelectionModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="savePackSelectionAndRedirect">
          <div class="form-group">
            <label for="packs">Number of Packs</label>
            <input type="number" v-model="packs" class="form-control" id="packs" />
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div v-if="roomModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ selectedRooms.length > 1 ? 'Select Rooms' : selectedRooms[0].room_name }}</h5>
          <button type="button" class="close" @click="closeRoomModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div v-for="room in selectedRooms" :key="room.room_id" class="col-md-6 mb-4">
              <div class="card h-100">
                <img class="img-fluid" style="height: 200px; object-fit: cover;" 
                     :src="`https://eduardos-resort.online/backend/backend/public/uploads/${room.image}`" alt="" />
                <div class="card-body">
                  <h5 class="card-title">{{ room.room_name }}</h5>
                  <p class="card-text">{{ room.description }}</p>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Number of Packs: {{ room.packs }}</li>
                    <li class="list-group-item">Price: Php.{{ room.price }}</li>

                  </ul>
                  <input type="checkbox" v-model="room.selected"> <!-- Checkbox for selecting rooms -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-3">
          <button class="btn btn-primary" @click="bookSelectedRooms">Book A Luxury Room</button>
        </div>
      </div>
    </div>
  </div>  
  <Booking
  v-if="bookingModalVisible"
  :selected-room-ids="selectedRoomIds"
  :selected-room-names="selectedRoomNames"
  :downpayment="downpayment" 
  @update-downpayment="downpayment = $event" 
  @close="closeBookingModal"
/>






  </div>

<!-- Cottage Modal -->
<div v-if="cottageModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Select Cottage</h5>
          <button type="button" class="close" @click="closeCottageModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div v-for="cottage in cottages" :key="cottage.cottage_id" class="col-md-6 mb-4">
              <div class="card h-100">
                <img :src="`https://eduardos-resort.online/backend/backend/public/uploads/${cottage.cottage_image}`" class="card-img-top" :alt="cottage.cottage_name">
                <div class="card-body">
                  <h5 class="card-title">{{ cottage.cottage_name }}</h5>
                  <p class="card-text">{{ cottage.cottage_description }}</p>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Price: Php.{{ cottage.cottage_price }}</li>
                  </ul>
                  <button type="button" class="btn btn-primary" @click="openTimeSelectionModal(cottage)">Select Time</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Time Selection Modal -->
<div v-if="timeSelectionModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Time for {{ selectedCottage.cottage_name }}</h5>
        <button type="button" class="close" @click="closeTimeSelectionModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="bookCottage">
        <div class="modal-body">
          <!-- Start Time Input -->
          <label for="selectedTime">Start Time</label>
          <input 
            type="datetime-local" 
            v-model="selectedTime" 
            class="form-control" 
            :min="minDate" 
            @change="updateEndTime" 
            required
          >

          <!-- End Time Input (Readonly) -->
          <label for="selectedTimeout">End Time (6 hours later)</label>
          <input 
            type="datetime-local" 
            v-model="selectedTimeout" 
            class="form-control" 
            required 
            readonly
          >

          <!-- Full Payment -->
          <label for="downpayment">Full Payment (Php)</label>
          <input type="number" v-model.number="downpayment" class="form-control" readonly>

          <!-- Proof of Downpayment -->
          <label for="proofOfDownpayment">Proof of Downpayment</label>
          <input 
            type="file" 
            @change="handleFileUpload" 
            accept="image/*" 
            class="form-control" 
            required
          >

          <!-- GCASH Button -->
          <div class="d-flex justify-content-between mt-1">
            <button type="button" class="btn btn-info" @click="openGcashQRCode">
              <i class="fas fa-qrcode"></i> GCASH
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" :disabled="isLoading">Book Cottage</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- GCash QR Code Modal -->
<div v-if="showGcashModal" class="modal" tabindex="-1" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan GCash QR Code</h5>
        <button type="button" class="close" @click="closeGcashModal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center d-flex flex-column align-items-center">
        <!-- QR Code Image (GCash) -->
        <img src="../assets/img/gcash.jpg" alt="GCash QR Code" class="img-fluid" style="max-width: 100%; height: auto;"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeGcashModal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Table Modal -->
<div v-if="tableModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Table</h5>
        <button type="button" class="close" @click="closeTableModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div v-for="table in tables" :key="table.table_id" class="col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">{{ table.table_name }}</h5>
                <p class="card-text">Description: {{ table.table_description }}</p>
                <button @click="openMenuModal(table)" class="btn btn-primary">Select Menu & Time</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div v-if="menuModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Select Menu & Time for {{ currentTable.table_name }}</h5>
          <button type="button" class="close" @click="closeMenuModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="bookTableWithMenu">
            <section id="menu" class="menu section-bg">
              <div class="container-fluid" data-aos="fade-up">
                <div class="section-title text-center">
                  <h2>Menu</h2>
                  <p>Check Our Tasty Menu</p>
                </div>

                <!-- Dropdown for Menu Categories -->
                <div class="row" data-aos="fade-up" data-aos-delay="100">
                  <div class="col-lg-12 d-flex justify-content-center mb-3">
                    <label for="menuCategoriesDropdown">Select Category:</label>
                    <select id="menuCategoriesDropdown" class="form-control mx-2" v-model="currentFilter" @change="filterMenu(currentFilter)">
                      <option value="" disabled>Select a Category</option>
                      <option v-for="category in menuCategories" :key="category" :value="category">{{ category }}</option>
                    </select>
                  </div>
                </div>

                <!-- Menu Items Grid -->
                <div class="row menu-container" data-aos="fade-up" data-aos-delay="200" style="max-height: 400px; overflow-y: auto;">
                  <div v-for="item in filteredMenuItems" :key="item.menu_id" class="col-lg-4 col-md-6 col-sm-12 mb-4 d-flex align-items-stretch">
                    <div class="card h-100 w-100">
                      <img :src="`https://eduardos-resort.online/backend/backend/public/uploads/${item.item_image}`" class="card-img-top img-fluid" 
                        alt="Menu Item Image" style="height: auto; max-height: 200px; object-fit: cover; width: 100%; display: block;">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                          {{ item.item_name }} - ₱{{ item.item_price }}
                        </h5>
                        <div class="form-check mt-2">
                          <input type="checkbox" class="form-check-input" :id="'item-' + item.menu_id" :value="item.menu_id" v-model="selectedItems[item.menu_id]" @change="updateTotalPrice">
                          <label class="form-check-label" :for="'item-' + item.menu_id">Select</label>
                        </div>
                        <input type="number" v-model.number="itemQuantities[item.menu_id]" min="1" :disabled="!selectedItems[item.menu_id]" class="form-control mt-2" placeholder="Qty" @input="updateTotalPrice">
                        <p class="card-text mt-auto">
                          {{ item.item_description }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <div class="form-group">
              <label for="dateTimeSelection">Date & Time</label>
              <input 
                type="datetime-local" 
                id="dateTimeSelection" 
                class="form-control" 
                v-model="selectedDateTime" 
                required 
                :min="minDateTime()"  
              />
            </div>


            <div class="form-group">
              <label for="paymentAmount">Payment Amount (₱)</label>
              <input type="number" id="paymentAmount" class="form-control" v-model.number="totalPaymentAmount" readonly>
            </div>
            <div class="form-group">
              <label for="paymentMethod">Payment Method</label>
              <select id="paymentMethod" class="form-control" v-model="paymentMethod" required>
                <option value="">Select Payment Method</option>
                <option value="GCash">GCash</option>
              </select>
            </div>

            <!-- Proof of Payment Input -->
            <div class="form-group">
              <label for="proofOfPayment">Proof of Payment</label>
              <input type="file" id="proofOfPayment" class="form-control" @change="handleFileUpload2" accept="image/*" required>
            </div>

            <!-- Button Section for GCASH and PayPal -->
            <div class="d-flex justify-content-between mt-1">
              <div>
                <button type="button" class="btn btn-info" @click="openGcashQRCode2">
                  <i class="fas fa-qrcode"></i> GCASH
                </button>
              </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Book Table with Menu</button>
          </form>
        </div>
      </div>
    </div>
  </div>


<!-- GCash QR Code Modal -->
<div v-if="showGcashModal2" class="modal" tabindex="-1" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan GCash QR Code</h5>
        <button type="button" class="close" @click="closeGcashModal2" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center d-flex flex-column align-items-center">
        <!-- QR Code Image (GCash) -->
        <img src="../assets/img/gcash.jpg" alt="GCash QR Code" class="img-fluid" style="max-width: 100%; height: auto;"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeGcashModal2">Close</button>
      </div>
    </div>
  </div>
</div>











    
    <!-- Notification Component -->
    <Notification v-if="notification.show" :type="notification.type" :message="notification.message" />



        </div>
      </div>
    </div>


    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
      ><i class="bi bi-arrow-up"></i
    ></a>
  <router-view />
  <Notification
  :show="notification.show"
  :type="notification.type"
  :message="notification.message"
/>

</template>

<script>
import Top from "@/components/Top.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import axios from "axios";
import Notification from '@/components/Notification.vue';
import Booking from "@/views/Booking.vue"; // Adjust the path as necessary


export default {
  components: {
    Top,
    FontAwesomeIcon,
    Notification,
    Booking
  },
  data() {
    return {
      isLoading: false,
      menuModalVisible: false,
      currentTable: {},
      currentFilter: '',
      menuCategories: [],
      filteredMenuItems: [],
      selectedItems: {},
      itemQuantities: {},
      selectedDateTime: "", // Initialize correctly
      paymentMethod: '',
      totalPaymentAmount: 0,
      showGcashModal: false,
      showGcashModal2: false,
      user: null,
      errors: {},
      query: "",
      data: [],
      showData: false,
      showNoMatchMessage: false,
      notifications: [],
      showNotifications: false,
      packSelectionModalVisible: false,
      packs: 3,
      roomModalVisible: false,
      selectedRooms: [],
      cottageModalVisible: false,
      tableModalVisible: false,
      tables: [],
      menus: [],
      selectedMenuItem: null,
      menuItems: [],
      cottages: [],
      timeSelectionModalVisible: false,
      selectedCottage: null,
      selectedTime: '',
      selectedTimeout: '',
      downpayment: 0,
      currentDateTime: new Date(),
      notification: {
        show: false,
        type: "",
        message: "",
      },
      menuCategories: ['All', 'breakfast', 'rice', 'noodles', 'asian curry', 'soup', 'refreshments', 'sizzling', 'filipino style', 'vegetables']
    };
  },
  
  computed: {
    isAuthenticated() {
      return !!sessionStorage.getItem("token");
    },
    authButtonText() {
      return this.isAuthenticated ? 'Logout' : 'Login';
    },
    authIcon() {
      return this.isAuthenticated ? 'fas fa-power-off logout-icon' : 'fas fa-sign-in-alt login-icon';
    },


  },
  created() {
    // Initialize data and fetch items
    this.fetchMenuItems();
    this.filterMenu('*');
    this.fetchCottages();
  },
  methods: {
        minDateTime() {
    // Create a new Date object for the current date and time
    const now = new Date();
    // Return the ISO string format for the min attribute
    return now.toISOString().slice(0, 16); // Format to "YYYY-MM-DDTHH:MM"
  },
    updateEndTime() {
  if (this.selectedTime) {
    const startTime = new Date(this.selectedTime);
    
    // Add 6 hours (6 * 60 * 60 * 1000 milliseconds)
    const endTime = new Date(startTime.getTime() + 6 * 60 * 60 * 1000);
    
    // Adjust for the user's time zone and format the end time correctly
    const timezoneOffset = endTime.getTimezoneOffset() * 60000; // Offset in milliseconds
    const adjustedEndTime = new Date(endTime.getTime() - timezoneOffset);

    // Format the end time as 'YYYY-MM-DDTHH:mm' for 'datetime-local'
    this.selectedTimeout = adjustedEndTime.toISOString().slice(0, 16);
  }
},

    closeRoomModal() {
    this.roomModalVisible = false; // Close the modal
  },
  bookSelectedRooms() {
  const selectedRooms = this.selectedRooms.filter(room => room.selected); // Filter selected rooms
  console.log("Selected Rooms:", selectedRooms); // Debug: Log selected rooms

  if (selectedRooms.length > 0) {
    const selectedRoomIds = selectedRooms.map(room => room.room_id); // Get selected room IDs
    const selectedRoomNames = selectedRooms.map(room => room.room_name); // Get selected room names

    const totalPrice = selectedRooms.reduce((sum, room) => {
      const sanitizedPrice = room.price.replace(/,/g, ''); // Remove commas from price string
      const roomPrice = Number(sanitizedPrice); // Convert to number
      console.log("Sanitized Room Price (as number):", roomPrice, "for Room:", room.room_name); // Debug: Log each room price

      if (isNaN(roomPrice)) {
        console.error(`Invalid price for room ${room.room_name}: ${room.price}`);
        return sum; // Skip invalid prices
      }

      return sum + roomPrice;
    }, 0);

    console.log("Total Price Calculated:", totalPrice); // Debug: Log total price
    this.selectedRoomNames = selectedRoomNames; // Store selected room names

    this.selectedRoomIds = selectedRoomIds; // Store selected room IDs
    this.totalRoomPrice = totalPrice; // Store total price

    // Calculate downpayment (30%)
    if (!isNaN(totalPrice) && totalPrice > 0) {
      this.downpayment = totalPrice * 0.3; // Calculate downpayment
    } else {
      this.downpayment = 0; // Set downpayment to 0 if price is invalid
    }

    console.log("Downpayment Calculated:", this.downpayment); // Debug: Log downpayment value

    // Emit the updated downpayment to the parent
    this.$emit('update-downpayment', this.downpayment); // Emit event to parent

    this.closeRoomModal(); // Close room selection modal
    this.bookingModalVisible = true; // Open booking modal
  } else {
    alert("Please select at least one room to book.");
  }
},


  closeBookingModal() {
    this.bookingModalVisible = false; // Close the booking modal
  },
  updateTotalPrice() {
    this.totalPaymentAmount = 0; // Reset total

    // Loop through selected items to calculate the total
    for (const itemId in this.selectedItems) {
      if (this.selectedItems[itemId]) {
        const item = this.filteredMenuItems.find(i => i.menu_id == itemId); // Find item details
        const quantity = this.itemQuantities[itemId] || 1; // Get quantity, default to 1 if not set

        if (item) {
          // Calculate price and update total
          this.totalPaymentAmount += item.item_price * quantity;
        }
      }
    }
  },


    openGcashQRCode() {
      this.showGcashModal = true;
    },
    closeGcashModal() {
      this.showGcashModal = false;
    },
    openGcashQRCode2() {
      this.showGcashModal2 = true;
    },
    closeGcashModal2() {
      this.showGcashModal2 = false;
    },
    handleFileUpload2(event) {
        this.proofOfPayment = event.target.files[0]; // Store the file
    },
    handleFileUpload(event) {
    this.proofOfDownpayment = event.target.files[0];
  },

    async cancelCottageBooking(bookingId) {
    try {
      await axios.post(`api/cottageBookings/${bookingId}`); // Adjust API endpoint
      // Refresh the data or handle the UI update accordingly
      this.showProfileModal(); // Re-fetch user data to reflect changes
    } catch (error) {
      console.error("Error cancelling booking:", error);
    }
  },
  async cancelReservation(reservationId) {
    try {
      await axios.post(`api/reservations/${reservationId}`); // Adjust API endpoint
      this.showProfileModal(); // Refresh data
    } catch (error) {
      console.error("Error cancelling reservation:", error);
    }
  },
  async cancelOrder(orderId) {
    try {
      await axios.post(`api/orders/${orderId}`); // Adjust API endpoint
      this.showProfileModal(); // Refresh data
    } catch (error) {
      console.error("Error cancelling order:", error);
    }
  },
  async showProfileModal() {
  const id = sessionStorage.getItem("id");
  try {
    const userResponse = await axios.get(`/user/${id}`); // Adjust endpoint
    console.log("API Response:", userResponse.data); // Log the response

    // Destructure the response for easier access
    const {
      user,
      cottageBookings = [], // Fallback to empty array
      reservations = [], // Fallback to empty array
      orders = [], // Fallback to empty array
      bookings = [], // Fallback to empty array
    } = userResponse.data;

    // Set the data to respective variables
    this.user = user || null; // Ensure user is set to null if not present
    this.cottageBookings = cottageBookings;
    this.reservations = reservations;
    this.orders = orders;
    this.bookings = bookings;

  } catch (error) {
    console.error("Error fetching user data:", error);
    
    // Reset data on error
    this.user = null;
    this.cottageBookings = [];
    this.reservations = [];
    this.orders = [];
    this.bookings = [];
  }
},

    authAction() {
      if (this.isAuthenticated) {
        this.logout();
      } else {
        this.$router.push('/login');
      }
    },
    checkAuth() {
  const userId = sessionStorage.getItem("id");
  if (!userId) {
    // Redirect to login if the user is not authenticated
    this.$router.push({ name: 'LoginForm' });
    return false; // Stop further execution
  }
  return true; // Continue if authenticated
},

    getMinDateTime() {
      const now = new Date();
      now.setMinutes(now.getMinutes() - now.getMinutes() % 15); 
      return now.toISOString().slice(0, 16); 
    },

    // Toggle item selection
    selectMenuItem(item) {
    console.log("Item selected:", item); // Log item to verify structure
    if (item && item.menu_id) {
      const isSelected = !this.selectedItems[item.menu_id]; // Toggle selection state

      // Update selection state
      this.$set(this.selectedItems, item.menu_id, isSelected);
      
      // Set quantity to 1 if the item is selected, otherwise remove the quantity
      if (isSelected) {
        this.$set(this.itemQuantities, item.menu_id, 1); // Set quantity to 1
      } else {
        this.$delete(this.itemQuantities, item.menu_id); // Remove quantity if deselected
      }

      this.updateTotalPrice(); // Update total price whenever an item is selected/deselected
    } else {
      console.error("Item or menu_id is undefined");
    }
  },

  async bookTableWithMenu() {
  // Step 1: Validate the selected date and time
  if (!this.selectedDateTime) {
    this.showNotification('error', 'Please select a date and time.');
    return;
  }

  // Ensure the selected date and time is in the future
  const reservationDateTime = new Date(this.selectedDateTime);
  if (reservationDateTime <= new Date()) {
    this.showNotification('error', 'The selected date and time must be in the future.');
    return;
  }

  // Step 2: Validate selected menu items
  const selectedItemIds = Object.keys(this.selectedItems).filter(itemId => this.selectedItems[itemId]);
  if (selectedItemIds.length === 0) {
    this.showNotification('error', 'Please select at least one menu item.');
    return;
  }

  // Prepare order items for submission
  const orderItems = selectedItemIds.map(itemId => ({
    menu_id: parseInt(itemId, 10),
    quantity: parseInt(this.itemQuantities[itemId] || 1, 10) // Default to 1 if not specified
  }));

  // Step 3: Prepare data for submission
  const userId = parseInt(sessionStorage.getItem("id"), 10);
  const tableId = this.currentTable ? parseInt(this.currentTable.table_id, 10) : null;
  const paymentAmount = parseFloat(this.totalPaymentAmount || 0).toFixed(2);
  const paymentMethod = this.paymentMethod || 'Credit Card';
  const reservationTimeISO = new Date(this.selectedDateTime).toISOString(); // Ensure it’s in ISO 8601 format

  console.log("Payment Amount:", this.totalPaymentAmount);

  const formData = new FormData();
  formData.append('user_id', userId);
  formData.append('table_id', tableId);
  formData.append('reservation_time', reservationTimeISO);
  formData.append('payment_amount', paymentAmount); // Ensure the amount is fixed to 2 decimal points
  formData.append('payment_method', paymentMethod);
  
  // Step 4: Handle proof of payment (if any)
  if (this.proofOfPayment) {
    formData.append('proof_of_payment', this.proofOfPayment); // Attach file
  } else {
    this.showNotification('error', 'Please upload proof of payment.');
    return; // Early exit if proof of payment is not provided
  }

  // Step 5: Add order items to form data
  orderItems.forEach((item, index) => {
    formData.append(`order_items[${index}][menu_id]`, item.menu_id);
    formData.append(`order_items[${index}][quantity]`, item.quantity);
  });

  // Step 6: Send the data to the backend
  try {
    const createResponse = await axios.post("/reservations", formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    // Step 7: Handle successful response
    if (createResponse.status === 200) {
      this.tableModalVisible = false; // Close modal on success
      this.showNotification('success', 'Table reservation with menu successful!');
      this.resetForm(); // Reset form after success
    }
  } catch (error) {
    console.error("Error reserving table with menu", error);
    // Step 8: Handle error response
    if (error.response && error.response.data && error.response.data.message) {
      this.showNotification('error', error.response.data.message);
    } else {
      this.showNotification('error', 'Reservation failed. Please try again.');
    }
  }
},

        showNotification(type, message) {
            this.notification = { show: true, type, message };
            setTimeout(() => this.notification.show = false, 2000);
        },
        resetForm() {
            this.selectedDateTime = "";
            this.selectedItems = {};
            this.itemQuantities = {};
            this.closeMenuModal();
        },
        checkAuth() {
            // Add your authentication check logic here
            return true;
        },
        closeMenuModal() {
            // Logic to close the menu modal
        },
        async bookCottage() {
  // Reset notification visibility
  this.notification.show = false;
  this.isLoading = true; // Start loading

  try {
    // Retrieve user ID and cottage ID
    const userId = sessionStorage.getItem("id");
    const cottageId = this.selectedCottage ? this.selectedCottage.cottage_id : null;

    // Check if all required fields are filled
    if (!userId || !cottageId || !this.selectedTime || !this.selectedTimeout || !this.downpayment || !this.proofOfDownpayment) {
      throw new Error("Missing required parameters. Please ensure all fields are filled.");
    }

    // Prepare FormData for the API request
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('selectedTime', new Date(this.selectedTime).toISOString());
    formData.append('selectedTimeout', new Date(this.selectedTimeout).toISOString());
    formData.append('cottage_id', cottageId);
    formData.append('downpayment', this.downpayment);

    // Ensure the file exists and is valid
    if (this.proofOfDownpayment) {
      formData.append('proofOfDownpayment', this.proofOfDownpayment);
    } else {
      throw new Error("Please upload the proof of downpayment.");
    }

    // Send formData using axios
    const response = await axios.post("cottageBooking", formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    // Handle successful response
    if (response.status === 200) {
      this.notification = {
        show: true,
        type: 'success',
        message: response.data.message || 'Booking successful!'
      };

      // Set a timeout to hide the notification after 3 seconds (3000 milliseconds)
      setTimeout(() => {
        this.notification.show = false; // Hide the notification after the timeout
      }, 3000);

      // Reset the booking form
      this.resetBookingForm(); // Use this method to reset form
    }
  } catch (error) {
    console.error("Error booking", error);

    // Improve error handling for different response statuses
    this.notification = {
      show: true,
      type: 'error',
      message: error.response?.data?.message || "Booking failed. Please try again."
    };

    // Optional: Additional logging for debugging
    if (error.response) {
      console.error("Response data:", error.response.data);
      console.error("Response status:", error.response.status);
    } else {
      console.error("Error message:", error.message);
    }
  }
  finally {
    this.isLoading = false; // End loading
  }
},
resetBookingForm() {
  this.selectedTime = "";
  this.selectedTimeout = "";
  this.downpayment = "";
  this.proofOfDownpayment = null;
  this.timeSelectionModalVisible = false; // Close the modal
},
    calculateCheckoutTime() {
      if (this.selectedTime) {
        let checkinDate = new Date(this.selectedTime);
        let timezoneOffset = checkinDate.getTimezoneOffset();
        checkinDate.setHours(checkinDate.getHours() + 6);
        checkinDate.setMinutes(checkinDate.getMinutes() - timezoneOffset);
        let checkoutTimeString = checkinDate.toISOString().slice(0, 16);
        this.selectedTimeout = checkoutTimeString;
      }
    },

    openTimeSelectionModal(cottage) {
      this.selectedCottage = cottage;
      this.timeSelectionModalVisible = true;
      this.cottageModalVisible = false;
      this.downpayment = cottage.cottage_price; // Set full payment to the price of the selected cottage

    },

    closeTimeSelectionModal() {
      this.timeSelectionModalVisible = false;
    },

    fetchCottages() {
      axios.get('/getCottage')
        .then(response => {
          this.cottages = response.data;
        })
        .catch(error => {
          console.error('Error fetching cottages:', error);
          alert('Failed to load cottages. Please try again.');
        });
    },

    fetchMenuItems() {
      axios.get('/getItem')
        .then(response => {
          this.menuItems = response.data;
          this.filterMenu(this.currentFilter);
        })
        .catch(error => {
          console.error('Failed to load menu items', error);
          this.menuItems = [];
        });
    },

    filterMenu(item_category) {
      this.currentFilter = item_category;
      if (this.menuItems && this.menuItems.length > 0) {
        this.filteredMenuItems = this.menuItems.filter(item => item_category === '*' || item.item_category === item_category);
      } else {
        this.filteredMenuItems = [];
      }
    },

    selectMenuItem(item) {
      this.selectedMenuItem = item;
    },

    openMenuModal(table) {
      this.currentTable = table;
      this.menuModalVisible = true;
    },

    closeMenuModal() {
      this.menuModalVisible = false;
    },

    bookMenuAndTime() {
      console.log(`Booking ${this.selectedMenu} for ${this.currentTable.table_name} at ${this.selectedTime}`);
      this.closeMenuModal();
    },

    showCottageModal() {
      const userId = sessionStorage.getItem("id");
  if (!userId) {
    this.$router.push({ name: 'LoginForm' });
  }
      this.fetchCottages();
      this.cottageModalVisible = true;
    },

    closeCottageModal() {
      this.cottageModalVisible = false;
    },

    showTableModal() {
      const userId = sessionStorage.getItem("id");
  if (!userId) {
    this.$router.push({ name: 'LoginForm' });
  }
      this.tableModalVisible = true;
      this.fetchTables();
    },

    closeTableModal() {
      this.tableModalVisible = false;
    },

    fetchTables() {
      axios.get('/getTable')
        .then(response => {
          this.tables = response.data;
        })
        .catch(error => {
          console.error('Error fetching tables:', error);
          alert('Failed to load tables. Please try again.');
        });
    },
    showPackSelectionModal() {
      const userId = sessionStorage.getItem("id");
  if (!userId) {
    this.$router.push({ name: 'LoginForm' });
  }
      this.packSelectionModalVisible = true;
    },

    closePackSelectionModal() {
      this.packSelectionModalVisible = false;
    },

    async savePackSelectionAndRedirect() {
      this.closePackSelectionModal();

      axios
        .get(`/rooms/total_pax/${this.packs}`)
        .then((response) => {
          console.log(response.data);

          const roomDetails = response.data;

          if (roomDetails && roomDetails.length > 0) {
            this.selectedRooms = roomDetails;
            this.roomModalVisible = true; 
          }
        })
        .catch((error) => {
          console.error("Error fetching rooms:", error);
        });
    },

    async logout() {
  try {
    // Send logout request to the backend
    const response = await axios.post("/logout");

    if (response.status === 200) {
      console.log("Logout successful");

      // Clear session storage data
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("id");

      // Clear local storage data (if used)
      localStorage.clear();

      // Clear all component state related to the user
      this.userInfo = {}; // Clear any user-specific data in the component state
      this.user = []; // Clear user data array
      this.notifications = []; // Clear notifications if applicable
      this.eventName = ""; // Clear event-related data
      this.eventTheme = "";
      this.eventDate = "";
      this.manifest = ""; // Clear manifest-related data
      this.hasBooking = false; // Reset booking status
      this.feed = []; // Clear feedback data

      // Optionally, clear cookies if any exist
      document.cookie.split(";").forEach((cookie) => {
        document.cookie = cookie
          .replace(/^ +/, "")
          .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
      });

      // Show a success message
      this.notification = {
        show: true,
        type: 'success',
        message: 'You have been logged out successfully.'
      };

      // Reload the page after a short delay to allow the notification to be seen
      setTimeout(() => {
        window.location.reload(); // Reloads the current page
      }, 1000); // Adjust the delay as needed (1 second here)
    } else {
      throw new Error("Logout failed");
    }
  } catch (error) {
    console.error("Error during logout", error);

    // Optionally, display an error message to the user
    this.notification = {
      show: true,
      type: 'error',
      message: 'Logout failed. Please try again.'
    };

    // Hide the notification after a short delay
    setTimeout(() => {
      this.notification.show = false;
    }, 2000); // Hide after 2 seconds
  }
},


    async getData() {
      try {
        const response = await axios.get(`/search/${this.query}`);
        this.data = response.data;
        this.showNoMatchMessage = true;
        this.showData = true;
        console.log(this.data);
      } catch (error) {
        console.error(error);
      }
    },

    async fetchNotifications() {
      const id = sessionStorage.getItem("id");
      try {
        const response = await axios.get(`/getNotif/${id}`);
        this.notifications = response.data;
      } catch (error) {
        console.error("Error fetching notifications:", error);
      }
    },

    closeDataList(event) {
      const appElement = this.$refs.appRef;
      if (appElement && !appElement.contains(event.target)) {
        this.showData = false;
        this.data = [];
        this.query = "";
      }
    },
  },
  created() {
    this.fetchMenuItems();
    this.filterMenu('*');
    this.fetchCottages();
  },
  mounted() {
    this.fetchNotifications();
    document.addEventListener("click", this.closeDataList);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.closeDataList);
  },
  watch: {
    selectedTime(newValue) {
      this.calculateCheckoutTime();
    },
  },
};
</script>

<style>
@import "@/assets/css/bootstrap.min.css";
@import "@/assets/css/style.css";

/* CSS for responsive modal */
.modal-dialog {
  max-width: 95vw; /* Adjust the maximum width as needed */
}

@media (min-width: 576px) { /* Small devices (landscape phones) */
  .modal-dialog {
    max-width: 90vw;
  }
}
.menu-item input[type="number"] {
  width: 60px;
  margin-left: 10px;
}

.menu-item input[type="checkbox"] {
  margin-right: 10px;
}

.menu-item label {
  cursor: pointer;
}


@media (min-width: 768px) { /* Medium devices (tablets) */
  .modal-dialog {
    max-width: 85vw;
  }
}

@media (min-width: 992px) { /* Large devices (desktops) */
  .modal-dialog {
    max-width: 80vw;
  }
}

.modal-body {
  max-height: 80vh; /* Adjust the max-height as needed */
  overflow-y: auto; /* Add vertical scrollbar when content exceeds max-height */
}




.card {
  border: 1px solid #ccc;
  border-radius: 8px;
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-img-top {
  max-height: 200px;
  object-fit: cover;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}

.card-body {
  padding: 1.25rem;
}

.card-title {
  font-size: 1.25rem;
}

.card-text {
  color: #6c757d;
}

.card-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #e9ecef;
}

.card {
  border: 1px solid #ccc;
  border-radius: 8px;
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-img-top {
  max-height: 200px;
  object-fit: cover;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
}

.card-body {
  padding: 1.25rem;
}

.card-title {
  font-size: 1.25rem;
}

.card-text {
  color: #6c757d;
}

.card-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #e9ecef;
}



.image-container {
  text-align: center;
}

.logo-image {
  max-width: 40%;

  height: 40%;
  width: 50%;
}

.logout-logo-btn {
  display: flex;
  align-items: center;
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.logout-icon {
  margin-right: 8px;
}

.icon-group {
  display: flex;
  gap: 10px;
}

/* CSS for styling router-link like a button */
.pack-links router-link {
  display: inline-block;
  padding: 8px 16px;
  text-decoration: none;
  color: #fff;
  background-color: #007bff;
  border: 1px solid transparent;
  border-radius: 4px;
  transition: background-color 0.3s ease, border-color 0.3s ease,
    color 0.3s ease;
}

.pack-links router-link:hover {
  background-color: #0056b3;
  border-color: #0056b3;
  color: #fff;
}

.pack-links router-link:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.pack-links router-link:active {
  background-color: #0056b3;
  border-color: #0056b3;
  color: #fff;
}

/* Active style for router-link */
.pack-links router-link.active {
  background-color: #007bff;
  border-color: #007bff;
}
#menu-filters {
  padding: 0;
  margin: 0;
}

#menu-filters li {
  cursor: pointer;
  text-align: center;
  padding: 10px;
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  margin-bottom: 10px;
  transition: background-color 0.3s, color 0.3s;
}
/* Ensure the modal is full-width with some padding */
.modal-dialog {
  max-width: 90vw; /* Adjust this percentage to control the width */
  width: 90vw; /* Full width with some margin */
  margin: 1.75rem auto; /* Center the modal with vertical margin */
}

/* Center the modal vertically */
.modal-dialog-centered {
  display: flex;
  justify-content: center;
  align-items: center;
  height: calc(100% - 1.75rem); /* Adjust based on vertical margin */
}

/* Adjust modal content to full width */
.modal-content {
  border-radius: 0.5rem;
}

/* Ensure the modal header and body are styled */
.modal-header .close {
  margin: -1rem -1rem -1rem auto;
}

/* Modal Container */
.modal-dialog {
  width: 100%; /* Full width of the screen */
  max-width: 1200px; /* Maximum width to prevent it from being too wide on large screens */
  margin: 1.75rem auto; /* Vertical margin for centering */
}

/* Modal Content */
.modal-content {
  border-radius: 0.5rem; /* Rounded corners */
  width: 100%; /* Full width within the dialog */
  height: auto; /* Adjust height based on content */
}

/* Modal Header */
.modal-header {
  border-bottom: 1px solid #dee2e6;
}

/* Close Button */
.modal-header .close {
  margin: -1rem -1rem -1rem auto;
  font-size: 1.5rem;
}

/* Menu Filters */
#menu-filters {
  padding: 0;
  margin: 0;
  list-style: none;
}

#menu-filters li {
  cursor: pointer;
  text-align: center;
  padding: 12px 10px;
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  margin-bottom: 10px;
  transition: background-color 0.3s, color 0.3s;
}

#menu-filters li:hover {
  background-color: #e2e6ea;
}

.filter-active {
  background-color: #007bff;
  color: #fff;
  border-color: #007bff;
}

/* Menu Item */
.menu-item {
  margin-bottom: 20px;
}

/* Menu Content */
.menu-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
}
.modal-body {
  padding: 20px; /* Add some padding for aesthetics */
}

.modal-content {
  max-width: 90%; /* Set maximum width for modal content */
}

@media (max-width: 768px) {
  .modal-dialog {
    width: 100%; /* Ensure the modal takes full width on small screens */
    margin: 0; /* Remove margins */
  }
}


/* Menu Ingredients */
.menu-ingredients {
  font-size: 0.9rem;
  color: #6c757d;
}

/* Adjustments for smaller screens */
@media (max-width: 767px) {
  .modal-dialog {
    margin: 1rem auto;
  }

  #menu-filters li {
    padding: 8px;
  }
}

</style>
