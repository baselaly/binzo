import Vue from 'vue'
import Router from 'vue-router'
import { UserGuard } from './guards.js'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      component: () => import('./views/Main.vue'),
      children: [
        {
          path: '',
          redirect: { name: 'home' },
          beforeEnter: UserGuard,
          meta: { Auth: true },
        },
        {
          path: '/home',
          name: 'home',
          component: () => import('./views/Home.vue'),
          beforeEnter: UserGuard,
          meta: { Auth: true },
        },
        {
          path: '/post/:id',
          name: 'post',
          component: () => import('./views/SinglePost.vue'),
          beforeEnter: UserGuard,
          meta: { Auth: true },
        },
        {
          path: '/profile',
          name: 'profile',
          component: () => import('./views/Profile.vue'),
          beforeEnter: UserGuard,
          meta: { Auth: true },
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./views/Login.vue'),
      beforeEnter: UserGuard,
      meta: { NotAuth: true },
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('./views/Register.vue'),
      beforeEnter: UserGuard,
      meta: { NotAuth: true },
    },
  ]
})
