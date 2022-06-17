<?php  
  $filename = "Laporan_PemasukanDokumen.xls";
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");
?>
<html>
<head>

    <title>Pemasukan Dokumen</title>
</head>
<body class="idr" onload="window.print()">
<div style="margin-left: 0%; margin-right: 0%;">
<h5>LAPORAN PERTANGGUNG JAWABAN PEMASUKAN DOKUMEN<br>
{{-- Nama Companny --}}
{{ $comp_name }}<br>
<?php
    if ($datefrForm == NULL AND $datetoForm == NULL) {
    }else{
?>
PERIODE {{ $datefrForm }} S.D {{ $datetoForm }}</h5>
<?php } ?>

<center>
<table id="mytable" border="1px" cellspacing="0">
       <tr>
        <td align="center" rowspan="2">No</td>
        <td align="center" rowspan="2">Jenis Dokumen</td>
        <td align="center" colspan="2">Dokumen Pabean</td>
        <td align="center" colspan="2">Bukti Penerimaan Barang</td>
        <td align="center" rowspan="2">Supplier</td>
        <td align="center" rowspan="2">Kode Barang</td>
        <td align="center" rowspan="2">Nama Barang</td>
        <td align="center" rowspan="2">Satuan</td>
        <td align="center" rowspan="2">Jumlah</td>
        <td align="center" colspan="2" class="border-2">Nilai Barang</td>
        </tr>
        <tr>
            <td align="center" scope="col" class="border-top-0 border-bottom-0  border-2">Nomor Aju</td>
            <td align="center" scope="col" class="border-top-0 border-bottom-0  border-2">Tanggal</td>
            <td align="center" scope="col" class="border-top-0 border-bottom-0  border-2">Nomor Pendaftaran</td>
            <td align="center" scope="col" class="border-top-0 border-bottom-0  border-2">Tanggal</td>
            <td align="center" scope="col" class="border-top-0 border-bottom-0  border-2">Rupiah</td>
            <td align="center" scope="col" class="border-top-0 border-bottom-0  border-2">USD</td>
        </tr> 
        @if(count($results) > 0)
        @php $no=0; 
        $dpnomor = "" @endphp
        @foreach ($results as $key => $item)  
        <tr>
            @if( $item->dpnomor == $dpnomor)
                <td class="border-2"></td>
                <td class="border-2"></td>
                <td class="border-2"></td>
                <td class="border-2"></td>
                <td class="border-2"></td>
                <td class="border-2"></td>
                <td class="border-2"></td>
            @else
                @php $no++ @endphp 
                <td class="border-2">{{ $no }}</td>
                <td class="border-2">{{ $item->jenis_dokumen }}</td>
                @if (substr($item->dpnomor,0,1) == 0)
                    <td class="border-2">{{ "'".$item->dpnomor }}</td>
                @else
                    <td class="border-2">{{ $item->dpnomor }}</td>
                @endif 
                <td class="border-2">{{ $item->dpnomor }}</td>
                <td class="border-2">{{ date("d/m/Y", strtotime($item->dptanggal)) }}</td>
                <td class="border-2">{{ $item->bpbnomor }}</td>
                <td class="border-2">{{ date("d/m/Y", strtotime($item->bpbtanggal)) }}</td>
                <td class="border-2">{{ $item->pemasok_pengirim }}</td>
            @endif
            <td class="border-2">{{ $item->kode_barang }}</td>
            <td class="border-2">{{ $item->nama_barang }}</td>
            <td class="border-2">{{ $item->sat }}</td>
            @if ($item->jumlah == 0)
            <td class="border-2">0</td>
            @else
            <td class="border-2">{{ $item->jumlah }}</td>
            @endif
            @if ($item->nilai_barang == 0)
            <td class="border-2">0</td>
            @else
            <td class="border-2">{{ number_format($item->nilai_barang, 2, '.', ',') }}</td>
            @endif
            @if ($item->nilai_barang_usd == 0)
            <td class="border-2">0</td>
            @else
            <td class="border-2">{{ number_format($item->nilai_barang_usd, 2, '.', ',') }}</td>
            @endif
        </tr>
        @php 
        $dpnomor = $item->dpnomor
        @endphp
        @endforeach
        @elseif(count($results) == 0)
            <td colspan="13" class="border-2"> 
            <label for="noresult" class="form-label">NO DATA RESULTS...</label>
            </td>
        @endif
    </table>
</center>
<br><br>
<p>
    <footer><a href="http://www.swifect.com">~ Swifect Inventory BC ~</a></footer>
</div>
</body>
</html>

<style type="text/css" media="print">
  @page { size: landscape; margin: 0px auto; }
</style>

<style type="text/css" media="print">
  @page { margin: 0px auto; }
</style>
