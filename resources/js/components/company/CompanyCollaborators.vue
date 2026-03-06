<template>
    <div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form>
                <!-- <form @submit.prevent="updateCompany" enctype="multipart/form-data"> -->
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="card-hover-shadow card-border mb-3 card">
                                <div class="card-header">
                                    Carga Masiva
                                </div>

                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-sm-8">
                                            <div class="position-relative">
                                                <!-- <label for="logo" class="form-label">Archivo de carga</label> -->
                                                <input
                                                    @change="onChangeLogo"
                                                    type="file"
                                                    name="logo"
                                                    id="logo"
                                                    class="form-control"
                                                >
                                                <span v-if="errors && errors.logo" class="error text-danger" for="logo">{{ errors.logo[0] }}</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 d-flex align-items-end">
                                            <button
                                                type="button"
                                                class="btn btn-success w-100"
                                                @click="clearLogo"
                                            >
                                                <!-- <font-awesome-icon :icon="['fas', 'file-excel']" /> -->
                                                Descargar Plantilla
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Procesar Archivo</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CompanyCollaborators',
    data() {
        return {
            // Add your component data here
        };
    },
    methods: {
        // Add your component methods here
    },
    computed: {
        // Add your computed properties here
    },
    mounted() {
        // Add your mounted logic here
    }
};
</script>

<style scoped>
/* Add your component styles here */
</style>
