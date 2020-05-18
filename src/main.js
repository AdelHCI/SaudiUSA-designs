import Vue from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
Vue.use(Vuetify)
Vue.config.productionTip = false
new Vue({
  vuetify : new Vuetify({rtl: true}),
  router,
  render: h => h(App),
}).$mount('#app')
