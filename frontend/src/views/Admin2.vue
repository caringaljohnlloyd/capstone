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

              </div>
              <div class="col-md-6">

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
  <div class="row">
    <!-- Table Inventory -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Table Inventory</h2>
          <button
            type="button"
            class="btn btn-custom"
            @click="openAddTableModal"
          >
            Add Table
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table
              id="tablesInventory"
              class="table table-hover"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th>Table Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(table, index) in tables" :key="table.table_id">
                  <td>{{ table.table_name }}</td>
                  <td>{{ table.table_description }}</td>
                  <td>{{ table.table_price }}</td>
                  <td class="action-buttons">
                    <button class="btn btn-custom" @click="openEditTableModal(table)">Edit</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Table Modal -->
    <div v-if="editTableModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Table</h5>
            <button type="button" class="close" @click="closeEditTableModal">&times;</button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateTable">
              <div class="form-group">
                <label for="edit_table_name">Table Name</label>
                <input type="text" class="form-control" v-model="editedTable.table_name" required />
              </div>
              <div class="form-group">
                <label for="edit_table_description">Description</label>
                <input type="text" class="form-control" v-model="editedTable.table_description" required />
              </div>
              <div class="form-group">
                <label for="edit_table_price">Price</label>
                <input type="number" class="form-control" v-model="editedTable.table_price" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom" @click="closeEditTableModal">Close</button>
                <button type="submit" class="btn btn-custom">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Table Modal -->
    <div v-if="addTableModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Table</h5>
            <button type="button" class="close" @click="closeAddTableModal">&times;</button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveTable">
              <div class="form-group">
                <label for="table_name">Table Name</label>
                <input type="text" class="form-control" v-model="table_name" required />
              </div>
              <div class="form-group">
                <label for="table_description">Description</label>
                <input type="text" class="form-control" v-model="table_description" required />
              </div>
              <div class="form-group">
                <label for="table_price">Price</label>
                <input type="number" class="form-control" v-model="table_price" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-custom" @click="closeAddTableModal">Close</button>
                <button type="submit" class="btn btn-custom">Add Table</button>
              </div>
            </form>
          </div>
        </div>
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
            @click="openAddItemModal"
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
                  <th>Item Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in menuItems" :key="item.menu_id">
                  <td>{{ item.item_name }}</td>
                  <td>{{ item.item_category }}</td>
                  <td>{{ item.item_price }}</td>
                  <td>
                    <button @click="openEditItemModal(item)" class="btn btn-primary" >Edit</button>
                    <!-- <button @click="deleteItem(item.menu_id)" class="btn btn-primary">Delete</button> -->
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
<div v-if="addModalItemVisible" class="modal fade show" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Menu Item</h5>
        <button type="button" class="close" @click="closeAddItemModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="addMenuItem">
          <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" id="item_name" class="form-control" v-model="item_name" required />
          </div>
          <div class="form-group">
            <label for="item_category">Category</label>
            <select id="item_category" class="form-control" v-model="item_category" required>
              <option value="" disabled>Select a category</option>
              <option value="breakfast">Breakfast</option>
              <option value="rice">Rice</option>
              <option value="noodles">Noodles</option>
              <option value="asian curry">Asian Curry</option>
              <option value="soup">Soup</option>
              <option value="refreshments">Refreshments</option>
              <option value="sizzling">Sizzling</option>
              <option value="filipino style">Filipino Style</option>
              <option value="vegetables">Vegetables</option>
            </select>
          </div>
          <div class="form-group">
            <label for="item_price">Price</label>
            <input type="number" id="item_price" class="form-control" v-model="item_price" required />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeAddItemModal">Close</button>
            <button type="submit" class="btn btn-primary">Add Item</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div v-if="successMessage" class="alert alert-success" role="alert">
    {{ successMessage }}
  </div>
</div>


<!-- Success Notification -->
<div v-if="successMessage" class="alert alert-success" role="alert">
  {{ successMessage }}
</div>

<!-- Error Notification -->
<div v-if="errorMessage" class="alert alert-danger" role="alert">
  {{ errorMessage }}
</div>

