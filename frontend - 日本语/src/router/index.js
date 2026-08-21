import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/noLogin/HomeView.vue'
import NoLoginBooks from '../views/noLogin/Books.vue'
import NoLoginBookInfo from '../views/noLogin/BookInfo.vue'
import NoLoginInfo from '../views/noLogin/Info.vue'
import Login from '../views/noLogin/Login.vue'
import Register from '@/views/noLogin/Register.vue'
import FIndPassword from '@/views/noLogin/FIndPassword.vue'

import UserHome from '@/views/User/Home.vue'
import UserBooks from '@/views/User/Books.vue'
import UserBookInfo from '@/views/User/BookInfo.vue'
import UserInfo from '@/views/User/UserInfo.vue'
import userBorrows from '@/views/User/Borrow.vue'
import Info from '@/views/User/Info.vue'

import RootLogin from '@/views/root/RootLogin.vue'
import RootTable from '@/views/root/RootTable.vue'
import RootCenter from '@/views/root/RootCenter.vue'
import RootBooks from '@/views/root/RootBooks.vue'
import RootInfo from '@/views/root/RootInfo.vue'
import RootUsers from '@/views/root/RootUsers.vue'
import RootBorrow from '@/views/root/RootBorrow.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/root/borrow',
      name: 'RootBorrow',
      component: RootBorrow,
    },
    {
      path: '/root/users',
      name: 'RootUsers',
      component: RootUsers,
    },
    {
      path: '/root/info',
      name: 'RootInfo',
      component: RootInfo,
    },
    {
      path: '/root/books',
      name: 'RootBooks',
      component: RootBooks,
    },
    {
      path: '/root/center',
      name: 'RootCenter',
      component: RootCenter,
    },
     {
      path: '/root/table',
      name: 'RootTable',
      component: RootTable,
    },
    {
      path: '/root/login',
      name: 'RootLogin',
      component: RootLogin,
    },

    {
      path: '/user/info',
      name: 'Info',
      component: Info,
    },
    {
      path: '/user/borrow',
      name: 'userBorrows',
      component: userBorrows,
    },
    {
      path: '/user/center',
      name: 'UserInfo',
      component: UserInfo,
    },
    {
      path: '/user/book/info',
      name: 'UserBookInfo',
      component: UserBookInfo,
    },
     {
      path: '/user/books',
      name: 'UserBooks',
      component: UserBooks,
    },
    {
      path: '/user/home',
      name: 'UserHome',
      component: UserHome,
    },

    {
      path: '/findpassword',
      name: 'FIndPassword',
      component: FIndPassword,
    },
    {
      path: '/register',
      name: 'Register',
      component: Register,
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/info',
      name: 'NoLoginInfo',
      component: NoLoginInfo,
    },
    {
      path: '/book/info',
      name: 'NoLoginBookInfo',
      component: NoLoginBookInfo,
    },
    {
      path: '/book',
      name: 'NoLoginBooks',
      component: NoLoginBooks,
    },
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
  ],
})

export default router
