<template>
<div class="main-content">

  <div class="sidebar" :class="{ 'collapsed': isSidebarCollapsed }">
  
  <img :src="require('../assets/images/logo1.png.png')" alt="Mono" class="logo">
  <router-link to="/admin2">
    <i class="fa-solid fa-chart-simple"></i><span>Business Dashboard</span>
  </router-link>
  <details >
    <summary class="inventory-summary">
      <i  style="padding: 10px; margin: 0" class="fa-solid fa-table"></i>
      <span style="padding: 0; margin: 0" class="dropdown-icon">▾</span> 
      <span style="padding: 0; margin: 0">Inventory Options</span>
    </summary>
    <div class="dropdown-menu">
      <router-link to="/analytics2">
        <i style="padding-right: 20px;" class="fa-solid fa-house"></i ><span>Room Inventory</span>
      </router-link>
      <router-link to="/shopinventory">
        <i  style="padding-right: 20px;" class="fa-solid fa-shop"></i><span>Shop Inventory</span>
      </router-link>
      <router-link to="/cottagebooking">
        <i  style="padding-right: 20px;" class="fa-solid fa-shop"></i><span>Cottage Inventory</span>
      </router-link>
      <router-link to="/reservations">
        <i  style="padding-right: 20px;" class=" fa-solid fa-utensils"></i><span>Restaurant Reservations</span>
      </router-link>
    
      <router-link to="/enrollment">
        <i  style="padding-right: 20px;" class="fa-solid fa-person-swimming"></i><span>Enrollment Inventory</span>
      </router-link>
    </div>
  </details>
  
  <router-link to="/teamadmin2">
    <i class="fa-solid fa-people-group"></i><span>Team</span>
  </router-link>
  <router-link to="/monitorusers2">
    <i class="fas fa-user"></i><span>Users</span>
  </router-link>
  <router-link to="/pos2">
    <i  class="fa-solid fa-cart-plus"></i><span>POS</span>
  </router-link>
  <router-link to="/history">
    <i  class="fa-solid fa-cart-plus"></i><span>History</span>
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
          <h2 class="fa-solid fa-cart-plus"> POS</h2><br>

          <div class="row g-4 ">
            <div v-for="shop in shop" class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
              <div class="room-item shadow rounded overflow-hidden card-menu">
                <div class="position-relative">
                  <img class="custom-image" 
                  :src="`https://eduardos-resort.online/backend/backend/public/uploads/${shop.prod_img}`"  alt="Product"  />
                  <small
                    class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">
                    Php. {{ shop.prod_price }}
                  </small>
                </div>
                <div class="p-4 mt-2 " >
                  <div class="d-flex justify-content-between mb-3">
                    <h5 class="mb-0 text-dark">
                      {{ shop.prod_name }}
                    </h5>
                    <div class="ps-2">
                      <star-rating :initialRating="shop.rating"
                        @rating-selected="updateRating(shop.shop_id, $event)"></star-rating>
                    </div>
                  </div>
                  <div class="d-flex mb-3">
                    <small class="border-end me-3 pe-3">
                      Quantity: {{ shop.prod_quantity }}
                    </small>
                  </div>
                  <p class="text-body mb-3">
                    {{ shop.prod_desc }}
                  </p>
                  <div class="d-flex justify-content-between">
                    <div class="input-group">
                      <input type="number" class="form-control text-center" v-model="selectedQuantity" min="1" />
                    </div>
                    <button class="btn btn-add " @click="addCart(shop.shop_id)">
                      <i class="fa fa-shopping-cart add-cart"> Add to Cart </i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="successMessage" class="alert alert-primary mt-3" role="alert">
              {{ successMessage }}
            </div>
          </div>

         
          <div class="card card-default">
  <Notification v-if="deleteSuccess" :show="deleteSuccess" type="success" message="Deleted successfully!" />
  
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th class="text-center">Product Name</th>
          <th class="text-center">Quantity</th>
          <th class="text-center">Price</th>
          <th class="text-center">Total Price</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cart in cart" :key="cart.shop_id">
          <td class="align-middle">
            <div class="product-item">
              <div class="custom-control custom-checkbox" style="float: left; margin-right: 10px; margin-top: 10px;">
                <input type="checkbox" :id="'checkbox_' + cart.cart_id" v-model="checkedItems" :value="cart.cart_id" @click="check(cart.cart_id)" />
                <label class="custom-control-label" :for="'checkbox_' + cart.cart_id"></label>
              </div>
              <a><img class="img-fluid menu" style="width: 100%; max-width: 250px; height: 200px; margin-top: 5px;" :src="`https://eduardos-resort.online/backend/backend/public/uploads/${getImg(cart).prod_img}`"  alt="Product" /></a>
              <div class="product-info">
                <h4 class="product-title">{{ getInfo(cart).prod_name }}</h4>
              </div>
            </div>
          </td>

          <td class="align-middle text-center">
            <div class="count-input">
              <button @click="updateQuantity(cart, 'decrement')" class="btn btn-primary btn-option rounded-circle" :disabled="cart.quantity <= 1">
                <span class="fas fa-minus"></span>
              </button>
              <span class="quantity">{{ cart.quantity }}</span>
              <button @click="updateQuantity(cart, 'increment')" class="btn btn-primary btn-option rounded-circle" :disabled="cart.quantity >= getMaxQuantity(cart)">
                <span class="fas fa-plus"></span>
              </button>
            </div>
          </td>

          <td class="align-middle text-center text-lg text-medium">{{ getPrice(cart).prod_price }}</td>
          <td class="align-middle text-center text-lg text-medium">{{ getTotal(cart) }}</td>
          <td class="align-middle text-center">
            <button @click="deleteCart(cart.cart_id)" class="btn btn-danger delete-cart">
              <i class="fa fa-trash delete-cart"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="floating-container">
    <div class="container">
      <div class="shopping-cart-footer">
        <div class="column text-lg invoice-section sticky-column">
          <h4>Products to Pay:</h4>
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
            <h4>Total:</h4>
            <span class="text-medium">Php.{{ calculateSubtotal() }}</span>
          </div>
        </div>
        <div class="column checkout-button-section">
          <div class="shopping-cart-footer">
            <div class="column">
              <a class="btn btn-add" @click="checkout">Proceed to Checkout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column checkout-products-section">
    <h4>Checkout Products:</h4>
    <table class="table">
      <thead>
        <tr>
          <th class="text-center">Product Name</th>
          <th class="text-center">Quantity</th>
          <th class="text-center">Price</th>
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

  <Notification v-if="insufficientStockError" :show="insufficientStockError" type="error" message="Insufficient stock for one or more items" />
  <Notification v-if="notification.show" :show="notification.show" :type="notification.type" :message="notification.message" />
  <Notification v-if="checkoutSuccess" :show="checkoutSuccess" type="success" message="Checkout successful!" />
  <Notification v-if="checkoutError" :show="checkoutError" type="error" message="Please select items before proceeding with the checkout." />
