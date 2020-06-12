@extends('pages.setup.main')

@section('title', ' | Data Pengguna')

@section('page')
	<li class="breadcrumb-item active">Data Pengguna</li>
@endsection

@push('css')
	<link href="{{ url('/public/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
@endpush


@section('header')
	<h1 class="page-header">Data Pengguna</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-2 col-lg-2 col-xl-2 col-xs-12">
                	@role('user|super-admin')
                    <div class="form-inline">
                        <a href="{{ route('datapengguna.tambah') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-10 col-lg-10 col-xl-10 col-xs-12">
                    <form id="frm-cari" action="{{ route('datapengguna') }}" method="GET">
                		<div class="form-inline pull-right">
                            <div class="form-group">
                                <select class="form-control selectpicker cari" name="tipe" data-live-search="true" data-style="btn-warning" data-width="100%">
                                    <option value="0" {{ $tipe == '0'? 'selected': '' }}>Exist</option>
                                    <option value="1" {{ $tipe == '1'? 'selected': '' }}>Deleted</option>
                                    <option value="2" {{ $tipe == '2'? 'selected': '' }}>All</option>
                                </select>
                            </div>&nbsp;
                            <div class="input-group">
                                <input type="text" class="form-control" name="cari" placeholder="Cari Nama" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
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
							<th>ID</th>
							<th>Nama</th>
							<th>NIP</th>
							<th>Pangkat</th>
							<th>Jabatan</th>
							<th>Bidang</th>
							<th>Struktural</th>
							<th>Pimpinan</th>
							<th class="width-120"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $row)
					    <tr>
					        <td>{{ ++$i }}</td>
                            <td>{{ $row->pengguna_id }}</td>
                            <td>{{ $row->pengguna_nama }}</td>
                            <td>{{ $row->pengguna_nip }}</td>
                            <td>{{ $row->pengguna_pangkat }}</td>
                            <td>{{ $row->jabatan->jabatan_nama }}</td>
                            <td>{{ $row->jabatan->bidang_nama }}</td>
                            <td>{{ $row->jabatan->jabatan_struktural == 1? "YA": "" }}</td>
                            <td>{{ $row->jabatan->jabatan_pimpinan == 1? "YA": "" }}</td>
					        <td class="text-center">
                                @if (!$row->trashed())
                                @if ($row->gambar_nama)
                                <a href="{{ url('/'.$row->gambar->gambar_lokasi) }}" target="_blank" class='btn btn-warning btn-xs '><i class='fas fa-signature'></i></a>
                                @endif
					        	@role('user|super-admin')
                                <a href="{{ url('/datapengguna/edit/'.$row->pengguna_id) }}" id='btn-del' class='btn btn-grey btn-xs '><i class='fas fa-edit'></i></a>
                                @if (!in_array($row->pengguna_id, config('admin.nip')))
	                            <a href="javascript:;" onclick="hapus('{{ $row->pengguna_id }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
                                @endif
	                    		@endrole
                                @else
                                <a href="javascript:;" onclick="restore('{{ $row->pengguna_id }}', '{{ $row->jumlah_total }}')" class="btn btn-info btn-xs " id='btn-restore' data-toggle="tooltip" title="Restore Data"><i class='fas fa-undo'></i></a>
                                @endif
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
				<label class="pull-right">Jumlah Data : {{ $data->total() }}</label>
			</div>
			This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
		</div>
	</div>
@endsection

@push('scripts')
<script src="{{ url('/public/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script>
    $(".cari").change(function() {
            $("#frm-cari").submit();
    });

    function restore(id) {
        Swal.fire({
            title: 'Restore Data',
            text: 'Anda akan mengembalikan pengguna ' + id + '',
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
                    url: '{{ url("/datapengguna/restore") }}/' + id,
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

    function hapus(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda akan menghapus pengguna ' + id + '',
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
                    url: '{{ url("/datapengguna/hapus") }}/' + id,
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
