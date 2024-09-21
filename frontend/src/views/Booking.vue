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

    <div id="app">
<!-- Booking Form -->
<form @submit.prevent="openModal">
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
        />
      </div>
    </div>

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
        <input
          type="number"
          id="downpayment"
          v-model="downpayment"
          class="form-control"
          required
        />
      </div>
    </div>

        <!-- Downpayment Proof -->
<div class="col-md-6">
  <div class="form-group">
    <label for="downpaymentProof">Upload Proof of Downpayment</label><br>
    <input 
      type="file" 
      ref="downpaymentProof" 
      @change="handleFileUpload" 
      class="form-control-file"
      multiple
      required
    >
  </div>
</div>

<div class="col-md-3">
<br>
<button type="button" class="btn btn-info" @click="openGcashQRCode">
          <i class="fas fa-qrcode"></i>  GCASH
        </button>
    </div>

    <div class="col-md-3">
<br>
<button type="button" class="btn btn-success " @click="openPaypalQRCode">
          <i class="fa-brands fa-paypal"></i>  PayPal
        </button>
    </div>

    <div class="col-md-12 mt-3">
      <button class="btn btn-primary w-100">
        Submit
      </button>
    </div>
  </div>
</form>

<!-- Modal Structure -->
<div v-if="showModal" class="custom-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Confirm Your Booking</h5>
      <button type="button" class="btn btn-info" @click="openGcashQRCode">
          <i class="fas fa-qrcode"></i>QR
        </button>
      <button type="button" class="close" @click="closeModal">&times; </button>
    </div>
    <div class="modal-body">
      <div class="modal-info">
        <p><strong>Check-in:</strong> {{ checkin }}</p>
        <p><strong>Check-out:</strong> {{ checkout }}</p>
        <p><strong>Payment Method:</strong> {{ payment_method === 'online' ? 'Pay Online' : 'Cash Payment' }}</p>
        <p><strong>Downpayment:</strong> PHP {{ downpayment }}</p>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-success" @click="save">Confirm Booking</button>
      <button class="btn btn-danger" @click="closeModal">Cancel</button>
    </div>
  </div>
</div>

<!-- Modal Overlay -->
<div v-if="showModal" class="modal-overlay" @click="closeModal"></div>
</div>


<!-- GCash QR Code Modal -->
<div v-if="showGcashModal" class="modal" tabindex="-1" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan GCash QR Code</h5>
        <button type="button" class="close" @click="closeGcashModal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <!-- QR Code Image (GCash) -->
        <img src="../assets/img/gcash.jpg" alt="GCash QR Code" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeGcashModal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Paypal QR Code Modal -->
<div v-if="showPaypalModal" class="modal" tabindex="-1" style="display: block;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan Paypal QR Code</h5>
        <button type="button" class="close" @click="closePaypalModal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <!-- QR Code Image (Paypal) -->
        <img src="../assets/img/gcash.jpg" alt="Paypal QR Code" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closePaypalModal">Close</button>
      </div>
    </div>
  </div>
</div>
 <!-- Success and Error Messages -->
 <div v-if="successMessage" class="alert alert-success mt-3">
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert alert-danger mt-3">
      {{ errorMessage }}


              
              <!-- End Success and Error Messages -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Booking End -->
  </div>

  
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
      payment_method: "",
      downpayment: "",
      downpaymentProof: null, // Store file here
      successMessage: "",
      errorMessage: "",
      showModal: false,
        successMessage: "",
        errorMessage: "",
        qrCodeData: "",
        isExpired: false,
        bookingInfo: [], 
        minDate: new Date().toISOString().slice(0, 16), // for example, to set the min date
        showGcashModal: false,
        showPaypalModal: false,

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
      openModal() {

      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    openGcashQRCode() {
      this.showGcashModal = true;
    },
    closeGcashModal() {
      this.showGcashModal = false;
    },
    openPaypalQRCode() {
      this.showPaypalModal = true;
    },
    closePaypalModal() {
      this.showPaypalModal = false;
    },
    handleFileUpload(event) {
      this.downpaymentProof = event.target.files[0];
    },

       
    async save() {
      try {
        const id = sessionStorage.getItem("id");

        // Create FormData object for file upload and form data
        const formData = new FormData();
        formData.append("id", id);
        formData.append("checkin", this.checkin);
        formData.append("checkout", this.checkout);
        formData.append("specialRequest", this.specialRequest);
        formData.append("room_id", this.$route.params.id);
        formData.append("payment_method", this.payment_method);
        formData.append("downpayment", this.downpayment);
        formData.append("downpaymentProof", this.downpaymentProof); // File upload

        const response = await axios.post("booking", formData, {
          headers: {
            "Content-Type": "multipart/form-data", // Important for file upload
          },
        });

        if (response.status === 200) {
          this.successMessage = response.data.message;
          this.checkin = "";
          this.checkout = "";
          this.specialRequest = "";
          this.payment_method = "";
          this.downpayment = "";
          this.downpaymentProof = null;

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
    doc.text(`Checkin: {this.bookingInfo.checkin}`, 10, 20);
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
/* Modal overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

/* Modal structure */
.custom-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  width: 100%;
  max-width: 500px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Modal content */
.modal-content {
  padding: 20px;
  border-radius: 8px;
}

/* Modal header */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e5e5e5;
  padding-bottom: 10px;
}

.modal-title {
  font-size: 18px;
  margin: 0;
}

.close {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
}

/* Modal body */
.modal-body {
  padding: 20px;
}

.modal-info p {
  margin: 0 0 10px;
  font-size: 16px;
  color: #333;
}

.modal-info strong {
  font-weight: 600;
}

/* Modal footer */
.modal-footer {
  display: flex;
  justify-content: flex-end;
  border-top: 1px solid #e5e5e5;
  padding-top: 10px;
}

.modal-footer .btn {
  margin-left: 10px;
  padding: 8px 20px;
  border-radius: 4px;
  font-size: 14px;
}

.modal-footer .btn-success {
  background-color: #28a745;
  color: #fff;
  border: none;
}

.modal-footer .btn-danger {
  background-color: #dc3545;
  color: #fff;
  border: none;
  cursor: pointer;
}

/* Add additional styles as needed */
</style>