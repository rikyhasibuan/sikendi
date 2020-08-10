<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <v-alert :alert=alert></v-alert>
                    <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false">
                    </loading>
                    <form autocomplete="off" method="POST" v-on:submit.prevent="onSubmit">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Bendahara *</label>
                                <select v-model="pelimpahan.bendahara" class="form-control" :class="{ 'is-invalid': pelimpahan.bendahara }">
                                    <option value="">Pilih Bendahara</option>
                                    <option v-for="v in this.bendahara_data" :value="v.nip" :key="v.nip">{{ v.nama }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sisa Anggaran Kegiatan BPP keseluruhan *</label>
                                <input 
                                  type="text" 
                                  readonly="true"
                                  class="form-control"
                                  placeholder="Sisa Anggaran Kegiatan BPP keseluruhan"
                                  v-model="pelimpahan.sisa_anggaran"
                                  :class="{ 'is-invalid': validasi.sisa_anggaran }"
                                >
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Jumlah Pelimpahan Uang *</label>
                                <input 
                                  type="text" 
                                  class="form-control" 
                                  placeholder="Jumlah Pelimpahan Uang" 
                                  v-model="pelimpahan.jumlah_pelimpahan"
                                  :class="{ 'is-invalid': validasi.jumlah_pelimpahan }"
                                >
                            </div>
                            <div class="form-group col-md-6">
                                <label>Kategori Pelimpahan *</label>
                                <select v-model="pelimpahan.jenis_pelimpahan" class="form-control" :class="{ 'is-invalid': pelimpahan.bendahara }">
                                    <option value="">Pilih Kategori Pelimpahan</option>
                                    <option value="UP">UP</option>
                                    <option value="GU">GU</option>
                                    <option value="TU">TU</option>
                                    <option value="LS">LS</option>
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
            pelimpahan: {
                'bendahara': '',
                'sisa_anggaran': '',
                'jenis_pelimpahan': '',
                'jumlah_pelimpahan': ''
            },
            validasi: {
                'bendahara': '',
                'sisa_anggaran': '',
                'jenis_pelimpahan': '',
                'jumlah_pelimpahan': ''
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
        'bendahara_data',
        'api',
        'route'
    ],
    methods: {
        clearAlert() {
            this.alert.error = false;
            this.alert.save = false;
            this.alert.duplicatedate = false;
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
                this.alert.duplicatedate = true;
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
