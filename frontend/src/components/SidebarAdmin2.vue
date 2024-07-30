<template>
  <div :style="styles.sidebar">
    <h2 :style="styles.logo">Admin</h2>
    <ul :style="styles.menu">
      <li :style="styles.menuItem" class="fas fa-tachometer-alt">Dashboard</li>
      <li :style="styles.menuItem">Users</li>
      <li :style="styles.menuItem">Settings</li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      styles: {
        sidebar: {
          width: window.innerWidth > 768 ? '250px' : '100px',
          background: '#2c3e50',
          color: 'white',
          padding: '20px',
          boxSizing: 'border-box'
         
        },
        logo: {
          marginBottom: '40px',
          fontSize: window.innerWidth > 768 ? '24px' : '16px'
        },
        menu: {
          listStyle: 'none',
          padding: 0
        },
        menuItem: {
          padding: '10px 0',
          cursor: 'pointer',
          marginright: '10px'
        }
      }
    };
  },
  mounted() {
    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    handleResize() {
      this.styles.sidebar.width = window.innerWidth > 768 ? '250px' : '100px';
      this.styles.logo.fontSize = window.innerWidth > 768 ? '24px' : '16px';
    }
  }
};
</script>

