<template>

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
   
      <div class="content-wrapper">
        <div class="content">
          <div class="card card-default">
            <div class="card-header">
              <h2>Staff List</h2>
              <button type="button" class="btn btn-custom" @click="openAddModal">Add Staff</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
              <table class="table-product ">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Use v-for to iterate through your staff data -->
                  <tr v-for="(staff, index) in staff" :key="index">
                    <td>{{ staff.staff_id }}</td>
                    <td>{{ staff.staff_image }}</td>
                    <td>{{ staff.staff_name }}</td>
                    <td>{{ staff.staff_email }}</td>
                    <td>{{ staff.contactNum }}</td>
                    <td>
                      <button class="btn btn-custom" @click="openStaffEditModal(staff)">Edit</button> |
                      <button class="btn btn-custom" @click="hidestaff(staff.staff_id)">Hide</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            </div>
          </div>
        </div>
      </div>
  
  <!-- Delete Confirmation Modal -->
  <div v-if="deleteConfirmationVisible" class="modal" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="close" @click="cancelDelete">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this staff member?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-custom" @click="cancelDelete">Cancel</button>
        <button type="button" class="btn btn-danger" @click="deleteStaffConfirmed">Delete</button>
      </div>
    </div>
  </div>
  </div>
  
    <!-- Add Modal -->
    <div v-if="addModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
    <div class="modal-header">
              <h5 class="modal-title ">Add Staff</h5>
              <button  type="button" class="close" @click="closeAddModal">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Add form -->
              <form @submit.prevent="saveStaff('add')">
                <div class="form-group">
                  <label for="staff_image">Staff Image</label>
                  <input type="file" @change="handleStaffImageUpload" accept="image/*" required>
                </div>
  
                <div class="form-group">
                  <label for="staff_name">Staff Name</label>
                  <input type="text" class="form-control" placeholder="Name" v-model="staff_name" required>
                </div>
                <div class="form-group">
                  <label for="staff_email">Staff Email</label>
                  <input type="email" class="form-control" placeholder="Email" v-model="staff_email" required>
                </div>
                <div class="form-group">
                  <label for="contactNum">Contact Number</label>
                  <input type="text" class="form-control" placeholder="Contact Number" v-model="contactNum" required>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-custom" @click="closeAddModal">Close</button>
                  <button type="submit" class="btn btn-custom">Save Staff</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Edit Staff Modal -->
    <div v-if="editStaffModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Staff</h5>
        <button type="button" class="close" @click="closeStaffEditModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Edit form for staff -->
        <form @submit.prevent="saveStaffEdit">
          <div class="form-group">
            <label for="staff_name">Staff Name</label>
            <input type="text" class="form-control" placeholder="Name" v-model="editedStaff.staff_name">
          </div>
          <div class="form-group">
            <label for="staff_email">Staff Email</label>
            <input type="email" class="form-control" placeholder="Email" v-model="editedStaff.staff_email">
          </div>
          <div class="form-group">
            <label for="contactNum">Contact Number</label>
            <input type="text" class="form-control" placeholder="Contact Number" v-model="editedStaff.contactNum">
          </div>
          <!-- Add other fields as needed -->
  
          <div class="modal-footer">
            <button type="button" class="btn btn-custom" @click="closeStaffEditModal">Close</button>
            <button type="submit" class="btn btn-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  </div>



    <div class="row">
    <div class="content-wrapper">
    <div class="content">
          <div class="card card-default">
            <div class="card-header">
      <h2>Assign Amenities to Rooms</h2>
    </div>
    <div class="card-body">
              <div class="table-responsive">
              <table class="table table-product ">
        <thead>
          <tr>
            <th>Room Name</th>
            <th>Select Amenities</th>
            <th>Assigned Amenities</th> <!-- New column for assigned amenities -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="room in rooms" :key="room.room_id">
            <td>{{ room.room_name }}</td>
            <td>
              <!-- Dropdown with Checkboxes -->
              <div class="dropdown">
                <button class="btn btn-custom" @click="toggleDropdown(room.room_id)">
                  Select Amenities
                </button>
                <div v-if="isDropdownOpen(room.room_id)" class="dropdown-content">
                  <div v-for="amenity in amenities" :key="amenity.amenity_id">
                    <input
                      type="checkbox"
                      :id="`dropdown-amenity-${room.room_id}-${amenity.amenity_id}`"
                      :value="amenity.amenity_id"
                      :checked="room.amenities.includes(amenity.amenity_id)"
                      @change="toggleAmenity(room.room_id, amenity.amenity_id)"
                    />
                    <label :for="`dropdown-amenity-${room.room_id}-${amenity.amenity_id}`">{{ amenity.amenity_name }}</label>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <!-- Display assigned amenities -->
              <ul>
                <li v-for="amenity in room.assignedAmenities" :key="amenity.amenity_id">{{ amenity.amenity_name }}</li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
      <Notification :show="notification.show" :type="notification.type" :message="notification.message" />
    </div>
  </div>
