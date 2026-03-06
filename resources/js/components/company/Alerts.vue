<template>
    <div>
        <div class="row">
            <div class="col-sm-12 col-xxl-6">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Próximos Cumpleaños
                    </div>
                    <div class="card-body">
                        <table style="width: 100%;" id="dt_birthdays" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Nº Documento</th>
                                    <th>Fecha</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in next_birthdays"
                                    :class="item.week == 'current' ? 'bk-green' : (item.week == 'next' ? 'bk-yellow' : '')">
                                    <td>{{ item.name }}</td>
                                    <td>{{ (item.document).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") }}</td>
                                    <td>{{ formatBirthDate(item) }}</td>
                                    <td style="text-align: right;">
                                        <!-- <a class="btn btn-sm btn-primary mx-1 my-1" @click="editRelativeData(item, index)" style="width: 80px;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /> Editar</a>
                                        <a class="btn btn-sm btn-danger mx-1 my-1" @click="deleteRelativeData(item, index)" style="width: 80px;"><font-awesome-icon :icon="['fas', 'trash-can']" /> Eliminar</a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xxl-6">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Vencimiento de Contratos
                    </div>
                    <div class="card-body">
                        <table style="width: 100%;" id="dt_contracts" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Nº Documento</th>
                                    <th>Fecha</th>
                                    <th style="text-align: right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in expiring_contracts"
                                    :class="item.week == 'current' ? 'bk-green' : item.week == 'next' ? 'bk-yellow' : item.week == 'previous' ? 'bk-red' : ''">
                                    <td>{{ item.name }}</td>
                                    <td>{{ (item.document).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") }}</td>
                                    <td>{{ formatContractDate(item) }}</td>
                                    <td style="text-align: right;">
                                        <!-- <a class="btn btn-sm btn-primary mx-1 my-1" @click="editRelativeData(item, index)" style="width: 80px;"><font-awesome-icon :icon="['fas', 'pen-to-square']" /> Editar</a>
                                        <a class="btn btn-sm btn-danger mx-1 my-1" @click="deleteRelativeData(item, index)" style="width: 80px;"><font-awesome-icon :icon="['fas', 'trash-can']" /> Eliminar</a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { birthdaysDatatable, contractsDatatable } from '../../assets/js/tables.js';

export default {
    props: {
        company_id: { default: null, },
    },
    data() {
        return {
            next_birthdays: [],
            expiring_contracts: [],
        }
    },
    mounted () {
        this.getNextBirthdays(this.company_id);
        this.getExpiringContracts(this.company_id);
    },
    methods: {
        getGenderData(company_id) {
            axios.get(`/gender-data/${company_id}`)
            .then(response => {
                this.gender_data = response.data.gender_data;
                this.chartGenderData = {
                    // labels: this.gender_data.labels,
                    datasets: [
                        {
                            backgroundColor: this.getRandomColorsFromArray(this.gender_data.labels.length),
                            data: this.gender_data.values
                        }
                    ],
                };
                this.chartGenderOptions = {
                    responsive: true,
                    maintainAspectRatio: true
                };
            })
            .catch(e => {
                console.error('Error:', e);
            })
        },
        getNextBirthdays(company_id) {
            axios.get(`/get-next-birthdays/${company_id}`)
            .then(response => {
                this.next_birthdays = response.data.next_birthdays;
                // this.callDatatableBirthdays();
                birthdaysDatatable();
            })
            .catch(error => {
                console.error('Error:', e);
            });
        },
        getExpiringContracts(company_id) {
            axios.get(`/get-expiring-contracts/${company_id}`)
            .then(response => {
                this.expiring_contracts = response.data.expiring_contracts;
                // this.callDatatableContracts();
                contractsDatatable();
            })
            .catch(error => {
                console.error('Error:', e);
            });
        },
        convertMonth(month) {
            switch (month) {
                case 1:
                    return 'Enero';
                case 2:
                    return 'Febrero';
                case 3:
                    return 'Marzo';
                case 4:
                    return 'Abril';
                case 5:
                    return 'Mayo';
                case 6:
                    return 'Junio';
                case 7:
                    return 'Julio';
                case 8:
                    return 'Agosto';
                case 9:
                    return 'Septiembre';
                case 10:
                    return 'Octubre';
                case 11:
                    return 'Noviembre';
                case 12:
                    return 'Diciembre';
                default:
                    return '';
            }
        },
        formatDate(date){
            const newDate = new Date(date)
            return `${newDate.getDate()}/${newDate.getMonth()+1 < 10 ?`0${newDate.getMonth()+1}`: newDate.getMonth()+1 }/${newDate.getFullYear()}`
        },
        formatBirthDate(item){
            return `${item.day} de ${this.convertMonth(item.month)}`;
        },
        formatContractDate(item){
            return `${item.day} de ${this.convertMonth(item.month)} de ${item.year}`;
        },
    }
}
</script>

<style scoped>
.bk-green {
    background-color: #d4edda;
}

.bk-yellow {
    background-color: #fff3cd;
}

.bk-red {
    background-color: #f8d7da;
}
</style>
