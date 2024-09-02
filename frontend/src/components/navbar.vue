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

      <!-- Logout Button -->
      <button @click="logout" class="btn btn-primary logout-logo-btn">
        <i class="fas fa-power-off logout-icon"></i> Logout
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
              <img class="card-img-top" :src="require(`@/assets/img/${room.image}`)" alt="Room Image">
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
          <div v-for="table in tables" :key="table.id" class="col-md-6 mb-4">
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
        <form>
          <section id="menu" class="menu section-bg">
            <div class="container" data-aos="fade-up">
              <div class="section-title">
                <h2>Menu</h2>
                <p>Check Our Tasty Menu</p>
              </div>
              <div class="row" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-12 d-flex justify-content-center">
                  <ul id="menu-filters" class="row list-unstyled">
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('*')" :class="{ 'filter-active': currentFilter === '*' }">All</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('breakfast')" :class="{ 'filter-active': currentFilter === 'breakfast' }">Breakfast</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('rice')" :class="{ 'filter-active': currentFilter === 'rice' }">Rice</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('noodles')" :class="{ 'filter-active': currentFilter === 'noodles' }">Noodles</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('asian curry')" :class="{ 'filter-active': currentFilter === 'asian curry' }">Asian Curry</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('soup')" :class="{ 'filter-active': currentFilter === 'soup' }">Soup</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('refreshments')" :class="{ 'filter-active': currentFilter === 'refreshments' }">Refreshments</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('sizzling')" :class="{ 'filter-active': currentFilter === 'sizzling' }">Sizzling</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('filipino style')" :class="{ 'filter-active': currentFilter === 'filipino style' }">Filipino Style</li>
                    <li class="col-md-4 col-lg-3 mb-2" @click="filterMenu('vegetables')" :class="{ 'filter-active': currentFilter === 'vegetables' }">Vegetables</li>
                  </ul>
                </div>
              </div>
              <div class="row menu-container" data-aos="fade-up" data-aos-delay="200">
                <div v-for="item in filteredMenuItems" :key="item.item_id" class="col-lg-6 menu-item filter-{{ item.item_category }}">
                  <div class="menu-content">
                    <a @click.prevent="selectMenuItem(item)">{{ item.item_name }}</a><span>${{ item.item_price }}</span>
                  </div>
                  <div class="menu-ingredients">
                    {{ item.item_description }}
                  </div>
                </div>
              </div>
            </div>
          </section>
          <div class="form-group">
            <label for="timeSelection">Time</label>
            <input type="time" id="timeSelection" class="form-control" v-model="selectedTime">
          </div>
          <button type="button" class="btn btn-primary" @click="bookMenuAndTime">Book</button>
        </form>
      </div>
    </div>
  </div>
