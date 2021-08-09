import Vue from 'vue'
import App from './App.vue'
import router from './router'
import Vuetify from 'vuetify/lib'
// import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify)
Vue.config.productionTip = false
new Vue({
  vuetify : new Vuetify({rtl: true}),
  router,
  render: h => h(App),
}).$mount('#app')
