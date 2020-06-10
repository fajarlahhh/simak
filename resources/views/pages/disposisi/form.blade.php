@extends('pages.main')

@section('title', ' | Form Disposisi')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Disposisi</li>
	<li class="breadcrumb-item active">{{ $data['nomor'] }}</li>
@endsection

@section('header')
	<h1 class="page-header">Disposisi <small>{{ $data['nomor'] }}</small></h1>
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
		<form action="{{ route('disposisi.'.($bawahan->count() > 0? 'simpan': 'selesai')) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@method('PUT')
			@csrf
			<div class="panel-body">
                <input type="hidden" name="disposisi_surat_id" value="{{ $data['surat'] }}">
                <input type="hidden" name="redirect" value="{{ $back }}">
                <input type="hidden" name="disposisi_id" value="{{ $data['id'] }}">
                <input type="hidden" name="disposisi_jenis_surat" value="{{ $data['jenis'] }}">
                <div class="row">
                    <div class="col-xl-7 m-b-10 m-t-5" >
                        <div class=" border overflow-auto">
                            @include('includes.component.pdf', ['file' => $data['file']])
                        </div>
                    </div>
                    <div class="col-xl-5">
                        @if ($data['atasan'] != null)
                        <div class="note note-danger">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="width-80">Dari</th>
                                    <th class="width-10">:</th>
                                    <td>{{ $data['atasan'] }}</td>
                                </tr>
                                <tr>
                                    <th>Sifat</th>
                                    <th>:</th>
                                    <td>{{ $data['sifat'] }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan</th>
                                    <th>:</th>
                                    <td>{{ $data['catatan'] }}</td>
                                </tr>
                                <tr>
                                    <th>Hasil</th>
                                    <th>:</th>
                                    <td>{{ $data['hasil'] }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu</th>
                                    <th>:</th>
                                    <td>{{ \Carbon\Carbon::parse($data['created_at'])->isoFormat('LLL') }}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- end #accordion -->
                        @endif
                        <div class="note note-primary">
                            <h4>
                                Form
                                @if ($bawahan->count() > 0)
                                Disposisi
                                @else
                                Tanggapan
                                @endif
                            </h4>
                            @foreach ($bawahan as $row)
                            <div class='checkbox checkbox-css col-md-4'>
                                <input type='checkbox' id='cssCheckbox{{ $row->jabatan_id }}' name='jabatan_id[]' value='{{ $row->jabatan_id }}' />
                                <label for='cssCheckbox{{ $row->jabatan_id }}' class='p-l-5'>{{ $row->jabatan_nama }}</label>
                            </div>
                            @endforeach
                            <hr>
                            @if ($bawahan->count() > 0)
                            <div class="form-group">
                                <label class="control-label">Sifat</label>
                                <input class="form-control" type="text" name="disposisi_sifat" value="{{ old('disposisi_sifat') }}" required/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Catatan</label>
                                <textarea class="form-control" rows="5" name="disposisi_catatan" required>{{ old('disposisi_catatan') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Hasil</label>
                                <input class="form-control" type="text" name="disposisi_hasil" value="{{ old('disposisi_hasil') }}" required/>
                            </div>
                            @else
                            <div class="form-group">
                                <label class="control-label">Tanggapan</label>
                                <textarea class="form-control" rows="5" name="disposisi_catatan" required>{{ old('disposisi_catatan') }}</textarea>
                            </div>
                            @endif
                            @role('user|super-admin|supervisor')
                            <input type="submit" value="Simpan" class="btn btn-sm btn-success m-r-3"  />
                            @endrole
                            <a href="{{ $back }}" class="btn btn-sm btn-danger">Batal</a>
                        </div>
                    </div>
                </div>
			</div>
            <div class="panel-footer">
                &nbsp;
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
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
@endpush
