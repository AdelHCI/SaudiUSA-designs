import Vue from 'vue'
import Router from 'vue-router'



Vue.use(Router)


let router = new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import(/* webpackChunkName: "home" */ './views/Home.vue'),
    },
    {
      path: '/uploadfile',
      name: 'upload',
      component: () => import(/* webpackChunkName: "upload" */ './views/Upload.vue'),
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
