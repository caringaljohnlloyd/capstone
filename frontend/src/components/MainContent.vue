<template>
  <div :style="styles.mainContent">
    <div :style="styles.card">
      <h2>Welcome to the Admin Dashboard</h2>
      <p>Manage your data here.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      styles: {
        mainContent: {
          flexGrow: 1,
          padding: '20px',
          boxSizing: 'border-box'
        },
        card: {
          background: 'white',
          padding: '20px',
          borderRadius: '5px',
          boxShadow: '0 2px 5px rgba(0,0,0,0.1)'
        }
      }
    };
  }
};
</script>
