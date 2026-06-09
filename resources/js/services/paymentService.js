import api from './api.js';

export default {
    process(data) {
        return api.post('/payments/process', data);
    },

    history() {
        return api.get('/payments/history');
    },
};
