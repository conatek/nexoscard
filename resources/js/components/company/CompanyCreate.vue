<template>
    <div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form @submit.prevent="storeCompany" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="card-hover-shadow card-border mb-3 card">
                                <div class="card-header">
                                    Información Básica
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="logo" class="form-label">Logo</label>
                                                <div class="input-group">
                                                    <input @change="onChangeLogo" type="file" name="logo" id="logo" class="form-control">
                                                </div>
                                                <span v-if="errors && errors.logo" class="error text-danger" for="logo">{{ errors.logo[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="company_type_id" class="form-label">Tipo de empresa*</label>
                                                <select v-model="company_type_id" name="company_type_id" class="form-control"  id="company_type_id" @change="handleChangeCompanyType">
                                                    <option value="" disabled selected hidden>Seleccionar Tipo de Empresa</option>
                                                    <option v-for="company_type in company_types" :value="company_type.id">{{ company_type.name }}</option>
                                                </select>
                                                <span v-if="errors && errors.company_type_id" class="error text-danger" for="company_type_id">{{ errors.company_type_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="position-relative mb-3">
                                                <label for="company_name" class="form-label">Nombre*</label>
                                                <input v-model="company_name" name="company_name" id="company_name" type="text" class="form-control" placeholder="Ingrese nombre empresa">
                                                <span v-if="errors && errors.company_name" class="error text-danger" for="company_name">{{ errors.company_name[0] }}</span>
                                            </div>
                                        </div>

                                        <!-- <div v-if="company_type_id == 1" class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="identification_type_id" class="form-label">Tipo de identificación*</label>
                                                <select v-model="identification_type_id" name="identification_type_id" class="form-control"  id="identification_type_id">
                                                    <option value="" disabled selected hidden>Seleccionar Tipo Identificación</option>
                                                    <option v-for="document_type in document_types" :value="document_type.id">{{ document_type.type }}</option>
                                                </select>
                                                <span v-if="errors && errors.identification_type_id" class="error text-danger" for="identification_type_id">{{ errors.identification_type_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div v-else-if="company_type_id == 2 && identification_type_default != null" class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="identification_type_id" class="form-label">Tipo de identificación*</label>
                                                <input v-model="identification_type_default.type" name="identification_type_id" id="identification_type_id" type="text" class="form-control" disabled>
                                                <span v-if="errors && errors.identification_type_id" class="error text-danger" for="identification_type_id">{{ errors.identification_type_id[0] }}</span>
                                            </div>
                                        </div> -->

                                        <div v-if="company_type_id == 1" class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="identification_type_id" class="form-label">Tipo de identificación*</label>
                                                <select v-model="identification_type_id" name="identification_type_id" class="form-control"  id="identification_type_id">
                                                    <option value="" disabled selected hidden>Seleccionar Tipo Identificación</option>
                                                    <option v-for="document_type in document_types" :value="document_type.id">{{ document_type.type }}</option>
                                                </select>
                                                <span v-if="errors && errors.identification_type_id" class="error text-danger" for="identification_type_id">{{ errors.identification_type_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div v-else-if="company_type_id == 2" class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="identification_type_id" class="form-label">Tipo de identificación*</label>
                                                <select v-model="identification_type_id" name="identification_type_id" class="form-control"  id="identification_type_id" disabled>
                                                    <option value="" disabled selected hidden>Seleccionar Tipo Identificación</option>
                                                    <option v-for="document_type in document_types" :value="document_type.id">{{ document_type.type }}</option>
                                                </select>
                                                <span v-if="errors && errors.identification_type_id" class="error text-danger" for="identification_type_id">{{ errors.identification_type_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="identification_number" class="form-label">Número de identificación*</label>
                                                <input v-model="identification_number" name="identification_number" id="identification_number" type="text" class="form-control" placeholder="Ingrese número identificación">
                                                <span v-if="errors && errors.identification_number" class="error text-danger" for="identification_number">{{ errors.identification_number[0] }}</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="province_id" class="form-label">Departamento*</label>
                                                <select v-model="province_id" @change="getCities(province_id)" name="province_id" class="form-control"  id="province_id">
                                                    <option value="" disabled selected hidden>Seleccionar Departamento</option>
                                                    <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                                                </select>
                                                <span v-if="errors && errors.province_id" class="error text-danger" for="province_id">{{ errors.province_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="city_id" class="form-label">Municipio*</label>
                                                <select v-model="city_id" name="city_id" class="form-control"  id="city_id">
                                                    <option value="" disabled selected hidden>Seleccionar Municipio</option>
                                                    <option v-for="city in cities" :value="city.id">{{ city.name }}</option>
                                                </select>
                                                <span v-if="errors && errors.city_id" class="error text-danger" for="city_id">{{ errors.city_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="position-relative mb-3">
                                                <label for="address" class="form-label">Dirección</label>
                                                <input v-model="address" name="address" id="address" type="text" class="form-control" placeholder="Ingrese dirección">
                                                <span v-if="errors && errors.address" class="error text-danger" for="address">{{ errors.address[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="industry_type_id" class="form-label">Industria*</label>
                                                <select v-model="industry_type_id" name="industry_type_id" class="form-control"  id="industry_type_id">
                                                    <option value="" disabled selected hidden>Seleccionar Sector</option>
                                                    <option v-for="industry_type in industry_types" :value="industry_type.id">{{ industry_type.name }}</option>
                                                </select>
                                                <span v-if="errors && errors.industry_type_id" class="error text-danger" for="industry_type_id">{{ errors.industry_type_id[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="size" class="form-label">Cantidad de empleados*</label>
                                                <input v-model="size" name="size" id="size" type="text" class="form-control" placeholder="Ingrese cantidad de empleados">
                                                <span v-if="errors && errors.size" class="error text-danger" for="size">{{ errors.size[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="founded_at" class="form-label">Fecha de fundación*</label>
                                                <input v-model="founded_at" name="founded_at" id="founded_at" type="date" class="form-control" placeholder="Ingrese fecha de nacimiento">
                                                <span v-if="errors_relative_data && errors_relative_data.founded_at" class="error text-danger" for="founded_at">{{ errors_relative_data.founded_at[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 d-flex align-items-center">
                                            <div class="form-group clearfix my-3">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" name="is_active" id="is_active" value="">
                                                    <label for="is_active" class="ml-2">¿Empresa activa?</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="position-relative mb-3">
                                                <label for="description" class="form-label">Descripción</label>
                                                <textarea v-model="description" name="description" id="description" type="text" class="form-control" placeholder="Ingrese descripción de la empresa" rows="7" cols="50"></textarea>
                                                <span v-if="errors && errors.description" class="error text-danger" for="description">{{ errors.description[0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="card-hover-shadow card-border mb-3 card">
                                <div class="card-header">
                                    Información de Contacto Principal
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="position-relative mb-3">
                                                <label for="contact_name" class="form-label">Nombres*</label>
                                                <input v-model="contact_name" name="contact_name" id="contact_name" type="text" class="form-control" placeholder="Ingrese nombre(s)">
                                                <span v-if="errors && errors.contact_name" class="error text-danger" for="contact_name">{{ errors.contact_name[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="contact_first_surname" class="form-label">Primer apellido*</label>
                                                <input v-model="contact_first_surname" name="contact_first_surname" id="contact_first_surname" type="text" class="form-control" placeholder="Ingrese primer apellido">
                                                <span v-if="errors && errors.contact_first_surname" class="error text-danger" for="contact_first_surname">{{ errors.contact_first_surname[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="contact_second_surname" class="form-label">Segundo apellido</label>
                                                <input v-model="contact_second_surname" name="contact_second_surname" id="contact_second_surname" type="text" class="form-control" placeholder="Ingrese segundo apellido">
                                                <span v-if="errors && errors.contact_second_surname" class="error text-danger" for="contact_second_surname">{{ errors.contact_second_surname[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="website" class="form-label">Sitio web</label>
                                                <input v-model="website" name="website" id="website" type="text" class="form-control" placeholder="Ingrese url de sitio web">
                                                <span v-if="errors && errors.website" class="error text-danger" for="website">{{ errors.website[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="email" class="form-label">Correo electrónico</label>
                                                <input v-model="email" name="email" id="email" type="text" class="form-control" placeholder="Ingrese correo electrónico">
                                                <span v-if="errors && errors.email" class="error text-danger" for="email">{{ errors.email[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="phone" class="form-label">Teléfono</label>
                                                <input v-model="phone" name="phone" id="phone" type="text" class="form-control" placeholder="Ingrese teléfono">
                                                <span v-if="errors && errors.phone" class="error text-danger" for="phone">{{ errors.phone[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="cellphone" class="form-label">Celular</label>
                                                <input v-model="cellphone" name="cellphone" id="cellphone" type="text" class="form-control" placeholder="Ingrese celular">
                                                <span v-if="errors && errors.cellphone" class="error text-danger" for="cellphone">{{ errors.cellphone[0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-hover-shadow card-border mb-3 card">
                                <div class="card-header">
                                    Información de Redes Sociales
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- <div class="col-sm-12 col-md-6 col-lg-6"> -->
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="facebook" class="form-label">Facebook</label>
                                                <input v-model="facebook" name="facebook" id="facebook" type="text" class="form-control" placeholder="Ingrese url facebook">
                                                <span v-if="errors && errors.facebook" class="error text-danger" for="facebook">{{ errors.facebook[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="instagram" class="form-label">Instagram</label>
                                                <input v-model="instagram" name="instagram" id="instagram" type="text" class="form-control" placeholder="Ingrese url instagram">
                                                <span v-if="errors && errors.instagram" class="error text-danger" for="instagram">{{ errors.instagram[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="linkedin" class="form-label">LinkedIn</label>
                                                <input v-model="linkedin" name="linkedin" id="linkedin" type="text" class="form-control" placeholder="Ingrese url linkedin">
                                                <span v-if="errors && errors.linkedin" class="error text-danger" for="linkedin">{{ errors.linkedin[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="x_twitter" class="form-label">X (Twitter)</label>
                                                <input v-model="x" name="x_twitter" id="x_twitter" type="text" class="form-control" placeholder="Ingrese url de X">
                                                <span v-if="errors && errors.x_twitter" class="error text-danger" for="x_twitter">{{ errors.x_twitter[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="position-relative mb-3">
                                                <label for="youtube" class="form-label">YouTube</label>
                                                <input v-model="youtube" name="youtube" id="youtube" type="text" class="form-control" placeholder="Ingrese url de YouTube">
                                                <span v-if="errors && errors.youtube" class="error text-danger" for="youtube">{{ errors.youtube[0] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        company_types: {
            default: null,
        },
        document_types: {
            default: null,
        },
        provinces: {
            default: null,
        },
        industry_types: {
            default: null,
        },
    },
    data() {
        return {
            identification_type_default: null,

            logo: '',
            company_type_id: '',
            company_name: '',
            identification_type_id: '',
            identification_number: '',
            province_id: '',
            city_id: '',
            address: '',
            industry_type_id: '',
            size: '',
            founded_at: '',
            status: '',
            description: '',
            contact_name: '',
            contact_first_surname: '',
            contact_second_surname: '',
            website: '',
            email: '',
            phone: '',
            cellphone: '',
            facebook: '',
            instagram: '',
            linkedin: '',
            x_twitter: '',
            youtube: '',

            cities: [],

            errors: null,
        };
    },
    mounted() {
        // Lógica de inicialización aquí
    },
    methods: {
        getCities(province) {
            let dataSend = {
                "province": province,
            }

            axios.post('/get-cities', dataSend).then(
                ({data}) => {
                    this.cities = data.cities
                })
        },
        handleChangeCompanyType() {
            if (this.company_type_id == 1){
                this.identification_type_id = '';
            } else {
                this.identification_type_id = 3; // NIT
            }
        },
        onChangeLogo(e) {
            this.logo = e.target.files[0]
        },
        storeCompany() {
            let fd = new FormData()

            if(this.logo) {
                fd.append('logo', this.logo)
            }

            fd.append('company_type_id', this.company_type_id)
            fd.append('company_name', this.company_name)
            fd.append('identification_type_id', this.identification_type_id)
            fd.append('identification_number', this.identification_number)
            fd.append('province_id', this.province_id)
            fd.append('city_id', this.city_id)
            fd.append('address', this.address)
            fd.append('industry_type_id', this.industry_type_id)
            fd.append('size', this.size)
            fd.append('founded_at', this.founded_at)
            fd.append('status', this.status)
            fd.append('description', this.description)
            fd.append('contact_name', this.contact_name)
            fd.append('contact_first_surname', this.contact_first_surname)
            fd.append('contact_second_surname', this.contact_second_surname)
            fd.append('website', this.website)
            fd.append('email', this.email)
            fd.append('phone', this.phone)
            fd.append('cellphone', this.cellphone)
            fd.append('facebook', this.facebook)
            fd.append('instagram', this.instagram)
            fd.append('linkedin', this.linkedin)
            fd.append('x_twitter', this.x_twitter)
            fd.append('youtube', this.youtube)

            let url = ''
            axios.post('/companies', fd).then(
                (res) => {
                    localStorage.setItem('origin', 'created');

                    // url = `/companies/${res.data.company.id}`
                    url = `/companies`
                    window.location.href = url
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors = error.response.data.errors
                    }
                })
        },
    },
};
</script>

<style scoped>
/* Estilos del componente aquí */
</style>
