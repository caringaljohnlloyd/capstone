<template>
  <div class="login-container">
    <div class="overlay">
      <div class="form-container">
        <div class="logo text-end mb-4">
          <img src="~@/assets/logo-login.png" alt="Logo" class="logo-img" />
        </div>

        <h2 class="login-title">Sign Up</h2>
        
        <form @submit.prevent="register" ref="registerForm">
          <div class="form-row">
     
            <div class="form-floating mb-3 col">
              <input type="text" class="form-control" name="firstName" v-model="firstName" required placeholder=" " />
              <label for="firstName" class="form-label">First Name</label>
            </div>
        
        
            <div class="form-floating mb-3 col">
              <input type="text" class="form-control" name="lastName" v-model="lastName" required placeholder=" " />
              <label for="lastName" class="form-label">Last Name</label>
            </div>
          </div>

          <div class="form-row">
        
            <div class="form-floating mb-3 col">
              <input type="email" class="form-control" name="email" v-model="email" required placeholder=" " />
              <label for="email" class="form-label">Email address</label>
            </div>

         
            <div class="form-floating mb-3 col">
              <input type="number" class="form-control" name="number" v-model="number" required placeholder=" " />
              <label for="number" class="form-label">Mobile Number</label>
            </div>
          </div>

          <div class="form-row">

            <div class="form-floating mb-3 position-relative col">
              <input :type="showPassword ? 'text' : 'password'" class="form-control" name="password" v-model="password" required minlength="8" placeholder=" " />
              <label for="password" class="form-label">Password</label>
              <span class="password-toggle" @click="togglePassword('password')">
                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </span>
            </div>

   
            <div class="form-floating mb-3 position-relative col">
              <input :type="showConfirmPassword ? 'text' : 'password'" name="confirmpassword" id="confirmpassword" class="form-control" required v-model="confirmpassword" @input="checkMsg" placeholder=" " />
              <label for="confirmpassword" class="form-label">Confirm Password</label>
              <span class="password-toggle" @click="togglePassword('confirmPassword')">
                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </span>
            </div>
          </div>

          <div class="form-row">
  <div class="form-floating mb-3 col">
    <label for="islandGroup" class="label-island-group">Island Group</label>
    <select class="form-select" v-model="selectedIslandGroup" @change="fetchRegions" required>
      <option value="" disabled style="font-weight: bold;">Select Island Group:</option>
      <option v-for="group in islandGroups" :key="group.code" :value="group.code"> {{ group.name }}</option>
    </select>
  </div>

  <div v-if="selectedIslandGroup" class="form-floating mb-3 col">
    <label for="region" class="label-island-group">Region</label>
    <select class="form-select" v-model="selectedRegion" @change="fetchProvinces" required>
      <option value="" disabled style="font-weight: bold;">Select Region:</option>
      <option v-for="region in regions" :key="region.code" :value="region.code">{{ region.name }}</option>
    </select>
  </div>
</div>

<div class="form-row">
  <div v-if="selectedRegion" class="form-floating mb-3 col">
    <label for="province" class="label-island-group">Province</label>
    <select class="form-select" v-model="selectedProvince" @change="fetchCities" required>
      <option value="" disabled style="font-weight: bold;">Select Province:</option>
      <option v-for="province in provinces" :key="province.code" :value="province.code"> {{ province.name }}</option>
    </select>
  </div>

  <div v-if="selectedProvince" class="form-floating mb-3 col">
    <label for="city" class="label-island-group">City</label>
    <select class="form-select" v-model="selectedCity" @change="fetchBarangays" required>
      <option value="" disabled style="font-weight: bold;">Select City:</option>
      <option v-for="city in cities" :key="city.code" :value="city.code"> {{ city.name }}</option>
    </select>
  </div>
</div>

<div class="form-row">
  <div v-if="selectedCity" class="form-floating mb-3 col">
    <label for="barangay" class="label-island-group">Barangay</label>
    <select class="form-select" v-model="selectedBarangay" required @change="updateFullAddress">
      <option value="" disabled style="font-weight: bold;">Select Barangay</option>
      <option v-for="barangay in barangays" :key="barangay.code" :value="barangay.code">{{ barangay.name }}</option>
    </select>
  </div>
