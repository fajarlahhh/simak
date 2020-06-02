<style>
    .v-top{
        vertical-align: text-top;
    }
</style>
<table style="vertical-align: text-top;">
    <tr>
        <td class="v-top">&nbsp;</td>
        <td class="v-top" style="width: 300px">Mataram, {{ \Carbon\Carbon::parse($data->edaran_tanggal)->isoFormat('LL') }}</td>
    </tr>
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
        <td class="v-top">
            Kepada <br>
            Yth. {!! $data->edaran_kepada !!}
        </td>
    </tr>
</table>
<br>
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
