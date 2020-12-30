import Vue from 'vue'
import vuetify from './plugins/vuetify' // path to vuetify export
import './plugins/axios'
import router from './router'
import VueToastr from "vue-toastr";
import App from './App.vue';
Vue.config.productionTip = false
Vue.use(VueToastr);

new Vue({
    vuetify,
    router,
    render: h => h(App)
}).$mount('#app')
  