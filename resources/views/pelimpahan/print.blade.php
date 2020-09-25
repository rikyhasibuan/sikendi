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
            font-size: 12pt;
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
                <h4 class="text-center">NOTA DINAS</h4>
                <table cellpadding="3" cellspacing="3">
                    <tr>
                        <td width="10%">Kepada</td>
                        <td width="1%">:</td>
                        <td>Yth. Inspektur Provinsi Jawa Barat</td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="1%">:</td>
                        <td>Kepala Sub Bagian Keuangan dan Aset Inspektorat Provinsi Jawa Barat</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{!! $pelimpahan->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!}</td>
                    </tr>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{!! $pelimpahan->nota_dinas !!}</td>
                    </tr>
                    <tr>
                        <td>Sifat</td>
                        <td>:</td>
                        <td>Biasa</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top;">Perihal</td>
                        <td style="vertical-align:top;">:</td>
                        <td style="vertical-align:top;">
                            @foreach ($detail as $v)
                                <p style="text-align:justify;">Pengajuan Pelimpahan Uang Persediaan ({!! $v->jenis_pelimpahan !!}) dari BP kepada {!! $v->pegawai->keterangan !!} Melalui IBC (Internet Banking Corporate) dengan nomor rekening {!! $v->pegawai->norek !!}.</p>
                            @endforeach
                        </td>
                    </tr>
                </table>
                <hr style="border-top: 5px double #8c8b8b;">
                <div style="margin-bottom:15px;text-align:justify;">
                    <p><span style="margin-left:50px;"></span>Dipermaklumkan dengan hormat, sehubungan dengan pembiayaan pelaksanaan kegiatan APBD Tahun Anggaran 2020 pada Inspektorat
                    Provinsi Jawa Barat, bersama ini kami mengajukan Pelimpahan Uang Persediaan dari Bendahara Pengeluaran an EKA BIDA BIDANI, A.Md untuk
                    Kegiatan yang dikelola oleh
                    @foreach ($detail as $v)
                        {!! $v->pegawai->keterangan !!} an. {!! $v->pegawai->nama !!} sebesar Rp.{!! $_common->rupiah($v->jumlah_pelimpahan) !!} ({!! $_common->terbilang($v->jumlah_pelimpahan) !!} Rupiah) melalui IBC (Internet Banking Corporate)
                    @endforeach
                    untuk kebutuhan pada bulan {!! $pelimpahan->tgl_nota_dinas->formatLocalized('%B %Y'); !!}.<p>
                </div>
                <div style="margin-bottom:15px;text-align:justify;">
                    <table class="table">
                        <tr>
                            <td style="border-top: 0px;">
                                Saldo Bank BP tanggal {!! $pelimpahan->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!}
                            </td>
                            <td style="border-top: 0px; text-align:right;">Rp. {!! $_common->rupiah($pelimpahan->jumlah_sp2d) !!}</td>
                        </tr>
                        <?php $i = 0; ?>
                        @foreach ($detail as $v)
                            @if ($i == 0)
                                <tr>
                                    <td>Pelimpahan Uang Persediaan ({!! $v->jenis_pelimpahan !!}) dari BP kepada BPP Inspektorat tanggal {!! $pelimpahan->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!} melalui IBC</td>
                                    <td style="text-align:right;">Rp. {!! $_common->rupiah($v->jumlah_pelimpahan) !!}</td>
                                </tr>
                            @else
                                <tr>
                                    <td>Pelimpahan Uang Persediaan ({!! $v->jenis_pelimpahan !!}) dari BP kepada BPP Inspektorat tanggal {!! $pelimpahan->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!} melalui IBC</td>
                                    <td style="border-top: 0px; text-align:right;">Rp. {!! $_common->rupiah($v->jumlah_pelimpahan) !!}</td>
                                </tr>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                        <tr>
                            <td>Total Pelimpahan Uang dari BP ke BPP tanggal {!! $pelimpahan->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!}</td>
                            <td style="text-align:right;">Rp. {!! $_common->rupiah($pelimpahan->jumlah_pelimpahan) !!}</td>
                        </tr>
                        <tr>
                            <td>Saldo Bank BP tanggal {!! $pelimpahan->tgl_nota_dinas->formatLocalized('%d %B %Y'); !!} setelah pelimpahan IBC</td>
                            <td style="text-align:right;">Rp.{!! $_common->rupiah($pelimpahan->sisa_sp2d) !!}</td>
                        </tr>
                    </table>
                </div>
                <p><span style="margin-left:50px;"></span>Demikian atas perhatian dan perkenannya diucapkan terima kasih.</p>
                <div style="margin-bottom:15px;text-align:justify;">
                    <table width="100%">
                        <tr>
                            <td width="50%"></td>
                            <td width="25%">
                                <table cellpadding="2" cellspacing="2" style="width:100%;">
                                    <tr>
                                        <td width="20%" style="text-align: center;"><b>Kepala Sub Bagian Keuangan dan Aset</b></td>
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
                    </table>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</body>
</html>
