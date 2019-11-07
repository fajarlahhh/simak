@extends('pages.setup.main')

@section('title', ' | Data Pengguna')

@section('page')
	<li class="breadcrumb-item active">Data Pengguna</li>
@endsection

@section('header')
	<h1 class="page-header">Data Pengguna</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-6 col-lg-7 col-xl-9 col-xs-12">
                	@role('user|admin')
                    <div class="form-inline">
                        <a href="{{ route('datapengguna.tambah') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-6 col-lg-5 col-xl-3 col-xs-12">
                	<form id="frm-cari" action="/pengguna" method="GET">
	                	<div class="input-group">
							<input type="text" class="form-control" name="cari" placeholder="Cari Nama" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
							<div class="input-group-append">
								 <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"></i></span>
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
							<th>Unit</th>
							<th>Jabatan</th>
							<th>Bagian</th>
							<th>Seksi</th>
							<th>Level</th>
							<th class="width-90"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $pengguna)
					    @if($pengguna->pegawai)
					    <tr>
					        <td>{{ ++$i }}</td>
					        <td>{{ $pengguna->pengguna_id }}</td>
					        <td>{{ $pengguna->pegawai->nm_pegawai }}</td>
					        <td>{{ $pengguna->pegawai->unit->nm_unit }}</td>
					        <td>{{ $pengguna->pegawai->jabatan->nm_jabatan }}</td>
					        <td>{{ $pengguna->pegawai->bagian->nm_bagian }}</td>
					        <td>{{ $pengguna->pegawai->seksi? $pengguna->pegawai->seksi->nm_seksi: '' }}</td>
					        <td>{{ ucFirst($pengguna->getRoleNames()[0]) }}</td>
					        <td>
					        	@role('user|admin')
                                <a href="/datapengguna/edit/{{ $pengguna->pengguna_id }}" id='btn-del' class='btn btn-grey btn-xs m-r-3'><i class='fas fa-edit'></i></a>
                                @if (!in_array($pengguna->pengguna_id, config('admin.nip')))
	                            <a href="javascript:;" onclick="hapus('{{ $pengguna->pengguna_id }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
                                @endif
	                    		@endrole
					        </td>
				      	</tr>
				      	@endif
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
	<script>
		$(".cari").change(function() {
		     $("#frm-cari").submit();
		});

		function hapus(id) {
			swal({
				title: 'Hapus Data',
				text: 'Anda akan menghapus pengguna dengan ID: ' + id + '',
				icon: 'warning',
				buttons: {
					cancel: {
						text: 'Batal',
						value: null,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Ya',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true
					}
				}
			}).then(function(isConfirm) {
		      	if (isConfirm) {
    				$.ajaxSetup({
					    headers: {
					        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					    }
					});
	          		$.ajax({
	          			url: "/datapengguna/hapus/" + id,
	          			type: "POST",
	          			data: {
	          				"_method": 'DELETE'
	          			},
	          			success: function(data){
	          				swal({
						       	title: data['swal_judul'],
						       	text: data['swal_pesan'],
						       	icon: data['swal_tipe'],

						   	}).then(function() {
							    location.reload(true)
							});
	          			},
	          			error: function (xhr, ajaxOptions, thrownError) {
            				swal("Hapus data", xhr.status, "error");
      					}
	          		})
		      	}
		    });
		}
	</script>
@endpush
