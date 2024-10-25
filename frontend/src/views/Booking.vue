<template>
  <div>
    <div v-if="showModal" class="custom-modal">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Room Booking</h5>
          <button type="button" class="close" @click="closeModal">&times;</button>
        </div>

        <div class="modal-body">
          <form @submit.prevent="save">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Selected Rooms:</label>
                  <ul>
                    <li v-for="roomName in selectedRoomNames" :key="roomName">{{ roomName }}</li>
                  </ul>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkin">Check-in Date</label>
                  <input
                    type="date"
                    id="checkin"
                    v-model="checkinDate"
                    class="form-control"
                    :min="minDate"
                    @change="setCheckinAndCheckout" 
                    required
                  />
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkinTime">Check-in Time</label>
                  <input
                    type="time"
                    id="checkinTime"
                    v-model="checkinTime"
                    class="form-control"
                    disabled  
                  />
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkout">Checkout</label>
                  <input
                    type="datetime-local"
                    id="checkout"
                    v-model="checkout"
                    class="form-control"
                    disabled  
                    required
                  />
                </div>
              </div>

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

              <div class="form-group">
        <label for="downpayment">Downpayment (PHP):</label>
        <input
  type="number"
  id="downpayment"
  :value="downpayment"  
  class="form-control"
  readonly
  required
/>

      </div>



              <div class="col-md-6">
                <div class="form-group">
                  <label for="downpaymentProof">Upload Proof of Downpayment</label><br />
                  <input
                    type="file"
                    ref="downpaymentProof"
                    @change="handleFileUpload"
                    class="form-control-file"
                    required
                  />
                </div>
              </div>
<div class="col-md-2">
<br>
<button type="button" class="btn btn-info" @click="openGcashQRCode">
          <i class="fas fa-qrcode"></i>QR
        </button>
  
    </div>
              <div class="col-md-12 mt-3">
                <button class="btn btn-primary w-100">Submit</button>
              </div>
            </div>
          </form>
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
        <img src="../assets/img/gcash.jpg" alt="GCash QR Code">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeGcashModal">Close</button>
      </div>
    </div>
  </div>
</div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click="closeModal"></div>

    <!-- Notification Display -->
    <Notification 
      v-if="notification.show" 
      :show="notification.show" 
      :type="notification.type" 
      :message="notification.message" 
    />
  </div>
</template>

<script>
import axios from "axios";
import Notification from "@/components/Notification.vue"; // Import the Notification component

export default {
  props: {
    selectedRoomNames: {
    type: Array,
    required: true,
  },

    selectedRoomIds: {
      type: Array,
      required: true,
    },
    totalRoomPrice: {
      type: Number,
      required: true,
    },
    downpayment: {
      type: Number,
      required: true,
    },
  },
  components: {
    Notification, // Register the Notification component
  },
  data() {
    return {
      showGcashModal: false,

      showModal: true,
      checkinDate: "",
      checkinTime: "14:00",
      checkout: "",
      specialRequest: "",
      payment_method: "",
      downpaymentProof: null,
      localDownpayment: this.downpayment, // Local copy of downpayment
      notification: {
        show: false,
        type: "",
        message: "",
      },
      id: sessionStorage.getItem('id') || '',
    };
  },
  mounted() {
    console.log("Received Downpayment:", this.localDownpayment); // Use localDownpayment instead of downpayment
  },
  computed: {
    minDate() {
      const today = new Date();
      today.setDate(today.getDate() + 1);
      return today.toISOString().slice(0, 10);
    },
  },
  methods: {
    openGcashQRCode() {
      this.showGcashModal = true;
    },
    closeGcashModal() {
      this.showGcashModal = false;
    },
    closeModal() {
      this.showModal = false;
      this.$emit('close');
    },
    handleFileUpload(event) {
      this.downpaymentProof = event.target.files[0];
    },
    async save() {
      try {
        const formData = new FormData();
        formData.append("checkin", this.checkinDate + "T" + this.checkinTime);
        formData.append("checkout", this.checkout);
        formData.append("specialRequest", this.specialRequest);
        formData.append("payment_method", this.payment_method);
        formData.append("downpayment", this.localDownpayment); // Use localDownpayment here
        formData.append("downpaymentProof", this.downpaymentProof);
        formData.append("id", this.id);

        // Append selectedRoomIds as an array
        this.selectedRoomIds.forEach((roomId) => {
          formData.append('room_ids[]', roomId);
        });

        const response = await axios.post("booking", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.status === 200) {
          this.showNotification('success', response.data.message);
          this.clearForm();
        }
      } catch (error) {
        this.showNotification('error', error.response?.data?.message || "Booking failed");
      }
    },
    clearForm() {
      this.checkinDate = "";
      this.checkinTime = "14:00";
      this.checkout = "";
      this.specialRequest = "";
      this.payment_method = "";
      this.localDownpayment = 0; // Reset local downpayment if needed
      this.downpaymentProof = null;
      this.closeModal();
    },
    showNotification(type, message) {
      this.notification = { show: true, type, message };
      setTimeout(() => {
        this.notification.show = false;
      }, 3000);
    },
    setCheckinAndCheckout() {
      if (this.checkinDate) {
        const checkinDateTime = new Date(this.checkinDate);
        checkinDateTime.setHours(14, 0, 0);

        if (!this.checkinTime) {
          this.checkinTime = checkinDateTime.toISOString().slice(11, 16);
        }

        const checkoutDateTime = new Date(checkinDateTime);
        checkoutDateTime.setHours(checkoutDateTime.getHours() + 29);

        this.checkout = checkoutDateTime.toISOString().slice(0, 16);
      }
    },
  },
};
</script>


<style>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

.custom-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  width: 100%;
  max-width: 600px; /* Increased max width */
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden; /* Prevents overflow of content */
}

.modal-content {
  padding: 20px;
  overflow: hidden; /* Prevents overflow of content */
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e5e5e5;
  padding-bottom: 10px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  padding-top: 10px;
  border-top: 1px solid #e5e5e5;
}

.alert {
  margin-top: 15px;
}

.modal-body {
  display: flex;
  flex-direction: column;
}

.modal-body img {
  max-width: 100%; /* Makes the image responsive */
  height: auto; /* Maintains aspect ratio */
}

.modal-dialog {
  max-width: 90%; /* Ensures modal doesn't exceed viewport width */
}

button.close {
  border: none; /* Removes button border */
  background: transparent; /* Transparent background for close button */
  font-size: 1.5em; /* Increases size for easier access */
}

@media (max-width: 768px) {
  .custom-modal {
    max-width: 100%; /* Full width on small screens */
    margin: 0 20px; /* Adds margin for better appearance */
  }

  .modal-content {
    padding: 10px; /* Reduce padding on small screens */
  }
}

</style>
