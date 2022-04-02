import Vue from 'vue'
import Router from 'vue-router'



Vue.use(Router)


let router = new Router({
  routes: [{
      path: '/',
      name: 'home',
      component: () => import( /* webpackChunkName: "home" */ './views/Home.vue'),
    },
    {
      path: '/uploadfile',
      name: 'upload',
      component: () => import( /* webpackChunkName: "upload" */ './views/Upload.vue'),
    },
    {
      path: '/login',
      name: 'login',
      props: true,
      component: () => import( /* webpackChunkName: "login" */ './views/Login.vue'),
    },
    {
      path: '/register',
      name: 'register',
      props: true,
      component: () => import( /* webpackChunkName: "register" */ './views/Register.vue'),
    },
    {
      path: '/forgot',
      name: 'forgot',
      props: true,
      component: () => import( /* webpackChunkName: "forgot" */ './views/Forgot.vue'),
    },
    {
      path: '*',
      redirect: '/',
    }
  ],
  mode: 'history',
  linkExactActiveClass: 'active'
})


export default router;