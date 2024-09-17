// main.js

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router"; // Import the router instance from router.js
import store from "./store";
import VueCookies from "vue-cookies";

import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import '@fortawesome/fontawesome-free/css/all.css'

createApp(App).use(router).use(store).use(VueCookies).mount('#app');