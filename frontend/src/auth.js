import axios from 'axios'

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const backend_url = process.env.VUE_APP_BACKEND_URL;

export async function checkAuth() {
    try {
        await axios.get(`${backend_url}/sanctum/csrf-cookie`);

        const response = await axios.get(`${backend_url}/api/current_user`);
        return response.status == 200;
    } catch (error) {
        console.log(error);
        return false;
    }
}