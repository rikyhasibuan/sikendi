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
                                <label>Tahun Anggaran *</label>
                                <select v-model="pelimpahan.tahun_anggaran" class="form-control" :class="{ 'is-invalid': validasi.tahun_anggaran }">
                                    <option value="">Pilih Tahun Anggaran</option>
                                    <option v-for="v in this.tahun_data" :value="v.tahun" :key="v.tahun">{{ v.tahun }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nomor Nota Dinas *</label>
                                <input type="text" class="form-control" placeholder="Nomor Nota Dinas" v-model="pelimpahan.nota_dinas" :class="{ 'is-invalid': validasi.nota_dinas }">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Tanggal Nota Dinas *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <date-picker
                                        id="tgl_nota_dinas"
                                        name="tgl_nota_dinas"
                                        v-model="pelimpahan.tgl_nota_dinas"
                                        :config="options"
                                        class="form-control"
                                        placeholder="Tanggal Nota Dinas"
                                        :class="{ 'is-invalid': validasi.tgl_nota_dinas }"
                                        autocomplete="off">
                                    </date-picker>
                                </div>
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

export default {
    data() {
        return {
            pelimpahan: {
                'tahun_anggaran': '',
                'nota_dinas': '',
                'tgl_nota_dinas': ''
            },
            validasi: {
                'tahun_anggaran': '',
                'nota_dinas': '',
                'tgl_nota_dinas': ''
            },
            options: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
                locale: 'id'
            },
            alert: {
                error: false,
                save: false,
                duplicate: false,
                validate: false
            },
            isLoading: false
        }
    },
    props: [
        'tahun_data',
        'api',
        'route'
    ],
    methods: {
        clearAlert() {
            this.alert.error = false;
            this.alert.save = false;
            this.alert.duplicate = false;
            this.alert.validate = false;
        },
        onSubmit(evt) {
            evt.preventDefault();
            this.clearAlert();
            let validasi = this.validate();
            if (validasi === true) {
                this.isLoading = true;
                service.postData(this.api, this.pelimpahan)
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
                this.alert.save = true;
                window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                this.reset();
                setTimeout(() => this.alert.save = false, 2000);
            } else if (result.status === 'duplicate') {
                this.alert.duplicate = true;
                window.scroll({ top: 0, left: 0, behavior: 'smooth' });
            }
        },
        reset() {
            this.pelimpahan.tahun_anggaran = '';
            this.pelimpahan.nota_dinas = '';
            this.pelimpahan.tgl_nota_dinas = '';
        },
        validate() {
            let condition = 0;
            let callback = false;
            
            if (this.pelimpahan.tahun_anggaran.length === 0) {
                this.validasi.tahun_anggaran = true;
                condition++;
            } else {
                this.validasi.tahun_anggaran = false;
            }

            if (this.pelimpahan.nota_dinas.length === 0) {
                this.validasi.nota_dinas = true;
                condition++;
            } else {
                this.validasi.nota_dinas = false;
            }

            if (this.pelimpahan.tgl_nota_dinas.length === 0) {
                this.validasi.tgl_nota_dinas = true;
                condition++;
            } else {
                this.validasi.tgl_nota_dinas = false;
            }

            if (condition > 0) {
                callback = false;
            } else {
                callback = true;
            }

            return callback;
        }
    },
    created() {
        this.isLoading = true;
    },
    mounted() {
        setTimeout(() => { this.isLoading = false }, 1000);
    }
};
</script>
