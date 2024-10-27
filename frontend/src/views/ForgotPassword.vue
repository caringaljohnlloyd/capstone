<template>
  <div class="login-container">
    <div class="overlay">
      <div class="form-container">
        <div class="logo text-end">
          <img src="~@/assets/logo-login.png" alt="Eduardo's Resort Logo" />
        </div>
        <h2 class="login-title">Forgot Password</h2>
        <form @submit.prevent="resetPassword">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" v-model="email" required placeholder="Email" />
            <label for="email" class="form-label">Email address</label>
          </div>

          <p class="alert alert-danger text-center">{{ errorMessage }}</p>

          <button type="submit" class="btn btn-login w-100 mb-3">Reset Password</button>

          <div class="text-center">
            <router-link class="link" to="/login">Remembered your password? Sign in</router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
  
<script>
import axios from 'axios';
import router from '@/router';

export default {
  data() {
    return {
      email: '',
      errorMessage: '',
    };
  },
  methods: {
    resetPassword() {
      const data = {
        email: this.email,
      };

      axios
        .post('/api/reset-password', data) 
        .then((response) => {
          if (response.data.message === 'Password reset successful') {
            router.push({
              path: '/update-password',
              query: { email: this.email },
            });
          }
        })
        .catch((error) => {
          console.error(error);
          if (error.response && error.response.status === 404) {
            this.errorMessage = 'Email not found. Please check and try again.';
          } else {
            this.errorMessage = 'An error occurred. Please try again!';
          }
        });
    },
  },
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
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-container {
  background: rgba(15, 23, 43, 0.7);
  padding: 40px;
  border-radius: 10px;
  width: 100%;
  max-width: 400px;
}

.logo img {
  width: 120px;
  margin-bottom: 10px;
}

.login-title {
  font-size: 2rem;
  color: #ffbb00;
  font-weight: bold;
  text-align: center;
  margin-bottom: 20px;
}

.form-floating .form-control {
  background-color: rgba(255, 255, 255, 0.2);
  color: #ddd;
  border: none;
  border-radius: 5px;
}

.form-floating .form-control:focus {
  background-color: rgba(255, 255, 255, 0.3);
  color: #fff;
  box-shadow: none;
}

.form-floating label {
  color: #ccc;
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
  transform: scale(1.10);
  border: none;
}

.link {
  color: #ddd;
  font-size: 0.9rem;
}

.link:hover {
  color: #ffbb00;
}
</style>