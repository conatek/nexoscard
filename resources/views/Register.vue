<template>
    <div class="app-container app-theme-white body-tabs-shadow bg-white">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 g-0 row">
                    <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9 px-4">
                            <div class="app-logo"></div>
                            <h4 class="mb-0">
                                <span class="d-block">Bienvenid@,</span>
                                <span>Solo toma unos segundos crear tu cuenta.</span>
                            </h4>
                            <h6 class="mt-3">¿Ya tienes una cuenta?
                                <router-link to="/login" class="text-primary">Ingresa ahora</router-link>
                            </h6>
                            <div class="divider row"></div>

                            <div v-if="errorMessage" class="alert alert-danger" role="alert">
                                {{ errorMessage }}
                            </div>

                            <div>
                                <form @submit.prevent="handleRegister">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="position-relative mb-3">
                                                <label for="name" class="form-label">Nombre</label>
                                                <input v-model="form.name"
                                                       id="name"
                                                       placeholder="Nombre..."
                                                       type="text"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': errors.name }"
                                                       required
                                                       autofocus>
                                                <span v-if="errors.name" class="invalid-feedback" role="alert">
                                                    <strong>{{ errors.name[0] }}</strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative mb-3">
                                                <label for="email" class="form-label">Correo electrónico</label>
                                                <input v-model="form.email"
                                                       id="email"
                                                       placeholder="Correo electronico..."
                                                       type="email"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': errors.email }"
                                                       required>
                                                <span v-if="errors.email" class="invalid-feedback" role="alert">
                                                    <strong>{{ errors.email[0] }}</strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative mb-3">
                                                <label for="password" class="form-label">Contraseña</label>
                                                <input v-model="form.password"
                                                       id="password"
                                                       placeholder="Contraseña..."
                                                       type="password"
                                                       class="form-control"
                                                       :class="{ 'is-invalid': errors.password }"
                                                       required>
                                                <span v-if="errors.password" class="invalid-feedback" role="alert">
                                                    <strong>{{ errors.password[0] }}</strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative mb-3">
                                                <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                                                <input v-model="form.password_confirmation"
                                                       id="password-confirm"
                                                       placeholder="Confirmar contraseña..."
                                                       type="password"
                                                       class="form-control"
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="divider row"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-auto">
                                            <button type="submit" class="btn btn-primary btn-lg" :disabled="loading">
                                                {{ loading ? 'Registrando...' : 'Registrar' }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-lg-block col-lg-4 bg-dark"
                         style="background-image: url('/images/sidebar/city2.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';

export default {
    data() {
        return {
            form: {
                company_id: 1,
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            errors: {},
            errorMessage: '',
            loading: false,
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
                    this.errorMessage = 'Error de conexión. Verifica tu red.';
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
