@extends('pages.suratkeluar.main')

@section('title', ' | '.$aksi.' Undangan')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Undangan</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Undangan <small>{{ $aksi }} Data</small></h1>
@endsection

@section('subcontent')
<form action="{{ route('undangan.'.strtolower($aksi)) }}" name="form-wizard" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="" enctype="multipart/form-data">
    @csrf
    @method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<div class="panel-heading">
			<div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
			<h4 class="panel-title">Form</h4>
		</div>
		<div class="panel-body p-5">
            @if ($aksi == 'Edit')
            <input type="hidden" name="undangan_nomor" value="{{ $aksi == 'Edit'? $data->undangan_nomor: old('undangan_nomor') }}" readonly/>
            @if ($catatan)
            <div class="alert alert-danger">
                <h4>Catatan Hasil Review </h4>
                {!! $catatan->review_catatan !!}<br>
                <small><strong>Oleh : {{ $catatan->jabatan->jabatan_nama }}</strong></small>
            </div>
            @endif
            @endif
            <br>
            <ul class="nav nav-tabs">
                @if ($edit == 1)
                <li class="nav-items">
                    <a href="#default-tab-1" data-toggle="tab" class="nav-link active show">
                        <span class="d-sm-none">Tab 1</span>
                        <span class="d-sm-block d-none">Tanggal, Sifat, Lampiran, Perihal</span>
                    </a>
                </li>
                <li class="nav-items">
                    <a href="#default-tab-2" data-toggle="tab" class="nav-link">
                        <span class="d-sm-none">Tab 2</span>
                        <span class="d-sm-block d-none">Tujuan</span>
                    </a>
                </li>
                @endif
                <li class="nav-items">
                    <a href="#default-tab-3" data-toggle="tab" class="nav-link {{ $edit == 2? "active show": "" }}">
                        <span class="d-sm-none">Tab 3</span>
                        <span class="d-sm-block d-none">Isi Surat</span>
                    </a>
                </li>
                @if ($edit == 1)
                <li class="nav-items">
                    <a href="#default-tab-4" data-toggle="tab" class="nav-link">
                        <span class="d-sm-none">Tab 4</span>
                        <span class="d-sm-block d-none">Tanda Tangan, Tembusan</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="tab-content">
                @if ($edit == 1)
                <div class="tab-pane fade active show" id="default-tab-1">
                    <fieldset>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tanggal Surat</label>
                                    <input type="text" readonly class="form-control datepicker" name="undangan_tanggal" required value="{{ date('d F Y', strtotime($aksi == 'Edit'? $data->undangan_tanggal:(old('undangan_tanggal')? old('undangan_tanggal'): now()))) }}" data-parsley-group="step-1"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Sifat</label>
                                    <input class="form-control"  type="text" name="undangan_sifat" value="{{ $aksi == 'Edit'? $data->undangan_sifat: old('undangan_sifat') }}" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Lampiran</label>
                                    <input class="form-control"  type="text" name="undangan_lampiran" value="{{ $aksi == 'Edit'? $data->undangan_lampiran: old('undangan_lampiran') }}" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Perihal</label>
                                    <textarea class="form-control" rows="3" name="undangan_perihal">{{ $aksi == 'Edit'? $data->undangan_perihal: old('undangan_perihal') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="note note-success">
                                    <div class="form-group">
                                        <label class="control-label">Upload Lampiran</label>
                                        <input type="file" class="form-control" accept="image/*" name="lampiran[]" multiple />
                                    </div>
                                    @if ($aksi == 'Edit' && $data->lampiran)
                                    <div class="row">
                                        @foreach ($data->lampiran as $lampiran)
                                        <div id="filelampiran{{ $i }}" class="col-md-4 text-center m-t-5">
                                            {{ ++$i }}
                                            <img src="{{ $lampiran->file }}" alt="" class="width-full">
                                            <br>
                                            <a href="javascript:;" class="btn btn-danger btn-xs m-t-5" onclick="hapus('{{ $lampiran->file }}', '{{ $i-1 }}')">Hapus</a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="tab-pane fade" id="default-tab-2">
                    <fieldset>
                        <div class="form-group">
                            <label class="control-label">Kepada</label>
                            <input class="form-control" type="text" name="undangan_kepada_awal" value="{{ $kepada? $kepada[0]: (old('undangan_kepada_awal')? old('undangan_kepada_awal'): "Kepada Yth :") }}" />
                        </div>
                        <div class="note note-default">
                            <label class="control-label">Tujuan</label>
                            <table class="table">
                                <tbody id="tujuan">
                                   @if ($kepada)
                                   @foreach ($kepada[1] as $tujuan)
                                   <tr>
                                       <td>
                                           <select class="form-control opd m-t-5" name="tujuan[]" style='width: 100%;'>
                                               <option value="{{ $tujuan }}" selected>{{ $tujuan }}</option>
                                           </select>
                                       </td>
                                       <td style='width: 5px'>
                                           <a onclick='delRekanan(this)' href='javascript:;' class='m-t-5 btn btn-danger btn-xs'>
                                               <i class='fa fa-times'></i>
                                           </a>
                                       </td>
                                   </tr>
                                   @endforeach
                                   @endif
                                </tbody>
                            </table>
                            <div class="text-center">
                                <a class="btn btn-warning btn-sm" href="javascript:;" style='cursor:pointer;' onclick="addRekanan('tujuan')">Tambah Tujuan</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Di</label>
                            <input class="form-control" type="text" name="undangan_kepada_akhir" value="{{ $kepada? $kepada[2]: (old('undangan_kepada_akhir')? old('undangan_kepada_akhir'): "di Tempat") }}" />
                        </div>
                    </fieldset>
                </div>
                @endif
                <div class="tab-pane fade {{ $edit == 2? "active show": "" }}" id="default-tab-3">
                    <fieldset>
                        <textarea class="form-control" rows="3" id="editor2"  name="undangan_isi">{{ $aksi == 'Edit'? $data->undangan_isi: old('undangan_isi') }}</textarea>
                    </fieldset>
                </div>
                @if ($edit == 1)
                <div class="tab-pane fade" id="default-tab-4">
                    <fieldset>
                        <div class="form-group input-group-sm">
                            <label class="control-label">Tanda Tangan</label>
                            <select class="form-control selectpicker" name="undangan_pejabat" data-live-search="true" data-style="btn-info" data-width="100%">
                                @foreach($pengguna as $row)
                                <option value="{{ $row->pengguna_id }}" {{ ($aksi == 'Edit' && $data->pengguna_nama == $row->pengguna_nama? 'selected': (old('gambar_nama') == $row->gambar_nama? 'selected': '')) }}>{{ $row->jabatan->jabatan_nama }} - {{ $row->pengguna_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group input-group-sm">
                            <label class="control-label">Jenis Tanda Tangan</label>
                            <select class="form-control selectpicker" name="undangan_jenis_ttd" data-live-search="true" data-style="btn-info" data-width="100%">
                                <option value="1" {{ ($aksi == 'Edit' && $data->undangan_ttd == 1? 'selected': (old('undangan_jenis_ttd') == 1? 'selected': '')) }}>QR Code</option>
                                <option value="2" {{ ($aksi == 'Edit' && $data->undangan_ttd != 1? 'selected': (old('undangan_jenis_ttd') == 1? 'selected': '')) }}>Gambar</option>
                            </select>
                        </div>
                        <div class="note note-default">
                            <label class="control-label">Tembusan</label>
                            <table class="table">
                                <tbody id="tembusan">
                                   @if ($tembusan)
                                   @foreach ($tembusan[1] as $tembusan)
                                   @php
                                       $opd = explode(" di ", $tembusan);
                                   @endphp
                                   <tr>
                                       <td>
                                           <select class="form-control opd m-t-5" name="tembusan[]" style='width: 100%;'>
                                               <option value="{{ $opd[0] }}" selected>{{ $opd[0] }}</option>
                                           </select>
                                       </td>
                                       <td style='width: 5px'>
                                           <a onclick='delRekanan(this)' href='javascript:;' class='m-t-5 btn btn-danger btn-xs'>
                                               <i class='fa fa-times'></i>
                                           </a>
                                       </td>
                                   </tr>
                                   @endforeach
                                   @endif
                                </tbody>
                            </table>
                            <div class="text-center">
                                <a class="btn btn-warning btn-sm" href="javascript:;" style='cursor:pointer;' onclick="addRekanan('tembusan')">Tambah Tembusan</a>
                            </div>
                        </div>
                    </fieldset>
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
	</div>
</form>
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
    <script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
    <script src="/assets/plugins/ckeditor4/ckeditor.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
    <script>
        var id = 0;
        $(document).ready(function(){
            select2();
        });

        function addRekanan(element){
            $("#" + element).append("<tr>\
                <td>\
                    <select class='form-control opd m-t-5' name='" + element + "[]' id='opd" + id + "' style='width: 100%;'></select>\
                </td>\
	            <td style='width: 5px'>\
	                <a onclick='delRekanan(this)' href='javascript:;' class='m-t-5 btn btn-danger btn-xs'>\
						<i class='fa fa-times'></i>\
					</a>\
	            </td>\</tr>");
            select2("#opd" + id );
            id++;
        }

        CKEDITOR.replace( 'editor2' );

		$('.datepicker').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			orientation: "bottom",
			autoclose: true
		});

        function delRekanan(id){
            $(id).closest("tr").remove();
        }

        function hapus(id, ket) {
            Swal.fire({
                title: 'Hapus Data',
                text: 'Anda akan menghapus lampiran ' + ket + '',
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
                        url: "/undangan/hapus/lampiran?file=" + id,
                        type: "POST",
                        data: {
                            "_method": 'DELETE'
                        },
                        success: function(data){
                            if(data == 1){
                                $("#filelampiran" + ket).remove();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Hapus data',
                                    text: 'Hapus Lampiran ' + ket + ' Berhasil'
                                });
                            }else{
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Hapus data',
                                    text: 'Hapus Lampiran ' + ket + ' Gagal'
                                });
                            }
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

	    function select2(elmt = '.opd'){
            $(elmt).select2({
                minimumInputLength: 1,
                ajax:{
                    url: '/dataopd/cari',
                    dataType: "json",
                    delay: 250,
                    type : 'GET',
                    data: function(params){
                        return{
                            cari : params.term
                        };
                    },
                    processResults: function(data){
                        var results = [];

                        $.each(data, function(index, item){
                            results.push({
                                id: item.opd_nama,
                                text: item.opd_nama
                            });
                        });
                        return{
                            results: results
                        };
                    },
                    cache: true,
                },
            });
	    }
    </script>
@endpush
