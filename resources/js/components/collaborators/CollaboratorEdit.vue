<template>
    <div>
        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
            <li class="nav-item">
                <a @click="tab_collaborator_status = 'general'" role="tab" class="nav-link" :class="tab_collaborator_status == 'general' ? 'active' : ''" id="tab-0" data-bs-toggle="tab" href="#tab-content-0">
                    <span>Información General</span>
                </a>
            </li>
            <li class="nav-item">
                <a @click="tab_collaborator_status = 'contract'" role="tab" class="nav-link" :class="tab_collaborator_status == 'contract' ? 'active' : ''" id="tab-1" data-bs-toggle="tab" href="#tab-content-1">
                    <span>Información Contractual</span>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a @click="tab_collaborator_status = 'documentation'" role="tab" class="nav-link" :class="tab_collaborator_status == 'documentation' ? 'active' : ''" id="tab-2" data-bs-toggle="tab" href="#tab-content-2">
                    <span>Gestión Documental</span>
                </a>
            </li> -->
        </ul>
        <div class="tab-content">
            <div class="tab-pane tabs-animation fade" :class="tab_collaborator_status == 'general' ? 'show active' : ''" id="tab-content-0" role="tabpanel">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <form @submit.prevent="updateCollaborator" enctype="multipart/form-data">
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
                                                        <label for="image" class="form-label">Foto</label>
                                                        <div class="input-group">
                                                            <input @change="onChangeImage" type="file" name="image" id="image" class="form-control">
                                                        </div>
                                                        <span v-if="errors && errors.image" class="error text-danger" for="image">{{ errors.image[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="document_type_id" class="form-label">Tipo de documento*</label>
                                                        <select v-model="document_type_id" name="document_type_id" class="form-control"  id="document_type_id">
                                                            <option value="" disabled selected hidden>Seleccionar Tipo Documento</option>
                                                            <option v-for="document_type in document_types" :value="document_type.id">{{ document_type.type }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.document_type_id" class="error text-danger" for="document_type_id">{{ errors.document_type_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="document_number" class="form-label">Documento*</label>
                                                        <input v-model="document_number" name="document_number" id="document_number" type="text" class="form-control" placeholder="Ingrese número documento">
                                                        <span v-if="errors && errors.document_number" class="error text-danger" for="document_number">{{ errors.document_number[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="civil_status_type_id" class="form-label">Estado civil*</label>
                                                        <select v-model="civil_status_type_id" class="form-control" name="civil_status_type_id" id="civil_status_type_id">
                                                            <option value="" disabled selected hidden>Seleccionar Sexo</option>
                                                            <option v-for="civil_status in civil_status_types" :value="civil_status.id">{{ civil_status.type }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.civil_status_type_id" class="error text-danger" for="civil_status_type_id">{{ errors.civil_status_type_id[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="name" class="form-label">Nombres*</label>
                                                        <input v-model="name" name="name" id="name" type="text" class="form-control" placeholder="Ingrese su(s) nombre(s)">
                                                        <span v-if="errors && errors.name" class="error text-danger" for="name">{{ errors.name[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="first_surname" class="form-label">Primer apellido*</label>
                                                        <input v-model="first_surname" name="first_surname" id="first_surname" type="text" class="form-control" placeholder="Ingrese su primer apellido">
                                                        <span v-if="errors && errors.first_surname" class="error text-danger" for="first_surname">{{ errors.first_surname[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="second_surname" class="form-label">Segundo apellido</label>
                                                        <input v-model="second_surname" name="second_surname" id="second_surname" type="text" class="form-control" placeholder="Ingrese su segundo apellido">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="sex_type_id" class="form-label">Sexo*</label>
                                                        <select v-model="sex_type_id" class="form-control" name="sex_type_id" id="sex_type_id">
                                                            <option value="" disabled selected hidden>Seleccionar Sexo</option>
                                                            <option v-for="sex in sex_types" :value="sex.id">{{ sex.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.sex_type_id" class="error text-danger" for="sex_type_id">{{ errors.sex_type_id[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="document_province_id" class="form-label">Dpto De Expedición*</label>
                                                        <select v-model="document_province_id" @change="getCities(document_province_id, 'document')" class="form-control" name="province" id="document_province_id">
                                                            <option value="" disabled selected hidden>Seleccionar Dpto</option>
                                                            <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.document_province_id" class="error text-danger" for="document_province_id">{{ errors.document_province_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="document_city_id" class="form-label">Municipio de Expedición*</label>
                                                        <select v-model="document_city_id" class="form-control" name="document_city_id" id="document_city_id">
                                                            <!-- <option value="" :selected="cities === []" disabled selected hidden>Seleccionar Municipio</option> -->
                                                            <option value="" disabled selected hidden>Seleccionar Municipio</option>
                                                            <option v-for="city in document_cities" :value="city.id">{{ city.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.document_city_id" class="error text-danger" for="document_city_id">{{ errors.document_city_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="expedition_date" class="form-label">Fecha de Expedición*</label>
                                                        <input v-model="expedition_date" name="expedition_date" id="expedition_date" type="date" class="form-control" placeholder="Ingrese fecha expedición documento">
                                                        <span v-if="errors && errors.expedition_date" class="error text-danger" for="expedition_date">{{ errors.expedition_date[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="rh_type_id" class="form-label">RH*</label>
                                                        <select v-model="rh_type_id" class="form-control" name="rh_type_id" id="rh_type_id">
                                                            <option value="" disabled selected hidden>Seleccionar RH</option>
                                                            <option v-for="rh in rh_types" :value="rh.id">{{ rh.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.rh_type_id" class="error text-danger" for="rh_type_id">{{ errors.rh_type_id[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="birth_province_id" class="form-label">Dpto De Nacimiento*</label>
                                                        <select v-model="birth_province_id" @change="getCities(birth_province_id, 'birth')" class="form-control" name="province" id="birth_province_id">
                                                            <option value="" disabled selected hidden>Seleccionar Dpto</option>
                                                            <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.birth_province_id" class="error text-danger" for="birth_province_id">{{ errors.birth_province_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="birth_city_id" class="form-label">Municipio de Nacimiento*</label>
                                                        <select v-model="birth_city_id" class="form-control" name="birth_city_id" id="birth_city_id">
                                                            <option value="" disabled selected hidden>Seleccionar Municipio</option>
                                                            <option v-for="city in birth_cities" :value="city.id">{{ city.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.birth_city_id" class="error text-danger" for="birth_city_id">{{ errors.birth_city_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="birth_date" class="form-label">Fecha de Nacimiento*</label>
                                                        <input v-model="birth_date" name="birth_date" id="birth_date" type="date" class="form-control">
                                                        <span v-if="errors && errors.birth_date" class="error text-danger" for="birth_date">{{ errors.birth_date[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="position-relative mb-3">
                                                        <label for="observations" class="form-label">Observaciones</label>
                                                        <textarea v-model="observations" name="observations" id="observations" type="text" class="form-control" placeholder="Ingrese sus observaciones" rows="4" cols="50"></textarea>
                                                        <span v-if="errors && errors.observations" class="error text-danger" for="observations">{{ errors.observations[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="card-hover-shadow card-border mb-3 card">
                                        <div class="card-header">
                                            Información de Domicilio
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="residence_province_id" class="form-label">Dpto De Residencia*</label>
                                                        <select v-model="residence_province_id" @change="getCities(residence_province_id, 'residence')" class="form-control" name="province" id="residence_province_id">
                                                            <option value="" disabled selected hidden>Seleccionar Dpto</option>
                                                            <option v-for="province in provinces" :value="province.id">{{ province.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.residence_province_id" class="error text-danger" for="residence_province_id">{{ errors.residence_province_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="residence_city_id" class="form-label">Municipio de Residencia*</label>
                                                        <select v-model="residence_city_id" class="form-control" name="residence_city_id" id="residence_city_id">
                                                            <option value="" disabled selected hidden>Seleccionar Municipio</option>
                                                            <option v-for="city in residence_cities" :value="city.id">{{ city.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.residence_city_id" class="error text-danger" for="residence_city_id">{{ errors.residence_city_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="stratum_type_id" class="form-label">Estrato Social*</label>
                                                        <select v-model="stratum_type_id" class="form-control" name="stratum_type_id" id="stratum_type_id">
                                                            <option value="" disabled selected hidden>Seleccionar Estrato</option>
                                                            <option v-for="stratum in stratum_types" :value="stratum.id">{{ stratum.id }} - {{ stratum.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.stratum_type_id" class="error text-danger" for="stratum_type_id">{{ errors.stratum_type_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="stratum_type_id" class="form-label">Tenencia*</label>
                                                        <select v-model="housing_tenure_id" class="form-control" name="housing_tenure_id" id="housing_tenure_id">
                                                            <option value="" disabled selected hidden>Seleccionar Tenencia</option>
                                                            <option v-for="housing_tenure in housing_tenure_types" :value="housing_tenure.id">{{ housing_tenure.type }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.housing_tenure_id" class="error text-danger" for="housing_tenure_id">{{ errors.housing_tenure_id[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="position-relative mb-3">
                                                        <label for="address" class="form-label">Dirección*</label>
                                                        <input v-model="address" name="address" id="address" type="text" class="form-control" placeholder="Ingrese su dirección">
                                                        <span v-if="errors && errors.address" class="error text-danger" for="address">{{ errors.address[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-hover-shadow card-border mb-3 card">
                                        <div class="card-header">
                                            Información De Contacto
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="phone" class="form-label">Teléfono fijo</label>
                                                        <input v-model="phone" name="phone" id="phone" type="text" class="form-control" placeholder="Ingrese su número fijo">
                                                        <span v-if="errors && errors.phone" class="error text-danger" for="phone">{{ errors.phone[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="cellphone" class="form-label">Celular*</label>
                                                        <input v-model="cellphone" name="cellphone" id="cellphone" type="text" class="form-control" placeholder="Ingrese su número celular">
                                                        <span v-if="errors && errors.cellphone" class="error text-danger" for="cellphone">{{ errors.cellphone[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="position-relative mb-3">
                                                        <label for="email" class="form-label">Correo electrónico*</label>
                                                        <input v-model="email" name="email" id="email" type="text" class="form-control" placeholder="Ingrese su correo electrónico">
                                                        <span v-if="errors && errors.email" class="error text-danger" for="email">{{ errors.email[0] }}</span>
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
        </div>


        <div class="tab-content">
            <div class="tab-pane tabs-animation fade" :class="tab_collaborator_status == 'contract' ? 'show active' : ''" id="tab-content-1" role="tabpanel">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <form @submit.prevent="actionContractualInformation" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="card-hover-shadow card-border mb-3 card">
                                        <div class="card-header">
                                            Información Contractual
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="position_id" class="form-label">Cargo*</label>
                                                        <select v-model="position_id" name="position_id" class="form-control"  id="position_id">
                                                            <option value="" disabled selected hidden>Seleccionar Cargo</option>
                                                            <option v-for="position_type in position_types" :value="position_type.id">{{ position_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.position_id" class="error text-danger" for="position_id">{{ errors.position_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="salary" class="form-label">Salario*</label>
                                                        <input v-model="salary" name="salary" id="salary" type="text" class="form-control" placeholder="Ingrese el salario">
                                                        <span v-if="errors && errors.salary" class="error text-danger" for="salary">{{ errors.salary[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="contract_type_id" class="form-label">Tipo de contrato*</label>
                                                        <select v-model="contract_type_id" name="contract_type_id" class="form-control"  id="contract_type_id">
                                                            <option value="" disabled selected hidden>Seleccionar Tipo Contrato</option>
                                                            <option v-for="contract_type in contract_types" :value="contract_type.id">{{ contract_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.contract_type_id" class="error text-danger" for="contract_type_id">{{ errors.contract_type_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="contract_start_date" class="form-label">Fecha de ingreso*</label>
                                                        <input v-model="contract_start_date" name="contract_start_date" id="contract_start_date" type="date" class="form-control">
                                                        <span v-if="errors && errors.contract_start_date" class="error text-danger" for="contract_start_date">{{ errors.contract_start_date[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="contract_end_date" class="form-label">Fecha de terminación</label>
                                                        <input v-model="contract_end_date" name="contract_end_date" id="contract_end_date" type="date" class="form-control">
                                                        <span v-if="errors && errors.contract_end_date" class="error text-danger" for="contract_end_date">{{ errors.contract_end_date[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="test_period_end_date" class="form-label">Fecha de terminación prueba</label>
                                                        <input v-model="test_period_end_date" name="test_period_end_date" id="test_period_end_date" type="date" class="form-control">
                                                        <span v-if="errors && errors.test_period_end_date" class="error text-danger" for="test_period_end_date">{{ errors.test_period_end_date[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="corporate_email" class="form-label">Correo corporativo</label>
                                                        <input v-model="corporate_email" name="corporate_email" id="corporate_email" type="text" class="form-control" placeholder="Ingrese correo corporativo">
                                                        <span v-if="errors && errors.corporate_email" class="error text-danger" for="corporate_email">{{ errors.corporate_email[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="corporate_cellphone" class="form-label">Celular corporativo</label>
                                                        <input v-model="corporate_cellphone" name="corporate_cellphone" id="corporate_cellphone" type="text" class="form-control" placeholder="Ingrese celular corporativo">
                                                        <span v-if="errors && errors.corporate_cellphone" class="error text-danger" for="corporate_cellphone">{{ errors.corporate_cellphone[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="card-hover-shadow card-border mb-3 card">
                                        <div class="card-header">
                                            Entidades Relacionadas
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="bank_id" class="form-label">Banco*</label>
                                                        <select v-model="bank_id" name="bank_id" class="form-control"  id="bank_id">
                                                            <option value="" disabled selected hidden>Seleccionar Banco</option>
                                                            <option v-for="bank_type in bank_types" :value="bank_type.id">{{ bank_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.bank_id" class="error text-danger" for="bank_id">{{ errors.bank_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="bank_account" class="form-label">Número de cuenta*</label>
                                                        <input v-model="bank_account" name="bank_account" id="bank_account" type="text" class="form-control" placeholder="Ingrese número de cuenta">
                                                        <span v-if="errors && errors.bank_account" class="error text-danger" for="bank_account">{{ errors.bank_account[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="eps_id" class="form-label">EPS*</label>
                                                        <select v-model="eps_id" name="eps_id" class="form-control"  id="eps_id">
                                                            <option value="" disabled selected hidden>Seleccionar EPS</option>
                                                            <option v-for="eps_type in eps_types" :value="eps_type.id">{{ eps_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.eps_id" class="error text-danger" for="eps_id">{{ errors.eps_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="arl_id" class="form-label">ARL*</label>
                                                        <select v-model="arl_id" name="arl_id" class="form-control"  id="arl_id">
                                                            <option value="" disabled selected hidden>Seleccionar ARL</option>
                                                            <option v-for="arl_type in arl_types" :value="arl_type.id">{{ arl_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.arl_id" class="error text-danger" for="arl_id">{{ errors.arl_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="afp_pension_id" class="form-label">AFP Pensiones*</label>
                                                        <select v-model="afp_pension_id" name="afp_pension_id" class="form-control"  id="afp_pension_id">
                                                            <option value="" disabled selected hidden>Seleccionar AFP</option>
                                                            <option v-for="afp_type in afp_types" :value="afp_type.id">{{ afp_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.afp_pension_id" class="error text-danger" for="afp_pension_id">{{ errors.afp_pension_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="afp_saving_id" class="form-label">AFP Cesantías*</label>
                                                        <select v-model="afp_saving_id" name="afp_saving_id" class="form-control"  id="afp_saving_id">
                                                            <option value="" disabled selected hidden>Seleccionar AFP</option>
                                                            <option v-for="afp_type in afp_types" :value="afp_type.id">{{ afp_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.afp_saving_id" class="error text-danger" for="afp_saving_id">{{ errors.afp_saving_id[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="position-relative mb-3">
                                                        <label for="ccf_id" class="form-label">Caja de compensación familiar*</label>
                                                        <select v-model="ccf_id" name="ccf_id" class="form-control"  id="ccf_id">
                                                            <option value="" disabled selected hidden>Seleccionar caja de compensación</option>
                                                            <option v-for="ccf_type in ccf_types" :value="ccf_type.id">{{ ccf_type.name }}</option>
                                                        </select>
                                                        <span v-if="errors && errors.ccf_id" class="error text-danger" for="ccf_id">{{ errors.ccf_id[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button v-if="contractual_information == null || contractual_information == ''" type="submit" class="mt-2 btn btn-primary">Guardar</button>
                            <button v-else type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="tab-content">
            <div class="tab-pane tabs-animation fade" :class="tab_collaborator_status == 'documentation' ? 'show active' : ''" id="tab-content-2" role="tabpanel">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-hover-shadow card-border mb-3 card">
                                    <div class="card-header">
                                        Gestión Documental
                                    </div>
                                    <div class="card-body">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
export default {
    props: {
        collaborator: {
            default: null,
        },
        company_id: {
            default: null,
        },
        provinces: {
            default: null,
        },
        document_types: {
            default: null,
        },
        civil_status_types: {
            default: null,
        },
        sex_types: {
            default: null,
        },
        rh_types: {
            default: null,
        },
        stratum_types: {
            default: null,
        },
        housing_tenure_types: {
            default: null,
        },
        position_types: {
            default: null,
        },
        contract_types: {
            default: null,
        },
        bank_types: {
            default: null,
        },
        eps_types: {
            default: null,
        },
        afp_types: {
            default: null,
        },
        arl_types: {
            default: null,
        },
        ccf_types: {
            default: null,
        },
    },
    data() {
        return {
            tab_collaborator_status: 'general',

            name: this.collaborator.name,
            first_surname: this.collaborator.first_surname,
            second_surname: this.collaborator.second_surname,

            document_type_id: this.collaborator.document_type_id,
            document_number: this.collaborator.document_number,
            expedition_date: this.collaborator.expedition_date,
            document_province_id: '',
            document_city_id: '',

            birth_date: this.collaborator.birth_date,
            birth_province_id: '',
            birth_city_id: '',

            civil_status_type_id: this.collaborator.civil_status_type_id,
            sex_type_id: this.collaborator.sex_type_id,
            rh_type_id: this.collaborator.rh_type_id,
            stratum_type_id: this.collaborator.stratum_type_id,

            residence_province_id: '',
            residence_city_id: '',
            housing_tenure_id: this.collaborator.housing_tenure_id,

            document_cities: [],
            birth_cities: [],
            residence_cities: [],

            address: this.collaborator.address,
            phone: this.collaborator.phone,
            cellphone: this.collaborator.cellphone,
            email: this.collaborator.email,

            image: this.collaborator.image_url,
            observations: this.collaborator.observations,

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

            errors: null,
            message: '',
        }
    },
    mounted () {
        this.loadInitialData()
        this.getContractualInformation(this.collaborator.id)
    },
    methods: {
        loadInitialData() {
            this.document_province_id = this.collaborator.document_province_id
            this.getCities(this.document_province_id, 'document')
            this.document_city_id = this.collaborator.document_city_id

            this.birth_province_id = this.collaborator.birth_province_id
            this.getCities(this.birth_province_id, 'birth')
            this.birth_city_id = this.collaborator.birth_city_id

            this.residence_province_id = this.collaborator.residence_province_id
            this.getCities(this.residence_province_id, 'residence')
            this.residence_city_id = this.collaborator.residence_city_id
        },
        getCities(province, type) {
            let dataSend = {
                "province": province,
            }

            axios.post('/get-cities', dataSend).then(
                ({data}) => {
                    if(type == 'document') {
                        this.document_cities = data.cities
                    } else if(type == 'birth') {
                        this.birth_cities = data.cities
                    } else if(type == 'residence') {
                        this.residence_cities = data.cities
                    }
                })
        },
        onChangeImage(e) {
            this.image = e.target.files[0]
        },
        getContractualInformation(collaborator_id) {
            axios.get(`/contractual-information/${collaborator_id}`)
            .then(response => {
                this.contractual_information = response.data.contractual_information;

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
            })
            .catch(e => {
                //
            })
        },
        updateCollaborator() {
            let dataSend = {
                'image': this.image,
                'name': this.name,
                'first_surname': this.first_surname,
                'second_surname': this.second_surname,
                'document_type_id': this.document_type_id,

            }

            let fd = new FormData()

            fd.append('image', this.image)
            fd.append('name', this.name)
            fd.append('first_surname', this.first_surname)
            fd.append('second_surname', this.second_surname)
            fd.append('document_type_id', this.document_type_id)
            fd.append('document_number', this.document_number)
            fd.append('document_province_id', this.document_province_id)
            fd.append('document_city_id', this.document_city_id)
            fd.append('expedition_date', this.expedition_date)
            fd.append('birth_province_id', this.birth_province_id)
            fd.append('birth_city_id', this.birth_city_id)
            fd.append('birth_date', this.birth_date)
            fd.append('civil_status_type_id', this.civil_status_type_id)
            fd.append('sex_type_id', this.sex_type_id)
            fd.append('rh_type_id', this.rh_type_id)
            fd.append('observations', this.observations)
            fd.append('residence_province_id', this.residence_province_id)
            fd.append('residence_city_id', this.residence_city_id)
            fd.append('stratum_type_id', this.stratum_type_id)
            fd.append('housing_tenure_id', this.housing_tenure_id)
            fd.append('address', this.address)
            fd.append('phone', this.phone)
            fd.append('cellphone', this.cellphone)
            fd.append('email', this.email)
            fd.append('_method', 'PUT')

            let url = ''
            axios.post(`/collaborators/${this.collaborator.id}`, fd).then(
                (res) => {
                    localStorage.setItem('origin', 'updated');

                    // url = `/collaborators/${res.data.collaborator.id}`
                    url = `/collaborators`
                    window.location.href = url
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        actionContractualInformation() {
            if(this.contractual_information == null || this.contractual_information == '') {
                this.storeContractualInformation()
            } else {
                this.updateContractualInformation()
            }
        },
        storeContractualInformation() {
            let dataSend = {
                'position_id': this.position_id,
                'salary': this.salary,
                'contract_type_id': this.contract_type_id,
                'contract_start_date': this.contract_start_date,
                'contract_end_date': this.contract_end_date,
                'test_period_end_date': this.test_period_end_date,
                'corporate_email': this.corporate_email,
                'corporate_cellphone': this.corporate_cellphone,
                'bank_id': this.bank_id,
                'bank_account': this.bank_account,
                'eps_id': this.eps_id,
                'afp_pension_id': this.afp_pension_id,
                'afp_saving_id': this.afp_saving_id,
                'arl_id': this.arl_id,
                'ccf_id': this.ccf_id,
                '_method': 'PUT',
            }

            let url = ''
            axios.post(`/collaborators/${this.collaborator.id}/contractual-information`, dataSend).then(
                (res) => {
                    localStorage.setItem('origin', 'contractual_info_created');

                    // url = `/collaborators/${res.data.collaborator_contract.collaborator_id}`
                    url = `/collaborators`
                    window.location.href = url
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        updateContractualInformation() {
            let dataSend = {
                'position_id': this.position_id,
                'salary': this.salary,
                'contract_type_id': this.contract_type_id,
                'contract_start_date': this.contract_start_date,
                'contract_end_date': this.contract_end_date,
                'test_period_end_date': this.test_period_end_date,
                'corporate_email': this.corporate_email,
                'corporate_cellphone': this.corporate_cellphone,
                'bank_id': this.bank_id,
                'bank_account': this.bank_account,
                'eps_id': this.eps_id,
                'afp_pension_id': this.afp_pension_id,
                'afp_saving_id': this.afp_saving_id,
                'arl_id': this.arl_id,
                'ccf_id': this.ccf_id,
            }

            let url = ''
            axios.put(`/collaborators/${this.collaborator.id}/contractual-information`, dataSend).then(
                (res) => {
                    localStorage.setItem('origin', 'contractual_info_updated');

                    // url = `/collaborators/${res.data.collaborator_contract.collaborator_id}`
                    url = `/collaborators`
                    window.location.href = url
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
    },
}
</script>
