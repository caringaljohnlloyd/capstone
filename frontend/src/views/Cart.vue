<template>
  <div>
    <Top />
    <div class="navbar-wrapper">
      <navbar />
    </div>
    <div class="container-fluid padding-bottom-3x mb-1">
      <Notification v-if="deleteSuccess" :show="deleteSuccess" type="success" message="Deleted successfully!" />

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center">Select</th>
              <th class="text-center">Product Name</th>
              <th class="text-center">Quantity</th>
              <th class="text-center">Price</th>
              <th class="text-center">Total Price</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cart in cart" :key="cart.shop_id">
              <td class="align-middle text-center">
                <input type="checkbox" :value="cart.cart_id" @change="toggleSelection(cart.cart_id)" :checked="isChecked(cart.cart_id)" />
              </td>
              <td class="align-middle">
                <div class="product-item">
                  <img class="img-fluid menu" :src="`https://eduardos-resort.online/backend/backend/public/uploads/${getImg(cart).prod_img}`" 
                  alt="Product" />
                  <div class="product-info">
                    <h4 class="product-title">{{ getInfo(cart).prod_name }}</h4>
                  </div>
                </div>
              </td>
              <td class="align-middle text-center">
                <div class="count-input">
                  <button @click="updateQuantity(cart, 'decrement')" class="btn btn-primary btn-sm rounded-circle"
                    :disabled="cart.quantity <= 1">
                    <i class="fas fa-minus"></i>
                  </button>
                  <span class="quantity">{{ cart.quantity }}</span>
                  <button @click="updateQuantity(cart, 'increment')" class="btn btn-primary btn-sm rounded-circle"
                    :disabled="cart.quantity >= getMaxQuantity(cart)">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </td>
              <td class="align-middle text-center text-lg text-medium">{{ getPrice(cart).prod_price }}</td>
              <td class="align-middle text-center text-lg text-medium">{{ getTotal(cart) }}</td>
              <td class="align-middle text-center">
                <button @click="deleteCart(cart.cart_id)" class="btn-trash btn-danger"><i class="fa fa-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

