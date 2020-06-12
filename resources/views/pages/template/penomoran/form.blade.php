@extends('pages.template.main')

@section('title', ' | Penomoran')

@push('css')
	<link href="{{ url('/public/assets/plugins/parsleyjs/src/parsley.css') }}" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Penomoran</li>
@endsection

@section('header')
	<h1 class="page-header">Penomoran</h1>
@endsection

@section('subcontent')
<form action="{{ route('penomoran.simpan') }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        @csrf
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
            <h4 class="panel-title">Form</h4>
        </div>
        <div class="panel-body">
            <div class="alert alert-warning">
                <ul>
                    <li>$urut$ : untuk nomor urut surat</li>
                    <li>$bidang$ : untuk menampilkan nama bidang</li>
                    <li>$tahun$ : untuk menampilkan tahun</li>
                    <li>$bulan$ : untuk menampilkan bulan</li>
                </ul>
            </div>
            <div class="form-group">
                <label class="control-label">Edaran</label>
                <input  class="form-control" type="text" name="edaran" value="{{ $data->count() > 0? $data->filter(function ($q) {
                    return $q->penomoran_jenis == 'edaran';
                })->first()->penomoran_format: old('edaran') }}" required />
            </div>
            <div class="form-group">
                <label class="control-label">SK</label>
                <input  class="form-control" type="text" name="sk" value="{{ $data->count() > 0? $data->filter(function ($q) {
                    return $q->penomoran_jenis == 'sk';
                })->first()->penomoran_format: old('sk') }}" required />
            </div>
            <div class="form-group">
                <label class="control-label">Surat Pengantar</label>
                <input  class="form-control" type="text" name="pengantar" value="{{ $data->count() > 0? $data->filter(function ($q) {
                    return $q->penomoran_jenis == 'pengantar';
                })->first()->penomoran_format: old('pengantar') }}" required />
            </div>
            <div class="form-group">
                <label class="control-label">Surat Tugas</label>
                <input  class="form-control" type="text" name="tugas" value="{{ $data->count() > 0? $data->filter(function ($q) {
                    return $q->penomoran_jenis == 'tugas';
                })->first()->penomoran_format: old('tugas') }}" required />
            </div>
            <div class="form-group">
                <label class="control-label">Undangan</label>
                <input  class="form-control" type="text" name="undangan" value="{{ $data->count() > 0? $data->filter(function ($q) {
                    return $q->penomoran_jenis == 'undangan';
                })->first()->penomoran_format: old('undangan') }}" required />
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
        </div>
        <div class="panel-footer">
            @role('user|super-admin|supervisor')
            <input type="submit" value="Simpan" class="btn btn-sm btn-success m-r-3"  />
            @endrole
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
	<script src="{{ url('/public/assets/plugins/parsleyjs/dist/parsley.js') }}"></script>
@endpush