<!-- Edit Modal -->
<div v-if="editModalItemVisible" class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Menu Item</h5>
        <button type="button" class="close" @click="closeEditItemModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="updateMenuItem">
          <div class="form-group">
            <label for="editItem_name">Item Name</label>
            <input
              type="text"
              class="form-control"
              v-model="currentItem.item_name"
              required
            />
          </div>
          <div class="form-group">
  <label for="editItem_category">Category</label>
  <select
    id="editItem_category"
    class="form-control"
    v-model="currentItem.item_category"
    required
  >
    <option value="" disabled>Select a category</option>
    <option value="breakfast">Breakfast</option>
    <option value="rice">Rice</option>
    <option value="noodles">Noodles</option>
    <option value="asian curry">Asian Curry</option>
    <option value="soup">Soup</option>
    <option value="refreshments">Refreshments</option>
    <option value="sizzling">Sizzling</option>
    <option value="filipino style">Filipino Style</option>
    <option value="vegetables">Vegetables</option>
  </select>
</div>

          <div class="form-group">
            <label for="editItem_price">Price</label>
            <input
              type="number"
              class="form-control"
              v-model="currentItem.item_price"
              required
            />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeEditItemModal">Close</button>
            <button type="button" class="btn btn-primary" @click="updateMenuItem">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <!-- Cottage Inventory Table -->
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Cottage Inventory</h2>
          <button
            type="button"
            class="btn btn-custom"
            @click="openCottageAddModal"
          >
            Add Cottage
          </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table
              id="cottagesTable"
              class="table table-hover table-product"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th>Cottage Image</th>
                  <th>Cottage Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cottage, index) in cottages" :key="cottage.cottage_id">
                  <td>{{ cottage.cottage_image }}</td>
                  <td>{{ cottage.cottage_name }}</td>
                  <td>{{ cottage.cottage_description }}</td>
                  <td>{{ cottage.cottage_price }}</td>
                  <td class="action-buttons">
                    <button class="btn btn-custom" @click="openCottageEditModal(cottage)">Edit</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Add Cottage Modal -->
  <div v-if="addCottageModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Cottage</h5>
          <button
            type="button"
            class="close"
            @click="closeCottageAddModal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="cottageName" class="label">Cottage Name:</label>
            <input v-model="cottage_name" type="text" class="form-control" id="cottageName" />
          </div>
          <div class="form-group">
            <label for="cottageDescription" class="label">Description:</label>
            <input v-model="cottage_description" type="text" class="form-control" id="cottageDescription" />
          </div>
          <div class="form-group">
            <label for="cottagePrice" class="label">Price:</label>
            <input v-model="cottage_price" type="number" class="form-control" id="cottagePrice" />
          </div>
          <div class="form-group">
            <label for="cottageImage" class="label">Image:</label>
            <input type="file" ref="cottageImageInput" class="form-control" id="cottageImage" @change="handleCottageImageUpload" />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-custom"
            @click="saveCottage('add')"
          >
            Save
          </button>
          <button
            type="button"
            class="btn btn-custom"
            @click="closeCottageAddModal"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Cottage Modal -->
  <div v-if="editCottageModalVisible" class="modal" tabindex="-1" role="dialog" style="display: block">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Cottage</h5>
          <button
            type="button"
            class="close"
            @click="closeCottageEditModal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="editCottageName" class="label">Cottage Name:</label>
            <input v-model="editedCottage.cottage_name" type="text" class="form-control" id="editCottageName" />
          </div>
          <div class="form-group">
            <label for="editCottageDescription" class="label">Description:</label>
            <input v-model="editedCottage.cottage_description" type="text" class="form-control" id="editCottageDescription" />
          </div>
          <div class="form-group">
            <label for="editCottagePrice" class="label">Price:</label>
            <input v-model="editedCottage.cottage_price" type="number" class="form-control" id="editCottagePrice" />
          </div>
          <div class="form-group">
            <label for="editCottageImage" class="label">Change Image:</label>
            <input type="file" ref="editCottageImageInput" @change="handleEditCottageImageUpload" />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-custom"
            @click="saveCottageEdit"
          >
            Save Changes
          </button>
          <button
            type="button"
            class="btn btn-custom"
            @click="closeCottageEditModal"
          >
            Close
          </button>
        </div>
      </div>
    </div>
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
            <table id="productsTable" class="table table-hover table-product" style="width: 100%">
              <thead>
                <tr>
                  <th>Booking Id</th>
                  <th>User</th>
                  <th>Checkin</th>
                  <th>Checkout</th>
                  <th>Special Request</th>
                  <th>Room</th>
                  <th>Booking Status</th>
                  <th>Booking Payment</th>
                  <th>Down Payment</th>
                  <th>Proof of Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="book in book" :key="book.book_id">
                  <td>{{ book.book_id }}</td>
                  <td>{{ book.name }}</td>
                  <td>{{ book.checkin }}</td>
                  <td>{{ book.checkout }}</td>
                  <td>{{ book.specialRequest }}</td>
                  <td>{{ book.room_name }}</td>
                  <td>{{ book.booking_status }}</td>
                  <td>{{ book.payment_method }}</td>
                  <td>{{ book.downpayment }}</td>
                  <td>
                    <img
                      class="img-fluid menu"
                      style="width: 100%; max-width: 150px; height: auto;"
                      :src="`http://localhost:8080/uploads/${book.downpaymentProof}`"
                      alt="Downpayment Proof"
                    />
                  </td>
                  <td>
                    <div class="dropdown">
                      <div class="select" @click="toggleDropdown(book.book_id)">
                        <span class="selected">Actions</span>
                        <div class="caret" :class="{ 'caret-rotate': openDropdown === book.book_id }"></div>
                      </div>
                      <ul class="menu" :class="{ 'menu-open': openDropdown === book.book_id }">
                        <li @click="handleDropdownAction('markAsPaid', book.book_id)">Mark as Paid</li>
                        <li @click="handleDropdownAction('acceptBooking', book.book_id)">Confirm Booking</li>
                        <li @click="handleDropdownAction('declineBooking', book.book_id)">Decline Booking</li>
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
        <h2>Cottage Booking Inventory</h2>
      </div>
      <div class="card-body">
        <div class="table-responsive">
      <table id="cottageBookingTable" class="table table-hover table-product" style="width: 100%">
        <thead>
          <tr>
            <th>Booking Id</th>
            <th>User Id</th>
            <th>Checkin</th>
            <th>Checkout</th>
            <th>Booking Status</th>
            <th>Cottage Id</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in bookings" :key="booking.cottagebooking_id">
            <td>{{ booking.cottagebooking_id }}</td>
            <td>{{ booking.user_id }}</td>
            <td>{{ booking.selectedTime }}</td>
            <td>{{ booking.selectedTimeout }}</td>
            <td>{{ booking.cottagebooking_status }}</td>
            <td>{{ booking.cottage_id }}</td>
            <td>{{ booking.created_at }}</td>
            <td>
  <div class="btn-group" role="group" aria-label="Booking Actions">
    <button 
      @click="confirmCottageBooking(booking.cottagebooking_id)" 
      class="btn btn-primary"
    >
      Confirm
    </button>
    <button 
      @click="declineCottageBooking(booking.cottagebooking_id)" 
      class="btn btn-danger" 
      :disabled="booking.cottagebooking_status === 'confirmed'" 
      :class="{ 'disabled': booking.cottagebooking_status === 'confirmed' }"
    >
      Decline
    </button>
    <button 
      @click="markCottageBookingAsPaid(booking.cottagebooking_id)" 
      class="btn btn-primary"
    >
      Mark as Paid
    </button>
  </div>
