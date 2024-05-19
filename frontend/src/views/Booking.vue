<template>
  <div>
    <Top />
    <navbar />
    <div class="container-xxl bg-white p-0">
      <div class="Booking">
        <div class="container-fluid page-header mb-5 p-0">
          <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
              <h1 class="display-3 text-white mb-3 animated slideInDown">
                Booking
              </h1>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="#">Pages</a></li>
                  <li
                    class="breadcrumb-item text-white active"
                    aria-current="page"
                  >
                    Contact
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Booking Start -->
    <div class="container-xxl py-5">
      <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
          <h6 class="section-title text-center text-primary text-uppercase">
            Room Booking
          </h6>
          <h1 class="mb-5">
            Book A <span class="text-primary text-uppercase">Luxury Room</span>
          </h1>
        </div>
        <div class="row g-5">
          <div class="col-lg-6">
            <div class="row g-3">
              <!-- Images -->
              <div class="col-6 text-end">
                <img
                  :src="require('../assets/img/pool3.jpg')"
                  alt=""
                  class="img-fluid rounded w-100 wow zoomIn"
                  data-wow-delay="0.1s"
                />
              </div>
              <div class="col-6 text-start">
                <img
                  :src="require('../assets/img/pool5.jpg')"
                  alt=""
                  class="img-fluid w-100 wow zoomIn"
                  data-wow-delay="0.3s"
                />
              </div>
              <div class="col-6 text-end">
                <img
                  :src="require('../assets/img/band.jpg')"
                  alt=""
                  class="img-fluid rounded w-75 wow zoomIn"
                  data-wow-delay="0.5s"
                />
              </div>
              <div class="col-6 text-start">
                <img
                  :src="require('../assets/img/pool2.jpg')"
                  alt=""
                  class="img-fluid rounded w-100 wow zoomIn"
                  data-wow-delay="0.7s"
                />
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="wow fadeInUp" data-wow-delay="0.2s">
              <!-- Booking Form -->
<!-- Booking Form -->
<form @submit.prevent="save">
  <div class="row">
<!-- Checkin -->
<div class="col-md-6">
  <div class="form-group">
    <label for="checkin">Checkin</label>
    <input
      type="datetime-local"
      id="checkin"
      v-model="checkin"
      class="form-control"
      :min="minDate"
      required
    />
  </div>
</div>

<!-- Checkout -->
<div class="col-md-6">
  <div class="form-group">
    <label for="checkout">Checkout</label>
    <input
      type="datetime-local"
      id="checkout"
      v-model="checkout"
      class="form-control"
      required
      readonly 
    />
  </div>
</div>

    <!-- Number of Adult -->
    <!-- <div class="col-md-6">
      <div class="form-group">
        <label for="adult">Number of Adult</label>
        <input
          type="number"
          placeholder="Number of Adult"
          v-model="adult"
          class="form-control"
          required
        />
      </div>
    </div> -->

    <!-- Number of Child -->
    <!-- <div class="col-md-6">
      <div class="form-group">
        <label for="child">Number of Child</label>
        <input
          type="number"
          placeholder="Number of Child"
          v-model="child"
          class="form-control"
        />
      </div>
    </div> -->

    <!-- Special Request -->
    <div class="col-md-12">
      <div class="form-group">
        <label for="specialRequest">Special Request</label>
        <textarea
          type="text"
          placeholder="Special Request"
          v-model="specialRequest"
          class="form-control"
          required
        ></textarea>
      </div>
    </div>

  <!-- Payment Method -->
<div class="col-md-12">
  <div class="form-group">
    <label for="paymentMethod">Payment Method</label>
    <select
      id="paymentMethod"
      v-model="payment_method"
      class="form-control"
      required
    >
      <option value="" disabled>Select Payment Method</option>
      <option value="online">Pay Online</option>
      <option value="cash">Cash Payment</option>
    </select>
  </div>
</div>
<!-- Downpayment Option -->
<div class="col-md-12">
  <div class="form-group">
    <label for="downpayment">Downpayment (PHP)</label><br>
    <input type="number" id="downpayment" v-model="downpayment" class="form-control">
  </div>
</div>



<div class="col-md-12 mt-3">
  <button  class="btn btn-primary w-100">
    Submit
  </button>
</div>

  </div>
</form>
 <!-- Success and Error Messages -->
 <div v-if="successMessage" class="alert alert-success mt-3">
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert alert-danger mt-3">
      {{ errorMessage }}
    </div>

              
              <!-- End Success and Error Messages -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Booking End -->

    <!-- Newsletter Start -->
    <div class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s">
      <div class="row justify-content-center">
        <div class="col-lg-10 border rounded p-1">
          <div class="border rounded text-center p-1">
            <div class="bg-white rounded text-center p-5">
              <h4 class="mb-4">
                Subscribe Our
                <span class="text-primary text-uppercase">Newsletter</span>
              </h4>
              <div class="position-relative mx-auto" style="max-width: 400px">
                <input
                  class="form-control w-100 py-3 ps-4 pe-5"
                  type="text"
                  placeholder="Enter your email"
                />
                <button
                  type="button"
                  class="btn btn-primary py-2 px-3 position-absolute top-0 end-0 mt-2 me-2"
                >
                  Submit
                </button>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <!-- Newsletter End -->
