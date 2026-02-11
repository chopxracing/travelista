import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8876/api',
    withCredentials: true //для Sanctum
});

export default api;
