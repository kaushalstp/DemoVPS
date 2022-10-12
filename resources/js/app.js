/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');       

window.Vue = require('vue');   

//import Vuex from 'vuex'; 

import { dom,faCircle } from '@fortawesome/fontawesome-svg-core' 

import { library } from '@fortawesome/fontawesome-svg-core'
import { faCoffee } from '@fortawesome/free-solid-svg-icons'
import { faJs, faVuejs } from '@fortawesome/free-brands-svg-icons' 
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome' 
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { faSearch,faTags,faBookmark,faEllipsisV,faGift,faCheckSquare,faShoppingCart,faUpload,faFileCode,faFile,faClock,faArrowCircleRight,faServer,faBell,faUniversity,faUserPlus,faMale,faShoppingBag,faQuestion, faKey, faAngleLeft,faAngleDown, faDatabase, faTrash, faCogs, faBook, faBars, faCreditCard, faEnvelope, faHome, faPhone, faBullhorn, faPlug, faVenusMars,faArrowCircleOUp,faArrowCircleUp, faStar, faPlus,faMinus, faLock, faDownload,faSignIn,faArrowAltCircleRight,faArrowAltSquareRight,faQuestionCircle,faTools } from '@fortawesome/free-solid-svg-icons'
import { fab} from '@fortawesome/free-brands-svg-icons'
import { faUser } from '@fortawesome/free-solid-svg-icons'

import { faFontAwesome } from '@fortawesome/free-brands-svg-icons'

import VueFormGenerator from "vue-form-generator";
import "vue-form-generator/dist/vfg.css";

import Userboard from './layouts/userboard.vue';
import Visitorboard from './layouts/visitorboard.vue';

import { ToggleButton } from 'vue-js-toggle-button';

import AlertIcon from 'vue-ionicons/dist/ios-alert.vue';

import PayPal from 'vue-paypal-checkout';

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import VueRecaptcha from 'vue-recaptcha';
import GoogleLogin from 'vue-google-login';
//import MaterialDesignTransition from 'vue-router-md-transition'; 

//import store from './store';

Vue.use(VueToast);
Vue.use(VueRecaptcha);


Vue.component('paypal-checkout', PayPal)

Vue.component('alert-icon', AlertIcon)
 
Vue.component('ToggleButton', ToggleButton);

Vue.component('userboard-layout', Userboard);
Vue.component('visitorboard-layout', Visitorboard);


/*import VueTinySlider from 'vue-tiny-slider'; 
import 'tiny-slider/src/tiny-slider';*/ 

require('vue-ionicons/ionicons.css');

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import store from './store';
 

dom.watch()

library.add(faCoffee) 
library.add(faUserSecret)
library.add(faSearch,faTags,faBookmark,faEllipsisV,faGift,faCheckSquare,faShoppingCart,faUpload,faFileCode,faFile,faClock,faArrowCircleRight,faBell,faServer,faUniversity,faMale,faUserPlus,faShoppingBag,faQuestion,faKey,faAngleLeft,faAngleDown,faDatabase,faTrash,faCogs,faBook,faBars,faCreditCard,faEnvelope,faHome,faPhone,faBullhorn, faPlug, faVenusMars,faArrowCircleUp,faStar, faPlus,faMinus,faLock, faDownload,faQuestionCircle,faTools)
library.add(fab)
library.add(faUser)

library.add(faFontAwesome)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.use(VueFormGenerator)
//Vue.use(VueTinySlider)
//Vue.component('tiny-slider', VueTinySlider)

// Install BootstrapVue
Vue.use(BootstrapVue)


// Install BootstrapVue icon
Vue.use(IconsPlugin)


import App from './App.vue';
import VueRouter from 'vue-router'; 
import VueAxios from 'vue-axios';
import axios from 'axios';
import {routes} from './routes';
//import bearer from '@websanova/vue-auth/src/drivers/auth/bearer';
//import auth from './auth';

//import Userboard from './layouts/userboard.vue';
//import Visitorboard from './layouts/Visitorboard.vue';

//import VueFormGenerator from 'vue-form-generator';
//import VueBootstrap4Table from 'vue-bootstrap4-table';
//Vue.use(VueFormGenerator);

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
//Vue.use(auth);

//Vue.component('userboard-layout', Userboard);
//Vue.component('visitorboard-layout', Visitorboard);

//Vue.use(VueBootstrap4Table);  
import VueSweetalert2 from 'vue-sweetalert2';
// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);
const router = new VueRouter({             
	base: '/',    
    mode: 'history',
    routes: routes
}); 

const app = new Vue({
    el: '#app',
    router: router,    
    render: h => h(App),    
});




