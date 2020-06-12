@extends('layouts.default')

@section('title', ' | Dashboard')

@section('content')
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
		<li class="breadcrumb-item active">Dashboard</li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Dashboard</h1>
	<!-- end page-header -->

	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="widget widget-stats bg-red">
				<div class="stats-icon"><i class="fa fa-podcast"></i></div>
				<div class="stats-info">
					<h4>Edaran Tahun {{ date ('Y')}}</h4>
					<p class="f-s-16">Total Keseluruhan : {{ number_format($edaran[0]) }}</p>
					<p class="f-s-16">Terbit : {{ number_format($edaran[1]) }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ url('/edaran')}}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="widget widget-stats bg-green">
				<div class="stats-icon"><i class="fa fa-dove"></i></div>
				<div class="stats-info">
					<h4>Surat Pengantar Tahun {{ date ('Y')}}</h4>
					<p class="f-s-16">Total Keseluruhan : {{ number_format($pengantar[0]) }}</p>
					<p class="f-s-16">Terbit : {{ number_format($pengantar[1]) }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ url('/pengantar') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="widget widget-stats bg-warning">
				<div class="stats-icon"><i class="fa fa-dove"></i></div>
				<div class="stats-info">
					<h4>Surat Tugas Tahun {{ date ('Y')}}</h4>
					<p class="f-s-16">Total Keseluruhan : {{ number_format($tugas[0]) }}</p>
					<p class="f-s-16">Terbit : {{ number_format($tugas[1]) }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ url('/tugas') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="widget widget-stats bg-blue">
				<div class="stats-icon"><i class="fa fa-dove"></i></div>
				<div class="stats-info">
					<h4>Undangan Tahun {{ date ('Y')}}</h4>
					<p class="f-s-16">Total Keseluruhan : {{ number_format($undangan[0]) }}</p>
					<p class="f-s-16">Terbit : {{ number_format($undangan[1]) }}</p>
                </div>
                <div class="stats-link">
                    <a href="{{ url('/undangan') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
			</div>
		</div>
	</div>
	<div class="row">
		@if ($auth->jabatan->jabatan_pimpinan == 0 && $review[0]->count() > 0)
		<div class="col-md-4">
			<div class="list-group">
				<a href="#" class="list-group-item list-group-item-action active bg-black">
				  	<strong>Daftar Revisi Surat Keluar</strong>
				</a>
				@foreach ($review[0]->take(5) as $row)
				<a href="{{ url('/'.strtolower($row->review_surat_jenis).'/edit?no='.$row->review_surat_nomor) }}" class="list-group-item list-group-item-action">Surat {{ $row->review_surat_jenis }}, Nomor {{ $row->review_surat_nomor }} <span class="badge badge-{{ $row->jabatan->bidang->warna }} pull-right">{{ $row->jabatan->bidang->bidang_nama }}</span></a>
                @endforeach
                @if ($review[0]->count() > 5)
				<a href="{{ url('/review') }}" class="list-group-item list-group-item-action">
				  	<strong class="text-blue-darker">Lihat Semua {{ $review[0]->count() }} data</strong>
				</a>
                @endif
			  </div>
		</div>
		@endif
		@if ($auth->jabatan->jabatan_struktural == 1)
		@if ($review[1]->count() > 0)
		<div class="col-md-4">
			<div class="list-group">
				<a href="#" class="list-group-item list-group-item-action active bg-black">
				  	<strong>Perlu Review Anda</strong>
				</a>
				@foreach ($review[1]->take(5) as $row)
				<a href="{{ route('review', array('no' => $row->review_surat_nomor, 'tipe' => $row->review_surat_jenis)) }}" class="list-group-item list-group-item-action">Surat {{ $row->review_surat_jenis }}, Nomor {{ $row->review_surat_nomor }} <span class="badge badge-{{ $row->jabatan->bidang->warna }} pull-right">{{ $row->jabatan->bidang->bidang_nama }}</span></a>
				@endforeach
                @if ($review[1]->count() > 5)
				<a href="{{ url('/review') }}" class="list-group-item list-group-item-action">
				  	<strong class="text-blue-darker">Lihat Semua {{ $review[1]->count() }} data</strong>
				</a>
                @endif
			  </div>
		</div>
		@endif
		@if ($disposisi && $disposisi->count() > 0)
		<div class="col-md-4">
			<div class="list-group">
				<a href="#" class="list-group-item list-group-item-action active bg-black">
				  	<strong>Perlu Disposisi Anda</strong>
				</a>
				@foreach ($disposisi->take(5) as $row)
				<a href="{{ route('disposisi', array('id' => $row['id'], 'tipe' => $row['jenis'])) }}" class="list-group-item list-group-item-action">{{ $row['jenis'] }}, Nomor {{ $row['nomor'] }} <span class="badge badge-secondary pull-right">{{ $row['asal'] }}</span></a>
				@endforeach
                @if ($disposisi->count() > 5)
				<a href="{{ url('/disposisi') }}" class="list-group-item list-group-item-action">
				  	<strong class="text-blue-darker">Lihat Semua {{ $disposisi->count() }} data</strong>
				</a>
                @endif
			  </div>
		</div>
		@endif
		@endif
	</div>
@endsection
