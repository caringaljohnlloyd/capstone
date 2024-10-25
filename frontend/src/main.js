import { createApp } from 'vue';
import App from './App.vue';
import './registerServiceWorker';
import router from './router';
import axios from 'axios';
import 'bootstrap';
import 'flatpickr/dist/flatpickr.css'; // Updated import statement for flatpickr CSS
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faFacebook, faGoogle, faTwitter } from '@fortawesome/free-brands-svg-icons';

library.add(faFacebook, faGoogle, faTwitter);
axios.defaults.baseURL = "https://eduardos-resort.online/backend/"; //h

const app = createApp(App);
app.use(router);
app.mount('#app');
app.component('font-awesome-icon', FontAwesomeIcon);