</div>
</div>

    <div class="row">
    <!-- Table Amenities -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Amenities Inventory</h2>
          <button type="button" class="btn btn-custom" @click="openAddAmenitiesModal">
            Add Amenity
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="amenitiesTable" class="table table-product" >
              <thead>
                <tr>
                  <th>Amenity Name</th>
                  <th>Description</th>
                  <th>Stocks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <tr v-for="(amenity, index) in amenities" :key="amenity.amenity_id">
      <td>{{ amenity.amenity_name }}</td>
      <td>{{ amenity.description }}</td>
      <td>{{ amenity.stock }}</td>
      <td class=" action-buttons">
        <button class="btn btn-custom" @click="openAddStockModal(amenity)">Add Stock</button>
        <button class="btn btn-custom" @click="openEditAmenitiesModal(amenity)">Edit</button>
        <button class="btn btn-danger" @click="deleteAmenity(amenity.amenity_id)">Delete</button>
        <button class="btn btn-info" @click="goToAudit(amenity.amenity_id)">View Audit</button>
      </td>
                </tr>
  
  </tbody>
  
            </table>
          </div>
          
          <!-- Success Message -->
          <div v-if="successMessage" class="alert alert-success" role="alert">
            {{ successMessage }}
          </div>
        </div>
      </div>
    </div>
  
    
    <!-- Edit Modal -->
    <div v-if="editAmenitiesModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Amenity</h5>
            <button type="button" class="btn btn-custom" @click="closeEditAmenitiesModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Edit form -->
            <form @submit.prevent="saveEditAmenities">
              <div class="form-group">
                <label for="edit_amenity_name">Amenity Name</label>
                <input type="text" class="form-control" placeholder="Name" v-model="editedAmenity.amenity_name" />
              </div>
              <div class="form-group">
                <label for="edit_amenity_desc">Amenity Description</label>
                <input type="text" class="form-control" placeholder="Description" v-model="editedAmenity.description" />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom" @click="closeEditAmenitiesModal">
                  Close
                </button>
                <button type="submit" class="btn btn-custom">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <!-- Add Stock Modal -->
  <div v-if="addStockModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Add Stock</h5>
                  <button type="button" class="btn btn-custom" @click="closeAddStockModal">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form @submit.prevent="saveAddStock">
                      <div class="form-group">
                          <label for="stock_amount">Stock Amount</label>
                          <input
                              type="number"
                              class="form-control"
                              v-model="newStock.amount"
                              min="1" 
                              required 
                          />
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-custom" @click="closeAddStockModal">Close</button>
                          <button type="submit" class="btn btn-custom">Add Stock</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
  
  
    <!-- Add Amenity Modal -->
    <div v-if="addAmenitiesModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Amenity</h5>
            <button type="button" class="btn btn-custom" @click="closeAddAmenitiesModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Add form -->
            <form @submit.prevent="saveAddAmenities">
              <div class="form-group">
                <label for="new_amenity_name">Amenity Name</label>
                <input type="text" class="form-control" placeholder="Name" v-model="newAmenity.amenity_name" />
              </div>
              <div class="form-group">
                <label for="new_amenity_desc">Amenity Description</label>
                <input type="text" class="form-control" placeholder="Description" v-model="newAmenity.description" />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom" @click="closeAddAmenitiesModal">
                  Close
                </button>
                <button type="submit" class="btn btn-custom">Add Amenity</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Success Message -->
    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
  </div>
  <div class="row">
          <div class="content-wrapper">
            <div class="content">
              <div class="card card-default">
                <div class="card-header">
                  <h2>Inventory List</h2>
                  <button type="button" class="btn btn-custom" @click="openAddInventoryModal">Add Inventory</button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table-product">
                      <thead>
                        <tr>
                          <th>Item Name</th>
                          <th>Quantity</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(inventory, index) in inventory" :key="index">
                          <td>{{ inventory.item_name }}</td>
                          <td>{{ inventory.quantity }}</td>
                          <td>
                            <button class="btn btn-custom" @click="openEditModal(inventory)">Edit</button> |
                            <button class="btn btn-danger" @click="deleteInventory(inventory.inventory_id)">Delete</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Inventory Modal -->
    <div v-if="addInventoryModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Inventory</h5>
            <button type="button" class="close" @click="closeInventoryAddModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="addInventory">
              <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" class="form-control" v-model="newInventory.item_name" required />
              </div>
              <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" v-model="newInventory.quantity" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom" @click="closeInventoryAddModal">Close</button>
                <button type="submit" class="btn btn-custom">Save Inventory</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Inventory Modal -->
    <div v-if="editModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Inventory</h5>
            <button type="button" class="close" @click="closeEditModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateInventory">
              <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" class="form-control" v-model="editedInventory.item_name" required />
              </div>
              <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" v-model="editedInventory.quantity" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom" @click="closeEditModal">Close</button>
                <button type="submit" class="btn btn-custom">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
  </div>
