<?php
use App\Libraries\Common;
$_common = new Common();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Dinas</title>
    <link rel="stylesheet" href="{!! asset('css/bootstrap.min.css') !!}">
    <style>
        @page  {
            size: auto;
            margin: 2.54cm 2.54cm 25cm 25cm;
        }
        body {
            margin: 0px;
        }

        * {
            font-family: 'Times New Roman', Times, serif;
            font-size: 1rem;
        }

        h4 {
            font-size: 14pt;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div style="text-align:center;"><img src="{!! asset('img/kop.png') !!}" style="width:100%;"></div>
                <table cellpadding="3" cellspacing="3">
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{!! $sp2t->nota_dinas !!}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{!! $sp2t->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top;">Perihal</td>
                        <td style="vertical-align:top;">:</td>
                        <td style="vertical-align:top;">Surat Perintah Pembayaran Transfer Melalui IBC</td>
                    </tr>
                    <tr>
                        <td width="10%" style="vertical-align:top;">Kepada Yth.</td>
                        <td width="1%"></td>
                        <td style="vertical-align:top;">Kuasa Pengguna Anggaran Inspektorat Provinsi Jawa Barat</td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="1%"></td>
                        <td style="vertical-align:top;">C.q. Sub. Bagian Keuangan dan Aset</td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="1%"></td>
                        <td style="vertical-align:top;">Di  Tempat</td>
                    </tr>
                </table>
                <br>
                <div style="margin-bottom:15px;text-align:justify;">
                    <p>Dengan ini kami mohon bantuan Bapak untuk melakukan pelimpahan uang melalui IBC (Internet Banking Corporate) :<p>
                </div>
                <div style="margin-bottom:15px;text-align:justify;">
                    <table cellspacing="3" cellpadding="3">
                        <tr>
                            <td width="25%">Dari Nomor Rekening</td>
                            <td width="1%">:</td>
                            <td>{!! $sp2t->pegawai->norek !!} {!! $sp2t->pegawai->keterangan !!}</td>
                        </tr>
                        <tr>
                            <td>Nominal</td>
                            <td width="1%"></td>
                            <td>Rp.{!! $_common->rupiah($sp2t->jumlah_transfer) !!}</td>
                        </tr>
                        <tr>
                            <td>Nama Bendahara Pengeluaran</td>
                            <td width="1%"></td>
                            <td>{!! $sp2t->pegawai->nama !!}</td>
                        </tr>
                    </table>
                    <br>
                    <p>Kepada pihak ketiga kami dengan rincian :</p>
                    <table cellspacing="3" cellpadding="3">
                        <tr>
                            <td width="5%">Program</td>
                            <td width="1%">:</td>
                            <td width="5%">{!! $detail[0]->program->kode_program !!}</td>
                            <td width="70%">{!! $detail[0]->program->nama_program !!}</td>
                        </tr>
                        <tr>
                            <td width="5%">Kegiatan</td>
                            <td width="1%">:</td>
                            <td width="5%">{!! $detail[0]->kegiatan->kode_kegiatan !!}</td>
                            <td width="70%">{!! $detail[0]->kegiatan->nama_kegiatan !!}</td>
                        </tr>
                    </table>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:8%;vertical-align: middle;text-align:center;" rowspan="2">Nama & No. Rek. Penerima</th>
                                <th style="width:5%;vertical-align: middle;text-align:center;" colspan="3">Nominal</th>
                                <th style="width:10%;vertical-align: middle;text-align:center;" rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th style="width:2%;text-align:center;">Jumlah Bruto</th>
                                <th style="width:2%;text-align:center;">Pajak</th>
                                <th style="width:3%;text-align:center;">Jumlah Transfer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalbruto = 0;
                            $totalpajak = 0;
                            ?>
                            @foreach($detail as $v)
                                <tr>
                                    <td style="text-align: center;">
                                        {!! $v->nomor_penerima_sp2t !!} / {!! $v->nama_penerima_sp2t !!}
                                    </td>
                                    <td style="text-align:right;vertical-align: middle;">
                                        Rp. {!! $_common->rupiah($v->nominalbruto) !!}
                                    </td>
                                    <td style="text-align:right;vertical-align: middle;">
                                        @if($v->ppn != 0)
                                            PPN - Rp. {!! $_common->rupiah($v->ppn) !!}<br>
                                        @endif
                                        @if($v->pph22 != 0)
                                            PPH22 - Rp. {!! $_common->rupiah($v->pph22) !!}<br>
                                        @endif
                                        @if($v->pph4 != 0)
                                            PPH4 - Rp. {!! $_common->rupiah($v->pph4) !!}<br>
                                        @endif
                                        @if($v->pph21 != 0)
                                            PPH21 - Rp. {!! $_common->rupiah($v->pph21) !!}<br>
                                        @endif
                                        @if($v->pph23 != 0)
                                            PPH23 - Rp. {!! $_common->rupiah($v->pph23) !!}<br>
                                        @endif
                                    </td>
                                    <td style="text-align:right;vertical-align: middle;">
                                        Rp. {!! $_common->rupiah($v->nominal_transfer) !!}
                                    </td>
                                    <td style="text-align: justify;vertical-align: middle;">
                                        {!! $v->keterangan !!}
                                    </td>
                                </tr>
                                <?php
                                $totalbruto += $v->nominalbruto;
                                $totalpajak += ($v->ppn + $v->pph22 + $v->pph4 + $v->pph21 + $v->pph23);
                                ?>
                            @endforeach
                            <tr>
                                <td style="text-align:center;vertical-align: middle;">
                                    <b>JUMLAH</b>
                                </td>
                                <td style="text-align:right;vertical-align: middle;">
                                    <b>Rp. {!! $_common->rupiah($totalbruto) !!}</b>
                                </td>
                                <td style="text-align:right;vertical-align: middle;">
                                    <b>Rp. {!! $_common->rupiah($totalpajak) !!}</b>
                                </td>
                                <td style="text-align:right;vertical-align: middle;">
                                    <b>Rp. {!! $sp2t->jumlah_transfer !!}</b>
                                </td>
                                <td style="text-align: center;vertical-align: middle;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p>Demikian atas bantuan dan kerjasamanya yang baik kami sampaikan terima kasih.</p>
                <div style="margin-bottom:15px;text-align:justify;">
                    {{-- <table width="100%">
                        <tr>
                            <td width="50%"></td>
                            <td width="25%">
                                <table cellpadding="2" cellspacing="2" style="width:100%;">
                                    <tr>
                                        <td width="20%" style="text-align: center;"><b>Bendahara Pengeluaran Pembantu</b></td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;"><br><br><br><br></td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">TITI MARTINI, S.TP., M.M.</td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">Pembina</td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">NIP. 197809022002122002</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table> --}}
                    <table width="100%">
                        <tr>
                            <td width="25%">
                                <table cellpadding="2" cellspacing="2" style="width:100%;">
                                    <tr>
                                        <td width="20%" style="text-align: center;"><b>Kuasa Pengguna Anggaran</b></td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;"><br><br><br><br></td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">Drs. SUBANDRIO ILHAM BASUKI</td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">Pembina Tk. I/IV/b</td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">NIP. 196507171992091001</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="25%">
                                <table cellpadding="2" cellspacing="2" style="width:100%;">
                                    <tr>
                                        <td width="20%" style="text-align: center;"><b>Bendahara Pengeluaran Pembantu</b></td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;"><br><br><br><br></td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">{!! $sp2t->pegawai->nama !!}</td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">{!! $sp2t->pegawai->pangkat !!}</td>
                                    </tr>
                                    <tr>
                                        <td width="10%" style="text-align: center;">NIP. {!! $sp2t->pegawai->nip !!}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</body>
</html>
