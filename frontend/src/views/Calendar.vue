<template>

<div class="main-content">

<div class="sidebar" :class="{ 'collapsed': isSidebarCollapsed }">

<img :src="require('../assets/images/logo1.png.png')" alt="Mono" class="logo">
<router-link to="/admin">
<i class="fa-solid fa-chart-simple"></i><span>Business Dashboard</span>
</router-link>
<router-link to="/analytics">
<i class="fa-solid fa-chart-line"></i><span>Analytics Board</span>
</router-link>
<router-link to="/teamadmin">
<i class="fa-solid fa-people-group"></i><span>Team</span>
</router-link>
<router-link to="/monitorusers">
<i class="fas fa-user"></i><span>Users</span>
</router-link>
<router-link to="/pos">
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
            <div class="col-12">
              <div class="card card-default">
                <div class="card-header">
                  <h2  class="fa-solid fa-user-check">    Current Users</h2><br>
                  <h4>Realtime</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <!-- Table Head -->
                      <thead>
                        <tr>
                          <th>User ID</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>Status</th>
                          <!-- Add more columns as needed -->
                        </tr>
                      </thead>
                      <!-- Table Body -->
                      <tbody>
                        <tr v-for="user in users" :key="user.id">
                          <td>{{ user.id }}</td>
                          <td>{{ user.name }}</td>
                          <td>{{ user.email }}</td>
                          <td>{{ user.status }}</td>
                          <!-- Add more cells as needed -->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer bg-white py-4">
                  <a href="#" class="text-uppercase">Current Users Overview</a>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
  </div>

</template>

<script>
import axios from 'axios';
import Notification from '@/components/Notification.vue';

export default {
  name: 'inventory',
  components: {

    Notification,
  },
  data() {
    return {
      isSidebarCollapsed: false,
      users: [],
    };
    
  },
  mounted() {
    this.fetchUserData();
  },
  created() {
    this.fetchUserData();
  },
  methods: {
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
    async fetchUserData() {
      try {
        const response = await axios.get('getData');
        this.users = response.data;
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
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
.tr td:hover{
  background-color: #f3f1eb;
  cursor: pointer;
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

.card-body .modal-body, .card-body .alert, th {
  font-family: "Edu AU VIC WA NT Hand", cursive;
  font-weight: bold; 
  font-size: 17px; 
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
  background-color: #f3f1eb;
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
  z-index: 1;
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
.btn-custom i {
  margin-right: 5px; /* Space between icon and text */
}
.table tbody tr:hover {
  background-color: #f3f1eb;
}
.add-item-btn, .table tbody tr td button, .btn-primary {
  font-family: 'Edu AU VIC WA NT Hand', cursive;
  border: none;
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