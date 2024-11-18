<template>
    <Notification
      :show="notification.show"
      :type="notification.type"
      :message="notification.message"
    />
  
    <div class="main-content">
      <!-- Booking History Table -->
      <h2>Booking History</h2>
      <table v-if="bookingHistory.length">
        <thead>
          <tr>
            <th>Name</th>
            <th>Booking Date</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(booking, index) in bookingHistory" :key="index">
            <td>{{ getUserName(booking.user_id) }}</td>
            <td>{{ booking.checkin }} - {{ booking.checkout }}</td>
            <td>{{ booking.specialRequest || 'N/A' }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else>Loading booking history...</p>
  
      <!-- Cottage History Table -->
      <h2>Cottage History</h2>
      <table v-if="cottageHistory.length">
        <thead>
          <tr>
            <th>Name</th>
            <th>Cottage Date</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(cottage, index) in cottageHistory" :key="index">
            <td>{{ getUserName(cottage.user_id) }}</td>
            <td>{{ cottage.date }}</td>
            <td>{{ cottage.details || 'N/A' }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else>Loading cottage history...</p>
  
      <!-- Reservation History Table -->
      <h2>Reservation History</h2>
      <table v-if="reservationHistory.length">
        <thead>
          <tr>
            <th>Name</th>
            <th>Reservation Date</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(reservation, index) in reservationHistory" :key="index">
            <td>{{ getUserName(reservation.user_id) }}</td>
            <td>{{ reservation.reservation_time }}</td>
            <td>{{ reservation.details || 'N/A' }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else>Loading reservation history...</p>
    </div>
  </template>
  
  
  <script>
  import axios from "axios";
  import { Chart, registerables } from "chart.js";
  Chart.register(...registerables);
  
  export default {
    name: "analytics",
    data() {
      return {
        bookingHistory: [],
      cottageHistory: [],
      reservationHistory: [],
      user: {}, // Mapping user ID to name
      notification: {
        show: false,
        type: '',
        message: ''
      }
      };
    },
    mounted() {
      this.fetchAllData();
    },
    methods: {
      async fetchAllData() {
      try {
        await Promise.all([
          this.fetchUsers(),
          this.fetchBookingHistory(),
          this.fetchCottageHistory(),
          this.fetchReservationHistory()
        ]);
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    async fetchUsers() {
      try {
        const response = await axios.get("/getData");
        console.log("User Data:", response.data);
        response.data.forEach(user => {
          this.user[user.id] = user.name; // Correct mapping of user ID to name
        });
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },
    async fetchBookingHistory() {
      try {
        const response = await axios.get("/getRoomBookingHistory");
        console.log("Booking History Data:", response.data);
        this.bookingHistory = response.data;
      } catch (error) {
        console.error("Error fetching booking history:", error);
      }
    },
    async fetchCottageHistory() {
      try {
        const response = await axios.get("/getCottageHistory");
        console.log("Cottage History Data:", response.data);
        this.cottageHistory = response.data;
      } catch (error) {
        console.error("Error fetching cottage history:", error);
      }
    },
    async fetchReservationHistory() {
      try {
        const response = await axios.get("/getReservationHistory");
        console.log("Reservation History Data:", response.data);
        this.reservationHistory = response.data;
      } catch (error) {
        console.error("Error fetching reservation history:", error);
      }
    },
    getUserName(userId) {
      return this.user[Number(userId)] || 'Unknown User';
    },

    }
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
    height: 100%;
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
    width: 100%;
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
  .card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .card-wrapper {
    flex: 1;
    min-width: 300px;
  }
  .card {
    background-color: #f4f4f4;
    border-radius: 10px;
    margin: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  
  }
  .card:hover {
    transform: translateY(-20px);
  }
  
  .card-header {
    background-color: #f2f2f2;
    padding: 5px;
    border-bottom: 1px solid #ddd;
  }
  .table {
    width: 100%;
    margin-bottom: 20px;
  }
  
  .table th, .table td {
    padding: 10px;
    text-align: left;
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
    padding: 12px; /* Padding inside header cells */
    text-align: center; /* Align text to the left */
    font-weight: bold; /* Bold text */
    border-right: 2px solid #ddd; /* Bottom border for header */
    
  }
  .card-body {
    flex: 1 1 auto;
    min-height: 1px;
   
  }
  .card-default .card-body {
    padding-top: 1rem;
  }
  
  .card-body .modal-body, .card-body .alert, th {
    font-family: "Poppins", sans-serif;
    font-weight: 800;
    font-style: normal;
    font-size: large;
    color:#0F172B; 
  }
  .table-product tbody td.action-buttons button,
  .table-product tbody td.action-buttons a, td {
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
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
    color:black;
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
  
  .btn-custom, .edit-btn {
    background-color: #fea116; /* Same as Add Item button */
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    padding: 10px;
    margin: 5px;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  }
  .edit-btn{
    padding: 12px;
    font-size: 15px;
    width: 65px;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
   
  }
  .btn-custom:hover {
    background-color: #0F172B;
    transform: scale(1.10);
    border:none;
  }
  .edit-btn:hover{
    background-color: #0F172B;
    transform: scale(1.10);
    border:none;
  }
  .btn:hover{
    background-color: #0F172B;
    transform: scale(1.10);
    border:none;
  }
  .btn-custom i {
    margin-right: 5px; /* Space between icon and text */
  }
  
  
  
  @media (max-width: 768px) {
    .header {
      flex-direction: column; /* Stack header items vertically on small screens */
      align-items: flex-start; /* Align items to the start on small screens */
    }
    .main-content {
      margin-left: 0; /* Remove left margin for small screens */
    }
  }
  @media (max-width: 768px) {
    .table-product th, .table-product td {
      padding: 8px; /* Adjust padding for smaller screens */
    }
    .card-body {
      padding: 1rem; /* Adjust padding for smaller screens */
    }
    .card-default{
      padding: 1rem;
    }
    .car-header{
      padding: 1rem;
    }
    .carding{
      padding: 1rem;
    }
    .header {
      flex-direction: column; /* Stack header items vertically */
      align-items: flex-start; /* Align items to the start */
    }
  
    .logout-logo-btn {
      margin-top: 10px; /* Add margin-top for spacing */
    }
  
  }
  @media (max-width: 576px) {
    .sidebar {
      width: 60px;
      padding: 10px;
    }
  
    .sidebar:hover {
      width: 40px;/* Ensure width stays consistent */
    }
    .sidebar a, .sidebar router-link {
      padding: 5px;
      font-size: 14px;
    }
    .sidebar img.logo {
      width: 30px; /* Smaller logo for mobile */
    }
  
    .main-content {
      margin-left: 60px;
      padding: 10px; /* Adjust margin for reduced sidebar width */
    }
  
    .sidebar:hover ~ .main-content {
      margin-left: 50px; /* Ensure margin stays consistent */
    }
  
    .card {
      margin: 10px; /* Reduce margin for smaller screens */
    }
  
    .card-body {
      padding: 0.5rem; /* Adjust padding for smaller screens */
    }
    .header {
      padding: 10px;
      flex-direction: column;
      align-items: flex-start;
    }
    .logout-logo-btn {
      margin-top: 10px;
    }
  
    .card-container {
      flex-direction: column;
    }
  
    .card-wrapper {
      min-width: 100%;
    }
  }
  .btn {
    border-radius: 5px;
    padding: 8px 12px; /* Adjust padding for small screens */
  }
  
  @media (max-width: 768px) {
    .card-big {
      padding-left: 0; /* Remove extra padding for smaller screens */
    }
    .content section {
      padding: 10px; /* Add padding to the section for better spacing */
    }
    .content button {
      display: block; /* Stack buttons vertically */
      margin-bottom: 10px;
    }
    canvas {
      width: 100% !important; /* Ensure canvas takes full width */
      height: auto !important; /* Adjust height automatically */
    }
    /* Card Styles */
  .card {
    margin: 5px 0; /* Reduce margin for smaller screens */
    width: 100%; /* Ensure cards take full width */
  }
  
  
  /* Adjust padding and font sizes */
  .card-header h2 {
    font-size: 18px; /* Adjust header font size */
  }
  .card-body {
    padding: 10px; /* Adjust padding */
  }
  }
  @media (max-width: 320px) {
    #user-acquisition {
      margin: 5px 0; /* Further reduced margin */
    }
  
    table {
      width: 100%; /* Ensure table takes full width */
      font-size: 12px; /* Adjust font size for small screens */
    }
  
    th, td {
      padding: 6px; /* Further reduced padding */
      text-align: left;
    }
    .header {
      flex-direction: column; /* Stack header items vertically */
      align-items: flex-start; /* Align items to the start */
    }
    
    .logout-logo-btn {
      margin-top: 10px; /* Add margin-top for spacing */
    }
    canvas {
      width: 100% !important; /* Ensure canvas takes full width */
      height: auto !important; /* Adjust height automatically */
    }
  }
  /* User Acquisition Table */
  @media (max-width: 768px) {
    #user-acquisition {
      margin: 10px 0; /* Reduce margin */
    }
    table {
      width: 100%; /* Ensure table takes full width */
      font-size: 14px; /* Adjust font size */
    }
    th, td {
      padding: 8px; /* Reduce padding */
      text-align: left; /* Align text to the left */
    }
  }
  
  /* Orders Inventory Table */
  @media (max-width: 768px) {
    .table-product {
      width: 100%; /* Ensure table takes full width */
      overflow-x: auto; /* Allow horizontal scrolling */
    }
    .table-product th, .table-product td {
      padding: 8px; /* Reduce padding */
      font-size: 14px; /* Adjust font size */
    }
  }
  /* General Responsive Adjustments */
  @media (max-width: 768px) {
    .main-content {
      margin-left: 0; /* Remove margin on smaller screens */
    }
    .sidebar {
      width: 100%; /* Full width sidebar on small screens */
      height: auto; /* Adjust height automatically */
  
    }
    .sidebar:hover {
      width: 100%; /* Full width on hover */
    }
    .header {
      flex-direction: column; /* Stack header items vertically */
      align-items: flex-start; /* Align items to the start */
    }
    .logout-logo-btn {
      margin-top: 10px; /* Add margin-top for spacing */
    }
    .btn-custom, .edit-btn {
      font-size: 12px; /* Adjust font size */
      padding: 8px; /* Adjust padding */
    }
  
  }
  
  
  @media (max-width: 945px) {
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
    .card-default, .card-body, .card-header , th , tr , td , table{
      width: auto;
    }
    .table-product {
      width: 100%; /* Ensure table takes full width */
      overflow-x: auto; /* Allow horizontal scrolling */
    }
    .table-product th, .table-product td {
      padding: 8px; /* Reduce padding */
      font-size: 14px; /* Adjust font size */
    }
    .main-content {
      margin-left: 0; /* Remove margin on smaller screens */
    }
    .sidebar {
      width: 100%; /* Full width sidebar on small screens */
      height: auto; /* Adjust height automatically */
  
    }
    .sidebar:hover {
      width: 100%; /* Full width on hover */
    }
    .header {
      flex-direction: column; /* Stack header items vertically */
      align-items: flex-start; /* Align items to the start */
    }
    .logout-logo-btn {
      margin-top: 10px; /* Add margin-top for spacing */
    }
    .btn-custom, .edit-btn {
      font-size: 12px; /* Adjust font size */
      padding: 8px; /* Adjust padding */
    }
    .header {
      flex-direction: column; /* Stack header items vertically on small screens */
      align-items: flex-start; /* Align items to the start on small screens */
    }
    .main-content {
      margin-left: 0; /* Remove left margin for small screens */
    }
    .card-big {
      padding-left: 0; /* Remove extra padding for smaller screens */
    }
  }
  
  @media (max-width: 1045px) {
    .card-default, .card-body, .card-header , th , tr , td , table{
      width: auto;
    }
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
  .card-main2{
    background-color: #f4f4f4;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: 0.5s;
    height: 300px;
    position: relative; 
  }
  
  </style>