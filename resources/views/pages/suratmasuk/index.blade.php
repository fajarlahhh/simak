@extends('pages.main')

@section('title', ' | Surat Masuk')

@section('page')
	<li class="breadcrumb-item active">Surat Masuk</li>
@endsection

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Surat Masuk</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-4 col-lg-5 col-xl-3 col-xs-12">
                	@role('user|super-admin|supervisor')
                    <div class="form-inline">
                        <a href="{{ route('suratmasuk.tambah') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-8 col-lg-7 col-xl-9 col-xs-12">
                	<form action="{{ route('suratmasuk') }}" method="GET" id="frm-cari">
                		<div class="form-inline pull-right">
							<div class="form-group">
								<select class="form-control selectpicker cari" name="tipe" data-live-search="true" data-style="btn-warning" data-width="100%">
									<option value="0" {{ $tipe == '0'? 'selected': '' }}>Exist</option>
									<option value="1" {{ $tipe == '1'? 'selected': '' }}>Deleted</option>
									<option value="2" {{ $tipe == '2'? 'selected': '' }}>All</option>
								</select>
							</div>&nbsp;
		                	<div class="input-group">
								<input type="text" class="form-control cari" name="cari" placeholder="Cari Nomor/Perihal/Asal/Keterangan" aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
								<div class="input-group-append">
									 <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span>
								</div>
							</div>
                		</div>
					</form>
                </div>
            </div>

		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-hover">
                    <thead>
						<tr>
							<th>No.</th>
							<th>Nomor</th>
							<th>Tanggal Surat</th>
							<th>Asal</th>
							<th>Perihal</th>
							<th>Tanggal Masuk</th>
							<th class="width-110"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $row)
					    <tr>
					        <td>{{ ++$i }}</td>
					        <td>
                                <label data-toggle="tooltip" data-container="#sm{{ $i }}" id="sm{{ $i }}" title="{{ $row->operator.", ".\Carbon\Carbon::parse($row->updated_at)->isoFormat('LLL') }}">{{ $row->surat_masuk_nomor }}</label>
                            </td>
					        <td>{{ \Carbon\Carbon::parse($row->surat_masuk_tanggal_surat)->isoFormat('LL') }}</td>
					        <td>{{ $row->surat_masuk_asal }}</td>
					        <td>{{ $row->surat_masuk_perihal }}</td>
					        <td>{{ \Carbon\Carbon::parse($row->surat_masuk_tanggal_masuk)->isoFormat('LL') }}</td>
					        <td class="text-right">
	                            <a href="{{ $row->file }}" target="_blank" class='btn btn-success btn-xs m-r-3'><i class='fas fa-file-pdf'></i></a>
					        	@role('user|super-admin|supervisor')
                                @if (!$row->trashed())
                                <a href="/suratmasuk/edit/{{ $row->surat_masuk_id }}" class="btn btn-secondary btn-xs m-r-3"><i class='fas fa-edit'></i></a>
                                <a href="javascript:;" onclick="hapus('{{ $row->surat_masuk_id }}', '{{ $row->surat_masuk_nomor }}')" class="btn btn-danger btn-xs m-r-3" id='btn-del' data-toggle="tooltip" title="Hapus Data"><i class='fas fa-trash'></i></a>
                                @else
                                <a href="javascript:;" onclick="restore('{{ $row->surat_masuk_id }}', '{{ $row->surat_masuk_nomor }}')" class="btn btn-info btn-xs m-r-3" id='btn-restore' data-toggle="tooltip" title="Restore Data"><i class='fas fa-undo'></i></a>
                                @endif
                                @endrole
					        </td>
				      	</tr>
					    @endforeach
				    </tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer form-inline">
            <div class="col-md-6 col-lg-10 col-xl-10 col-xs-12">
				{{ $data->links() }}
			</div>
			<div class="col-md-6 col-lg-2 col-xl-2 col-xs-12">
				<label class="pull-right">Jumlah : {{ $data->total() }}</label>
			</div>
			This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
		</div>
	</div>
@endsection

@push('scripts')
<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    $(".cari").change(function() {
            $("#frm-cari").submit();
    });

    $(function () {
        $('#datetimepicker').datepicker({
            autoclose: true,
            minViewMode: 1,
            format: 'MM yyyy'
        }).on('changeDate', function(selected){
            $("#frm-cari").submit();
        });
    });

    function restore(id, ket) {
        Swal.fire({
            title: 'Restore Data',
            text: 'Anda akan mengembalikan surat masuk ' + ket + '',
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
                    url: "/suratmasuk/restore?no=" + id,
                    type: "POST",
                    data: {
                        "_method": 'PATCH'
                    },
                    success: function(data){
                        location.reload(true);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Restore data',
                            text: xhr.status
                        })
                    }
                });
            }
        });
    }

    function hapus(id, ket) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda akan menghapus surat masuk ' + ket + '',
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
                    url: "/suratmasuk/hapus?no=" + id,
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
