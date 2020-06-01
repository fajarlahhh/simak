@extends('pages.setup.main')

@section('title', ' | '.$aksi.' Surat Masuk')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Surat Masuk</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Surat Masuk <small>{{ $aksi }} Data</small></h1>
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
		<form action="{{ route('suratmasuk.'.strtolower($aksi)) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="" enctype="multipart/form-data">
			@method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
			@csrf
			<div class="panel-body">
                <input type="hidden" name="redirect" value="{{ $back }}">
                @if($aksi == 'Edit')
                <input type="hidden" name="ID" value="{{ $data->surat_masuk_nomor }}">
                <input type="hidden" name="file_old" value="{{ $data->file }}">
                @endif
                <div class="form-group">
                    <label class="control-label">Nomor Surat</label>
                    <input class="form-control" type="text" name="surat_masuk_nomor" value="{{ $aksi == 'Edit'? $data->surat_masuk_nomor: old('surat_masuk_nomor') }}" required data-parsley-minlength="1" data-parsley-maxlength="250" autocomplete="off"  />
                </div>
                <div class="form-group">
                    <label class="control-label">Tanggal Masuk</label>
                    <input type="text" readonly class="form-control datepicker" name="surat_masuk_tanggal_masuk" required value="{{ date('d F Y', strtotime($aksi == 'Edit'? $data->surat_masuk_tanggal_masuk: (old('surat_masuk_tanggal_masuk')? old('surat_masuk_tanggal_masuk'): now()))) }}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Tanggal Surat</label>
                    <input type="text" readonly class="form-control datepicker" name="surat_masuk_tanggal_surat" required value="{{ date('d F Y', strtotime($aksi == 'Edit'? $data->surat_masuk_tanggal_surat: (old('surat_masuk_tanggal_surat')? old('surat_masuk_tanggal_surat'): now()))) }}"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Asal</label>
                    <input class="form-control" type="text" name="surat_masuk_asal" value="{{ $aksi == 'Edit'? $data->surat_masuk_asal: old('surat_masuk_asal') }}" required />
                </div>
                <div class="form-group">
                    <label class="control-label">Perihal</label>
                    <textarea class="form-control" rows="3" name="surat_masuk_perihal">{{ $aksi == 'Edit'? $data->surat_masuk_perihal: old('surat_masuk_perihal') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Rangkuman Isi Surat</label>
                    <textarea class="form-control" rows="3" id="editor1" name="surat_masuk_keterangan">{{ $aksi == 'Edit'? $data->surat_masuk_keterangan: old('surat_masuk_keterangan') }}</textarea>
                </div>
                <div class="note note-danger">
                    <div class="form-group">
                        <label class="control-label">Upload PDF</label>
                        <input class="form-control" type="file" name="file" accept="application/pdf" {{ $aksi == "Edit"? "": "required" }} autocomplete="off" />
                    </div>
                    @if ($aksi == "Edit")
                    <div class="overflow-auto">
                        @include('includes.component.pdf')
                    </div>
                    @endif
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
