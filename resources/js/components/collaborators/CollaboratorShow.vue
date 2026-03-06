<template>
    <div>
        <div v-if="origin == 'updated'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
            <span class="pe-2">
                <i class="fa fa-star"></i>
            </span>
            Colaborador actualizado correctamente!
        </div>
        <div v-else-if="origin == 'created'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
            <span class="pe-2">
                <i class="fa fa-star"></i>
            </span>
            Colaborador creado correctamente!
        </div>
        <div v-else-if="origin == 'contractual_info_updated'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
            <span class="pe-2">
                <i class="fa fa-star"></i>
            </span>
            Información contractual actualizada correctamente!
        </div>
        <div v-else-if="origin == 'contractual_info_created'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
            <span class="pe-2">
                <i class="fa fa-star"></i>
            </span>
            Información contractual creada correctamente!
        </div>

        <!-- <div class="card-shadow-primary profile-responsive card-border mb-3 card"> -->
        <div class="card-shadow-primary profile-responsive card-border mb-3 card">
            <div class="dropdown-menu-header">
                <div class="dropdown-menu-header-inner" style="background-color: #127cb3;">
                    <div class="menu-header-image opacity-3" style="background-image: url('');"></div>
                    <div class="menu-header-content btn-pane-right">
                        <div class="avatar-icon-wrapper me-2 avatar-icon-xl">
                            <div v-if="collaborator && collaborator.image_url" class="avatar-icon rounded">
                                <img :src="collaborator.image_url" :alt="collaborator.name">
                            </div>
                            <div v-else class="avatar-icon rounded">
                                <img :src="'/images/default-profile.jpeg'" :alt="collaborator.name">
                            </div>
                        </div>
                        <div>
                            <h5 class="menu-header-title">{{ collaborator.name }}</h5>
                            <h6 class="menu-header-subtitle">{{ collaborator.email }}</h6>
                        </div>
                        <div class="menu-header-btn-pane">
                            <button @click="emitEditCollaborator" class="btn btn-lg btn-mh-white">
                                <font-awesome-icon :icon="['fas', 'pen-to-square']" />
                                Editar colaborador
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
                                <a @click="changeMainTab('basic')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-smile btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Información Básica
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('contract')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-layers btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Información Contractual
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('additional')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-frame-expand btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Información Adicional
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a @click="changeMainTab('documents')" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
                                    <i class="lnr-map btn-icon-wrapper btn-icon-lg mb-3"></i>
                                    Documentos
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div v-if="card_selected == 'basic'">
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
                                        <p class="">Nombre completo:</p>
                                    </div>
                                    <div class="box-value vl-1">
                                        <p class="">{{ collaborator.name }} {{ collaborator.first_surname }} {{ collaborator.second_surname }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-2">
                                        <p class="">Tipo de documento:</p>
                                    </div>
                                    <div class="box-value vl-2">
                                        <p class="">{{ document_type.type }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-3">
                                        <p class="">Número de documento:</p>
                                    </div>
                                    <div class="box-value vl-3">
                                        <p class="">{{ collaborator.document_number }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-4">
                                        <p class="">Lugar de expedición:</p>
                                    </div>
                                    <div class="box-value vl-4">
                                        <p class="">{{ document_city.name }} ({{ document_province.name }})</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-5">
                                        <p class="">Fecha de expedición:</p>
                                    </div>
                                    <div class="box-value vl-5">
                                        <p class="">{{ collaborator.expedition_date }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-6">
                                        <p class="">Lugar de nacimiento:</p>
                                    </div>
                                    <div class="box-value vl-6">
                                        <p class="">{{ birth_city.name }} ({{ birth_province.name }})</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-7">
                                        <p class="">Fecha de nacimiento:</p>
                                    </div>
                                    <div class="box-value vl-7">
                                        <p class="">{{ collaborator.birth_date }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-8">
                                        <p class="">Estado civil:</p>
                                    </div>
                                    <div class="box-value vl-8">
                                        <p class="">{{ civil_status.type }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-9">
                                        <p class="">Sexo:</p>
                                    </div>
                                    <div class="box-value vl-9">
                                        <p class="">{{ sex_type.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-10">
                                        <p class="">Tipo de sangre:</p>
                                    </div>
                                    <div class="box-value vl-10">
                                        <p class="">{{ rh_type.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-11">
                                        <p class="">Mayor logro académico:</p>
                                    </div>
                                    <div class="box-value vl-11">
                                        <p class="">{{ highest_academic_achievement.type }}</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-12">
                                        <p class="">Observaciones</p>
                                    </div>
                                    <div class="box-value vl-12">
                                        <p class="">{{ (collaborator.observations == null || collaborator.observations == '') ? 'Sin asignar' : collaborator.observations }}</p>
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
                            Información de Domicilio
                        </div>
                        <div class="card-body">
                            <div class="wrapper-address mt-3">
                                <div class="data-pair">
                                    <div class="box-label lb-13">
                                        <p class="">Lugar de residencia:</p>
                                    </div>
                                    <div class="box-value vl-13">
                                        <p class="">{{ residence_city.name }} ({{ residence_province.name }})</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-14">
                                        <p class="">Estrato social:</p>
                                    </div>
                                    <div class="box-value vl-14">
                                        <p class="">{{ stratum_type.id }} - {{ stratum_type.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-15">
                                        <p class="">Dirección:</p>
                                    </div>
                                    <div class="box-value vl-15">
                                        <p class="">{{ collaborator.address }}</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-16">
                                        <p class="">Tenencia:</p>
                                    </div>
                                    <div class="box-value vl-16">
                                        <p class="">{{ housing_tenure.type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            Información de Contacto
                        </div>
                        <div class="card-body">
                            <div class="wrapper-contact mt-3">
                                <div class="data-pair">
                                    <div class="box-label lb-17">
                                        <p class="">Teléfono fijo:</p>
                                    </div>
                                    <div class="box-value vl-17">
                                        <p class="">{{ collaborator.phone }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-18">
                                        <p class="">Celular:</p>
                                    </div>
                                    <div class="box-value vl-18">
                                        <p class="">{{ collaborator.cellphone }}</p>
                                    </div>
                                </div>
                                <div class="data-pair full-width">
                                    <div class="box-label lb-19">
                                        <p class="">Email:</p>
                                    </div>
                                    <div class="box-value vl-19">
                                        <p class="">{{ collaborator.email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div v-else-if="card_selected == 'contract'">
            <div class="row">
                <div class="col-12">
                    <div v-if="contractual_information !== null && contractual_information !== ''" class="main-card mb-3 card">
                        <div class="card-header">
                            Información Contractual
                        </div>
                        <div class="card-body">
                            <div class="wrapper-contract mt-3">
                                <div class="data-pair">
                                    <div class="box-label lb-25">
                                        <p class="">Cargo:</p>
                                    </div>
                                    <div class="box-value vl-25">
                                        <p v-if="contractual_information && contractual_information.position && contractual_information.position.name" class="">{{ contractual_information.position.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-26">
                                        <p class="">Salario:</p>
                                    </div>
                                    <div class="box-value vl-26">
                                        <p v-if="contractual_information && contractual_information.salary" class="">$ {{ numberFormat(Math.round(contractual_information.salary)) }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-27">
                                        <p class="">Tipo de contrato:</p>
                                    </div>
                                    <div class="box-value vl-27">
                                        <p v-if="contractual_information && contractual_information.contract_type && contractual_information.contract_type.name" class="">{{ contractual_information.contract_type.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-28">
                                        <p class="">Fecha inicio:</p>
                                    </div>
                                    <div class="box-value vl-28">
                                        <p v-if="contractual_information && contractual_information.contract_start_date" class="">{{ contractual_information.contract_start_date }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-29">
                                        <p class="">Fecha fin:</p>
                                    </div>
                                    <div class="box-value vl-29">
                                        <p v-if="contractual_information && contractual_information.contract_end_date" class="">{{ contractual_information.contract_end_date }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-30">
                                        <p class="">Fecha fin prueba:</p>
                                    </div>
                                    <div class="box-value vl-30">
                                        <p v-if="contractual_information && contractual_information.test_period_end_date" class="">{{ contractual_information.test_period_end_date }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-31">
                                        <p class="">Caja de compensación:</p>
                                    </div>
                                    <div class="box-value vl-31">
                                        <p v-if="contractual_information && contractual_information.ccf && contractual_information.ccf.name" class="">{{ contractual_information.ccf.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-32">
                                        <p class="">EPS:</p>
                                    </div>
                                    <div class="box-value vl-32">
                                        <p v-if="contractual_information && contractual_information.eps && contractual_information.eps.name" class="">{{ contractual_information.eps.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-33">
                                        <p class="">AFP Pensiones:</p>
                                    </div>
                                    <div class="box-value vl-33">
                                        <p v-if="contractual_information && contractual_information.afp_pension && contractual_information.afp_pension.name" class="">{{ contractual_information.afp_pension.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-34">
                                        <p class="">AFP Cesantías:</p>
                                    </div>
                                    <div class="box-value vl-34">
                                        <p v-if="contractual_information && contractual_information.afp_saving && contractual_information.afp_saving.name" class="">{{ contractual_information.afp_saving.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-35">
                                        <p class="">ARL:</p>
                                    </div>
                                    <div class="box-value vl-35">
                                        <p v-if="contractual_information && contractual_information.arl && contractual_information.arl.name" class="">{{ contractual_information.arl.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-36">
                                        <p class="">Email corp.:</p>
                                    </div>
                                    <div class="box-value vl-36">
                                        <p v-if="contractual_information && contractual_information.corporate_email" class="">{{ contractual_information.corporate_email }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-37">
                                        <p class="">Celular corp.:</p>
                                    </div>
                                    <div class="box-value vl-37">
                                        <p v-if="contractual_information && contractual_information.corporate_cellphone" class="">{{ contractual_information.corporate_cellphone }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-38">
                                        <p class="">Banco:</p>
                                    </div>
                                    <div class="box-value vl-38">
                                        <p v-if="contractual_information && contractual_information.bank && contractual_information.bank.name" class="">{{ contractual_information.bank.name }}</p>
                                    </div>
                                </div>
                                <div class="data-pair">
                                    <div class="box-label lb-39">
                                        <p class="">Número de cuenta:</p>
                                    </div>
                                    <div class="box-value vl-39">
                                        <p v-if="contractual_information && contractual_information.bank_account" class="">{{ contractual_information.bank_account }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="main-card mb-3 card">
                        <div class="card-header">
                            Información Contractual No Disponible
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div v-else-if="card_selected == 'additional'" >
            <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                <li class="nav-item">
                    <a @click="change_tab_additional_info_status('family_information')" role="tab" class="nav-link" :class="tab_additional_info_status == 'family_information' ? 'active' : ''" id="tab-0" data-bs-toggle="tab" href="#tab-content-0">
                        <span>Información Familiar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a @click="change_tab_additional_info_status('academic_information')" role="tab" class="nav-link" :class="tab_additional_info_status == 'academic_information' ? 'active' : ''" id="tab-1" data-bs-toggle="tab" href="#tab-content-1">
                        <span>Información Académica</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a @click="change_tab_additional_info_status('medical_information')" role="tab" class="nav-link" :class="tab_additional_info_status == 'medical_information' ? 'active' : ''" id="tab-2" data-bs-toggle="tab" href="#tab-content-2">
                        <span>Información Médica</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a @click="change_tab_additional_info_status('home_visit')" role="tab" class="nav-link" :class="tab_additional_info_status == 'home_visities' ? 'active' : ''" id="tab-3" data-bs-toggle="tab" href="#tab-content-3">
                        <span>Visitas Domiciliarias</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane tabs-animation fade" :class="tab_additional_info_status == 'family_information' ? 'show active' : ''" id="tab-content-0" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12 col-xxl-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div v-if="message" class="message-success mb-3">
                                        <div class="content d-flex align-items-start p-2">
                                            <p class="mb-0" style="font-size: 14px;"> {{ message }}</p>
                                        </div>
                                    </div>
                                    <div v-if="relatives_data && relatives_data.length>0" class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <a @click="addRelativeData" class="wrapper-add-data p-2 mb-3" :class="(add_relative_data && !edit_relative_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Familiar</p>
                                            </a>
                                        </div>
                                        <div v-for="(item, index) in relatives_data" class="col-md-12 col-lg-6">
                                            <div class="wrapper-data mb-3 position-relative" :class="(selected_relative_data && selected_relative_data.id) == item.id ? 'selected shadow' : ''">
                                                <div @click="changeRelativeData(item.id, index)" class="box box1">
                                                    <div class="preliminary-information">
                                                        <p class="data-position-one text-truncate w-100">{{ item.name }} {{ item.first_surname }} {{ item.second_surname }}</p>
                                                        <p class="data-position-two text-truncate w-100">{{ item.relationship.name }}</p>
                                                        <p class="data-position-three text-truncate w-100">Edad: {{ getAge(item.birth_date) }} años</p>
                                                    </div>
                                                </div>
                                                <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                                    <a class="edit-data" @click="editRelativeData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                                    <a class="delete-data" @click="showDeleteAlert('deleteRelativeData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="row">
                                        <div class="col-md-12">
                                            <a @click="addRelativeData" class="wrapper-add-data p-2 mb-3" :class="(add_relative_data && !edit_relative_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Familiar</p>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- <table v-if="relatives_data && relatives_data.length>0" style="width: 100%;" id="example" class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Parentesco</th>
                                                <th>Edad</th>
                                                <th style="text-align: right;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in relatives_data" :class="(selected_relative_data && selected_relative_data.id) == item.id ? 'selected shadow' : ''">
                                                <td @click="changeRelativeData(item.id, index)" style="cursor: pointer;">{{ item.name }} {{ item.first_surname }} {{ item.second_surname }}</td>
                                                <td @click="changeRelativeData(item.id, index)" style="cursor: pointer;">{{ item.relationship }}</td>
                                                <td @click="changeRelativeData(item.id, index)" style="cursor: pointer;">{{ getAge(item.birth_date) }}</td>
                                                <td style="text-align: right;">
                                                    <a class="btn btn-sm btn-primary mx-1 my-1" @click="editRelativeData(item, index)" style="width: 80px;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /> Editar</a>
                                                    <a class="btn btn-sm btn-danger mx-1 my-1" @click="deleteRelativeData(item, index)" style="width: 80px;"><font-awesome-icon :icon="['fas', 'trash-can']" /> Eliminar</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                            <div v-if="add_relative_data && !edit_relative_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="storeRelativeData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Agregar Familiar
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="name" class="form-label">Nombres*</label>
                                                                        <input v-model="name" name="name" id="name" type="text" class="form-control" placeholder="Ingrese nombre(s)">
                                                                        <span v-if="errors_relative_data && errors_relative_data.name" class="error text-danger" for="name">{{ errors_relative_data.name[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="first_surname" class="form-label">Primer apellido*</label>
                                                                        <input v-model="first_surname" name="first_surname" id="first_surname" type="text" class="form-control" placeholder="Ingrese primer apellido">
                                                                        <span v-if="errors_relative_data && errors_relative_data.first_surname" class="error text-danger" for="first_surname">{{ errors_relative_data.first_surname[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="second_surname" class="form-label">Segundo apellido</label>
                                                                        <input v-model="second_surname" name="second_surname" id="second_surname" type="text" class="form-control" placeholder="Ingrese segundo apellido">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="relationship_type_id" class="form-label">Parentesco*</label>
                                                                        <select v-model="relationship_type_id" class="form-control" name="relationship_type_id" id="relationship_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Parentesco</option>
                                                                            <option v-for="relationship in relationship_types" :value="relationship.id">{{ relationship.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_relative_data && errors_relative_data.relationship_id" class="error text-danger" for="relationship_id">{{ errors_relative_data.relationship_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="sex_type_id" class="form-label">Sexo*</label>
                                                                        <select v-model="sex_type_id" class="form-control" name="sex_type_id" id="sex_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Sexo</option>
                                                                            <option v-for="sex in sex_types" :value="sex.id">{{ sex.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_relative_data && errors_relative_data.sex_type_id" class="error text-danger" for="sex_type_id">{{ errors_relative_data.sex_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="occupation_type_id" class="form-label">Ocupación*</label>
                                                                        <select v-model="occupation_type_id" class="form-control" name="occupation_type_id" id="occupation_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Ocupación</option>
                                                                            <option v-for="occupation in occupation_types" :value="occupation.id">{{ occupation.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_relative_data && errors_relative_data.occupation_id" class="error text-danger" for="occupation_type_id">{{ errors_relative_data.occupation_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="birth_date" class="form-label">Fecha de Nacimiento*</label>
                                                                        <input v-model="birth_date" name="birth_date" id="birth_date" type="date" class="form-control" placeholder="Ingrese fecha de nacimiento">
                                                                        <span v-if="errors_relative_data && errors_relative_data.birth_date" class="error text-danger" for="birth_date">{{ errors_relative_data.birth_date[0] }}</span>
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
                            <div v-else-if="!add_relative_data && edit_relative_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="updateRelativeData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Editar Familiar
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="name" class="form-label">Nombres*</label>
                                                                        <input v-model="name" name="name" id="name" type="text" class="form-control" placeholder="Ingrese nombre(s)">
                                                                        <span v-if="errors_relative_data && errors_relative_data.name" class="error text-danger" for="name">{{ errors_relative_data.name[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="first_surname" class="form-label">Primer apellido*</label>
                                                                        <input v-model="first_surname" name="first_surname" id="first_surname" type="text" class="form-control" placeholder="Ingrese primer apellido">
                                                                        <span v-if="errors_relative_data && errors_relative_data.first_surname" class="error text-danger" for="first_surname">{{ errors_relative_data.first_surname[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="second_surname" class="form-label">Segundo apellido</label>
                                                                        <input v-model="second_surname" name="second_surname" id="second_surname" type="text" class="form-control" placeholder="Ingrese segundo apellido">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="relationship_type_id" class="form-label">Parentesco*</label>
                                                                        <select v-model="relationship_type_id" class="form-control" name="relationship_type_id" id="relationship_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Parentesco</option>
                                                                            <option v-for="relationship in relationship_types" :value="relationship.id">{{ relationship.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_relative_data && errors_relative_data.relationship_id" class="error text-danger" for="relationship_type_id">{{ errors_relative_data.relationship_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="sex_type_id" class="form-label">Sexo*</label>
                                                                        <select v-model="sex_type_id" class="form-control" name="sex_type_id" id="sex_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Sexo</option>
                                                                            <option v-for="sex in sex_types" :value="sex.id">{{ sex.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_relative_data && errors_relative_data.sex_type_id" class="error text-danger" for="sex_type_id">{{ errors_relative_data.sex_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="occupation_type_id" class="form-label">Ocupación*</label>
                                                                        <select v-model="occupation_type_id" class="form-control" name="occupation_type_id" id="occupation_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Ocupación</option>
                                                                            <option v-for="occupation in occupation_types" :value="occupation.id">{{ occupation.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_relative_data && errors_relative_data.occupation_id" class="error text-danger" for="occupation_type_id">{{ errors_relative_data.occupation_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="birth_date" class="form-label">Fecha de Nacimiento*</label>
                                                                        <input v-model="birth_date" name="birth_date" id="birth_date" type="date" class="form-control" placeholder="Ingrese fecha de nacimiento">
                                                                        <span v-if="errors_relative_data && errors_relative_data.birth_date" class="error text-danger" for="birth_date">{{ errors_relative_data.birth_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="!add_relative_data && !edit_relative_data && selected_relative_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                    <div class="card-header">
                                                        Información del Familiar
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrapper-relative mt-3">
                                                            <div class="data-pair">
                                                                <div class="box-label lb-20">
                                                                    <p class="">Nombre:</p>
                                                                </div>
                                                                <div class="box-value vl-20">
                                                                    <p class="">{{ selected_relative_data.name }} {{ selected_relative_data.first_surname }} {{ selected_relative_data.second_surname }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-21">
                                                                    <p class="">Parentesco:</p>
                                                                </div>
                                                                <div class="box-value vl-21">
                                                                    <p class="">{{ selected_relative_data.relationship.name }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-22">
                                                                    <p class="">Sexo:</p>
                                                                </div>
                                                                <div class="box-value vl-22">
                                                                    <p class="">{{ selected_relative_data.sex.name }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-23">
                                                                    <p class="">Ocupación:</p>
                                                                </div>
                                                                <div class="box-value vl-23">
                                                                    <p class="">{{ selected_relative_data.occupation.name }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-24">
                                                                    <p class="">Fecha de nacimiento:</p>
                                                                </div>
                                                                <div class="box-value vl-24">
                                                                    <p class="">{{ selected_relative_data.birth_date }}</p>
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

            <div class="tab-content">
                <div class="tab-pane tabs-animation fade" :class="tab_additional_info_status == 'academic_information' ? 'show active' : ''" id="tab-content-1" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12 col-xxl-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div v-if="message" class="message-success mb-3">
                                        <div class="content d-flex align-items-start p-2">
                                            <p class="mb-0" style="font-size: 14px;"> {{ message }}</p>
                                        </div>
                                    </div>
                                    <div v-if="academic_achievements_data && academic_achievements_data.length>0" class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <a @click="addAcademicData" class="wrapper-add-data p-2 mb-3" :class="(add_academic_data && !edit_academic_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Logro</p>
                                            </a>
                                        </div>
                                        <div v-for="(item, index) in academic_achievements_data" class="col-md-12 col-lg-6">
                                            <div class="wrapper-data mb-3 position-relative" :class="(selected_academic_data && selected_academic_data.id) == item.id ? 'selected shadow' : ''">
                                                <div @click="changeAcademicData(item.id, index)" class="box box1">
                                                    <div class="preliminary-information">
                                                        <p class="data-position-one text-truncate w-100">{{ item.degree }}</p>
                                                        <p class="data-position-two text-truncate w-100">{{ item.achievement_type }}</p>
                                                        <p class="data-position-three text-truncate w-100">Fecha: {{ item.end_date }}</p>
                                                    </div>
                                                </div>
                                                <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                                    <a class="edit-data" @click="editAcademicData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                                    <!-- <a class="delete-data" @click="deleteAcademicData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a> -->
                                                    <a class="delete-data" @click="showDeleteAlert('deleteAcademicData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="row">
                                        <div class="col-md-12">
                                            <a @click="addAcademicData" class="wrapper-add-data p-2 mb-3" :class="(add_academic_data && !edit_academic_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Logro</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                            <div v-if="add_academic_data && !edit_academic_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="storeAcademicData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Agregar Logro Académico
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="degree" class="form-label">Título obtenido*</label>
                                                                        <input v-model="degree" name="degree" id="degree" type="text" class="form-control" placeholder="Ingrese título obtenido">
                                                                        <span v-if="errors_academic_data && errors_academic_data.degree" class="error text-danger" for="degree">{{ errors_academic_data.degree[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="institution" class="form-label">Institución*</label>
                                                                        <input v-model="institution" name="institution" id="institution" type="text" class="form-control" placeholder="Ingrese institución">
                                                                        <span v-if="errors_academic_data && errors_academic_data.institution" class="error text-danger" for="institution">{{ errors_academic_data.institution[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="achievement_type_id" class="form-label">Nivel de formación*</label>
                                                                        <select v-model="achievement_type_id" class="form-control" name="achievement_type_id" id="achievement_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Tipo de Logro</option>
                                                                            <option v-for="achievement_type in achievement_types" :value="achievement_type.id">{{ achievement_type.type }}</option>
                                                                        </select>
                                                                        <span v-if="errors_academic_data && errors_academic_data.achievement_type_id" class="error text-danger" for="achievement_type_id">{{ errors_academic_data.achievement_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="end_date" class="form-label">Fecha de terminación*</label>
                                                                        <input v-model="end_date" name="end_date" id="end_date" type="date" class="form-control">
                                                                        <span v-if="errors_academic_data && errors_academic_data.end_date" class="error text-danger" for="end_date">{{ errors_academic_data.end_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="certificate" class="form-label">Certificado</label>
                                                                        <div class="input-group">
                                                                            <input @change="onChangeCertificate" type="file" name="certificate" id="certificate" class="form-control">
                                                                        </div>
                                                                        <span v-if="errors_academic_data && errors_academic_data.certificate" class="error text-danger" for="certificate">{{ errors_academic_data.certificate[0] }}</span>
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
                            <div v-else-if="!add_academic_data && edit_academic_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="updateAcademicData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Editar Logro Académico
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="degree" class="form-label">Título obtenido*</label>
                                                                        <input v-model="degree" name="degree" id="degree" type="text" class="form-control" placeholder="Ingrese título obtenido">
                                                                        <span v-if="errors_academic_data && errors_academic_data.degree" class="error text-danger" for="degree">{{ errors_academic_data.degree[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="institution" class="form-label">Institución*</label>
                                                                        <input v-model="institution" name="institution" id="institution" type="text" class="form-control" placeholder="Ingrese institución">
                                                                        <span v-if="errors_academic_data && errors_academic_data.institution" class="error text-danger" for="institution">{{ errors_academic_data.institution[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="achievement_type_id" class="form-label">Nivel de formación*</label>
                                                                        <select v-model="achievement_type_id" class="form-control" name="achievement_type_id" id="achievement_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Tipo de Logro</option>
                                                                            <option v-for="achievement_type in achievement_types" :value="achievement_type.id">{{ achievement_type.type }}</option>
                                                                        </select>
                                                                        <span v-if="errors_academic_data && errors_academic_data.achievement_type_id" class="error text-danger" for="achievement_type_id">{{ errors_academic_data.achievement_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="end_date" class="form-label">Fecha de terminación*</label>
                                                                        <input v-model="end_date" name="end_date" id="end_date" type="date" class="form-control">
                                                                        <span v-if="errors_academic_data && errors_academic_data.end_date" class="error text-danger" for="end_date">{{ errors_academic_data.end_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="certificate" class="form-label">Certificado</label>
                                                                        <div class="input-group">
                                                                            <input @change="onChangeCertificate" type="file" name="certificate" id="certificate" class="form-control">
                                                                        </div>
                                                                        <span v-if="errors_academic_data && errors_academic_data.certificate" class="error text-danger" for="certificate">{{ errors_academic_data.certificate[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="!add_academic_data && !edit_academic_data && selected_academic_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                    <div class="card-header">
                                                        Información del Logro
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrapper-academic my-3">
                                                            <div class="data-pair">
                                                                <div class="box-label lb-40">
                                                                    <p class="">Título:</p>
                                                                </div>
                                                                <div class="box-value vl-40">
                                                                    <p class="">{{ selected_academic_data.degree }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-41">
                                                                    <p class="">Institución:</p>
                                                                </div>
                                                                <div class="box-value vl-41">
                                                                    <p class="">{{ selected_academic_data.institution }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-42">
                                                                    <p class="">Nivel de formación:</p>
                                                                </div>
                                                                <div class="box-value vl-42">
                                                                    <p class="">{{ selected_academic_data.achievement_type }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-43">
                                                                    <p class="">Fecha de grado:</p>
                                                                </div>
                                                                <div class="box-value vl-43">
                                                                    <p class="">{{ selected_academic_data.end_date }}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button v-if="selected_academic_data && selected_academic_data.certificate_url" @click="downloadAcademicCertificate(selected_academic_data.id)" class="mb-2 mr-2 btn-icon btn btn-primary">
                                                            <font-awesome-icon :icon="['fas', 'download']" /> Descargar Certificado
                                                        </button>
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
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade" :class="tab_additional_info_status == 'medical_information' ? 'show active' : ''" id="tab-content-2" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12 col-xxl-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div v-if="message" class="message-success mb-3">
                                        <div class="content d-flex align-items-start p-2">
                                            <p class="mb-0" style="font-size: 14px;"> {{ message }}</p>
                                        </div>
                                    </div>
                                    <div v-if="medical_examination_data && medical_examination_data.length>0" class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <a @click="addMedicalExaminationData" class="wrapper-add-data p-2 mb-3" :class="(add_medical_examination_data && !edit_medical_examination_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Examen Médico</p>
                                            </a>
                                        </div>
                                        <div v-for="(item, index) in medical_examination_data" class="col-md-12 col-lg-6">
                                            <div class="wrapper-data mb-3 position-relative" :class="(selected_medical_examination_data && selected_medical_examination_data.id) == item.id ? 'selected shadow' : ''">
                                                <div @click="changeMedicalExaminationData(item.id, index)" class="box box1">
                                                    <div class="preliminary-information">
                                                        <p class="data-position-one text-truncate w-100">{{ item.examination_type }}</p>
                                                        <p class="data-position-two text-truncate w-100">Fecha: {{ item.examination_date }}</p>
                                                        <p class="data-position-three text-truncate w-100">Observaciones: {{ item.observations ? item.observations : 'Ninguna' }}</p>
                                                    </div>
                                                </div>
                                                <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                                    <a class="edit-data" @click="editMedicalExaminationData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                                    <a class="delete-data" @click="showDeleteAlert('deleteMedicalExaminationData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="row">
                                        <div class="col-md-12">
                                            <a @click="addMedicalExaminationData" class="wrapper-add-data p-2 mb-3" :class="(add_medical_examination_data && !edit_medical_examination_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Examen Médico</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                            <div v-if="add_medical_examination_data && !edit_medical_examination_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="storeMedicalExaminationData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Agregar Examen Médico
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_type_id" class="form-label">Tipo de Evaluación Médica*</label>
                                                                        <select v-model="examination_type_id" class="form-control" name="examination_type_id" id="examination_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Tipo de Examen</option>
                                                                            <option v-for="examination in examination_types" :value="examination.id">{{ examination.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_type_id" class="error text-danger" for="examination_type_id">{{ errors_medical_examination_data.examination_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_date" class="form-label">Fecha*</label>
                                                                        <input v-model="examination_date" name="examination_date" id="examination_date" type="date" class="form-control" placeholder="Ingrese fecha de nacimiento">
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_date" class="error text-danger" for="examination_date">{{ errors_medical_examination_data.examination_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_result" class="form-label">Resultado</label>
                                                                        <div class="input-group">
                                                                            <input @change="onChangeExaminationResult" type="file" name="examination_result" id="examination_result" class="form-control">
                                                                        </div>
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_result" class="error text-danger" for="examination_result">{{ errors_medical_examination_data.examination_result[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_observations" class="form-label">Observaciones</label>
                                                                        <input v-model="examination_observations" name="examination_observations" id="examination_observations" type="text" class="form-control" placeholder="Ingrese observaciones">
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_observations" class="error text-danger" for="examination_observations">{{ errors_medical_examination_data.examination_observations[0] }}</span>
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
                            <div v-else-if="!add_medical_examination_data && edit_medical_examination_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="updateMedicalExaminationData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Editar Información de Salud
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_type_id" class="form-label">Tipo de Evaluación Médica*</label>
                                                                        <select v-model="examination_type_id" class="form-control" name="examination_type_id" id="examination_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Tipo de Examen</option>
                                                                            <option v-for="examination in examination_types" :value="examination.id">{{ examination.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_type_id" class="error text-danger" for="examination_type_id">{{ errors_medical_examination_data.examination_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_date" class="form-label">Fecha*</label>
                                                                        <input v-model="examination_date" name="examination_date" id="examination_date" type="date" class="form-control" placeholder="Ingrese fecha de nacimiento">
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_date" class="error text-danger" for="examination_date">{{ errors_medical_examination_data.examination_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_result" class="form-label">Resultado</label>
                                                                        <div class="input-group">
                                                                            <input @change="onChangeExaminationResult" type="file" name="examination_result" id="examination_result" class="form-control">
                                                                        </div>
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_result" class="error text-danger" for="examination_result">{{ errors_medical_examination_data.examination_result[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="examination_observations" class="form-label">Observaciones</label>
                                                                        <input v-model="examination_observations" name="examination_observations" id="examination_observations" type="text" class="form-control" placeholder="Ingrese observaciones">
                                                                        <span v-if="errors_medical_examination_data && errors_medical_examination_data.examination_observations" class="error text-danger" for="examination_observations">{{ errors_medical_examination_data.examination_observations[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="!add_medical_examination_data && !edit_medical_examination_data && selected_medical_examination_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                    <div class="card-header">
                                                        Información de Salud
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrapper-medical-examination my-3">
                                                            <div class="data-pair">
                                                                <div class="box-label lb-44">
                                                                    <p class="">Tipo de evaluación:</p>
                                                                </div>
                                                                <div class="box-value vl-44">
                                                                    <p class="">{{ selected_medical_examination_data.examination_type }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-45">
                                                                    <p class="">Fecha:</p>
                                                                </div>
                                                                <div class="box-value vl-45">
                                                                    <p class="">{{ selected_medical_examination_data.examination_date }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-46">
                                                                    <p class="">Observaciones:</p>
                                                                </div>
                                                                <div class="box-value vl-46">
                                                                    <p class="">{{ selected_medical_examination_data.observations }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button v-if="selected_medical_examination_data && selected_medical_examination_data.result_url" @click="downloadMedicalExaminationResult(selected_medical_examination_data.id)" class="mb-2 mr-2 btn-icon btn btn-primary">
                                                            <font-awesome-icon :icon="['fas', 'download']" /> Descargar Resultado
                                                        </button>
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
            <div class="tab-content">
                <div class="tab-pane tabs-animation fade" :class="tab_additional_info_status == 'home_visit' ? 'show active' : ''" id="tab-content-3" role="tabpanel">
                    <div class="row">
                        <div class="col-sm-12 col-xxl-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div v-if="message" class="message-success mb-3">
                                        <div class="content d-flex align-items-start p-2">
                                            <p class="mb-0" style="font-size: 14px;"> {{ message }}</p>
                                        </div>
                                    </div>
                                    <div v-if="home_visit_data && home_visit_data.length>0" class="row">
                                        <div class="col-md-12 col-lg-6">
                                            <a @click="addHomeVisitData" class="wrapper-add-data p-2 mb-3" :class="(add_home_visit_data && !edit_home_visit_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Visita</p>
                                            </a>
                                        </div>
                                        <div v-for="(item, index) in home_visit_data" class="col-md-12 col-lg-6">
                                            <div class="wrapper-data mb-3 position-relative" :class="(selected_home_visit_data && selected_home_visit_data.id == item.id) ? 'selected shadow' : ''">
                                                <div @click="changeHomeVisitData(item.id, index)" class="box box1">
                                                    <div class="preliminary-information">
                                                        <p class="data-position-one text-truncate w-100">Motivo: {{ item.home_visit_type  }}</p>
                                                        <p class="data-position-two text-truncate w-100">Fecha: {{ item.visit_date }}</p>
                                                        <!-- <p class="data-position-three text-truncate w-100">Observaciones: {{ item.home_visit_observations ? item.home_visit_observations : 'Ninguna' }}</p> -->
                                                        <p class="data-position-three text-truncate w-100">Observaciones: {{ item.observations ? item.observations : 'Ninguna' }}</p>
                                                    </div>
                                                </div>
                                                <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                                    <a class="edit-data" @click="editHomeVisitData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                                    <a class="delete-data" @click="showDeleteAlert('deleteHomeVisitData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="row">
                                        <div class="col-md-12">
                                            <a @click="addHomeVisitData" class="wrapper-add-data p-2 mb-3" :class="(add_home_visit_data && !edit_home_visit_data) ? 'selected shadow' : ''">
                                                <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                                <p>Agregar Visita</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                            <div v-if="add_home_visit_data && !edit_home_visit_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="storeHomeVisitData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Agregar Visita Domiciliaria
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="home_visit_type_id" class="form-label">Motivo de visita*</label>
                                                                        <select v-model="home_visit_type_id" class="form-control" name="home_visit_type_id" id="home_visit_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Motivo de Visita</option>
                                                                            <option v-for="home_visit in home_visit_types" :value="home_visit.id">{{ home_visit.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.home_visit_type_id" class="error text-danger" for="home_visit_type_id">{{ errors_home_visit_data.home_visit_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="visit_date" class="form-label">Fecha de visita*</label>
                                                                        <input v-model="visit_date" name="visit_date" id="visit_date" type="date" class="form-control">
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.visit_date" class="error text-danger" for="visit_date">{{ errors_home_visit_data.visit_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="next_visit_date" class="form-label">Próxima visita</label>
                                                                        <input v-model="next_visit_date" name="next_visit_date" id="next_visit_date" type="date" class="form-control">
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.next_visit_date" class="error text-danger" for="next_visit_date">{{ errors_home_visit_data.next_visit_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="home_visit_observations" class="form-label">Observaciones</label>
                                                                        <input v-model="home_visit_observations" name="home_visit_observations" id="home_visit_observations" type="text" class="form-control" placeholder="Ingrese observaciones">
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.home_visit_observations" class="error text-danger" for="home_visit_observations">{{ errors_home_visit_data.home_visit_observations[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="home_visit_report" class="form-label">Reporte</label>
                                                                        <div class="input-group">
                                                                            <input @change="onChangeHomeVisitReport" type="file" name="home_visit_report" id="home_visit_report" class="form-control">
                                                                        </div>
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.home_visit_report" class="error text-danger" for="home_visit_report">{{ errors_home_visit_data.home_visit_report[0] }}</span>
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
                            <div v-else-if="!add_home_visit_data && edit_home_visit_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form @submit.prevent="updateHomeVisitData" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                        <div class="card-header">
                                                            Editar Visita Domiciliaria
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="home_visit_type_id" class="form-label">Motivo de visita*</label>
                                                                        <select v-model="home_visit_type_id" class="form-control" name="home_visit_type_id" id="home_visit_type_id">
                                                                            <option value="" disabled selected hidden>Seleccionar Motivo de Visita</option>
                                                                            <option v-for="home_visit in home_visit_types" :value="home_visit.id">{{ home_visit.name }}</option>
                                                                        </select>
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.home_visit_type_id" class="error text-danger" for="home_visit_type_id">{{ errors_home_visit_data.home_visit_type_id[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="visit_date" class="form-label">Fecha de visita*</label>
                                                                        <input v-model="visit_date" name="visit_date" id="visit_date" type="date" class="form-control">
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.visit_date" class="error text-danger" for="visit_date">{{ errors_home_visit_data.visit_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="next_visit_date" class="form-label">Próxima visita</label>
                                                                        <input v-model="next_visit_date" name="next_visit_date" id="next_visit_date" type="date" class="form-control">
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.next_visit_date" class="error text-danger" for="next_visit_date">{{ errors_home_visit_data.next_visit_date[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="home_visit_observations" class="form-label">Observaciones</label>
                                                                        <input v-model="home_visit_observations" name="home_visit_observations" id="home_visit_observations" type="text" class="form-control" placeholder="Ingrese observaciones">
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.home_visit_observations" class="error text-danger" for="home_visit_observations">{{ errors_home_visit_data.home_visit_observations[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                                    <div class="position-relative mb-3">
                                                                        <label for="home_visit_report" class="form-label">Reporte</label>
                                                                        <div class="input-group">
                                                                            <input @change="onChangeHomeVisitReport" type="file" name="home_visit_report" id="home_visit_report" class="form-control">
                                                                        </div>
                                                                        <span v-if="errors_home_visit_data && errors_home_visit_data.home_visit_report" class="error text-danger" for="home_visit_report">{{ errors_home_visit_data.home_visit_report[0] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="!add_home_visit_data && !edit_home_visit_data && selected_home_visit_data" class="">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                    <div class="card-header">
                                                        Información de Visita Domiciliaria
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="wrapper-home-visit my-3">
                                                            <div class="data-pair">
                                                                <div class="box-label lb-47">
                                                                    <p class="">Motivo de visita:</p>
                                                                </div>
                                                                <div class="box-value vl-47">
                                                                    <p class="">{{ selected_home_visit_data.home_visit_type }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-48">
                                                                    <p class="">Fecha visita:</p>
                                                                </div>
                                                                <div class="box-value vl-48">
                                                                    <p class="">{{ selected_home_visit_data.visit_date }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-49">
                                                                    <p class="">Fecha próxima:</p>
                                                                </div>
                                                                <div class="box-value vl-49">
                                                                    <p class="">{{ selected_home_visit_data.next_visit_date }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="data-pair">
                                                                <div class="box-label lb-50">
                                                                    <p class="">Observaciones:</p>
                                                                </div>
                                                                <div class="box-value vl-50">
                                                                    <p class="">{{ selected_home_visit_data.observations ? selected_home_visit_data.observations : 'Ninguna' }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button v-if="selected_home_visit_data && selected_home_visit_data.report_url" @click="downloadHomeVisitReport(selected_home_visit_data.id)" class="mb-2 mr-2 btn-icon btn btn-primary">
                                                            <font-awesome-icon :icon="['fas', 'download']" /> Descargar Reporte
                                                        </button>
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
        <div v-else-if="card_selected == 'documents'">
            <div class="row">
                <div class="col-sm-12 col-xxl-6">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div v-if="message" class="message-success mb-3">
                                <div class="content d-flex align-items-start p-2">
                                    <p class="mb-0" style="font-size: 14px;"> {{ message }}</p>
                                </div>
                            </div>
                            <div v-if="document_data && document_data.length>0" class="row">
                                <div class="col-md-12 col-lg-6">
                                    <a @click="addDocumentData" class="wrapper-add-data p-2 mb-3" :class="(add_document_data && !edit_document_data) ? 'selected shadow' : ''">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Documento</p>
                                    </a>
                                </div>
                                <div v-for="(item, index) in document_data" class="col-md-12 col-lg-6">
                                    <div class="wrapper-data mb-3 position-relative" :class="(selected_document_data && selected_document_data.id == item.id) ? 'selected shadow' : ''">
                                        <div @click="changeDocumentData(item.id, index)" class="box box1">
                                            <div class="preliminary-information">
                                                <p class="data-position-one text-truncate w-100">{{ item.document_type  }}</p>
                                                <!-- <p class="data-position-two text-truncate w-100">Fecha: {{ item.visit_date }}</p> -->
                                                <p class="data-position-three text-truncate w-100">Observaciones: {{ item.observations ? item.observations : 'Ninguna' }}</p>
                                            </div>
                                        </div>
                                        <div class="box box2" style="display: flex; flex-direction: column; justify-content: space-between;">
                                            <a class="edit-data" @click="editDocumentData(item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /></a>
                                            <a class="delete-data" @click="showDeleteAlert('deleteDocumentData', item, index)" style="font-size: 22px; text-align:right;"><font-awesome-icon :icon="['fas', 'trash-can']" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="row">
                                <div class="col-md-12">
                                    <a @click="addDocumentData" class="wrapper-add-data p-2 mb-3" :class="(add_document_data && !edit_document_data) ? 'selected shadow' : ''">
                                        <p><i class="fa fa-plus" aria-hidden="true"></i></p>
                                        <p>Agregar Documento</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-6">
                    <div v-if="add_document_data && !edit_document_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <form @submit.prevent="storeDocumentData" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                <div class="card-header">
                                                    Agregar Documento
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="position-relative mb-3">
                                                                <label for="document_type_id" class="form-label">Tipo de documento*</label>
                                                                <select v-model="contractual_document_type_id" class="form-control" name="document_type_id" id="document_type_id">
                                                                    <option value="" disabled selected hidden>Seleccionar Tipo de Documento</option>
                                                                    <option v-for="document in contractual_documents_types" :value="document.id">{{ document.name }}</option>
                                                                </select>
                                                                <span v-if="errors_document_data && errors_document_data.document_type_id" class="error text-danger" for="document_type_id">{{ errors_document_data.document_type_id[0] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="position-relative mb-3">
                                                                <label for="document_observations" class="form-label">Observaciones</label>
                                                                <input v-model="document_observations" name="document_observations" id="document_observations" type="text" class="form-control" placeholder="Ingrese observaciones">
                                                                <span v-if="errors_document_data && errors_document_data.document_observations" class="error text-danger" for="document_observations">{{ errors_document_data.document_observations[0] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="position-relative mb-3">
                                                                <label for="document" class="form-label">Documento</label>
                                                                <div class="input-group">
                                                                    <input @change="onChangeDocument" type="file" name="document" id="document" class="form-control">
                                                                </div>
                                                                <span v-if="errors_document_data && errors_document_data.document" class="error text-danger" for="document">{{ errors_document_data.document[0] }}</span>
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
                    <div v-else-if="!add_document_data && edit_document_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <form @submit.prevent="updateDocumentData" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                                <div class="card-header">
                                                    Editar Documento
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="position-relative mb-3">
                                                                <label for="document_type_id" class="form-label">Tipo de documento*</label>
                                                                <!-- <select v-model="document_type_id" class="form-control" name="document_type_id" id="document_type_id"> -->
                                                                <select v-model="document_type_id" class="form-control" name="document_type_id" id="document_type_id">
                                                                    <option value="" disabled selected hidden>Seleccionar Tipo de Documento</option>
                                                                    <option v-for="document in contractual_documents_types" :value="document.id">{{ document.name }}</option>
                                                                </select>
                                                                <span v-if="errors_document_data && errors_document_data.document_type_id" class="error text-danger" for="document_type_id">{{ errors_document_data.document_type_id[0] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="position-relative mb-3">
                                                                <label for="document_observations" class="form-label">Observaciones</label>
                                                                <input v-model="document_observations" name="document_observations" id="document_observations" type="text" class="form-control" placeholder="Ingrese observaciones">
                                                                <span v-if="errors_document_data && errors_document_data.document_observations" class="error text-danger" for="document_observations">{{ errors_document_data.document_observations[0] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="position-relative mb-3">
                                                                <label for="document" class="form-label">Documento</label>
                                                                <div class="input-group">
                                                                    <input @change="onChangeDocument" type="file" name="document" id="document" class="form-control">
                                                                </div>
                                                                <span v-if="errors_document_data && errors_document_data.document" class="error text-danger" for="document">{{ errors_document_data.document[0] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="!add_document_data && !edit_document_data && selected_document_data" class="">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-hover-shadow card-border mb-3 card frame-information-card">
                                            <div class="card-header">
                                                Información de Documento
                                            </div>
                                            <div class="card-body">
                                                <div class="wrapper-document my-3">
                                                    <div class="data-pair">
                                                        <div class="box-label lb-51">
                                                            <p class="">Tipo de documento:</p>
                                                        </div>
                                                        <div class="box-value vl-51">
                                                            <p class="">{{ selected_document_data.document_type }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="data-pair">
                                                        <div class="box-label lb-52">
                                                            <p class="">Observaciones:</p>
                                                        </div>
                                                        <div class="box-value vl-52">
                                                            <p class="">{{ selected_document_data.observations ? selected_document_data.observations : 'Ninguna' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button v-if="selected_document_data && selected_document_data.document_url" @click="downloadDocument(selected_document_data.id)" class="mb-2 mr-2 btn-icon btn btn-primary">
                                                    <font-awesome-icon :icon="['fas', 'download']" /> Descargar Documento
                                                </button>
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
        collaborator: { default: null, },
        document_type: { default: null, },
        document_province: { default: null, },
        document_city: { default: null, },
        birth_province: { default: null, },
        birth_city: { default: null, },
        residence_province: { default: null, },
        residence_city: { default: null, },
        civil_status: { default: null, },
        sex_type: { default: null, },
        rh_type: { default: null, },
        highest_academic_achievement: { default: null, },
        stratum_type: { default: null, },
        housing_tenure: { default: null, },
        relationship_types: { default: null, },
        relationship_type: { default: null, },
        occupation_types: { default: null, },
        occupation_type: { default: null, },
        sex_types: { default: null, },
        achievement_types: { default: null, },
        examination_types: { default: null, },
        home_visit_types: { default: null, },
        contractual_documents_types: { default: null, },
    },
    data() {
        return {
            tab_additional_info_status: 'family_information',
            card_selected: 'basic',

            message: '',

            name: '',
            first_surname: '',
            second_surname: '',
            relationship_type_id: '',
            occupation_type_id: '',
            sex_type_id: '',
            birth_date: '',

            achievement_type_id: '',
            degree: '',
            institution: '',
            end_date: '',
            certificate: null,

            examination_type_id: '',
            examination_date: '',
            examination_result: '',
            examination_observations: '',

            home_visit_type_id: '',
            visit_date: '',
            next_visit_date: '',
            home_visit_observations: '',
            home_visit_report: null,

            contractual_document_type_id: '',
            document_observations: '',
            document: null,

            contractual_information: '',
            position_id: '',
            salary: '',
            contract_type_id: '',
            contract_start_date: '',
            contract_end_date: '',
            test_period_end_date: '',
            corporate_email: '',
            corporate_cellphone: '',
            bank_id: '',
            bank_account: '',
            eps_id: '',
            afp_pension_id: '',
            afp_saving_id: '',
            arl_id: '',
            ccf_id: '',


            relatives_data: [],
            errors_relative_data: [],

            academic_achievements_data: [],
            errors_academic_data: [],

            medical_examination_data: [],
            errors_medical_examination_data: [],

            home_visit_data: [],
            errors_home_visit_data: [],

            document_data: [],
            errors_document_data: [],

            add_relative_data: false,
            edit_relative_data: false,
            selected_relative_data: null,
            relative_data_to_edit: null,

            add_academic_data: false,
            edit_academic_data: false,
            selected_academic_data: null,
            academic_data_to_edit: null,

            add_medical_examination_data: false,
            edit_medical_examination_data: false,
            selected_medical_examination_data: null,
            medical_examination_data_to_edit: null,

            add_home_visit_data: false,
            edit_home_visit_data: false,
            selected_home_visit_data: null,
            home_visit_data_to_edit: null,

            add_document_data: false,
            edit_document_data: false,
            selected_document_data: null,
            document_data_to_edit: null,

            successfully_created_message: false,
            successfully_updated_message: false,
            successfully_deleted_message: false,

            origin: '',
        }
    },
    mounted () {
        this.getOrigin()

        this.getRelativesData(this.collaborator.id)
        this.getAcademicData(this.collaborator.id)
        this.getMedicalExaminationData(this.collaborator.id)
        this.getHomeVisitData(this.collaborator.id)
        this.getContractualInformation(this.collaborator.id)
        this.getDocumentData(this.collaborator.id)
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
                if(action == 'deleteRelativeData') {
                    this.deleteRelativeData(item, index)
                } else if(action == 'deleteAcademicData') {
                    this.deleteAcademicData(item, index)
                } else if(action == 'deleteMedicalExaminationData') {
                    this.deleteMedicalExaminationData(item, index)
                } else if(action == 'deleteHomeVisitData') {
                    this.deleteHomeVisitData(item, index)
                } else if(action == 'deleteDocumentData') {
                    this.deleteDocumentData(item, index)
                }
                this.$swal({
                    title: "Eliminado!",
                    text: "Su registro ha sido borrado.",
                    icon: "success"
                });
            }
            });
        },
        emitEditCollaborator() {
            this.$emit('editCollaborator', this.collaborator.id)
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
        downloadAcademicCertificate(academic_achievement_id) {
            axios.get(`/download-academic-certificate/${academic_achievement_id}`)
            .then(response => {
                window.open(response.data.certificate_download_url, '_blank');
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        downloadMedicalExaminationResult(medical_examination_id) {
            axios.get(`/download-medical-examination-result/${medical_examination_id}`)
            .then(response => {
                window.open(response.data.result_download_url, '_blank');
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        downloadHomeVisitReport(home_visit_id) {
            axios.get(`/download-home-visit-report/${home_visit_id}`)
            .then(response => {
                window.open(response.data.report_download_url, '_blank');
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        downloadDocument(document_id) {
            axios.get(`/download-document/${document_id}`)
            .then(response => {
                window.open(response.data.document_download_url, '_blank');
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        getContractualInformation(collaborator_id) {
            axios.get(`/contractual-information/${collaborator_id}`)
            .then(response => {
                this.contractual_information = response.data.contractual_information;

                if(this.contractual_information !== null) {
                    this.position_id = this.contractual_information.position_id;
                    this.salary = this.contractual_information.salary;
                    this.contract_type_id = this.contractual_information.contract_type_id;
                    this.contract_start_date = this.contractual_information.contract_start_date;
                    this.contract_end_date = this.contractual_information.contract_end_date;
                    this.test_period_end_date = this.contractual_information.test_period_end_date;
                    this.corporate_email = this.contractual_information.corporate_email;
                    this.corporate_cellphone = this.contractual_information.corporate_cellphone;
                    this.bank_id = this.contractual_information.bank_id;
                    this.bank_account = this.contractual_information.bank_account;
                    this.eps_id = this.contractual_information.eps_id;
                    this.afp_pension_id = this.contractual_information.afp_pension_id;
                    this.afp_saving_id = this.contractual_information.afp_saving_id;
                    this.arl_id = this.contractual_information.arl_id;
                    this.ccf_id = this.contractual_information.ccf_id;
                }

                // this.card_selected = 'contract';
            })
            .catch(e => {
                console.error('Error:', e);

                // this.card_selected = 'contract';
            })
        },
        addRelativeData() {
            if(this.add_relative_data == false) {
                this.add_relative_data = true
                this.edit_relative_data = false
            } else {
                this.edit_relative_data = false
            }

            this.selected_relative_data = null

            this.name = ''
            this.first_surname = ''
            this.second_surname = ''
            this.relationship_type_id = ''
            this.occupation_type_id = ''
            this.sex_type_id = ''
            this.birth_date = ''

            this.selected_relative_data = null
            this.errors_relative_data = []

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        addAcademicData() {
            if(this.add_academic_data == false) {
                this.add_academic_data = true
                this.edit_academic_data = false
            } else {
                this.edit_academic_data = false
            }

            this.selected_academic_data = null

            this.achievement_type_id = ''
            this.degree = ''
            this.institution = ''
            this.end_date = ''
            this.certificate = null

            this.selected_academic_data = null
            this.errors_academic_data = []

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        addMedicalExaminationData() {
            if(this.add_medical_examination_data == false) {
                this.add_medical_examination_data = true
                this.edit_medical_examination_data = false
            } else {
                this.edit_medical_examination_data = false
            }

            this.selected_medical_examination_data = null

            this.examination_type_id = ''
            this.examination_date = ''
            this.examination_observations = ''
            this.examination_result = null

            this.selected_medical_examination_data = null
            this.errors_medical_examination_data = []

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        addHomeVisitData() {
            if(this.add_home_visit_data == false) {
                this.add_home_visit_data = true
                this.edit_home_visit_data = false
            } else {
                this.edit_home_visit_data = false
            }

            this.selected_home_visit_data = null

            this.home_visit_type_id = ''
            this.visit_date = ''
            this.next_visit_date = ''
            this.home_visit_observations = ''
            this.home_visit_report = null

            this.selected_home_visit_data = null
            this.errors_home_visit_data = []

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        addDocumentData() {
            if(this.add_document_data == false) {
                this.add_document_data = true
                this.edit_document_data = false
            } else {
                this.edit_document_data = false
            }

            this.selected_document_data = null

            this.document_type_id = ''
            this.document_observations = ''
            this.document = null

            this.selected_document_data = null
            this.errors_document_data = []

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        getRelativesData(collaborator_id) {
            axios.get(`/relative-data/${collaborator_id}`)
            .then(response => {
                this.relatives_data = response.data.relatives_data;
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        getAcademicData(collaborator_id) {
            axios.get(`/academic-data/${collaborator_id}`)
            .then(response => {
                this.academic_achievements_data = response.data.academic_achievements_data;
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        getMedicalExaminationData(collaborator_id) {
            axios.get(`/medical-examination-data/${collaborator_id}`)
            .then(response => {
                this.medical_examination_data = response.data.medical_examination_data;
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        getHomeVisitData(collaborator_id) {
            axios.get(`/home-visit-data/${collaborator_id}`)
            .then(response => {
                this.home_visit_data = response.data.home_visit_data;
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        getDocumentData(collaborator_id) {
            axios.get(`/document-data/${collaborator_id}`)
            .then(response => {
                this.document_data = response.data.document_data;
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        changeMainTab(tab) {
            this.card_selected = tab
            // if(tab == 'contract') {
            //     this.getContractualInformation(this.collaborator.id)
            // }
            this.message = ''
        },
        change_tab_additional_info_status(status) {
            this.tab_additional_info_status = status

            this.add_relative_data = false
            this.edit_relative_data = false
            this.add_academic_data = false
            this.edit_academic_data = false
            this.add_medical_examination_data = false
            this.edit_medical_examination_data = false
            this.add_home_visit_data = false
            this.edit_home_visit_data = false

            this.selected_relative_data = null
            this.selected_academic_data = null
            this.selected_medical_examination_data = null
            this.selected_home_visit_data = null

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false

            this.message = ''
        },
        changeRelativeData(id, index) {
            let new_selection_relative_data;

            if(this.relatives_data && this.relatives_data.length>0) {
                this.relatives_data.forEach(element => {
                    if(element.id !== id) {

                    } else {
                        new_selection_relative_data = element
                    }
                }, new_selection_relative_data);
            }

            this.selected_relative_data = new_selection_relative_data

            this.add_relative_data = false
            this.edit_relative_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        changeAcademicData(id, index) {
            let new_selection_academic_data;

            if(this.academic_achievements_data && this.academic_achievements_data.length>0) {
                this.academic_achievements_data.forEach(element => {
                    if(element.id !== id) {

                    } else {
                        new_selection_academic_data = element
                    }
                }, new_selection_academic_data);
            }

            this.selected_academic_data = new_selection_academic_data

            this.add_academic_data = false
            this.edit_academic_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        changeMedicalExaminationData(id, index) {
            let new_selection_medical_examination_data;

            if(this.medical_examination_data && this.medical_examination_data.length>0) {
                this.medical_examination_data.forEach(element => {
                    if(element.id !== id) {

                    } else {
                        new_selection_medical_examination_data = element
                    }
                }, new_selection_medical_examination_data);
            }

            this.selected_medical_examination_data = new_selection_medical_examination_data

            this.add_medical_examination_data = false
            this.edit_medical_examination_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        changeHomeVisitData(id, index) {
            let new_selection_home_visit_data;

            if(this.home_visit_data && this.home_visit_data.length>0) {
                this.home_visit_data.forEach(element => {
                    if(element.id !== id) {

                    } else {
                        new_selection_home_visit_data = element
                    }
                }, new_selection_home_visit_data);
            }

            this.selected_home_visit_data = new_selection_home_visit_data

            this.add_home_visit_data = false
            this.edit_home_visit_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        changeDocumentData(id, index) {
            let new_selection_document_data;

            if(this.document_data && this.document_data.length>0) {
                this.document_data.forEach(element => {
                    if(element.id !== id) {

                    } else {
                        new_selection_document_data = element
                    }
                }, new_selection_document_data);
            }

            this.selected_document_data = new_selection_document_data

            this.add_document_data = false
            this.edit_document_data = false

            this.successfully_created_message = false
            this.successfully_updated_message = false
            this.successfully_deleted_message = false
        },
        onChangeCertificate(e) {
            this.certificate = e.target.files[0]
        },
        onChangeExaminationResult(e) {
            this.examination_result = e.target.files[0]
        },
        onChangeHomeVisitReport(e) {
            this.home_visit_report = e.target.files[0]
        },
        onChangeDocument(e) {
            this.document = e.target.files[0]
        },
        storeRelativeData() {

            let dataSend = {
                'collaborator_id': this.collaborator.id,
                'name': this.name,
                'first_surname': this.first_surname,
                'second_surname': this.second_surname,
                'relationship_id': this.relationship_type_id,
                'occupation_id': this.occupation_type_id,
                'sex_type_id': this.sex_type_id,
                'birth_date': this.birth_date,
            }

            let url = ''
            axios.post('/relative-data', dataSend).then(
                (response) => {
                    this.getRelativesData(this.collaborator.id)
                    this.selected_relative_data = response.data.relative_data;

                    this.add_relative_data = false
                    this.edit_relative_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_relative_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_relative_data = error.response.data.errors
                    }
                })
        },
        storeAcademicData() {
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('achievement_type_id', this.achievement_type_id)
            fd.append('degree', this.degree)
            fd.append('institution', this.institution)
            fd.append('end_date', this.end_date)
            fd.append('certificate', this.certificate)

            let url = ''
            axios.post('/academic-data', fd).then(
                (response) => {
                    this.getAcademicData(this.collaborator.id)
                    this.academic_achievements_data = response.data.academic_achievements_data;
                    this.selected_academic_data = response.data.academic_data;

                    this.add_academic_data = false
                    this.edit_academic_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_academic_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_academic_data = error.response.data.errors
                    }
                })
        },
        storeMedicalExaminationData() {
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('examination_type_id', this.examination_type_id)
            fd.append('examination_date', this.examination_date)
            fd.append('examination_result', this.examination_result)
            fd.append('examination_observations', this.examination_observations)

            let url = ''
            axios.post('/medical-examination-data', fd).then(
                (response) => {
                    this.getMedicalExaminationData(this.collaborator.id)
                    this.selected_medical_examination_data = response.data.medical_examination_data;

                    this.add_medical_examination_data = false
                    this.edit_medical_examination_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_medical_examination_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_medical_examination_data = error.response.data.errors
                    }
                })
        },
        storeHomeVisitData() {
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('home_visit_type_id', this.home_visit_type_id)
            fd.append('visit_date', this.visit_date)
            fd.append('next_visit_date', this.next_visit_date)
            fd.append('home_visit_observations', this.home_visit_observations)
            fd.append('home_visit_report', this.home_visit_report)

            let url = ''
            axios.post('/home-visit-data', fd).then(
                (response) => {
                    this.getHomeVisitData(this.collaborator.id)
                    this.selected_home_visit_data = response.data.home_visit_data;

                    this.add_home_visit_data = false
                    this.edit_home_visit_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_home_visit_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_home_visit_data = error.response.data.errors
                    }
                })
        },
        storeDocumentData() {
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('document_type_id', this.contractual_document_type_id)
            fd.append('document_observations', this.document_observations)
            if(this.document) {
                fd.append('document', this.document)
            }

            let url = ''
            axios.post('/document-data', fd).then(
                (response) => {
                    this.getDocumentData(this.collaborator.id)
                    this.selected_document_data = response.data.document_data;

                    this.add_document_data = false
                    this.edit_document_data = false

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_document_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_document_data = error.response.data.errors
                    }
                })
        },
        editRelativeData(item, index) {
            let new_selection_relative_data;

            if(this.relatives_data && this.relatives_data.length>0) {
                this.relatives_data.forEach(element => {
                    if(element.id !== item.id) {

                    } else {
                        new_selection_relative_data = element
                    }
                }, new_selection_relative_data);
            }
            this.selected_relative_data = new_selection_relative_data

            this.relative_data_to_edit = item

            this.add_relative_data = false
            this.edit_relative_data = true

            this.name = item.name
            this.first_surname = item.first_surname
            this.second_surname = item.second_surname
            this.relationship_type_id = item.relationship_id
            this.occupation_type_id = item.occupation_id
            this.sex_type_id = item.sex_id
            this.birth_date = item.birth_date

            this.errors_relative_data = null
        },
        editAcademicData(item, index) {
            let new_selection_academic_data;

            if(this.academic_achievements_data && this.academic_achievements_data.length>0) {
                this.academic_achievements_data.forEach(element => {
                    if(element.id !== item.id) {

                    } else {
                        new_selection_academic_data = element
                    }
                }, new_selection_academic_data);
            }
            this.selected_academic_data = new_selection_academic_data

            this.academic_data_to_edit = item

            this.add_academic_data = false
            this.edit_academic_data = true

            this.achievement_type_id = item.achievement_type_id
            this.degree = item.degree
            this.institution = item.institution
            this.end_date = item.end_date
            this.certificate = null

            this.errors_academic_data = null
        },
        editMedicalExaminationData(item, index) {
            let new_selection_medical_examination_data;

            if(this.medical_examination_data && this.medical_examination_data.length>0) {
                this.medical_examination_data.forEach(element => {
                    if(element.id !== item.id) {

                    } else {
                        new_selection_medical_examination_data = element
                    }
                }, new_selection_medical_examination_data);
            }
            this.selected_medical_examination_data = new_selection_medical_examination_data

            this.medical_examination_data_to_edit = item

            this.add_medical_examination_data = false
            this.edit_medical_examination_data = true

            this.examination_type_id = item.examination_type_id
            this.examination_date = item.examination_date
            this.examination_observations = item.observations
            this.examination_result = null

            this.errors_medical_examination_data = null
        },
        editHomeVisitData(item, index) {
            let new_selection_home_visit_data;

            if(this.home_visit_data && this.home_visit_data.length>0) {
                this.home_visit_data.forEach(element => {
                    if(element.id !== item.id) {

                    } else {
                        new_selection_home_visit_data = element
                    }
                }, new_selection_home_visit_data);
            }
            this.selected_home_visit_data = new_selection_home_visit_data

            this.home_visit_data_to_edit = item

            this.add_home_visit_data = false
            this.edit_home_visit_data = true

            this.home_visit_type_id = item.home_visit_type_id
            this.visit_date = item.visit_date
            this.next_visit_date = item.next_visit_date
            this.home_visit_observations = item.observations
            this.home_visit_report = null

            this.errors_home_visit_data = null
        },
        editDocumentData(item, index) {
            let new_selection_document_data;

            if(this.document_data && this.document_data.length>0) {
                this.document_data.forEach(element => {
                    if(element.id !== item.id) {

                    } else {
                        new_selection_document_data = element
                    }
                }, new_selection_document_data);
            }
            this.selected_document_data = new_selection_document_data

            this.document_data_to_edit = item

            this.add_document_data = false
            this.edit_document_data = true

            this.document_type_id = item.document_type_id
            this.document_observations = item.observations
            this.document = null

            this.errors_document_data = null
        },
        updateRelativeData(){
            let dataSend = {
                'id': this.relative_data_to_edit.id,
                'collaborator_id': this.collaborator.id,
                'name': this.name,
                'first_surname': this.first_surname,
                'second_surname': this.second_surname,
                'relationship_id': this.relationship_type_id,
                'occupation_id': this.occupation_type_id,
                'sex_type_id': this.sex_type_id,
                'birth_date': this.birth_date,
            }

            let url = ''
            axios.put(`/relative-data-update/${this.relative_data_to_edit.id}`, dataSend).then(
                (response) => {
                    this.getRelativesData(this.collaborator.id)
                    this.selected_relative_data = response.data.relative_data

                    this.add_relative_data = false
                    this.edit_relative_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_academic_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_relative_data = error.response.data.errors
                    }
                })
        },
        updateAcademicData(){
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('achievement_type_id', this.achievement_type_id)
            fd.append('degree', this.degree)
            fd.append('institution', this.institution)
            fd.append('end_date', this.end_date)
            fd.append('certificate', this.certificate)
            fd.append('_method', 'PUT')

            let url = ''
            axios.post(`/academic-data-update/${this.academic_data_to_edit.id}`, fd).then(
                (response) => {
                    this.getAcademicData(this.collaborator.id)
                    this.selected_academic_data = response.data.academic_data

                    this.add_academic_data = false
                    this.edit_academic_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_academic_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_academic_data = error.response.data.errors
                    }
                })
        },
        updateMedicalExaminationData(){
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('examination_type_id', this.examination_type_id)
            fd.append('examination_date', this.examination_date)
            fd.append('examination_observations', this.examination_observations)
            fd.append('examination_result', this.examination_result)
            fd.append('_method', 'PUT')

            let url = ''
            axios.post(`/medical-examination-data-update/${this.medical_examination_data_to_edit.id}`, fd).then(
                (response) => {
                    this.getMedicalExaminationData(this.collaborator.id)
                    this.selected_medical_examination_data = response.data.medical_examination

                    this.add_medical_examination_data = false
                    this.edit_medical_examination_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_medical_examination_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_medical_examination_data = error.response.data.errors
                    }
                })
        },
        updateHomeVisitData(){
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('home_visit_type_id', this.home_visit_type_id)
            fd.append('visit_date', this.visit_date)
            fd.append('next_visit_date', this.next_visit_date)
            fd.append('home_visit_observations', this.home_visit_observations)
            fd.append('home_visit_report', this.home_visit_report)
            fd.append('_method', 'PUT')

            let url = ''
            axios.post(`/home-visit-data-update/${this.home_visit_data_to_edit.id}`, fd).then(
                (response) => {
                    this.getHomeVisitData(this.collaborator.id)
                    this.selected_home_visit_data = response.data.home_visit

                    this.add_home_visit_data = false
                    this.edit_home_visit_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_home_visit_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_home_visit_data = error.response.data.errors
                    }
                })
        },
        updateDocumentData(){
            let fd = new FormData()

            fd.append('collaborator_id', this.collaborator.id)
            fd.append('document_type_id', this.document_type_id)
            fd.append('document_observations', this.document_observations != null ? this.document_observations : '')
            fd.append('document', this.document)
            fd.append('_method', 'PUT')

            let url = ''
            axios.post(`/document-data-update/${this.document_data_to_edit.id}`, fd).then(
                (response) => {
                    this.getDocumentData(this.collaborator.id)
                    this.selected_document_data = response.data.document_data

                    this.add_document_data = false
                    this.edit_document_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    this.getMessage(response.data.message)

                    this.errors_document_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_document_data = error.response.data.errors
                    }
                })
        },
        deleteRelativeData(item, index) {

            axios.delete(`/relative-data-delete/${item.id}`).then(
                (response) => {
                    this.getRelativesData(this.collaborator.id)

                    this.add_relative_data = false
                    this.edit_relative_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.getMessage(response.data.message)

                    this.selected_relative_data = null

                    this.errors_relative_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_relative_data = error.response.data.errors
                    }
                })
        },
        deleteAcademicData(item, index) {
            axios.delete(`/academic-data-delete/${item.id}`).then(
                (response) => {
                    this.getAcademicData(this.collaborator.id)

                    this.add_academic_data = false
                    this.edit_academic_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.getMessage(response.data.message)

                    this.selected_academic_data = null

                    this.errors_academic_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_academic_data = error.response.data.errors
                    }
                })
        },
        deleteMedicalExaminationData(item, index) {
            axios.delete(`/medical-examination-data-delete/${item.id}`).then(
                (response) => {
                    this.getMedicalExaminationData(this.collaborator.id)

                    this.add_medical_examination_data = false
                    this.edit_medical_examination_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.getMessage(response.data.message)

                    this.selected_medical_examination_data = null

                    this.errors_medical_examination_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_medical_examination_data = error.response.data.errors
                    }
                })
        },
        deleteHomeVisitData(item, index) {
            axios.delete(`/home-visit-data-delete/${item.id}`).then(
                (response) => {
                    this.getHomeVisitData(this.collaborator.id)

                    this.add_home_visit_data = false
                    this.edit_home_visit_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.getMessage(response.data.message)

                    this.selected_home_visit_data = null

                    this.errors_home_visit_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_home_visit_data = error.response.data.errors
                    }
                })
        },
        deleteDocumentData(item, index) {
            axios.delete(`/document-data-delete/${item.id}`).then(
                (response) => {
                    this.getDocumentData(this.collaborator.id)

                    this.add_document_data = false
                    this.edit_document_data = false

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.getMessage(response.data.message)

                    this.selected_document_data = null

                    this.errors_document_data = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors_document_data = error.response.data.errors
                    }
                })
        },
        getAge(dateString) {
            let hoy = new Date()
            let fechaNacimiento = new Date(dateString)
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear()
            let diferenciaMeses = hoy.getMonth() - fechaNacimiento.getMonth()
            if (
                diferenciaMeses < 0 ||
                (diferenciaMeses === 0 && hoy.getDate() < fechaNacimiento.getDate())
            ) {
                edad--
            }
            return edad
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
    @import './../../assets/css/collaborator_show.css';
    @import './../../assets/css/custom.css';
</style>
