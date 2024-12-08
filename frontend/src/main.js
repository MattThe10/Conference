import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import axios from "axios";

axios.defaults.baseURL =
  process.env.VUE_APP_BACKEND_URL || "http://localhost:3000";
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

async function initializeCSRF() {
  try {
    await axios.get("/sanctum/csrf-cookie");
    console.log("CSRF token initialized");
  } catch (error) {
    console.log("Error initializing CSRF token:", error);
  }
}

initializeCSRF();
createApp(App).use(router).mount("#app");
