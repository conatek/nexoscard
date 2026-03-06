<template>
    <div>
        <div class="row">
            <div v-if="chartGenderData && chartGenderOptions" class="col-md-4">
                <div class="main-card mb-3 card">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Género</h5>
                        <Doughnut 
                            :data="chartGenderData" 
                            :options="chartGenderOptions" 
                        />
                    </div> -->
                    <div class="card-body">
                        <h5 class="card-title">Género</h5>
                        <div class="row d-flex">
                            <div class="col-md-6">
                                <Doughnut 
                                    :data="chartGenderData" 
                                    :options="chartGenderOptions" 
                                />
                            </div>
                            <div class="col-md-6 labels align-content-center">
                                <ul v-if="gender_data && gender_data.values && gender_data.labels && gender_data.labels.length > 0" class="custom-list">
                                    <li v-for="(label, index) in gender_data.labels" class="custom-list-item" :style="{ backgroundColor: chartGenderData.datasets[0].backgroundColor[index] }">{{ label }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="chartCivilStatusData && chartCivilStatusOptions" class="col-md-4">
                <div class="main-card mb-3 card">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Estado Civil</h5>
                        <Doughnut 
                            :data="chartCivilStatusData" 
                            :options="chartCivilStatusOptions" 
                        />
                    </div> -->
                    <div class="card-body">
                        <h5 class="card-title">Estado Civil</h5>
                        <div class="row d-flex">
                            <div class="col-md-6">
                                <Doughnut 
                                    :data="chartCivilStatusData" 
                                    :options="chartCivilStatusOptions" 
                                />
                            </div>
                            <div class="col-md-6 labels align-content-center">
                                <ul v-if="civil_status_data && civil_status_data.values && civil_status_data.labels && civil_status_data.labels.length > 0" class="custom-list">
                                    <li v-for="(label, index) in civil_status_data.labels" class="custom-list-item" :style="{ backgroundColor: chartCivilStatusData.datasets[0].backgroundColor[index] }">{{ label }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="chartAgeRangesData && chartAgeRangesOptions" class="col-md-4">
                <div class="main-card mb-3 card">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Rangos de Edad</h5>
                        <Doughnut 
                            :data="chartAgeRangesData" 
                            :options="chartAgeRangesOptions" 
                        />
                    </div> -->
                    <div class="card-body">
                        <h5 class="card-title">Rangos de Edad</h5>
                        <div class="row d-flex">
                            <div class="col-md-6">
                                <Doughnut 
                                    :data="chartAgeRangesData" 
                                    :options="chartAgeRangesOptions" 
                                />
                            </div>
                            <div class="col-md-6 labels align-content-center">
                                <ul v-if="age_ranges_data && age_ranges_data.values && age_ranges_data.labels && age_ranges_data.labels.length > 0" class="custom-list">
                                    <li v-for="(label, index) in age_ranges_data.labels" class="custom-list-item" :style="{ backgroundColor: chartAgeRangesData.datasets[0].backgroundColor[index] }">{{ label }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="chartAcademicLevelData && chartAcademicLevelOptions" class="col-md-4">
                <div class="main-card mb-3 card">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Nivel Académico</h5>
                        <Doughnut 
                            :data="chartAcademicLevelData" 
                            :options="chartAcademicLevelOptions" 
                        />
                    </div> -->
                    <div class="card-body">
                        <h5 class="card-title">Nivel Académico</h5>
                        <div class="row d-flex">
                            <div class="col-md-6">
                                <Doughnut 
                                    :data="chartAcademicLevelData" 
                                    :options="chartAcademicLevelOptions" 
                                />
                            </div>
                            <div class="col-md-6 labels align-content-center">
                                <ul v-if="academic_level_data && academic_level_data.values && academic_level_data.labels && academic_level_data.labels.length > 0" class="custom-list">
                                    <li v-for="(label, index) in academic_level_data.labels" class="custom-list-item" :style="{ backgroundColor: chartAcademicLevelData.datasets[0].backgroundColor[index] }">{{ label }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="chartSocialStrataData && chartSocialStrataOptions" class="col-md-4">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Estrato Social</h5>
                        <div class="row d-flex">
                            <div class="col-md-6">
                                <Doughnut 
                                    :data="chartSocialStrataData" 
                                    :options="chartSocialStrataOptions" 
                                />
                            </div>
                            <div class="col-md-6 labels align-content-center">
                                <ul v-if="social_strata_data && social_strata_data.values && social_strata_data.labels && social_strata_data.labels.length > 0" class="custom-list">
                                    <li v-for="(label, index) in social_strata_data.labels" class="custom-list-item" :style="{ backgroundColor: chartSocialStrataData.datasets[0].backgroundColor[index] }">{{ label }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="chartLengthServiceData && chartLengthServiceOptions" class="col-md-4">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Antiguedad</h5>
                        <div class="row d-flex">
                            <div class="col-md-6">
                                <Doughnut 
                                    :data="chartLengthServiceData" 
                                    :options="chartLengthServiceOptions" 
                                />
                            </div>
                            <div class="col-md-6 labels align-content-center">
                                <ul v-if="length_service_data && length_service_data.values && length_service_data.labels && length_service_data.labels.length > 0" class="custom-list">
                                    <li v-for="(label, index) in length_service_data.labels" class="custom-list-item" :style="{ backgroundColor: chartLengthServiceData.datasets[0].backgroundColor[index] }">{{ label }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div v-if="chartTestOneData && chartTestOneOptions" class="col-md-3">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Nivel Académico</h5>
                        <Bar 
                            :data="chartTestOneData" 
                            :options="chartTestOneOptions" 
                        />
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale)

export default {
    components: {
        Bar,
        Doughnut
    },
    props: {
        company_id: { default: null, },
    },
    data() {
        return {
            gender_data: [],
            civil_status_data: [],
            age_ranges_data: [],
            academic_level_data: [],

            
            chartGenderData: null,
            chartCivilStatusData: null,
            chartAgeRangesData: null,
            chartAcademicLevelData: null,
            chartSocialStrataData: null,
            chartLengthServiceData: null,
            
            chartTestOneData: null,
            
            chartGenderOptions: null,
            chartCivilStatusOptions: null,
            chartAgeRangesOptions: null,
            chartAcademicLevelOptions: null,
            chartSocialStrataOptions: null,
            chartLengthServiceOptions: null,
            
            chartTestOneOptions: null,
        }
    },
    mounted () {
        this.getGenderData(this.company_id);
        this.getCivilStatusData(this.company_id);
        this.getAgeRangesData(this.company_id);
        this.getAcademicLevelData(this.company_id);
        this.getSocialStrataData(this.company_id);
        this.getLengthServiceData(this.company_id);

        this.chartTestOneData = {
            labels: [ 'January', 'February', 'March'],
            datasets: [
                {
                    label: 'Data One',
                    backgroundColor: '#f87979',
                    data: [40, 20, 12]
                }
            ]
        };
        this.chartTestOneOptions = {
            responsive: true
        };
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
        getCivilStatusData(company_id) {
            axios.get(`/civil-status-data/${company_id}`)
            .then(response => {
                this.civil_status_data = response.data.civil_status_data;
                this.chartCivilStatusData = {
                    // labels: this.civil_status_data.labels,
                    datasets: [
                        {
                            backgroundColor: this.getRandomColorsFromArray(this.civil_status_data.labels.length),
                            data: this.civil_status_data.values
                        }
                    ],
                };
                this.chartCivilStatusOptions = {
                    responsive: true,
                    maintainAspectRatio: true
                };
            })
            .catch(e => {
                console.error('Error:', e);  
            })
        },
        getAgeRangesData(company_id) {
            axios.get(`/age-ranges-data/${company_id}`)
            .then(response => {
                this.age_ranges_data = response.data.age_ranges_data;
                this.chartAgeRangesData = {
                    // labels: this.age_ranges_data.labels,
                    datasets: [
                        {
                            backgroundColor: this.getRandomColorsFromArray(this.age_ranges_data.labels.length),
                            data: this.age_ranges_data.values
                        }
                    ],
                };
                this.chartAgeRangesOptions = {
                    responsive: true,
                    maintainAspectRatio: true
                };
            })
            .catch(e => {
                console.error('Error:', e);  
            })
        },
        getAcademicLevelData(company_id) {
            axios.get(`/academic-level-data/${company_id}`)
            .then(response => {
                this.academic_level_data = response.data.academic_level_data;
                this.chartAcademicLevelData = {
                    // labels: this.academic_level_data.labels,
                    datasets: [
                        {
                            backgroundColor: this.getRandomColorsFromArray(this.academic_level_data.labels.length),
                            data: this.academic_level_data.values
                        }
                    ],
                };
                this.chartAcademicLevelOptions = {
                    responsive: true,
                    maintainAspectRatio: true
                };
            })
            .catch(e => {
                console.error('Error:', e);  
            })
        },
        getSocialStrataData(company_id) {
            axios.get(`/social-strata-data/${company_id}`)
            .then(response => {
                this.social_strata_data = response.data.social_strata_data;
                this.chartSocialStrataData = {
                    // labels: this.social_strata_data.labels,
                    datasets: [
                        {
                            backgroundColor: this.getRandomColorsFromArray(this.social_strata_data.labels.length),
                            data: this.social_strata_data.values
                        }
                    ],
                };
                this.chartSocialStrataOptions = {
                    responsive: true,
                    maintainAspectRatio: true
                };
            })
            .catch(e => {
                console.error('Error:', e);  
            })
        },
        getLengthServiceData(company_id) {
            axios.get(`/length-service-data/${company_id}`)
            .then(response => {
                this.length_service_data = response.data.length_service_data;
                this.chartLengthServiceData = {
                    // labels: this.length_service_data.labels,
                    datasets: [
                        {
                            backgroundColor: this.getRandomColorsFromArray(this.length_service_data.labels.length),
                            data: this.length_service_data.values
                        }
                    ],
                };
                this.chartLengthServiceOptions = {
                    responsive: true,
                    maintainAspectRatio: true
                };
            })
            .catch(e => {
                console.error('Error:', e);  
            })
        },
        getRandomColors(quantity) {
            let colors = [];
            for (let i = 0; i < quantity; i++) {
                colors.push('#' + Math.floor(Math.random()*16777215).toString(16));
            }
            return colors;
        },
        getRandomColorsFromArray(quantity) {
            // let availableColors = ['#FFDB5C','#FFAF61','#FF70AB','#640D6B','#B51B75','#E65C19','#8DECB4','#41B06E','#430A5D','#5F374B','#8C6A5D','#891652','#59D5E0','#EBF400','#EE4266','#525CEB','#527853','#EA906C','#1A5D1A','#22A699'];
            let availableColors = ['#03AED2','#68D2E8','#FDDE55','#FEEFAD','#FFBB70','#ED9455','#BACD92','#75A47F','#94FFD8','#A3D8FF','#F8C794','#D8AE7E','#ACE1AF','#E0FBE2','#F3D0D7','#FFEFEF','#7EA1FF','#B3C8CF','#BED7DC','#6AD4DD'];
            let shuffledColors = availableColors.sort(function() { return 0.5 - Math.random() });
            let randomColors = shuffledColors.slice(0, quantity);
            return randomColors;
        }
    }
}
</script>

<style scoped>
    /* @import './../../assets/css/sociodemographic_profile.css'; */

    ul.custom-list {
        margin-top: 10px;
        padding-left: 0;
    }

    .custom-list-item {
        list-style: none;
        padding-left: 20px;
        margin-bottom: 2px;
        font-size: 12px;
    }
    
    /* .custom-list-item::before {
        content: "";
        display: inline-block;
        width: 30px;
        height: 10px;
        background-color: blue;
        margin-right: 10px;
    } */
</style>