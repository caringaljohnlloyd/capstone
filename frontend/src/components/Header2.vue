<template>
  <div :style="styles.header">
    <h1 :style="styles.title">Dashboard</h1>
  </div>
</template>

<script>
export default {
  data() {
    return {
      styles: {
        header: {
          background: '#34495e',
          color: 'white',
          padding: '10px 20px',
          boxSizing: 'border-box'
        },
        title: {
          margin: 0
        }
      }
    };
  }
};
</script>