<div class="floating-container">
  <div class="column invoice-section">
    <h4>Payment Details:</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cartId in checkedItems" :key="cartId">
          <td>{{ getInfo(getCartItem(cartId)).prod_name }}</td>
          <td>Php.{{ getPrice(getCartItem(cartId)).prod_price }}</td>
        </tr>
      </tbody>
    </table>
    <div>
      <h4>Total:</h4> <span class="text-medium">Php.{{ calculateSubtotal() }}</span>
    </div>
  </div>

  <div class="column checkout-button-section">
    <div class="payment-form">
      <h4>Select Payment Method:</h4>

      <div>
        <label>
          <input type="radio" v-model="paymentMethod" value="gcash"> GCash
        </label>
      </div>

      <!-- Downpayment Proof Upload Section -->
      <h4>Upload Fullpayment Proof:</h4>
      <div class="d-flex align-items-center mb-3">
        <div class="col-md-6">
          <input type="file" @change="handleFileUpload" accept="image/*" class="form-control" required />
        </div>
        <div class="col-md-3 ms-2">
          <button type="button" class="btn-gcash btn-info w-100" @click="openGcashQRCode">
            <i class="fas fa-qrcode"></i> GCASH
          </button>
        </div>

      </div>

      <a class="btn btn-dark" @click="checkout">Proceed to Checkout</a>
    </div>
  </div>

  <div class="column checkout-products-section">
    <h4>Checkout Products:</h4>
    <table class="table">
      <thead>
        <tr>
          <th class="text-center">Product Name</th>
          <th class="text-center">Price</th>
          <th class="text-center">Quantity</th>
          <th class="text-center">Total Price</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in checkedOutProducts" :key="product.cart_id">
          <td>{{ getInfo(product).prod_name }}</td>
          <td>Php.{{ getPrice(product).prod_price }}</td>
          <td>{{ product.quantity }}</td>
          <td>Php.{{ getTotal(product) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div v-if="showGcashModal" class="modal fade show" tabindex="-1" style="display: block;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan GCash QR Code</h5>
        <button type="button" class="close" @click="closeGcashModal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <!-- QR Code Image (GCash) -->
        <div class="img-container">
          <img src="../assets/img/gcash.jpg" alt="GCash QR Code" class="img-fluid" style="max-width: 100%; height: auto;"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeGcashModal">Close</button>
      </div>
    </div>
  </div>
</div>




      <Notification v-if="insufficientStockError" :show="insufficientStockError" type="error"
        message="Insufficient stock for one or more items" />
      <Notification v-if="notification.show" :show="notification.show" :type="notification.type"
        :message="notification.message" />

      <Notification v-if="checkoutSuccess" :show="checkoutSuccess" type="success" message="Checkout successful!" />
      <Notification v-if="checkoutError" :show="checkoutError" type="error"
        message="Please select items before proceeding with the checkout." />

    </div>
  </div>
</template>
<style scoped>
.img-container {
  max-width: 100%; /* Ensure it doesn't overflow the modal */
  overflow: auto;  /* Allow scrolling */
  text-align: center; /* Center align the image */
}

.modal-body {
  padding: 20px; /* Add some padding */
}
/* General Styling */
.floating-container {
  margin-top: 20px;
  padding: 20px;
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  display: flex;
  flex-wrap: wrap; /* Allow items to wrap on smaller screens */
}

.column {
  flex: 1;
  padding: 10px;
}

.shopping-cart-footer {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap; /* Allow columns to wrap */
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.table th, .table td {
  text-align: center;
  vertical-align: middle; /* Vertically center table content */
  padding: 10px; /* Add padding for better spacing */
}

.table img {
  width: 60px;
  height: auto;
  display: block;
  margin: 0 auto;
  border-radius: 5px;
}

.invoice-section h4,
.checkout-button-section h4,
.checkout-products-section h4 {
  margin-bottom: 15px;
}

/* Responsive Modifications */
@media (max-width: 768px) {
  .floating-container {
    flex-direction: column; /* Stack columns vertically on small screens */
    align-items: stretch;
  }

  .column {
    margin-bottom: 20px; /* Space between columns */
  }

  .invoice-section, .checkout-button-section, .checkout-products-section {
    flex: 1 1 100%; /* Full width on small screens */
  }

  .table th, .table td {
    font-size: 14px;
    padding: 8px;
  }

  .modal-dialog {
    max-width: 90%; /* Make modal smaller on small screens */
  }

  .modal-header {
    padding: 15px;
  }

  .modal-title {
    font-size: 18px;
  }

  .modal-footer {
    padding: 15px;
  }

  .modal-body img {
    max-width: 100%;
    height: auto;
    margin: 0 auto;
  }
}
</style>




<script>
import axios from 'axios';
import Top from '@/components/Top.vue';
import navbar from '@/components/navbar.vue';
import Notification from "@/components/Notification.vue";

export default {
  name: 'cart',
  components: {
 Top,
    navbar,   Notification,    


  },
  data() {
    return {
      showGcashModal: false,
      paymentMethod: null,
      downpaymentProof: null,
      cartCheckedOutIds: [],
      
    checkedOutProducts: [],
       cart: [],
      deleteSuccess: false,
      prod_name: [],
      prod_price: [],
      prod_img: [],
      user: null,
      checkedItems: [],
      checkoutSuccess: false,
      checkoutError: false,
      insufficientStockError: false, 
      notification: {
        show: false,
        type: "",
        message: "",
      },

    };
  },
  mounted() {
    this.getCart();
    this.getInfo();
    this.getPrice();
    this.getImg();


  },
  methods: {
    handleFileUpload(event) {
    this.downpaymentProof = event.target.files[0];
  },
  openGcashQRCode() {
      this.showGcashModal = true;
    },
    closeGcashModal() {
      this.showGcashModal = false;
    },
    toggleSelection(cartId) {
    const index = this.checkedItems.indexOf(cartId);
    if (index === -1) {
      this.checkedItems.push(cartId);
    } else {
      this.checkedItems.splice(index, 1);
    }
  },

  isChecked(cartId) {
    return this.checkedItems.includes(cartId);
  },

  async checkout() {
    try {
    const id = sessionStorage.getItem("id");

    if (this.checkedItems.length === 0) {
      console.warn("No items selected for checkout.");
      this.checkoutError = true;
      setTimeout(() => {
        this.checkoutError = false;
      }, 3000);
      return;
    }

    if (!this.paymentMethod) {
      console.warn("Please select a payment method.");
      this.notification = {
        show: true,
        type: 'error',
        message: 'Please select a payment method.',
      };
      setTimeout(() => {
        this.notification = {
          show: false,
          type: '',
          message: '',
        };
      }, 3000);
      return;
    }

    const orderItems = this.checkedItems.map(cartId => {
      const cart = this.cart.find(cart => cart.cart_id === cartId);
      const shop_id = cart ? cart.shop_id : null;

      return {
        shop_id: shop_id,
        quantity: cart ? cart.quantity : 0,
        total_price: this.getTotal(cart),
      };
    });

    const orderData = {
      id: id,
      status: 'pending',
      total_price: parseFloat(this.calculateSubtotal()),
      items: orderItems,
      order_payment_method: this.paymentMethod,
    };

    // Create FormData object
    const formData = new FormData();
    formData.append('orderData', JSON.stringify(orderData)); // Append order data as string
    if (this.downpaymentProof) {
      formData.append('downpayment_proof', this.downpaymentProof); // Append the file
    }

    const response = await axios.post('checkout', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });


    if (response.status === 400) {
      console.warn("Insufficient stock for one or more items");
      this.notification = {
        show: true,
        type: 'error',
        message: 'Insufficient stock for one or more items',
      };
      setTimeout(() => {
        this.notification = {
          show: false,
          type: '',
          message: '',
        };
      }, 3000);
      return;
    }

    if (response.status === 200) {
      const invoice_id = response.data.invoice_id;

      const newlyCheckedOutProducts = this.cart.filter(cart => this.checkedItems.includes(cart.cart_id));

      this.checkedOutProducts.push(...newlyCheckedOutProducts);

      this.cart = this.cart.filter(cart => !this.checkedItems.includes(cart.cart_id));

      this.checkoutSuccess = true;
      this.paymentMethod = null;
      this.downpaymentProof = null;

      setTimeout(() => {
        this.checkoutSuccess = false;
        this.checkedItems = []; 
      }, 2000);
    } else {
      console.error('Checkout failed:', response.data.message);
    }
  } catch (error) {
    console.error('Checkout Error:', error);
  }
},







    handlePaymentSelection(paymentMethod) {
      this.paymentMethod = paymentMethod;
    },
    getCartItem(cartId) {
    return this.cart.find(cart => cart.cart_id === cartId) || {};
  },
    async getCart() {
      const id = sessionStorage.getItem("id");
      try {
        const [prod, user, shop] = await Promise.all([
          axios.get(`/getProd/${id}`),
          axios.get("/getData"),
          axios.get("/getShop"),
        ]);

        this.cart = prod.data.map(item => ({
          ...item,
          selected: false,
        }));

        this.data = user.data;
        this.prod_name = shop.data;
        this.prod_price = shop.data;
        this.prod_img = shop.data;
      } catch (error) {
        console.error("Error fetching cart:", error);
      }
    },

    getInfo(prod) {
  const shop = this.prod_name.find(shop => shop.shop_id === prod.shop_id) || {};
  console.log(`Product Name: ${shop.prod_name}, Price: ${shop.prod_price}`);
  return shop;
},

    getPrice(prod) {
      return this.prod_price.find(shop => shop.shop_id === prod.shop_id) || {};

    },
    getImg(prod) {
      return this.prod_img.find(shop => shop.shop_id === prod.shop_id) || {};

    },
    async deleteCart(delcart) {
      const confirmResult = await new Promise(resolve => {
        const confirmDialog = window.confirm("Do you want to DELETE this item?");
        resolve(confirmDialog);
        this.deleteSuccess = true;
      setTimeout(() => {
        this.deleteSuccess = false;
      }, 2000);
      });

      if (confirmResult) {
        try {
          await axios.post("del", {
            cart_id: delcart,
          });

          this.cart = this.cart.filter(item => item.cart_id !== delcart);
          this.deleteSuccess = true;
          setTimeout(() => {
            this.deleteSuccess = false;
          }, 2000);
        } catch (error) {
          console.error("Error deleting item:", error);
        }
      }
    },
    getMaxQuantity(cart) {
      const item = this.getInfo(cart);
      return item.prod_quantity || 0;
    },

    updateQuantity(cart, action) {
      if (action === 'increment' && cart.quantity < this.getMaxQuantity(cart)) {
        cart.quantity++;
      } else if (action === 'decrement' && cart.quantity > 1) {
        cart.quantity--;
      }

      this.updateTotalPrice(cart);
    },
    updateTotalPrice(cart) {
      const item = this.getInfo(cart);
      const newTotal = (item.prod_price || 0) * cart.quantity;

      axios.post("/updateCartQuantity", {
        cart_id: cart.cart_id,
        quantity: cart.quantity,
      })
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.error("Error updating quantity:", error);
        });
    },
    getTotal(prod) {
      const item = this.getInfo(prod);
      return (item.prod_price || 0) * prod.quantity;
    },

    calculateSubtotal() {
  const subtotal = this.checkedItems.reduce((total, cartId) => {
    const prod = this.cart.find(item => item.cart_id === cartId);
    if (prod) {
      const item = this.getInfo(prod);
      const itemTotal = (item.prod_price || 0) * prod.quantity;
      console.log(`Cart ID: ${cartId}, Product Name: ${item.prod_name}, Price: ${item.prod_price}, Item Total: ${itemTotal}`);
      return total + itemTotal;
    }
    return total;
  }, 0).toFixed(2);

  console.log('Selected Items:', this.checkedItems);

  return subtotal;
},

check(cartId) {
  const index = this.checkedItems.indexOf(cartId);

  if (index === -1) {
    this.checkedItems = [...this.checkedItems, cartId];
  } else {
    this.checkedItems = this.checkedItems.filter(item => item !== cartId);
  }
  console.log('Selected Items:', this.checkedItems);
},
  

    getcheckedItems() {
      return this.cart.filter(item => item.selected);
    },

  },


};
</script>

