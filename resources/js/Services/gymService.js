import api from './api';

export const gymService = {
    /**
     * Tizimga kirish va Access Token olish.
     * @param {string} email 
     * @param {string} password 
     * @returns {Promise} access_token va foydalanuvchi ma'lumotlari
     */
    async login(email, password) {
        try {
            const response = await api.post('/api/auth/login', { email, password });
            
            // Tokenni xavfsiz saqlash
            if (response.access_token) {
                localStorage.setItem('api_token', response.access_token);
            }
            
            return response;
        } catch (error) {
            throw error;
        }
    },

    /**
     * Barcha mijozlarni qidirish va filtrlash.
     * @param {Object} params Qidiruv parametrlari (masalan: { search: 'Ali' })
     * @returns {Promise} Mijozlar ro'yxati
     */
    async getMembers(params = {}) {
        return await api.get('/api/members', { params });
    },

    /**
     * Abonementi tugamagan mijoz uchun tashrif (check-in) belgilash.
     * @param {number|string} clientId Mijozning ID si (yoki FaceID tokeni)
     * @returns {Promise} Tashrif natijasi
     */
    async checkIn(clientId) {
        return await api.post('/api/attendance/check-in', { client_id: clientId });
    },

    /**
     * Mijoz abonementini uzaytirish.
     * @param {Object} data Uzaytirish ma'lumotlari
     * @param {number|string} data.clientId Mijoz ID si
     * @param {number} data.amount To'lov summasi
     * @param {number} data.months Necha oyga uzaytirilishi
     * @returns {Promise} Uzaytirish natijasi
     */
    async updateSubscription({ clientId, amount, months }) {
        return await api.post('/api/subscriptions/update', {
            client_id: clientId,
            amount: amount,
            months: months
        });
    },

    /**
     * Tizimdan chiqish va tokenni tozalash
     */
    logout() {
        localStorage.removeItem('api_token');
        // ixtiyoriy: api.post('/api/auth/logout') ni chaqirish
    }
};

export default gymService;
