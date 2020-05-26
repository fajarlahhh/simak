@extends('pages.setup.main')

@section('title', ' | '.$aksi.' Data Penyimpanan')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Penyimpanan</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Data Penyimpanan <small>{{ $aksi }} Data</small></h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
			<h4 class="panel-title">Form</h4>
		</div>
		<form action="{{ route('datapenyimpanan.'.strtolower($aksi)) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ $back }}">
                @if($aksi == 'Edit')
                <input type="hidden" name="penyimpanan_id" value="{{ $data->penyimpanan_id }}">
                @endif
                <div class="form-group">
                    <label class="control-label">Tempat Penyimpanan</label>
                    <input class="form-control" type="text" name="penyimpanan_nama" value="{{ $aksi == 'Edit'? $data->penyimpanan_nama: old('penyimpanan_nama') }}" required data-parsley-minlength="1" data-parsley-maxlength="250" autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label class="control-label">Deskripsi</label>
                    <input class="form-control" type="text" name="penyimpanan_deskripsi" value="{{ $aksi == 'Edit'? $data->penyimpanan_deskripsi: old('penyimpanan_deskripsi') }}" required autocomplete="off"  />
                </div>
                <div class="form-group">
                    <input type="checkbox" data-render="switchery" data-theme="yellow" value="1" {{ ($aksi == 'Edit' && $data->pimpinan == 1? 'checked': (old('pimpinan') == 1? 'checked': '')) }} data-change="check-switchery-state-text" name="pimpinan"/>
                    <label class="control-label">Pimpinan</label>
                </div>
			</div>
			<div class="panel-footer">
				@role('user|admin')
				<input type="submit" value="Simpan" class="btn btn-sm btn-success m-r-3"  />
				@endrole
	            <a href="{{ $back }}" class="btn btn-sm btn-danger">Batal</a>
	            <div class="pull-right">
					This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
				</div>
	        </div>
		</form>
	</div>
    @if ($errors->any())
	<div class="alert alert-danger">
		<ul>
		    @foreach ($errors->all() as $error)
	      	<li>{{ $error }}</li>
		    @endforeach
		</ul>
	</div>
    @endif
@endsection

@push('scripts')
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
@endpush
