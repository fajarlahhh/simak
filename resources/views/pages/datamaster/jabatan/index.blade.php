@extends('pages.setup.main')

@section('title', ' | Data Jabatan')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Data Jabatan</li>
@endsection

@section('header')
	<h1 class="page-header">Data Jabatan</h1>
@endsection

@section('subcontent')
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-2 col-lg-2 col-xl-2 col-xs-12">
                @role('user|super-admin|supervisor')
                <div class="form-inline">
                    <a href="{{ route('datajabatan.tambah') }}" class="btn btn-primary">Tambah</a>
                </div>
                @endrole
            </div>
            <div class="col-md-10 col-lg-10 col-xl-10 col-xs-12">
                <form id="frm-cari" action="{{ route('datajabatan') }}" method="GET">
                    <div class="form-inline pull-right">
                        <div class="input-group">
                            <input type="text" class="form-control cari" name="cari" placeholder="Cari Nama Jabatan" aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2" value="{{ $cari }}">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"><i class="fad fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Jabatan</th>
                        <th>Bidang</th>
                        <th>Atasan</th>
                        <th>Pimpinan</th>
                        <th>Struktural</th>
                        <th>Verifikator</th>
                        <th class="width-90"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td class="align-middle">{{ $row->jabatan_nama }}</td>
                        <td class="align-middle">{{ $row->bidang->bidang_nama }}</td>
                        <td class="align-middle">{{ $row->jabatan_parent? $row->atasan->jabatan_nama: "" }}</td>
                        <td class="align-middle">{{ $row->jabatan_pimpinan == 0? "": "Ya" }}</td>
                        <td class="align-middle">{{ $row->jabatan_struktural == 0? "": "Ya" }}</td>
                        <td class="align-middle">{{ $row->jabatan_verifikator == 0? "": "Ya" }}</td>
                        <td class="text-center">
                            @role('user|super-admin|supervisor')
                            <a href="/datajabatan/edit/{{ $row->jabatan_id }}" id='btn-del' class='btn btn-grey btn-xs m-r-3'><i class='fas fa-edit'></i></a>
                            <a href="javascript:;" onclick="hapus('{{ $row->jabatan_id }}', '{{ $row->jabatan_nama }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
                            @endrole
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer form-inline">
        <div class="col-md-6 col-lg-10 col-xl-10 col-xs-12">
            {{ $data->links() }}
        </div>
        <div class="col-md-6 col-lg-2 col-xl-2 col-xs-12">
            <label class="pull-right">Jumlah Data : {{ $data->total() }}</label>
        </div>
        This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
    </div>
</div>
<div class="modal fade" id="modal-detail">
	<div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pratinjau Usulan Program/Jabatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div id="modal-detail-form"></div>
        </div>
	</div>
</div>

@endsection

@push('scripts')
<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script>
    $(".cari").change(function() {
         $("#frm-cari").submit();
    });

    function hapus(id, ket) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda akan menghapus jabatan ' + ket + '',
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
                    url: "/datajabatan/hapus/" + id,
                    type: "POST",
                    data: {
                        "_method": 'DELETE'
                    },
                    success: function(data){
                        location.reload(true);
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
</script>
@endpush
