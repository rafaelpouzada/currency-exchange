import LandingPage from '../views/LandingPage';
import LoginForm from "../views/LoginForm.vue";
import RegisterForm from '../views/RegisterForm';
import ForgotPassword from '../views/ForgotPassword';
import AdminHome from '../views/admin/AdminHome';
import AdminComponent from '../views/admin/AdminComponent';

export default {
  routes: [
    {
      path: '/',
      component: LandingPage,
      meta: { requiresAuth: false },
    },
    {
      path: '/login',
      component: LoginForm,
      meta: { requiresAuth: false },
    },
    {
      path: "/recovery-password",
      name: "recovery-password",
      component: ForgotPassword,
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
            component: AdminHome,
            name: 'admin.home',
            meta: { requiresAuth: true },
        },
      ],
    }
  ]
};
