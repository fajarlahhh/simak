@section('title', ' | Cetak Edaran'.$data->edaran_nomor)
<style>
    .v-top{
        vertical-align: text-top;
    }
    .column-1 {
        float: left;
        width: 60%;
    }
    .column-2 {
        float: left;
        width: 40%;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
<div class="row">
    <div class="column-1">
        <table style="vertical-align: text-top; width:80%; padding: 0px; margin-top:12px">
            <tr>
                <td class="v-top">
                    <table>
                        <tr>
                            <td class="v-top">Nomor</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->edaran_nomor !!}</td>
                        </tr>
                        <tr>
                            <td class="v-top">Lampiran</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->edaran_lampiran !!}</td>
                        </tr>
                        <tr>
                            <td class="v-top">Sifat</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->edaran_sifat !!}</td>
                        </tr>
                        <tr>
                            <td class="v-top">Perihal</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->edaran_perihal !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="column-2">
        Mataram, {{ \Carbon\Carbon::parse($data->edaran_tanggal)->isoFormat('LL') }}<br>
        {!! $data->edaran_kepada !!}
    </div>
</div>
<br>
{!! $data->salam_pembuka !!}
<br>
{!! $data->edaran_isi !!}
<br>
{!! $data->salam_penutup !!}
<br>
<br>
<br>
<table style="vertical-align: text-top;">
    <tr>
        <td class="v-top" style="width: 400px">&nbsp;</td>
        <td class="v-top">
            {{ $data->jabatan_nama }}<br>
            {{ env('APP_DESKRIPSI') }}<br>
            {!! $data->edaran_ttd == 1? QrCode::size(150)->generate(URL::to('/cetak/edaran?no='.$data->edaran_nomor)): "<img src='/".$data->edaran_ttd."' height='150'>" !!}<br>
            {!! $data->edaran_pejabat !!}<br>
        </td>
    </tr>
</table>
<br>
<br>
@if ($data->edaran_tembusan)
    {!! $data->edaran_tembusan !!}
@endif
@if ($data->lampiran->count() > 0)
<pagebreak></pagebreak>
<br><br><br><br><br><br>
<center><h1>Lampiran</h1></center>
@foreach ($data->lampiran as $row)
<pagebreak></pagebreak>
<img src='{{ $row->file }}' class="width-full">
@endforeach
@endif
