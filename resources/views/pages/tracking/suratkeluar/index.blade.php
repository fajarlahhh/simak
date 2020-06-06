@extends('pages.tracking.main')

@section('title', ' | Tracking Surat Keluar')

@section('page')
	<li class="breadcrumb-item active">Surat Keluar</li>
@endsection

@push('css')
	<link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Tracking Surat Keluar</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
            <div class="form-group">
                <label class="control-label text-white">Cari Nomor/Sifat/Perihal</label>
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
        $("#report-container").load("/trackingsuratkeluar/detail/" + $(this).select2('data')[0]['jenis'] + "?no=" + $(this).select2('data')[0]['id']);
    });

    function format(data) {
        if (!data.id) { return data.text; }
        var $data = $("<div><strong>Nomor : " + data.nomor + "</strong><div class='pull-right'>" + data.tanggal + "</div><br>Bidang : " + data.bidang + "<br>Perihal : " + data.perihal + "<br><strong>Status : " + (data.fix == 1? "Disetujui & Diterbitkan": "Belum Disetujui") + "</strong><br>Dibuat Oleh : " + data.operator + "</div>");
        return $data;
    }

    $("#select2").select2({
        minimumInputLength: 1,
        templateResult: format,
        ajax:{
            url: '/trackingsuratkeluar/cari',
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
                        id: item.nomor,
                        nomor: item.nomor,
                        tanggal: item.tanggal,
                        sifat: item.sifat,
                        perihal: item.perihal? item.perihal: "-",
                        fix: item.fix,
                        operator: item.operator,
                        jenis: item.jenis,
                        bidang: item.bidang.bidang_nama
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
