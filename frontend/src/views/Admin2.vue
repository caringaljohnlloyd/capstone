<template>
  <Notification
  :show="notification.show"
  :type="notification.type"
  :message="notification.message"
/>
<div>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

  <div class="sidebar">
    <img :src="require('../assets/images/logo1.png.png')" alt="Mono" class="logo">
    <router-link to="/admin2">
      <i class="fa-solid fa-chart-simple"></i><span>Business Dashboard</span>
    </router-link>
    <router-link to="/analytics2">
      <i class="fa-solid fa-chart-line"></i><span>Analytics Board</span>
    </router-link>
    <router-link to="/teamadmin2">
      <i class="fa-solid fa-people-group"></i><span>Team</span>
    </router-link>
    <router-link to="/monitorusers2">
      <i class="fas fa-user"></i><span>Users</span>
    </router-link>
    <router-link to="/pos2">
      <i class="fa-solid fa-table-columns"></i><span>POS</span>
    </router-link>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>EDUARDO'S ADMIN</h1>
      <button @click="logout" class="btn btn-custom logout-logo-btn">
        <i class="fas fa-power-off logout-icon"></i>
        Logout
      </button>
    </div>

    <div class="card">
      <div class="content">
        
        <div class="row">
  <!-- Table Product -->
  <div class="col-12">
    <div class="card card-default">
      <div class="card-header">
        <h2>Shop Inventory</h2>
        <button
          type="button"
          class="btn btn-custom"
          @click="openAddModal"
        >
          Add Item
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table
            id="productsTable"
            class="table table-hover table-product"
            style="width: 100%"
          >
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
                <td>{{ info.prod_img }}</td>
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
        <div
          v-if="quantityModalVisible"
          class="modal"
          tabindex="-1"
          role="dialog"
          style="display: block"
        >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Quantity</h5>
                <button
                  type="button"
                  class="close"
                  @click="closeQuantityModal"
                  aria-label="Close"
                >
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
                <button
                  type="button"
                  class="btn btn-custom"
                  @click="addQuantity"
                >
                  Add
                </button>
                <button
                  type="button"
                  class="btn btn-custom "  style="background:#0F172B; border:none; "
                  @click="closeQuantityModal"
                >
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
           

              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-custom "  style="background:#0F172B; border:none; "
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
<!--shoppee kain muna -->
<div class="row">
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mdi mdi-desktop-mac">Rooms</h2>
          <button type="button" class="btn btn-custom" @click="openAddRoomModal">
            Add Room
          </button>
        </div>
        <div class="card-body pt-26">
          <div class="table-responsive">
            <table class="table table-hover table-product" >
              <thead>
                <tr>
                  <th>Room ID</th>
                  <th>Image</th>
                  <th>Room Name</th>
                  <th>Price</th>
                  <th>Bed</th>
                  <th>Bath</th>
                  <th>Number of Packs</th>
                  <th>Description</th>
                  <th>Room Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="room in room" :key="room.room_id">
                  <td>{{ room.room_id }}</td>
                  <td>{{ room.image }}</td>
                  <td>{{ room.room_name }}</td>
                  <td>{{ room.price }}</td>
                  <td>{{ room.bed }}</td>
                  <td>{{ room.bath }}</td>
                  <td>{{ room.packs }}</td>
                  <td>{{ room.description }}</td>
                  <td>{{ room.room_status }}</td>
                  <td>
                    <button class="edit-btn" @click="openRoomEditModal(room)">Edit</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
  </div>
  <!-- Edit Room Modal -->
  <div v-if="editRoomModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Room</h5>
          <button type="button" class="close" @click="closeRoomEditModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Edit form for rooms -->
          <form @submit.prevent="saveRoomEdit">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="room_name">Room Name</label>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Name"
                    v-model="editedRoom.room_name"
                  />
                </div>
                <div class="form-group">
                  <label for="price">Room Price</label>
                  <div class="input-group">
                    <input
                      type="number"
                      class="form-control"
                      placeholder="Price"
                      v-model="editedRoom.price"
                    />
                  </div>
                </div>
                <div class="form-group">
                  <label for="bed">Number of Beds</label>
                  <input
                    type="number"
                    class="form-control"
                    placeholder="Beds"
                    v-model="editedRoom.bed"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="bath">Number of Baths</label>
                  <input
                    type="number"
                    class="form-control"
                    placeholder="Baths"
                    v-model="editedRoom.bath"
                  />
                </div>
                <div class="form-group">
                  <label for="description">Room Description</label>
                  <textarea
                    class="form-control"
                    placeholder="Description"
                    v-model="editedRoom.description"
                  ></textarea>
                </div>
                <div class="form-group">
                  <label for="room_status">Room Status</label>
                  <select class="form-control" v-model="editedRoom.room_status">
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-custom "  style="background:#0F172B; border:none; " @click="closeRoomEditModal">
                Close
              </button>
              <button type="submit" class="btn btn-custom">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Add Room Modal -->
  <div v-if="addRoomModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Room</h5>
          <button type="button" class="close" @click="closeAddRoomModal">
            &times;
          </button>
        </div>
        <form @submit.prevent="saveRoom">
          <div class="modal-body">
            <div class="form-group">
              <label for="image">Room Images</label>
              <input
                type="file"
                ref="roomImageInput"
                @change="handleRoomImageUpload"
                class="form-control-file"
                multiple
                required
              />
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="room_name">Room Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Name"
                  v-model="room_name"
                  required
                />
              </div>
              <div class="form-group col-md-6">
                <label for="price">Room Price</label>
                <input
                  type="number"
                  class="form-control"
                  placeholder="Price"
                  v-model="price"
                  required
                />
              </div>
              <div class="form-group col-md-6">
                <label for="downpayment">Room Downpayment</label>
                <input
                  type="number"
                  class="form-control"
                  placeholder="Downpayment"
                  v-model="downpayment"
                  required
                />
              </div>
              <div class="form-group col-md-6">
                <label for="packs">Number of Pax</label>
                <select class="form-control" v-model="packs">
                  <option value="3">3 Pax</option>
                  <option value="4">4 Pax</option>
                  <option value="6">6 Pax</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="bed">Number of Beds</label>
                <input
                  type="number"
                  class="form-control"
                  placeholder="Beds"
                  v-model="bed"
                  required
                />
              </div>
              <div class="form-group col-md-6">
                <label for="bath">Number of Baths</label>
                <input
                  type="number"
                  class="form-control"
                  placeholder="Baths"
                  v-model="bath"
                  required
                />
              </div>
            </div>
            <div class="form-group">
              <label for="description">Room Description</label>
              <textarea
                class="form-control"
                placeholder="Description"
                v-model="description"
                required
              ></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-custom "  style="background:#0F172B; border:none; " @click="closeAddRoomModal">
              Close
            </button>
            <button type="submit" class="btn btn-custom">Add</button>
          </div>
        </form>
      </div>
    </div>
  </div>
 <!-- Menu Table -->
 <div class="row">
 <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Menu Management</h2>
          <button
            type="button"
            class="btn btn-primary"
            @click="openAddModal"
          >
            Add Menu Item
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table
              class="table table-hover table-product"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th>Item ID</th>
                  <th>Item Name</th>
                  <th>Category</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in menuItems" :key="item.menu_id">
                  <td>{{ item.item_id }}</td>
                  <td>{{ item.item_name }}</td>
                  <td>{{ item.item_category }}</td>
                  <td>{{ item.item_description }}</td>
                  <td>{{ item.item_price }}</td>
                  <td>
                    <button @click="openEditModal(item)">Edit</button>
                    <button @click="deleteItem(item.menu_id)">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- Add Modal -->
    <div v-if="addModalVisible" class="modal fade show" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Menu Item</h5>
            <button type="button" class="close" @click="closeAddModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="addMenuItem">
              <div class="form-group">
                <label for="itemName">Item Name</label>
                <input type="text" id="itemName" class="form-control" v-model="newItem.item_name" required />
              </div>
              <div class="form-group">
                <label for="itemCategory">Category</label>
                <input type="text" id="itemCategory" class="form-control" v-model="newItem.item_category" required />
              </div>
              <div class="form-group">
                <label for="itemDescription">Description</label>
                <textarea id="itemDescription" class="form-control" v-model="newItem.item_description" required></textarea>
              </div>
              <div class="form-group">
                <label for="itemPrice">Price</label>
                <input type="number" id="itemPrice" class="form-control" v-model="newItem.item_price" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeAddModal">Close</button>
                <button type="submit" class="btn btn-primary">Add Item</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="editModalVisible" class="modal fade show" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Menu Item</h5>
            <button type="button" class="close" @click="closeEditModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateMenuItem">
              <div class="form-group">
                <label for="editItemName">Item Name</label>
                <input type="text" id="editItemName" class="form-control" v-model="currentItem.item_name" required />
              </div>
              <div class="form-group">
                <label for="editItemCategory">Category</label>
                <input type="text" id="editItemCategory" class="form-control" v-model="currentItem.item_category" required />
              </div>
              <div class="form-group">
                <label for="editItemDescription">Description</label>
                <textarea id="editItemDescription" class="form-control" v-model="currentItem.item_description" required></textarea>
              </div>
              <div class="form-group">
                <label for="editItemPrice">Price</label>
                <input type="number" id="editItemPrice" class="form-control" v-model="currentItem.item_price" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeEditModal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
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

    <div class="row">
      <!-- Table Product -->
      <div class="col-12">
        <div class="card card-default">
          <div class="card-header">
            <h2>Booking Inventory</h2>
          </div>
          <div class="card-body">
            <div class="table-responsive">
            <table
              id="productsTable"
              class="table table-hover table-product"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th>Booking Id</th>
                  <th>User</th>
                  <th>Checkin</th>
                  <th>Checkout</th>
                  <th>Adult</th>
                  <th>Child</th>
                  <th>Special Request</th>
                  <th>Room</th>
                  <th>Booking Status</th>
                  <th>Booking Payment</th>
                  <th>Down Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="book in book">
                  <td>{{ book.book_id }}</td>
                  <td>{{ book.name }}</td>
                  <td>{{ book.checkin }}</td>
                  <td>{{ book.checkout }}</td>
                  <td>{{ book.adult }}</td>
                  <td>{{ book.child }}</td>
                  <td>{{ book.specialRequest }}</td>
                  <td>{{ book.room_name }}</td>
                  <td>{{ book.booking_status }}</td>
                  <td>{{ book.payment_method }}</td>
                  <td>{{ book.downpayment }}</td>
                  <td>
                    <div class="dropdown">
                      <button
                        class="btn btn-custom dropdown-toggle"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        Actions
                      </button>
                      <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                      >
                        <a
                          class="dropdown-item"
                          @click="markAsPaid(book.book_id)"
                          >Paid</a
                        >
                        <a
                          class="dropdown-item"
                          @click="acceptBooking(book.book_id)"
                          >Confirm</a
                        >
                        <a
                          class="dropdown-item"
                          @click="declineBooking(book.book_id)"
                          >Decline Booking</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Quantity Modal -->

            <!-- Success Message -->
            <div
              v-if="successMessage"
              class="alert alert-success"
              role="alert"
            >
              {{ successMessage }}
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
    <!-- Table Product -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Swimming Inventory</h2>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="productsTable" class="table table-hover table-product" style="width: 100%">
              <thead>
                <tr>
                  <th>Enroll Id</th>
                  <th>Account Id</th>
                  <th>FullName</th>
                  <th>Age</th>
                  <th>Contact Number</th>
                  <th>Experience</th>
                  <th>Lesson Date</th>
                  <th>Status</th>
                  <th class="actions-header">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="enroll in enroll" :key="enroll.enroll_id">
                  <td>{{ enroll.enroll_id }}</td>
                  <td>{{ enroll.id }}</td>
                  <td>{{ enroll.fullname }}</td>
                  <td>{{ enroll.age }}</td>
                  <td>{{ enroll.contact_number }}</td>
                  <td>{{ enroll.experience }}</td>
                  <td>{{ enroll.lesson_date }}</td>
                  <td>{{ enroll.enrollment_status }}</td>
                  <td>
                    <div class="dropdown">
                      <div class="select" @click="toggleDropdown(enroll.enroll_id)">
                        <span class="selected">Actions</span>
                        <div class="caret" :class="{ 'caret-rotate': openDropdown === enroll.enroll_id }"></div>
                      </div>
                      <ul class="menu" :class="{ 'menu-open': openDropdown === enroll.enroll_id }">
                        <li @click="handleDropdownAction('Paid', enroll.enroll_id)">Paid</li>
                        <li @click="handleDropdownAction('Accept', enroll.enroll_id)">Accept</li>
                        <li @click="handleDropdownAction('Decline', enroll.enroll_id)">Decline</li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Success Message -->
            <div v-if="successMessage" class="alert alert-success" role="alert">
              {{ successMessage }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


    <div class="row">
      <!-- Table Product -->
      <div class="col-12">
        <div class="card card-default">
          <div class="card-header">
            <h2>Orders Inventory</h2>
          </div>
          <div class="card-body">
            <div class="table-responsive">
            <table
              id="ordersTable"
              class="table table-hover table-product"
              style="width: 100%"
            >
              <!-- Table headers -->
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>User</th>
                  <th>Total Price</th>
                  <th>Shop</th>
                  <th>Quantity</th>
                  <th>status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- Loop through orders and display rows -->
                <tr v-for="order in orders" :key="order.order_id">
                  <td>{{ order.order_id }}</td>
                  <td>{{ order.user_name }}</td>
                  <td>{{ order.final_price }}</td>
                  <td>{{ order.prod_name }}</td>
                  <td>{{ order.quantity }}</td>
                  <td>{{ order.order_status }}</td>

                  <td>
                    <div class="dropdown">
                      <div class="select" @click="toggleDropdown(order.order_id)">
                        <span class="selected">Actions</span>
                        <div class="caret" :class="{ 'caret-rotate': openDropdown === order.order_id }"></div>
                      </div>
                      <ul class="menu" :class="{ 'menu-open': openDropdown === order.order_id }">
                        <li @click="handleDropdownAction('markOrderPaid', order.order_id)">Mark as Paid</li>
                        <li @click="handleDropdownAction('confirmOrder', order.order_id)">Confirm Order</li>
                        <li @click="handleDropdownAction('declineOrder', order.order_id)">Decline Order</li>
                      </ul>
                    </div>
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
            <div
              v-if="successMessage"
              class="alert alert-success mt-3"
              role="alert"
            >
              {{ successMessage }}
          </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Table Date -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Swimming Lesson Date Inventory</h2>
          <button type="button" class="btn btn-custom" @click="openAddDateModal">
            Add Date
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="productsTable" class="table table-hover table-product" style="width: 100%">
              <thead>
                <tr>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="date in dates" :key="date">
                  <td>{{ date }}</td>
                </tr>
              </tbody>
            </table>
            <!-- Success Message -->
            <div v-if="successMessage" class="alert alert-success" role="alert">
              {{ successMessage }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Input Date -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Swimming Lesson Date Inventory</h2>
          <button type="button" class="btn btn-custom" @click="openAddDateModal">
            Add Date
          </button>
        </div>
        <div class="card-body">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Select Date" ref="datepicker" />
          </div>
          <!-- Success Message -->
          <div v-if="successMessage" class="alert alert-success" role="alert">
            {{ successMessage }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Adding -->
  <div v-if="addDateModalVisible" class="modal show">
    <!-- Modal content for adding -->
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Date</h5>
          <button type="button" class="close" @click="closeAddDateModal">
            &times;
          </button>
        </div>
        <!-- Add form -->
        <form @submit.prevent="saveDate" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label for="swimming_date">Swimming Date</label>
              <input type="date" class="form-control" placeholder="Date" v-model="swimming_date" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-custom "  style="background:#0F172B; border:none; " @click="closeAddDateModal">Close</button>
            <button type="submit" class="btn btn-custom">Add</button>
          </div>
        </form>
      </div>
    </div>
  </div>

    <Notification
      :show="notification.show"
      :type="notification.type"
      :message="notification.message"
      class="bottom-right-notification"
    />
  </div>
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
name: 'admin2',
components: {
  Notification,
},
data() {
  return {
    menuItems: [],
      addModalVisible: false,
      editModalVisible: false,
      newItem: {
        item_name: '',
        item_category: '',
        item_description: '',
        item_price: ''
      },
      currentItem: {},
      successMessage: '',
    addDateModalVisible: false,
    successMessage: '',
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
    infos: [],
    shop: [],
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
    bed: "",
    bath: "",
    packs: "3", // Default to 3 packs
    description: "",
    downpayment: "",
    room_img: "",
    ConfirmationVisible: false,
    openDropdown: null, 
    successMessage: '', 
  };
},
mounted() {
  this.fetchMenuItems();

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
  this.getbook();
  this.getenroll();
  this.getDate();
  this.fetchOrdersForAllUsers();
},

methods: {
  async fetchMenuItems() {
      try {
        const response = await axios.get('/api/menu');
        this.menuItems = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    openAddModal() {
      this.addModalVisible = true;
    },
    closeAddModal() {
      this.addModalVisible = false;
    },
    async addMenuItem() {
      try {
        await axios.post('/api/menu', this.newItem);
        this.successMessage = 'Item added successfully!';
        this.closeAddModal();
        this.fetchMenuItems();
      } catch (error) {
        console.error(error);
      }
    },
    openEditModal(item) {
      this.currentItem = { ...item };
      this.editModalVisible = true;
    },
    closeEditModal() {
      this.editModalVisible = false;
    },
    async updateMenuItem() {
      try {
        await axios.put(`/api/menu/${this.currentItem.menu_id}`, this.currentItem);
        this.successMessage = 'Item updated successfully!';
        this.closeEditModal();
        this.fetchMenuItems();
      } catch (error) {
        console.error(error);
      }
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
    // Implement this method
    this.addDateModalVisible = true;
  },
  closeAddDateModal() {
    // Implement this method
    this.addDateModalVisible = false;
  },
  openDatePicker() {
    // Open the flatpickr calendar when the input field is clicked
    this.flatpickrInstance.open();
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
  async declineOrder(orderId) {
    try {
      const response = await axios.post(`/api/decline-order/${orderId}`);

      if (response.status === 200) {
        this.showSuccessNotification(`Order ${orderId} Declined`);
        this.fetchOrdersForAllUsers();
        this.getInfo();

        this.$emit("data-saved");
      } else {
        console.error("Failed to decline order:", response.data.message);
      }
    } catch (error) {
      console.error("Error declining order:", error);
    }
  },

  async declineBooking(booking_id) {
    try {
      const response = await axios.post(`/api/decline-booking/${booking_id}`);

      if (response.status === 200) {
        this.showSuccessNotification(`Booking ${booking_id} Declined`);
        this.getbook();
        this.getRoom();

        this.$emit("data-saved");
      } else {
        console.error("Failed to decline booking:", response.data.message);
      }
    } catch (error) {
      console.error("Error declining booking:", error);
    }
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
      .get(`/api/user-orders/`)
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
    axios
      .post(`/api/mark-order-paid/${orderId}`)
      .then((response) => {
        console.log(response.data);
        this.fetchOrdersForAllUsers();
      })
      .catch((error) => {
        console.error("Error marking order as paid:", error);
      });
  },

  confirmOrder(orderId) {
    axios
      .post(`/api/confirm-order/${orderId}`)
      .then((response) => {
        console.log(response.data);
        this.fetchOrdersForAllUsers();
      })
      .catch((error) => {
        console.error("Error confirming order:", error);
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
        bed: this.editedRoom.bed,
        bath: this.editedRoom.bath,
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

      data.append("bed", this.bed);
      data.append("bath", this.bath);
      data.append("description", this.description);
      this.showSuccessNotification("Room Added Successfully");

      const ins = await axios.post("saveRoom", data);

      this.closeAddRoomModal();
      this.room_img = "";
      this.room_name = "";
      this.price = "";
      this.downpayment = "";
      this.packs = "";

      this.bed = "";
      this.bath = "";
      this.description = "";

      this.getRoom();
      this.getbook();
    } catch (error) {
      console.error(error);
    }
  },

  async markAsPaid(booking_id) {
    try {
      const response = await axios.post(`/mark-as-paid/${booking_id}`);

      if (response.status === 200) {
        this.showSuccessNotification(`Booking ${booking_id} Marked as Paid`);
        this.getbook();

        this.$emit("data-saved");
      } else {
        console.error("Failed to mark as paid:", response.data.message);
      }
    } catch (error) {
      console.error("Error marking as paid:", error);
    }
  },
  async markthisPaid(enroll_id) {
    try {
      const response = await axios.post(`/mark-this-paid/${enroll_id}`);

      if (response.status === 200) {
        this.showSuccessNotification(
          `enrollment ${enroll_id} Marked this Paid`
        );
        this.getbook();

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
    handleDropdownAction(action, book_id) {

      if (action === 'markAsPaid') {
        this.markAsPaid(book_id);
      } else if (action === 'acceptBooking') {
        this.acceptBooking(book_id);
      } else if (action === 'declineBooking') {
        this.declinBooking(book_id);
      }
      this.openDropdown = null; 
    },
    showSuccessNotification(message) {
      this.successMessage = message;
      setTimeout(() => {
        this.successMessage = '';
      }, 3000);
    },
  async acceptBooking(booking_id) {
    try {
      const response = await axios.post(`/accept-booking/${booking_id}`);

      if (response.status === 200) {
        const updatedBookingId = response.data.booking_id;
        this.showSuccessNotification(`Booking ${updatedBookingId} Accepted`);
        this.getbook();
        this.$emit("data-saved");
      } else {
        console.error("Failed to accept booking:", response.data.message);
      }
    } catch (error) {
      console.error("Error accepting booking:", error);
    }
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

  async getbook() {
    const b = await axios.get("/getbook");
    this.book = b.data;
  },
  async getRoom() {
    const r = await axios.get("/getRoom");
    this.room = r.data;
    this.getbook();
  },
  async getenroll() {
    const b = await axios.get("/getenroll");
    this.enroll = b.data;
  },

  async getDate() {
    try {
      const response = await axios.get("/getDate");

      // Check if response status is OK
      if (response && response.status === 200) {
        // Check if response data is valid
        if (response.data && Array.isArray(response.data)) {
          // Assuming the dates are in the correct format
          this.dates = response.data;
          this.initFlatpickr();
        } else {
          console.error(
            "Error fetching dates: Response data is empty or not an array"
          );
        }
      } else {
        console.error("Error fetching dates: Invalid response status");
      }
    } catch (error) {
      console.error("Error fetching dates:", error);
      // Handle error if needed
    }
  },
  initFlatpickr() {
    this.flatpickrInstance = flatpickr(this.$refs.datepicker, {
      dateFormat: 'Y-m-d',
      enable: this.dates,
      onClose: selectedDates => {
        this.selectedDate = selectedDates[0];
      },
      onReady: (selectedDates, dateStr, instance) => {
        selectedDates.forEach(date => {
          const dateElem = instance.days.querySelector(`[data-date="${date}"]`);
          if (dateElem) {
            dateElem.classList.add('custom-selected-date');
          }
        });
      }
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
    } catch (error) {
      console.log(error);
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

  async saveDate() {
    try {
      const formData = new FormData();
      formData.append('swimming_date', this.swimming_date);

      const response = await axios.post('/saveDate', formData);

      if (response.status === 200) {
        this.successMessage2 = 'Date added successfully';
        this.closeAddDateModal();
        this.swimming_date = '';
        this.getDate();
        setTimeout(() => {
          this.successMessage2 = '';
        }, 3000);
      } else {
        console.error('Failed to add date:', response.data);
        this.successMessage2 = 'Failed to add date';
      }
    } catch (error) {
      console.error('Error saving date:', error);
      this.successMessage2 = 'Error saving date';
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
      const data = {
        prod_name: this.editedInfo.prod_name,
        prod_desc: this.editedInfo.prod_desc,
        prod_price: this.editedInfo.prod_price,
      };

      const apiUrl = `/updateShop/${this.editedInfo.shop_id}`;
      const response = await axios.put(apiUrl, data);

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

    this.editedInfo.newImage = file;
  },
},
};
</script>



<style scoped>
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

.table-product {
  width: 100%;
  border-collapse: collapse;
}

.table-product th, .table-product td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.card-body .modal-body, .card-body .alert, th , .modal-body {
  font-family: "Edu AU VIC WA NT Hand", cursive;
  font-weight: bold; 
  font-size: 17px; 
  
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

.table-product tbody td.action-buttons button,
.table-product tbody td.action-buttons a, td {
  font-family: "Edu AU VIC WA NT Hand", cursive;
  margin-top:20px;
  margin-bottom:20px;
}
.table-product tbody td.action-buttons {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  gap: 10px;
  white-space: nowrap;
  padding: 0; /* Remove padding */
  margin: 0; /* Remove margin */
}
.table-product tbody td {
  padding: 12px; /* Ensure padding is consistent */
  margin: 0; /* Ensure margin is consistent */

}

.table-product tbody tr {
  margin: 0;
  padding: 0;
 
}
.table-product tbody tr:hover {
  background-color: #ff9933; /* Change background color on hover */
}
/* Header alignment for actions column */
.actions-header {
  text-align: center;
  
}

.table-product td {
  vertical-align: middle; /* Align the "Actions" button vertically */
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
  font-family: "Edu AU VIC WA NT Hand", cursive;
padding: 10px;  

}
.edit-btn{
  padding: 12px;
  font-size: 15px;
  width: 65px;
  font-family: "Edu AU VIC WA NT Hand", cursive;
 
}
.btn:hover {
  background-color: #0F172B;
  transform: scale(1.10);
  border:none;
}
.edit-btn:hover{
  background-color: #0F172B;
  transform: scale(1.10);
  border:none;
}


</style>