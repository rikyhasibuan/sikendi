<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <v-alert :alert=alert></v-alert>
                    <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                    <form autocomplete="off" method="POST" v-on:submit.prevent="onSubmit">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Program *</label>
                                <select 
                                    v-model="sp2t.program_id" 
                                    class="form-control" 
                                    @change="onChangeProgram($event)" 
                                    :class="{ 'is-invalid': validasi.program_id }"
                                >
                                    <option value="">Pilih Program</option>
                                    <option v-for="v in this.program" :value="v.id" :key="v.id">
                                        {{ v.nama_program }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Kegiatan *</label>
                                <select 
                                    v-model="sp2t.kegiatan_id" 
                                    class="form-control" 
                                    @change="onChangeKegiatan($event)" 
                                    :class="{ 'is-invalid': validasi.kegiatan_id }"
                                >
                                    <option value="">Pilih Kegiatan</option>
                                    <option v-for="v in this.kegiatan" :value="v.id" :key="v.id">
                                        {{ v.nama_kegiatan }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Belanja *</label>
                                <select 
                                    v-model="sp2t.belanja_id" 
                                    class="form-control" 
                                    :class="{ 'is-invalid': validasi.belanja_id }"
                                >
                                    <option value="">Pilih Belanja</option>
                                    <option v-for="v in this.belanja" :value="v.id" :key="v.id">
                                        {{ v.nama_belanja }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nama Rekening Penerima *</label>
                                <vue-bootstrap-typeahead 
                                    :data="nama_sp2t"
                                    placeholder="Nama Rekening Penerima"
                                    v-model="nama_penerima_sp2t"
                                    :serializer="res => res.nama_penerima"
                                    @hit="nomor_penerima_sp2t = $event.norek_penerima"
                                    :class="{ 'is-invalid': validasi.nama_penerima_sp2t }"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nomor Rekening Penerima *</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                    placeholder="Nomor Rekening Penerima"
                                    v-model="nomor_penerima_sp2t"
                                    :class="{ 'is-invalid': validasi.nomor_penerima_sp2t }"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nominal *</label>
                                <money 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Nominal" 
                                    :change="onChangeBruto()"
                                    v-model="sp2t.nominalbruto"
                                    :class="{ 'is-invalid': validasi.nominalbruto }"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>PPN *</label>
                                <money type="text" :change="onChangePpn()" class="form-control" v-model="sp2t.ppn" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>PPh Pasal 22 *</label>
                                <money type="text" :change="onChangePph22()" class="form-control" v-model="sp2t.pph22" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>PPh Final Pasal 4 ayat 2 *</label>
                                <money type="text" :change="onChangePph4()" class="form-control" v-model="sp2t.pph4" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>PPh Pasal 21 *</label>
                                <money type="text" :change="onChangePph21()" class="form-control" v-model="sp2t.pph21" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>PPh Pasal 23 *</label>
                                <money type="text" :change="onChangePph23()" class="form-control" v-model="sp2t.pph23" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nominal Transfer *</label>
                                <money 
                                    type="text" 
                                    class="form-control readonly" 
                                    v-model="sp2t.nominal_transfer"
                                    readonly="readonly"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Data</button>
                                <a :href="route" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import service from './../../services.js';
import _ from 'underscore';

export default {
    data() {
        return {
            program:'',
            kegiatan:'',
            belanja:'',
            nama_sp2t: [],
            nama_penerima_sp2t: '',
            nomor_penerima_sp2t: '',
            /* sp2t: {
                'program_id': '',
                'kegiatan_id': '',
                'belanja_id': '',
                'nama_penerima_sp2t': '',
                'nomor_penerima_sp2t': '',
                'nominalbruto': '',
                'ppn': '',
                'pph22': '',
                'pph4': '',
                'pph21': '',
                'pph23': '',
                'nominal_transfer': ''
            }, */
            validasi: {
                'program_id': '',
                'kegiatan_id': '',
                'belanja_id': '',
                'nominalbruto': '',
                'nama_penerima_sp2t': '',
                'nomor_penerima_sp2t': '',
            },
            program: [],
            kegiatan: [],
            belanja: [],
            options: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
                locale: 'id'
            },
            alert: {
                error: false,
                update: false,
                validate: false
            },
            isLoading: false
        }
    },
    props: [
        'sp2t',
        'program_data',
        'kegiatan_data',
        'belanja_data',
        'api',
        'route'
    ],
    methods: {
        onChangeBruto() {
            this.sp2t.nominal_transfer = this.sp2t.nominalbruto;
        },
        onChangePpn() {
            // this.sp2t.nominal_transfer = this.sp2t.nominalbruto - this.sp2t.ppn;
        },
        onChangePph21() {
            // this.sp2t.nominal_transfer = this.sp2t.nominalbruto - this.sp2t.ppn - this.sp2t.pph21;
        },
        onChangePph22() {
            // this.sp2t.nominal_transfer = this.sp2t.nominalbruto - this.sp2t.ppn - this.sp2t.pph21 - this.sp2t.pph22;
        },
        onChangePph23() {
            // this.sp2t.nominal_transfer = this.sp2t.nominalbruto - this.sp2t.ppn - this.sp2t.pph21 - this.sp2t.pph23;
        },
        onChangePph4() {
            // this.sp2t.nominal_transfer = this.sp2t.nominalbruto - this.sp2t.ppn - this.sp2t.pph21 - this.sp2t.pph4;
        },
        getNamaPenerimaSp2t(query) {
            service.fetchData('../api/ajax/penerimasp2t/' + query)
            .then(response => {
                this.nama_sp2t = response;
                console.log(this.nama_sp2t);
            })
            .catch(error => {
                console.log(error);
            });
        },
        onChangeProgram(evt) {
            const program = evt.target.value;
            service.fetchData('../api/ajax/kegiatan/' + program)
            .then(response => {
                this.sp2t.kegiatan_id = '';
                this.sp2t.belanja_id = '';
                this.kegiatan = response;
                this.belanja = [];
            })
            .catch(error => {
                console.log(error);
            });
        },
        onChangeKegiatan(evt) {
            const kegiatan = evt.target.value;
            service.fetchData('../api/ajax/belanja/' + kegiatan)
            .then(response => {
                this.sp2t.belanja_id = '';
                this.belanja = response;
            })
            .catch(error => {
                console.log(error);
            });
        },
        clearAlert() {
            this.alert.error = false;
            this.alert.update = false;
            this.alert.validate = false;
        },
        onSubmit(evt) {
            evt.preventDefault();
            this.clearAlert();
            let validasi = this.validate();
            if (validasi === true) {
                this.sp2t.nama_penerima_sp2t  = this.nama_penerima_sp2t;
                this.sp2t.nomor_penerima_sp2t = this.nomor_penerima_sp2t;
                this.isLoading = true;
                service.putData(this.api, this.sp2t)
                    .then(result => {
                        this.response(result);
                    }).catch(error => {
                        setTimeout(() => { this.isLoading = false }, 1000);
                        this.alert.error = true;
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                        console.log(error);
                    });
            }
        },
        response(result) {
            setTimeout(() => { this.isLoading = false }, 1000);
            if (result.status === 'ok') {
                this.alert.update = true;
                window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                this.reset();
                setTimeout(() => this.alert.update = false, 2000);
            }
        },
        validate() {
            let condition = 0;
            let callback = false;
            
            if (this.sp2t.program_id.length === 0) {
                this.validasi.program_id = true;
                condition++;
            } else {
                this.validasi.program_id = false;
            }

            if (this.sp2t.kegiatan_id.length === 0) {
                this.validasi.kegiatan_id = true;
                condition++;
            } else {
                this.validasi.kegiatan_id = false;
            }

            if (this.sp2t.belanja_id.length === 0) {
                this.validasi.belanja_id = true;
                condition++;
            } else {
                this.validasi.belanja_id = false;
            }

            if (this.sp2t.nominalbruto.length === 0) {
                this.validasi.nominalbruto = true;
                condition++;
            } else {
                this.validasi.nominalbruto = false;
            }

            if (this.nama_penerima_sp2t.length === 0) {
                this.validasi.nama_penerima_sp2t = true;
                condition++;
            } else {
                this.validasi.nama_penerima_sp2t = false;
            }

            if (this.nomor_penerima_sp2t.length === 0) {
                this.validasi.nomor_penerima_sp2t = true;
                condition++;
            } else {
                this.validasi.nomor_penerima_sp2t = false;
            }

            if (condition > 0) {
                callback = false;
            } else {
                callback = true;
            }

            return callback;
        }
    },
    watch: {
        nama_penerima_sp2t: _.debounce(function(v) { this.getNamaPenerimaSp2t(v) }, 500)
    },
    created() {
        this.isLoading = true;
        this.program = this.program_data;
        this.belanja = this.belanja_data;
        this.kegiatan = this.kegiatan_data;
        this.getNamaPenerimaSp2t(this.sp2t.nama_penerima_sp2t)
        this.nama_penerima_sp2t  = this.sp2t.nama_penerima_sp2t;
        this.nomor_penerima_sp2t = this.sp2t.nomor_penerima_sp2t;

        service.fetchData('../api/ajax/kegiatan/' + this.sp2t.program_id)
        .then(response => {
            this.kegiatan = response;
        })
        .catch(error => {
            console.log(error);
        });

        service.fetchData('../api/ajax/belanja/' + this.sp2t.kegiatan_id)
        .then(response => {
            this.belanja = response;
        })
        .catch(error => {
            console.log(error);
        });
    },
    mounted() {
        setTimeout(() => { this.isLoading = false }, 1000);
        console.log(this.sp2t)
    }
};
</script>