import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import IndexPage from '../views/IndexPage.vue';
import AboutView from '../views/AboutView.vue';
import HomeView from '../views/HomeView.vue';
import LoginForm from '../views/LoginForm.vue';
import register from '../components/register.vue';
import Room from '../views/Room.vue';
import Shop from '../views/Shop.vue';
import Include from '../views/Include.vue'; // The user landing page
import ContactView from '../views/ContactView.vue';
import Service from '../views/Service.vue';
import Table from '../views/Table.vue';
import Booking from '../views/Booking.vue';
import Team from '../views/Team.vue';
import Testimonial from '../views/Testimonial.vue';
import Admin2 from '../views/Admin2.vue';
import Analytics2 from '../views/Analytics2.vue';
import TeamAdmin2 from '../views/TeamAdmin2.vue';
import monitorusers2 from '../views/Calendar2.vue';
import ForgotPassword from '../views/ForgotPassword.vue';
import UpdatePassword from '../views/UpdatePassword.vue';
import Cart from '../views/Cart.vue';
import AuditHistory from '@/views/AuditHistory.vue';
import Invoice from '@/components/Invoice.vue';
import Home from '../views/Home.vue';
import pos2 from '../views/Pos2.vue';
import SwimmingLesson from '@/components/SwimmingLesson.vue';
import AmenitiesAudit from '../views/AmenitiesAudit.vue';
import TokenExpired from '../views/token-expired.vue';
import AccessDenied from '../views/AccessDenied.vue'; // Import the Access Denied component


const routes = [
  {
    path: '/',
    redirect: '/user'  // Redirect to user page
  },
  {
    path: '/auth/access',
    component: AccessDenied,
  },
  {
    path: '/user',
    component: Include
  },
  {
    path: '/token-expired',
    name: 'TokenExpired',
    component: TokenExpired,
  },
  {
    path: '/login',
    name: 'LoginForm',
    component: LoginForm
  },
  {
    path: '/insert',
    component: IndexPage
  },
  {
    path: '/about',
    component: AboutView
  },
  {
    path: '/service',
    component: Service
  },
  {
    path: '/rooms/total_pax/:packs?',
    name: 'RoomsWithTotalPax',
    component: Room,
    meta: { requiresAuth: true } // Require login for rooms
  },
  {
    path: '/shop',
    component: Shop
  },
  {
    path: '/contact',
    component: ContactView
  },
  {
    path: '/table',
    component: Table
  },
  {
    path: '/swimminglesson',
    component: SwimmingLesson
  },
  {
    path: '/booking/:id',
    component: Booking,
    name: 'booking',
    meta: { requiresAuth: true } // Require login for booking actions
  },
  {
    path: '/team',
    component: Team
  },
  {
    path: '/testimonial',
    component: Testimonial
  },
  {
    path: '/shopcart',
    component: Cart,
    meta: { requiresAuth: true } // Require login for accessing the shopping cart
  },
  {
    path: '/register',
    component: register
  },
  {
    path: '/admin2',
    component: Admin2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },
  
  {
    path: '/analytics2',
    component: Analytics2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },
  {
    path: '/amenitiesAudit/:amenityId',
    name: 'AmenitiesAudit',
    component: AmenitiesAudit,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },
  {
    path: '/teamadmin2',
    component: TeamAdmin2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },

  {
    path: '/monitorusers2',
    component: monitorusers2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },

  {
    path: '/pos2',
    component: pos2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },
  {
    path: '/reset-password',
    component: ForgotPassword
  },
  {
    path: '/update-password',
    component: UpdatePassword
  },
  {
    path: '/audit-history/:shopId',
    name: 'auditHistory',
    component: AuditHistory,
    props: true,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] } // Allow both admin and admin2
  },
  {
    path: '/getInvoices/:invoice_id',
    name: 'invoice',
    component: Invoice,
    meta: { requiresAuth: true }
  },
  {
    path: '/home',
    component: Home
  }
];

const userType = { value: null }; // This will store the user's role from the backend

// Fetch the user's role and other details from the backend using the `id`
const fetchUserData = async () => {
  try {
    const id = sessionStorage.getItem("id"); // Use "id" directly from sessionStorage
    const response = await axios.get(`/fetchUser/${id}`); // API call to get user data
    console.log('API Response:', response.data);

    // Assign user role from the response
    userType.value = response.data.role; // Set user role (assumed to be "role" in response)

    // Log the assigned user role
    console.log('Assigned user role:', userType.value);
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
};


const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

router.beforeEach(async (to, from, next) => {
  const isLoggedin = checkUserLogin(); // Check if the user is logged in
  console.log(`User login status: ${isLoggedin}`);
  console.log(`Attempting to access: ${to.path}`);

  // Check if the route requires authentication
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    console.log('Route requires authentication');

    if (isLoggedin) {
      console.log('User is authenticated. Proceeding to fetch user data.');
      
      // Fetch the user data (role) from the backend
      await fetchUserData();
      const role = userType.value; // Get the user role
      console.log(`User role: ${role}`);

      const allowedRoles = to.meta.roles || []; // Allowed roles for the route
      console.log(`Allowed roles for this route: ${allowedRoles}`);

      // Check if the user's role is allowed to access the route
      if (allowedRoles.length === 0 || allowedRoles.includes(role)) {
        next(); // Proceed if the user has access
      } else {
        console.log('User does not have the required role. Redirecting to unauthorized page.');
        next('/auth/access'); // Redirect to unauthorized page
      }
    } else {
      console.log('User is not authenticated. Redirecting to login.');
      next("/auth/login"); // Redirect to login page
    }
  } else {
    console.log('No authentication required. Proceeding to route.');
    next(); // Proceed if the route does not require authentication
  }
});


// Function to check if the user is logged in (token exists in sessionStorage)
function checkUserLogin() {
  const userToken = sessionStorage.getItem('token'); // Check for token
  const isLoggedIn = !!userToken; // Convert to boolean
  console.log(`User login status: ${isLoggedIn}`);
  return isLoggedIn;
}

export default router;
