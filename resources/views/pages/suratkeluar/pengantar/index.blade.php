@extends('pages.suratkeluar.main')

@section('title', ' | Surat Pengantar')

@section('page')
	<li class="breadcrumb-item active">Surat Pengantar</li>
@endsection

@push('css')
	<link href="{{ url('/public/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
	<link href="{{ url('/public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Surat Pengantar</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-2 col-lg-2 col-xl-2 col-xs-12">
                	@role('user|super-admin|supervisor')
                    <div class="form-inline">
                        <a href="{{ route('pengantar.tambah') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-10 col-lg-10 col-xl-10 col-xs-12">
                	<form action="{{ route('pengantar') }}" method="GET" id="frm-cari">
                		<div class="form-inline pull-right">
							<div class="form-group">
								<select class="form-control selectpicker cari" name="tahun" data-live-search="true" data-style="btn-danger" data-width="100%">
                                    @for ($thn = 2020; $thn <= date('Y'); $thn++)
									<option value="{{ $thn }}" {{ $tahun == $thn? 'selected': '' }}>{{ $thn }}</option>
                                    @endfor
								</select>
							</div>&nbsp;
							<div class="form-group">
								<select class="form-control selectpicker cari" name="terbit" data-live-search="true" data-style="btn-success" data-width="100%">
									<option value="0" {{ $terbit == '0'? 'selected': '' }}>Belum Disetujui</option>
									<option value="1" {{ $terbit == '1'? 'selected': '' }}>Sudah Disetujui & Diterbitkan</option>
								</select>
							</div>&nbsp;
							<div class="form-group">
								<select class="form-control selectpicker cari" name="tipe" data-live-search="true" data-style="btn-warning" data-width="100%">
									<option value="0" {{ $tipe == '0'? 'selected': '' }}>Exist</option>
									<option value="1" {{ $tipe == '1'? 'selected': '' }}>Deleted</option>
									<option value="2" {{ $tipe == '2'? 'selected': '' }}>All</option>
								</select>
							</div>&nbsp;
		                	<div class="input-group">
								<input type="text" class="form-control cari" name="cari" placeholder="Cari Nomor/Sifat/Perihal" aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
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
							<th>Sifat</th>
							<th>Perihal</th>
							<th>Tanda Tangan</th>
							<th class="width-10"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $row)
					    <tr>
                            <td>
                                {{ ++$i }}
                            </td>
					        <td>
                                <label data-toggle="tooltip" data-container="#sm{{ $i }}" id="sm{{ $i }}" title="{{ $row->operator.", ".\Carbon\Carbon::parse($row->updated_at)->isoFormat('LLL') }}">{{ $row->pengantar_nomor }}</label>
                            </td>
					        <td>{{ \Carbon\Carbon::parse($row->pengantar_tanggal)->isoFormat('LL') }}</td>
					        <td>{{ $row->pengantar_sifat }}</td>
                            <td>{{ $row->pengantar_perihal }}</td>
                            <td>{!! $row->jabatan_nama." - ".$row->pengantar_pejabat !!}</td>
                            <td class="with-btn-group align-middle" nowrap>
                                <div class="btn-group">
                                    <a href="{{ url('/pengantar/cetak?no='.$row->pengantar_nomor) }}" target="_blank"" class="btn btn-default btn-sm">Preview</a>
                                    <a href="#" class="btn btn-default btn-sm dropdown-toggle width-30 no-caret" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        @role('user|super-admin|supervisor')
                                        @if (!$row->trashed())
                                        @if ($row->fix == 0)
                                        <li>
                                            <a href="{{ url('/pengantar/edit?no='.$row->pengantar_nomor) }}" class="m-2">
                                                Edit Keseluruhan
                                                @if ($row->harus_revisi)
                                                <i class="fas fa-exclamation-circle text-red m-t-3 m-l-2 fa-lg" ></i>
                                                @endif
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/pengantar/edit/isi?no='.$row->pengantar_nomor) }}" class="m-2"> Edit Isi</a>
                                        </li>
                                        @endif
                                        <li>
                                            <a href="javascript:;" onclick="hapus('{{ $row->pengantar_nomor }}')" class="m-2" id='btn-del'> Hapus</a>
                                        </li>
                                        @else
                                        <li>
                                            <a href="javascript:;" onclick="restore('{{ $row->pengantar_nomor }}')" class="m-2" id='btn-del'> Restore</a>
                                        </li>
                                        @endif
                                        @endrole
                                    </ul>
                                </div>
                                @if ($row->harus_revisi)
                                <i class="fas fa-exclamation-circle text-red m-t-3 m-l-2 fa-lg" data-toggle="tooltip" title="Harus Revisi"></i>
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
				<label class="pull-right">Jumlah : {{ $data->total() }}</label>
			</div>
			This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
		</div>
	</div>
@endsection

@push('scripts')
<script src="{{ url('/public/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ url('/public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "inherit" );
    });

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

    function restore(id) {
        Swal.fire({
            title: 'Restore Data',
            text: 'Anda akan mengembalikan surat pengantar ' + id + '',
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
                    url: '{{ url("/pengantar/restore") }}?no=' + id,
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
            text: 'Anda akan menghapus surat pengantar ' + id + '',
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
                    url: '{{ url("/pengantar/hapus") }}?no=' + id,
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
