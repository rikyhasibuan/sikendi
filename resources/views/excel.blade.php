<?php
use App\Libraries\Common;
use App\Models\Kegiatan;
use App\Models\Belanja;
use App\Models\Anggaran;
use App\Models\Pegawai;
$common = new Common();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DATA KENDALI KEUANGAN BENDAHARA PENGELUARAN ( BP )</title>
</head>
<body>
<table>
    <tr>
        <td colspan="7"><h1><b>DATA KENDALI KEUANGAN BENDAHARA PENGELUARAN ( BP )</b></h1></td>
    </tr>
    <tr>
        <td colspan="7"><h1><b>INSPEKTORAT PROVINSI JAWA BARAT TAHUN ANGGARAN …….</b></h1></td>
    </tr>
    <tr>
        <td colspan="7"><h1><b>BULAN …... 2020</b></h1></td>
    </tr>
</table>
<table cellborder="1" width="100%">
    <thead>
        <tr>
            <th width="5%" rowspan="2"><b>No</b></th>
            <th width="15%"><b>KODE KEGIATAN</b></th>
            <th width="25%"><b>NAMA BPP</b></th>
            <th width="35%" rowspan="2"><b>JUMLAH ANGGARAN KEGIATAN</b></th>
            <th width="15%" colspan="7"><b>SP2D ( MUTASI KREDIT REKENING KORAN )</b></th>
            <th width="15%" rowspan="2"><b>SISA ANGGARAN KEGIATAN</b></th>
        </tr>
        <tr>
            <th width="15%"><b>KODERING</b></th>
            <th width="75%"><b>NAMA KEGIATAN</b></th>
            <th width="25%"><b>NOMOR SP2D</b></th>
            <th width="15%"><b>TANGGAL SP2D</b></th>
            <th width="15%"><b>UP</b></th>
            <th width="15%"><b>GU</b></th>
            <th width="15%"><b>TU</b></th>
            <th width="15%"><b>LS</b></th>
            <th width="15%"><b>JUMLAH</b></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        $sql_kegiatan = Kegiatan::searchBendahara($bendahara)->get();
        $kegiatan = Kegiatan::where('bendahara', $bendahara)->get();
        $kegiatan_list = [];
        foreach ($kegiatan as $v) {
            $kegiatan_list[] = $v->id;
        }
        $anggaran = Anggaran::whereIn('kegiatan_id', $kegiatan_list)->sum('jumlah');
        $pegawai = Pegawai::find($bendahara);
        $total_anggaran = 0;
        ?>
        <tr>
            <td colspan="3" style="vertical-align: middle;">
                {{ $pegawai->nama }} ({{ $pegawai->keterangan }})<br>
                NOMOR REKENING : {{ $pegawai->norek }} 
            </td>
            <td>{{ $common->rupiah($anggaran) }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($sql_kegiatan as $v)
            <?php $anggaran_kegiatan = Anggaran::where('kegiatan_id', $v->id)->sum('jumlah'); ?>
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $v->kode_kegiatan }}</td>
                <td>{{ $v->nama_kegiatan }}</td>
                <td>{{ $common->rupiah($anggaran_kegiatan) }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
            $sql_belanja = Belanja::where('kegiatan_id', $v->id)->get();
            ?>
            @foreach($sql_belanja as $k)
                <?php 
                    $anggaran_belanja = Anggaran::where('belanja_id', $k->id)->sum('jumlah');
                ?>
                <tr>
                    <td></td>
                    <td>{{ $k->kode_belanja }}</td>
                    <td>{{ $k->nama_belanja}}</td>
                    <td>{{ $common->rupiah($anggaran_belanja) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
            <?php
            $total_anggaran += $anggaran_kegiatan;
            // $total_serapan += $row['serapan'];
            // $total_sisa += $row['sisa'];
            ?>
            <tr>
            <td colspan="3" style="vertical-align: middle;"><b>JUMLAH</b></td>
            <td>{{ $common->rupiah($total_anggaran) }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>