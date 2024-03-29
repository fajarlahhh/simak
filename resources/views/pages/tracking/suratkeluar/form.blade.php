<div class="row">
    <div class="col-xl-7 m-b-10 m-t-5" >
        <div class=" border overflow-auto" style="height: 900px;">
            <style type="text/css">
                p, table {
                    margin-top: 0px;
                    margin-bottom: 0px;
                }
                hr {
                    border: 1px solid;
                }

                .v-top{
                    vertical-align: text-top;
                }
            </style>
            <div class="bg-white p-20" style="font-family: 'Times New Roman', Times, serif;">
                {!! $data->kop_isi !!}
                @include($halaman)
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        @if ($data->review)
        <div class="note note-primary">
            <h5>History Review</h5>
            <table class="table">
                <tr>
                    <th class="width-100">No.</th>
                    <th>Catatan</th>
                    <th class="width-150">Reviewer</th>
                    <th class="width-100">Waktu</th>
                </tr>
                @foreach ($data->review as $history)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        {!! $history->fix == 5? "<strong>Disetujui & Diterbitkan</strong>": $history->review_catatan !!}
                    </td>
                    <td>
                        {!! $history->jabatan->jabatan_nama !!}
                    </td>
                    <td>
                        {{ $history->fix? \Carbon\Carbon::parse($history->updated_at)->isoFormat('LL'): "" }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>
</div>
