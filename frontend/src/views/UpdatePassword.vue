<template>
  <div class="login-container">
    <div class="overlay">
      <div class="form-container">
        <div class="logo text-end">
          <img src="~@/assets/logo-login.png" alt="Eduardo's Resort Logo" />
        </div>
        <h2 class="login-title">Update Password</h2>
        <form @submit.prevent="updatePassword" class="form">
          <div class="form-floating mb-3">
            <input
              type="password"
              class="form-control"
              v-model="newPassword"
              required
              minlength="8"
              :class="{ 'is-invalid': newPasswordError }"
              placeholder="New Password"
            />
            <label for="newPassword" class="form-label">New Password</label>
            <div class="invalid-feedback">Password must be at least 8 characters.</div>
          </div>
          <div class="form-floating mb-3">
            <input
              type="password"
              class="form-control"
              v-model="confirmPassword"
              required
              minlength="8"
              :class="{ 'is-invalid': confirmPasswordError }"
              placeholder="Confirm Password"
            />
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <div class="invalid-feedback">Passwords do not match.</div>
          </div>
          <p class="alert alert-danger" v-if="errorMessage">{{ errorMessage }}</p>

          <button type="submit" class="btn btn-login w-100 mb-3">Update Password</button>

          <div class="text-center">
            <router-link to="/login" class="link">Back to Login</router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import router from '@/router';
import Notification from '@/components/Notification.vue';

export default {
  components: {
    Notification,
  },
  data() {
    return {
      newPassword: '',
      confirmPassword: '',
      errorMessage: '',
      successMessage: '',
    };
  },
  methods: {
    updatePassword() {
      if (this.newPassword !== this.confirmPassword) {
        this.errorMessage = 'Passwords do not match';
        return;
      }

      const data = {
        email: this.$route.query.email,
        newPassword: this.newPassword,
      };

      axios
        .post('/api/update-password', data)
        .then((response) => {
          if (response.data.message === 'Password updated successfully') {
            this.successMessage = 'Password updated successfully';
            router.push('/login');
          }
        })
        .catch((error) => {
          console.error(error);
          this.errorMessage = 'An error occurred. Please try again!';
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
