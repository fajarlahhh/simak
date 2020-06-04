<div class="note note-success">
    <table class="table table-borderless">
        <tr>
            <td class="width-150 p-0">
                <label class="control-label">Nomor Surat</label>
            </td>
            <td class="width-10 p-0"> : </td>
            <td class="p-0">{{ $data->surat_masuk_nomor }}</td>
        </tr>
        <tr>
            <td class="p-0">
                <label class="control-label">Tanggal Surat</label>
            </td>
            <td class="p-0"> : </td>
            <td class="p-0">{{ \Carbon\Carbon::parse($data->surat_masuk_tanggal_surat)->isoFormat('LL') }}</td>
        </tr>
        <tr>
            <td class="p-0">
                <label class="control-label">Asal</label>
            </td>
            <td class="p-0"> : </td>
            <td class="p-0">{{ $data->surat_masuk_asal }}</td>
        </tr>
        <tr>
            <td  class="p-0">
                <label class="control-label">Perihal</label>
            </td>
            <td class="p-0"> : </td>
            <td class="p-0">{{ $data->surat_masuk_perihal }}</td>
        </tr>
        <tr>
            <td  class="p-0">
                <label class="control-label">Tanggal Masuk</label>
            </td>
            <td class="p-0"> : </td>
            <td class="p-0">{{ \Carbon\Carbon::parse($data->surat_masuk_tanggal_masuk)->isoFormat('LL') }}</td>
        </tr>
        <tr>
            <td  class="p-0">
                <label class="control-label">Rangkuman Isi Surat</label>
            </td>
            <td class="p-0"> : </td>
            <td class="p-0">{!! $data->surat_masuk_keterangan !!}</td>
        </tr>
    </table>
</div>

<table class="table">

</table>
