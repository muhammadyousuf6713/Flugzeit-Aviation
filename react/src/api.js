import axios from 'axios';

// Create an Axios instance
const api = axios.create({
    baseURL: 'http://localhost/backend-aku/public/api/api-academic-program', // Adjust to your Laravel backend URL
    headers: {
        'Content-Type': 'application/json',
    },
});

// Fetch academic programs
export const fetchAcademicPrograms = async () => {
    try {
        const response = await api.get('/list');
        return response.data;
    } catch (error) {
        console.error('Error fetching academic programs:', error);
        throw error;
    }
};

export default api;
