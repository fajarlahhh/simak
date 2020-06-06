@extends('pages.tracking.main')

@section('title', ' | Tracking Surat Masuk')

@section('page')
	<li class="breadcrumb-item active">Surat Masuk</li>
@endsection

@push('css')
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Tracking Surat Masuk</h1>
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
            <div id="report-container">
            </div>
		</div>
	</div>
@endsection

@push('scripts')
<script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
<script>
    $("#select2").on("change", function(e) {
        //$('#report-container').show();
        $("#report-container").load("/trackingsuratmasuk/detail/" + $(this).select2('data')[0]['id']);
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
            url: '/trackingsuratmasuk/cari',
            dataType: "json",
            delay: 250,
            type : 'GET',
            data: function(params){
                return{
                    cari : params.term
                };
            },
            beforeSend: function() {
                $('#report-container div').hide();
            },
            processResults: function(data){
                var results = [];

                $.each(data, function(index, item){
                    results.push({
                        id: item.surat_masuk_id,
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
