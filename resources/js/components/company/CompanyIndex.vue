<template>
    <div>
        <div class="app-page-title">
            <div v-if="selected_company == null && add_company == false && edit_company == false && load_collaborators == false" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-culture text-success"></i>
                    </div>
                    <div>
                        Listado de Empresas
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="addCompany()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-plus"></i>
                        Agregar
                    </button>
                </div>
            </div>
            <div v-else-if="selected_company != null && add_company == false && edit_company == false && load_collaborators == false" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-culture text-success"></i>
                    </div>
                    <div>
                        Detalle de la Empresa
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="returnToList()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-arrow-left"></i>
                        Volver al listado
                    </button>
                </div>
            </div>
            <div v-else-if="selected_company == null && add_company == true && edit_company == false && load_collaborators == false" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-culture text-success"></i>
                    </div>
                    <div>
                        Agregar Empresa
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="returnToList()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-arrow-left"></i>
                        Volver al listado
                    </button>
                </div>
            </div>
            <div v-else-if="selected_company != null && add_company == false && edit_company == true && load_collaborators == false" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-culture text-success"></i>
                    </div>
                    <div>
                        Editar Empresa
                    </div>
                </div>
                <div class="page-title-actions">
                    <button @click="returnToList()" class="btn btn-mh-dark-blue me-3">
                        <i class="fa fa-arrow-left"></i>
                        Volver al listado
                    </button>
                </div>
            </div>
            <div v-else-if="selected_company != null && add_company == false && edit_company == false && load_collaborators == true" class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users text-success"></i>
                    </div>
                    <div>
                        Cargar Colaboradores
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
        <!-- <div class="main-card mb-3 card">
            <div class="card-body"> -->
                <div class="row">
                    <div class="col-md-12">
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
                        <div v-else-if="origin == 'deleted'" class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
                            <span class="pe-2">
                                <i class="fa fa-star"></i>
                            </span>
                            Empresa eliminada correctamente!
                        </div>
                    </div>
                </div>
                <div v-if="selected_company == null && add_company == false && edit_company == false && load_collaborators == false">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <div class="">
                                        <i class="fa fa-search fa-w-16 "></i>
                                    </div>
                                </div>
                                <input v-model="search" @input="handleSearch" placeholder="Buscar Empresa ..." type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div v-if="companies !== null" class="row">
                        <div v-for="company in paginatedData" :key="company.id" class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="card-profile mb-3">
                                <div class="img-avatar">
                                    <img v-if="company && company.logo_url" :src="company.logo_url" :alt="company.company_name">
                                    <img v-else :src="'/images/default-profile.jpeg'" :alt="company ? company.company_name : 'Default profile'">
                                </div>
                                <div class="card-profile-text">
                                    <div class="portada"></div>
                                    <div class="title-total">
                                        <div class="title text-truncate">Sector de la Empresa</div>
                                        <div class="name-profile text-truncate" style="border-bottom: 1px dotted #127cb3; padding-bottom: 10px; margin-bottom: 10px;">{{ company ? company.company_name : '' }}</div>
                                        <div class="email-profile text-truncate">{{ company ? company.email : '' }}</div>
                                        <div class="cellphone-profile text-truncate">{{ company ? company.cellphone : '' }}</div>

                                        <!-- <div class="desc"></div> -->
                                        <div class="actions">
                                            <button v-if="company" @click="getCompany(company.id)"><i class="fa fa-eye"></i></button>
                                            <button v-if="company" @click="editCompany(company.id)"><i class="fa fa-edit"></i></button>
                                            <button v-if="company" @click="showDeleteAlert('deleteCompany', company.id)"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <nav v-if="companies !== null" class="" aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a @click="getPreviousPage()" class="page-link" aria-label="Previous">
                                    <span aria-hidden="true">«</span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                            </li>
                            <li v-for="page in totalPages" class="page-item" :class="currentPage == page ? 'active' : ''">
                                <a @click="getPageData(page)" class="page-link">{{ page }}</a>
                            </li>
                            <li class="page-item">
                                <a @click="getNextPage()" class="page-link" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div v-if="selected_company !== null && add_company == false && edit_company == false && load_collaborators == false">
                    <company-detail
                        @editCompany="editCompany"
                        @loadCollaborators="loadCollaborators"
                        :company="selected_company"
                        :company_type="companyData.company_type"
                        :industry_type="companyData.industry_type"
                        :identification_type="companyData.identification_type"
                        :province="companyData.province"
                        :city="companyData.city"
                    ></company-detail>
                </div>
                <div v-if="selected_company == null && add_company == true && edit_company == false && load_collaborators == false">
                    <company-create
                        :company_types="selectsDataCreate.company_types"
                        :document_types="selectsDataCreate.document_types"
                        :provinces="selectsDataCreate.provinces"
                        :industry_types="selectsDataCreate.industry_types"
                    ></company-create>
                </div>
                <div v-if="selected_company != null && add_company == false && edit_company == true && load_collaborators == false">
                    <company-edit
                        :company="companyDataEdit.company"
                        :company_types="companyDataEdit.company_types"
                        :document_types="companyDataEdit.document_types"
                        :provinces="companyDataEdit.provinces"
                        :industry_types="companyDataEdit.industry_types"
                    ></company-edit>
                </div>
                <div v-if="selected_company != null && add_company == false && edit_company == false && load_collaborators == true">
                    <company-collaborators></company-collaborators>

                    <!-- <p>Aquí va el componente para carga masiva de colaboradores ...</p> -->
                </div>


            <!-- </div>
        </div> -->
    </div>
