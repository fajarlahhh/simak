@extends('pages.main')

@section('title', ' | Tracking Disposisi')

@section('page')
	<li class="breadcrumb-item active">Tracking Disposisi</li>
@endsection

@push('css')
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Tracking Disposisi</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
            <div class="form-group">
                <label class="control-label text-white">Cari Nomor/Asal/Perihal</label>
                <select class="form-control" id="select2" style='width: 100%;'>
                </select>
            </div>
        </div>
		<div class="panel-body" >
            <div id="report-container" >
                <table class="table">
                    <tr>
                        <td class="width-150">
                            <label class="control-label">Nomor Surat</label>
                        </td>
                        <td class="width-10"> : </td>
                        <td id="nomor"></td>
                    </tr>
                    <tr>
                        <td >
                            <label class="control-label">Tanggal Masuk</label>
                        </td>
                        <td> : </td>
                        <td id="tgl_masuk"></td>
                    </tr>
                    <tr>
                        <td >
                            <label class="control-label">Tanggal Masuk</label>
                        </td>
                        <td> : </td>
                        <td id="tgl_surat"></td>
                    </tr>
                    <tr>
                        <td >
                            <label class="control-label">Asal</label>
                        </td>
                        <td> : </td>
                        <td id="asal"></td>
                    </tr>
                    <tr>
                        <td >
                            <label class="control-label">Perihal</label>
                        </td>
                        <td> : </td>
                        <td id="perihal"></td>
                    </tr>
                    <tr>
                        <td >
                            <label class="control-label">Rangkuman Isi Surat</label>
                        </td>
                        <td> : </td>
                        <td id="rangkuman"></td>
                    </tr>
                </table>
            </div>
		</div>
	</div>
@endsection

@push('scripts')
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<script>
    $("#select2").on("change", function(e) {
		$("#nomor").text($(this).select2('data')[0]['nomor']);
		$("#tgl_masuk").text($(this).select2('data')[0]['tgl_masuk']);
		$("#tgl_surat").text($(this).select2('data')[0]['tgl_surat']);
		$("#asal").text($(this).select2('data')[0]['asal']);
		$("#perihal").text($(this).select2('data')[0]['perihal']);
		$("#rangkuman").text($(this).select2('data')[0]['rangkuman']);
    });

    function format(data) {
        if (!data.id) { return data.text; }
        var $data = $("<div style='color: #151e1e;'>" + data.nomor + "<br><small>Asal : " + data.asal + "<br>Perihal : " + data.perihal + "<small></div>");
        return $data;
    }

    $("#select2").select2({
        minimumInputLength: 1,
        templateResult: format,
        ajax:{
            url: '/trackingdisposisi/cari',
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
                        id: item.surat_masuk_nomor,
                        nomor: item.surat_masuk_nomor,
                        tgl_masuk: item.surat_masuk_tanggal_masuk,
                        tgl_surat: item.surat_masuk_tanggal_surat,
                        asal: item.surat_masuk_asal,
                        rangkuman: item.surat_masuk_keterangan,
                        perihal: item.surat_masuk_perihal
                    });
                });
                return{
                    results: results
                };
            },
            cache: true,
        },
    });
</script>
@endpush
