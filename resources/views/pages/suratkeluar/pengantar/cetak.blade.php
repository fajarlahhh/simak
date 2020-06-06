@section('title', ' | Cetak Surat Pengantar'.$data->pengantar_nomor)
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
                            <td class="v-top">{!! $data->pengantar_nomor !!}</td>
                        </tr>
                        <tr>
                            <td class="v-top">Lampiran</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->pengantar_lampiran !!}</td>
                        </tr>
                        <tr>
                            <td class="v-top">Sifat</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->pengantar_sifat !!}</td>
                        </tr>
                        <tr>
                            <td class="v-top">Perihal</td>
                            <td class="v-top"> : </td>
                            <td class="v-top">{!! $data->pengantar_perihal !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="column-2">
        Mataram, {{ \Carbon\Carbon::parse($data->pengantar_tanggal)->isoFormat('LL') }}<br>
        {!! $data->pengantar_kepada !!}
    </div>
</div>
<br>
{!! $data->salam_pembuka !!}
<br>
{!! $data->pengantar_isi !!}
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
            @if ($data->fix == 1)
            {!! $data->pengantar_ttd == 1? QrCode::size(150)->generate(URL::to('/cetak/pengantar?no='.$data->pengantar_nomor)): "<img src='/".$data->pengantar_ttd."' height='150'>" !!}
            @endif
            <br>
            {!! $data->pengantar_pejabat !!}<br>
        </td>
    </tr>
</table>
<br>
<br>
@if ($data->pengantar_tembusan)
    {!! $data->pengantar_tembusan !!}
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