</div>




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
      query: "",
      data: [],
      showData: false,
      showNoMatchMessage: false,
      notifications: [],
      showNotifications: false,
      packSelectionModalVisible: false,
      packs: 3,
      roomModalVisible: false,
      selectedRooms: [], // Change from selectedRoom to selectedRooms
      cottageModalVisible: false,
    tableModalVisible: false,
    tables: [], // Data array to store table data
    menus: [], // you might need to fetch this data from your backend
    menuModalVisible: false,
    currentTable: null,
    filteredMenuItems: [],
    currentFilter: '*',
    selectedMenuItem: null,
    menuItems: [], // Initialized as an empty array
    cottages: [],  
    cottageModalVisible: false, 
    timeSelectionModalVisible: false,
    selectedCottage: null,
    selectedTime: '',
    selectedTimeout: '',
    minDate: new Date().toISOString().slice(0, 16), 
    notification: {
      show: false,
      type: "", 
      message: "",
    },
    };
  },
  methods: {

    async reserveTable() {
    try {
      const userId = sessionStorage.getItem("id");
      const tableId = this.currentTable ? this.currentTable.table_id : null;
      const reservationTime = new Date(this.selectedTime).toISOString();

      if (!userId || !tableId || !this.selectedTime) {
        throw new Error("Missing required parameters. Please ensure all fields are filled.");
      }

      const response = await axios.post("/reserveTable", {
        user_id: userId,
        table_id: tableId,
        reservation_time: reservationTime,
      });

      if (response.status === 200) {
        this.notification = {
          show: true,
          type: 'success',
          message: response.data.message || 'Table reservation successful!'
        };
        this.selectedTime = ""; // Clear selected time
        this.closeMenuModal(); // Close the modal after successful reservation
        setTimeout(() => this.notification.show = false, 2000);
      }
    } catch (error) {
      console.error("Error reserving table", error);
      this.notification = {
        show: true,
        type: 'error',
        message: error.response?.data?.message || "Table reservation failed"
      };
      setTimeout(() => this.notification.show = false, 2000);
    }
  },
  selectMenuItem(item) {
    this.selectedMenuItem = item;
    console.log(`Selected Menu Item: ${item.item_name}`); // For debugging
  },

  bookMenuAndTime() {
    if (this.selectedMenuItem) {
      console.log(`Booking ${this.selectedMenuItem.item_name} for ${this.currentTable.table_name} at ${this.selectedTime}`);
      this.reserveTable(); // Call reserveTable instead
    } else {
      alert("Please select a menu item.");
    }
  },
    async bookCottage() {
  this.notification.show = false;
  try {
    // Retrieve the cottage ID and user ID from sessionStorage
    const userId = sessionStorage.getItem("id");
    const cottageId = this.selectedCottage ? this.selectedCottage.cottage_id : null;

    // Log values for debugging
    console.log('User ID:', userId);
    console.log('Cottage ID:', cottageId);
    console.log('Selected Time:', this.selectedTime);
    console.log('Selected Timeout:', this.selectedTimeout);

    // Ensure all required parameters are present
    if (!userId || !cottageId || !this.selectedTime || !this.selectedTimeout) {
      throw new Error("Missing required parameters. Please ensure all fields are filled.");
    }

    // Format time if needed (e.g., ISO string)
    const selectedTimeISO = new Date(this.selectedTime).toISOString();
    const selectedTimeoutISO = new Date(this.selectedTimeout).toISOString();

    // Make the POST request with the required parameters
    const response = await axios.post("cottageBooking", {
      user_id: userId,          // Corrected key
      selectedTime: selectedTimeISO,
      selectedTimeout: selectedTimeoutISO,
      cottage_id: cottageId,   // Corrected key
    });

    // Handle successful response
    if (response.status === 200) {
      this.notification = {
        show: true,
        type: 'success',
        message: response.data.message || 'Booking successful!'
      };
      // Reset selected time and timeout
      this.selectedTime = "";
      this.selectedTimeout = "";
      // Hide notification after 2 seconds
      setTimeout(() => this.notification.show = false, 2000);
    }
  } catch (error) {
    console.error("Error booking", error);
    if (error.response) {
      console.log("Detailed Error Response: ", error.response.data);
    } else {
      console.log("Error details: ", error.message);
    }
    // Display error notification
    this.notification = {
      show: true,
      type: 'error',
      message: error.response?.data?.message || "Booking failed" // Corrected path
    };
    // Hide notification after 2 seconds
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
        this.cottages = response.data;  // Assuming the response data is the array of cottages
      })
      .catch(error => {
        console.error('Error fetching cottages:', error);
        alert('Failed to load cottages. Please try again.'); // Inform the user in case of an error
      });
  },
    fetchMenuItems() {
  axios.get('/getItem')
    .then(response => {
      this.menuItems = response.data;
      this.filterMenu(this.currentFilter); // Ensure this only runs after data is fetched
    })
    .catch(error => {
      console.error('Failed to load menu items', error);
      this.menuItems = []; // Ensure menuItems is set to an empty array on error
    });
},
filterMenu(item_category) {
  this.currentFilter = item_category;
  if (this.menuItems && this.menuItems.length > 0) {
    this.filteredMenuItems = this.menuItems.filter(item => item_category === '*' || item.item_category === item_category);
  } else {
    // Possibly handle the empty or undefined case, e.g., by setting filteredMenuItems to an empty array
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
      this.fetchTables(); // Fetch tables when the modal is about to show

    },
    closeTableModal() {
      this.tableModalVisible = false;
    },
    // Method to close the room modal
    closeRoomModal() {
      this.roomModalVisible = false;
    },
    fetchTables() {
      axios.get('/getTable') // Using the Axios instance to make a GET request
        .then(response => {
          this.tables = response.data; // Assign response data to tables data property
        })
        .catch(error => {
          console.error('Error fetching tables:', error);
          alert('Failed to load tables. Please try again.'); // Inform the user in case of an error
        });
    },
    // Method to book the selected room
    bookSelectedRooms() {
    const selectedRoomIds = this.selectedRooms
      .filter(room => room.selected)
      .map(room => room.room_id);

    if (selectedRoomIds.length > 0) {
      // Redirect to booking page for each selected room
      selectedRoomIds.forEach(roomId => {
        this.$router.push({ name: "booking", params: { id: roomId } });
      });
    } else {
      // Show an alert or message indicating that no rooms are selected
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
        this.selectedRooms = roomDetails; // Change selectedRoom to selectedRooms
        this.roomModalVisible = true; 
      }
    })
    .catch((error) => {
      console.error("Error fetching rooms:", error);
    });
},

    async logout() {
      try {
        const response = await axios.post("/logout");

        if (response.status === 200) {
          console.log("Logout successful");

          sessionStorage.removeItem("token");

          this.$router.push("/");
        }
      } catch (error) {
        console.error("Error during logout", error);
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
        const response = await axios.get(`/getNotif/${id}`); // Replace with your actual endpoint
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
  this.fetchMenuItems();  // Initially fetch all menu items
  this.filterMenu('*');  // Apply initial filter
  this.fetchCottages();  // Fetch cottages when the component is created

},
  mounted() {
    this.fetchNotifications(); // Fetch notifications when the component is mounted
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