</div>

       
        </div>
  
 
  <Notification :show="notification.show" :type="notification.type" :message="notification.message" />

      </div>
    </div>
  </div>

</template>

<script>
import axios from "axios";
import Notification from "@/components/Notification.vue";

export default {
  components: {

    Notification,

  },
  data() {
    return {
      isSidebarCollapsed: false,
            cartCheckedOutIds: [],
      checkedItems: [],
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
      shop: [],
      name: [],
      quantity: 1,
      selectedQuantity: 1, // default quantity
      notification: {
        show: false,
        type: "", // "success" or "error"
        message: "",
      },
    };
  },
  mounted() {
    this.getCart();
    this.getInfo();
    this.getPrice();
    this.getImg();
    this.getShop();
  },
  methods: {
    toggleSidebar() {
      this.isSidebarCollapsed = !this.isSidebarCollapsed;
    },
    getCartItem(cartId) {
      return this.cart.find((cart) => cart.cart_id === cartId) || {};
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
    async getCart() {
      const id = sessionStorage.getItem("id");
      try {
        const [prod, user, shop] = await Promise.all([
          axios.get(`/getProd/${id}`),
          axios.get("/getData"),
          axios.get("/getShop"),
        ]);

        this.cart = prod.data.map((item) => ({
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
      const shop =
        this.prod_name.find((shop) => shop.shop_id === prod.shop_id) || {};
      console.log(`Product Name: ${shop.prod_name}, Price: ${shop.prod_price}`);
      return shop;
    },

    getPrice(prod) {
      return (
        this.prod_price.find((shop) => shop.shop_id === prod.shop_id) || {}
      );
    },
    getImg(prod) {
      return this.prod_img.find((shop) => shop.shop_id === prod.shop_id) || {};
    },
    async deleteCart(delcart) {
      const confirmResult = await new Promise((resolve) => {
        const confirmDialog = window.confirm(
          "Do you want to DELETE this item?"
        );
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

          this.cart = this.cart.filter((item) => item.cart_id !== delcart);
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
      if (action === "increment" && cart.quantity < this.getMaxQuantity(cart)) {
        cart.quantity++;
      } else if (action === "decrement" && cart.quantity > 1) {
        cart.quantity--;
      }

      this.updateTotalPrice(cart);
    },
    updateTotalPrice(cart) {
      const item = this.getInfo(cart);
      const newTotal = (item.prod_price || 0) * cart.quantity;

      axios
        .post("/updateCartQuantity", {
          cart_id: cart.cart_id,
          quantity: cart.quantity,
        })
        .then((response) => {
          console.log(response.data);
        })
        .catch((error) => {
          console.error("Error updating quantity:", error);
        });
    },
    getTotal(prod) {
      const item = this.getInfo(prod);
      return (item.prod_price || 0) * prod.quantity;
    },

    calculateSubtotal() {
      const subtotal = this.checkedItems
        .reduce((total, cartId) => {
          const prod = this.cart.find((item) => item.cart_id === cartId);
          if (prod) {
            const item = this.getInfo(prod);
            const itemTotal = (item.prod_price || 0) * prod.quantity;
            console.log(
              `Cart ID: ${cartId}, Product Name: ${item.prod_name}, Price: ${item.prod_price}, Item Total: ${itemTotal}`
            );
            return total + itemTotal;
          }
          return total;
        }, 0)
        .toFixed(2);

      console.log("Selected Items:", this.checkedItems);

      return subtotal;
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

        const orderItems = this.checkedItems.map((cartId) => {
          const cart = this.cart.find((cart) => cart.cart_id === cartId);
          const shop_id = cart ? cart.shop_id : null;

          return {
            shop_id: shop_id,
            quantity: cart ? cart.quantity : 0,
            total_price: this.getTotal(cart),
          };
        });

        const orderData = {
          id: id,
          status: "pending",
          total_price: parseFloat(this.calculateSubtotal()),
          items: orderItems,
        };

        const response = await axios.post("checkout", orderData);

        if (response.status === 400) {
          console.warn("Insufficient stock for one or more items");
          this.notification = {
            show: true,
            type: "error",
            message: "Insufficient stock for one or more items",
          };
          setTimeout(() => {
            this.notification = {
              show: false,
              type: "",
              message: "",
            };
          }, 3000);
          return;
        }

        if (response.status === 200) {
          const invoice_id = response.data.invoice_id;

          const newlyCheckedOutProducts = this.cart.filter((cart) =>
            this.checkedItems.includes(cart.cart_id)
          );

          this.checkedOutProducts.push(...newlyCheckedOutProducts);

          this.cart = this.cart.filter(
            (cart) => !this.checkedItems.includes(cart.cart_id)
          );

          this.checkoutSuccess = true;
          setTimeout(() => {
            this.checkoutSuccess = false;
            this.checkedItems = [];
          }, 2000);
        } else {
          console.error("Checkout failed:", response.data.message);
        }
      } catch (error) {
        console.error("Checkout Error:", error);
      }
    },

    check(cartId) {
      const index = this.checkedItems.indexOf(cartId);

      if (index === -1) {
        this.checkedItems = [...this.checkedItems, cartId];
      } else {
        this.checkedItems = this.checkedItems.filter((item) => item !== cartId);
      }
      console.log("Selected Items:", this.checkedItems);
    },

    getcheckedItems() {
      return this.cart.filter((item) => item.selected);
    },
    updateRating(shop_id, rating) {
      this.submitRatingToBackend(shop_id, rating);
    },
    updateQuantity(amount) {
      // Update quantity based on the plus or minus button clicked
      this.quantity += amount;
      // Ensure quantity is at least 1
      if (this.quantity < 1) {
        this.quantity = 1;
      }
    },
    async submitRatingToBackend(shop_id, rating) {
      try {
        const response = await axios.post("/submit-rating", {
          shop_id,
          rating,
        });
        console.log(response.data);
      } catch (error) {
        console.error(error);
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
    async getRoom() {
      const r = await axios.get("/getRoom");
      this.room = r.data;
    },
    async getShop() {
      const s = await axios.get("/getShop");
      this.shop = s.data;
    },
    updateQuantity(product, increment) {
      product.selectedQuantity = (product.selectedQuantity || 0) + increment;

      if (product.selectedQuantity < 0) {
        product.selectedQuantity = 0;
      }
    },

    async addCart(shop_id, quantity) {
      try {
        const id = sessionStorage.getItem("id");
        const product = this.shop.find((item) => item.shop_id === shop_id);
        const quantity = this.selectedQuantity;

        if (product.prod_quantity >= quantity) {
          const response = await axios.post("getCart", {
            shop_id: shop_id,
            id: id,
            quantity: quantity,
          });
          this.getCart();
          this.showSuccessNotification("Product added to cart successfully");

          setTimeout(() => {
            this.successMessage = "";
          }, 2000);
        } else {
          this.showErrorNotification("Product is out of stock");
        }
      } catch (error) {
        console.error("Error adding to cart", error);
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

        const orderItems = this.checkedItems.map((cartId) => {
          const cart = this.cart.find((cart) => cart.cart_id === cartId);
          const shop_id = cart ? cart.shop_id : null;

          return {
            shop_id: shop_id,
            quantity: cart ? cart.quantity : 0,
            total_price: this.getTotal(cart),
          };
        });

        const orderData = {
          id: id,
          status: "pending",
          total_price: parseFloat(this.calculateSubtotal()),
          items: orderItems,
        };

        const response = await axios.post("checkoutpos", orderData);

        if (response.status === 400) {
          console.warn("Insufficient stock for one or more items");
          this.notification = {
            show: true,
            type: "error",
            message: "Insufficient stock for one or more items",
          };
          setTimeout(() => {
            this.notification = {
              show: false,
              type: "",
              message: "",
            };
          }, 3000);
          return;
        }

        if (response.status === 200) {
          const invoice_id = response.data.invoice_id;
          this.checkoutSuccess = true;
          setTimeout(() => {
            this.checkoutSuccess = false;
            this.cart = this.cart.filter(
              (cart) => !this.checkedItems.includes(cart.cart_id)
            );
            this.checkedItems = [];
            // this.$router.push({ name: 'invoice', params: { invoice_id: invoice_id } });
          }, 2000);
        } else {
          console.error("Checkout failed:", response.data.message);
        }
      } catch (error) {
        console.error("Checkout Error:", error);
      }
    },
  },
};
</script>



<style scoped>
  h1 {
    color: white;
    text-align: left;
    margin: 0;
  }

  i {
    padding-right: 38px;
    padding-left: 10px;
  }

  span {
    padding: 0;
    margin: 0;
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
    transition: width 0.5s;
  }

  .sidebar img.logo {
    width: 50px;
    height: auto;
    display: block;
    transition: width 0.5s;
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
    transition: background-color 0.5s;
  }

  .sidebar:hover {
    width: 200px;
  }

  @media (max-width: 768px) {
    .sidebar {
      width: 100%;
      height: auto;
      position: relative;
      overflow: hidden;
    }

    .sidebar img.logo {
      width: 80px;
    }

    .sidebar:hover {
      width: 100%;
    }

    .sidebar a, .sidebar router-link {
      justify-content: center;
    }
  }

  .main-content {
    margin-left: 93px;
    padding: 0;
    transition: margin-left 0.5s;
  }

  .sidebar:hover ~ .main-content {
    margin-left: 200px;
  }

  @media (max-width: 768px) {
    .main-content {
      margin-left: 0;
    }
  }

  .header {
    background-color: #0F172B;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1;
    margin-left: 0;
  }

  @media (max-width: 768px) {
    .header {
      flex-direction: column;
      align-items: flex-start;
    }
  }

  .logout-logo-btn {
    background-color: #FEA116;
    border: none;
    padding: 10px 15px;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: flex;
    align-items: center;
  }

  .logout-logo-btn i {
    margin-right: -30px;
    padding-left: 2px;
  }
  .form-control{

    font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-size: large;
  color:#090909;
  }

  .card {
    background-color: #f4f4f4;
    padding: 20px;
    border-radius: 8px;
  }

  .table-responsive {
    width: 100%;
    overflow-x: auto;
    margin-top: 50px;
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

  .card-body .modal-body, .card-body .alert, th, .modal-body {
    font-family: "Poppins", sans-serif;
  font-weight: 800;
  font-style: normal;
  font-size: large;
  color:#0F172B;
  }

  .table-product tbody td.action-buttons button, .table-product tbody td.action-buttons a, td {
    font-family: "Edu AU VIC WA NT Hand", cursive;
    margin-top: 20px;
    margin-bottom: 20px;
  }

  .table-product tbody td.action-buttons {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    white-space: nowrap;
    padding: 0;
    margin: 0;
  }

  .table-product tbody td {
    padding: 12px;
    margin: 0;
  }

  .table-product tbody tr {
    margin: 0;
    padding: 0;
  }

  .table-product tbody tr:hover {
    background-color: #ff9933;
  }

  .actions-header {
    text-align: center;
  }

  .table-product td {
    vertical-align: middle;
  }

  .active {
    background: #fea116;
  }

  @media (max-width: 768px) {
    .table-product th, .table-product td {
      padding: 8px;
    }
  }

  .btn-dark {
    background-color: #fea116;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Edu AU VIC WA NT Hand", cursive;
    padding: 10px;
    margin-bottom: 20px;
  }
  .border-end{
    font-family: "Poppins", sans-serif;
  font-weight: 800;
  font-style: normal;
  font-size: large;
  color:#0F172B;
  }
  .mb-0{
    font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-size: large;
  color:#0F172B;
  }

  small, h5, p {
    font-family: "Edu AU VIC WA NT Hand", cursive;
  }
  .text-body{
    font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-size: large;
  color:#0a0a0a;
  }

  .btn-dark:hover {
    background-color: #0F172B;
    transform: scale(1.10);
  }
  .align-middle{
    font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-size: large;
  color:#080808;
  }

  .btn-add {
    border-radius: 5px;
    background-color: #FEA116;
    color: white;
    padding: 5px;
    margin-left: 5px;
  }
  .text-medium{
    font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-style: normal;
  font-size: large;
  color:#b40404;
  }

  .add-cart {
    padding: 0;
    margin: 0;
  }
 
  .btn:hover {
    background-color: #0F172B;
    transform: scale(1.10);
  }

  .delete-cart {
    border: none;
    padding-right: 12px;
    padding-top: 7px;
    padding-bottom: 7px;
  }

  .card-menu:hover {
    transform: translateY(-30px);
  }

  .card-menu {
    transition: transform 0.2s;
  }

  .btn-option {
    width: 40px;
    height: 40px;
    border-radius: 70px;
    margin: 5px;
    border: none;
    padding:0;
    transition: background-color 0.3s, transform 0.3s;
  }
  .card-default{
    margin-top: 75px;
  }
  .card.card-big{
    margin: 20px;
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
  margin: 10px;
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
.custom-image {
    width: 100%;
    height: 330px;
    object-fit: cover;
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
    right: 20px;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
    color:#f8f8f9; /* Align to the right side */
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

  </style>