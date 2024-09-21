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
          <div class="container-fluid">
            <div class="card-container">
              <div class="row">

  
                    
              
                          <section class="card-main">
                            <h2>Sales Report</h2>
                            <button class="btn-custom" @click="printSalesReport">Print Sales Report</button>
                            <div class="card-main">
                              <button class="btn-custom" @click="updateChart('daily')">Daily</button>
                              <button class="btn-custom" @click="updateChart('monthly')">Monthly</button>
                              <button class="btn-custom" @click="updateChart('yearly')">Yearly</button>
                            </div>
                            <canvas class="card-main" ref="combinedCanvas"></canvas>
                          </section>
                   
                
          
                <div class="card-wrapper">
                  <div class="card card-default card-size carding ">
                    <div class="card-header">
                      <h2>Users</h2>
                    </div>
                    <div class="card-body">
                      <div v-if="loading">Loading...</div>
                      <div v-else>
                        <div class="bg-primary d-flex justify-content-between flex-wrap p-4 text-white align-items-lg-end carding" style="height: 100%; width: 100%">
                          <div class="d-flex flex-column">
                            <span class="h3 text-white">{{ numberOfClients }}</span>
                            <span class="text-white">Total Clients</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-wrapper">
                  <div class="card card-default card-size carding ">
                    <div class="card-header">
                      <h2>E-Shop</h2>
                    </div>
                    <div class="card-body">
                      <div class="bg-primary d-flex justify-content-between flex-wrap p-4 text-white align-items-lg-end carding" style="height: 100%; width: 100%">
                        <div class="d-flex flex-column">
                          <span class="h3 text-white">{{ numberOfItems }}</span>
                          <span class="text-white">Items</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-wrapper">
                  <div class="card card-default card-size carding">
                    <div class="card-header">
                      <h2>Booking Count</h2>
                    </div>
                    <div class="card-body">
                      <div class="bg-primary d-flex justify-content-between flex-wrap p-4 text-white align-items-lg-end carding" style="height: 100%; width: 100%">
                        <div class="d-flex flex-column">
                          <span class="h3 text-white">{{ numberOfbooking }}</span>
                          <span class="text-white">Booking</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-wrapper" style="padding: 0;">
                  <div class="card card-default" id="user-acquisition" >
                    <div class="card-header border-bottom pb-0">
                      <h2>User Acquisition</h2>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>User</th>
                            <th>Feedback</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody v-for="feed in feed" :key="name.id">
                          <tr>
                            <td>{{ getName(feed).name }}</td>
                            <td>{{ feed.feedback }}</td>
                            <td>
                              <button @click="hideFeed(feed.feed_id)">Hide</button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="card-footer d-flex flex-wrap bg-white">
                      <a href="#" class="text-uppercase py-3" >Acquisition Report</a>
                    </div>
                  </div>
                </div>
                <div class="card-wrapper">
                  <div class="card card-default">
                    <div class="card-header">
                      <h2>Orders Inventory</h2>
                    </div>
                    <div class="card-body" style="padding: 0;">
                      <table id="ordersTable" class="table table-hover table-product" style="width: 100%">
                        <thead>
                          <tr>
                            <th>Room ID</th>
                            <th>Payment</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="book in book" :key="book.room_id">
                            <td>{{ book.room_id }}</td>
                            <td>{{ book.payment_method }}</td>
                            <td>{{ book.booking_status }}</td>
                          </tr>
                        </tbody>
                      </table>
                      <div v-if="loading" class="text-center mt-3">
                        <div class="spinner-border" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                      <div v-if="successMessage" class="alert alert-success mt-3" role="alert">
                        {{ successMessage }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>  
            </div>
          </div>
        </div>
      </div>
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
      isSidebarCollapsed: false,
      successMessage: '', 
      loading: '',
      name: [],
      feed: [],
      book: [],
      notification: {
      show: false,
      type: "",
      message: "",
    },
      numberOfClients: 0,
      numberOfItems: 0,
      numberOfbooking: 0,
      dailySales: {},
      monthlySales: {},
      yearlySales: {},
      dailyChart: null,
      monthlyChart: null,
      yearlyChart: null,
      combinedChart: null,
      currentReportType: "daily", // default to daily
      reportData: {}, // Initialize as an empty object instead of null
      monthNames: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
    };
  },
  mounted() {
    this.getData();
    this.getShop();
    this.getFeed();
    this.getName();
    this.getbook();
    this.getHistory();
    this.fetchCombinedSalesData();
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
    printSalesReport() {
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>Sales Report</title>
        <style>
          @media print {
            /* Hide header and footer on print */
            #header, #footer {
              display: none;
            }
          }
          table {
            border-collapse: collapse;
            width: 100%;
          }
          th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
          }
          th {
            background-color: #f2f2f2;
          }
          #header, #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #777;
          }
          #header {
            top: 0;
            text-align: center;
          }
          #footer {
            bottom: 0;
            text-align: center;
          }
        </style>
      </head>
      <body>
        <div id="header">
  <img src="https://example.com/your-image.jpg" alt="Header Image" style="width: 100%;" />
</div>

        <h2 style="text-align: center;">Sales Report</h2>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Total Sales</th>
            </tr>
          </thead>
          <tbody>
            ${this.generateTableRows()} 
          </tbody>
        </table>
        <div id="footer">
          <img src="../assets/img/pool3.jpg" alt="Footer Image" style="width: 100%;" />
        </div>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print(); // Trigger print dialog
},

