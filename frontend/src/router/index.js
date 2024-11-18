import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

// Import only frequently-used components eagerly
import AccessDenied from '../views/AccessDenied.vue';
import TokenExpired from '../views/token-expired.vue';
import ForgotPassword from '../views/ForgotPassword.vue';
import UpdatePassword from '../views/UpdatePassword.vue';
import Include from '../views/Include.vue'; // User landing page

// Lazy-load components to reduce initial bundle size
const IndexPage = () => import('../views/IndexPage.vue');
const AboutView = () => import('../views/AboutView.vue');
const HomeView = () => import('../views/HomeView.vue');
const LoginForm = () => import('../views/LoginForm.vue');
const Register = () => import('../components/register.vue');
const Room = () => import('../views/Room.vue');
const Shop = () => import('../views/Shop.vue');
const Service = () => import('../views/Service.vue');
const Table = () => import('../views/Table.vue');
const Booking = () => import('../views/Booking.vue');
const Team = () => import('../views/Team.vue');
const Testimonial = () => import('../views/Testimonial.vue');
const Admin2 = () => import('../views/Admin2.vue');
const Analytics2 = () => import('../views/Analytics2.vue');
const TeamAdmin2 = () => import('../views/TeamAdmin2.vue');
const MonitorUsers2 = () => import('../views/Calendar2.vue');
const Cart = () => import('../views/Cart.vue');
const AuditHistory = () => import('@/views/AuditHistory.vue');
const Invoice = () => import('@/components/Invoice.vue');
const Home = () => import('../views/Home.vue');
const Pos2 = () => import('../views/Pos2.vue');
const SwimmingLesson = () => import('@/components/SwimmingLesson.vue');
const AmenitiesAudit = () => import('../views/AmenitiesAudit.vue');
const ShopInventory = () => import('../views/shopinventory.vue');
const CottageBooking = () => import('../views/cottagebooking.vue');
const Reservations = () => import('../views/reservations.vue');
const Enrollment = () => import('../views/enrollment.vue');
const history = () => import('../views/history.vue');


const routes = [
  { path: '/', redirect: '/user' },
  { path: '/auth/access', component: AccessDenied },
  { path: '/user', component: Include },
  { path: '/token-expired', name: 'TokenExpired', component: TokenExpired },
  { path: '/login', name: 'LoginForm', component: LoginForm },
  { path: '/reset-password', component: ForgotPassword },
  { path: '/update-password', component: UpdatePassword },
  { path: '/about', component: AboutView },
  { path: '/service', component: Service },
  { path: '/rooms/total_pax/:packs?', name: 'RoomsWithTotalPax', component: Room, meta: { requiresAuth: true } },
  { path: '/shop', component: Shop },
  { path: '/table', component: Table },
  { path: '/swimminglesson', component: SwimmingLesson },
  { path: '/booking/:id', name: 'booking', component: Booking, meta: { requiresAuth: true } },
  { path: '/team', component: Team },
  { path: '/testimonial', component: Testimonial },
  { path: '/shopcart', component: Cart, meta: { requiresAuth: true } },
  { path: '/register', component: Register },
  {
    path: '/admin2',
    component: Admin2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/enrollment',
    component: Enrollment,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/reservations',
    component: Reservations,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/shopinventory',
    component: ShopInventory,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/cottagebooking',
    component: CottageBooking,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/analytics2',
    component: Analytics2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/amenitiesAudit/:amenityId',
    name: 'AmenitiesAudit',
    component: AmenitiesAudit,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/teamadmin2',
    component: TeamAdmin2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/monitorusers2',
    component: MonitorUsers2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/pos2',
    component: Pos2,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/history',
    component: history,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/audit-history/:shopId',
    name: 'auditHistory',
    component: AuditHistory,
    props: true,
    meta: { requiresAuth: true, roles: ['admin', 'admin2'] },
  },
  {
    path: '/getInvoices/:invoice_id',
    name: 'invoice',
    component: Invoice,
    meta: { requiresAuth: true },
  },
  { path: '/home', component: Home },
];

const userType = { value: null };

const fetchUserData = async () => {
  if (userType.value) return; // Only fetch if the role hasn't been set
  try {
    const id = sessionStorage.getItem("id");
    const { data } = await axios.get(`/fetchUser/${id}`);
    userType.value = data.role;
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
};

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const isLoggedin = Boolean(sessionStorage.getItem('token'));

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (isLoggedin) {
      await fetchUserData();
      const role = userType.value;
      const allowedRoles = to.meta.roles || [];

      if (!allowedRoles.length || allowedRoles.includes(role)) {
        next();
      } else {
        next('/auth/access');
      }
    } else {
      next('/auth/login');
    }
  } else {
    next();
  }
});

export default router;
