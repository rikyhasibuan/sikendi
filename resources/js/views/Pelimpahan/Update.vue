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
                                <select v-model="pelimpahan.tahun_anggaran" class="form-control" readonly="readonly">
                                    <option value="">Pilih Tahun Anggaran</option>
                                    <option v-for="v in this.tahun_data" :value="v.tahun" :key="v.tahun">{{ v.tahun }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Nomor Nota Dinas *</label>
                                <input type="text" class="form-control" placeholder="Nomor Nota Dinas" v-model="pelimpahan.nota_dinas" required>
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
                                        autocomplete="off"
                                        required>
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
                alert: {
                    error: false,
                    update: false
                },
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                    locale: 'id'
                },
                isLoading: false,
            }
        },
        props: ['tahun_data','pelimpahan', 'api', 'route'],
        methods: {
            onSubmit(evt) {
                this.isLoading = false;
                service.putData(this.api, this.pelimpahan)
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
