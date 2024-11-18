<template>
    <Notification
    :show="notification.show"
    :type="notification.type"
    :message="notification.message"
  />
  
  <div class="main-content">
    <div class="sidebar" :class="{ 'collapsed': isSidebarCollapsed }">
  
  <img :src="require('../assets/images/logo1.png.png')" alt="Mono" class="logo">
  <router-link to="/admin2">
    <i class="fa-solid fa-chart-simple"></i><span>Business Dashboard</span>
  </router-link>
  <details >
    <summary class="inventory-summary">
      <i  style="padding: 10px; margin: 0" class="fa-solid fa-table"></i>
      <span style="padding: 0; margin: 0" class="dropdown-icon">▾</span> 
      <span style="padding: 0; margin: 0">Inventory Options</span>
    </summary>
    <div class="dropdown-menu">
      <router-link to="/analytics2">
        <i style="padding-right: 20px;" class="fa-solid fa-house"></i ><span>Room Inventory</span>
      </router-link>
      <router-link to="/shopinventory">
        <i  style="padding-right: 20px;" class="fa-solid fa-shop"></i><span>Shop Inventory</span>
      </router-link>
      <router-link to="/cottagebooking">
        <i  style="padding-right: 20px;" class="fa-solid fa-shop"></i><span>Cottage Inventory</span>
      </router-link>
      <router-link to="/reservations">
        <i  style="padding-right: 20px;" class=" fa-solid fa-utensils"></i><span>Restaurant Reservations</span>
      </router-link>
    
      <router-link to="/enrollment">
        <i  style="padding-right: 20px;" class="fa-solid fa-person-swimming"></i><span>Enrollment Inventory</span>
      </router-link>
    </div>
  </details>
  
  <router-link to="/teamadmin2">
    <i class="fa-solid fa-people-group"></i><span>Team</span>
  </router-link>
  <router-link to="/monitorusers2">
    <i class="fas fa-user"></i><span>Users</span>
  </router-link>
  <router-link to="/pos2">
    <i  class="fa-solid fa-cart-plus"></i><span>POS</span>
  </router-link>
  <router-link to="/history">
    <i  class="fa-solid fa-cart-plus"></i><span>History</span>
  </router-link>
  </div>
  
      <div class="header">
        <h1 class="h1-main">EDUARDO'S ADMIN</h1>
  
        <!-- Navbar Toggler -->
        <button
          class="navbar-toggler"
          type="button"
          @click="toggleSidebar"
        >
        <i class="fa-solid fa-bars" style="padding: 5px; margin:5px; width: 40px;"></i>
        </button>
  
        <!-- Logout Button -->
        <button @click="logout" class="btn btn-custom logout-logo-btn">
          <i class="fas fa-power-off logout-icon"></i>
          Logout
        </button>
      </div>
  
  
  
  
      <div class="card-main">
        <div class="content">
  
  
  
          <div class="row">
      <!-- Table Product -->
      <div class="col-12">
          <div class="card card-default">
              <div class="card-header">
                  <h2 class="fa-solid fa-shop"> Shop Inventory</h2><br>
                  <button type="button" class="btn btn-custom" @click="openAddModal">
                      Add Item
                  </button>
              </div>
              <div class="card-body">
                  <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                      <table id="productsTable" class="table table-product">
                          <thead>
                              <tr>
                                  <th>Product Image</th>
                                  <th>Product Name</th>
                                  <th>Quantity</th>
                                  <th>Description</th>
                                  <th>Price</th>
                                  <th>Total Price</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                  <tr v-for="(info, index) in infos" :key="info.id">
                    <td>
                      <img 
                        class="img-fluid product-image" 
                        :src="`https://eduardos-resort.online/backend/backend/public/uploads/${info.prod_img}`" 
                        alt="Product Image" 
                        style="max-width: 80px; height: auto; object-fit: cover;"
                      />
                    </td>
                    <td>{{ info.prod_name }}</td>
                    <td>{{ info.prod_quantity }}</td>
                    <td>{{ info.prod_desc }}</td>
                    <td>{{ info.prod_price }}</td>
                    <td>{{ info.prod_quantity * info.prod_price }}</td>
                    <td class="action-buttons">
                      <button class="btn btn-custom" @click="openQuantityModal(info)">Add Quantity</button>
                      <router-link 
                        class="btn btn-custom" 
                        :to="{ name: 'auditHistory', params: { shopId: info.shop_id }}" 
                      >
                        Audit History
                      </router-link>
                      <button class="btn btn-custom" @click="openEditModal(info)">Edit</button>
                    </td>
                  </tr>
                </tbody>
                      </table>
                  </div>
  
                  <!-- Quantity Modal -->
                  <div v-if="quantityModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title">Add Quantity</h5>
                                  <button type="button" class="close" @click="closeQuantityModal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                              <div class="modal-body">
                                  <div class="form-group">
                                      <label for="productName" class="label">Product Name:</label>
                                      <p id="productName">{{ selectedProduct.prod_name }}</p>
                                  </div>
                                  <div class="form-group">
                                      <label for="currentQuantity" class="label">Current Quantity:</label>
                                      <p id="currentQuantity">{{ selectedProduct.prod_quantity }}</p>
                                  </div>
                                  <div class="form-group">
                                      <label for="addQuantity" class="label">Add Quantity:</label>
                                      <input
                                          type="number"
                                          class="form-control"
                                          id="addQuantity"
                                          v-model="quantityToAdd"
                                      />
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-custom" @click="addQuantity">
                                      Add
                                  </button>
                                  <button type="button" class="btn btn-custom" style="background:#0F172B; border:none;" @click="closeQuantityModal">
                                      Close
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
  
                  <!-- Success Message -->
                  <div v-if="successMessage" class="alert alert-success" role="alert">
                      {{ successMessage }}
                  </div>
              </div>
          </div>
      </div>
  </div>
  
      <!-- Edit Modal -->
      <div
        v-if="editModalVisible"
        class="modal"
        tabindex="-1"
        role="dialog"
        style="display: block"
      >
  <!-- Edit Modal -->
  <div v-if="editModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Edit Product</h5>
                  <button type="button" class="close" @click="closeEditModal">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <!-- Edit form -->
                  <form @submit.prevent="saveEdit">
                      <div class="form-group">
                          <label for="edit_prod_name">Product Name</label>
                          <input
                              type="text"
                              class="form-control"
                              placeholder="Name"
                              v-model="editedInfo.prod_name"
                          />
                      </div>
                      <div class="form-group">
                          <label for="edit_prod_desc">Product Description</label>
                          <input
                              type="text"
                              class="form-control"
                              placeholder="Description"
                              v-model="editedInfo.prod_desc"
                          />
                      </div>
                      <div class="form-group">
                          <label for="edit_prod_price">Product Price</label>
                          <input
                              type="number"
                              class="form-control"
                              placeholder="Price"
                              v-model="editedInfo.prod_price"
                          />
                      </div>
                      <div class="form-group">
                          <label for="edit_prod_img">Change Product Image</label>
                          <input type="file" ref="editImageInput" @change="handleEditImageUpload" />
                      </div>
                      <div class="modal-footer">
                          <button
                              type="button"
                              class="btn btn-custom"
                              style="background:#0F172B; border:none;"
                              @click="closeEditModal"
                          >
                              Close
                          </button>
                          <button type="submit" class="btn btn-custom">
                              Save Changes
                          </button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <div v-if="successMessage" class="alert alert-success" role="alert">
          {{ successMessage }}
      </div>
  </div>
  
        <div v-if="successMessage" class="alert alert-success" role="alert">
          {{ successMessage }}
        </div>
      </div>
  
      <!-- Modal for Adding -->
    <div class="col-12">
      <div v-if="addModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
        <!-- Modal content for adding -->
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Product</h5>
              <button type="button" class="close" @click="closeAddModal">
                &times;
              </button>
            </div>
            <!-- Add form -->
            <form @submit.prevent="saveShop('add')" enctype="multipart/form-data">
              <div class="modal-body">
                <!-- Your form inputs -->
                <div class="form-group">
                  <label for="prod_image">Product Image</label>
                  <input
                    type="file"
                    ref="imageInput"
                    @change="handleImageUpload"
                    class="form-control"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="prod_name">Product Name</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Name"
                    v-model="prod_name"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="prod_quantity">Product Quantity</label>
                  <input
                    type="number"
                    class="form-control"
                    placeholder="Quantity"
                    v-model="prod_quantity"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="prod_desc">Product Description</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Description"
                    v-model="prod_desc"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="prod_price">Product Price</label>
                  <input
                    type="number"
                    class="form-control"
                    placeholder="Price"
                    v-model="prod_price"
                    required
                  />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom "  style="background:#0F172B; border:none; " @click="closeAddModal">
                  Close
                </button>
                <button type="submit" class="btn btn-custom">Add</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div v-if="successMessage" class="alert alert-success" role="alert">
        {{ successMessage }}
      </div>
    </div>
