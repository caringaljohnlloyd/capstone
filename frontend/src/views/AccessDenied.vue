<template>
    <div class="access-denied">
      <h1>Access Denied</h1>
      <p>You do not have permission to access this page.</p>
      <router-link to="/login" class="btn">Login</router-link>
      <router-link to="/" class="btn">Home</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AccessDenied',
  };
  </script>
  
  <style scoped>
  .access-denied {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh; /* Full height of the viewport */
    text-align: center;
  }
  
  h1 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #e74c3c; /* Red color for emphasis */
  }
  
  p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
  }
  
  .btn {
    margin: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    background-color: #3498db; /* Blue color */
    color: white;
    text-decoration: none;
    transition: background-color 0.3s;
  }
  
  .btn:hover {
    background-color: #2980b9; /* Darker blue on hover */
  }
  </style>
  