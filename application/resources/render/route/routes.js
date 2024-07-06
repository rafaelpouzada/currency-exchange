import LandingPage from '../views/LandingPage';
import LoginForm from "../views/LoginForm.vue";
import RegisterForm from '../views/RegisterForm';
import QuotationList from '../views/admin/QuotationList';
import AdminComponent from '../views/components/AdminComponent.vue';
import CreateQuotation from "../views/admin/CreateQuotation.vue";

export default {
  routes: [
    {
      path: '/',
      component: LandingPage,
      meta: { requiresAuth: false },
    },
    {
      path: '/login',
      name: 'login',
      component: LoginForm,
      meta: { requiresAuth: false },
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterForm,
      meta: { requiresAuth: false },
    },
    {
      path: '/admin',
      component: AdminComponent,
      meta: { requiresAuth: true },
      children: [
        {
            path: 'home',
            component: QuotationList,
            name: 'admin.home',
            meta: { requiresAuth: true },
        },
        {
            path: 'quotation',
            component: CreateQuotation,
            name: 'admin.quotation',
            meta: { requiresAuth: true },
        }
      ],
    }
  ]
};