</td>



          </tr>
        </tbody>
      </table>
    </div>
      </div>
    </div>
  </div>
</div>

        
        <div class="row">
  <!-- Reservation List -->
  <div class="col-12">
  <div class="card card-default">
    <div class="card-header">
      <h2>Reservations</h2>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="reservationsTable" class="table table-hover" style="width: 100%">
          <thead>
            <tr>
              <th>Reservation ID</th>
              <th>User</th>
              <th>Table</th>
              <th>Order Items</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(reservation, index) in reservations" :key="reservation.reservation_id">
              <td>{{ reservation.reservation_id }}</td>
              <td>{{ reservation.user.name }}</td>
              <td>{{ reservation.table.table_name }}</td>
              <td>
                <ul>
                  <li v-for="item in reservation.order_items" :key="item.id">
                    {{ item.item_name }} ({{ item.quantity }})
                  </li>
                </ul>
              </td>
              <td>{{ reservation.status }}</td>
              <td class="action-buttons">


                <!-- View Details Button -->
                <button class="btn btn-custom" @click="viewDetails(reservation)">
                  View Details
                </button>

                <!-- Accept Reservation Button -->
                <button class="btn btn-success" @click="acceptReservation(reservation.reservation_id)">
                  Accept
                </button>

                <!-- Decline Reservation Button -->
                <button class="btn btn-danger" @click="declineReservation(reservation.reservation_id)">
                  Decline
                </button>

                <!-- Mark as Paid Button -->
                <button class="btn btn-warning" @click="markReservationAsPaid(reservation.reservation_id)">
                  Mark as Paid
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Details Modal -->
      <div v-if="detailsModalVisible" class="modal fade show" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Reservation Details</h5>
              <button type="button" class="close" @click="closeDetailsModal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="reservationId">Reservation ID:</label>
                <p id="reservationId">{{ selectedReservation.reservation_id }}</p>
              </div>
              <div class="form-group">
                <label for="user">User:</label>
                <p id="user">{{ selectedReservation.user.name }}</p>
              </div>
              <div class="form-group">
                <label for="table">Table:</label>
                <p id="table">{{ selectedReservation.table.table_name }}</p>
              </div>
              <div class="form-group">
                <label for="orderItems">Order Items:</label>
                <ul>
                  <li v-for="item in selectedReservation.order_items" :key="item.id">
                    {{ item.item_name }} ({{ item.quantity }})
                  </li>
                </ul>
              </div>
              <div class="form-group">
                <label for="status">Status:</label>
                <p id="status">{{ selectedReservation.status }}</p>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-custom" @click="closeDetailsModal">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Success and Error Messages -->
      <div v-if="successMessage" class="alert alert-success" role="alert">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="alert alert-danger" role="alert">
        {{ errorMessage }}
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
            <div v-if="successMessageDate" class="alert alert-success" role="alert">
              {{ successMessageDate }}
            </div>
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
      table_price: '',         // New table price
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
    currentItem: {
        menu_id: null,
        item_name: '',
        item_category: '',
        item_price: ''
      },
      addModalItemVisible: false,
      editModalItemVisible: false,
      item_name: '',
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
          table_price: this.table_price,
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
          table_price: this.editedTable.table_price,
        };

        await axios.post(`/updateTable/${this.editedTable.table_id}`, data);
        this.closeEditTableModal();
        this.fetchTables();

        this.showNotification("Table updated successfully!", "success");
      } catch (error) {
        console.error('Error updating table:', error);
      }
    },

    showNotification(message, type) {
      this.notification.message = message;
      this.notification.type = type;
      this.notification.show = true;

      // Hide notification after 3 seconds
      setTimeout(() => {
        this.notification.show = false;
      }, 3000);
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
      this.table_price = '';
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
    const response = await axios.get('api/cottage-bookings'); // Update with your API endpoint
    this.bookings = response.data; // Assuming you have a `bookings` data property
  } catch (error) {
    console.error('Error fetching cottage bookings:', error);
    this.errorMessage = 'Failed to load cottage booking data.'; // Assuming you have an `errorMessage` property
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


// async updateReservationStatus() {
//   try {
//     const response = await axios.put(`/admin/reservations/updateStatus/${this.selectedReservation.reservation_id}`, {
//       status: this.statusToUpdate
//     });

//     if (response.status === 200) {
//       this.showSuccessNotification(`Reservation ${this.selectedReservation.reservation_id} status updated to ${this.statusToUpdate}`);
//       this.fetchReservations(); // Refresh the list
//       this.closeStatusModal();
//     } else {
//       console.error("Failed to update status:", response.data.message);
//       this.errorMessage = `Failed to update status: ${response.data.message}`;
//     }
//   } catch (error) {
//     console.error('Error updating status:', error);
//     this.errorMessage = 'Failed to update status. Please try again.';
//   }
// },
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
    async addMenuItem() {
  try {
    const response = await axios.post("http://localhost:8080/api/menu", {
      item_name: this.item_name,
      item_category: this.item_category,
      item_price: this.item_price
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
    if (error.response) {
      console.error("Server responded with a status:", error.response.status);
      console.error("Response data:", error.response.data);
      console.error("Response headers:", error.response.headers);
      this.showErrorNotification(`Server error: ${error.response.data.message}`);
    } else if (error.request) {
      console.error("No response received:", error.request);
      this.showErrorNotification("No response received from server.");
    } else {
      console.error("Error setting up request:", error.message);
      this.showErrorNotification("Error setting up request: " + error.message);
    }
  }
},

clearForm() {
  this.item_name = '';
  this.item_category = '';
  this.item_price = '';
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
  if (!item.menu_id) {
    console.error('Item ID is missing.');
    this.showErrorNotification('Item ID is missing.');
    return;
  }
  this.currentItem = { ...item };
  console.log('Selected item:', this.currentItem); // Check the contents of currentItem
  this.editModalItemVisible = true;
},
  closeEditItemModal() {
    this.editModalItemVisible = false;
  },
  async updateMenuItem() {
  try {
    // Prepare the data to be sent in the request
    const data = {
      item_name: this.currentItem.item_name,
      item_category: this.currentItem.item_category,
      item_price: this.currentItem.item_price,
    };

    // Construct the API URL
    const apiUrl = `/api/menu/${this.currentItem.menu_id}`;

    // Make an API call to update the menu item
    const response = await axios.put(apiUrl, data);

    // Log success and notify user
    console.log("Item updated successfully:", response.data);
    this.showSuccessNotification(response.data.message || 'Item updated successfully!');

    // Close the modal and refresh the menu items list
    this.closeEditItemModal();
    this.fetchMenuItems(); // Refresh the list of menu items

    // Emit an event to notify other components
    this.$emit("item-updated");
  } catch (error) {
    console.error("Error updating item:", error);
    if (error.response) {
      console.error("Server responded with a status:", error.response.status);
      console.error("Response data:", error.response.data);
      this.showErrorNotification(`Server error: ${error.response.data.message || 'Unknown server error'}`);
    } else if (error.request) {
      console.error("No response received:", error.request);
      this.showErrorNotification('No response received from server.');
    } else {
      console.error("Error setting up request:", error.message);
      this.showErrorNotification('Error setting up request: ' + error.message);
    }
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

  async getBook() {
    try {
      const response = await axios.get('/getbook');
      this.book = response.data;
    } catch (error) {
      console.error('Error fetching books:', error);
    }
  },
  async declineBooking(booking_id) {
    try {
      const response = await axios.post(`/api/decline-booking/${booking_id}`);

      if (response.status === 200) {
        this.showSuccessNotification(`Booking ${booking_id} Declined`);
        this.getBook(); // Ensure this method is defined
      } else {
        console.error("Failed to decline booking:", response.data.message);
      }
    } catch (error) {
      console.error("Error declining booking:", error);
    }
  },
  async markAsPaid(booking_id) {
    try {
      const response = await axios.post(`/mark-as-paid/${booking_id}`);

      if (response.status === 200) {
        this.showSuccessNotification(`Booking ${booking_id} Marked as Paid`);
        this.getBook(); // Ensure this method is defined
      } else {
        console.error("Failed to mark as paid:", response.data.message);
      }
    } catch (error) {
      console.error("Error marking as paid:", error);
    }
  },
  async acceptBooking(booking_id) {
    try {
      const response = await axios.post(`/accept-booking/${booking_id}`);

      if (response.status === 200) {
        this.showSuccessNotification(`Booking ${booking_id} Accepted`);
        this.getBook(); // Ensure this method is defined
      } else {
        console.error("Failed to accept booking:", response.data.message);
      }
    } catch (error) {
      console.error("Error accepting booking:", error);
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
    handleDropdownAction(action, book_id) {
      if (action === 'markAsPaid') {
        this.markAsPaid(book_id);
      } else if (action === 'acceptBooking') {
        this.acceptBooking(book_id);
      } else if (action === 'declineBooking') {
        this.declineBooking(book_id);
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
        const response = await axios.post("http://localhost:8080/saveDate", {
            swimming_date: this.swimming_date,
        });
        this.successMessageDate = response.data.message;
        this.dates.push(this.swimming_date); // Assuming you want to add it to the dates list
        this.closeAddDateModal(); // Close the modal
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
</style>