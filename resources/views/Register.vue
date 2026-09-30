<template>
    <div class="auth-page">
        <!-- Left Side - Form -->
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <!-- Mobile Logo -->
                <router-link to="/inicio" class="mobile-logo" aria-label="Nexos Card">
                    <img :src="logoUrl" alt="">
                    <span>NEXOS Card</span>
                </router-link>

                <div class="auth-header">
                    <h1>Crear Cuenta</h1>
                    <p>Regístrate y comienza a crear tus tarjetas digitales</p>
                </div>

                <!-- Error Alert -->
                <div v-if="errorMessage" class="error-alert">
                    <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ errorMessage }}</span>
                </div>

                <form @submit.prevent="handleRegister" class="auth-form">
                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Nombre completo
                        </label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <input
                                v-model="form.name"
                                id="name"
                                type="text"
                                class="form-input"
                                :class="{ 'has-error': errors.name }"
                                placeholder="Tu nombre"
                                required
                                autofocus
                            >
                        </div>
                        <span v-if="errors.name" class="error-text">{{ errors.name[0] }}</span>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Correo electrónico
                        </label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <input
                                v-model="form.email"
                                id="email"
                                type="email"
                                class="form-input"
                                :class="{ 'has-error': errors.email }"
                                placeholder="tu@email.com"
                                required
                            >
                        </div>
                        <span v-if="errors.email" class="error-text">{{ errors.email[0] }}</span>
                    </div>

                    <!-- Password Fields Row -->
                    <div class="form-row">
                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                Contraseña
                            </label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <input
                                    v-model="form.password"
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="form-input"
                                    :class="{ 'has-error': errors.password }"
                                    placeholder="Mínimo 6 caracteres"
                                    required
                                >
                                <button type="button" class="toggle-password" @click="showPassword = !showPassword">
                                    <svg v-if="!showPassword" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <svg v-else viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                                    </svg>
                                </button>
                            </div>
                            <span v-if="errors.password" class="error-text">{{ errors.password[0] }}</span>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                Confirmar contraseña
                            </label>
                            <div class="input-wrapper">
                                <svg class="input-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <input
                                    v-model="form.password_confirmation"
                                    id="password_confirmation"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="form-input"
                                    placeholder="Repetir contraseña"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" :disabled="loading">
                        <span v-if="loading" class="spinner"></span>
                        <span>{{ loading ? 'Creando cuenta...' : 'Crear cuenta' }}</span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="auth-footer">
                    <p>
                        ¿Ya tienes una cuenta?
                        <router-link to="/login" class="auth-link">Inicia sesión</router-link>
                    </p>
                </div>

                <!-- Back to Home -->
                <router-link to="/inicio" class="back-link">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Volver al inicio
                </router-link>
            </div>
        </div>

        <AuthShowcase variant="register" />
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';
import AuthShowcase from '@/components/auth/AuthShowcase.vue';

export default {
    components: { AuthShowcase },

    data() {
        return {
            // Enlazado dinámico: con un src literal, Vite intenta importar el archivo de public/.
            logoUrl: '/images/landing/logo-mark.webp',
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            errors: {},
            errorMessage: '',
            loading: false,
            showPassword: false,
        };
    },
    methods: {
        async handleRegister() {
            this.errors = {};
            this.errorMessage = '';
            this.loading = true;

            try {
                const auth = useAuth();
                await auth.register(this.form);
                this.$router.push({ name: 'home' });
            } catch (error) {
                if (error.response) {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    } else {
                        this.errorMessage = error.response.data.message || 'Error al registrar. Intenta de nuevo.';
                    }
                } else {
                    this.errorMessage = 'Error de conexion. Verifica tu red.';
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
/* Base Layout */
.auth-page {
    display: flex;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Left Side - Form */
.auth-form-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    background: white;
}

.auth-form-wrapper {
    width: 100%;
    max-width: 480px;
}

.mobile-logo {
    display: none;
    margin-bottom: 2rem;
    align-items: center;
    gap: 0.5rem;
    color: #1e293b;
    text-decoration: none;
}

.mobile-logo img {
    width: 40px;
    height: 40px;
}

.mobile-logo span {
    font-family: 'Rammetto One', 'Outfit', sans-serif;
    font-size: 1.15rem;
}

.auth-header {
    margin-bottom: 2rem;
}

.auth-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: #64748b;
    font-size: 0.95rem;
}

/* Error Alert */
.error-alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 10px;
    color: #dc2626;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.alert-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

/* Form */
.auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 1rem;
    width: 18px;
    height: 18px;
    color: #94a3b8;
    pointer-events: none;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.75rem;
    font-size: 0.95rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: white;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.form-input.has-error {
    border-color: #ef4444;
}

.form-input::placeholder {
    color: #94a3b8;
}

.toggle-password {
    position: absolute;
    right: 1rem;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    color: #94a3b8;
    transition: color 0.2s;
}

.toggle-password:hover {
    color: #64748b;
}

.toggle-password svg {
    width: 18px;
    height: 18px;
}

.error-text {
    font-size: 0.8rem;
    color: #ef4444;
}

/* Submit Button */
.btn-submit {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.875rem;
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);
    margin-top: 0.5rem;
}

.btn-submit:hover:not(:disabled) {
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.35);
    transform: translateY(-1px);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Footer */
.auth-footer {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f1f5f9;
}

.auth-footer p {
    color: #64748b;
    font-size: 0.95rem;
}

.auth-link {
    color: #8b5cf6;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.auth-link:hover {
    color: #7c3aed;
    text-decoration: underline;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    color: #64748b;
    font-size: 0.9rem;
    text-decoration: none;
    transition: color 0.2s;
}

.back-link:hover {
    color: #8b5cf6;
}

.back-link svg {
    width: 16px;
    height: 16px;
}

/* Responsive */
@media (max-width: 992px) {

    .auth-form-container {
        padding: 2rem;
    }

    .mobile-logo {
        display: inline-flex;
    }
}

@media (max-width: 576px) {
    .auth-form-container {
        padding: 1.5rem;
    }

    .auth-header h1 {
        font-size: 1.5rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
