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
                                <select v-model="sp2d.tahun_anggaran" class="form-control" :class="{ 'is-invalid': validasi.tahun_anggaran }">
                                    <option value="">Pilih Tahun Anggaran</option>
                                    <option v-for="v in this.tahun_data" :value="v.tahun" :key="v.tahun">{{ v.tahun }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nomor SP2D *</label>
                                <input type="text" class="form-control" placeholder="Isi Nomor SP2D" v-model="sp2d.nomor_sp2d" :class="{ 'is-invalid': validasi.nomor_sp2d }">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Tanggal SP2D *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <date-picker
                                        id="tgl_sp2d"
                                        name="tgl_sp2d"
                                        v-model="sp2d.tgl_sp2d"
                                        :config="options"
                                        class="form-control"
                                        placeholder="Tanggal SP2D"
                                        :class="{ 'is-invalid': validasi.tgl_sp2d }"
                                        autocomplete="off">
                                    </date-picker>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Jumlah SP2D *</label>
                                <input type="text" class="form-control" placeholder="Jumlah SP2D" v-model="sp2d.jumlah_sp2d" :class="{ 'is-invalid': validasi.jumlah_sp2d }">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Kategori SP2D *</label>
                                <select v-model="sp2d.jenis_sp2d" class="form-control" :class="{ 'is-invalid': validasi.jenis_sp2d }">
                                    <option value="">Pilih Kategori SPD2D</option>
                                    <option v-for="(v,k) in this.jenis_sp2d" :value="v" :key="k">{{ v }}</option>
                                </select>
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
            sp2d: {
                'tahun_anggaran': '',
                'nomor_sp2d': '',
                'tgl_sp2d': '',
                'jumlah_sp2d':'',
                'jenis_sp2d':''
            },
            validasi: {
                'tahun_anggaran': '',
                'nomor_sp2d': '',
                'tgl_sp2d': '',
                'jumlah_sp2d':'',
                'jenis_sp2d':''
            },
            jenis_sp2d: {
                'UP': 'UP',
                'GU': 'GU',
                'TU': 'TU',
                'LS': 'TU'
            },
            options: {
                format: 'YYYY-MM-DD',
                useCurrent: false,
                locale: 'id'
            },
            alert: {
                error: false,
                save: false,
                duplicatedate: false,
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
            this.alert.validate = false;
        },
        onSubmit(evt) {
            evt.preventDefault();
            this.clearAlert();
            let validasi = this.validate();
            if (validasi === true) {
                this.isLoading = true;
                service.postData(this.api, this.sp2d)
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
            this.sp2d.tahun_anggaran = '';
            this.sp2d.nomor_sp2d = '';
            this.sp2d.tgl_sp2d = '';
            this.sp2d.jumlah_sp2d = '';
            this.sp2d.jenis_sp2d = '';
        },
        validate() {
            let condition = 0;
            let callback = false;
            
            if (this.sp2d.tahun_anggaran.length === 0) {
                this.validasi.tahun_anggaran = true;
                condition++;
            } else {
                this.validasi.tahun_anggaran = false;
            }

            if (this.sp2d.nomor_sp2d.length === 0) {
                this.validasi.nomor_sp2d = true;
                condition++;
            } else {
                this.validasi.nomor_sp2d = false;
            }

            if (this.sp2d.tgl_sp2d.length === 0) {
                this.validasi.tgl_sp2d = true;
                condition++;
            } else {
                this.validasi.tgl_sp2d = false;
            }

            if (this.sp2d.jumlah_sp2d.length === 0) {
                this.validasi.jumlah_sp2d = true;
                condition++;
            } else {
                this.validasi.jumlah_sp2d = false;
            }

            if (this.sp2d.jenis_sp2d.length === 0) {
                this.validasi.jenis_sp2d = true;
                condition++;
            } else {
                this.validasi.jenis_sp2d = false;
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
