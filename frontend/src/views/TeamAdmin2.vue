<template>

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
 
    <div class="content-wrapper">
  

      <!-- Add your staff table here -->
      <div class="content">
        <div class="card card-default">
          <div class="card-header">
            <h2>Staff List</h2>
            <button type="button" class="btn btn-secondary" @click="openAddModal">Add Staff</button>
          </div>
          <div class="card-body">
            <div class="table-responsive">
            <table class="table ">
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
                    <button class="edit-btn" @click="openStaffEditModal(staff)">Edit</button> |
                    <button class="edit-btn" @click="hidestaff(staff.staff_id)">Hide</button>
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
      <button type="button" class="btn btn-secondary" @click="cancelDelete">Cancel</button>
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
                <button type="button" class="btn btn-secondary" @click="closeAddModal">Close</button>
                <button type="submit" class="btn btn-primary">Save Staff</button>
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
          <button type="button" class="btn btn-secondary" @click="closeStaffEditModal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
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
mounted() {
  this.getStaff();
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

 .modal-body, .card-body .alert, th {
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
  background-color: #ff9933; /* Change background color on hover */
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
  border:none;
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
margin: 5px;

}
.btn-secondary{
  background-color: #fea116;
  border:none;
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
.table td {
  vertical-align: middle; /* Align the "Actions" button vertically */
}
.table tbody tr:hover {
  background-color: #ff9933; /* Change background color on hover */
}
.add-item-btn, .table tbody tr td button, .btn-secondary {
  font-family: 'Edu AU VIC WA NT Hand', cursive;
  border: none;
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
</style>