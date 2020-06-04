@extends('pages.suratkeluar.main')

@section('title', ' | '.$aksi.' Edaran')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Edaran</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Edaran <small>{{ $aksi }} Data</small></h1>
@endsection

@section('subcontent')
<form action="{{ route('edaran.'.strtolower($aksi)) }}" name="form-wizard" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="" enctype="multipart/form-data">
    @csrf
    @method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
    <div id="wizard">
        <ul>
            @if ($edit == 1)
            <li class="col-md-3 col-sm-4 col-6">
                <a href="#step-1">
                    <span class="number">1</span>
                    <span class="info text-ellipsis">
                        Header
                        <small class="text-ellipsis">Tanggal, Sifat, Lampiran, Perihal</small>
                    </span>
                </a>
            </li>
            <li class="col-md-3 col-sm-4 col-6">
                <a href="#step-2">
                    <span class="number">2</span>
                    <span class="info text-ellipsis">
                        Tujuan
                        <small class="text-ellipsis">Tujuan</small>
                    </span>
                </a>
            </li>
            @endif
            <li class="col-md-3 col-sm-4 col-6">
                <a href="#step-3">
                    <span class="number">3</span>
                    <span class="info text-ellipsis">
                        Isi
                        <small class="text-ellipsis">Isi Surat</small>
                    </span>
                </a>
            </li>
            @if ($edit == 1)
            <li class="col-md-3 col-sm-4 col-6">
                <a href="#step-4">
                    <span class="number">4</span>
                    <span class="info text-ellipsis">
                        Footer
                        <small class="text-ellipsis">Tanda Tangan, Tembusan</small>
                    </span>
                </a>
            </li>
            @endif
        </ul>
        <div>
            @if ($edit == 1)
            <div id="step-1">
                <!-- begin fieldset -->
                <fieldset>
                    <div class="row">
                        <div class="col-md-4">
                            @if ($aksi == 'Edit')
                            <div class="form-group">
                                <label class="control-label">Nomor</label>
                                <input class="form-control" type="text" name="edaran_nomor" value="{{ $aksi == 'Edit'? $data->edaran_nomor: old('edaran_nomor') }}" readonly/>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label">Tanggal Surat</label>
                                <input type="text" readonly class="form-control datepicker" name="edaran_tanggal" required value="{{ date('d F Y', strtotime($aksi == 'Edit'? $data->edaran_tanggal:(old('edaran_tanggal')? old('edaran_tanggal'): now()))) }}" data-parsley-group="step-1"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sifat</label>
                                <input class="form-control"  type="text" name="edaran_sifat" value="{{ $aksi == 'Edit'? $data->edaran_sifat: old('edaran_sifat') }}" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Lampiran</label>
                                <input class="form-control"  type="text" name="edaran_lampiran" value="{{ $aksi == 'Edit'? $data->edaran_lampiran: old('edaran_lampiran') }}" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Perihal</label>
                                <textarea class="form-control" rows="3" name="edaran_perihal">{{ $aksi == 'Edit'? $data->edaran_perihal: old('edaran_perihal') }}</textarea>
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
                <!-- end fieldset -->
            </div>
            <div id="step-2">
                <!-- begin fieldset -->
                <fieldset>
                    <div class="form-group">
                        <label class="control-label">Kepada</label>
                        <input class="form-control" type="text" name="edaran_kepada_awal" value="{{ $awal? $awal: (old('edaran_kepada_awal')? old('edaran_kepada_awal'): "Kepada Yth :") }}" />
                    </div>
                    <div class="note note-default">
                        <label class="control-label">Tujuan</label>
                        <table class="table">
                            <tbody id="tujuan">
                               @if ($tujuan)
                               @foreach ($tujuan as $tujuan)
                               <tr>
                                   <td>
                                       <select class="form-control edaran_kepada_tujuan m-t-5" name="edaran_kepada_tujuan[]" style='width: 100%;'>
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
                            <a class="btn btn-warning btn-sm" href="javascript:;" style='cursor:pointer;' onclick="addRekanan()">Tambah Tujuan</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Di</label>
                        <input class="form-control" type="text" name="edaran_kepada_akhir" value="{{ $akhir? $akhir: (old('edaran_kepada_akhir')? old('edaran_kepada_akhir'): "di Tempat") }}" />
                    </div>

                </fieldset>
                <!-- end fieldset -->
            </div>
            @endif
            <div id="step-3" class="p-0">
                <fieldset class="">
                    <textarea class="form-control" rows="3" id="editor2"  name="edaran_isi">{{ $aksi == 'Edit'? $data->edaran_isi: old('edaran_isi') }}</textarea>
                </fieldset>
            </div>
            @if ($edit == 1)
            <div id="step-4">
                <fieldset>
                    <div class="form-group input-group-sm">
                        <label class="control-label">Tanda Tangan</label>
                        <select class="form-control selectpicker" name="edaran_pejabat" data-live-search="true" data-style="btn-info" data-width="100%">
                            @foreach($pengguna as $row)
                            <option value="{{ $row->pengguna_id }}" {{ ($aksi == 'Edit' && $data->pengguna_nama == $row->pengguna_nama? 'selected': (old('gambar_nama') == $row->gambar_nama? 'selected': '')) }}>{{ $row->jabatan_nama }} - {{ $row->pengguna_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group input-group-sm">
                        <label class="control-label">Jenis Tanda Tangan</label>
                        <select class="form-control selectpicker" name="edaran_jenis_ttd" data-live-search="true" data-style="btn-info" data-width="100%">
                            <option value="1" {{ ($aksi == 'Edit' && $data->edaran_ttd == 1? 'selected': (old('edaran_jenis_ttd') == 1? 'selected': '')) }}>QR Code</option>
                            <option value="2" {{ ($aksi == 'Edit' && $data->edaran_ttd != 1? 'selected': (old('edaran_jenis_ttd') == 1? 'selected': '')) }}>Gambar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tembusan</label>
                        <textarea class="form-control" id="editor3" rows="3" name="edaran_tembusan" >{{ $aksi == 'Edit'? $data->edaran_tembusan: old('edaran_tembusan') }}</textarea>
                    </div>
                </fieldset>
            </div>
            @endif
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
	<script src="/assets/plugins/smartwizard/dist/js/jquery.smartWizard.js"></script>
    <script src="/assets/plugins/ckeditor4/ckeditor.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
    <script>
        var id = 0;
        $(document).ready(function(){
            select2();
        });

        function addRekanan(){
            $("#tujuan").append("<tr>\
                <td>\
                    <select class='form-control edaran_kepada_tujuan m-t-5' name='edaran_kepada_tujuan[]' id='edaran_kepada_tujuan" + id + "' style='width: 100%;'></select>\
                </td>\
	            <td style='width: 5px'>\
	                <a onclick='delRekanan(this)' href='javascript:;' class='m-t-5 btn btn-danger btn-xs'>\
						<i class='fa fa-times'></i>\
					</a>\
	            </td>\</tr>");
            select2("#edaran_kepada_tujuan" + id );
            id++;
        }

        CKEDITOR.replace( 'editor2' );
        CKEDITOR.replace( 'editor3' , {
            height: '150px',
        });

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
                        url: "/edaran/hapus/lampiran?file=" + id,
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

        $('#wizard').smartWizard({
            selected: 0,
            theme: 'default',
            transitionEffect:'',
            transitionSpeed: 0,
            useURLhash: false,
            showStepURLhash: false,
            toolbarSettings: {
                toolbarPosition: 'bottom',
                toolbarExtraButtons: [
                    $('<a></a>').text('Simpan').addClass('btn btn-success').on('click', function(){
                        $('form[name="form-wizard"]').submit();
                    }),
                    $('<a></a>').text('Batal').addClass('btn btn-danger').on('click', function(){
                        window.history.back();
                    }),
                ]
            }
        });

	    function select2(elmt = '.edaran_kepada_tujuan'){
            $(elmt).select2({
                minimumInputLength: 1,
                ajax:{
                    url: '/datarekanan/cari',
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
                                id: item.rekanan_nama,
                                text: item.rekanan_nama
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
