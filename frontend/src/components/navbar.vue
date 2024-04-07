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
            <div
              class="collapse navbar-collapse justify-content-between"
              id="navbarCollapse"
            >
              <div class="navbar-nav mr-auto py-0">
                <router-link
                  to="/user"
                  class="nav-item nav-link"
                  :class="{ active: $route.path === '/user' }"
                  >Home</router-link
                >
                <router-link
                  to="/about"
                  class="nav-item nav-link"
                  :class="{ active: $route.path === '/about' }"
                  >About</router-link
                >
                <router-link
                  to="/service"
                  class="nav-item nav-link"
                  :class="{ active: $route.path === '/service' }"
                  >Services</router-link
                >
                <router-link
                  to="/shop"
                  class="nav-item nav-link"
                  :class="{ active: $route.path === '/shop' }"
                  >Shop</router-link
                >

                <button
                  type="button"
                  class="btn btn-link"
                  @click="showPackSelectionModal"
                >
                  Select Packs
                </button>

                <div
                  class="modal"
                  v-if="packSelectionModalVisible"
                  tabindex="-1"
                  role="dialog"
                  style="display: block"
                >
                  <div
                    class="modal-dialog modal-dialog-centered"
                    role="document"
                  >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Select Number of Packs</h5>
                        <button
                          type="button"
                          class="close"
                          @click="closePackSelectionModal"
                        >
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form @submit.prevent="savePackSelectionAndRedirect">
                          <div class="form-group">
                            <label for="packs">Number of Packs</label>
                            <input
                              type="number"
                              v-model="packs"
                              class="form-control"
                              id="packs"
                            />
                          </div>
                          <button type="submit" class="btn btn-primary">
                            Save
                          </button>
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
        <!-- Button to book selected rooms -->
      </div>
    </div>
  </div>
</div>

                <div class="nav-item dropdown">
                  <a
                    href="#"
                    class="nav-link dropdown-toggle"
                    data-toggle="dropdown"
                    data-target="#dropdownMenu"
                    >Pages</a
                  >
                  <div class="dropdown-menu rounded-0 m-0" id="dropdownMenu">
                    <a href="/team" class="dropdown-item">Our Team</a>
                    <a href="/testimonial" class="dropdown-item">Testimonial</a>
                  </div>
                </div>

                <router-link
                  to="/contact"
                  class="nav-item nav-link"
                  :class="{ active: $route.path === '/contact' }"
                  >Contact</router-link
                >
                <div
                  class="col-lg-5 px-5 text-end d-flex align-items-center justify-content-end"
                >
                  <div class="nav-item dropdown">
                    <a
                      href="#"
                      class="nav-link dropdown-toggle fs-6"
                      data-bs-toggle="dropdown"
                    >
                      <i class="fas fa-bell"></i>
                      <span
                        class="badge rounded-pill badge-notification bg-danger small-badge"
                        >{{ notifications.length }}</span
                      >
                    </a>
                    <div class="dropdown-menu bg-light m-0">
                      <template v-if="notifications.length">
                        <div
                          v-for="(notification, index) in notifications"
                          :key="index"
                        >
                          <a class="dropdown-item" :href="notification.link">{{
                            notification.message
                          }}</a>
                        </div>
                      </template>
                      <template v-else>
                        <p class="dropdown-item">No new notifications</p>
                      </template>
                    </div>
                  </div>

                  <router-link to="/shopcart" class="text-primary me-3">
                    <i class="fa fa-shopping-cart"></i>
                  </router-link>
                  <button
                    @click="logout"
                    class="btn btn-primary logout-logo-btn"
                  >
                    <i class="fas fa-power-off logout-icon"></i>
                    Logout
                  </button>
                </div>
              </div>
            </div>
          </nav>
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
</template>

<script>
import Top from "@/components/Top.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import axios from "axios";

export default {
  components: {
    Top,
    FontAwesomeIcon,
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
      
    };
  },
  methods: {
    // Method to close the room modal
    closeRoomModal() {
      this.roomModalVisible = false;
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
  mounted() {
    this.fetchNotifications(); // Fetch notifications when the component is mounted
    document.addEventListener("click", this.closeDataList);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.closeDataList);
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
</style>
