@extends('pages.suratkeluar.main')

@section('title', ' | '.$aksi.' Edaran')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
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
<form action="{{ route('edaran.'.strtolower($aksi)) }}" name="form-wizard" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="" enctype="multipart/form-data">
    @csrf
    @method(strtolower($aksi) == 'tambah'? 'POST': 'PUT')
    <div id="wizard">
        <ul>
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
            <li class="col-md-3 col-sm-4 col-6">
                <a href="#step-3">
                    <span class="number">3</span>
                    <span class="info text-ellipsis">
                        Isi
                        <small class="text-ellipsis">Isi Surat</small>
                    </span>
                </a>
            </li>
            <li class="col-md-3 col-sm-4 col-6">
                <a href="#step-4">
                    <span class="number">4</span>
                    <span class="info text-ellipsis">
                        Footer
                        <small class="text-ellipsis">Tanda Tangan, Tembusan</small>
                    </span>
                </a>
            </li>
        </ul>
        <div>
            <div id="step-1">
                <!-- begin fieldset -->
                <fieldset>
                    <div class="row">
                        <div class="col-md-4">@if ($aksi == 'Edit')
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
                                <textarea class="form-control" rows="3" data-parsley-group="step-1" data-parsley-required="true" data-parsley-errors-messages-disabled="" required name="edaran_perihal">{{ $aksi == 'Edit'? $data->edaran_perihal: old('edaran_perihal') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="note note-success">
                                <div class="form-group">
                                    <label class="control-label">Upload Lampiran</label>
                                    <input type="file" class="form-control" accept="image/*" name="lampiran[]" multiple />
                                </div>
                                @if ($data->lampiran)
                                <div class="row">
                                    @foreach ($data->lampiran as $lampiran)
                                    <div class="col-md-4 text-center">
                                        <img src="{{ $lampiran->file }}" alt="" class="width-full">
                                        <br>
                                        <button class="btn btn-danger btn-xs">Hapus</button>
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
                        <textarea class="form-control" id="editor1" rows="3" name="edaran_kepada" >{{ $aksi == 'Edit'? $data->edaran_kepada: old('edaran_kepada') }}</textarea>
                    </div>
                </fieldset>
                <!-- end fieldset -->
            </div>
            <div id="step-3">
                <fieldset>
                    <textarea class="form-control" rows="3" id="editor2"  name="edaran_isi">{{ $aksi == 'Edit'? $data->edaran_isi: old('edaran_isi') }}</textarea>
                </fieldset>
            </div>
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
                        <textarea class="form-control" id="editor3" rows="3" name="edaran_tembusan" required>{{ $aksi == 'Edit'? $data->edaran_tembusan: old('edaran_tembusan') }}</textarea>
                    </div>
                </fieldset>
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
	<script src="/assets/plugins/smartwizard/dist/js/jquery.smartWizard.js"></script>
    <script src="/assets/plugins/ckeditor4/ckeditor.js"></script>
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script>
        CKEDITOR.replace( 'editor1' , {
            height: '200px',
        });
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

        $('#wizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
            var res = $('form[name="form-wizard"]').parsley().validate('step-' + (stepNumber + 1));
            return res;
        });

        $('#wizard').keypress(function( event ) {
            if (event.which == 13 ) {
                $('#wizard').smartWizard('next');
            }
        });
    </script>
@endpush
