@php
	$headerClass = (!empty($headerInverse)) ? 'navbar-inverse ' : 'navbar-default ';
	$headerMenu = (!empty($headerMenu)) ? $headerMenu : '';
	$hiddenSearch = (!empty($headerLanguageBar)) ? 'hidden-xs' : '';
	$headerMegaMenu = (!empty($headerMegaMenu)) ? $headerMegaMenu : '';
	$headerTopMenu = (!empty($headerTopMenu)) ? $headerTopMenu : '';
@endphp
<!-- begin #header -->
<div id="header" class="header {{ $headerClass }}">
	<!-- begin navbar-header -->
	<div class="navbar-header">
	    <a href="/" class="navbar-brand">
            <b>{{ env('APP_COMPANY_ALIAS') }}</b> {{ config("app.name") }}
	    </a>
	    <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
	    </button>
  	</div>
	<!-- end navbar-header -->

	<!-- begin header-nav -->
	<ul class="navbar-nav navbar-right">
		<li class="dropdown navbar-user">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<img src="/assets/img/user/user.png" alt="" />
				<span class="d-none d-md-inline">{{ $nama_pegawai }}</span> <b class="caret"></b>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a href="#modal-katasandi" id="btn-password" class="dropdown-item" data-toggle="modal">Ganti Kata Sandi</a>
				<div class="dropdown-divider"></div>
				<a href="{{ route('logout') }}" class="dropdown-item" >{{ __('Log Out') }}</a>

			</div>
		</li>
	</ul>
	<!-- end header navigation right -->
</div>
<!-- end #header -->
<div class="modal fade" id="modal-katasandi">
	<div class="modal-dialog">
		<div id="modal-password"></div>
	</div>
</div>

@push('scripts')
	<script type="text/javascript">
		$("#btn-password").click(function(){
	    	$("#modal-password").load("/gantisandi");
	      	$.getScript("/assets/plugins/bootstrap-show-password/dist/bootstrap-show-password.min.js");
	      	$.getScript("/assets/plugins/parsleyjs/dist/parsley.js");
	  	});
	</script>
@endpush
