@extends('pages.setup.main')

@section('title', ' | '.$aksi.' Data Jabatan')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Jabatan</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Data Jabatan <small>{{ $aksi }} Data</small></h1>
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
		<form action="{{ route('datajabatan.'.strtolower($aksi)) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ $back }}">
                @if($aksi == 'Edit')
                <input type="hidden" name="id" value="{{ $data->jabatan_id }}">
                @endif
                <div class="form-group">
                    <label class="control-label">Nama Jabatan</label>
                    <input class="form-control" type="text" name="jabatan_nama" value="{{ $aksi == 'Edit'? $data->jabatan_nama: old('jabatan_nama') }}" required data-parsley-minlength="1" data-parsley-maxlength="250" autocomplete="off"  />
                </div>
                <div class="form-group input-group-sm">
                    <label class="control-label">Bidang</label>
                    <select class="form-control selectpicker" name="bidang_id" data-live-search="true" data-style="btn-info" data-width="100%">
                        @foreach($bidang as $row)
                        <option value="{{ $row->bidang_id }}" {{ ($aksi == 'Edit' && $data->bidang_id == $row->bidang_id? 'selected': (old('bidang_id') == $row->bidang_id? 'selected': '')) }}>{{ $row->bidang_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group input-group-sm">
                    <label class="control-label">Atasan</label>
                    <select class="form-control selectpicker" name="jabatan_parent" data-live-search="true" data-style="btn-info" data-width="100%">
                        <option value="">Tidak Ada</option>
                        @foreach($jabatan as $row)
                        <option value="{{ $row->jabatan_id }}" {{ ($aksi == 'Edit' && $data->jabatan_parent == $row->jabatan_id? 'selected': (old('jabatan_parent') == $row->jabatan_id? 'selected': '')) }}>{{ $row->jabatan_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="checkbox" data-render="switchery" data-theme="yellow" value="1" {{ ($aksi == 'Edit' && $data->jabatan_pimpinan == 1? 'checked': (old('jabatan_pimpinan') == 1? 'checked': '')) }} data-change="check-switchery-state-text" name="jabatan_pimpinan"/>
                    <label class="control-label">Pimpinan</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" data-render="switchery" data-theme="yellow" value="1" {{ $aksi == 'Tambah'? 'checked': ($aksi == 'Edit' && $data->jabatan_struktural == 1? 'checked': (old('jabatan_struktural') == 1? 'checked': '')) }} data-change="check-switchery-state-text" name="jabatan_struktural"/>
                    <label class="control-label">Jabatan Struktural</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" data-render="switchery" data-theme="yellow" value="1" {{ ($aksi == 'Edit' && $data->jabatan_verifikator == 1? 'checked': (old('jabatan_verifikator') == 1? 'checked': '')) }} data-change="check-switchery-state-text" name="jabatan_verifikator"/>
                    <label class="control-label">Verifikator</label>
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
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="/assets/plugins/switchery/switchery.min.js"></script>
	<script>
        if ($('[data-render=switchery]').length !== 0) {
            $('[data-render=switchery]').each(function() {
                var themeColor = COLOR_GREEN;
                if ($(this).attr('data-theme')) {
                    switch ($(this).attr('data-theme')) {
                        case 'red':
                            themeColor = COLOR_RED;
                            break;
                        case 'blue':
                            themeColor = COLOR_BLUE;
                            break;
                        case 'purple':
                            themeColor = COLOR_PURPLE;
                            break;
                        case 'orange':
                            themeColor = COLOR_ORANGE;
                            break;
                        case 'black':
                            themeColor = COLOR_BLACK;
                            break;
                    }
                }
                var option = {};
                option.color = themeColor;
                option.secondaryColor = ($(this).attr('data-secondary-color')) ? $(this).attr('data-secondary-color') : '#dfdfdf';
                option.className = ($(this).attr('data-classname')) ? $(this).attr('data-classname') : 'switchery';
                option.disabled = ($(this).attr('data-disabled')) ? true : false;
                option.disabledOpacity = ($(this).attr('data-disabled-opacity')) ? parseFloat($(this).attr('data-disabled-opacity')) : 0.5;
                option.speed = ($(this).attr('data-speed')) ? $(this).attr('data-speed') : '0.5s';
                var switchery = new Switchery(this, option);
            });
        }
    </script>
@endpush
