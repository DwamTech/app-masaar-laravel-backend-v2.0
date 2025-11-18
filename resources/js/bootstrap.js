import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// Attach CSRF token from meta tag for stateful Sanctum requests
const csrfToken = document.head?.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}
// Send cookies for Sanctum stateful auth
window.axios.defaults.withCredentials = true;
