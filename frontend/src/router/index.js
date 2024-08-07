import { createRouter, createWebHistory } from 'vue-router'
import IndexPage from '../views/IndexPage.vue'
import AboutView from '../views/AboutView.vue'
import HomeView from '../views/HomeView.vue'
import LoginForm from '../views/LoginForm.vue'
import register from '../components/register.vue'
import Room from '../views/Room.vue'
import Shop from '../views/Shop.vue'
import Include from '../views/Include.vue'
import ContactView from '../views/ContactView.vue'
import Service from '../views/Service.vue'
import Table from '../views/Table.vue'
import Booking from '../views/Booking.vue'
import Team from '../views/Team.vue'
import Testimonial from '../views/Testimonial.vue'
import Admin from '../views/Admin.vue'
import Admin2 from '../views/Admin2.vue'
import Analytics from '../views/Analytics.vue'
import Analytics2 from '../views/Analytics2.vue'
import Chat from '../views/Chat.vue'
import Contacts from '../views/Contacts.vue'
import TeamAdmin from '../views/TeamAdmin.vue'
import TeamAdmin2 from '../views/TeamAdmin2.vue'
import monitorusers from '../views/Calendar.vue'
import monitorusers2 from '../views/Calendar2.vue'
import Email_inbox from '../views/Email_inbox.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import UpdatePassword from '../views/UpdatePassword.vue'
import Cart from '../views/Cart.vue'
import AuditHistory from '@/views/AuditHistory.vue';
import Invoice from '@/components/Invoice.vue';
import Home from '../views/Home.vue'
import pos from '../views/Pos.vue'
import pos2 from '../views/Pos2.vue'
import packs3 from '@/components/packs.vue'
import packs4 from '@/components/pack4.vue'
import packs6 from '@/components/packs6.vue'
import SwimmingLesson from '@/components/SwimmingLesson.vue'




const routes = [
  {
    path: '/',
    name: LoginForm,
    component: LoginForm
  },
  {
    path: '/insert',
    component: IndexPage,
    meta: { requiresAuth: true, role: 'user' }

  },
  {
    path: '/about',
    component: AboutView,
    meta: { requiresAuth: true, role: 'user' }

  },
  {
    path: '/service',
    component: Service,
    meta: { requiresAuth: true, role: 'user' }


  },
  {
    path: '/rooms/total_pax/:packs?' , 
    name: 'RoomsWithTotalPax',
    component: Room,
    meta: { requiresAuth: true, role: 'user' }
  },
  
  
{
  path: '/shop',
  component: Shop,
  meta: { requiresAuth: true, role: 'user' }

},
{
  path: '/user',
  component: Include, 
  meta: { requiresAuth: true, role: 'user' }
},
{
  path: '/contact',
  component: ContactView,
  meta: { requiresAuth: true, role: 'user' }

},
{
  path: '/table',
  component: Table,
  meta: { requiresAuth: true, role: 'user' }

},
{
  path: '/swimminglesson',
  component: SwimmingLesson,
  meta: { requiresAuth: true, role: 'user' }

},
{
  path: '/booking/:id', 
  component: Booking,
  name:'booking',
  meta: { requiresAuth: true, role: 'user' }
},
{
  path: '/packs/:packs', 
  component: packs3,
  name:'packs3',
  meta: { requiresAuth: true, role: 'user' }
},
{
  path: '/packs/:packs', 
  component: packs4,
  name:'packs4',
  meta: { requiresAuth: true, role: 'user' }
},
{
  path: '/packs/:packs', 
  component: packs6,
  name:'packs6',
  meta: { requiresAuth: true, role: 'user' }
},
{
  path: '/team',
  component: Team,
  meta: { requiresAuth: true, role: 'user' }

},
{
  path: '/testimonial',
  component: Testimonial,
  meta: { requiresAuth: true, role: 'user' }

},

{
  path: '/shopcart',
  component: Cart,
  meta: { requiresAuth: true, role: 'user' }

},
{
  path: '/register',
  component: register,

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
  meta: { requiresAuth: true, role: 'admin' }

},
{
  path: '/monitorusers',
  component: monitorusers,
  meta: { requiresAuth: true, role: 'admin' }

},
{
  path: '/monitorusers2',
  component: monitorusers2,
  meta: { requiresAuth: true, role: 'admin' }

},
{
  path: '/pos',
  component: pos,
  meta: { requiresAuth: true, role: 'admin' }

},
{
  path: '/pos2',
  component: pos2,
  meta: { requiresAuth: true, role: 'admin' }

},
{
  path: '/email_inbox',
  component: Email_inbox,
  meta: { requiresAuth: true, role: 'admin' }

},
{
path: '/reset-password',
component: ForgotPassword,

},
{
  path: '/update-password',
  component: UpdatePassword,
},
{ 
   path: '/audit-history/:shopId',
    name: 'auditHistory',
    component: AuditHistory, 
    props: true, 
        meta: { requiresAuth: true, role: 'admin' }

},
{
  path: '/getInvoices/:invoice_id',
  name: 'invoice',
  component: Invoice, 
  meta: { requiresAuth: true },
},
{
  path: '/home',
  component: Home,


},
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})
router.beforeEach((to, from, next) => {
  const isLoggedin = checkUserLogin();
  const isAdmin = checkAdminRole();

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (isLoggedin) {
      if (isAdmin && to.path !== '/admin') {
        next('/admin');
      } else {
        next();
      }
    } else {
      next("/");
    }
  } else {
    next();
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

export default router



