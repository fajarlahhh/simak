@extends('pages.template.main')

@section('title', ' | Kop Surat')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Kop Surat</li>
@endsection

@section('header')
	<h1 class="page-header">Kop Surat</h1>
@endsection

@section('subcontent')
    <form action="{{ route('kopsurat.simpan') }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
            @csrf
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
                <h4 class="panel-title">Form</h4>
            </div>
            <div class="panel-body panel-form">
                <textarea id="editor1" name="kop_isi" rows="10" cols="80">
                    {{ $data ? $data->kop_isi: old('kop_isi') }}
                </textarea>
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
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
    <script src="/assets/plugins/ckeditor4/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'editor1', {
            height: '300px',
        } );
    </script>
@endpush
