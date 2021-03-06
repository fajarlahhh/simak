@extends('pages.datamaster.main')

@section('title', ' | Gambar')

@push('css')
	<link href="{{ url('/public/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
	<link href="{{ url('/public/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet') }}" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Gambar</li>
@endsection

@section('header')
	<h1 class="page-header">Gambar</h1>
@endsection

@section('subcontent')
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-2 col-lg-2 col-xl-2 col-xs-12">
                @role('user|super-admin|supervisor')
                <div class="form-inline">
                    <a href="{{ route('gambar.tambah') }}" class="btn btn-primary">Tambah</a>
                </div>
                @endrole
            </div>
            <div class="col-md-10 col-lg-10 col-xl-10 col-xs-12">
                <form id="frm-cari" action="{{ route('gambar') }}" method="GET">
                    <div class="form-inline pull-right">
                        <div class="input-group">
                            <input type="text" class="form-control cari" name="cari" placeholder="Cari Gambar" aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            @foreach ($data as $index => $row)
            <div class="col-md-3">
                <strong>{{ $row->gambar_nama }}</strong><br>
                <img src="{{ url('public/'.$row->gambar_lokasi) }}" alt="" style="width: 100%">
                <div class="input-group">
                    <input id="clipboard-default{{ $i }}" type="text" class="form-control" value="{{ url('public/'.$row->gambar_lokasi) }}" readonly/>
                    <div class="input-group-append">
                        <button class="btn btn-warning clipboard" type="button" data-clipboard-target="#clipboard-default{{ $i }}">Copy</button>
                        <button onclick="hapus('{{ $row->gambar_nama }}')" type="button"  id='btn-del' class='btn btn-danger btn-xs'>Hapus</button>
                    </div>
                </div>
            </div>
            @php ++$i @endphp
            @endforeach
        </div>
    </div>
    <div class="panel-footer form-inline">
        <div class="col-md-6 col-lg-10 col-xl-10 col-xs-12">
            {{ $data->links() }}
        </div>
        <div class="col-md-6 col-lg-2 col-xl-2 col-xs-12">
            <label class="pull-right">Jumlah Data : {{ $data->total() }}</label>
        </div>
        This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
    </div>
</div>
<div class="modal fade" id="modal-detail">
	<div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pratinjau Usulan Program/Jabatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div id="modal-detail-form"></div>
        </div>
	</div>
</div>

@endsection

@push('scripts')
<script src="{{ url('/public/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ url('/public/assets/plugins/clipboard/clipboard.min.js') }}"></script>
<script>
    $(".cari").change(function() {
         $("#frm-cari").submit();
    });


	var clipboard = new ClipboardJS('.clipboard');

	clipboard.on('success', function(e) {
		$(e.trigger).tooltip({
			title: 'Copied',
			placement: 'top'
		});
		$(e.trigger).tooltip('show');
		setTimeout(function() {
			var bootstrapVersion = handleCheckBootstrapVersion();
			if (bootstrapVersion >= 3 && bootstrapVersion < 4) {
				$(e.trigger).tooltip('destroy');
			} else if (bootstrapVersion >= 4 && bootstrapVersion < 5) {
				$(e.trigger).tooltip('dispose');
			}
		}, 500);
	});

    function hapus(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda akan menghapus gambar ' + id + '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value == true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url("/gambar/hapus") }}?nama=' + id,
                    type: "POST",
                    data: {
                        "_method": 'DELETE'
                    },
                    success: function(data){
                        location.reload(true);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hapus data',
                            text: xhr.status
                        })
                    }
                });
            }
        });
    }
</script>
@endpush