</div>

    <Notification :show="notification.show" :type="notification.type" :message="notification.message" />
  </template>
  
  <script>
  
  import Notification from '@/components/Notification.vue';
  
  import axios from 'axios';
  
  export default {
  name: 'YourComponentName',
  components: {
  
    Notification,
  },
  data() {
    return {
      inventory: [], // List of inventory items
      newInventory: { item_name: '', quantity: '' }, // New inventory item data
      editedInventory: { item_name: '', quantity: '' }, // Edited inventory item data
      addInventoryModalVisible: false,
      editModalVisible: false,
  
      addStockModalVisible: false,
          newStock: { amount: 0 },
      amenities: [], // List of amenities
      newAmenity: { amenity_name: '', description: '' }, // For adding a new amenity
      editedAmenity: {}, // For editing an existing amenity
      addAmenitiesModalVisible: false, // Add modal visibility
      editAmenitiesModalVisible: false, // Edit modal visibility
      notification: { show: false, type: '', message: '' }, // Notification state
      rooms: [], // List of rooms with assigned amenities
        amenities: [], // List of available amenities
        newAmenity: {
          name: '',
          description: ''
        },
        notification: {
          show: false,
          type: "",
          message: "",
        },
        openDropdowns: {}, // Track open/close state of dropdowns
      isSidebarCollapsed: false,
    staff: [],
    addModalVisible: false,
    staff_image: '',
    staff_name: '',
    staff_email: '',
    contactNum: '',
    successMessage: "",
    notification: {
      show: false,
      type: "",
      message: "",
    },
    editStaffModalVisible: false, 
    editedStaff: null,
    deleteConfirmationVisible: false, 
  
  };
  },
  created() {
    this.fetchInventory(); // Fetch inventory when the component is created
  },
  mounted() {
    this.getStaff();
    this.fetchRoomsAndAmenities();
  
  },
  methods: {
    // Fetch all inventory items
async fetchInventory() {
  try {
    const response = await axios.get('/inventory');
    this.inventory = response.data;
  } catch (error) {
    console.error('Error fetching inventory:', error);
  }
},

// Open the Add Inventory Modal
openAddInventoryModal() {
  this.addInventoryModalVisible = true;
},

// Close the Add Inventory Modal
closeInventoryAddModal() {
  this.addInventoryModalVisible = false;
},

// Open the Edit Inventory Modal
openEditModal(item) {
  this.editedInventory = { ...item }; // Pre-fill the form with existing data
  this.editModalVisible = true;
},

// Close the Edit Inventory Modal
closeEditModal() {
  this.editModalVisible = false;
},

// Add a new inventory item
async addInventory() {
  try {
    const response = await axios.post('/addinventory', this.newInventory);
    if (response.data.status === 'success') {
      this.fetchInventory(); // Reload the inventory list
      this.closeInventoryAddModal(); // Close the modal
      this.showNotification('success', response.data.message);
    } else {
      this.showNotification('error', 'Failed to add inventory item');
    }
  } catch (error) {
    console.error('Error adding inventory:', error);
    this.showNotification('error', 'Error adding inventory item');
  }
},

// Update an existing inventory item
async updateInventory() {
  try {
    const response = await axios.post(`/updateinventory/${this.editedInventory.inventory_id}`, this.editedInventory);
    if (response.data.status === 'success') {
      this.fetchInventory(); // Reload the inventory list
      this.closeEditModal(); // Close the modal
      this.showNotification('success', response.data.message);
    } else {
      this.showNotification('error', 'Failed to update inventory item');
    }
  } catch (error) {
    console.error('Error updating inventory:', error);
    this.showNotification('error', 'Error updating inventory item');
  }
},

// Delete an inventory item
async deleteInventory(inventory_id) {
  try {
    const response = await axios.delete(`/deleteinventory/${inventory_id}`);
    if (response.data.status === 'success') {
      this.fetchInventory(); // Reload the inventory list
      this.showNotification('success', response.data.message);
    } else {
      this.showNotification('error', response.data.message || 'Failed to delete inventory item');
    }
  } catch (error) {
    console.error('Error deleting inventory:', error);
    this.showNotification('error', 'Error deleting inventory item: ' + error.message);
  }
},

// Show Notification
showNotification(type, message) {
  this.notification.type = type;
  this.notification.message = message;
  this.notification.show = true;

  // Automatically hide the notification after 2 seconds
  setTimeout(() => {
    this.notification.show = false;
  }, 2000); // Hide after 2 seconds
},
        goToAudit(amenityId) {
      this.$router.push({ name: 'AmenitiesAudit', params: { amenityId } });
    },
  // Open the Add Stock Modal
  openAddStockModal(amenity) {
      this.newStock.amount = 0; // Reset amount
      this.editedAmenity = amenity; // Store the amenity to which the stock is being added
      this.addStockModalVisible = true;
  },
  
  // Close the Add Stock Modal
  closeAddStockModal() {
      this.addStockModalVisible = false;
  },
  
  async saveAddStock() {
      try {
          const response = await axios.post(`/api/amenities/${this.editedAmenity.amenity_id}/stock`, this.newStock);
          if (response.data.status === 'success') {
              this.fetchAmenities(); // Refresh the amenities list
              this.closeAddStockModal();
              this.showNotification('success', response.data.message);
          } else {
              throw new Error(response.data.message || 'Unknown error occurred');
          }
      } catch (error) {
          console.error('Error adding stock:', error);
          this.showNotification('error', error.response?.data?.message || 'Failed to add stock');
      }
  },
  
  async fetchAmenities() {
      try {
          const response = await axios.get('/api/amenities');
          this.amenities = response.data;
      } catch (error) {
          console.error('Error fetching amenities:', error);
          this.showNotification('error', 'Error fetching amenities');
      }
  },
  
  // Open the Add Amenities Modal
  openAddAmenitiesModal() {
      this.newAmenity = { amenity_name: '', description: '' };
      this.addAmenitiesModalVisible = true;
  },
  
  // Close the Add Amenities Modal
  closeAddAmenitiesModal() {
      this.addAmenitiesModalVisible = false;
  },
  
  // Save the new amenity to the backend
  async saveAddAmenities() {
      try {
          const response = await axios.post('/api/amenities', this.newAmenity);
          if (response.data.status === 'success') {
              this.fetchAmenities(); // Refresh the amenities list
              this.closeAddAmenitiesModal();
              this.showNotification('success', response.data.message);
          }
      } catch (error) {
          console.error('Error adding amenity:', error);
          this.showNotification('error', 'Error adding amenity');
      }
  },
  
  // Open the Edit Amenities Modal
  openEditAmenitiesModal(amenity) {
      this.editedAmenity = { ...amenity }; // Clone the selected amenity for editing
      this.editAmenitiesModalVisible = true;
  },
  
  // Close the Edit Amenities Modal
  closeEditAmenitiesModal() {
      this.editAmenitiesModalVisible = false;
  },
  
  // Save the edited amenity to the backend
  async saveEditAmenities() {
      try {
          const response = await axios.put(`/api/amenities/${this.editedAmenity.amenity_id}`, this.editedAmenity);
          if (response.data.status === 'success') {
              this.fetchAmenities(); // Refresh the amenities list
              this.closeEditAmenitiesModal();
              this.showNotification('success', response.data.message);
          }
      } catch (error) {
          console.error('Error updating amenity:', error);
          this.showNotification('error', 'Error updating amenity');
      }
  },
  
  // Show Notification
  showNotification(type, message) {
      this.notification.type = type;
      this.notification.message = message;
      this.notification.show = true;
  
      // Automatically hide the notification after 2 seconds
      setTimeout(() => {
          this.notification.show = false;
      }, 2000); // Change to 2000 milliseconds (2 seconds)
  },
  
  // Delete an amenity from the backend
  async deleteAmenity(id) {
      try {
          const response = await axios.delete(`/api/amenities/${id}`);
          if (response.data.status === 'success') {
              this.fetchAmenities(); // Refresh the amenities list
              this.showNotification('success', response.data.message);
          } else {
              this.showNotification('error', response.data.message || 'Failed to delete amenity.');
          }
      } catch (error) {
          console.error('Error deleting amenity:', error);
          this.showNotification('error', 'Error deleting amenity: ' + error.message);
      }
  },
  
    fetchRoomsAndAmenities() {
        axios.get('/getRoom')
          .then(response => {
            console.log('Rooms data:', response.data);
            this.rooms = response.data.map(room => ({
              ...room,
              amenities: room.amenities ? room.amenities.map(a => a.amenity_id) : [],
              assignedAmenities: [] // Initialize with empty list
            }));
            // Fetch amenities for each room
            this.rooms.forEach(room => this.getAmenitiesForRoom(room.room_id));
          })
          .catch(error => {
            console.error('Error fetching rooms:', error);
          });
  
        axios.get('/getAllAmenities')
          .then(response => {
            console.log('Amenities data:', response.data);
            this.amenities = response.data;
          })
          .catch(error => {
            console.error('Error fetching amenities:', error);
          });
      },
      toggleAmenity(roomId, amenityId) {
        const room = this.rooms.find(r => r.room_id === roomId);
        const index = room.amenities.indexOf(amenityId);
        if (index > -1) {
          room.amenities.splice(index, 1);
        } else {
          room.amenities.push(amenityId);
        }
        this.updateRoomAmenities(roomId, room.amenities);
      },
      updateRoomAmenities(roomId, amenities) {
        axios.post(`/assignAmenitiesToRoom/${roomId}`, { amenities })
          .then(response => {
            if (response.data.status !== 'success') {
              this.showNotification('error', 'Failed to update amenities');
            } else {
              // Refresh assigned amenities for the room
              this.getAmenitiesForRoom(roomId);
            }
          })
          .catch(error => {
            console.error('Error updating room amenities:', error);
            this.showNotification('error', 'Failed to update amenities');
          });
      },
      addAmenity() {
      const newAmenity = { 
        amenity_name: this.newAmenity.name, 
        description: this.newAmenity.description 
      };
  
      axios.post('/addAmenity', newAmenity)
        .then(response => {
          if (response.data.status === 'success') {
            this.fetchRoomsAndAmenities(); // Refresh the amenities list
            this.newAmenity.name = '';
            this.newAmenity.description = '';
            this.showNotification('success', 'Amenity added successfully');
          } else {
            this.showNotification('error', 'Failed to add amenity');
          }
        })
        .catch(error => {
          console.error('Error adding amenity:', error);
          this.showNotification('error', 'Failed to add amenity');
        });
    },
    
    showNotification(type, message) {
      this.notification = {
        show: true,
        type,
        message
      };
      
      // Hide notification after 5 seconds
      setTimeout(() => {
        this.notification.show = false;
      }, 2000);
    },
      toggleDropdown(roomId) {
        this.openDropdowns = {
          ...this.openDropdowns,
          [roomId]: !this.openDropdowns[roomId]
        };
      },
      isDropdownOpen(roomId) {
        return !!this.openDropdowns[roomId];
      },
      getAmenitiesForRoom(roomId) {
        axios.get(`/getAmenitiesForRoom/${roomId}`)
    .then(response => {
      console.log('Amenities response:', response.data); // Log the response
      const room = this.rooms.find(r => r.room_id === roomId);
      if (room) {
        room.assignedAmenities = response.data; // Update assigned amenities
      }
    })
    .catch(error => {
      console.error('Error fetching amenities for room:', error);
    });
  
      },
  
  
    toggleSidebar() {
        this.isSidebarCollapsed = !this.isSidebarCollapsed;
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
    async hidestaff(staffId) {
      const confirmResult = window.confirm("Do you want to HIDE this item?");
  
      if (confirmResult) {
        try {
          await axios.post(`/api/staffback/hide/${staffId}`);
          this.staff = this.staff.map(staff => {
            if (staff.staff_id === staffId) {
              return { ...staff, is_hidden: 1 };
            }
            return staff;
          });
          console.log('staffback hidden successfully');
        } catch (error) {
          console.error('Error hiding staffback:', error);
        }
      }
    },
    confirmDeleteStaff(staff) {
    this.editedStaff = { ...staff };
    this.deleteConfirmationVisible = true;
  },
  
  cancelDelete() {
    this.editedStaff = null;
    this.deleteConfirmationVisible = false;
  },
  
  async deleteStaffConfirmed() {
    try {
      const apiUrl = `/deleteStaff/${this.editedStaff.staff_id}`;
      const response = await axios.delete(apiUrl);
  
      console.log('Staff deleted successfully:', response.data);
      this.cancelDelete();
      this.showSuccessNotification("Staff Deleted Successfully");
      this.getStaff();
  
    } catch (error) {
      console.error('Error deleting staff:', error);
      this.showErrorNotification("Failed to delete staff");
    }
  },
  
    openStaffEditModal(staff) {
  this.editedStaff = { ...staff }; 
  this.editStaffModalVisible = true;
  },
  
    closeStaffEditModal() {
      this.editedStaff = null;
      this.editStaffModalVisible = false;
    },
  
    async saveStaffEdit() {
      try {
        const data = {
          staff_name: this.editedStaff.staff_name,
          staff_email: this.editedStaff.staff_email,
          contactNum: this.editedStaff.contactNum,
        };
  
        const apiUrl = `/updateStaff/${this.editedStaff.staff_id}`;
        const response = await axios.put(apiUrl, data);
  
        console.log('Staff updated successfully:', response.data);
        this.closeStaffEditModal();
        this.showSuccessNotification("Staff Updated Successfully");
        this.getStaff();
  
      } catch (error) {
        console.error('Error updating staff:', error);
        this.showErrorNotification("Failed to update staff");
      }
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
  
    openAddModal() {
      this.addModalVisible = true;
    },
  
    closeAddModal() {
      this.addModalVisible = false;
    },
  
    async saveStaff(mode) {
      try {
        let data = {};
  
        if (mode === 'add') {
          if (this.staff_image instanceof FormData) {
            data = this.staff_image;
            data.append('staff_name', this.staff_name);
            data.append('staff_email', this.staff_email);
            data.append('contactNum', this.contactNum);
          } else {
            data = {
              staff_name: this.staff_name,
              staff_email: this.staff_email,
              contactNum: this.contactNum,
            };
          }
        }
  
        const response = await axios.post('saveStaff', data);
  
        if (mode === 'add') {
          this.closeAddModal();
          this.staff_image = '';
          this.staff_name = '';
          this.staff_email = '';
          this.contactNum = '';
          this.showSuccessNotification("Staff Added Successfully");
  
          this.getStaff();
        }
      } catch (error) {
        console.error(error);
      }
    },
  
    async getStaff() {
      const response = await axios.get('/getStaff');
      this.staff = response.data;
    },
  
    // Add your other methods such as editStaff and deleteStaff here
  
    handleStaffImageUpload(event) {
      const file = event.target.files[0];
      const formData = new FormData();
      formData.append('staff_image', file);
  
      this.staff_image = formData;
    },
  },
  
  };
  
  </script>
  
  
  <style scoped>
  .room {
    margin-bottom: 20px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
  }
  
  table, th, td {
    border: 1px solid #ddd;
  }
  
  th, td {
    padding: 8px;
    text-align: left;
  }
  
  th {
    background-color: #f4f4f4;
  }
  
  /* Dropdown Styling */
  .dropdown {
    position: relative;
    display: inline-block;
  }
  

  .dropdown-content {
    display: block;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    max-height: 200px;
    overflow-y: auto;
  }
  
  .dropdown-content input[type="checkbox"] {
    margin-right: 5px;
  }
  
  .dropdown-content div {
    padding: 8px;
    cursor: pointer;
  }
  
  .dropdown-content div:hover {
    background-color: #f1f1f1;
  }
  
  .add-amenity {
    margin-top: 20px;
  }
  
  /* Existing styles */
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
  
  .sidebar:hover ~ .main-content {
    margin-left: 200px; /* Adjust margin when sidebar is expanded */
  }
  
  /* Adjust main content for smaller screens */
  @media (max-width: 768px) {
    .main-content {
      margin-left: 0; /* Remove left margin for small screens */
    }
  }
  
  .header {
    background-color: #0F172B;
    color: white;
    padding: 10px 20px; /* Adjust padding for space */
    display: flex;
    justify-content: space-between; /* Distribute space between header elements */
    align-items: center; /* Vertically center items */
    position: sticky;
    top: 0;
    z-index: 1; /* Ensure the header stays on top of other content */
    margin-left: 0; /* Remove left margin */
  }
  
  @media (max-width: 768px) {
    .header {
      flex-direction: column; /* Stack header items vertically on small screens */
      align-items: flex-start; /* Align items to the start on small screens */
    }
  }
  
  
  .logout-logo-btn {
    background-color: #FEA116; /* Background color for the logout button */
    border: none; /* Remove default border */
    padding: 10px 15px; /* Add some padding */
    color: white; /* Text color */
    cursor: pointer; /* Pointer cursor on hover */
    border-radius: 5px; /* Rounded corners */
    font-size: 14px; /* Adjust font size */
    display: flex;
    align-items: center;
  }
  
  .logout-logo-btn i {
    margin-right: 5px; /* Space between icon and text */
  }
  
  .card {
    background-color: #f4f4f4;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    margin-left: 20px;
    margin-right: 20px;
  }
  
  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }
 
  .table-product th {
  background-color: #0F172B; /* Dark background for header */
  color: #ffffff; /* White text color */
  padding: 12px; /* Padding inside header cells */
  text-align: center; /* Align text to the left */
  font-weight: bold; /* Bold text */
  border-right: 2px solid #ddd; /* Bottom border for header */
  
}

  .table-product {
    width: 100%;
    border-collapse: collapse;
  }
  .table-product tbody  td {
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
    color:#0F172B;
    margin: 0;
    padding: 0;
  }

  .card-body .modal-body, .card-body .alert, th , .modal-body {
    font-family: "Poppins", sans-serif;
    font-weight: 800;
    font-style: normal;
    font-size: large;
    color:#0F172B;
  }
   .modal-body, .card-body .alert, th {
    font-family: "Edu AU VIC WA NT Hand", cursive;
    font-weight: bold; 
    font-size: 17px; 
  }
  .table-product tbody td.action-buttons button,
  .table-product tbody td.action-buttons a, td {
   margin-left: 20px;
   margin-bottom: 20px;
    font-family: "Poppins", sans-serif;
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
  
  .table-product tbody tr {
    margin: 0;
    padding: 0;
  }
  .table-product tbody tr:hover {
    background-color: rgb(253, 253, 221); /* Change background color on hover */
  }
  /* Header alignment for actions column */
  .actions-header {
    text-align: center;
    
  }
  
  .table-product td {
    vertical-align: middle; /* Align the "Actions" button vertically */
  }
  
  
  
  @media (max-width: 768px) {
    .table-product th, .table-product td {
      padding: 8px;
    }
  
  }
  .btn{
    border-radius:5px;
   
  }
  .btn-custom, .edit-btn {
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
.btn-danger{
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
}
 
  .btn:hover {
    background-color: #0F172B;
    transform: scale(1.10);
    border:none;
  }


  .btn-custom i {
    margin-right: 5px; /* Space between icon and text */
  }
  .table-product td {
    vertical-align: middle; /* Align the "Actions" button vertically */
  }
  .table tbody tr:hover {
    background-color: rgb(253, 253, 221);  /* Change background color on hover */
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
    transition: background-color 0.5s; /* Smooth transition for hover effect */
  }
  
  
  .main-content {
    margin-left: 93px; /* Default margin for the sidebar width */
    padding: 0;
    transition: margin-left 0.5s; /* Smooth transition for the margin */
    overflow-y: auto; /* Enable scrolling if the content is too long */
    height: calc(100vh - 60px); /* Set height considering the header */
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
    padding: 20px;
    margin: 20px; /* Add margin around the card */
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
    .header {
      padding: 10px 20px; /* Add padding to header */
    }
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