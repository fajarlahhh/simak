@extends('pages.suratkeluar.main')

@section('title', ' | '.$aksi.' Edaran')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Edaran</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Edaran <small>{{ $aksi }} Data</small></h1>
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
		<form action="{{ route('suratmasuk.'.strtolower($aksi)) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
			@csrf
			<div class="panel-body">
                <input type="hidden" name="redirect" value="{{ $back }}">
                @if($aksi == 'Edit')
                <input type="hidden" name="ID" value="{{ $data->edaran_nomor }}">
                <input type="hidden" name="file_old" value="{{ $data->file }}">
                @endif
                <div class="form-group">
                    <label class="control-label">Nomor Edaran</label>
                    <input class="form-control" type="text" name="edaran_nomor" value="{{ $aksi == 'Edit'? $data->edaran_nomor: old('edaran_nomor') }}" required data-parsley-minlength="1" data-parsley-maxlength="250" autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label class="control-label">Tanggal Surat</label>
                    <input type="text" readonly class="form-control datepicker" name="edaran_tanggal" required value="{{ date('d F Y', strtotime($aksi == 'Edit'? $data->edaran_tanggal: (old('edaran_tanggal')? old('edaran_tanggal'): now()))) }}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Sifat</label>
                    <input class="form-control" type="text" name="edaran_sifat" value="{{ $aksi == 'Edit'? $data->edaran_sifat: old('edaran_sifat') }}" required />
                </div>
                <div class="form-group">
                    <label class="control-label">Perihal</label>
                    <input class="form-control" type="text" name="edaran_perihal" value="{{ $aksi == 'Edit'? $data->edaran_perihal: old('edaran_perihal') }}" required />
                </div>
                <div class="form-group">
                    <label class="control-label">Isi Edaran</label>
                    <textarea class="form-control" rows="3" id="editor1" name="surat_masuk_keterangan">{{ $aksi == 'Edit'? $data->surat_masuk_keterangan: old('surat_masuk_keterangan') }}</textarea>
                </div>
			</div>
			<div class="panel-footer">
				@role('user|super-admin|supervisor')
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
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="/assets/plugins/ckeditor4/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'editor1' );
        
		$('.datepicker').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			orientation: "bottom",
			autoclose: true
		});
    </script>
@endpush
