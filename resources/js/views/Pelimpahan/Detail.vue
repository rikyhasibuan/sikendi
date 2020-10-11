<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <loading :opacity="100" :active.sync="isLoading" :can-cancel="false" :is-full-page="false"></loading>
                    <transition name="fade">
                        <table class="table table-hover table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td style="width:15%;"><b>Nomor Nota Dinas</b></td>
                                    <td style="width:85%;">{{ pelimpahan.nota_dinas }}</td>
                                </tr>
                                <tr>
                                    <td style="width:15%;"><b>Tanggal Nota Dinas</b></td>
                                    <td style="width:85%;">{{ pelimpahan.tgl_nota_dinas | moment }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </transition>
                    <div style="margin-top:25px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a v-if="(pelimpahan.status == 0) && (access.write === 1)" :href="route + '/nominal/create?pelimpahan=' + pelimpahan.id" class="btn btn-success mb-2 mr-2"><i class="fa fa-plus"></i> Tambah Data</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <v-alert :alert="alert"></v-alert>
                            <transition name="fade">
                                <div class="table-responsive" v-if="showTable === true">
                                    <table class="table table-hover table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width:15%;text-align:center;vertical-align:middle;" rowspan="2">BPP</th>
                                                <th style="width:10%;text-align:center;vertical-align:middle;" colspan="5">Nilai Pelimpahan Uang</th>
                                                <th style="width:12%;text-align:center;vertical-align:middle;" rowspan="2">Action</th>
                                            </tr>
                                            <tr>
                                                <th style="width:10%;text-align:center;vertical-align:middle;">UP</th>
                                                <th style="width:10%;text-align:center;vertical-align:middle;">GU</th>
                                                <th style="width:10%;text-align:center;vertical-align:middle;">TU</th>
                                                <th style="width:10%;text-align:center;vertical-align:middle;">LS</th>
                                                <th style="width:10%;text-align:center;vertical-align:middle;">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" style="text-align: right;vertical-align: middle;">
                                                    <b>Saldo Bank Bendahara Pengeluaran sebelum pelimpahan uang</b>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <b>Rp.{{ pelimpahan.jumlah_sp2d | rupiah }}</b>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;"></td>
                                            </tr>
                                            <tr v-for="v in pelimpahandetail" :key="v.id">
                                                <td style="vertical-align: middle;">
                                                    {{ v.pegawai.nama }}
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <span v-if="v.jenis_pelimpahan === 'UP'">
                                                        Rp.{{ v.jumlah_pelimpahan | rupiah }}
                                                    </span>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <span v-if="v.jenis_pelimpahan === 'GU'">
                                                        Rp.{{ v.jumlah_pelimpahan | rupiah }}
                                                    </span>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <span v-if="v.jenis_pelimpahan === 'TU'">
                                                        Rp.{{ v.jumlah_pelimpahan | rupiah }}
                                                    </span>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <span v-if="v.jenis_pelimpahan === 'LS'">
                                                        Rp.{{ v.jumlah_pelimpahan | rupiah }}
                                                    </span>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <b>Rp. {{ v.jumlah_pelimpahan | rupiah }}</b>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;">
                                                    <div>
                                                        <a v-if="(pelimpahan.status == 0) && (access.update === 1)" :href="route + '/nominal/edit?pelimpahan='+ v.pelimpahan_id +'&id=' + v.id" class="btn btn-sm btn-warning mr-sm-1">
                                                            <i class="fa fa-wrench"></i> Ubah
                                                        </a>
                                                        <button v-else class="btn btn-sm btn-warning disabled mr-sm-1"><i class="fa fa-wrench"></i> Ubah</button>
                                                        <a v-if="(pelimpahan.status == 0) && (access.delete === 1)" href="#" @click="toggleModal(v.id)"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash-o"></i> Hapus
                                                        </a>
                                                        <button v-else class="btn btn-sm btn-danger disabled"><i class="fa fa-trash-o"></i> Hapus</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="text-align: right;vertical-align: middle;">
                                                    <b>Saldo Bank Bendahara Pengeluaran setelah pelimpahan uang</b>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <b>Rp.{{ sisa_sp2d | rupiah }}</b>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;"></td>
                                            </tr>
                                            <tr v-if="sisa > 0">
                                                <td colspan="5" style="text-align: right;vertical-align: middle;">
                                                    <b>Dana Yang Dikembalikan ke Saldo Bank Bendahara Pengeluaran</b>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <b>Rp.{{ sisa | rupiah }}</b>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </transition>
                            <transition name="fade">
                                <v-delete :element="'pelimpahandetail_delete_modal'" :id="id" @delete="deleteData"></v-delete>
                            </transition>
                        </div>
                    </div>
                    <a :href="route" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
                    &nbsp;&nbsp;
                    <a  v-if="(pelimpahan.status == 0)" href="#" class="btn btn-warning" @click="sendPelimpahan()"><i class="fa fa-envelope"></i> Kirim Data</a>
                    <a  v-if="(pelimpahan.status == 1)" href="#" class="btn btn-success" @click="cetakPelimpahan(pelimpahan.id)"><i class="fa fa-print"></i> Cetak Data</a>
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
                id:'',
                isLoading: false,
                alert: {
                    error:false,
                    empty:false,
                    delete:false
                },
                saldo: 0,
                showForm: false,
                showTable: false,
                sisa_sp2d: 0
            }
        },
        props: [
            'sisa',
            'pelimpahan',
            'pelimpahandetail',
            'sp2d',
            'lock',
            'route',
            'print_action',
            'access',
            'api',
            'send_api',
            'print_api'
        ],
        methods: {
            sendPelimpahan() {
                if (this.sisa_sp2d >= 0) {
                    service.postData(this.send_api)
                    .then(response => {
                        if(response.status === 'ok') {
                            alert('Pengiriman Data Pada BPP Berhasil!');
                            location.reload();
                        } else {
                            alert('error');
                        }
                    }).catch(error => {
                        this.alert.delete = false;
                        this.alert.error = true;
                        $('#pelimpahandetail_delete_modal').modal('hide');
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        console.log(error);
                    });
                } else {
                    alert('Jumlah Pelimpahan Uang Melebihi Saldo Bank Bendahara Pengeluaran');
                }
            },
            cetakPelimpahan(id) {
                let newWindow = window.open();
                newWindow.location = this.print_api;
            },
            deleteData(id) {
                service.deleteData(this.api + '&id=' + id)
                .then(response => {
                    if(response.status === 'ok') {
                        this.alert.delete = true;
                        $('#pelimpahandetail_delete_modal').modal('hide');
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        setTimeout(function() {
                            this.alert.delete=false;
                            location.reload();
                        }, 1000);
                    }
                }).catch(error => {
                    this.alert.delete = false;
                    this.alert.error = true;
                    $('#pelimpahandetail_delete_modal').modal('hide');
                    window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                    console.log(error);
                });
            },
            toggleModal(id) {
                $("#pelimpahandetail_delete_modal").modal('show');
                this.id = id;
            },
        },
        computed: {
            total(v) {
                return this.saldo - v;
            }
        },
        created() {
            this.isLoading = true;
            if (this.pelimpahandetail.length < 1) {
                this.alert.empty = true;
                this.showTable = false;
            } else {
                this.showTable = true;
                let jml = this.pelimpahan.jumlah_sp2d;
                for (var i = 0; i < this.pelimpahandetail.length; i++) {
                    jml -= this.pelimpahandetail[i]['jumlah_pelimpahan']
                }
                this.sisa_sp2d = jml
            }
            this.saldo = this.sp2d
        },
        mounted() {
            this.isLoading = false;
        }
    };
</script>
