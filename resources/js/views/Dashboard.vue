<template>
    <div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sisa Anggaran Kegiatan</span>
                                <span class="info-box-number">{{ anggaran - sp2d | rupiah }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">SP2D</span>
                                <span class="info-box-number">{{ sp2d | rupiah }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pelimpahan</span>
                                <span class="info-box-number">{{ pelimpahan | rupiah }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">SP2T</span>
                                <span class="info-box-number">{{ sp2t | rupiah }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                        <form method="POST" v-on:submit.prevent="fetchData">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <date-picker
                                            id="periode"
                                            name="periode"
                                            v-model="periode"
                                            :config="options"
                                            class="form-control"
                                            placeholder="Periode"
                                            autocomplete="off">
                                        </date-picker>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Tampikan Data</button>
                                </div>
                            </div>
                        </form>
                        <highcharts :options="sp2d_chart"></highcharts>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import service from './../services.js';
import { Chart } from 'highcharts-vue';

export default {
    data: function() {
        return {
            anggaran:'',
            sp2d:'',
            pelimpahan:'',
            sp2t: '',
            sp2d_chart: {},
            pelimpahan_chart: {},
            sp2t_chart: {},
            periode:'',
            tahun: '',
            chart_tahun: '',
            output_table:{},
            isLoading: false,
            options:null,
            attrs: [
                {
                    key: 'today',
                    highlight: true,
                    dates: new Date(),
                },
            ],
            title: {
                anggaran:'',
                serapan:''
            },
            options: {
                format: 'YYYY',
                viewMode:'years',
                useCurrent: false,
                locale: 'id'
            }
        }
    },
    props:['api'],
    components: {
        highcharts: Chart
    },
    created() {
        this.isLoading = true;
        this.fetchData();
    },
    mounted() {
        let date = new Date();
        this.tahun = date.getFullYear();
    },
    methods: {
        fetchData() {
            this.isLoading = true;
            if (this.periode != '') {
                this.tahun = this.periode
            }
            service.fetchData(this.api + '?tahun='+ this.tahun)
            .then(
                response => {
                    this.isLoading = false;
                    this.chart_tahun = this.tahun;
                    this.anggaran = response.anggaran;
                    this.sp2d = response.sp2d;
                    this.pelimpahan = response.pelimpahan;
                    this.sp2t = response.sp2t;
                    this.generateSp2dChart(response.sp2d_chart, response.pelimpahan_chart, response.sp2t_chart);
                }
            ).catch(error => {
                this.isLoading = false;
                console.log(error);
            });
        },
        generateSp2dChart(sp2d, pelimpahan, sp2t) {
            this.sp2d_chart = {
                chart: {
                    type: 'column',
                    height: '40%'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Statistik Data ' + this.tahun
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: null
                    },
                    labels: {
                        formatter: function() {
                            if (this.value >= 1E6) {
                                return (this.value / 1000000).toFixed(0) + ' Jt';
                            }
                            return this.value / 1000;
                        }
                    },
                },
                xAxis: {
                    categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    title: {
                        text: null
                    }
                },
                series: [{ name: 'Jumlah SP2D', data: sp2d }, { name: 'Jumlah Pelimpahan', data: pelimpahan }, { name: 'Jumlah SP2T', data: sp2t }],
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}:</td><td style="padding:0"><b>Rp.{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                legend: {
                    enabled: false
                }
            }
        }
    }
};
</script>
