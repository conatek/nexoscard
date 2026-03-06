import { reactive, computed } from 'vue';
import api from '@/services/api';

const state = reactive({
    user: null,
    token: null,
});

export function useAuth() {
    const isAuthenticated = computed(() => !!state.token);

    async function login(credentials) {
        const { data } = await api.post('/login', credentials);
        state.token = data.access_token;
        state.user = data.user;
        localStorage.setItem('auth_token', data.access_token);
        localStorage.setItem('auth_user', JSON.stringify(data.user));
        return data;
    }

    async function register(form) {
        const { data } = await api.post('/register', form);
        state.token = data.access_token;
        state.user = data.user;
        localStorage.setItem('auth_token', data.access_token);
        localStorage.setItem('auth_user', JSON.stringify(data.user));
        return data;
    }

    async function logout() {
        try {
            await api.post('/logout');
        } catch {
            // Si el token ya expiró, igual limpiar estado local
        }
        state.token = null;
        state.user = null;
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
    }

    async function fetchUser() {
        try {
            const { data } = await api.get('/me');
            state.user = data;
            localStorage.setItem('auth_user', JSON.stringify(data));
            return data;
        } catch {
            state.token = null;
            state.user = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
            return null;
        }
    }

    async function loadFromStorage() {
        const token = localStorage.getItem('auth_token');
        const user = localStorage.getItem('auth_user');
        if (token) {
            state.token = token;
            state.user = user ? JSON.parse(user) : null;
            await fetchUser();
        }
    }

    return {
        state,
        isAuthenticated,
        login,
        register,
        logout,
        fetchUser,
        loadFromStorage,
    };
}