<!-- Modal -->
<!-- <div class="modal" :class="{ 'is-active': showModal }">
  <div class="modal-background" @click="showModal = false"></div>
  <div class="modal-content">
    <div class="text-center">
      <h4>Booking Information</h4>
    <h4>QR Code</h4>
    <canvas id="qrcodeCanvas" width="200" height="200"></canvas>
    <p v-if="isExpired" class="text-danger">QR code has expired.</p>
  </div>

    <button @click="generatePDF" class="btn btn-primary">Download Booking Information</button>
@click="showModal = true"
   
    <button class="modal-close is-large" aria-label="close" @click="showModal = false"></button>
  </div>
</div> -->



  </div>
</template>
  <script>
  // import { saveAs } from 'file-saver';
  import jsPDF from 'jspdf';

  import QRCode from "qrcode"; 
  import Top from "@/components/Top.vue";
  import navbar from "@/components/navbar.vue";
  import End from "@/components/End.vue";
  import axios from "axios";

  export default {
    name: "booking",
    components: {
      Top,
      navbar,
      End,
    },
    data() {
      return {
        checkin: "",
        checkout: "",
        specialRequest: "",
        successMessage: "",
        errorMessage: "",
        payment_method: "",
        downpayment:"",
        qrCodeData: "",
        isExpired: false,
        showModal: false, 
        bookingInfo: [], 
      };
    },
    mounted() {
      // Call getBookingInfo when the component is mounted
      this.getBookingInfo();
    },
    computed:{
      minDate() {
    const today = new Date();
    const minDate = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);// Bukas
    return minDate.toISOString().slice(0, -8); // Format bilang YYYY-MM-DDTHH:MM
  },
    },
    methods: {
      async generateQRCode() {
    try {
      const canvas = document.getElementById("qrcodeCanvas");
      console.log("Booking Info:", this.bookingInfo); // Check booking info
      await QRCode.toCanvas(canvas, JSON.stringify(this.bookingInfo));
    } catch (error) {
      console.error("Error generating QR code", error);
    }
  },

      generatePDF() {
  //   // Create a new PDF document
    const doc = new jsPDF();

  //   // Add content to the PDF
    doc.text(`Booking Information:`, 10, 10);
    doc.text(`Checkin: ${this.bookingInfo.checkin}`, 10, 20);
    doc.text(`Checkout: ${this.bookingInfo.checkout}`, 10, 30);
    doc.text(`Special Request: ${this.bookingInfo.specialRequest}`, 10, 40);
    doc.text(`Payment Method: ${this.bookingInfo.payment_method}`, 10, 50);
    doc.text(`Downpayment: ${this.bookingInfo.downpayment}`, 10, 60);
    // Add more content as needed

    // Save the PDF as a downloadable file
    doc.save('booking_information.pdf');
  },
    calculateCheckoutTime() {
  if (this.checkin) {
    // Create a Date object from the checkin string
    let checkinDate = new Date(this.checkin);

    // Add 21 hours directly to the checkin date
    checkinDate.setHours(checkinDate.getHours() + 29);

    // Format the checkout time string in the 'YYYY-MM-DDTHH:MM' format
    let checkoutTimeString = checkinDate.toISOString().slice(0, 16);

    // Update the checkout data property
    this.checkout = checkoutTimeString;
  }
},

      async save() {
        try {
          const id = sessionStorage.getItem("id");
          const response = await axios.post("booking", {
            id: id,
            checkin: this.checkin,
            checkout: this.checkout,
            specialRequest: this.specialRequest,
            room_id: this.$route.params.id,
            payment_method: this.payment_method,
            downpayment: this.downpayment,
          });

          if (response.status === 200) {
            this.successMessage = response.data.message;
            this.checkin = "";
            this.checkout = "";
            this.specialRequest = "";
            this.payment_method = "";
            this.downpayment = "";

            setTimeout(() => {
              this.successMessage = "";
            }, 2000);
          }
        } catch (error) {
          console.error("Error booking", error);
          if (error.response && error.response.status === 400) {
            this.errorMessage = error.response.data.message || "Booking failed";
          } else {
            this.errorMessage = "Error booking";
          }
          this.successMessage = "";
        }
      },

      async getBookingInfo() {
  try {
    console.log("Fetching booking info...");
    const id = sessionStorage.getItem("id");
    if (!id) {
      console.error("User book_id not found in session storage");
      return;
    }
    const response = await axios.get(`/getBooking/${id}`);
    console.log("Booking info:", response.data);
    
    this.bookingInfo = response.data;
    
    this.qrCodeData = response.data.qrCodeData;

    this.showModal = true;
  } catch (error) {
    console.error("Error fetching booking info", error);
  }
},


    },

    watch: {
      checkin(newValue) {
        this.calculateCheckoutTime();
      },
    },
  };
  </script>

<style>
@import "@/assets/css/bootstrap.min.css";
@import "@/assets/css/style.css";
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
  justify-content: center; /* Center the content horizontally */
  align-items: center; /* Center the content vertically */
  display: flex; /* Enable flexbox layout */
}

.modal.is-active {
  display: flex;
}

.modal-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  max-width: 80%;
}

.modal-close {
  position: absolute;
  top: 15px;
  right: 15px;
  cursor: pointer;
}
.Booking {
  background-image: url("~@/assets/img/pool4.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  width: 100%;
  height: 338px;
}

/* Add additional styles as needed */
</style>
