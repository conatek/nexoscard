<template>
    <div>
        <div v-if="origin == 'updated'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
            <span class="pe-2">
                <i class="fa fa-star"></i>
            </span>
            Empresa actualizada correctamente!
        </div>
        <div v-else-if="origin == 'created'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
            <span class="pe-2">
                <i class="fa fa-star"></i>
            </span>
            Empresa creada correctamente!
        </div>
        <div class="card-shadow-primary profile-responsive card-border mb-3 card">
            <div class="dropdown-menu-header">
                <div class="dropdown-menu-header-inner" style="background-color: #127cb3;">
                    <div class="menu-header-image opacity-3" style="background-image: url('');"></div>
                    <div class="menu-header-content btn-pane-right">
                        <div class="avatar-icon-wrapper me-2 avatar-icon-xl">
                            <div v-if="company && company.logo_url" class="avatar-icon rounded">
                                <img :src="company.logo_url" :alt="company.company_name">
                            </div>
                            <div v-else class="avatar-icon rounded">
                                <img :src="'/images/default-profile.jpeg'" :alt="company.company_name">
                            </div>
                        </div>
                        <div>
                            <h5 class="menu-header-title">{{ company.company_name }}</h5>
                            <h6 class="menu-header-subtitle">Información complementaria</h6>
                        </div>
                        <div class="menu-header-btn-pane">
                            <button @click="emitEditCompany" class="btn btn-lg btn-mh-white m-2">
                                <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                                Editar empresa
                            </button>
                            <button @click="emitLoadCollaborators" class="btn btn-lg btn-mh-white m-2">
                                <font-awesome-icon :icon="['fas', 'upload']" />
                                Carga masiva de colaboradores
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li class="p-0 list-group-item">
                    <div class="grid-menu grid-menu-2col">
                        <div class="g-0 row">
                            <div class="col-sm-6">
                                <a @click="changeMainTab('general')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-apartment btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    <!-- <span class="lnr lnr-apartment"></span> -->
                                    Información General
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('negotiation')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr lnr-thumbs-up btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Negociación
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('modules')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr lnr-database btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Módulos
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('billing')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-book btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Facturación
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div v-if="card_selected == 'general'">
            <div class="row">
                <div class="col-12">
                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            Información Básica
                        </div>
                        <div class="card-body">
                            <div class="wrapper-basic mt-3">
                                <div class="data-pair">
                                    <div class="box-label lb-1">
                                        <p class="">Nombre empresa:</p>
                                    </div>
                                    <div class="box-value vl-1">
                                        <p class="">{{ company.company_name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-2">
                                        <p class="">Tipo empresa:</p>
                                    </div>
                                    <div class="box-value vl-2">
                                        <p class="">{{ company_type.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-3">
                                        <p class="">Sector:</p>
                                    </div>
                                    <div class="box-value vl-3">
                                        <p class="">{{ industry_type.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-4">
                                        <p class="">Tipo de identificación:</p>
                                    </div>
                                    <div class="box-value vl-4">
                                        <p class="">{{ identification_type.type }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-5">
                                        <p class="">Número de identificación:</p>
                                    </div>
                                    <div class="box-value vl-5">
                                        <p class="">{{ company.identification_number }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-6">
                                        <p class="">Sede principal:</p>
                                    </div>
                                    <div class="box-value vl-6">
                                        <p class="">{{ city.name }} ({{ province.name }})</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-7">
                                        <p class="">Tamaño:</p>
                                    </div>
                                    <div class="box-value vl-7">
                                        <p class="">{{ company.size }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-8">
                                        <p class="">Dirección:</p>
                                    </div>
                                    <div class="box-value vl-8">
                                        <p class="">{{ company.address }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-9">
                                        <p class="">Fecha de fundación:</p>
                                    </div>
                                    <div class="box-value vl-9">
                                        <p class="">{{ company.founded_at }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-10">
                                        <p class="">Estado:</p>
                                    </div>
                                    <div class="box-value vl-10">
                                        <p class="">{{ company.is_active == 0 ? 'Inactiva' : 'Activa' }}</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-11">
                                        <p class="">Descripción</p>
                                    </div>
                                    <div class="box-value vl-11">
                                        <p class="">{{ company.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            Información de Contacto
                        </div>
                        <div class="card-body">
                            <div class="wrapper-contact mt-3">
                                <div class="data-pair full-width">
                                    <div class="box-label lb-12">
                                        <p class="">Nombre:</p>
                                    </div>
                                    <div class="box-value vl-12">
                                        <p v-if="company && company.contact_name" class="">{{ company.contact_name }} {{ company.contact_first_surname }} {{ company.contact_second_surname }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-13">
                                        <p class="">Sitio web:</p>
                                    </div>
                                    <div class="box-value vl-13">
                                        <p v-if="company && company.website" class="">{{ company.website }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-14">
                                        <p class="">Email:</p>
                                    </div>
                                    <div class="box-value vl-14">
                                        <p v-if="company && company.email" class="">{{ company.email }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-15">
                                        <p class="">Teléfono:</p>
                                    </div>
                                    <div class="box-value vl-15">
                                        <p v-if="company && company.phone" class="">{{ company.phone }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-16">
                                        <p class="">Celular:</p>
                                    </div>
                                    <div class="box-value vl-16">
                                        <p v-if="company && company.cellphone" class="">{{ company.cellphone }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            Información de Medios Sociales
                        </div>
                        <div class="card-body">
                            <div class="wrapper-social mt-3">
                                <div class="data-pair">
                                    <div class="box-label lb-17">
                                        <p class="">Facebook:</p>
                                    </div>
                                    <div class="box-value vl-17">
                                        <p v-if="company && company.facebook" class="">{{ company.facebook }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-18">
                                        <p class="">Instagram:</p>
                                    </div>
                                    <div class="box-value vl-18">
                                        <p v-if="company && company.instagram" class="">{{ company.instagram }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-19">
                                        <p class="">LinkedIn:</p>
                                    </div>
                                    <div class="box-value vl-19">
                                        <p v-if="company && company.linkedin" class="">{{ company.linkedin }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-20">
                                        <p class="">X (Twitter):</p>
                                    </div>
                                    <div class="box-value vl-20">
                                        <p v-if="company && company.x_twitter" class="">{{ company.x_twitter }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-21">
                                        <p class="">YouTube:</p>
                                    </div>
                                    <div class="box-value vl-21">
                                        <p v-if="company && company.youtube" class="">{{ company.youtube }}</p>
                                        <p v-else class="">Sin asignar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="card_selected == 'negotiation'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <p class="mb-3">Negociación</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="card_selected == 'modules'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <p class="mb-3">Módulos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="card_selected == 'billing'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <p class="mb-3">Facturación</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        company: { default: null, },
        company_type: { default: null, },
        industry_type: { default: null, },
        identification_type: { default: null, },
        province: { default: null, },
        city: { default: null, },
    },
    data() {
        return {
            card_selected: 'general',
            message: '',

            province_id: '',
            city_id: '',
            cities: null,
            name: '',
            address: '',
            phone: '',
            email: '',

            area_campus: '',
            area_campus_id: '',
            area_leader_id: '',
            area_name: '',
            area_description: '',

            position_area_id: '',
            position_criticality_level_id: '',
            position_risk_class_id: '',
            position_name: '',
            position_estimated_salary: '',
            position_description: '',

            criticality_levels: [],
            risk_classes: [],

            contracts_data: [],
            campuses_data: [],
            areas_data: [],
            positions_data: [],
            collaborators_data: [],

            add_campus_data: false,
            edit_campus_data: false,
            selected_campus_data: null,
            campus_data_to_edit: null,

            add_area_data: false,
            edit_area_data: false,
            selected_area_data: null,
            area_data_to_edit: null,

            add_position_data: false,
            edit_position_data: false,
            selected_position_data: null,
            position_data_to_edit: null,

            successfully_created_message: false,
            successfully_updated_message: false,
            successfully_deleted_message: false,

            showMessageCampus: false,
            showMessageArea: false,
            showMessagePosition: false,

            errors_campus_data: null,
            errors_area_data: null,
            errors_position_data: null,

            origin: '',
        }
    },
    mounted () {
        this.getOrigin()

        this.getContractsData(this.company.id)

        this.getCampusesData(this.company.id)
        this.getAreasData(this.company.id)
        this.getPositionsData(this.company.id)
        this.getCollaboratorsData(this.company.id)
    },
    methods: {
        showDeleteAlert(action, item, index) {
            // Ver ejemplos en: https://sweetalert2.github.io/#examples
            this.$swal({
                title: "¿Seguro que deseas eliminar este registro?",
                text: "¡No podrás revertir esto!",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3f6ad8",
                cancelButtonColor: "#444054",
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "Cancelar",
            }).then((result) => {
            if (result.isConfirmed) {
                if(action == 'deleteCampusData') {
                    this.deleteCampusData(item, index)
                } else if(action == 'deleteAreaData') {
                    this.deleteAreaData(item, index)
                } else if(action == 'deletePositionData') {
                    this.deletePositionData(item, index)
                }
                this.$swal({
                    title: "Eliminado!",
                    text: "Su registro ha sido borrado.",
                    icon: "success"
                });
            }
            });
        },
        emitEditCompany() {
            this.$emit('editCompany', this.company.id)
        },
        emitLoadCollaborators() {
            this.$emit('loadCollaborators', this.company.id)
        },
        changeMainTab(tab) {
            this.card_selected = tab
            this.message = ''
        },
        getMessage(msg) {
            if(msg != '' && msg != null) {
                this.message = msg
            }

            setTimeout(() => {
                this.message = ''

                this.successfully_created_message = false
                this.successfully_updated_message = false
                this.successfully_deleted_message = false

                this.showMessageCampus = false
                this.showMessageArea = false
                this.showMessagePosition = false
            }, 3000)
        },
        getOrigin() {
            const origin = localStorage.getItem('origin');
            if (origin !== null) {
                this.origin = origin;
                localStorage.removeItem('origin');
            }
            setTimeout(() => {
                this.origin = '';
            }, 3000);
        },
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
        addCampusData() {
            if(this.add_campus_data == false) {
                this.add_campus_data = true
                this.edit_campus_data = false
            } else {
                this.edit_campus_data = false
            }

            this.selected_campus_data = null

            this.name = ''
            this.address = ''
            this.province_id = ''
            this.city_id = ''
            this.phone = ''
            this.email = ''

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.showMessageCampus = true
            this.showMessageArea = false
            this.showMessagePosition = false

            this.errors_campus_data = null
        },
        addAreaData() {
            if(this.add_area_data == false) {
                this.add_area_data = true
                this.edit_area_data = false
            } else {
                this.edit_area_data = false
            }

            this.selected_area_data = null

            this.area_campus_id = ''
            this.area_leader_id = ''
            this.area_name = ''
            this.area_description = ''

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.showMessageCampus = false
            this.showMessageArea = true
            this.showMessagePosition = false

            this.errors_area_data = null
        },
        addPositionData() {
            if(this.add_position_data == false) {
                this.add_position_data = true
                this.edit_position_data = false
            } else {
                this.edit_position_data = false
            }

            this.selected_position_data = null

            this.position_area_id = ''
            this.position_criticality_level_id = ''
            this.position_risk_class_id = ''
            this.position_name = ''
            this.position_estimated_salary = ''
            this.position_description = ''

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.showMessageCampus = false
            this.showMessageArea = false
            this.showMessagePosition = true

            this.errors_position_data = null
        },
        getContractsData(company_id) {
            axios.get(`/contracts-data/${company_id}`)
            .then(response => {
                this.contracts_data = response.data.contracts;
            })
            .catch(e => {
                //
            })
        },
        getCampusesData(company_id) {
            axios.get(`/campus-data/${company_id}`)
            .then(response => {
                this.campuses_data = response.data.campuses;
            })
            .catch(e => {
                //
            })
        },
        getAreasData(company_id) {
            axios.get(`/areas-data/${company_id}`)
            .then(response => {
                this.areas_data = response.data.areas;
            })
            .catch(e => {
                //
            })
        },
        getPositionsData(company_id) {
            axios.get(`/positions-data/${company_id}`)
            .then(response => {
                this.positions_data = response.data.positions;
                this.criticality_levels = response.data.criticality_levels;
                this.risk_classes = response.data.risk_classes;
            })
            .catch(e => {
                //
            })
        },
        getCollaboratorsData(company_id) {
            axios.get(`/collaborators-data/${company_id}`)
            .then(response => {
                this.collaborators_data = response.data.collaborators;
            })
            .catch(e => {
                //
            })
        },
        changeCampusData(id) {
            let new_selection_campus_data;

            if(this.campuses_data && this.campuses_data.length>0) {
                this.campuses_data.forEach(element => {
                    if(element.id !== id) {
                        //
                    } else {
                        new_selection_campus_data = element
                    }
                }, new_selection_campus_data);
            }

            this.selected_campus_data = new_selection_campus_data
            console.log('//////////////////////////////////////////////Prueba');
            console.log(this.selected_campus_data);

            this.add_campus_data = false
            this.edit_campus_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.showMessageCampus = false
            this.showMessageArea = false
            this.showMessagePosition = false
        },
        changeAreaData(id, index) {
            let new_selection_area_data;

            if(this.areas_data && this.areas_data.length>0) {
                this.areas_data.forEach(element => {
                    if(element.id !== id) {
                        //
                    } else {
                        new_selection_area_data = element
                    }
                }, new_selection_area_data);
            }

            this.selected_area_data = new_selection_area_data

            this.add_area_data = false
            this.edit_area_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.showMessageCampus = false
            this.showMessageArea = false
            this.showMessagePosition = false
        },
        changePositionData(id, index) {
            let new_selection_position_data;

            if(this.positions_data && this.positions_data.length>0) {
                this.positions_data.forEach(element => {
                    if(element.id !== id) {
                        //
                    } else {
                        new_selection_position_data = element
                    }
                }, new_selection_position_data);
            }

            this.selected_position_data = new_selection_position_data
            console.log(this.selected_position_data);

            this.add_position_data = false
            this.edit_position_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.showMessageCampus = false
            this.showMessageArea = false
            this.showMessagePosition = false
        },
        storeCampus() {
            let dataSend = {
                'company_id': this.company.id,
                'province_id': this.province_id,
                'city_id': this.city_id,
                'name': this.name,
                'address': this.address,
                'phone': this.phone,
                'email': this.email,
            }

            let url = ''
            axios.post('/campuses', dataSend).then(
                (response) => {
                    this.getCampusesData(this.company.id)
                    this.selected_campus_data = response.data.campus;
                    // this.campus_data = response.data.campus_data;
                    this.add_campus_data = false
                    this.edit_campus_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.showMessageCampus = true
                    this.showMessageArea = false
                    this.showMessagePosition = false

                    this.getMessage(response.data.message)

                    this.errors_campus_data = null

                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log('//////////////////////////////////////////////');
                        console.log(error.response.data.errors)
                        this.errors_campus_data = error.response.data.errors
                    }
                })
        },
        storeArea() {
            let dataSend = {
                'company_id': this.company.id,
                'campus_id': this.area_campus_id,
                'leader_id': this.area_leader_id,
                'name': this.area_name,
                'description': this.area_description,
            }

            let url = ''
            axios.post('/areas', dataSend).then(
                (response) => {
                    this.getAreasData(this.company.id)
                    this.selected_area_data = response.data.area;

                    this.add_area_data = false
                    this.edit_area_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.showMessageCampus = false
                    this.showMessageArea = true
                    this.showMessagePosition = false

                    this.getMessage(response.data.message)

                    this.errors_area_data = null

                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log('//////////////////////////////////////////////');
                        console.log(error.response.data.errors)
                        this.errors_area_data = error.response.data.errors
                    }
                })
        },
        storePosition() {
            let dataSend = {
                'company_id': this.company.id,
                'area_id': this.position_area_id,
                'criticality_level_id': this.position_criticality_level_id,
                'risk_class_id': this.position_risk_class_id,
                'name': this.position_name,
                'estimated_salary': this.position_estimated_salary,
                'description': this.position_description,
            }

            let url = ''
            axios.post('/positions', dataSend).then(
                (response) => {
                    this.getPositionsData(this.company.id)
                    this.selected_position_data = response.data.position;

                    this.add_position_data = false
                    this.edit_position_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.showMessageCampus = false
                    this.showMessageArea = false
                    this.showMessagePosition = true

                    this.getMessage(response.data.message)

                    this.errors_position_data = null

                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log('//////////////////////////////////////////////');
                        console.log(error.response.data.errors)
                        this.errors_position_data = error.response.data.errors
                    }
                })
        },
        editCampusData(item, index) {
            let new_selection_campus_data;

            if(this.campuses_data && this.campuses_data.length>0) {
                this.campuses_data.forEach(element => {
                    if(element.id !== item.id) {
                        //
                    } else {
                        new_selection_campus_data = element
                    }
                }, new_selection_campus_data);
            }
            this.selected_campus_data = new_selection_campus_data

            this.campus_data_to_edit = item

            this.add_campus_data = false
            this.edit_campus_data = true

            this.name = item.name
            this.address = item.address
            this.province_id = this.campus_data_to_edit.province_id
            this.getCities(this.province_id)
            this.city_id = this.campus_data_to_edit.city_id
            this.phone = item.phone
            this.email = item.email

            this.errors_campus_data = null
        },
        editAreaData(item, index) {
            let new_selection_area_data;

            if(this.areas_data && this.areas_data.length>0) {
                this.areas_data.forEach(element => {
                    if(element.id !== item.id) {
                        //
                    } else {
                        new_selection_area_data = element
                    }
                }, new_selection_area_data);
            }
            this.selected_area_data = new_selection_area_data

            this.area_data_to_edit = item

            this.add_area_data = false
            this.edit_area_data = true

            this.area_campus_id = item.campus_id
            this.area_leader_id = item.leader_id
            this.area_name = item.name
            this.area_description = item.description

            this.errors_area_data = null
        },
        editPositionData(item, index) {
            let new_selection_position_data;

            if(this.positions_data && this.positions_data.length>0) {
                this.positions_data.forEach(element => {
                    if(element.id !== item.id) {
                        //
                    } else {
                        new_selection_position_data = element
                    }
                }, new_selection_position_data);
            }
            this.selected_position_data = new_selection_position_data

            this.position_data_to_edit = item

            this.add_position_data = false
            this.edit_position_data = true

            this.position_area_id = item.area_id
            this.position_criticality_level_id = item.criticality_level_id
            this.position_risk_class_id = item.risk_class_id
            this.position_name = item.name
            this.position_estimated_salary = item.estimated_salary
            this.position_description = item.description

            this.errors_position_data = null
        },
        updateCampus() {
            let dataSend = {
                'id': this.campus_data_to_edit.id,
                'company_id': this.company.id,
                'province_id': this.province_id,
                'city_id': this.city_id,
                'name': this.name,
                'address': this.address,
                'phone': this.phone,
                'email': this.email,
            }

            let url = ''
            axios.put(`/campuses/${this.campus_data_to_edit.id}`, dataSend).then(
                (response) => {
                    this.getCampusesData(this.company.id)
                    this.selected_campus_data = response.data.campus

                    this.add_campus_data = false
                    this.edit_campus_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.showMessageCampus = true
                    this.showMessageArea = false
                    this.showMessagePosition = false

                    this.getMessage(response.data.message)

                    this.errors_campus_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors_campus_data = error.response.data.errors
                    }
                })
        },
        updateArea() {
            let dataSend = {
                'id': this.area_data_to_edit.id,
                'company_id': this.company.id,
                'campus_id': this.area_campus_id,
                'leader_id': this.area_leader_id,
                'name': this.area_name,
                'description': this.area_description,
            }

            let url = ''
            axios.put(`/areas/${this.area_data_to_edit.id}`, dataSend).then(
                (response) => {
                    this.getAreasData(this.company.id)
                    this.selected_area_data = response.data.area

                    this.add_area_data = false
                    this.edit_area_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.showMessageCampus = false
                    this.showMessageArea = true
                    this.showMessagePosition = false

                    this.getMessage(response.data.message)

                    this.errors_area_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors_area_data = error.response.data.errors
                    }
                })
        },
        updatePosition() {
            let dataSend = {
                'id': this.position_data_to_edit.id,
                'company_id': this.company.id,
                'area_id': this.position_area_id,
                'criticality_level_id': this.position_criticality_level_id,
                'risk_class_id': this.position_risk_class_id,
                'name': this.position_name,
                'estimated_salary': this.position_estimated_salary,
                'description': this.position_description,
            }

            let url = ''
            axios.put(`/positions/${this.position_data_to_edit.id}`, dataSend).then(
                (response) => {
                    this.getPositionsData(this.company.id)
                    this.selected_position_data = response.data.position;

                    this.add_position_data = false
                    this.edit_position_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.showMessageCampus = false
                    this.showMessageArea = false
                    this.showMessagePosition = true

                    this.getMessage(response.data.message)

                    this.errors_position_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors_position_data = error.response.data.errors
                    }
                })
        },
        deleteCampusData(item, index) {

            axios.delete(`/campus-data-delete/${item.id}`).then(
                (response) => {
                    this.getCampusesData(this.company.id)

                    this.add_campus_data = false
                    this.edit_campus_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.showMessageCampus = true
                    this.showMessageArea = false
                    this.showMessagePosition = false

                    this.getMessage(response.data.message)

                    this.selected_campus_data = null
                    this.errors_campus_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors_campus_data = error.response.data.errors
                    }
                })
        },
        deleteAreaData(item, index) {

            axios.delete(`/area-data-delete/${item.id}`).then(
                (response) => {
                    this.getAreasData(this.company.id)

                    this.add_area_data = false
                    this.edit_area_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.showMessageCampus = false
                    this.showMessageArea = true
                    this.showMessagePosition = false

                    this.getMessage(response.data.message)

                    this.selected_area_data = null
                    this.errors_area_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors_area_data = error.response.data.errors
                    }
                })
        },
        deletePositionData(item, index) {

            axios.delete(`/position-data-delete/${item.id}`).then(
                (response) => {
                    this.getPositionsData(this.company.id)

                    this.add_position_data = false
                    this.edit_position_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.showMessageCampus = false
                    this.showMessageArea = false
                    this.showMessagePosition = true

                    this.getMessage(response.data.message)

                    this.selected_position_data = null
                    this.errors_position_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        console.log(error.response.data.errors)
                        this.errors_position_data = error.response.data.errors
                    }
                })
        },
        numberFormat(number) {
            const exp = /(\d)(?=(\d{3})+(?!\d))/g
            const rep = '$1.'
            let arr = number.toString().split('.')
            arr[0] = arr[0].replace(exp,rep)
            return arr[1] ? arr.join('.'): arr[0]
        },
    },
}
</script>

<style scoped>
    @import './../../assets/css/company_detail.css';
    @import './../../assets/css/custom.css';
</style>
