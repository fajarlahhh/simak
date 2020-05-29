@extends('pages.setup.main')

@section('title', ' | Salam')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Salam</li>
@endsection

@section('header')
	<h1 class="page-header">Salam</h1>
@endsection

@section('subcontent')
    <form action="{{ route('salam.simpan') }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    @csrf
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        </div>
                        <h4 class="panel-title">Salam Pembuka</h4>
                    </div>
                    <div class="panel-body panel-form">
                        <textarea id="editor1" name="salam_pembuka" rows="10" cols="80">
                            {{ $data ? $data->salam_pembuka: old('salam_pembuka') }}
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    @csrf
                    <!-- begin panel-heading -->
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        </div>
                        <h4 class="panel-title">Salam Penutup</h4>
                    </div>
                    <div class="panel-body panel-form">
                        <textarea id="editor2" name="salam_penutup" rows="10" cols="80">
                            {{ $data ? $data->salam_penutup: old('salam_penutup') }}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
        @role('user|super-admin|supervisor')
        <input type="submit" value="Simpan" class="btn btn-sm btn-success m-r-3"  />
        @endrole
        <div class="pull-right">
            This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
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
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
    <script src="/assets/plugins/ckeditor4/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'editor1' );
        CKEDITOR.replace( 'editor2' );
    </script>
@endpush
