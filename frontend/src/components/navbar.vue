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
    <button
      type="button"
      class="navbar-toggler"
      data-bs-toggle="collapse"
      data-bs-target="#navbarCollapse"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
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

        <!-- Auth Button -->
        <button @click="authAction" class="btn btn-primary logout-logo-btn">
          <i :class="authIcon"></i> {{ authButtonText }}
        </button>
      </div>  
    </div>
  </nav>

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

<!-- Room Modal -->
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
              <img class="img-fluid menu" style="width: 200%; max-width: 500px; height: 330px;"
              :src="`http://localhost:8080/uploads/${room.image}`" alt="" />
                            <div class="card-body">
                <h5 class="card-title">{{ room.room_name }}</h5>
                <p class="card-text">{{ room.description }}</p>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Number of Packs: {{ room.packs }}</li>
                  <li class="list-group-item">Price: Php.{{ room.price }} | 500 {{ room.downpayment }}</li>
                  <li class="list-group-item">Number of Beds: {{ room.bed }}</li>
                  <li class="list-group-item">Number of Baths: {{ room.bath }}</li>
                </ul>
                <input type="checkbox" v-model="room.selected">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-3">
        <button @click="bookSelectedRooms" class="btn btn-primary">Book Selected Rooms</button>
      </div>
    </div>
  </div>
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
              <img :src="require('@/assets/img/' + cottage.cottage_image)" class="card-img-top" :alt="cottage.cottage_name">
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
          <input type="datetime-local" v-model="selectedTime" class="form-control" :min="minDate" required>
          <input type="datetime-local" v-model="selectedTimeout" class="form-control" required readonly>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Book Cottage</button>
        </div>
      </form>
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
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Price: Php{{ table.table_price }}</li>
                </ul>
                <button @click="openMenuModal(table)" class="btn btn-primary">Select Menu & Time</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Menu Modal -->
<div v-if="menuModalVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
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
            <div class="container" data-aos="fade-up">
              <div class="section-title">
                <h2>Menu</h2>
                <p>Check Our Tasty Menu</p>
              </div>
              <div class="row" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-12 d-flex justify-content-center">
                  <ul id="menu-filters" class="row list-unstyled">
                    <li v-for="category in menuCategories" :key="category" class="col-md-4 col-lg-3 mb-2" @click="filterMenu(category)" :class="{ 'filter-active': currentFilter === category }">
                      {{ category }}
                    </li>
                  </ul>
                </div>
              </div>
              <div class="row menu-container" data-aos="fade-up" data-aos-delay="200">
                <div v-for="item in filteredMenuItems" :key="item.menu_id" class="col-lg-6 menu-item" :class="`filter-${item.item_category}`">
                  <div class="menu-content">
                    <input type="checkbox" :id="'item-' + item.menu_id" :value="item.menu_id" v-model="selectedItems[item.menu_id]">
                    <label :for="'item-' + item.menu_id">
                      {{ item.item_name }} - ${{ item.item_price }}
                    </label>
                    <input type="number" v-model.number="itemQuantities[item.menu_id]" min="1" :disabled="!selectedItems[item.menu_id]" class="ml-2" placeholder="Qty">
                  </div>
                  <div class="menu-ingredients">
                    {{ item.item_description }}
                  </div>
                </div>
              </div>
            </div>
          </section>
          <div class="form-group">
            <label for="dateTimeSelection">Date & Time</label>
            <input type="datetime-local" id="dateTimeSelection" class="form-control" v-model="selectedDateTime" required :min="minDateTime">
          </div>
          <div class="form-group">
            <label for="paymentAmount">Payment Amount (₱)</label>
            <input type="number" id="paymentAmount" class="form-control" v-model.number="paymentAmount" min="0" step="0.01" required>
          </div>
          <div class="form-group">
            <label for="paymentMethod">Payment Method</label>
            <select id="paymentMethod" class="form-control" v-model="paymentMethod" required>
              <option value="" disabled>Select Payment Method</option>
              <option value="Credit Card">Credit Card</option>
              <option value="Cash">Cash</option>
              <option value="Paypal">Paypal</option>
              <!-- Add other payment methods as needed -->
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Book Table with Menu</button>
        </form>
      </div>
    </div>
  </div>
