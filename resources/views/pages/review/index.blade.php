@extends('pages.main')

@section('title', ' | Review')

@section('page')
	<li class="breadcrumb-item active">Review</li>
@endsection

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Review</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12">
                	<form action="/review" method="GET" id="frm-cari">
                		<div class="form-inline pull-right">
		                	<div class="input-group">
								<input type="text" class="form-control cari" name="cari" placeholder="Cari Nomor/Perihal/Jenis" aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
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
                            <th>Perihal</th>
							<th>Jenis Surat</th>
							<th class="width-110"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $row)
					    <tr>
					        <td>{{ ++$i }}</td>
					        <td>
                                <label data-toggle="tooltip" title="{{ $row->operator.", ".\Carbon\Carbon::parse($row->created_at)->isoFormat('LLL') }}">{{ $row->review_nomor_surat }}</label>
                            </td>
					        <td>
                                @switch($row->review_jenis_surat)
                                    @case('Edaran')
                                        {{ \Carbon\Carbon::parse($row->edaran_tanggal)->isoFormat('LL') }}
                                        @break
                                    @case(2)
                                        
                                        @break
                                @endswitch
                            </td>
					        <td>
                                @switch($row->review_jenis_surat)
                                    @case('Edaran')
                                        {{ $row->edaran->edaran_perihal }}
                                        @break
                                    @case(2)
                                        
                                        @break
                                @endswitch
                            </td>
					        <td>{{ $row->review_jenis_surat }}</td>
					        <td class="text-right">
					        	@role('user|super-admin|supervisor')
                                @if (Auth::user()->jabatan->jabatan_struktural == 1)
                                <a href="{{ route('review', array('no' => $row->review_nomor_surat, 'tipe' => $row->review_jenis_surat)) }}" class="btn btn-success btn-xs m-r-3"><i class='fas fa-check'></i></a>
                                @else
                                @switch($row->review_jenis_surat)
                                    @case('Edaran')
                                        <a href="/edaran/edit?no={{ $row->review_nomor_surat }}" class="btn btn-default btn-xs m-r-3"><i class='fas fa-edit'></i></a>
                                        @break
                                    @case(2)
                                        
                                        @break
                                    @default
                                        
                                @endswitch
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
