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
                            </tbody>
                        </table>
                    </transition>
                    <div style="margin-top:25px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <a v-if="(access.write === 1)" :href="route +'/create?sp2t='+ sp2t.id" class="btn btn-success mb-2 mr-2">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </a>
                                <!-- <span v-if="pelim.length !== 0 && access.approval === 1 && dinasbop.status === 0">
                                    <a v-if="(approval_type === 'kassubag' || approval_type === 'administrator') && (approval_tab.kassubag.approval === 0)" class="btn btn-warning mb-2 mr-2" href="#" @click="toggleRevisiModal('kassubag')">
                                        <i class="fa fa-edit"></i> Form Revisi Kassubag
                                    </a>
                                    <a v-if="(approval_type === 'kassubag' || approval_type === 'administrator') && (approval_tab.kassubag.approval === 0)" class="btn btn-success mb-2 mr-2" href="#" @click="toggleApprovalModal('kassubag')">
                                        <i class="fa fa-check"></i> Approval Kassubag
                                    </a>
                                    <a v-if="(approval_type === 'sekretaris' || approval_type === 'administrator') && (approval_tab.sekretaris.approval === 0)" class="btn btn-warning mb-2 mr-2" href="#" @click="toggleRevisiModal('sekretaris')">
                                        <i class="fa fa-edit"></i> Form Revisi Sekretaris
                                    </a>
                                    <a v-if="(approval_type === 'sekretaris' || approval_type === 'administrator') && (approval_tab.sekretaris.approval === 0)" class="btn btn-success mb-2 mr-2" href="#" @click="toggleApprovalModal('sekretaris')">
                                        <i class="fa fa-check"></i> Approval Sekretaris
                                    </a>
                                    <a v-if="(approval_type === 'inspektur' || approval_type === 'administrator') && (approval_tab.inspektur.approval === 0)" class="btn btn-warning mb-2 mr-2" href="#" @click="toggleRevisiModal('inspektur')">
                                        <i class="fa fa-edit"></i> Form Revisi Inspektur
                                    </a>
                                    <a v-if="(approval_type === 'inspektur' || approval_type === 'administrator') && (approval_tab.inspektur.approval === 0)" class="btn btn-success mb-2 mr-2" href="#" @click="toggleApprovalModal('inspektur')">
                                        <i class="fa fa-check"></i> Approval Inspektur
                                    </a>
                                </span> -->
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
                                                <th style="width:3%;text-align:center;" rowspan="2">Kode Rekening</th>
                                                <th style="width:20%;text-align:center;" rowspan="2">Program / Kegiatan / Belanja / Nama Penerima</th>
                                                <th style="width:10%;text-align:center;" colspan="3">Nominal</th>
                                                <th style="width:8%;text-align:center;" rowspan="2">Action</th>
                                            </tr>
                                            <tr>
                                                <th style="width:3%;text-align:center;">Jumlah Bruto</th>
                                                <th style="width:10%;text-align:center;">Potongan Pajak</th>
                                                <th style="width:3%;text-align:center;">Jumlah Transfer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<!--                                        <tr>
                                                <td colspan="5" style="text-align: right;vertical-align: middle;">
                                                    <b>Saldo Bank Bendahara Pengeluaran sebelum pelimpahan uang</b>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <b>Rp.{{ pelimpahan.jumlah_sp2d | rupiah }}</b>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;"></td>
                                            </tr> -->
                                            <tr v-for="v in sp2tdetail" :key="v.id">
                                                <td style="vertical-align: middle;">
                                                    {{ v.program.kode_program }}<br>
                                                    {{ v.kegiatan.kode_kegiatan }}<br>
                                                    {{ v.belanja.kode_belanja }}<br>
                                                    {{ v.nomor_penerima_sp2t }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ v.program.nama_program }}<br>
                                                    {{ v.kegiatan.nama_kegiatan }}<br>
                                                    {{ v.belanja.nama_belanja }}<br>
                                                    {{ v.nama_penerima_sp2t }}
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <br><br><br>
                                                    Rp.{{ v.nominalbruto | rupiah }}
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <br><br><br>
                                                    <span v-if="v.ppn !== 0">PPN Rp.{{ v.ppn | rupiah }}</span>
                                                    <span v-else-if="v.pph22 !== 0">PPh Pasal 22 Rp.{{ v.pph22 | rupiah }}</span>
                                                    <span v-else-if="v.pph4 !== 0">PPh Final Pasal 4 ayat 2 Rp.{{ v.pph4 | rupiah }}</span>
                                                    <span v-else-if="v.pph21 !== 0">PPh Pasal 21 Rp.{{ v.pph21 | rupiah }}</span>
                                                    <span v-else-if="v.pph23 !== 0">PPh Pasal 23 Rp.{{ v.pph23 | rupiah }}</span>
                                                    <span v-else></span>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <br><br><br>
                                                    Rp.{{ v.nominal_transfer | rupiah }}
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;">
                                                    <div>
                                                        <a v-if="(access.update === 1)" :href="route + '/edit?sp2t='+ v.sp2t_id +'&id=' + v.id" class="btn btn-sm btn-warning mr-sm-1">
                                                            <i class="fa fa-wrench"></i> Ubah
                                                        </a>
                                                        <button v-else class="btn btn-sm btn-warning disabled mr-sm-1"><i class="fa fa-wrench"></i> Ubah</button>
                                                        <a v-if="(access.delete === 1)" href="#" @click="toggleModal(v.id)"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash-o"></i> Hapus
                                                        </a>
                                                        <button v-else class="btn btn-sm btn-danger disabled"><i class="fa fa-trash-o"></i> Hapus</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align: right;vertical-align: middle;">
                                                    <b>Jumlah Pencairan Transfer</b>
                                                </td>
                                                <td style="text-align:right;vertical-align: middle;">
                                                    <b>Rp.{{ sp2t.jumlah_transfer | rupiah }}</b>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </transition>
                            <transition name="fade">
                                <v-delete :element="'sp2tdetail_delete_modal'" :id="id" @delete="deleteData"></v-delete>
                            </transition>
                        </div>
                        <!-- <div class="col-md-12 col-xs-12" v-if="pelimpahandetail.length !== 0">
                            <hr>
                            <transition name="fade"><v-revision-log :element="element" :revision="approval_tab"></v-revision-log></transition>
                            <transition name="fade"><v-revision :role="approval_role" :element="'driver_revision_modal'" @revision="createRevision"></v-revision></transition>
                            <transition name="fade"><v-approval :role="approval_role" :element="'driver_approval_modal'" @approve="createApproval"></v-approval></transition>
                        </div> -->
                    </div>
                    <a :href="route" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                showTable: false
            }
        },
        props: [
            'sp2t',
            'sp2tdetail',
            'lock',
            'route',
            'print_action',
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
            }
            this.saldo = this.sp2d
        },
        mounted() {
            this.isLoading = false;
        }
    };
</script>