</div>


          <div class="alert" :class="{ 'alert-danger': !isValid || !passwordsMatch || errorMessage, 'alert-success': passwordsMatch }" v-if="showMessage">
            {{ errorMessage || message }}
          </div>

          <div class="alert alert-success" v-if="registered && !emailSent">Successfully registered! Please check your email for authentication.</div>
          <div class="alert alert-info" v-if="emailSent">Please check your email for authentication instructions.</div>

    
          <button type="submit" class="btn btn-login w-100 mb-3" :disabled="!passwordsMatch">Sign up</button>

    
          <div class="mt-3 text-center">
            <router-link to="/login" class="link">Already have an account?</router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>



<script>
import axios from 'axios';

export default {
  data() {
    return {
      firstName: "", 
      lastName: "", 
      name: "",
      email: "",
      password: "",
      confirmpassword: "",
      number: "",
      fullAddress: "", 
      showPassword: false,
      showConfirmPassword: false,
      showMessage: false,
      registered: false,
      emailSent: false,
      errorMessage: "", 
      islandGroups: [],
      regions: [],
      cities: [],
      barangays: [],
      provinces: [], 
      selectedIslandGroup: null,
      selectedRegion: null,
      selectedCity: null,
      selectedBarangay: null,
      selectedProvince: null,  
    };
  },
  computed: {
    passwordsMatch() {
      return this.password === this.confirmpassword;
    },
    isValid() {
      return this.password !== '' && this.confirmpassword !== '';
    },
    message() {
      return this.passwordsMatch ? 'Passwords match!' : 'Passwords do not match.';
    },
  },
  methods: {
    togglePassword(field) {
      if (field === 'password') {
        this.showPassword = !this.showPassword;
      } else if (field === 'confirmPassword') {
        this.showConfirmPassword = !this.showConfirmPassword;
      }
    },
    checkMsg() {
      this.dirty = this.confirmpassword !== '';
      this.showMessage = this.dirty;

      if (!this.dirty) {
        this.showMessage = false;
        this.errorMessage = ''; 
      }
    },
    updateFullAddress() {
      const islandGroup = this.islandGroups.find(group => group.code === this.selectedIslandGroup)?.name || '';
      const region = this.regions.find(region => region.code === this.selectedRegion)?.name || '';
      const province = this.provinces.find(province => province.code === this.selectedProvince)?.name || ''; // New line for province
      const city = this.cities.find(city => city.code === this.selectedCity)?.name || '';
      const barangay = this.barangays.find(barangay => barangay.code === this.selectedBarangay)?.name || '';
      this.fullAddress = `${islandGroup}, ${region}, ${province}, ${city}, ${barangay}`.trim().replace(/,+/g, ', '); // Updated address
    },
    async register() {
  try {
    const res = await axios.post("register", {
      name: `${this.firstName} ${this.lastName}`,
      email: this.email,
      password: this.password,
      number: this.number,
      address: this.fullAddress, 
    });

 
    this.name = "";
    this.firstName = "";
    this.Lastname = "";
    this.email = "";
    this.password = "";
    this.confirmpassword = "";
    this.number = "";
    this.fullAddress = ""; // Clear address
    this.selectedIslandGroup = null; // Clear selected island group
    this.selectedRegion = null; // Clear selected region
    this.selectedProvince = null; // Clear selected province
    this.selectedCity = null; // Clear selected city
    this.selectedBarangay = null; // Clear selected barangay
    this.cities = []; // Clear cities
    this.barangays = []; // Clear barangays
    this.provinces = []; // Clear provinces

    // Update state
    this.registered = true;
    this.emailSent = true; 
    this.showMessage = false;
    this.errorMessage = ""; 

    // Reset the form (this will clear any additional fields)
    this.$refs.registerForm.reset();

  } catch (error) {
    if (error.response && error.response.data && error.response.data.error) {
      this.errorMessage = error.response.data.error;
      setTimeout(() => {
        this.errorMessage = "";      
      }, 4000);
    }
  }
},
    async fetchIslandGroups() {
      try {
        const response = await axios.get('https://psgc.gitlab.io/api/island-groups.json');
        this.islandGroups = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Sort island groups alphabetically
      } catch (error) {
        console.error('Error fetching island groups:', error);
      }
    },
    async fetchRegions() {
      if (!this.selectedIslandGroup) return;
      try {
        const response = await axios.get(`https://psgc.gitlab.io/api/island-groups/${this.selectedIslandGroup}/regions.json`);
        this.regions = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Sort regions alphabetically
      } catch (error) {
        console.error('Error fetching regions:', error);
      }
    },
    async fetchCities() {
      if (!this.selectedProvince) return; // Ensure a province is selected
      try {
        const response = await axios.get(`https://psgc.gitlab.io/api/provinces/${this.selectedProvince}/cities-municipalities.json`);
        this.cities = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Sort cities alphabetically
      } catch (error) {
        console.error('Error fetching cities:', error);
      }
    },
    async fetchBarangays() {
      if (!this.selectedCity) return;
      try {
        const response = await axios.get(`https://psgc.gitlab.io/api/cities-municipalities/${this.selectedCity}/barangays.json`);
        this.barangays = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Sort barangays alphabetically
      } catch (error) {
        console.error('Error fetching barangays:', error);
      }
    },
    async fetchProvinces() {
      if (!this.selectedRegion) return;  // Ensure a region is selected
      try {
        const response = await axios.get(`https://psgc.gitlab.io/api/regions/${this.selectedRegion}/provinces.json`);
        this.provinces = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Sort provinces alphabetically
      } catch (error) {
        console.error('Error fetching provinces:', error);
      }
    }
  },
  mounted() {
    this.fetchIslandGroups(); // Fetch island groups when the component mounts
  }
};
</script>