generateTableRows() {
  let rows = '';
  // Generate table rows dynamically based on chart data
  const chartData = this.reportData[this.currentReportType + 'Sales'];
  if (chartData) {
    chartData.forEach(sale => {
      rows += `
        <tr>
          <td>${this.getDateLabel(sale)}</td>
          <td>${sale.total_sales}</td>
        </tr>
      `;  
    });
  }
  return rows;
},
getDateLabel(sale) {
  if (this.currentReportType === 'monthly') {
    return `${this.monthNames[parseInt(sale.month) - 1]} ${sale.year}`;
  } else if (this.currentReportType === 'yearly') {
    return sale.year.toString();
  } else {
    return sale.date;
  }
},

    fetchCombinedSalesData() {
      axios
        .get("generateAllReports")
        .then((response) => {
          this.reportData = response.data; // Store the data
          console.log("Fetched report data:", this.reportData);
          this.updateChart(this.currentReportType); // Render chart initially with default data
        })
        .catch((error) => {
          console.error("Failed to fetch sales data", error);
        });
    },
    renderCombinedChart() {
      const ctx = this.$refs.combinedCanvas.getContext("2d");
      if (this.combinedChart) {
        this.combinedChart.destroy(); // Destroy the existing chart if it exists
      }
      const dataConfig = this.getDataConfig();
      this.combinedChart = new Chart(ctx, {
        type: "bar", // or line, depending on your preference
        data: dataConfig.data,
        options: dataConfig.options,
      });
    },

    getDataConfig() {
      const config = {
        daily: {
          data: {
            labels: this.reportData.dailySales.map((sale) => sale.date),
            datasets: [
              {
                label: "Daily Sales",
                data: this.reportData.dailySales.map(
                  (sale) => sale.total_sales
                ),
                backgroundColor: "rgba(54, 162, 235, 0.5)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        },
        monthly: {
          data: {
            labels: this.reportData.monthlySales.map(
              (month) =>
                `${this.monthNames[parseInt(month.month) - 1]} ${month.year}`
            ), // Concatenate month name and year
            datasets: [
              {
                label: "Monthly Sales",
                data: this.reportData.monthlySales.map(
                  (month) => month.total_sales
                ),
                backgroundColor: "rgba(255, 99, 132, 0.5)",
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        },

        yearly: {
          data: {
            labels: this.reportData.yearlySales.map((sale) =>
              sale.year.toString()
            ),
            datasets: [
              {
                label: "Yearly Sales",
                data: this.reportData.yearlySales.map(
                  (sale) => sale.total_sales
                ),
                backgroundColor: "rgba(75, 192, 192, 0.5)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
              x: {
                ticks: {
                  autoSkip: false,
                },
              },
            },
          },
        },
      };

      return config[this.currentReportType]; // Return config based on current report type
    },

    updateChart(type) {
      this.currentReportType = type;
      this.renderCombinedChart();
    },
    async hideFeed(feedId) {
      const confirmResult = window.confirm("Do you want to HIDE this item?");

      if (confirmResult) {
        try {
          await axios.post(`/api/feedback/hide/${feedId}`);
          this.feed = this.feed.map((feed) => {
            if (feed.feed_id === feedId) {
              return { ...feed, is_hidden: 1 };
            }
            return feed;
          });
          console.log("Feedback hidden successfully");
          this.getFeed();
        } catch (error) {
          console.error("Error hiding feedback:", error);
        }
      }
    },

    async getFeed() {
      const [g, n] = await Promise.all([
        axios.get("/getFeedback"),
        axios.get("/getData"),
      ]);
      this.feed = g.data;
      this.name = n.data;
    },

    getName(g) {
      return this.name.find((n) => n.id === g.id) || {};
    },

    async getData() {
      const response = await axios.get("/getData");
      this.data = response.data;
      this.numberOfClients = this.data.length;
    },

    async getShop() {
      const items = await axios.get("/getShop");
      this.data = items.data;
      this.numberOfItems = this.data.length;
    },
    async getHistory() {
      const b = await axios.get("/getBookingHistory");
      this.book = b.data;
    },
    async getbook() {
      const items = await axios.get("/getbook");
      this.data = items.data;
      this.numberOfbooking = this.data.length;
    },
    fetchDailySales() {
      axios
        .get("/sales-report/daily")
        .then((response) => {
          this.dailySales = response.data;
        })
        .catch((error) => {
          console.error("Error fetching daily sales:", error);
        });
    },
    fetchMonthlySales() {
      axios
        .get("/sales-report/monthly")
        .then((response) => {
          this.monthlySales = response.data;
        })
        .catch((error) => {
          console.error("Error fetching monthly sales:", error);
        });
    },
    fetchYearlySales() {
      axios
        .get("/sales-report/yearly")
        .then((response) => {
          this.yearlySales = response.data;
        })
        .catch((error) => {
          console.error("Error fetching yearly sales:", error);
        });
    },
  },
  filters: {
    currency(value) {
      const number = parseFloat(value);
      return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
      }).format(number);
    },
  },
  created() {
    this.fetchDailySales();
    this.fetchMonthlySales();
    this.fetchYearlySales();
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

.table-product th, .table-product td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.card-body {
  flex: 1 1 auto;
  min-height: 1px;
 
}
.card-default .card-body {
  padding-top: 1rem;
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
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  text-decoration: none;
  transition: background-color 0.3s, transform 0.3s;
  padding: 10px;
  margin: 5px;
  font-family: "Edu AU VIC WA NT Hand", cursive;
}
.edit-btn{
  padding: 12px;
  font-size: 15px;
  width: 65px;
  font-family: "Edu AU VIC WA NT Hand", cursive;
 
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
</style>