</div>
  <br>

  <div class="content">
  <div class="row">
    <!-- Table Product -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2  class="fa-solid fa-clipboard-list">  Orders Inventory</h2>
        </div>
        <div class="card-body">
          <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table id="ordersTable" class="table  table-product" >
              <thead>
                <tr>
                  <th>User</th>
                  <th>Total Price</th>
                  <th>Items</th>
                  <th>Status</th>
                  <th>Proof Of Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders" :key="order.order_id">
                  <td>{{ order.user_name }}</td>
                  <td>{{ order.total_price }}</td>
                  <td>{{ order.items }}</td>
                  <td>{{ order.order_status }}</td>
  
                  <!-- Proof of Payment Button -->
                  <td >
                   <button style="margin-top: 14px;"
                      class="btn btn-info"
                      @click="openProofOfPaymentModal(order.downpayment_proof)"
                      v-if="order.downpayment_proof"
                    >
                      View Proof
                    </button>
                    <span v-else>No Proof Available</span>
                  </td>
  
                  <!-- Action Buttons -->
                  <td class="action-buttons">
                    <button class="btn btn-primary" @click="confirmOrder(order.order_id)">
                      Confirm
                    </button>
                    <button
                      @click="declineOrder(order.order_id)"
                      class="btn btn-secondary"
                      :disabled="order.order_status === 'confirmed'"
                    >
                      Decline
                    </button>
                    <button
                      @click="markOrderPaid(order.order_id)"
                      class="btn btn-tertiary"
                    >
                      Mark as Paid
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
  
            <!-- Loading Indicator -->
            <div v-if="loading" class="text-center mt-3">
              <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
  
            <!-- Success Message -->
            <div v-if="successMessage" class="alert alert-success mt-3" role="alert">
              {{ successMessage }}
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Unique Proof of Payment Modal -->
    <div v-if="isProofOfPaymentModalOpen" class="modal fade show" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Order Proof of Payment</h5>
            <button type="button" class="close" @click="closeProofOfPaymentModal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <img
              :src="selectedProofImage"
              class="img-fluid"
              alt="Proof of Payment"
            />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeProofOfPaymentModal">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  

  <Notification
    :show="notification.show"
    :type="notification.type"
    :message="notification.message"
  />

    </div>
  </div>
  
  
  </template>
  
  
  <script>
  import axios from "axios";
  import flatpickr from "flatpickr";
  import "flatpickr/dist/flatpickr.min.css";
  import "bootstrap";
  import Notification from "@/components/Notification.vue";
  
  export default {
  name: 'shopinventory',
  components: {
    Notification,
  },
  data() {
    return {
      selectedProofImage: "", // URL for the selected proof image
        isProofOfPaymentModalOpen: false, // State for the proof of payment modal
      selectedProofImage: "", // URL for the selected proof of payment image
      isReservationProofModalOpen: false, // Tracks if the proof modal is open
      selectedProofImage: "", // Holds the selected proof image URL
      isProofModalOpen: false, // Tracks the modal's open state
      selectedProofImage: "", // Stores the selected proof image URL
        isModalOpen: false, // Tracks if the modal is open
      cottages: [],
        cottage_name: '',
        cottage_description: '',
        cottage_price: '',
        cottage_img: null,
        successMessage: '',
        addCottageModalVisible: false,
        editCottageModalVisible: false,
        editedCottage: null,
      tables: [],              // Holds table inventory data
        table_name: '',          // New table name
        table_description: '',   // New table description
        editedTable: null,       // Stores table data for editing
        addTableModalVisible: false,  // Controls visibility of Add Table modal
        editTableModalVisible: false, // Controls visibility of Edit Table modal
        successMessage: '',       // Success message state
      reservations: [],
        statusModalVisible: false,
        detailsModalVisible: false,
        selectedReservation: {},
        statusToUpdate: '',
        successMessage: '',
        errorMessage: '',
      menuItems: [],
      selectedImage: null,
  
      currentItem: {
          menu_id: null,
          item_name: '',
          item_category: '',
          item_price: ''
        },
        addModalItemVisible: false,
        
        editMenuItemModalVisible: false,
    editedMenuItem: {
      item_name: '',
      item_category: '',
      item_price: '',
      item_image: null,  // Holds the uploaded file
    },      item_name: '',
      item_category: '',
      item_price: '',
        successMessage: '',
        errorMessage: '',
        currentItem: {},
      addDateModalVisible: false,
      successMessageDate: null,
      successMessage: null,
      flatpickrInstance: null,
      selectedDate: null,
      dates: [], // date of swimming
      orders: [],
      loading: false,
      editRoomModalVisible: false,
      editedRoom: null,
      editModalVisible: false,
      editedInfo: null,
      room: [],
      book: [],
      enroll: [],
      reorderLevel: 10,
      notifications: [], // Change this from single notification to an array
      infos: [],
      shop: [],
      lowStockItems: [], 
      date: [],
      swimming_date: "",
      successMessage2: "",
      addModalVisible: false,
      prod_img: "",
      prod_name: "",
      prod_quantity: "",
      prod_desc: "",
      prod_price: "",
      addingItemId: null,
      quantityModalVisible: false,
      selectedProduct: null,
      quantityToAdd: 0,
      successMessage: "",
      notification: {
        show: false,
        type: "",
        message: "",
      },
      addRoomModalVisible: false,
      room_name: "",
      price: "",
  
      packs: "3", // Default to 3 packs
      description: "",
      downpayment: "",
      room_img: "",
      ConfirmationVisible: false,
      openDropdown: null, 
      successMessage: '', 
      bookings: [],
        successMessage: '',
        errorMessage: '',
        notification: {
        show: false,
        type: 'success',  // Default notification type (e.g., success, error, etc.)
        message: '',
      },
    };
  },
  mounted() {
    this.getCottages();
    this.fetchTables();
    this.fetchCottageBookings(); // Fetch bookings when component is mounted
    this.fetchReservations();
    this.fetchMenuItems();
    this.getBook(); // This will work if getBook() is defined in methods
    this.initializeDropdowns();
    },
    beforeDestroy() {
      this.$nextTick(() => {
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
          const select = dropdown.querySelector('.select');
          const options = dropdown.querySelectorAll('.menu li');
          select.removeEventListener('click', this.handleDropdownClick);
          options.forEach(option => {
            option.removeEventListener('click', this.handleOptionClick);
          });
        });
      });
    
    this.getDate();
  },
  
  created() {
    this.getInfo();
    this.getRoom();
    this.getBook();
    this.getenroll();
    this.getDate();
    this.fetchOrdersForAllUsers();
  },
  
  methods: {
    openProofOfPaymentModal(proofImage) {
        // Set the selected proof image and open the modal
        this.selectedProofImage = `https://eduardos-resort.online/backend/backend/public/uploads/${proofImage}`;
        this.isProofOfPaymentModalOpen = true;
      },
      closeProofOfPaymentModal() {
        // Close the proof of payment modal
        this.isProofOfPaymentModalOpen = false;
      },
    openProofModalReservation(proof_of_payment) {
        // Set the selected proof image and open the modal
        this.selectedProofImage = `https://eduardos-resort.online/backend/backend/public/uploads/${proof_of_payment}`;
        this.isReservationProofModalOpen = true;
      },
      closeReservationProofModal() {
        // Close the proof of payment modal
        this.isReservationProofModalOpen = false;
      },
    openProofModal(downpaymentProof) {
        // Set the selected image URL and open the modal
        this.selectedProofImage = `https://eduardos-resort.online/backend/backend/public/uploads/${downpaymentProof}`;
        this.isProofModalOpen = true;
      },
      closeProofModal() {
        // Close the modal
        this.isProofModalOpen = false;
      },
    openModal(proofImage) {
        // Set the selected image and open the modal
        this.selectedProofImage = `https://eduardos-resort.online/backend/backend/public/uploads/${proofImage}`;
        this.isModalOpen = true;},
        closeModal() {
        // Close the modal
        this.isModalOpen = false;
      },
  // Handle Image Upload for Add Cottage
  handleCottageImageUpload() {
      const fileInput = this.$refs.cottageImageInput;
      const file = fileInput.files[0];
  
      const formData = new FormData();
      formData.append("cottage_image", file);
  
      this.cottage_img = formData;
    },
  
    // Save a new cottage
    async saveCottage(mode) {
      try {
        let data = {};
  
        if (mode === "add") {
          if (this.cottage_img instanceof FormData) {
            data = this.cottage_img;
            data.append("cottage_name", this.cottage_name);
            data.append("cottage_description", this.cottage_description);
            data.append("cottage_price", this.cottage_price);
          } else {
            data = {
              cottage_name: this.cottage_name,
              cottage_description: this.cottage_description,
              cottage_price: this.cottage_price,
            };
          }
        }
  
        const response = await axios.post("/cottages", data);
  
        if (mode === "add") {
          this.closeCottageAddModal();
          this.cottage_name = "";
          this.cottage_description = "";
          this.cottage_price = "";
          this.getCottages();
          this.showNotification("Cottage Added Successfully", "success");
        }
      } catch (error) {
        console.error(error);
        this.showNotification("Error adding cottage", "error");
      }
    },
  
    // Open Edit Cottage Modal
    openCottageEditModal(cottage) {
      this.editedCottage = { ...cottage };
      this.editCottageModalVisible = true;
    },
  
    // Close Edit Cottage Modal
    closeCottageEditModal() {
      this.editedCottage = null;
      this.editCottageModalVisible = false;
      this.showNotification("Cottage Updated Successfully", "success");
    },
  
    // Save the edited cottage details
    async saveCottageEdit() {
    try {
      const formData = new FormData();
  
      // Append cottage details
      formData.append("cottage_name", this.editedCottage.cottage_name);
      formData.append("cottage_description", this.editedCottage.cottage_description);
      formData.append("cottage_price", this.editedCottage.cottage_price);
  
      // Append image file if a new image was selected
      if (this.editedCottage.newImage) {
        formData.append("cottage_image", this.editedCottage.newImage);
      }
  
      const apiUrl = `/cottages/update/${this.editedCottage.cottage_id}`;
      
      // Send the request with form data
      const response = await axios.post(apiUrl, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
  
      this.closeCottageEditModal();
      this.getCottages();
      this.showNotification("Cottage Updated Successfully", "success");
    } catch (error) {
      console.error("Error updating cottage:", error);
      this.showNotification("Error updating cottage", "error");
    }
  },
  
  handleEditCottageImageUpload() {
    const fileInput = this.$refs.editCottageImageInput;
    const file = fileInput.files[0];
  
    // Assign the new image to the editedCottage object
    this.editedCottage.newImage = file;
  },
  
    // Show success notification
    // showCottageSuccessNotification(message) {
    //   this.notification.message = message;
    //   this.notification.type = 'success';
    //   this.notification.show = true;
  
    //   setTimeout(() => {
    //     this.notification.show = false;
    //   }, 3000);  // Hide after 3 seconds
    // },
  
    // Show error notification
    // showCottageErrorNotification(message) {
    //   this.notification.message = message;
    //   this.notification.type = 'error';
    //   this.notification.show = true;
  
    //   setTimeout(() => {
    //     this.notification.show = false;
    //   }, 3000);  // Hide after 3 seconds
    // },  
  
      // Fetch all cottages
      async getCottages() {
        try {
          const response = await axios.get("/cottages");
          this.cottages = response.data;
        } catch (error) {
          console.error("Error fetching cottages:", error);
        }
      },
  
      // Show success notifications for cottage operations
      showCottageSuccessNotification(message) {
        this.successMessage = message;
        setTimeout(() => {
          this.successMessage = '';
        }, 3000);
      },
  
      // Open Add Cottage Modal
      openCottageAddModal() {
        this.addCottageModalVisible = true;
      },
  
      // Close Add Cottage Modal
      closeCottageAddModal() {
        this.addCottageModalVisible = false;
      },
  
  
  
  async fetchTables() {
        try {
          const response = await axios.get('/getTables');
          this.tables = response.data;
        } catch (error) {
          console.error('Error fetching tables:', error);
        }
      },
  
      async saveTable() {
        try {
          const data = {
            table_name: this.table_name,
            table_description: this.table_description,
          };
  
          await axios.post('/addTable', data);
          this.closeAddTableModal();
          this.fetchTables();
  
          this.showNotification("Table added successfully!", "success");
        } catch (error) {
          console.error('Error saving table:', error);
        }
      },
  
      async updateTable() {
        try {
          const data = {
            table_name: this.editedTable.table_name,
            table_description: this.editedTable.table_description,
          };
  
          await axios.post(`/updateTable/${this.editedTable.table_id}`, data);
          this.closeEditTableModal();
          this.fetchTables();
  
          this.showNotification("Table updated successfully!", "success");
        } catch (error) {
          console.error('Error updating table:', error);
        }
      },
  
     
  
      openAddTableModal() {
        this.addTableModalVisible = true;
      },
  
      closeAddTableModal() {
        this.addTableModalVisible = false;
        this.clearTableForm();
      },
  
      openEditTableModal(table) {
        this.editedTable = { ...table };  
        this.editTableModalVisible = true;
      },
  
      closeEditTableModal() {
        this.editTableModalVisible = false;
      },
  
      clearTableForm() {
        this.table_name = '';
        this.table_description = '';
      },
    async declineCottageBooking(cottagebooking_id) {
    try {
      const response = await axios.post(`api/cottage-bookings/decline/${cottagebooking_id}`);
  
      if (response.status === 200) {
        this.showSuccessNotification(`Cottage booking ${cottagebooking_id} has been declined.`);
        this.fetchCottageBookings(); // Refresh the booking list
      } else {
        console.error("Failed to decline cottage booking:", response.data.message);
        this.showErrorNotification(`Failed to decline booking: ${response.data.message}`);
      }
    } catch (error) {
      if (error.response && error.response.status === 400) {
        this.showErrorNotification("Cannot decline a confirmed booking.");
      } else {
        console.error("Error declining cottage booking:", error);
        this.showErrorNotification("An error occurred while declining the cottage booking.");
      }
    }
  },
  
  
  async confirmCottageBooking(cottagebooking_id) {
    try {
      const response = await axios.post(`api/cottage-bookings/confirm/${cottagebooking_id}`);
  
      if (response.status === 200) {
        this.showSuccessNotification(`Cottage booking ${cottagebooking_id} has been confirmed.`);
        this.fetchCottageBookings(); // Refresh the booking list
      } else {
        console.error("Failed to confirm cottage booking:", response.data.message);
        this.showErrorNotification(`Failed to confirm booking: ${response.data.message}`);
      }
    } catch (error) {
      console.error("Error confirming cottage booking:", error);
      this.showErrorNotification("An error occurred while confirming the cottage booking.");
    }
  },
  
  async markCottageBookingAsPaid(cottagebooking_id) {
    try {
      const response = await axios.post(`api/cottage-bookings/mark-paid/${cottagebooking_id}`);
  
      if (response.status === 200) {
        this.showSuccessNotification(`Cottage booking ${cottagebooking_id} marked as paid.`);
        this.fetchCottageBookings(); // Refresh the booking list
      } else {
        console.error("Failed to mark cottage booking as paid:", response.data.message);
        this.showErrorNotification(`Failed to mark booking as paid: ${response.data.message}`);
      }
    } catch (error) {
      console.error("Error marking cottage booking as paid:", error);
      this.showErrorNotification("An error occurred while marking the cottage booking as paid.");
    }
  },
  
  async fetchCottageBookings() {
        try {
          const [bookingsResponse, usersResponse] = await Promise.all([
            axios.get('api/cottage-bookings'), // Booking data
            axios.get('/getData'), // User data
          ]);
  
          const bookings = bookingsResponse.data;
          const users = usersResponse.data;
  
          // Map user names into bookings
          this.bookings = bookings.map((booking) => {
            const user = users.find((u) => u.id === booking.user_id);
            return {
              ...booking,
              name: user ? user.name : 'Unknown User', // Fallback if user not found
            };
          });
        } catch (error) {
          console.error('Error fetching data:', error);
          this.errorMessage = 'Failed to load cottage bookings.';
        }
      },
    async fetchReservations() {
        try {
          const response = await axios.get('/admin/reservations'); // Update with your API endpoint
          this.reservations = response.data;
        } catch (error) {
          console.error('Error fetching reservations:', error);
          this.errorMessage = 'Failed to load reservation data.';
        }
      },
      openStatusModal(reservation) {
        this.selectedReservation = reservation;
        this.statusToUpdate = reservation.status;
        this.statusModalVisible = true;
      },
      closeStatusModal() {
        this.statusModalVisible = false;
      },
      async declineReservation(reservation_id) {
    try {
      const response = await axios.post(`reservation/decline/${reservation_id}`);
  
      if (response.status === 200) {
        this.showSuccessNotification(`Reservation ${reservation_id} has been declined.`);
        this.fetchReservations(); // Refresh the reservation list
      } else {
        console.error("Failed to decline reservation:", response.data.message);
        this.showErrorNotification(`Failed to decline reservation: ${response.data.message}`);
      }
    } catch (error) {
      console.error("Error declining reservation:", error);
      this.showErrorNotification("An error occurred while declining the reservation.");
    }
  },
  
  async acceptReservation(reservation_id) {
    try {
      const response = await axios.post(`reservation/confirm/${reservation_id}`);
  
      if (response.status === 200) {
        this.showSuccessNotification(`Reservation ${reservation_id} has been accepted.`);
        this.fetchReservations(); // Refresh the reservation list
      } else {
        console.error("Failed to accept reservation:", response.data.message);
        this.showErrorNotification(`Failed to accept reservation: ${response.data.message}`);
      }
    } catch (error) {
      console.error("Error accepting reservation:", error);
      this.showErrorNotification("An error occurred while accepting the reservation.");
    }
  },
  
  
  async markReservationAsPaid(reservation_id) {
    try {
      const response = await axios.post(`reservation/paid/${reservation_id}`);
  
      if (response.status === 200) {
        this.showSuccessNotification(`Reservation ${reservation_id} Marked as Paid`);
        this.fetchReservations(); // Refresh the reservation list
      } else {
        console.error("Failed to mark reservation as paid:", response.data.message);
      }
    } catch (error) {
      console.error("Error marking reservation as paid:", error);
    }
  },
  
  
    showSuccessNotification(message) {
      // Implementation for showing success notifications
      console.log(`Success: ${message}`);
    },
      viewDetails(reservation) {
        this.selectedReservation = reservation;
        this.detailsModalVisible = true;
      },
      closeDetailsModal() {
        this.detailsModalVisible = false;
      },
    async fetchMenuItems() {
        try {
          const response = await axios.get('/api/menu');
          this.menuItems = response.data;
        } catch (error) {
          console.error(error);
        }
      },
      openAddItemModal() {
        this.addModalItemVisible = true;
      },
      closeAddItemModal() {
        this.addModalItemVisible = false;
      },
      onFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.item_image = file; // Store the selected file
      }
    },
      async addMenuItem() {
      const formData = new FormData();
      formData.append('item_name', this.item_name);
      formData.append('item_category', this.item_category);
      formData.append('item_price', this.item_price);
  
      if (this.item_image) {
          formData.append('item_image', this.item_image);
      }
  
      try {
          const response = await axios.post("https://eduardos-resort.online/backend/api/menu", formData, {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
          });
  
          if (response.data.status === "success") {
              this.showSuccessNotification(response.data.message);
              this.clearForm();
              this.closeAddItemModal();
              // Optionally fetch the updated menu list
              this.fetchMenuItems();
          } else {
              console.error("Error from server:", response.data.message, response.data.errors);
              this.showErrorNotification(response.data.message);
          }
      } catch (error) {
          console.error("An error occurred:", error);
          this.handleError(error);
      }
  },
  
  clearForm() {
      this.item_name = '';
      this.item_category = '';
      this.item_price = '';
      this.item_image = null; // Clear the image file
    },
  
      showSuccessNotification(message) {
        this.successMessage = message;
        setTimeout(() => {
          this.successMessage = '';
        }, 3000); // Clear the message after 3 seconds
      },
      showErrorNotification(message) {
        this.errorMessage = message;
        setTimeout(() => {
          this.errorMessage = '';
        }, 5000); // Clear the message after 5 seconds
      },
      openEditItemModal(item) {
    // Check if the item has a valid menu ID
    if (!item.menu_id) {
      console.error('Item ID is missing.');
      this.showErrorNotification('Item ID is missing.');
      return;
    }
    
    // Initialize the editedMenuItem with the selected item's data
    this.editedMenuItem = {
      menu_id: item.menu_id, // Assuming menu_id is needed for the update
      item_name: item.item_name,
      item_category: item.item_category,
      item_price: item.item_price,
      item_image: null, // Reset the item_image for potential upload
    };
  
    console.log('Selected item for editing:', this.editedMenuItem); // Check the contents of editedMenuItem
  
    // Open the modal for editing
    this.editMenuItemModalVisible = true;
  },
  
  closeMenuItemEditModal() {
    this.editMenuItemModalVisible = false;
    this.editedMenuItem = { // Reset editedMenuItem for future edits
      item_name: '',
      item_category: '',
      item_price: '',
      item_image: null,
    };
  },
  
  handleMenuItemImageUpload() {
      const fileInput = this.$refs.editMenuItemImageInput; // Reference to the input file element
      const file = fileInput.files[0]; // Get the first file selected
  
      // Check if a file was selected
      if (file) {
          this.editedMenuItem.item_image = file; // Store the file directly for upload
          console.log("Selected image file:", file); // Debug: Check if the file is captured correctly
      }
  },
  
  async saveMenuItemEdit() {
      try {
          const formData = new FormData();
          formData.append("item_name", this.editedMenuItem.item_name);
          formData.append("item_category", this.editedMenuItem.item_category);
          formData.append("item_price", this.editedMenuItem.item_price);
          
          // Append the image file if it exists
          if (this.editedMenuItem.item_image) {
              formData.append("item_image", this.editedMenuItem.item_image);
          }
  
          // Make the PUT request to update the menu item
          const response = await axios.post(`https://eduardos-resort.online/backend/api/menu/${this.editedMenuItem.menu_id}`, formData, {
              headers: {
                  'Content-Type': 'multipart/form-data', // Set the content type for FormData
              }
          });
  
          console.log("Item updated successfully:", response.data);
          this.showSuccessNotification(response.data.message || 'Item updated successfully!');
          this.closeMenuItemEditModal();
          this.fetchMenuItems(); // Refresh the list of menu items
      } catch (error) {
          console.error("Error updating item:", error);
          this.showErrorNotification('Failed to update item.');
      }
  },
  
  
  
      showSuccessNotification(message) {
        this.successMessage = message;
        setTimeout(() => this.successMessage = '', 3000);
      },
      showErrorNotification(message) {
        this.errorMessage = message;
        setTimeout(() => this.errorMessage = '', 5000);
      },
      clearForm() {
        this.currentItem = {
          menu_id: null,
          item_name: '',
          item_category: '',
          item_price: ''
        };
      },
      async deleteItem(itemId) {
        try {
          await axios.delete(`/api/menu/${itemId}`);
          this.successMessage = 'Item deleted successfully!';
          this.fetchMenuItems();
        } catch (error) {
          console.error(error);
        }
      },
    isActive(route) {
        return this.$route.path === route;
      },
    openAddDateModal() {
      this.addDateModalVisible = true;
    },
  
    closeAddDateModal() {
  
      this.addDateModalVisible = false;
    },
    openDatePicker() {
      this.flatpickrInstance.open();
    },
    async logout() {
        try {
          const response = await axios.post("/logout");
  
          if (response.status === 200) {
            console.log("Logout successful");
  
            sessionStorage.removeItem("token");
            sessionStorage.removeItem("id");
  
            localStorage.clear();
  
            this.userInfo = {}; 
            this.user = []; 
            this.notifications = []; 
            this.eventName = ""; 
            this.eventTheme = "";
            this.eventDate = "";
            this.manifest = ""; 
            this.hasBooking = false; 
            this.feed = []; 
            document.cookie.split(";").forEach((cookie) => {
              document.cookie = cookie
                .replace(/^ +/, "")
                .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
            });
  
            this.notification = {
              show: true,
              type: 'success',
              message: 'You have been logged out successfully.'
            };
  
            setTimeout(() => {
              this.$router.push('/'); 
            }, 1000); 
            
          } else {
            throw new Error("Logout failed");
          }
        } catch (error) {
          console.error("Error during logout", error);
  
          this.notification = {
            show: true,
            type: 'error',
            message: 'Logout failed. Please try again.'
          };
  
          setTimeout(() => {
            this.notification.show = false;
          }, 1000); 
        }
      },
  
    async getBook() {
      try {
        const response = await axios.get('/getbook');
        this.book = response.data;
      } catch (error) {
        console.error('Error fetching books:', error);
      }
    },
    async markAsPaidByGroup(group_id) {
      try {
          const response = await axios.post(`/api/mark-as-paid-group/${group_id}`);
          if (response.status === 200) {
              this.showSuccessNotification(`All bookings in group ${group_id} marked as Paid`);
              this.getBook(); // Refresh the booking list
          } else {
              console.error("Failed to mark bookings as paid:", response.data.message);
          }
      } catch (error) {
          console.error("Error marking bookings as paid:", error);
      }
  },
  
  async acceptBookingByGroup(group_id) {
      try {
          const response = await axios.post(`/api/accept-booking-group/${group_id}`);
          if (response.status === 200) {
              this.showSuccessNotification(`All bookings in group ${group_id} accepted`);
              this.getBook(); // Refresh the booking list
          } else {
              console.error("Failed to accept bookings:", response.data.message);
          }
      } catch (error) {
          console.error("Error accepting bookings:", error);
      }
  },
  
  async declineBookingByGroup(group_id) {
      try {
          const response = await axios.post(`/api/decline-booking-group/${group_id}`);
          if (response.status === 200) {
              this.showSuccessNotification(`All bookings in group ${group_id} declined`);
              this.getBook(); // Refresh the booking list
          } else {
              console.error("Failed to decline bookings:", response.data.message);
          }
      } catch (error) {
          console.error("Error declining bookings:", error);
      }
  },
  
  toggleDropdown(group_id) {
      this.openDropdown = this.openDropdown === group_id ? null : group_id; // Toggles the dropdown
  },
    async declineenrolling(enroll_id) {
      try {
        const response = await axios.post(`/api/decline-enroll/${enroll_id}`);
  
        if (response.status === 200) {
          this.showSuccessNotification(`Enrolling ${enroll_id} Declined`);
          this.getenroll();
  
          this.$emit("data-saved");
        } else {
          console.error("Failed to decline enroll:", response.data.message);
        }
      } catch (error) {
        console.error("Error declining enroll:", error);
      }
    },
  
    fetchOrdersForAllUsers() {
      this.loading = true;
      axios
        .get(`/api/orders`)
        .then((response) => {
          console.log(response.data); // Add this line to log the response
          this.orders = response.data.orders;
          return this.orders.filter((order) => order.order_status !== "paid");
        })
        .catch((error) => {
          console.error("Error fetching orders:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
  
    markOrderPaid(orderId) {
      axios.post(`/api/mark-order-paid/${orderId}`)
        .then((response) => {
          console.log('Mark order paid response:', response.data);
          this.fetchOrdersForAllUsers();
        })
        .catch((error) => {
          console.error('Error marking order as paid:', error.response ? error.response.data : error);
        });
    },
  
    confirmOrder(orderId) {
      axios.post(`/api/confirm-order/${orderId}`)
        .then((response) => {
          console.log('Confirm order response:', response.data);
          this.fetchOrdersForAllUsers();
        })
        .catch((error) => {
          console.error('Error confirming order:', error.response ? error.response.data : error);
        });
    },
  
    declineOrder(orderId) {
      axios.post(`/api/decline-order/${orderId}`)
        .then((response) => {
          console.log('Decline order response:', response.data);
          this.fetchOrdersForAllUsers();
        })
        .catch((error) => {
          console.error('Error declining order:', error.response ? error.response.data : error);
        });
    },
  
    openRoomEditModal(room) {
      this.editedRoom = { ...room };
      this.editRoomModalVisible = true;
    },
  
    closeRoomEditModal() {
      this.editedRoom = null;
      this.editRoomModalVisible = false;
    },
  
    async saveRoomEdit() {
      try {
        const data = {
          room_name: this.editedRoom.room_name,
          price: this.editedRoom.price,
          downpayment: this.editedRoom.downpayment,
          description: this.editedRoom.description,
          room_status: this.editedRoom.room_status, // Add room_status to the data object
        };
  
        const apiUrl = `/updateRoom/${this.editedRoom.room_id}`;
        const response = await axios.put(apiUrl, data);
  
        console.log("Room updated successfully:", response.data);
        this.closeRoomEditModal();
        this.showSuccessNotification("Room Updated Successfully");
        this.getRoom();
      } catch (error) {
        console.error("Error updating room:", error);
        this.showErrorNotification("Failed to update room");
      }
    },
  
    openAddRoomModal() {
      this.addRoomModalVisible = true;
    },
    closeAddRoomModal() {
      this.addRoomModalVisible = false;
    },
    handleRoomImageUpload() {
      const fileInput = this.$refs.roomImageInput;
      const file = fileInput.files[0];
  
      const formData = new FormData();
      formData.append("image", file);
  
      this.room_img = formData;
    },
    async saveRoom() {
      try {
        // Use FormData for room image uploads
        const data = this.room_img;
        data.append("room_name", this.room_name);
        data.append("price", this.price);
        data.append("downpayment", this.downpayment);
        data.append("packs", this.packs);
        data.append("description", this.description);
        this.showSuccessNotification("Room Added Successfully");
  
        const ins = await axios.post("saveRoom", data);
  
        this.closeAddRoomModal();
        this.room_img = "";
        this.room_name = "";
        this.price = "";
        this.downpayment = "";
        this.packs = "";
        this.description = "";
  
        this.getRoom();
        this.getbook();
      } catch (error) {
        console.error(error);
      }
    },
  
    async markthisPaid(enroll_id) {
      try {
        const response = await axios.post(`/mark-this-paid/${enroll_id}`);
  
        if (response.status === 200) {
          this.showSuccessNotification(
            `enrollment ${enroll_id} Marked this Paid`
          );
  
          this.$emit("data-saved");
        } else {
          console.error("Failed to mark this paid:", response.data.message);
        }
      } catch (error) {
        console.error("Error marking this paid:", error);
      }
    },
    initializeDropdowns() {
        this.$nextTick(() => {
          const dropdowns = document.querySelectorAll('.dropdown');
          
          dropdowns.forEach(dropdown => {
            const select = dropdown.querySelector('.select');
            const caret = dropdown.querySelector('.caret');
            const menu = dropdown.querySelector('.menu');
            const options = dropdown.querySelectorAll('.menu li');
            const selected = dropdown.querySelector('.selected');
      
            select.addEventListener('click', () => {
              select.classList.toggle('select-clicked');
              caret.classList.toggle('caret-rotate');
              menu.classList.toggle('menu-open');
            });
      
            options.forEach(option => {
              option.addEventListener('click', () => {
                selected.innerText = option.innerText;
                select.classList.remove('select-clicked');
                caret.classList.remove('caret-rotate');
                menu.classList.remove('menu-open');
                options.forEach(opt => {
                  opt.classList.remove('active');
                });
                option.classList.add('active');
              });
            });
          });
        });
      },
   toggleDropdown(enroll_id) {
        this.openDropdown = this.openDropdown === enroll_id ? null : enroll_id;
      },
      toggleDropdown(order_id) {
        this.openDropdown = this.openDropdown === order_id ? null : order_id;
      },
      toggleDropdown(book_id) {
        this.openDropdown = this.openDropdown === book_id ? null : book_id;
      },
      handleDropdownAction(action, enroll_id) {
     
        if (action === 'Paid') {
          this.markthisPaid(enroll_id);
        } else if (action === 'Accept') {
          this.acceptenrolling(enroll_id);
        } else if (action === 'Decline') {
          this.declineenrolling(enroll_id);
        }
        this.openDropdown = null; 
      },
      handleDropdownAction(action, order_id) {
     
     if (action === 'markOrderPaid') {
       this.markOrderPaid(order_id);
     } else if (action === 'confirmOrder') {
       this.confirmOrder(order_id);
     } else if (action === 'declineOrder') {
       this.declineOrder(order_id);
     }
     this.openDropdown = null; 
   },
   handleDropdownAction(action, group_id) {
      if (action === 'markAsPaid') {
          this.markAsPaidByGroup(group_id); // Call the new group method
      } else if (action === 'acceptBooking') {
          this.acceptBookingByGroup(group_id); // Call the new group method
      } else if (action === 'declineBooking') {
          this.declineBookingByGroup(group_id); // Call the new group method
      }
      this.openDropdown = null; 
  },
  
  
      showSuccessNotification(message) {
        this.successMessage = message;
        setTimeout(() => {
          this.successMessage = '';
        }, 3000);
      },
  
    async acceptenrolling(enroll_id) {
      try {
        const response = await axios.post(`/accept-enrolling/${enroll_id}`);
  
        if (response.status === 200) {
          const updatedEnrollingId = response.data.enroll_id;
          this.showSuccessNotification(
            `Enrolling ${updatedEnrollingId} Accepted`
          );
          this.getenroll();
          this.$emit("data-saved");
        } else {
          console.error("Failed to accept enrolling:", response.data.message);
        }
      } catch (error) {
        console.error("Error accepting enrolling:", error);
      }
    },
  
    async getRoom() {
      const r = await axios.get("/getRoom");
      this.room = r.data;
      this.getBook();
    },
    async getenroll() {
      const b = await axios.get("/getenroll");
      this.enroll = b.data;
    },
    async saveDate() {
      try {
          const response = await axios.post("https://eduardos-resort.online/backend/saveDate", {
              swimming_date: this.swimming_date,
          });
          this.successMessageDate = response.data.message;
          this.dates.push(this.swimming_date); // Assuming you want to add it to the dates list
          this.closeAddDateModal(); // Close the modal
          this.showNotification("Date Added Successfully", "success");
  
      } catch (error) {
          console.error("Error saving date:", error);
          // Check if error response exists
          const errorMessage = error.response ? error.response.data.message : "Unknown error occurred.";
          this.successMessageDate = "Failed to save date: " + errorMessage;
      }
  },
  
  
  
  
      async getDate() {
        try {
          const response = await axios.get("/getDate");
          if (response && response.status === 200) {
            if (response.data && Array.isArray(response.data)) {
              this.dates = response.data;
              this.initFlatpickr();
            } else {
              console.error("Error fetching dates: Response data is empty or not an array");
            }
          } else {
            console.error("Error fetching dates: Invalid response status");
          }
        } catch (error) {
          console.error("Error fetching dates:", error);
        }
      },
      initFlatpickr() {
        this.flatpickrInstance = flatpickr(this.$refs.datepicker, {
          dateFormat: 'Y-m-d',
          enable: this.dates,
          onClose: selectedDates => {
            this.selectedDate = selectedDates[0];
          },
        });
      },
    
  
    navigateToAuditHistory(info) {
      const shopId = info.shop_id;
      this.$router.push({ name: "auditHistory", params: { shopId } });
    },
  
    async getAuditHistory(shopId) {
      try {
        const response = await axios.get(`/api/audit/history/${shopId}`);
        this.auditHistory = response.data;
      } catch (error) {
        console.error("Error fetching audit history:", error);
      }
    },
  
    openQuantityModal(info) {
      this.selectedProduct = info;
      this.quantityToAdd = 0;
      this.quantityModalVisible = true;
    },
  
    closeQuantityModal() {
      this.selectedProduct = null;
      this.quantityToAdd = 0;
      this.quantityModalVisible = false;
    },
  
    async addQuantity() {
      const updatedProduct = { ...this.selectedProduct };
      updatedProduct.prod_quantity += parseInt(this.quantityToAdd);
      const response = await axios.put(
        `/updateQuantity/${updatedProduct.shop_id}`,
        {
          id: updatedProduct.id,
          quantity: this.quantityToAdd,
        }
      );
      const index = this.infos.findIndex(
        (product) => product.id === updatedProduct.id
      );
      this.infos[index] = updatedProduct;
      this.closeQuantityModal();
      this.showSuccessNotification("Quantity Updated Successfully");
      this.getInfo();
      this.$emit("data-saved");
    },
  
    showSuccessNotification(message) {
      this.notification = {
        show: true,
        type: "success",
        message: message,
      };
  
      setTimeout(() => {
        this.notification.show = false;
      }, 2000);
    },
  
    showErrorNotification(message) {
      this.notification = {
        show: true,
        type: "error",
        message: message,
      };
  
      setTimeout(() => {
        this.notification.show = false;
      }, 2000);
    },
  
    async getInfo() {
  try {
    const inf = await axios.get("getDataShop");
    this.infos = inf.data;
    this.shop = inf.data;
    this.checkReorderLevels();
  } catch (error) {
    console.log(error);
  }
},

checkReorderLevels() {
  this.lowStockItems = this.infos.filter(product => product.prod_quantity < this.reorderLevel);

  if (this.lowStockItems.length > 0) {
    const itemNames = this.lowStockItems.map(item => item.prod_name).join(', ');
    this.notification.message = `Low stock alert: ${itemNames} are below the reorder level.`;
    this.notification.type = 'danger'; // Set notification type to danger
    this.notification.show = true;
  } else {
    this.notification.show = false; // Hide notification if no low stock items
  }
},



showNotification(message, type, persistent = false) {
  // Add a new notification to the array
  this.notifications.push({
    message,
    type,
    show: true,
    persistent,
  });

  // Hide notification after 3 seconds if not persistent
  if (!persistent) {
    setTimeout(() => {
      // Remove the notification after 3 seconds
      this.notifications.shift(); // Remove the first notification
    }, 3000);
  }
},
    openAddModal() {
      this.addModalVisible = true;
    },
  
    closeAddModal() {
      this.addModalVisible = false;
   
    },
    handleImageUpload() {
      const fileInput = this.$refs.imageInput;
      const file = fileInput.files[0];
  
      const formData = new FormData();
      formData.append("prod_image", file);
  
      this.prod_img = formData;
    },
  
      async saveShop(mode) {
        try {
          let data = {};
          if (mode === "add") {
            
            if (this.prod_img instanceof FormData) {
              data = this.prod_img;
              data.append("prod_name", this.prod_name);
              data.append("prod_quantity", this.prod_quantity);
              data.append("prod_desc", this.prod_desc);
              data.append("prod_price", this.prod_price);
              this.showSuccessNotification("Product Added Successfully");
            } else {
              data = {
                prod_name: this.prod_name,
                prod_quantity: this.prod_quantity,
                prod_desc: this.prod_desc,
                prod_price: this.prod_price,
              };
            }
          }
  
          const ins = await axios.post("saveShop", data);
  
          if (mode === "add") {
            this.closeAddModal();
            this.prod_img = "";
            this.prod_name = "";
            this.prod_quantity = "";
            this.prod_desc = "";
            this.prod_price = "";
            this.getInfo();
          }
        } catch (error) {
          console.error(error);
        }
      },
  
    openEditModal(info) {
      this.editedInfo = { ...info };
      this.editModalVisible = true;
    },
  
    closeEditModal() {
      this.editedInfo = null;
      this.editModalVisible = false;
      this.showSuccessNotification("Product Updated Successfully");
    },
    async saveEdit() {
      try {
          const formData = new FormData(); // Use FormData to handle image uploads
          formData.append("prod_name", this.editedInfo.prod_name);
          formData.append("prod_desc", this.editedInfo.prod_desc);
          formData.append("prod_price", this.editedInfo.prod_price);
          
          // Append the new image if it exists
          if (this.editedInfo.newImage) {
              formData.append("prod_img", this.editedInfo.newImage);
          }
  
          const apiUrl = `/updateShop/${this.editedInfo.shop_id}`;
          const response = await axios.post(apiUrl, formData, {
              headers: {
                  'Content-Type': 'multipart/form-data' // Set content type to multipart/form-data
              }
          });
  
          console.log("Product updated successfully:", response.data);
          this.closeEditModal();
          this.showSuccessNotification("Product Updated Successfully");
  
          this.getInfo();
  
          this.$emit("data-saved");
      } catch (error) {
          console.error("Error updating product:", error);
      }
  },
  
  
  handleEditImageUpload() {
      const fileInput = this.$refs.editImageInput;
      const file = fileInput.files[0];
  
      this.editedInfo.newImage = file; // Store the file directly for upload
  },
  
  },
  };
  </script>
  
  
  <style scoped>
  .disabled {
    pointer-events: none; /* Disable click events */
    opacity: 0.5; /* Make it look inactive */
  }
  
  h1 {
    color: white;
    text-align: left;
    margin: 0; /* Remove default margin */
  }
  
  i {
    padding-right: 38px;
    padding-left: 10px;
  }
  
  span{
    padding: 0;
    margin: 0;
  }
  
  /* Sidebar styles */
  .sidebar {
    width: 93px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #0F172B;
    color: #fff;
    padding: 20px;
    overflow-y: auto;
    transition: width 0.5s; /* Smooth transition for the width */
  }
  
  .sidebar img.logo {
    width: 50px; 
    height: auto; 
    display: block;
    transition: width 0.5s; /* Smooth transition for the logo size */
  }
  
  .sidebar:hover img.logo {
    width: 100px; 
  }
  
  .sidebar a, .sidebar router-link {
    display: flex;
    align-items: center;
    color: #fff;
    padding: 10px;
    text-decoration: none;
    margin: 5px 0;
  }
  
  .sidebar a:hover, .sidebar router-link:hover {
    background-color: #FEA116;
    transition: background-color 0.5s; /* Smooth transition for hover effect */
  }
  
  .sidebar:hover {
    width: 200px;
  }
  
  
  /* Make sidebar responsive */
  @media (max-width: 768px) {
    .sidebar {
      width: 100%; /* Full width on small screens */
      height: auto; /* Height auto adjusts */
      position: relative; /* Change position to relative */
      overflow: hidden; /* Hide overflow on small screens */
     
    }
  
    .sidebar img.logo {
      width: 80px; /* Adjust logo size for small screens */
    }
  
    .sidebar:hover {
      width: 100%; /* Full width on hover for small screens */
    }
  
    .sidebar a, .sidebar router-link {
      justify-content: center; /* Center items for small screens */
    }
  }
  
  .main-content {
    margin-left: 93px; /* Default margin for the sidebar width */
    padding: 0; /* Remove default padding */
    transition: margin-left 0.5s; /* Smooth transition for the margin */
  }
  
  
  /* Adjust main content for smaller screens */
  @media (max-width: 768px) {
    .main-content {
      margin-left: 0; /* Remove left margin for small screens */
    }
  }
  
  
  
  
  .logout-logo-btn {
    background-color: #FEA116;
    border: none;
    padding: 10px 15px;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: flex;
    align-items: center;
    position: absolute;
    bottom: 10px; /* Position at the bottom */
    right: 20px; /* Align to the right */
  }
  
  .logout-logo-btn i {
    margin-right: 5px; /* Space between icon and text */
  }
  
  .card-main {
    background-color: #f4f4f4;
    padding: 20px;
    margin: 20px; /* Add margin around the card */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add shadow for better visibility */
    transition: 0.5s;
  }
  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }
  
  .table-product {
    width: 100%;
    border-collapse: collapse;
  }
  
  .table-product th {
  background-color: #0F172B; /* Dark background for header */
  color: #ffffff; /* White text color */
 margin: 0;
 padding: 12px;
  text-align: center; /* Align text to the left */
  font-weight: bold; /* Bold text */
  border-right: 2px solid #ddd; /* Bottom border for header */
  
}
  
  .card-body .modal-body, .card-body .alert, th , .modal-body {
    font-family: "Poppins", sans-serif;
    font-weight: 800;
    font-style: normal;
    font-size: large;
    color:#0F172B;
  }
  
  .modal {
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 1050; /* Ensure the modal backdrop is on top */
  }
  
  /* Modal Dialog */
  .modal-dialog {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100% - 1rem);
  }
  
  /* Modal Content */
  .modal-content {
    border-radius: 0.3rem;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin: 1rem;
    max-width: 500px; /* Adjust as needed */
    width: 100%;
  }
  
  
  .table-product tbody  td {
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
    color:#0F172B;
    margin: 0;
    padding: 0;
  }
  
  
  .table-product tbody td.action-buttons {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    white-space: nowrap;
    margin: 0;
    padding: 0;
    margin-top: 25px;
  }
  .table-product tbody td {
    padding: 12px; /* Ensure padding is consistent */
  
  
  }
  td img {
    display: block; /* Make sure the image is block-level to avoid being clipped */
    max-width: 100%; /* Ensure image fits inside the cell */
    height: auto;
  }
  .table-responsive {
    max-height: none; /* Or increase the max-height to allow space for images */
    overflow-y: visible; /* To ensure images are not cut off */
  }
  table {
    table-layout: auto;
  }
  
  
  .table-product tbody tr:hover {
    background-color: rgb(253, 253, 221); /* Change background color on hover */
  }
  /* Header alignment for actions column */
  .actions-header {
    text-align: center;
    overflow: hidden; /* Ensures no scrollbars appear */
    white-space: nowrap; /* Prevents wrapping */
  }
  
  
  
  .dropdown {
  
    position: relative;
  }
  .dropdown * {
    box-sizing: border-box;
  }
  .select {
    background: #fea116;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
  
    padding: 1em;
    cursor: pointer;
    transition: background 0.3s;
    border-radius: 10px;
  }
  .select-clicked {
    border: 2px #fea116 solid;
    box-shadow: 0 0 0.8em #fea116;
  }
  .select:hover {
    background: #0F172B;
  }
  
  .caret {
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid white;
    transition: 0.3s;
  }
  .caret-rotate {
    transform: rotate(180deg);
  }
  .menu {
    list-style: none;
    padding: 0.2em 0.5em;
    background: #fff;
    border: 1px #fff solid;
    box-shadow: 0 0.5em 1em rgba(0, 0, 0, 0.2);
    border-radius: 0.5em;
    color: #fea116;
    position: absolute;
    top: 3em;
    left: 50%;
    width: 100%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 9999; /* Ensure the dropdown appears above other elements */
    display: none;
    
  }
  .menu-open {
    display: block;
    opacity: 1;
  }
  .menu li {
    padding: 0.7em 0.5em;
    margin: 0.3em 0;
    border-radius: 0.5em;
    cursor: pointer;
  }
  .menu li:hover {
    background: #0F172B;
  }
  .active {
    background: #fea116;
  }
  menu.no-scroll {
    overflow: hidden;
  }
  
  @media (max-width: 768px) {
    .table-product th, .table-product td {
      padding: 8px;
    }
  
  }
  .btn{
    border-radius:5px;
   
  }
  .btn-custom {
    background-color: #fea116; /* Same as Add Item button */
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  padding: 10px;  
  }
  .btn-info{
    background-color: #0077b6; /* Same as Add Item button */
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  padding: 10px; 
  
  }
  .btn-primary {
    background-color: #046f09; /* Same as Add Item button */
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  padding: 10px;  
  }
  .btn-secondary {
    background-color: #e21604; /* Same as Add Item button */
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  padding: 10px;  
  }.btn-tertiary {
    background-color: #fea116; /* Same as Add Item button */
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  padding: 10px;  
  }
  
  
  
  .btn-custom:hover{
  color:white;
  }
  
  .btn:hover {
    background-color: #0F172B;
    transform: scale(1.10);
    border:none;
  }
  
  
  
  /* Sidebar styles */
  .sidebar {
    width: 93px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #0F172B;
    color: #fff;
    padding: 20px;
    overflow-y: auto;
  
  
  }
  
  .sidebar img.logo {
    width: 50px;
    height: auto;
    display: block;
    transition: width 0.5s; /* Smooth transition for the logo size */
  }
  
  .sidebar:hover img.logo {
    width: 100px;
  }
  
  
  .sidebar a,
  .sidebar router-link {
    display: flex;
    align-items: center;
    color: #fff;
    padding: 10px;
    text-decoration: none;
    margin: 5px 0;
  }
  
  .sidebar a:hover,
  .sidebar router-link:hover {
    background-color: #FEA116;
    transition: background-color 0.5s; 
  }
  
  
  .main-content {
    margin-left: 93px; 
    padding: 0;
    transition: margin-left 0.5s; /* Smooth transition for the margin */
    overflow-y: auto; /* Enable scrolling if the content is too long */
    height: calc(100vh - 60px); 
  }
  .header {
    background-color: #0F172B;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    top: 0;
    z-index: 1;
    transition: 0.5s;
    position: relative;
  }
  .card-main {
    background-color: #f4f4f4;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add shadow for better visibility */
    transition: 0.5s;
  }
  .sidebar:hover ~ .card-main {
    margin-left: 200px;
    
  }
  .sidebar:hover ~ .header {
    margin-left: 50px;
  }
  /* Navbar Toggler for Small Screens */
  .navbar-toggler {
    display: none;
    background-color: #FEA116;
    border: none;
    color:white;
    cursor: pointer;
    border-radius: 5px;
   padding: 0;
   margin:0;
    position: absolute;
    right: 20px;
    align-items: center;
  }
  .navbar-toggler-icon{
    padding: 0;
    margin: 0;
  }
  .sidebar.collapsed + .navbar-toggler {
    display: block;
    transition: 0.3s;
  }
  
  @media (max-width: 768px) { 
    .header {
      display: flex;
      align-items: center; /* Vertically center items */
      position: relative; /* Ensure positioning context */
    }
    
    /* Adjust header padding for smaller screens */
  
    .sidebar {
      width: 100%; /* Full width on small screens */
      height: auto; /* Height auto adjusts */
      position: fixed; 
      overflow: hidden; /* Hide overflow on small screens */
      transform: translateY(-100%); /* Hide sidebar by default on small screens */
    
    }
  
    .sidebar.collapsed {
      transform: translateY(0); /* Show sidebar when toggled */
      position: relative; 
      transition: 0.2s;
    }
    .main-content {
      margin-left: 0;
      height: auto;
  
    }
  
    .navbar-toggler {
      display: block;
      top: 10px;
    } 
    .header h1 {
      margin-bottom: 50px; /* Adjust spacing for smaller screens */
    }
  
    .logout-logo-btn {
      position: absolute;
      bottom: 10px; /* Ensure it stays at the bottom */
      right: 20px; /* Align to the right side */
    }
    
  }
  
  /* Image in Table Styling */
  .cottage-image {
    max-width: 100px; /* Adjust the size as needed */
    height: auto;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
  }
  
  .cottage-image:hover {
    transform: scale(1.05);
    transition: transform 0.2s;
  }
  .room-image {
          max-width: 80px;
          height: auto;
          object-fit: cover;
          border-radius: 5px;
      }
  
      .table-product {
          width: 100%;
          margin-top: 10px;
      }
  
  
  
      .alert {
          margin-top: 10px;
      }
      .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
  }
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 500px;
    max-width: 90%;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .close-button {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
  }
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
  }
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 500px;
    max-width: 90%;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
  }
  
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .close-button {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
  }
  
  .caret {
    margin-left: 10px;
    transition: transform 0.3s;
  }
  
  .caret-rotate {
    transform: rotate(180deg);
  }
  
  .menu {
    display: none;
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-top: 5px;
    padding: 5px 0;
    list-style: none;
  }
  
  .menu-open {
    display: block;
  }
  
  .menu li {
    padding: 8px 12px;
    cursor: pointer;
  }
  
  .menu li:hover {
    background-color: #f0f0f0;
  }
  .modal.fade.show {
    display: block;
    background: rgba(0, 0, 0, 0.5);
  }
  
  .modal-dialog {
    max-width: 500px;
    width: 100%;
  }
  
  .modal-header .close {
    border: none;
    background: none;
    font-size: 24px;
    cursor: pointer;
  }
  details {
  position: relative;
  display: inline-block;
}

details summary {
  cursor: pointer;
  
  font-weight: bold;
}

/* Align items in the summary */
.inventory-summary {
  display: flex;
  align-items: center;
  gap: 4px; /* Space between elements */
  cursor: pointer;
  padding: 10px;
  color: #fff;
}

/* Style for the dropdown icon */
.inventory-summary .dropdown-icon {
  font-size: 25px;
  transition: transform 0.3s ease; /* Smooth rotation for the arrow */
}

/* Rotate the dropdown icon when details are open */
details[open] .inventory-summary .dropdown-icon {
  transform: rotate(180deg); /* Arrow points up when open */
}
details:hover{
  background-color: #FEA116;
  transition: background-color 0.5s; 
}
.dropdown-menu {
  display: none;

  flex-direction: column;
  gap: 10px;
  border: 1px solid #ccc;
  background-color: navy;
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 10;
}

details[open] .dropdown-menu {
  display: block;
}

.dropdown-menu router-link {
  text-decoration: none;
  color: #333;
}

  </style>