import axios from "axios";
import jQuery from "jquery";

// Disponibiliza jQuery globalmente
window.$ = window.jQuery = jQuery;

// Configura Axios
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Configura CSRF token automaticamente
const token = document.querySelector('meta[name="csrf-token"]')?.content;
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token;
}
