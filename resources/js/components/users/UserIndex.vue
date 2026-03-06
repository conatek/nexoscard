<template>
    <div>
        <div class="app-page-title">
            <div v-if="selected_user == null && add_user == false && edit_user == false" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users text-success"></i>
                    </div>
                    <div>
                        Listado de Usuarios
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="addUser()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-plus"></i>
                        Agregar
                    </button>
                </div>
            </div>
            <div v-else-if="selected_user == null && add_user == true && edit_user == false" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users text-success"></i>
                    </div>
                    <div>
                        Agregar Usuario
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="returnToList()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-arrow-left"></i>
                        Volver al listado
                    </button>
                </div>
            </div>
            <div v-else-if="selected_user != null && add_user == false && edit_user == true" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users text-success"></i>
                    </div>
                    <div>
                        Editar Usuario
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="returnToList()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-arrow-left"></i>
                        Volver al listado
                    </button>
                </div>
            </div>
        </div>

        <!-- <div v-if="message" class="message-success mb-3">
            <div class="content d-flex align-items-start p-2">
                <p class="mb-0" style="font-size: 14px;"> {{ message }}</p>
            </div>
        </div> -->

        <div v-if="origin === 'created'" class="message-success mb-3">
            <div class="content d-flex align-items-start p-2">
                <p class="mb-0">Usuario creado exitosamente</p>
            </div>
        </div>
        <div v-if="origin === 'updated'" class="message-success mb-3">
            <div class="content d-flex align-items-start p-2">
                <p class="mb-0">Usuario actualizado exitosamente</p>
            </div>
        </div>
        <div v-if="origin === 'deleted'" class="message-success mb-3">
            <div class="content d-flex align-items-start p-2">
                <p class="mb-0">Usuario eliminado exitosamente</p>
            </div>
        </div>

        <div v-if="selected_user == null && add_user == false && edit_user == false" class="main-card mb-3 card">
            <div class="card-body table-responsive">
                <table style="width: 100%;" id="dt_users" class="table table-cntk table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Empresa</th>
                            <th class="text-center">Roles</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="user.id">
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.company.company_name }}</td>
                            <td class="text-center">
                                <div v-for="role in user.roles" :key="role.id">
                                    <span v-if="role" class="badge bg-success" style="min-width: 100px;">{{ role.name }}</span>
                                    <span v-else class="badge bg-danger" style="min-width: 100px;">Sin rol</span>
                                </div>
                            </td>
                            <td class="align-middle text-end">
                                <button v-if="user" @click="editUser(user.id)" class="me-2 btn-icon btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i> Editar
                                </button>
                                <button v-if="user" @click="showDeleteAlert(user.id, index)" class="btn-icon btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Empresa</th>
                            <th class="text-center">Roles</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div v-else-if="selected_user == null && add_user == true && edit_user == false" class="main-card mb-3 card">
            <div class="card-body">
                <form @submit.prevent="storeUser" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input v-model="name" name="name" id="name" type="text" class="form-control" placeholder="Ingresa el nombre del ususario" autofocus>
                                <span v-if="errors && errors.name" class="error text-danger" for="name">{{ errors.name[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input v-model="email" name="email" id="email" type="email" class="form-control" placeholder="Ingresa el correo electrónico" autocomplete="new-password">
                                <span v-if="errors && errors.email" class="error text-danger" for="email">{{ errors.email[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative mb-3">
                                <label for="company_id" class="form-label">Empresa*</label>
                                <select v-if="companies.length > 0" v-model="company_id" name="company_id" class="form-control"  id="company_id">
                                    <option value="" disabled selected hidden>Selecciona la empresa</option>
                                    <option v-for="company in companies" :value="company.id">{{ company.company_name }}</option>
                                </select>
                                <span v-if="errors && errors.company_id" class="error text-danger" for="company_id">{{ errors.company_id[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative mb-3">
                                <label for="image" class="form-label">Imagen</label>
                                <div class="input-group">
                                    <input @change="onChangeImage" type="file" name="image" id="image" class="form-control">
                                </div>
                                <span v-if="errors && errors.image" class="error text-danger" for="image">{{ errors.image[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input v-model="password" name="password" id="password" type="password" class="form-control" placeholder="Ingresa la contraseña solo si la deseas modificar" autocomplete="new-password">
                                <span v-if="errors && errors.password" class="error text-danger" for="password">{{ errors.password[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 btn btn-primary">Guardar</button>
                    <hr>
                    <p>Asignar Roles</p>
                    <div v-if="roles" class="row">
                        <div v-for="role in roles" :key="role.id" class="col-lg-3 col-md-4 col-sm-6 py-1">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <!-- <p>{{ role.name }}</p> -->
                                    <input type="checkbox" :id="'checkbox_' + role.name"
                                        :value="role.id" v-model="selected_roles">
                                    <label :for="'checkbox_' + role.name">{{ role.name }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div v-else-if="selected_user != null && add_user == false && edit_user == true && userData">
            <div class="col-md-12">
                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary">
                    <div class="card-body mx-auto">
                        <div class="avatar-icon-wrapper avatar-icon-xl d-flex mb-3 justify-content-center">
                            <div class="avatar-icon">
                                <!-- <img v-if="userData && userData.image_url" :src="userData.image_url" :alt="userData.name">
                                <img v-else :src="userData && userData.image_url ? userData.image_url : '/images/default-profile.jpeg'" :alt="userData.name"> -->

                                <img
                                    :src="userData.image_url ? userData.image_url : '/images/default-profile.jpeg'"
                                    :alt="userData.name ? userData.name : 'Perfil predeterminado'">
                            </div>
                        </div>
                        <div>
                            <h5 class="menu-header-title text-center">{{ userData.name }}</h5>
                            <h6 class="menu-header-subtitle text-center">{{ userData.email }}</h6>
                            <h6 class="menu-header-subtitle text-center">{{ selected_user.company.company_name }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form @submit.prevent="updateUser" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input v-model="name" name="name" id="name" type="text" class="form-control" autofocus>
                                    <span v-if="errors && errors.name" class="error text-danger" for="name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input v-model="email" name="email" id="email" type="email" class="form-control">
                                    <span v-if="errors && errors.email" class="error text-danger" for="email">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative mb-3">
                                    <label for="image" class="form-label">Imagen</label>
                                    <div class="input-group">
                                        <input @change="onChangeImage" type="file" name="image" id="image" class="form-control">
                                    </div>
                                    <span v-if="errors && errors.image" class="error text-danger" for="image">{{ errors.image[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input v-model="password" name="password" id="password" type="password" class="form-control" placeholder="Ingrese la contraseña solo si desea modificar">
                                    <span v-if="errors && errors.password" class="error text-danger" for="password">{{ errors.password[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                        <hr>
                        <p>Asignar Roles</p>
                        <div v-if="roles" class="row">
                            <div v-for="role in roles" :key="role.id" class="col-lg-3 col-md-4 col-sm-6 py-1">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <!-- <p>{{ role.name }}</p> -->
                                        <input type="checkbox" :id="'checkbox_' + role.name"
                                            :value="role.id" v-model="selected_roles">
                                        <label :for="'checkbox_' + role.name">{{ role.name }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { usersDatatable } from '../../assets/js/tables.js';

export default {
    props: {
        companies: {
            default: null,
        },
        users: {
            default: null,
        },
        roles: {
            default: null,
        },
    },
    data() {
        return {
            selected_user: null,
            add_user: false,
            edit_user: false,

            userData: null,
            // company_id: '',

            selected_roles: [],
            company_id: '',
            name: '',
            email: '',
            image: null,
            password: '',

            errors: null,
            message: '',

            successfully_created_message: false,
            successfully_updated_message: false,
            successfully_deleted_message: false,

            origin: '',
        };
    },
    mounted() {
        this.getOrigin()
        usersDatatable();
    },
    watch: {
        userData: {
            immediate: true,
            handler(newUserData) {
                if (newUserData?.roles) {
                    this.selected_roles = newUserData.roles.map(role => role.id);
                }
            }
        }
    },
    methods: {
        showDeleteAlert(item, index) {
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
                this.deleteUser(item, index)
                this.$swal({
                    title: "Eliminado!",
                    text: "Su registro ha sido borrado.",
                    icon: "success"
                });
            }
            });
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
        returnToList() {
            this.selected_user = null
            this.add_user = false
            this.edit_user = false

            usersDatatable();
        },
        onChangeImage(e) {
            this.image = e.target.files[0]
        },
        addUser() {
            this.selected_user = null;
            this.add_user = true;
            this.edit_user = false;

            this.company_id = '';
            this.name = '';
            this.email = '';
            this.image = null;
            this.password = '';
            this.selected_roles = [];

        },
        storeUser() {
            let fd = new FormData()

            fd.append('company_id', this.company_id)
            fd.append('name', this.name)
            fd.append('email', this.email)
            fd.append('image', this.image)
            fd.append('password', this.password)
            this.selected_roles.forEach((role, index) => {
                fd.append(`roles[${index}]`, role);
            });

            // console.log('Este es el FD:');
            // for (let [key, value] of fd.entries()) {
            //     console.log(`${key}: ${value}`);
            // }

            axios.post('/users', fd).then(
                (res) => {
                    localStorage.setItem('origin', 'created');

                    this.successfully_created_message = true
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = false

                    this.getMessage(res.data.message)

                    window.location.href = '/users'
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        editUser(user) {
            this.selected_user = this.users.find(u => u.id === user);
            this.add_user = false;
            this.edit_user = true;

            this.company_id = this.selected_user.company_id;
            this.name = this.selected_user.name;
            this.email = this.selected_user.email;

            axios.get(`/users/${user}/edit`).then(
                (res) => {
                    this.userData = res.data.user;
                    this.errors = null;
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        updateUser() {
            let fd = new FormData()

            fd.append('company_id', this.company_id)
            fd.append('name', this.name)
            fd.append('email', this.email)
            fd.append('image', this.image)
            if (this.password) {
                fd.append('password', this.password);
            }
            this.selected_roles.forEach((role, index) => {
                fd.append(`roles[${index}]`, role);
            });
            fd.append('_method', 'PUT')

            let url = ''
            console.log(`/users/${this.selected_user.id}`);
            axios.post(`/users/${this.selected_user.id}`, fd).then(
                (res) => {
                    localStorage.setItem('origin', 'updated');

                    this.successfully_created_message = false
                    this.successfully_updated_message = true
                    this.successfully_deleted_message = false

                    url = `/users`
                    window.location.href = url

                    this.getMessage(res.data.message)

                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        deleteUser(user) {
            console.log('El usuario es:');
            console.log(user);
            axios.delete(`/users/${user}`).then(
                (res) => {
                    localStorage.setItem('origin', 'deleted');

                    this.successfully_created_message = false
                    this.successfully_updated_message = false
                    this.successfully_deleted_message = true

                    this.getMessage(res.data.message)

                    window.location.href = '/users'
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
    },
};
</script>

<style scoped>
    @import './../../assets/css/custom.css';

    .message-success {
        width: 100%;
        background: #D8FFDC;
        border-radius: 4px;
        padding: 10px;
    }

    .message-success .content {
        align-items: flex-start;
        justify-content: center;
        border-left: 5px solid #00660A;
    }

    .message-success .content p {
        font-size: 16px;
        line-height: 16px;
    }
</style>
