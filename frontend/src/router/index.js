import { createRouter, createWebHistory } from 'vue-router';
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
import Admin from '../views/Admin.vue';
import Admin2 from '../views/Admin2.vue';
import Analytics from '../views/Analytics.vue';
import Analytics2 from '../views/Analytics2.vue';
import Chat from '../views/Chat.vue';
import Contacts from '../views/Contacts.vue';
import TeamAdmin from '../views/TeamAdmin.vue';
import TeamAdmin2 from '../views/TeamAdmin2.vue';
import monitorusers from '../views/Calendar.vue';
import monitorusers2 from '../views/Calendar2.vue';
import Email_inbox from '../views/Email_inbox.vue';
import ForgotPassword from '../views/ForgotPassword.vue';
import UpdatePassword from '../views/UpdatePassword.vue';
import Cart from '../views/Cart.vue';
import AuditHistory from '@/views/AuditHistory.vue';
import Invoice from '@/components/Invoice.vue';
import Home from '../views/Home.vue';
import pos from '../views/Pos.vue';
import pos2 from '../views/Pos2.vue';
import packs3 from '@/components/packs.vue';
import packs4 from '@/components/pack4.vue';
import packs6 from '@/components/packs6.vue';
import SwimmingLesson from '@/components/SwimmingLesson.vue';
import AmenitiesAudit from '../views/AmenitiesAudit.vue';

const routes = [
  {
    path: '/',
    redirect: '/user'  // Redirect to user page
  },
  {
    path: '/user',
    component: Include
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
    path: '/packs/:packs',
    component: packs3,
    name: 'packs3',
    meta: { requiresAuth: true } // Require login for packs
  },
  {
    path: '/packs/:packs',
    component: packs4,
    name: 'packs4',
    meta: { requiresAuth: true } // Require login for packs
  },
  {
    path: '/packs/:packs',
    component: packs6,
    name: 'packs6',
    meta: { requiresAuth: true } // Require login for packs
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
    path: '/admin',
    component: Admin,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/admin2',
    component: Admin2,
    meta: { requiresAuth: true, role: 'admin2' }
  },
  {
    path: '/analytics',
    component: Analytics,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/analytics2',
    component: Analytics2,
    meta: { requiresAuth: true, role: 'admin2' }
  },
  {
    path: '/amenitiesAudit/:amenityId',
    name: 'AmenitiesAudit',
    component: AmenitiesAudit,
    meta: { requiresAuth: true, role: 'admin2' }

  },
  
  {
    path: '/chat',
    component: Chat,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/contacts',
    component: Contacts,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/teamadmin',
    component: TeamAdmin,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/teamadmin2',
    component: TeamAdmin2,
    meta: { requiresAuth: true, role: 'admin2' }
  },
  {
    path: '/monitorusers',
    component: monitorusers,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/monitorusers2',
    component: monitorusers2,
    meta: { requiresAuth: true, role: 'admin2' }
  },
  {
    path: '/pos',
    component: pos,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/pos2',
    component: pos2,
    meta: { requiresAuth: true, role: 'admin2' }
  },
  {
    path: '/email_inbox',
    component: Email_inbox,
    meta: { requiresAuth: true, role: 'admin' }
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
    meta: { requiresAuth: true, role: 'admin2' }
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

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const isLoggedin = checkUserLogin();
  const isAdmin = checkAdminRole();

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!isLoggedin) {
      // Redirect to login page if not logged in
      next('/login');
    } else {
      // Check if user has admin role if required
      if (to.meta.role === 'admin' && !isAdmin) {
        next('/user'); // Redirect to user landing page if not admin
      } else {
        next(); // Allow navigation if logged in and role matches
      }
    }
  } else {
    next(); // Allow navigation if no authentication is required
  }
});

function checkUserLogin() {
  const userToken = sessionStorage.getItem("token");
  return !!userToken;
}

function checkAdminRole() {
  const userRole = sessionStorage.getItem("role");
  return userRole === "admin";
}

export default router;
