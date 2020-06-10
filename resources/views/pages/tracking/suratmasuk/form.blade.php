<div class="row">
    <div class="col-xl-7 m-b-10 m-t-5" >
        @include('includes.component.pdf', ['file' => $data->file])
    </div>
    <div class="col-lg-5">
        <div class="note note-success">
            <table class="table table-borderless">
                <h4>Detail Surat Masuk</h4>
                <hr>
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
        @if ($data->disposisi)
        <div class="note note-primary">
            <div class="table-responsive">
                <h5>History Disposisi</h5>
                <table class="table">
                    <tr>
                        <th class="width-100">No.</th>
                        <th>Sifat</th>
                        <th>Catatan</th>
                        <th>Hasil</th>
                        <th class="width-150">Operator</th>
                        <th class="width-100">Tujuan</th>
                        <th class="width-100">Waktu</th>
                    </tr>
                    @foreach ($data->history_disposisi as $row)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            {!! $row->disposisi_sifat !!}
                        </td>
                        <td>
                            {!! $row->disposisi_catatan !!}
                        </td>
                        <td>
                            {!! $row->disposisi_hasil !!}
                        </td>
                        <td>
                            {!! $row->operator !!}
                        </td>
                        <td>
                            @foreach ($row->detail as $detail)
                            {!! $detail->jabatan->jabatan_nama !!}
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($row->created_at)->isoFormat('LLL') }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

<table class="table">

</table>
