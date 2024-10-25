<template>
    <div class="token-expired">
      <div class="container">
        <h1>Token Expired</h1>
        <p>
          Unfortunately, your verification token has expired. Please click the button below to request a new verification email.
        </p>
  
        <div v-if="errorMessage" class="alert alert-danger">
          {{ errorMessage }}
        </div>
  
        <div v-if="successMessage" class="alert alert-success">
          {{ successMessage }}
        </div>
  
        <form @submit.prevent="resendVerificationEmail">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input
              type="email"
              v-model="email"
              id="email"
              class="form-control"
              placeholder="Enter your email address"
              required
            />
          </div>
  
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading">Resending...</span>
            <span v-else>Resend Verification Email</span>
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        email: '',
        loading: false,
        errorMessage: '',
        successMessage: '',
      };
    },
    methods: {
      async resendVerificationEmail() {
        this.loading = true;
        this.errorMessage = '';
        this.successMessage = '';
  
        try {
          const response = await axios.post('/resendVerificationEmail', {
  email: this.email,

          });
  
          if (response.data.message === 'Verification email resent successfully.') {
            this.successMessage = 'A new verification email has been sent!';
          } else {
            this.errorMessage = response.data.message || 'An error occurred.';
          }
        } catch (error) {
          this.errorMessage = error.response?.data?.message || 'Failed to resend email.';
        } finally {
          this.loading = false;
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }
  
  h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #FEA116;
  }
  
  p {
    text-align: center;
    margin-bottom: 30px;
  }
  
  .btn-primary {
    background-color: #FEA116;
    border-color: #FEA116;
  }
  
  .alert {
    margin-top: 20px;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  input[type="email"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
  }
  </style>
    