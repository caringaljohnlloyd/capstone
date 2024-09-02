<<template>
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
    <a href="#home"><i class="fa-solid fa-chart-simple"></i><span>Business Dashboard</span></a>
    <a href="#services"><i class="fa-solid fa-chart-line"></i><span>Analytics Board</span></a>
    <a href="#clients"><i class="fa-solid fa-people-group"></i><span>Team</span></a>
    <a href="#contact"><i class="fas fa-user"></i><span>Users</span></a>
    <a href="#contact"><i class="fa-solid fa-table-columns"></i><span>POS</span></a>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>Dashboard</h1>
      <button @click="logout" class="btn btn-primary logout-logo-btn">
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
              class="btn btn-primary"
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
                  <td>
                    <button @click="openQuantityModal(info)">
                      Add Quantity
                    </button>
                    |
                    <router-link
                      style="color: black"
                      :to="{
                        name: 'auditHistory',
                        params: { shopId: info.shop_id },
                      }"
                      >Audit History</router-link
                    >
                    |
                    <button @click="openEditModal(info)">Edit</button>
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
                    <!-- Form groups for modal body -->
                    <div class="form-group">
                      <label for="productName" class="label"
                        >Product Name:</label
                      >
                      <p id="productName">{{ selectedProduct.prod_name }}</p>
                    </div>
                    <div class="form-group">
                      <label for="currentQuantity" class="label"
                        >Current Quantity:</label
                      >
                      <p id="currentQuantity">
                        {{ selectedProduct.prod_quantity }}
                      </p>
                    </div>
                    <div class="form-group">
                      <label for="addQuantity" class="label"
                        >Add Quantity:</label
                      >
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
                      class="btn btn-primary"
                      @click="addQuantity"
                    >
                      Add
                    </button>
                    <button
                      type="button"
                      class="btn btn-secondary"
                      @click="closeQuantityModal"
                    >
                      Close
                    </button>
                  </div>
                </div>
              </div>
            </div>
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
              <!-- Add other fields as needed -->

              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeEditModal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">
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
    <!-- Modal for Adding -->
    <div class="col-12">
      <div class="modal" :class="{ show: addModalVisible }">
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
            <form
              @submit.prevent="saveShop('add')"
              enctype="multipart/form-data"
            >
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
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeAddModal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">Add</button>
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
            <button
              type="button"
              class="btn btn-primary"
              @click="openAddRoomModal"
            >
              Add Room
            </button>
          </div>
          <div class="card-body pt-26">
            <div class="table-responsive">
            <table class="table">
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
                <tr v-for="room in room">
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
                    <button @click="openRoomEditModal(room)">Edit</button>
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
    <div
      v-if="editRoomModalVisible"
      class="modal"
      tabindex="-1"
      role="dialog"
      style="display: block"
    >
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
                  <!-- <div class="form-group">
      <label for="downpayment">Room Downpayment</label>
      <div class="input-group">
       
        <input type="number" class="form-control" placeholder="Downpayment" v-model="editedRoom.downpayment">
      </div>
    </div> -->
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
                    <select
                      class="form-control"
                      v-model="editedRoom.room_status"
                    >
                      <option value="Available">Available</option>
                      <option value="Booked">Booked</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeRoomEditModal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">
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

    <div class="col-12">
      <div class="modal" :class="{ show: addRoomModalVisible }">
        <div class="modal-dialog modal-lg">
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
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeAddRoomModal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </form>
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
                        class="btn btn-secondary dropdown-toggle"
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
            <table
              id="productsTable"
              class="table table-hover table-product"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th>Enroll Id</th>
                  <th>Account Id</th>
                  <th>FullName</th>
                  <th>Age</th>
                  <th>Contact Number</th>
                  <th>Experience</th>
                  <th>Lesson Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="enroll in enroll">
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
                      <button
                        class="btn btn-secondary dropdown-toggle"
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
                          @click="markthisPaid(enroll.enroll_id)"
                          >Paid</a
                        >
                        <a
                          class="dropdown-item"
                          @click="acceptenrolling(enroll.enroll_id)"
                          >Confirm</a
                        >
                        <a
                          class="dropdown-item"
                          @click="declineenrolling(enroll.enroll_id)"
                          >Decline Enroll</a
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
                      <!-- Dropdown actions -->
                      <button
                        class="btn btn-secondary dropdown-toggle"
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
                          @click="markOrderPaid(order.order_id)"
                          >Mark as Paid</a
                        >
                        <a
                          class="dropdown-item"
                          @click="confirmOrder(order.order_id)"
                          >Confirm Order</a
                        >
                        <a
                          class="dropdown-item"
                          @click="declineOrder(order.order_id)"
                          >Decline Order</a
                        >
                      </div>
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
          <button
            type="button"
            class="btn btn-primary"
            @click="openAddDateModal"
          >
            Add Date
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

  <!-- <div class="row">
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <h2>Swimming Lesson Date Inventory</h2>
          <button
type="button"
class="btn btn-primary"
@click="openAddDateModal"
>
Add Date
</button>
        </div>
        <div class="card-body">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Select Date"
              ref="datepicker"
            />
          </div>
          <div v-if="successMessage" class="alert alert-success" role="alert">
            {{ successMessage }}
          </div>
        </div>
      </div>
    </div>
  </div> -->

<!-- Modal for Adding -->
<div class="modal" :class="{ show: addDateModalVisible }">
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
          <input
            type="date"
            class="form-control"
            placeholder="Date"
            v-model="swimming_date"
            required
          />
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          @click="closeAddDateModal"
        >
          Close
        </button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
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
      class="bottom-right-notification"
    />
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
   
    addDateModalVisible: false,

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
    

  };
},
mounted() {

  // Fetch dates from the server
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
 
    //hanggang dito
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
    this.showSuccessNotification("Product Added Successfully");
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
        // Use FormData for image uploads
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
  openAddModal() {
    this.addModalVisible = true;
  },
  closeAddModal() {
    this.addModalVisible = false;
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

.sidebar a {
display: flex;
align-items: center;
color: #fff;
padding: 10px;
text-decoration: none;
margin: 5px 0;
}

.sidebar a:hover {
background-color: #FEA116;
transition: background-color 0.5s; /* Smooth transition for hover effect */
}

.sidebar:hover {
width: 200px;
}

.main-content {
margin-left: 93px; /* Default margin for the sidebar width */
padding: 0; /* Remove default padding */
transition: margin-left 0.5s; /* Smooth transition for the margin */
}

.sidebar:hover ~ .main-content {
margin-left: 200px; /* Adjust margin when sidebar is expanded */
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

@media (max-width: 768px) {
.sidebar {
  width: 100%;
  height: auto;
  position: relative;
}
.main-content {
  margin-left: 0;
}
.table-product th, .table-product td {
  padding: 8px;
}


.modal.show {
  display: block;
}










}
</style>
>