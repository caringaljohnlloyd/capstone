<template>
  <div class="login-container">
    <div class="overlay">
      <div class="form-container">
        <div class="logo text-end">
          <img src="~@/assets/logo-login.png" alt="Eduardo's Resort Logo" />
        </div>
        <h2 class="login-title">Login</h2>

        <form @submit.prevent="login">
     
          <div class="form-floating mb-3">
            <input type="email" class="form-control" v-model="email" required placeholder="Email" />
            <label for="email" class="form-label">Email Address</label>
          </div>

       
          <div class="form-floating mb-3 position-relative">
            <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="password" required minlength="8" placeholder="Password" />
            <label for="password" class="form-label">Password</label>
            <span class="password-toggle" @click="togglePassword">
              <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </span>
          </div>

 
          <p class="alert alert-danger text-center" v-if="errorMessage">{{ errorMessage }}</p>


          <button type="submit" class="btn btn-login w-100 mb-3">Sign In</button>

    
          <div class="text-center">
            <router-link class="link" to="/register">Don't have an account yet?</router-link>
            <span class="mx-2">|</span>
            <router-link class="link" to="/reset-password">Forgot Password?</router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

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

.password-toggle {
  position: absolute;
  top: 50%;
  right: 15px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #ddd;
}

.password-toggle:hover {
  color: #ffbb00;
}

.btn-login {
    background-color: #fea116; 
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
  align-items: center;
  justify-content: center;
    transition: background-color 0.3s, transform 0.3s;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
  padding: 10px;  
  }
  .btn-login:hover {
    color: white;
    background-color: #0F172B;
    transform: scale(1.10);
    border:none;
  }

.alert-danger {
  color: #ff4444;
  background-color: transparent;
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

<script>
import axios from 'axios';
import router from '@/router'; 

export default {
  data() {
    return {
      email: '',
      password: '',
      showPassword: false,
      errorMessage: '',
    };
  },
  methods: {
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
    login() {
      const data = {
        email: this.email,
        password: this.password,
      };

      axios
        .post('/login', JSON.stringify(data), {
          headers: {
            'Content-Type': 'application/json',
          },
        })
        .then((response) => {
          console.log(response.data);

          if (response.data.message === 'Login successful') {
            sessionStorage.setItem("token", response.data.token);
            sessionStorage.setItem("id", response.data.id);
            sessionStorage.setItem("book_id", response.data.book_id);

            if (response.data.role === 'admin') {
              router.push('/admin2');
            } else {
              router.push('/user');
            }
          }
        })
        .catch((error) => {
          console.error(error);
          this.errorMessage = 'Invalid email or password, try again!';
          setTimeout(() => {
            this.errorMessage = '';
          }, 1000); 
        });
    },
  },
};
</script>
