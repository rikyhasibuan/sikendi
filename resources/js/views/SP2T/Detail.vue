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
                                    <td style="width:85%;">{{ sp2t.nota_dinas }}</td>
                                </tr>
                                <tr>
                                    <td style="width:15%;"><b>Tanggal Nota Dinas</b></td>
                                    <td style="width:85%;">{{ sp2t.tgl_nota_dinas | moment }}</td>
                                </tr>
                                <tr>
                                    <td style="width:15%;"><b>Bendahara</b></td>
                                    <td style="width:85%;">{{ sp2t.pegawai.nama }}</td>
                                </tr>
                                <tr>
                                    <td style="width:15%;">
                                        <b>Status Approval</b>
                                    </td>
                                    <td>
                                        <span
                                            v-if="approval.kassubag === 1"
                                            class="badge badge-success"
                                            style="padding:0.75em 0.75em !important;"
                                        >
                                            KASSUBAG SUDAH MENYETUJUI
                                        </span>
                                        <span
                                            v-if="approval.verifikatur === 1"
                                            class="badge badge-success"
                                            style="padding:0.75em 0.75em !important;"
                                        >
                                            VERIFIKATUR SUDAH MENYETUJUI
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </transition>
                    <div style="margin-top:25px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a
                                    v-if="(access.write === 1 && sp2t.status === 0 && userlevel === 3)"
                                    :href="route +'/create?sp2t='+ sp2t.id"
                                    class="btn btn-success mb-2 mr-2">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                                <span v-if="sp2tdetail.length !== 0 && access.approval === 1 && sp2t.status === 0">
                                    <a
                                        v-if="(role === 'kassubag' || role === 'administrator') && (approval.kassubag === 0)"
                                        class="btn btn-warning mb-2 mr-2"
                                        href="#"
                                        @click="toggleRevisiModal('kassubag')"
                                    >
                                        <i class="fa fa-edit"></i> Form Revisi Kassubag
                                    </a>
                                    <a
                                        v-if="(role === 'kassubag' || role === 'administrator') && (approval.kassubag === 0)"
                                        class="btn btn-success mb-2 mr-2"
                                        href="#"
                                        @click="toggleApprovalModal('kassubag')"
                                    >
                                        <i class="fa fa-check"></i> Approval Kassubag
                                    </a>
                                    <a
                                        v-if="(role === 'verifikasi' || role === 'administrator') && (approval.verifikatur === 0)"
                                        class="btn btn-warning mb-2 mr-2"
                                        href="#"
                                        @click="toggleRevisiModal('verifikatur')"
                                    >
                                        <i class="fa fa-edit"></i> Form Revisi Verifikatur
                                    </a>
                                    <a
                                        v-if="(role === 'verifikasi' || role === 'administrator') && (approval.verifikatur === 0)"
                                        class="btn btn-success mb-2 mr-2"
                                        href="#"
                                        @click="toggleApprovalModal('verifikatur')"
                                    >
                                        <i class="fa fa-check"></i> Approval Verifikatur
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <v-alert :alert="alert"></v-alert>
                            <transition name="fade">
                                <div class="table-responsive" v-if="showTable === true">
                                    <div style="overflow-x: auto;">
                                        <div style="min-width: 25%;">
                                        <table class="table table-hover table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width:3%;text-align:center;" rowspan="2">Kode Rekening</th>
                                                    <th style="width:20%;text-align:center;" rowspan="2">Program / Kegiatan / Belanja / Nama Penerima</th>
                                                    <th style="width:10%;text-align:center;" colspan="3">Nominal</th>
                                                    <th style="width:10%;text-align:center;">Keterangan</th>
                                                    <th style="width:8%;text-align:center;" rowspan="2">Action</th>
                                                </tr>
                                                <tr>
                                                    <th style="width:3%;text-align:center;">Jumlah Bruto</th>
                                                    <th style="width:10%;text-align:center;">Potongan Pajak</th>
                                                    <th style="width:3%;text-align:center;">Jumlah Transfer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" style="text-align: right;vertical-align: middle;">
                                                        <b>Jumlah Uang Pelimpahan</b>
                                                    </td>
                                                    <td style="text-align:right;vertical-align: middle;">
                                                        <b>Rp.{{ sp2t.jumlah_pelimpahan | rupiah }}</b>
                                                    </td>
                                                    <td colspan="2" style="text-align: center;vertical-align: middle;"></td>
                                                    <td style="text-align: center;vertical-align: middle;"></td>
                                                    <td style="text-align: center;vertical-align: middle;"></td>
                                                </tr>
                                                <tr v-for="v in sp2tdetail" :key="v.id">
                                                    <td style="vertical-align: middle;">
                                                        {{ v.program.kode_program }}<br>
                                                        {{ v.kegiatan.kode_kegiatan }}<br>
                                                        {{ v.belanja.kode_belanja }}<br><br>
                                                    </td>
                                                    <td style="vertical-align: middle;">
                                                        {{ v.program.nama_program }}<br>
                                                        {{ v.kegiatan.nama_kegiatan }}<br>
                                                        {{ v.belanja.nama_belanja }}<br>
                                                        {{ v.nama_penerima_sp2t }} / {{ v.nomor_penerima_sp2t }}
                                                    </td>
                                                    <td style="text-align:right;vertical-align: middle;">
                                                        <br><br><br>
                                                        Rp.{{ v.nominalbruto | rupiah }}
                                                    </td>
                                                    <td style="text-align:right;vertical-align: middle;">
                                                        <br><br><br>
                                                        <span v-if="v.ppn !== 0">PPN Rp.{{ v.ppn | rupiah }}<br></span>
                                                        <span v-if="v.pph22 !== 0">PPh Pasal 22 Rp.{{ v.pph22 | rupiah }}<br></span>
                                                        <span v-if="v.pph4 !== 0">PPh Final Pasal 4 ayat 2 Rp.{{ v.pph4 | rupiah }}<br></span>
                                                        <span v-if="v.pph21 !== 0">PPh Pasal 21 Rp.{{ v.pph21 | rupiah }}<br></span>
                                                        <span v-if="v.pph23 !== 0">PPh Pasal 23 Rp.{{ v.pph23 | rupiah }}</span>
                                                    </td>
                                                    <td style="text-align:right;vertical-align: middle;">
                                                        <br><br><br>
                                                        Rp.{{ v.nominal_transfer | rupiah }}
                                                    </td>
                                                    <td style="vertical-align: middle;">{{ v.keterangan }}</td>
                                                    <td style="text-align: center;vertical-align: middle;">
                                                        <div>
                                                            <!-- <a
                                                                v-if="(access.update === 1)"
                                                                :href="route + '/edit?sp2t='+ v.sp2t_id +'&id=' + v.id"
                                                                class="btn btn-sm btn-warning mr-sm-1">
                                                                    <i class="fa fa-wrench"></i> Ubah
                                                            </a>
                                                            <button
                                                                v-else class="btn btn-sm btn-warning disabled mr-sm-1">
                                                                <i class="fa fa-wrench"></i> Ubah</button> -->
                                                            <a
                                                                v-if="(access.delete === 1 && sp2t.status === 0 && userlevel === 3)"
                                                                href="#"
                                                                @click="toggleModal(v.id)"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fa fa-trash-o"></i> Hapus
                                                            </a>
                                                            <button
                                                                v-else class="btn btn-sm btn-danger disabled">
                                                                <i class="fa fa-trash-o"></i> Hapus
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align: right;vertical-align: middle;">
                                                        <b>Jumlah Pencairan Transfer</b>
                                                    </td>
                                                    <td style="text-align:right;vertical-align: middle;">
                                                        <b>Rp.{{ totalbruto | rupiah }}</b>
                                                    </td>
                                                    <td style="text-align: right;vertical-align: middle;">
                                                        <b>Rp.{{ totalpajak | rupiah }}</b>
                                                    </td>
                                                    <td style="text-align: right;vertical-align: middle;">
                                                        <b>Rp.{{ totaltransfer | rupiah }}</b>
                                                    </td>
                                                    <td style="text-align: center;vertical-align: middle;"></td>
                                                    <td style="text-align: center;vertical-align: middle;"></td>
                                                </tr>
                                                <tr v-if="sp2t.sisa_pelimpahan > sp2t.jumlah_transfer && sp2t.status == 2">
                                                    <td colspan="2" style="text-align: right;vertical-align: middle;">
                                                        <b>Sisa Dana Dikembalikan</b>
                                                    </td>
                                                    <td style="text-align:right;vertical-align: middle;">
                                                        <b>Rp.{{ sp2t.sisa_pelimpahan | rupiah }}</b>
                                                    </td>
                                                    <td style="text-align: right;vertical-align: middle;">
                                                    </td>
                                                    <td style="text-align: right;vertical-align: middle;">
                                                    </td>
                                                    <td style="text-align: center;vertical-align: middle;"></td>
                                                    <td style="text-align: center;vertical-align: middle;"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                            <transition name="fade">
                                <v-delete :element="'sp2tdetail_delete_modal'" :id="id" @delete="deleteData"></v-delete>
                            </transition>
                        </div>
                        <div class="col-md-12 col-xs-12" v-if="sp2tdetail.length !== 0">
                            <hr>
                            <transition name="fade">
                                <v-revision-log :revisi="revisi" :approval="approval"></v-revision-log>
                            </transition>
                            <transition name="fade">
                                <v-revision :role="sp2t_role" :element="'revisi_modal'" @revision="createRevision"></v-revision>
                            </transition>
                            <transition name="fade">
                                <v-approval :role="sp2t_role" :element="'approval_modal'" @approve="createApproval"></v-approval>
                            </transition>
                            <transition name="fade">
                                <v-restore :id="sp2t.id" :element="'restore_modal'" @restorefund="restoreFund"></v-restore>
                            </transition>
                        </div>
                    </div>
                    <a :href="route" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
                    &nbsp;&nbsp;
                    <a
                        v-if="sp2t.status > 0"
                        href="#" class="btn btn-success"
                        @click="cetakSp2t(sp2t.id)"
                    >
                        <i class="fa fa-print"></i> Cetak Data
                    </a>
                    <a
                        v-if="sp2t.sisa_pelimpahan > sp2t.jumlah_transfer && sp2t.status == 1"
                        href="#" class="btn btn-warning"
                        @click="toggleRestoreModal(sp2t.id)"
                    >
                        <i class="fa fa-refresh"></i> Pengembalian Dana
                    </a>
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
                totalbruto:0,
                totalpajak:0,
                totaltransfer:0,
                saldo: 0,
                showForm: false,
                showTable: false,
                usernip: '',
                userlevel:'',
                sp2t_role: ''
            }
        },
        props: [
            'sp2t',
            'sp2tdetail',
            'revisi',
            'approval',
            'role',
            'lock',
            'route',
            'print_api',
            'access',
            'api'
        ],
        methods: {
            deleteData(id) {
                service.deleteData(this.api + '&id=' + id)
                .then(response => {
                    if(response.status === 'ok') {
                        this.alert.delete = true;
                        $('#sp2tdetail_delete_modal').modal('hide');
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        setTimeout(function() {
                            this.alert.delete=false;
                            location.reload();
                        }, 1000);
                    }
                }).catch(error => {
                    this.alert.delete = false;
                    this.alert.error = true;
                    $('#sp2tdetail_delete_modal').modal('hide');
                    window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                    console.log(error);
                });
            },
            toggleModal(id) {
                $("#sp2tdetail_delete_modal").modal('show');
                this.id = id;
            },
            cetakSp2t(id) {
                let newWindow = window.open();
                newWindow.location = this.print_api;
            },
            toggleRevisiModal(role) {
                this.sp2t_role = role;
                $("#revisi_modal").modal('show');
            },
            toggleApprovalModal(role) {
                this.sp2t_role = role;
                $("#approval_modal").modal('show');
            },
            toggleRestoreModal(id) {
                $("#restore_modal").modal('show');
            },
            createRevision(callback) {
                service.postData(
                    '../api/sp2t/approval?nip='+ this.usernip +'&act=revision&role='+ this.sp2t_role +'&id=' + this.sp2t.id,
                    { catatan: callback.catatan}
                )
                .then(response => {
                    if(response.status === 'ok') {
                        this.alert.delete = true;
                        $('#revisi_modal').modal('hide');
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        alert('CATATAN REVISI BERHASIL DIBUAT');
                        location.reload();
                    }
                }).catch(error => {
                    this.alert.delete = false;
                    this.alert.error = true;
                    $('#revisi_modal').modal('hide');
                    alert('TERJADI KESALAHAN PADA SISTEM!');
                    window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                    console.log(error);
                });
            },
            createApproval(role) {
                service.postData(
                    '../api/sp2t/approval?nip='+ this.usernip +'&act=approve&role='+ this.sp2t_role +'&id='+ this.sp2t.id
                )
                .then(response => {
                    if(response.status === 'ok') {
                        $('#approval_modal').modal('hide');
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        alert('PROSES APPROVAL BERHASIL');
                        location.reload();
                    }
                }).catch(error => {
                    $('#approval_modal').modal('hide');
                    window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                    alert('TERJADI KESALAHAN PADA SISTEM!');
                    console.log(error);
                });
            },
            restoreFund(id) {
                service.postData(
                    '../api/sp2t/restore?nip='+ this.usernip +'&id='+ this.sp2t.id
                )
                .then(response => {
                    if(response.status === 'ok') {
                        $('#restore_modal').modal('hide');
                        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                        alert('PROSES PENGEMBALIAN DANA BERHASIL');
                        location.reload();
                    }
                }).catch(error => {
                    $('#restore_modal').modal('hide');
                    window.scroll({ top: 0, left: 0, behavior: 'smooth' });
                    alert('TERJADI KESALAHAN PADA SISTEM!');
                    console.log(error);
                });
            },
        },
        computed: {
            total(v) {
                return this.saldo - v;
            }
        },
        created() {
            this.isLoading = true;
            if (this.sp2tdetail.length < 1) {
                this.alert.empty = true;
                this.showTable = false;
            } else {
                this.showTable = true;
                let sumbruto = 0;
                let sumpajak = 0;
                for (let i = 0; i < this.sp2tdetail.length; i++) {
                    sumbruto += this.sp2tdetail[i].nominalbruto;
                    sumpajak += (this.sp2tdetail[i].ppn + this.sp2tdetail[i].pph22 + this.sp2tdetail[i].pph4 + this.sp2tdetail[i].pph21 + this.sp2tdetail[i].pph23);
                }
                this.totalbruto = sumbruto;
                this.totalpajak = sumpajak;
                this.totaltransfer = sumbruto - sumpajak;
            }
            this.saldo = this.sp2d
            this.usernip = this.$cookies.get('nip');
            this.userlevel = this.$cookies.get('level');
        },
        mounted() {
            this.isLoading = false;
            console.log(this.sp2t);
        }
    };
</script>