<style scoped>
.login-container {
  background: url('~@/assets/background-image.png') center/cover no-repeat;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.overlay {
  background: rgba(0, 0, 0, 0.7);
  padding: 20px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-container {
  background: rgba(15, 23, 43, 0.7);
  padding: 40px;
  border-radius: 10px;
  width: 100%;
  max-width: 600px; /* Increase width to allow horizontal layout */
}

.logo-img {
  width: 150px; /* Adjust this value to control the width */
  height: auto; /* Maintain aspect ratio */
  align-items: right;
  justify-self: right;
}

.login-title {
  font-size: 2rem;
  color: #ffbb00;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 15px;
}

.form-floating .form-control {
  background-color: rgba(255, 255, 255, 0.2);
  color: #ddd;
  border: none;
  border-radius: 5px;
  width: 100%; /* Ensure inputs take full width */
}

.form-floating .form-control:focus {
  background-color: rgba(255, 255, 255, 0.3);
  color: #fff;
  box-shadow: none;
}

.form-floating label {
  color: #ccc;
}

.password-toggle {
  position: absolute;
  top: 10px;
  right: 15px;
  cursor: pointer;
  color: #999;
}

.password-toggle:hover {
  color: #666;
}

.alert-danger {
  color: #ff4444;
  background-color: transparent;
  border: none;
}

.btn-login {
  background-color: #fea116;
  border: none;
  color: white;
  cursor: pointer;
  border-radius: 5px;
  font-size: 14px;
  text-decoration: none;
  transition: background-color 0.3s, transform 0.3s;
  font-family: "Poppins", sans-serif;
  font-weight: 700;
  padding: 10px;
  justify-content: center;
  align-items: center;
}

.btn-login:hover {
  color: white;
  background-color: #0F172B;
  transform: scale(1.05);
  border: none;
}
.label-island-group {
  font-family: 'Arial', sans-serif; /* Font family */
  font-size: 1rem; /* Adjust size */
  font-weight: bold; /* Make it bold for better visibility */
  color: #ffffff; /* Set a white or high-contrast color */
  opacity: 1; /* Ensure it’s fully opaque */
  letter-spacing: 0.5px; /* Slightly space out letters */
  margin-bottom: 5px; /* Space below the label */
  text-shadow: 0 0 5px rgba(0.01, 0.01, 0.01, 0.01); /* Adds a shadow for better visibility */

  padding: 0 2px; /* Add some padding for the background */
  border-radius: 3px; /* Optional: rounded corners */
}



</style>
