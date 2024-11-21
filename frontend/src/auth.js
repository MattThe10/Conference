import axios from 'axios'

axios.defaults.baseURL = process.env.VUE_APP_BACKEND_URL || 'http://localhost:3000';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

export async function checkAuth() {
    try {
        await axios.get('/sanctum/csrf-cookie');

        const response = await axios.get('/api/current_user');

        if (response.status == 200) {
            return true;
        }
    } catch (error) {
        console.log(error);
        return false;
    }
}