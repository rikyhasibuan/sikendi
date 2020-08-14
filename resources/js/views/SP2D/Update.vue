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
                                <input type="text" class="form-control" placeholder="Isi Nomor SP2D" v-model="sp2d.tahun_anggaran" readonly="readonly">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nomor SP2D *</label>
                                <input type="text" class="form-control" placeholder="Isi Nomor SP2D" v-model="sp2d.nomor_sp2d">
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
                                        autocomplete="off">
                                    </date-picker>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Jumlah SP2D *</label>
                                <input type="text" class="form-control" placeholder="Jumlah SP2D" v-model="sp2d.jumlah_sp2d" readonly="readonly">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Kategori SP2D *</label>
                                <select v-model="sp2d.jenis_sp2d" class="form-control">
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
                validasi: {
                    'tahun_anggaran': '',
                    'nomor_sp2d': '',
                    'tgl_sp2d': '',
                    'jumlah_sp2d':'',
                    'jenis_sp2d':''
                },
                alert: {
                    error: false,
                    update: false
                },
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                    locale: 'id'
                },
                jenis_sp2d: {
                    'UP': 'UP',
                    'GU': 'GU',
                    'TU': 'TU',
                    'LS': 'TU'
                },
                isLoading: false,
            }
        },
        props: ['tahun_data','sp2d', 'api', 'route'],
        methods: {
            onSubmit(evt) {
                this.isLoading = false;
                service.putData(this.api, this.sp2d)
                    .then(result => {
                        this.response(result);
                    }).catch(error => {
                        this.isLoading = false;
                        this.alert.error = true;
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        console.log(error);
                    });
            },
            response(result) {
                if (result.status === 'ok') {
                    this.alert.error = false;
                    this.alert.update = true;
                    window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                    setTimeout(() => this.alert.update = false, 5000);
                }
                this.isLoading = false;
            }
        },
        created() {
            this.isLoading = true;
        },
        mounted() {
            this.isLoading = false;
        }
    };
</script>
