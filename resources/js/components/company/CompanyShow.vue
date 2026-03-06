<template>
    <div>
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
                            <!-- <h5 class="menu-header-title">{{ collaborator.name }}</h5>
                            <h6 class="menu-header-subtitle">{{ collaborator.email }}</h6> -->

                            <h5 class="menu-header-title">{{ company.company_name }}</h5>
                            <h6 class="menu-header-subtitle">Información complementaria</h6>
                        </div>
                        <div class="menu-header-btn-pane">
                            <!-- <a :href="`/collaborators/${collaborator.id}/edit`" class="ladda-button btn btn-pill btn-light" data-style="slide-right">
                                <span class="ladda-label">Editar</span>
                                <span class="ladda-spinner"></span>
                            </a> -->
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
                                <a @click="changeMainTab('campus')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-pushpin btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Sedes
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('areas')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-crop btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Áreas
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('positions')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-rocket btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Cargos
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
        <div v-else-if="card_selected == 'campus'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div v-if="message" class="message-success mb-3">
                                <div class="content d-flex align-items-start p-2">
                                    <p class="mb-0" style="font-size: 14px;"> {{ this.message }}</p>
                                </div>
                            </div>
                            <div v-if="campuses_data && campuses_data.length>0" class="row">
                                <div class="col-md-12 col-lg-6">
                                    <a @click="addCampusData" class="wrapper-add-data p-2 mb-3" :class="(add_campus_data && !edit_campus_data) ? 'selected shadow' : ''">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Sede</p>
                                    </a>
                                </div>
                                <div v-for="(item, index) in campuses_data" class="col-md-12 col-lg-6">
                                    <div class="wrapper-data mb-3 position-relative" :class="(selected_campus_data && selected_campus_data.id) == item.id ? 'selected shadow' : ''">
                                        <div @click="changeCampusData(item.id)" class="box box1">
                                            <div class="preliminary-information">
                                                <p class="data-position-one text-truncate w-100">{{ item.name }}</p>
                                                <p class="data-position-two text-truncate w-100">{{ item.address }}</p>
                                                <p class="data-position-three text-truncate w-100">{{ item.city.name }} ({{ item.province.name }})</p>
                                            </div>
                                        </div>
                                        <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                            <a class="edit-data" @click="editCampusData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                            <a v-if="areas_data && !areas_data.some(area => area.campus_id === item.id)" class="delete-data" @click="showDeleteAlert('deleteCampusData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="row">
                                <div class="col-md-12">
                                    <a @click="addCampusData" class="wrapper-add-data p-2 mb-3">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Sede</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                    <div v-if="add_campus_data && !edit_campus_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card add-campus-card">
                                            <div class="card-header">
                                                Agregar Sede
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <input v-model="name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_campus_data && errors_campus_data.name" class="error text-danger" for="name">{{ errors_campus_data.name[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="address" class="form-label">Dirección</label>
                                                            <input v-model="address" name="address" id="address" placeholder="Ingresar dirección" type="text" class="form-control">
                                                            <span v-if="errors_campus_data && errors_campus_data.address" class="error text-danger" for="address">{{ errors_campus_data.address[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="area_id" class="form-label">Departamento</label>
                                                            <select v-model="province_id" @change="getCities(province_id)" class="form-control" name="province" id="province">
                                                                <option value="">-- Seleccionar Departamento --</option>
                                                                <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                                                            </select>
                                                            <span v-if="errors_campus_data && errors_campus_data.province_id" class="error text-danger" for="province">{{ errors_campus_data.province_id[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="area_id" class="form-label">Municipio</label>
                                                            <select v-model="city_id" class="form-control" name="city" id="city">
                                                                <option value="" :selected="cities === []">-- Seleccionar Municipio --</option>
                                                                <option v-for="city in cities" :value="city.id">{{ city.name }}</option>
                                                            </select>
                                                            <span v-if="errors_campus_data && errors_campus_data.city_id" class="error text-danger" for="city">{{ errors_campus_data.city_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="phone" class="form-label">Teléfono</label>
                                                            <input v-model="phone" name="phone" id="phone" placeholder="Ingresar teléfono" type="text" class="form-control">
                                                            <span v-if="errors_campus_data && errors_campus_data.phone" class="error text-danger" for="phone">{{ errors_campus_data.phone[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input v-model="email" name="email" id="email" placeholder="Ingresar email" type="text" class="form-control">
                                                            <span v-if="errors_campus_data && errors_campus_data.email" class="error text-danger" for="email">{{ errors_campus_data.email[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="storeCampus()" type="submit" class="mt-2 btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_campus_data && edit_campus_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card edit-campus-card">
                                            <div class="card-header">
                                                Editar Sede
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <input v-model="name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_campus_data && errors_campus_data.name" class="error text-danger" for="name">{{ errors_campus_data.name[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="address" class="form-label">Dirección</label>
                                                            <input v-model="address" name="address" id="address" placeholder="Ingresar dirección" type="text" class="form-control">
                                                            <span v-if="errors_campus_data && errors_campus_data.address" class="error text-danger" for="address">{{ errors_campus_data.address[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="area_id" class="form-label">Departamento</label>
                                                            <select v-model="province_id" @change="getCities(province_id)" class="form-control" name="province" id="province">
                                                                <option value="">-- Seleccionar Departamento --</option>
                                                                <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                                                            </select>
                                                            <span v-if="errors_campus_data && errors_campus_data.province_id" class="error text-danger" for="province">{{ errors_campus_data.province_id[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="area_id" class="form-label">Municipio</label>
                                                            <select v-model="city_id" class="form-control" name="city" id="city">
                                                                <option value="" :selected="cities === []">-- Seleccionar Municipio --</option>
                                                                <option v-for="city in cities" :value="city.id">{{ city.name }}</option>
                                                            </select>
                                                            <span v-if="errors_campus_data && errors_campus_data.city_id" class="error text-danger" for="city">{{ errors_campus_data.city_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="phone" class="form-label">Teléfono</label>
                                                            <input v-model="phone" name="phone" id="phone" placeholder="Ingresar teléfono" type="text" class="form-control">
                                                            <span v-if="errors_campus_data && errors_campus_data.phone" class="error text-danger" for="phone">{{ errors_campus_data.phone[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input v-model="email" name="email" id="email" placeholder="Ingresar email" type="text" class="form-control">
                                                            <span v-if="errors_campus_data && errors_campus_data.email" class="error text-danger" for="email">{{ errors_campus_data.email[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <button type="submit" class="mt-2 btn btn-primary">Actualizar</button> -->
                                <button @click="updateCampus()" type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_campus_data && !edit_campus_data && selected_campus_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card edit-campus-card">
                                            <div class="card-header">
                                                Información de la Sede
                                            </div>
                                            <div class="card-body">
                                                <div class="wrapper-campus mt-3">
                                                    <div class="data-pair">
                                                        <div class="box-label lb-22">
                                                            <p class="">Nombre:</p>
                                                        </div>
                                                        <div class="box-value vl-22">
                                                            <p class="">{{ selected_campus_data.name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-23">
                                                            <p class="">Ubicación:</p>
                                                        </div>
                                                        <div class="box-value vl-23">
                                                            <p class="">{{ selected_campus_data.city.name }} ({{ selected_campus_data.province.name }})</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-24">
                                                            <p class="">Dirección:</p>
                                                        </div>
                                                        <div class="box-value vl-24">
                                                            <p class="">{{ selected_campus_data.address }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-25">
                                                            <p class="">Teléfono:</p>
                                                        </div>
                                                        <div class="box-value vl-25">
                                                            <p class="">{{ selected_campus_data.phone }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-26">
                                                            <p class="">Correo electrónico:</p>
                                                        </div>
                                                        <div class="box-value vl-26">
                                                            <p class="">{{ selected_campus_data.email }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="card_selected == 'areas'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div v-if="message" class="message-success mb-3">
                                <div class="content d-flex align-items-start p-2">
                                    <p class="mb-0" style="font-size: 14px;"> {{ this.message }}</p>
                                </div>
                            </div>
                            <div v-if="areas_data && areas_data.length>0" class="row">
                                <div class="col-md-12 col-lg-6">
                                    <a @click="addAreaData" class="wrapper-add-data p-2 mb-3" :class="(add_area_data && !edit_area_data) ? 'selected shadow' : ''">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Área</p>
                                    </a>
                                </div>
                                <div v-for="(item, index) in areas_data" class="col-md-12 col-lg-6">
                                    <div class="wrapper-data mb-3 position-relative" :class="(selected_area_data && selected_area_data.id) == item.id ? 'selected shadow' : ''">
                                        <div @click="changeAreaData(item.id, index)" class="box box1">
                                            <div class="preliminary-information">
                                                <p class="data-position-one text-truncate w-100">{{ item.name }}</p>
                                                <p class="data-position-two text-truncate w-100">Sede: {{ item.campus.name }}</p>
                                                <p class="data-position-three text-truncate w-100">Líder: {{ item.leader.name }} {{ item.leader.first_surname }} {{ item.leader.second_surname }}</p>
                                            </div>
                                        </div>
                                        <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                            <a class="edit-data" @click="editAreaData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                            <a v-if="positions_data && !positions_data.some(position => position.area_id === item.id)" class="delete-data" @click="showDeleteAlert('deleteAreaData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="row">
                                <div class="col-md-12">
                                    <a @click="addAreaData" class="wrapper-add-data p-2 mb-3">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Área</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                    <div v-if="add_area_data && !edit_area_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card add-campus-card">
                                            <div class="card-header">
                                                Agregar Área
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <input v-model="area_name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_area_data && errors_area_data.name" class="error text-danger" for="name">{{ errors_area_data.name[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="campus" class="form-label">Sede</label>
                                                            <select v-model="area_campus_id" class="form-control" name="campus_id" id="campus">
                                                                <option value="">-- Seleccionar Sede --</option>
                                                                <option v-for="campus in campuses_data" :value="campus.id">{{ campus.name }}</option>
                                                            </select>
                                                            <span v-if="errors_area_data && errors_area_data.campus_id" class="error text-danger" for="campus">{{ errors_area_data.campus_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="leader" class="form-label">Líder de área</label>
                                                            <select v-model="area_leader_id" class="form-control" name="leader_id" id="leader">
                                                                <option value="">-- Seleccionar Líder --</option>
                                                                <option v-for="collaborator in collaborators_data" :value="collaborator.id">{{ collaborator.name }} {{ collaborator.first_surname }} {{ collaborator.second_surname }}</option>
                                                            </select>
                                                            <span v-if="errors_area_data && errors_area_data.leader_id" class="error text-danger" for="leader">{{ errors_area_data.leader_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="description" class="form-label">Descripción</label>
                                                            <input v-model="area_description" name="description" id="description" placeholder="Ingresar descripción" type="text" class="form-control">
                                                            <span v-if="errors_area_data && errors_area_data.description" class="error text-danger" for="description">{{ errors_area_data.description[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="storeArea()" type="submit" class="mt-2 btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_area_data && edit_area_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card edit-area-card">
                                            <div class="card-header">
                                                Editar Área
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <input v-model="area_name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_area_data && errors_area_data.name" class="error text-danger" for="name">{{ errors_area_data.name[0] }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="campus" class="form-label">Sede</label>
                                                            <select v-model="area_campus_id" class="form-control" name="campus_id" id="campus">
                                                                <option value="">-- Seleccionar Sede --</option>
                                                                <option v-for="campus in campuses_data" :value="campus.id">{{ campus.name }}</option>
                                                            </select>
                                                            <span v-if="errors_area_data && errors_area_data.campus_id" class="error text-danger" for="campus">{{ errors_area_data.campus_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="leader" class="form-label">Líder de área</label>
                                                            <select v-model="area_leader_id" class="form-control" name="leader_id" id="leader">
                                                                <option value="">-- Seleccionar Líder --</option>
                                                                <option v-for="collaborator in collaborators_data" :value="collaborator.id">{{ collaborator.name }} {{ collaborator.first_surname }} {{ collaborator.second_surname }}</option>
                                                            </select>
                                                            <span v-if="errors_area_data && errors_area_data.leader_id" class="error text-danger" for="leader">{{ errors_area_data.leader_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="description" class="form-label">Descripción</label>
                                                            <input v-model="area_description" name="description" id="description" placeholder="Ingresar descripción" type="text" class="form-control">
                                                            <span v-if="errors_area_data && errors_area_data.description" class="error text-danger" for="description">{{ errors_area_data.description[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="updateArea()" type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_area_data && !edit_area_data && selected_area_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card edit-area-card">
                                            <div class="card-header">
                                                Información del Área
                                            </div>
                                            <div class="card-body">
                                                <div class="wrapper-area mt-3">
                                                    <div class="data-pair">
                                                        <div class="box-label lb-27">
                                                            <p class="">Nombre:</p>
                                                        </div>
                                                        <div class="box-value vl-27">
                                                            <p class="">{{ selected_area_data.name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-28">
                                                            <p class="">Sede:</p>
                                                        </div>
                                                        <div class="box-value vl-28">
                                                            <p class="">{{ selected_area_data.campus.name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-29">
                                                            <p class="">Líder:</p>
                                                        </div>
                                                        <div class="box-value vl-29">
                                                            <p class="">{{ selected_area_data.leader.name }} {{ selected_area_data.leader.first_surname }} {{ selected_area_data.leader.second_surname }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-30">
                                                            <p class="">Descripción:</p>
                                                        </div>
                                                        <div class="box-value vl-30">
                                                            <p class="">{{ selected_area_data.description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="card_selected == 'positions'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div v-if="message" class="message-success mb-3">
                                <div class="content d-flex align-items-start p-2">
                                    <p class="mb-0" style="font-size: 14px;"> {{ this.message }}</p>
                                </div>
                            </div>
                            <div v-if="positions_data && positions_data.length>0" class="row">
                                <div class="col-md-12 col-lg-6">
                                    <a @click="addPositionData" class="wrapper-add-data p-2 mb-3" :class="(add_position_data && !edit_position_data) ? 'selected shadow' : ''">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Cargo</p>
                                    </a>
                                </div>
                                <div v-for="(item, index) in positions_data" class="col-md-12 col-lg-6">
                                    <div class="wrapper-data mb-3 position-relative" :class="(selected_position_data && selected_position_data.id) == item.id ? 'selected shadow' : ''">
                                        <div @click="changePositionData(item.id, index)" class="box box1">
                                            <div class="preliminary-information">
                                                <p class="data-position-one text-truncate w-100">{{ item.name }}</p>
                                                <p class="data-position-two text-truncate w-100">Área: {{ item.area.name }}</p>
                                                <p class="data-position-three text-truncate w-100">Salario estimado: $ {{ numberFormat(Math.round(item.estimated_salary)) }}</p>
                                            </div>
                                        </div>
                                        <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                            <a class="edit-data" @click="editPositionData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                            <a v-if="contracts_data && !contracts_data.some(contract => contract.position_id === item.id)" class="delete-data" @click="showDeleteAlert('deletePositionData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="row">
                                <div class="col-md-12">
                                    <a @click="addPositionData" class="wrapper-add-data p-2 mb-3">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Cargo</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                    <div v-if="add_position_data && !edit_position_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card add-campus-card">
                                            <div class="card-header">
                                                Agregar Cargo
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <input v-model="position_name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_position_data && errors_position_data.name" class="error text-danger" for="name">{{ errors_position_data.name[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="area" class="form-label">Área</label>
                                                            <select v-model="position_area_id" class="form-control" name="area_id" id="area">
                                                                <option value="">-- Seleccionar Área --</option>
                                                                <option v-for="area in areas_data" :value="area.id">{{ area.name }}</option>
                                                            </select>
                                                            <span v-if="errors_position_data && errors_position_data.area_id" class="error text-danger" for="area">{{ errors_position_data.area_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="estimated_salary" class="form-label">Salario estimado</label>
                                                            <input v-model="position_estimated_salary" name="estimated_salary" id="estimated_salary" placeholder="Ingresar salario estimado" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_position_data && errors_position_data.estimated_salary" class="error text-danger" for="estimated_salary">{{ errors_position_data.estimated_salary[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="criticality_level" class="form-label">Nivel de criticidad</label>
                                                            <select v-model="position_criticality_level_id" class="form-control" name="criticality_level_id" id="criticality_level">
                                                                <option value="">-- Seleccionar Criticidad --</option>
                                                                <option v-for="criticality_level in criticality_levels" :value="criticality_level.id">{{ criticality_level.level }}</option>
                                                            </select>
                                                            <span v-if="errors_position_data && errors_position_data.criticality_level_id" class="error text-danger" for="criticality_level">{{ errors_position_data.criticality_level_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="risk_class" class="form-label">Clase de riesgo</label>
                                                            <select v-model="position_risk_class_id" class="form-control" name="risk_class_id" id="risk_class">
                                                                <option value="">-- Seleccionar Riesgo --</option>
                                                                <option v-for="risk_class in risk_classes" :value="risk_class.id">{{ risk_class.class }} - {{ risk_class.description }}</option>
                                                            </select>
                                                            <span v-if="errors_position_data && errors_position_data.risk_class_id" class="error text-danger" for="risk_class">{{ errors_position_data.risk_class_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="description" class="form-label">Descripción</label>
                                                            <input v-model="position_description" name="description" id="description" placeholder="Ingresar descripción" type="text" class="form-control">
                                                            <span v-if="errors_position_data && errors_position_data.description" class="error text-danger" for="description">{{ errors_position_data.description[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="storePosition()" type="submit" class="mt-2 btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_position_data && edit_position_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card add-campus-card">
                                            <div class="card-header">
                                                Editar Cargo
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="name" class="form-label">Nombre</label>
                                                            <input v-model="position_name" name="name" id="name" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_position_data && errors_position_data.name" class="error text-danger" for="name">{{ errors_position_data.name[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="area" class="form-label">Área</label>
                                                            <select v-model="position_area_id" class="form-control" name="area_id" id="area">
                                                                <option value="">-- Seleccionar Área --</option>
                                                                <option v-for="area in areas_data" :value="area.id">{{ area.name }}</option>
                                                            </select>
                                                            <span v-if="errors_position_data && errors_position_data.area_id" class="error text-danger" for="area">{{ errors_position_data.area_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="estimated_salary" class="form-label">Salario estimado</label>
                                                            <input v-model="position_estimated_salary" name="estimated_salary" id="estimated_salary" placeholder="Ingresar salario estimado" type="text" class="form-control" autofocus>
                                                            <span v-if="errors_position_data && errors_position_data.estimated_salary" class="error text-danger" for="estimated_salary">{{ errors_position_data.estimated_salary[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="criticality_level" class="form-label">Nivel de criticidad</label>
                                                            <select v-model="position_criticality_level_id" class="form-control" name="criticality_level_id" id="criticality_level">
                                                                <option value="">-- Seleccionar Criticidad --</option>
                                                                <option v-for="criticality_level in criticality_levels" :value="criticality_level.id">{{ criticality_level.level }}</option>
                                                            </select>
                                                            <span v-if="errors_position_data && errors_position_data.criticality_level_id" class="error text-danger" for="criticality_level">{{ errors_position_data.criticality_level_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="position-relative mb-3">
                                                            <label for="risk_class" class="form-label">Clase de riesgo</label>
                                                            <select v-model="position_risk_class_id" class="form-control" name="risk_class_id" id="risk_class">
                                                                <option value="">-- Seleccionar Riesgo --</option>
                                                                <option v-for="risk_class in risk_classes" :value="risk_class.id">{{ risk_class.class }} - {{ risk_class.description }}</option>
                                                            </select>
                                                            <span v-if="errors_position_data && errors_position_data.risk_class_id" class="error text-danger" for="risk_class">{{ errors_position_data.risk_class_id[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="position-relative mb-3">
                                                            <label for="description" class="form-label">Descripción</label>
                                                            <input v-model="position_description" name="description" id="description" placeholder="Ingresar descripción" type="text" class="form-control">
                                                            <span v-if="errors_position_data && errors_position_data.description" class="error text-danger" for="description">{{ errors_position_data.description[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button @click="updatePosition()" type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_position_data && !edit_position_data && selected_position_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card edit-area-card">
                                            <div class="card-header">
                                                Información del Cargo
                                            </div>
                                            <div class="card-body">
                                                <div class="wrapper-position mt-3">
                                                    <div class="data-pair">
                                                        <div class="box-label lb-31">
                                                            <p class="">Nombre:</p>
                                                        </div>
                                                        <div class="box-value vl-31">
                                                            <p class="">{{ selected_position_data.name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-32">
                                                            <p class="">Área:</p>
                                                        </div>
                                                        <div class="box-value vl-32">
                                                            <p class="">{{ selected_position_data.area.name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-33">
                                                            <p class="">Salario:</p>
                                                        </div>
                                                        <div class="box-value vl-33">
                                                            <p class="">$ {{ numberFormat(selected_position_data.estimated_salary) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-34">
                                                            <p class="">Criticidad:</p>
                                                        </div>
                                                        <div class="box-value vl-34">
                                                            <p class="">{{ selected_position_data.criticality_level.level }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-35">
                                                            <p class="">Riesgo:</p>
                                                        </div>
                                                        <div class="box-value vl-35">
                                                            <p class="">{{ selected_position_data.risk_class.class }} - {{ selected_position_data.risk_class.description }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-36">
                                                            <p class="">Descripción:</p>
                                                        </div>
                                                        <div class="box-value vl-36">
                                                            <p class="">{{ selected_position_data.description }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        provinces: { default: null, },
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
        }
    },
    mounted () {
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
    @import './../../assets/css/company_show.css';
</style>
