@extends('pages.main')

@section('title', ' | Disposisi')

@section('page')
	<li class="breadcrumb-item active">Disposisi</li>
@endsection

@push('css')
	<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Disposisi</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-hover" id="data-table-default">
                    <thead>
						<tr>
							<th>No.</th>
							<th>Nomor</th>
							<th>Tanggal Surat</th>
							<th>Tanggal Masuk</th>
							<th>Asal</th>
                            <th>Perihal</th>
							<th class="width-110"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $row)
					    <tr>
					        <td>{{ ++$i }}</td>
					        <td class="text-left">
                                <span data-toggle="tooltip" title="{{ $row['operator'].", ".\Carbon\Carbon::parse($row['created_at'])->isoFormat('LLL') }}">{{ $row['nomor'] }}</span>
                            </td>
					        <td>{{ \Carbon\Carbon::parse($row['tanggal_surat'])->isoFormat('LL') }}</td>
					        <td>{{ \Carbon\Carbon::parse($row['tanggal_masuk'])->isoFormat('LL') }}</td>
					        <td>{{ $row['asal'] }}</td>
					        <td>{{ $row['perihal'] }}</td>
					        <td class="text-right">
					        	@role('user|super-admin|supervisor')
                                @if (Auth::user()->jabatan->jabatan_struktural == 1)
                                <a href="{{ route('disposisi', array('id' => $row['id'], 'tipe' => $row['jenis'])) }}" class="btn btn-success btn-xs m-r-3"><i class='fas fa-paper-plane'></i></a>
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
			This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
		</div>
	</div>
@endsection

@push('scripts')
<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script>
	if ($('#data-table-default').length !== 0) {
		$('#data-table-default').DataTable({
            columnDefs : [{
                targets: 0,
                className: 'width-10'
                },{
                targets: [1, 4],
                className: 'width-150'
                },{
                targets: [2,3],
                className: 'width-100'
                }]
        });
	}
</script>
@endpush
