<template>
    <div>
        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
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
                        
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                        <transition name="fade">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">ANGGARAN KEGIATAN</th>
                                        <th style="text-align: center;">SP2D</th>
                                        <th style="text-align: center;">SISA ANGGARAN KEGIATAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:right;">{{ anggaran | rupiah }}</td>
                                        <td style="text-align:right;">{{ sp2d | rupiah }}</td>
                                        <td style="text-align:right;">{{ anggaran - sp2d | rupiah }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </transition>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                        <transition name="fade">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">PELIMPAHAN</th>
                                        <th style="text-align: center;">SISA ANGGARAN DI BANK BP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:right;">{{ pelimpahan | rupiah }}</td>
                                        <td style="text-align:right;">{{ sp2d - pelimpahan | rupiah }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </transition>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                        <transition name="fade">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">SP2T</th>
                                        <th style="text-align: center;">SALDO BANK DI REKENING BPP</th>
                                        <th style="text-align: center;">SALDO BANK DI REKENING BP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:right;">{{ sp2t | rupiah }}</td>
                                        <td style="text-align:right;">{{ pelimpahan - sp2t | rupiah }}</td>
                                        <td style="text-align:right;">{{ sp2d - pelimpahan - sp2t | rupiah }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </transition>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                        <transition name="fade">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">SP2T</th>
                                        <th style="text-align: center;">SALDO BANK DI REKENING BPP</th>
                                        <th style="text-align: center;">SALDO BANK DI REKENING BP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:right;">{{ sp2t | rupiah }}</td>
                                        <td style="text-align:right;">{{ pelimpahan - sp2t | rupiah }}</td>
                                        <td style="text-align:right;">{{ sp2d - pelimpahan - sp2t | rupiah }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </transition>
                    </div>
                </div>
            </div>
        </div> -->
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
            periode:'',
            tahun: '',
            chart_tahun: '',
            resapanAnggaran: {},
            anggaran:{},
            serapan:{},
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
                format: 'YYYY-MM',
                viewMode:'years',
                useCurrent: false,
                locale: 'id',
                minDate:'2020/01/01'
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
            let date  = this.periode.split('-');
            let tahun = date[0];
            let bulan = (typeof date[1] !== "undefined") ? parseInt(date[1]) : '';

            service.fetchData(this.api)
                .then(
                    response => {
                        this.isLoading = false;
                        this.chart_tahun = this.tahun;
                        this.anggaran = response.anggaran;
                        this.sp2d = response.sp2d;
                        this.pelimpahan = response.pelimpahan;
                        this.sp2t = response.sp2t;
                    }
                ).catch(error => {
                    this.isLoading = false;
                    console.log(error);
                });
        },
        generateAnggaranChart(response) {
            this.anggaran = {
                chart: {
                    type: 'bar',
                    height: '50%'
                },
                title: {
                    text: response.anggaran.anggaran[0].name
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Pagu'
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
                    categories: response.kegiatan,
                    title: {
                        text: null
                    }
                },
                series: response.anggaran.anggaran,
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
        },
        generateSerapanChart(response) {
            this.serapan = {
                chart: {
                    type: 'bar',
                    height: '50%'
                },
                title: {
                    text: response.serapan.serapan[0].name
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Realisasi Anggaran'
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
                    categories: response.kegiatan,
                    title: {
                        text: null
                    }
                },
                series: response.serapan.serapan,
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
