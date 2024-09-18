<template>
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 mt-5 pt-3 pb-3 form-wrapper bg-dark shadow">
      <div class="container">
        <h3 class="text-primary text-center mb-4">Register</h3>
        <hr />

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

          <!-- Address Input with Floating Label -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="address" v-model="address" required placeholder=" ">
            <label for="address" class="form-label">Barangay/City/Province</label>
          </div>

          <!-- Number Input with Floating Label -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="number" v-model="number" required placeholder=" ">
            <label for="number" class="form-label">Number</label>
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
      address: "",
      number: "",
      showPassword: false,
      showConfirmPassword: false, // State to control confirm password visibility
      showMessage: false,
      registered: false,
      emailSent: false,
      errorMessage: "", 
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
      if (this.passwordsMatch) {
        return 'Passwords match!';
      } else {
        return 'Passwords do not match.';
      }
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
    async register() {
      try {
        const res = await axios.post("register", {
          name: this.name,
          email: this.email,
          password: this.password,
          confirmpassword: this.confirmpassword,
          address: this.address,
          number: this.number,
        });

        // Clear form fields
        this.name = "";
        this.email = "";
        this.password = "";
        this.confirmpassword = "";
        this.address = "";
        this.number = "";

        // Update state
        this.registered = true;
        this.emailSent = true; 
        this.showMessage = false;
        this.errorMessage = ""; 

        // Reset the form
        this.$refs.registerForm.reset();

        // Emit event or handle additional logic if needed
        this.$emit('data-saved');
        
      } catch (error) {
        if (error.response && error.response.data && error.response.data.error) {
          this.errorMessage = error.response.data.error;
          setTimeout(() => {
            this.errorMessage = "";      
          }, 4000);
        }
      }
    }
  }
};
</script>

<style scoped>
@import '@/assets/css/bootstrap.min.css';
@import '@/assets/css/style.css';

.form-wrapper {
  border-radius: 10px;
  transition: box-shadow 0.3s ease-in-out;
}

.form-wrapper:hover {
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.alert-danger {
  color: #dc3545;
  border-color: #dc3545;
  transition: opacity 0.3s ease-in-out;
}

.text-muted {
  color: #6c757d;
}

.password-toggle {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #6c757d;
}

.password-toggle:hover {
  color: #007bff;
}
</style>

