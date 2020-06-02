@extends('pages.setup.main')

@section('title', ' | '.$aksi.' Pengguna')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Pengguna</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Pengguna <small>{{ $aksi }} Data</small></h1>
@endsection

@section('subcontent')
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        </div>
        <h4 class="panel-title"><i class="far fa-file-alt"></i> Form</h4>
    </div>
    <form action="{{ route('datapengguna.'.strtolower($aksi)) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
        @method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
        @csrf
        <div class="panel-body">
            <input type="hidden" name="redirect" value="{{ $back }}">
            <div class="row">
                @if($aksi == 'Edit')
                <input type="hidden" name="ID" value="{{ $data->pengguna_id }}">
                @endif
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="control-label">ID</label>
                        <input class="form-control" type="text" name="pengguna_id" value="{{ $aksi == 'Edit'? $data->pengguna_id: old('pengguna_id') }}" required {{ $aksi == 'Edit'? "readonly": "" }}/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nama</label>
                        <input  class="form-control" type="text" name="pengguna_nama" value="{{ $aksi == 'Edit'? $data->pengguna_nama: old('pengguna_nama') }}" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">NIP</label>
                        <input  class="form-control" type="text" name="pengguna_nip" value="{{ $aksi == 'Edit'? $data->pengguna_nip: old('pengguna_nip') }}" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">No. Hp</label>
                        <input  class="form-control" type="text" name="pengguna_hp" value="{{ $aksi == 'Edit'? $data->pengguna_hp: old('pengguna_hp') }}" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Pangkat</label>
                        <input  class="form-control" type="text" name="pengguna_pangkat" value="{{ $aksi == 'Edit'? $data->pengguna_pangkat: old('pengguna_pangkat') }}" required />
                    </div>
                    <div class="form-group input-group-sm">
                        <label class="control-label">Bidang</label>
                        <select class="form-control selectpicker" name="bidang_nama" data-live-search="true" data-style="btn-info" data-width="100%">
                            <option value="" {{ ($aksi == 'Edit' && $data->bidang_nama == null? 'selected': (old('bidang_nama') == null? 'selected': '')) }}>Tidak Ada</option>
                            @foreach($bidang as $row)
                            <option value="{{ $row->bidang_nama }}" {{ ($aksi == 'Edit' && $data->bidang_nama == $row->bidang_nama? 'selected': (old('bidang_nama') == $row->bidang_nama? 'selected': '')) }}>{{ $row->bidang_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group input-group-sm">
                        <label class="control-label">Jabatan</label>
                        <select class="form-control selectpicker" name="jabatan_nama" data-live-search="true" data-style="btn-info" data-width="100%">
                            @foreach($jabatan as $row)
                            <option value="{{ $row->jabatan_nama }}" {{ ($aksi == 'Edit' && $data->jabatan_nama == $row->jabatan_nama? 'selected': (old('jabatan_nama') == $row->jabatan_nama? 'selected': '')) }}>{{ $row->jabatan_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group input-group-sm">
                        <label class="control-label">Tanda Tangan</label>
                        <select class="form-control selectpicker" name="gambar_nama" data-live-search="true" data-style="btn-info" data-width="100%">
                            <option value="">Tidak Ada</option>
                            @foreach($gambar as $row)
                            <option value="{{ $row->gambar_nama }}" {{ ($aksi == 'Edit' && $data->gambar_nama == $row->gambar_nama? 'selected': (old('gambar_nama') == $row->gambar_nama? 'selected': '')) }}>{{ $row->gambar_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($aksi == 'Edit')
                        @if ($data->gambar_nama)
                            <img src="/{{ $data->gambar->gambar_lokasi }}" alt="" class="width-full">
                        @endif
                    @endif
                    @include('includes.error')
                </div>
                <div class="col-md-7">
                    <div class="note note-secondary">
                        <div class="form-group">
                            <label class="control-label">Kata Sandi</label>
                            <input class="form-control" type="password" name="pengguna_sandi" autocomplete="off" id="pengguna_sandi" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Level</label>
                            <select class="form-control selectpicker" style="width : 100%" name="pengguna_level" id="pengguna_level" data-style="btn-info" onchange="hakakses()" data-width="100%">
                                @foreach($level as $lvl)
                                <option value="{{ $lvl->id }}" {{ ($aksi == 'Edit' && $data->getRoleNames()[0] == $lvl->name? 'selected': (old('pengguna_level') == $lvl->id? 'selected': '')) }}>{{ ucfirst($lvl->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <h4>Hak Akses</h4>
                        <div class="panel-body row">
                            @foreach ($menu as $menu)
                            <div class="hakakses checkbox checkbox-css col-md-6 col-lg-4">
                                <input type="checkbox" onchange="child('cssCheckbox{{ $i }}')" id="cssCheckbox{{ $i }}" name="izin[]" value="{{ $menu['id'] }}" {{ ($aksi == 'Edit'? ($data->roles[0]->name == 'admin'? 'checked': ($data->hasPermissionTo(!empty($menu['sub_menu'])? strtolower($menu['id']) : $menu['id'])? 'checked': '')): '') }}/>
                                <label for="cssCheckbox{{ $i }}" class="p-l-5">{{ $menu['title'] }}</label>
                                @foreach ($menu['sub'] as $sub)
                                <div class='hakakses checkbox checkbox-css col-md-12'>
                                    <input type='checkbox' onchange='parent("cssCheckbox{{ $i }}")' class='cssCheckbox{{ $i }}' id='cssCheckbox{{ $sub['value'] }}' name='izin[]' value='{{ $sub['value'] }}'
                                    {{ ($aksi == 'Edit'? ($data->roles[0]->name == 'admin'? 'checked': ($data->hasPermissionTo($sub['id'])? 'checked': '')): '') }}/>
                                    <label for='cssCheckbox{{ $sub['id'] }}' class='p-l-5'>{{ $sub['title'] }}</label>
                                </div>
                                @endforeach
                                @php
                                    $i++;
                                @endphp
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            @role('supervisor|super-admin|supervisor')
            <input type="submit" value="Simpan" class="btn btn-sm btn-success m-r-3"  />
            @endrole
            <a href="{{ $back }}" class="btn btn-sm btn-danger">Batal</a>
            <div class="pull-right">
                This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/bootstrap-show-password/dist/bootstrap-show-password.min.js"></script>
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
	<script>
		$(document).ready(function() {
			hakakses();
    		$('#pengguna_sandi').password();
        });

		function child(elmt) {
			if ($('#' + elmt).is(':checked')) {
				$('.' + elmt).prop('checked', true);
			}else{
				$('.' + elmt).prop('checked', false);
			}
		}
		function parent(elmt) {
			var i = 0;
		    $('.' + elmt).each(function() {
		    	if ($('.' + elmt).is(':checked')) {
		        	i++;
		    	}
		    });
		    if (i > 0) {
		    	$('#' + elmt).prop('checked', true);
		    }else{
		    	$('#' + elmt).prop('checked', false);
		    }
		}
		function hakakses() {
			if ($('#pengguna_level').val() == 1) {
				$('.hakakses input').prop('disabled', true);
				$('.hakakses input').prop('checked', true);
  				$(".hakakses").addClass("disabled");
			}else{
				$('.hakakses input').prop('disabled', false);
				if ('{{ $aksi }}' == 'tambah') {
					$('.hakakses input').prop('checked', false);
				}
  				$(".hakakses").removeClass("disabled");
			}
		}
	</script>
@endpush
