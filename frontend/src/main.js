import Vue from 'vue'
import App from './App.vue'
import store from './store'
import './registerServiceWorker'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from "axios";
import VueCookies from 'vue-cookies'
import router from './router'

Vue.config.productionTip = false
Vue.use(Vuetify)
Vue.use(VueCookies)
Vue.prototype.$http = axios;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