</div>







    
    <!-- Notification Component -->
    <Notification v-if="notification.show" :type="notification.type" :message="notification.message" />



        </div>
      </div>
    </div>

    <div class="app" ref="appRef">
      <form class="d-flex me-2" @submit.prevent="getData">
        <input
          v-model="query"
          class="form-control"
          type="search"
          placeholder="Search"
          aria-label="Search"
        />
      </form>
      <ul class="list-group" v-show="showData">
        <li class="list-group-item" v-for="(item, index) in data" :key="index">
          {{ item.matchedWord }}
        </li>
        <li class="list-group-item" v-if="showNoMatchMessage && !data.length">
          No matching word found
        </li>
      </ul>
    </div>
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
      ><i class="bi bi-arrow-up"></i
    ></a>
  </div>
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

export default {
  components: {
    Top,
    FontAwesomeIcon,
    Notification
  },
  data() {
    return {
      errors: {}, // To store validation error messages
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
      menuModalVisible: false,
      currentTable: null,
      filteredMenuItems: [],
      currentFilter: '*',
      selectedMenuItem: null,
      menuItems: [],
      cottages: [],
      timeSelectionModalVisible: false,
      selectedCottage: null,
      selectedTime: '',
      selectedTimeout: '',
      minDate: new Date().toISOString().slice(0, 16),
      selectedDateTime: "", // To hold the combined date and time
      itemQuantities: {}, // Track quantities by item IDs
      selectedItems: {}, // Track selected items by their IDs
      minDateTime: this.getMinDateTime(), // Minimum date-time for selection
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
    }
  },
  created() {
    // Initialize data and fetch items
    this.fetchMenuItems();
    this.filterMenu('*');
    this.fetchCottages();
  },
  methods: {
    authAction() {
      if (this.isAuthenticated) {
        this.logout();
      } else {
        this.$router.push('/login');
      }
    },
    // Method to check if the user is authenticated
    checkAuth() {
      const userId = sessionStorage.getItem("id");
      if (!userId) {
        this.$router.push({ name: 'LoginForm' });
        return false;
      }
      return true;
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
                this.$set(this.selectedItems, item.menu_id, !this.selectedItems[item.menu_id]);
            } else {
                console.error("Item or menu_id is undefined");
            }
        },
        async bookTableWithMenu() {
    if (!this.checkAuth()) return;

    if (!this.selectedDateTime) {
        this.showNotification('error', 'Please select date and time.');
        return;
    }

    // Initialize reservationDateTime
    const reservationDateTime = new Date(this.selectedDateTime);

    // Ensure the selected date and time is in the future
    if (reservationDateTime <= new Date()) {
        this.showNotification('error', 'The selected date and time must be in the future.');
        return;
    }

    // Convert to ISO 8601 format manually
    const reservationTimeISO = reservationDateTime.toISOString().replace(/(\.\d{3})(\d+)(Z)$/, '$1$3'); // Optional: remove milliseconds

    // Ensure at least one menu item is selected
    const selectedItemIds = Object.keys(this.selectedItems).filter(itemId => this.selectedItems[itemId]);
    if (selectedItemIds.length === 0) {
        this.showNotification('error', 'Please select at least one menu item.');
        return;
    }

    // Prepare the order items payload
    const orderItems = selectedItemIds.map(itemId => ({
        menu_id: parseInt(itemId, 10), // Ensure menu_id is an integer
        quantity: parseInt(this.itemQuantities[itemId] || 1, 10) // Ensure quantity is an integer
    }));

    // Extract userId and tableId
    const userId = parseInt(sessionStorage.getItem("id"), 10); // Ensure userId is an integer
    const tableId = this.currentTable ? parseInt(this.currentTable.table_id, 10) : null; // Ensure tableId is an integer

    // Check for missing required parameters
    if (!userId || !tableId || !this.selectedDateTime) {
        this.showNotification('error', 'Missing required parameters. Please ensure all fields are filled.');
        return;
    }

    // Prepare payment details (dummy values, replace with actual user input if needed)
    const paymentAmount = parseFloat(this.paymentAmount || 0).toFixed(2); // Ensure payment amount is a float
    const paymentMethod = this.paymentMethod || 'Credit Card'; // Replace with actual method if needed

    // Log the payload for debugging
    console.log('Payload being sent:', {
        user_id: userId,
        table_id: tableId,
        reservation_time: reservationTimeISO,
        order_items: orderItems,
        payment_amount: paymentAmount,
        payment_method: paymentMethod
    });

    try {
        // Send the request to the server to create the reservation
        const createResponse = await axios.post("/reservations", {
            user_id: userId,
            table_id: tableId,
            reservation_time: reservationTimeISO,
            order_items: orderItems,
            payment_amount: paymentAmount,
            payment_method: paymentMethod
        }, {
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (createResponse.status === 200) {
            // Handle successful response
            this.showNotification('success', createResponse.data.message || 'Table reservation with menu successful!');
            this.errors = {}; // Clear errors on successful request
            this.resetForm(); // Reset the form
            console.log('Reservation ID:', createResponse.data.reservation_id);
        }
    } catch (error) {
        console.error("Error reserving table with menu", error);
        if (error.response) {
            console.error('Server responded with:', error.response.data);
            this.errors = error.response.data.errors || {}; // Set errors from server response
            this.showNotification('error', error.response.data.message || "Reservation failed");
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
      if (!this.checkAuth()) return; // Check authentication before proceeding

      this.notification.show = false;
      try {
        const userId = sessionStorage.getItem("id");
        const cottageId = this.selectedCottage ? this.selectedCottage.cottage_id : null;

        console.log('User ID:', userId);
        console.log('Cottage ID:', cottageId);
        console.log('Selected Time:', this.selectedTime);
        console.log('Selected Timeout:', this.selectedTimeout);

        if (!userId || !cottageId || !this.selectedTime || !this.selectedTimeout) {
          throw new Error("Missing required parameters. Please ensure all fields are filled.");
        }

        const selectedTimeISO = new Date(this.selectedTime).toISOString();
        const selectedTimeoutISO = new Date(this.selectedTimeout).toISOString();

        const response = await axios.post("cottageBooking", {
          user_id: userId,
          selectedTime: selectedTimeISO,
          selectedTimeout: selectedTimeoutISO,
          cottage_id: cottageId,
        });

        if (response.status === 200) {
          this.notification = {
            show: true,
            type: 'success',
            message: response.data.message || 'Booking successful!'
          };
          this.selectedTime = "";
          this.selectedTimeout = "";
          setTimeout(() => this.notification.show = false, 2000);
        }
      } catch (error) {
        console.error("Error booking", error);
        this.notification = {
          show: true,
          type: 'error',
          message: error.response?.data?.message || "Booking failed"
        };
        setTimeout(() => this.notification.show = false, 2000);
      }
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
      this.fetchCottages();
      this.cottageModalVisible = true;
    },

    closeCottageModal() {
      this.cottageModalVisible = false;
    },

    showTableModal() {
      this.tableModalVisible = true;
      this.fetchTables();
    },

    closeTableModal() {
      this.tableModalVisible = false;
    },

    closeRoomModal() {
      this.roomModalVisible = false;
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

    bookSelectedRooms() {
      const selectedRoomIds = this.selectedRooms
        .filter(room => room.selected)
        .map(room => room.room_id);

      if (selectedRoomIds.length > 0) {
        selectedRoomIds.forEach(roomId => {
          this.$router.push({ name: "booking", params: { id: roomId } });
        });
      } else {
        alert("Please select at least one room to book.");
      }
    },

    showPackSelectionModal() {
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
