@extends('pages.main')

@section('title', ' | Review')

@section('page')
	<li class="breadcrumb-item active">Review</li>
@endsection

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
                        @if (!$row->edaran->trashed())
					    <tr>
					        <td>{{ ++$i }}</td>
					        <td>
                                <label data-toggle="tooltip" title="{{ $row->operator.", ".\Carbon\Carbon::parse($row->created_at)->isoFormat('LLL') }}">{{ $row->review_surat_nomor }}</label>
                            </td>
					        <td>
                                @switch($row->review_surat_jenis)
                                    @case('Edaran')
                                        {{ \Carbon\Carbon::parse($row->edaran_tanggal)->isoFormat('LL') }}
                                        @break
                                    @case('Pengantar')
                                        {{ \Carbon\Carbon::parse($row->pengantar_tanggal)->isoFormat('LL') }}
                                        @break
                                    @case('Tugas')
                                        {{ \Carbon\Carbon::parse($row->tugas_tanggal)->isoFormat('LL') }}
                                        @break
                                    @case('Undangan')
                                        {{ \Carbon\Carbon::parse($row->undangan_tanggal)->isoFormat('LL') }}
                                        @break
                                @endswitch
                            </td>
					        <td>
                                @switch($row->review_surat_jenis)
                                    @case('Edaran')
                                        {{ $row->edaran->edaran_perihal }}
                                        @break
                                    @case('Pengantar')
                                        {{ $row->pengantar->pengantar_perihal }}
                                        @break
                                    @case('Tugas')
                                        {{ $row->tugas->tugas_perihal }}
                                        @break
                                    @case('Undangan')
                                        {{ $row->undangan->eundangan_perihal }}
                                        @break
                                @endswitch
                            </td>
					        <td>{{ $row->review_surat_jenis }}</td>
					        <td class="text-right">
					        	@role('user|super-admin|supervisor')
                                @if (Auth::user()->jabatan->jabatan_struktural == 1)
                                <a href="{{ route('review', array('no' => $row->review_surat_nomor, 'tipe' => $row->review_surat_jenis)) }}" class="btn btn-success btn-xs m-r-3"><i class='fas fa-check'></i></a>
                                @else
                                <a href="{{ url('/'.strtolower($row->review_surat_jenis).'/edit?no='.$row->review_surat_nomor) }}" class="btn btn-default btn-xs m-r-3"><i class='fas fa-edit'></i></a>
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
				<label class="pull-right">Jumlah : {{ $data->total() }}</label>
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
</script>
@endpush
