<template>
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 form-wrapper bg-dark shadow">
      <div class="container">
        
        <!-- Logo Section -->
        <div class="text-center mb-4">
          <img src="@/assets/img/logo.png" alt="Logo" class="logo-img" />
        </div>

        <form @submit.prevent="register" ref="registerForm">
          <!-- Name Input with Floating Label -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="name" v-model="name" required placeholder=" ">
            <label for="name" class="form-label">Name</label>
          </div>

          <!-- Email Input with Floating Label -->
          <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" v-model="email" required placeholder=" ">
            <label for="email" class="form-label">Email address</label>
          </div>

          <!-- Password Input with Floating Label and Eye Icon -->
          <div class="form-floating mb-3 position-relative">
            <input :type="showPassword ? 'text' : 'password'" class="form-control" name="password" v-model="password" required minlength="8" placeholder=" ">
            <label for="password" class="form-label">Password</label>
            <span class="password-toggle" @click="togglePassword('password')">
              <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </span>
          </div>

          <!-- Confirm Password Input with Floating Label and Eye Icon -->
          <div class="form-floating mb-3 position-relative">
            <input :type="showConfirmPassword ? 'text' : 'password'" name="confirmpassword" id="confirmpassword" class="form-control" required v-model="confirmpassword" @input="checkMsg" placeholder=" ">
            <label for="confirmpassword" class="form-label">Confirm Password</label>
            <span class="password-toggle" @click="togglePassword('confirmPassword')">
              <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </span>
          </div>

          <!-- Number Input with Floating Label -->
          <div class="form-floating mb-3">
            <input type="number" class="form-control" name="number" v-model="number" required placeholder=" ">
            <label for="number" class="form-label">Mobile Number</label>
          </div>

          <!-- Island Group Select Dropdown -->
          <div class="form-floating mb-3">
            <select class="form-select" v-model="selectedIslandGroup" @change="fetchRegions" required>
              <option value="" disabled>Select Island Group</option>
              <option v-for="group in islandGroups" :key="group.code" :value="group.code">
                {{ group.name }}
              </option>
            </select>
            <label for="islandGroup" class="form-label">Island Group</label>
          </div>

          <!-- Region Select Dropdown (conditionally rendered) -->
          <div v-if="selectedIslandGroup" class="form-floating mb-3">
            <select class="form-select" v-model="selectedRegion" @change="fetchProvinces" required>
              <option value="" disabled>Select Region</option>
              <option v-for="region in regions" :key="region.code" :value="region.code">
                {{ region.name }}
              </option>
            </select>
            <label for="region" class="form-label">Region</label>
          </div>

          <!-- Province Select Dropdown (conditionally rendered) -->
          <div v-if="selectedRegion" class="form-floating mb-3">
            <select class="form-select" v-model="selectedProvince" @change="fetchCities" required>
              <option value="" disabled>Select Province</option>
              <option v-for="province in provinces" :key="province.code" :value="province.code">
                {{ province.name }}
              </option>
            </select>
            <label for="province" class="form-label">Province</label>
          </div>

          <!-- City Select Dropdown (conditionally rendered) -->
          <div v-if="selectedProvince" class="form-floating mb-3">
            <select class="form-select" v-model="selectedCity" @change="fetchBarangays" required>
              <option value="" disabled>Select City</option>
              <option v-for="city in cities" :key="city.code" :value="city.code">
                {{ city.name }}
              </option>
            </select>
            <label for="city" class="form-label">City</label>
          </div>

          <!-- Barangay Select Dropdown (conditionally rendered) -->
          <div v-if="selectedCity" class="form-floating mb-3">
            <select class="form-select" v-model="selectedBarangay" required @change="updateFullAddress">
              <option value="" disabled>Select Barangay</option>
              <option v-for="barangay in barangays" :key="barangay.code" :value="barangay.code">
                {{ barangay.name }}
              </option>
            </select>
            <label for="barangay" class="form-label">Barangay</label>
          </div>

          <!-- Password Match Feedback -->
          <div class="alert" :class="{ 'alert-danger': !isValid || !passwordsMatch || errorMessage, 'alert-success': passwordsMatch }" v-if="showMessage">{{ errorMessage || message }}</div>

          <!-- Success Message -->
          <div class="alert alert-success" v-if="registered && !emailSent">Successfully registered! Please check your email for authentication.</div>

          <!-- Email Sent Message -->
          <div class="alert alert-info" v-if="emailSent">Please check your email for authentication instructions.</div>

          <!-- Submit Button with Hover Effect -->
          <button type="submit" class="btn btn-primary mx-auto w-100 mb-3" :disabled="!passwordsMatch">Sign up</button>

          <!-- Links -->
          <div class="mt-3 text-center">
            <router-link to="/login">Already have an account</router-link>
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
      name: "",
      email: "",
      password: "",
      confirmpassword: "",
      number: "",
      fullAddress: "", // Full address
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
      provinces: [],  // New data property for provinces
      selectedIslandGroup: null,
      selectedRegion: null,
      selectedCity: null,
      selectedBarangay: null,
      selectedProvince: null,  // New data property for selected province
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
      name: this.name,
      email: this.email,
      password: this.password,
      number: this.number,
      address: this.fullAddress, // Send the full address
    });

    // Clear form fields
    this.name = "";
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
@media (max-width: 768px) {
  .logo-img {
    width: 100px; /* Smaller width for mobile screens */
  }
}
.logo-img {
  width: 150px; /* Adjust this value to control the width */
  height: auto; /* Maintain aspect ratio */
  margin: 0 auto; /* Center horizontally */
  display: block; /* Ensure it's centered within the parent */
}

/* Optional: Add margin or padding adjustments if needed */
.text-center.mb-4 {
  padding: 10px 0; /* Adjust padding around the logo */
}

</style>
