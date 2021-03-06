@extends('pages.main')

@section('title', ' | Review')

@push('css')
	<link href="{{ url('/public/assets/plugins/parsleyjs/src/parsley.css') }}" rel="stylesheet" />
	<link href="{{ url('/public/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Review</li>
	<li class="breadcrumb-item active">{{ $data->review_surat_nomor }}</li>
@endsection

@section('header')
	<h1 class="page-header">Review <small>{{ $data->review_surat_nomor }}</small></h1>
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
		<form action="{{ route('review.simpan') }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@method('PUT')
			@csrf
			<div class="panel-body">
                <input type="hidden" name="redirect" value="{{ $back }}">
                <input type="hidden" name="review_surat_nomor" value="{{ $data->belum_review->review_surat_nomor }}" required/>
                <input type="hidden" name="review_nomor" value="{{ $data->belum_review->review_nomor }}" required/>
                <div class="row">
                    <div class="col-xl-8 m-b-10 m-t-5" >
                        <div class=" border overflow-auto" style="height: 900px;">
                            <style type="text/css">
                                p, table {
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                }
                                hr {
                                    border: 1px solid;
                                }

                                .v-top{
                                    vertical-align: text-top;
                                }
                            </style>
                            <div class="bg-white p-20" style="font-family: 'Times New Roman', Times, serif;">
                                {!! $data->kop_isi !!}
                                @include($halaman)
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        @if ($history)
                        <div class="note note-danger">
                            <h5>History Review</h5>
                            <div class="overflow-auto" style="height: 200px">
                                <table class="table">
                                    <tr>
                                        <th>Catatan</th>
                                        <th>Verifikator</th>
                                        <th>Waktu</th>
                                    </tr>
                                    @foreach ($history as $history)
                                    <tr>
                                        <td>
                                            {!! $history->review_catatan !!}
                                        </td>
                                        <td>
                                            {!! $history->jabatan->jabatan_nama !!}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($history->updated_at)->isoFormat('LL') }}
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                            </div>
                        </div>
                        @endif
                        <div class="note note-primary">
                            <div class="form-group">
                                <label class="control-label">Hasil</label>
                                <select class="form-control " name="fix" id="fix" data-live-search="true" data-style="btn-info" data-width="100%">
                                    <option value="1">Revisi</option>
                                    @if (Auth::user()->jabatan->jabatan_pimpinan == 1)
                                    <option value="5">Menyetujui & Terbitkan</option>
                                    @else
                                    @if (Auth::user()->jabatan->jabatan_verifikator == 1)
                                    <option value="4">Teruskan ke Pimpinan</option>
                                    @else
                                    @if ($atasan->jabatan_pimpinan == 1)
                                    <option value="3">Teruskan ke Verifikator</option>
                                    @else
                                    <option value="2">Teruskan ke Atasan</option>
                                    @endif
                                    @endif
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" id="catatan">
                                <label class="control-label">Catatan</label>
                                <textarea class="form-control" rows="15" id="editor1" name="review_catatan"></textarea>
                            </div>
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
	<script src="{{ url('/public/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ url('/public/assets/plugins/parsleyjs/dist/parsley.js') }}"></script>
    <script src="{{ url('/public/assets/plugins/ckeditor4/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'editor1',{
            height: 200
        } );

        $("#fix").change(function () {
            if(this.value == 1){
                $("#catatan").show();
            }else{
                $("#catatan").hide();
            }
        })
    </script>
@endpush
