<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="position-relative mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input v-model="name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative mb-3">
                    <label for="address" class="form-label">Dirección</label>
                    <input v-model="address" name="address" id="address" placeholder="Ingresar dirección" type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="position-relative mb-3">
                    <label for="area_id" class="form-label">Departamento</label>
<!--                    <select v-model="province_id" @change="getCities(province_id)" class="multiselect-dropdown form-control" name="province" id="province">-->
                    <select v-model="province_id" @change="getCities(province_id)" class="form-control" name="province" id="province">
<!--                        <option :value="city_id != '' ? city_id : ''">{{ value == '' ? '&#45;&#45; Seleccionar Departamento &#45;&#45;' : '' }}</option>-->
                        <option value="">-- Seleccionar Departamento --</option>
                        <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative mb-3">
                    <label for="area_id" class="form-label">Municipio</label>
<!--                    <select v-model="city_id" class="multiselect-dropdown form-control" name="city" id="city">-->
                    <select v-model="city_id" class="form-control" name="city" id="city">
                        <option value="" :selected="cities === []">-- Seleccionar Municipio --</option>
                        <option v-for="city in cities" :value="city.id">{{ city.name }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="position-relative mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input v-model="phone" name="phone" id="phone" placeholder="Ingresar teléfono" type="text" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input v-model="email" name="email" id="email" placeholder="Ingresar email" type="text" class="form-control">
                </div>
            </div>
        </div>
        <button @click="updateCampus()" type="submit" class="mt-2 btn btn-primary">Guardar</button>
    </div>
</template>

<script>
export default {
    props: {
        campus: {
            default: [],
        },
        provinces: {
            default: null,
        },
    },
    data() {
        return {
            campus_id: '',
            company_id: '',
            province_id: '',
            city_id: '',
            cities: null,
            name: '',
            address: '',
            phone: '',
            email: '',
        }
    },
    mounted () {
        this.campus_id = this.campus['id']
        this.company_id = this.campus['company_id']

        this.province_id = this.campus['province_id']
        if (this.province_id) {
            this.getCities(this.province_id)
            this.city_id = this.campus['city_id']
        }

        this.name = this.campus['name']
        this.address = this.campus['address']
        this.phone = this.campus['phone']
        this.email = this.campus['email']
    },
    methods: {
        getCities(province) {
            let dataSend = {
                "province": province,
            }

            this.city_id = ''

            axios.post('/get-cities', dataSend).then(
                ({data}) => {
                    this.cities = data.cities
                })
        },
        updateCampus() {
            let dataSend = {
                'id': this.campus_id,
                'company_id': this.company_id,
                'province_id': this.province_id,
                'city_id': this.city_id,
                'name': this.name,
                'address': this.address,
                'phone': this.phone,
                'email': this.email,
            }

            let url = ''
            axios.put(`/campuses/${this.campus_id}`, dataSend).then(
                (res) => {
                    url = `/campuses/${res.data.campus}`
                    window.location.href = url;
                }).catch(
                (error) => {
                    console.log(error)
                })
        },
    },
}
</script>
