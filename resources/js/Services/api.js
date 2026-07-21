import axios from 'axios';

// Create a configured Axios instance
const api = axios.create({
    baseURL: '/', // Same origin
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// Request Interceptor: Attach Token automatically
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('api_token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response Interceptor: Global error handling
api.interceptors.response.use(
    (response) => {
        // Return only the data portion if successful
        return response.data;
    },
    (error) => {
        let errorMessage = 'Tizimda noma\'lum xatolik yuz berdi.';
        
        if (error.response) {
            // Server responded with an error status code
            const status = error.response.status;
            const data = error.response.data;

            if (status === 401) {
                errorMessage = 'Sizning ruxsatingiz tugagan. Iltimos, qayta tizimga kiring.';
            } else if (status === 403) {
                errorMessage = 'Sizda bu amalni bajarish uchun ruxsat yo\'q.';
            } else if (status === 422) {
                // Validation errors
                errorMessage = data.message || 'Kiritilgan ma\'lumotlarda xatolik bor.';
            } else if (status === 500) {
                errorMessage = 'Server xatoligi. Iltimos, keyinroq qayta urinib ko\'ring.';
            } else {
                errorMessage = data.message || errorMessage;
            }
        } else if (error.request) {
            // Request was made but no response received
            errorMessage = 'Server bilan bog\'lanishda xatolik (Tarmoq uzilgan bo\'lishi mumkin).';
        }

        // Return a structured error object that can be caught by components
        return Promise.reject({
            originalError: error,
            message: errorMessage,
            status: error.response?.status
        });
    }
);

export default api;