</template>

<script>
export default {
    props: {
        companies: {
            default: null,
        },
    },
    data() {
        return {
            loading: 0,

            message: '',

            selected_company: null,
            add_company: false,
            edit_company: false,
            load_collaborators: false,

            companyData: null,
            companyDataEdit: null,
            selectsDataCreate: null,

            companiesData: null,
            companiesPerPage: 12,
            companiesWithFilter: false,
            paginatedData: [],
            currentPage: 1,
            totalPages: 0,
            search: '',
            filteredCompanies: [],

            origin: '',
        }
    },
    mounted () {
        this.getOrigin()

        this.getTotalPages(this.companies)
        this.getPageData(1)
    },
    methods: {
        showDeleteAlert(action, item) {
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
                if(action == 'deleteCompany') {
                    this.deleteCompany(item)
                }
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
        returnToList() {
            this.selected_company = null
            this.add_company = false
            this.edit_company = false
            this.load_collaborators = false
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
        handleSearch() {
            if (this.search.length > 0) {
                this.companiesWithFilter = true;

                this.filteredCompanies = this.companies.filter(company => {
                    return (
                        (company.company_name && company.company_name.toLowerCase().includes(this.search.toLowerCase())) ||
                        (company.identification_number && company.identification_number.toLowerCase().includes(this.search.toLowerCase())) ||
                        (company.email && company.email.toLowerCase().includes(this.search.toLowerCase())) ||
                        (company.cellphone && company.cellphone.toLowerCase().includes(this.search.toLowerCase()))
                    );
                });

                this.getTotalPages(this.filteredCompanies);
                this.getPageData(1); // Siempre muestra la primera página después de buscar
            } else {
                this.companiesWithFilter = false;
                this.getTotalPages(this.companies);
                this.getPageData(1); // Muestra la primera página al limpiar la búsqueda
            }
        },
        getTotalPages(data) {
            this.totalPages = Math.ceil(data.length / this.companiesPerPage)
        },
        getPageData(page) {
            this.paginatedData = [];
            let start = (page * this.companiesPerPage) - this.companiesPerPage;
            let end = (page * this.companiesPerPage);

            // Utiliza las empresas filtradas si hay una búsqueda activa, de lo contrario usa todas las empresas
            const data = this.companiesWithFilter ? this.filteredCompanies : this.companies;

            this.paginatedData = data.slice(start, end);
            this.currentPage = page;
        },
        getPreviousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
            this.getPageData(this.currentPage);
        },

        getNextPage() {
            const data = this.companiesWithFilter ? this.filteredCompanies : this.companies;
            if (this.currentPage < Math.ceil(data.length / this.companiesPerPage)) {
                this.currentPage++;
            }
            this.getPageData(this.currentPage);
        },
        getCompany(company){
            this.selected_company = this.companies.find(c => c.id === company)
            this.add_company = false
            this.edit_company = false
            this.load_collaborators = false

            axios.get(`/companies/${company}`).then(
                (res) => {
                    this.companyData = res.data
                    // localStorage.setItem('origin', 'deleted');

                    // url = `/collaborators`
                    // window.location.href = url

                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        addCompany(){
            // this.$router.push({ name: 'collaborators.create' })
            this.selected_company = null
            this.add_company = true
            this.edit_company = false
            this.load_collaborators = false

            axios.get(`/companies/create`).then(
                (res) => {
                    this.selectsDataCreate = res.data
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        editCompany(company){
            this.selected_company = this.companies.find(c => c.id === company)
            this.add_company = false
            this.edit_company = true
            this.load_collaborators = false

            axios.get(`/companies/${company}/edit`).then(
                (res) => {
                    this.companyDataEdit = res.data
                    this.errors = null
                }).catch(
                (error) => {
                    if(error && error.response && error.response.data && error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
                })
        },
        loadCollaborators(company){
            this.selected_company = this.companies.find(c => c.id === company)
            this.add_company = false
            this.edit_company = false
            this.load_collaborators = true
        },
        deleteCompany(id){
            // console.log('Eliminar empresa: ' + id);

            let url = ''
            axios.delete(`/companies/${id}/destroy`).then(
                (res) => {
                    localStorage.setItem('origin', 'deleted');

                    // this.getMessage(res.data.message)

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
}
</script>

<style scoped>
    @import './../../assets/css/custom.css';
    /* .message-success {
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

    .card-profile {
        height: 230px;
        background: #fff;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: row;
        border-radius: 5px;
        position: relative;
    }

    .card-profile .name-profile {
        margin: 0;
        padding: 0 1rem;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .card-profile .surname-profile {
        margin: 0;
        padding: 0 1rem;
        font-weight: bold;
        color: #8e8e8e;
    }

    .card-profile .email-profile {
        margin: 0;
        padding: 0 1rem;
    }

    .card-profile .cellphone-profile {
        margin: 0;
        padding: 0 1rem;
    }

    .card-profile .title {
        padding: 1rem;
        text-align: right;
        color: #12b338;
        font-weight: bold;
        font-size: 12px;
    }

    .card-profile .desc {
        padding: 0.5rem 1rem;
        font-size: 12px;
    }

    .card-profile .actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        padding: 0.5rem 1rem;
    }

    .img-avatar>img {
        width: 80px;
        height: 80px;
        position: absolute;
        border-radius: 50%;
        border: 6px solid white;
        background-image: linear-gradient(-60deg, #16a085 0%, #f4d03f 100%);
        top: 15px;
        left: 15px;
        transition:.5s;
        &:hover{
            transform: rotate(22deg)
        }
    }

    .card-profile-text {
        width: 100%;
        display: flex;
    }

    .title-total {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .portada {
        flex-shrink: 0;
        width: 110px;
        height: 100%;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        background-image: url("/images/card-image-bk.png");
        background-position: bottom center;
        background-size: cover;
    }

    .actions>button {
        text-align: center;
        border: none;
        background: none;
        font-size: 24px;
        color: #12b338;
        cursor: pointer;
        transition:.5s;
        &:hover{
            color: #0d7726  ;
            transform: rotate(22deg)
        }
    } */
</style>
