<template>
    <div>
        <div class="row">
            <div class="col-sm-12 col-xxl-6">
                <div class="main-card mb-3 card">
                    <!-- <div class="card-header">
                        Próximos Cumpleaños
                    </div> -->
                    <div class="card-body">
                        <p>En este espacio ubicaremos los indicadores</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

// import { birthdaysDatatable, contractsDatatable } from '../../assets/js/tables.js';

export default {
    props: {
        company_id: { default: null, },
    },
    data() {
        return {
            // next_birthdays: [],
            // expiring_contracts: [],
        }
    },
    mounted () {
        //
    },
    methods: {
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
/*  */
</style>