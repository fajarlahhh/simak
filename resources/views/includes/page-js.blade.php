<script src="/assets/js/bundle.js"></script>
<script src="/assets/js/theme/default.min.js"></script>
<script src="/assets/js/apps.min.js"></script>
<script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
<script src="/assets/plugins/gritter/js/jquery.gritter.min.js"></script>
<script src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.2/firebase-database.js"></script>
<script src="/assets/js/firebase.js"></script>

<script>
	$(document).ready(function() {
		App.init();

        $('[data-toggle="tooltip"]').tooltip();

		@if(Session::get('swal_pesan'))
            swal("{{ Session::get('swal_judul') }}", "{{ Session::get('swal_pesan') }}", "{{ Session::get('swal_tipe') }}");
        @endif

        @if(Session::get('gritter_judul'))
	    setTimeout(function() {
			$.gritter.add({
				title: '{{ Session::get('gritter_judul').$nama_pegawai.'!' }}',
				text: '{{ Session::get('gritter_teks') }}',
				image: '{{ Session::get('gritter_gambar') }}',
				sticky: true,
				time: '',
				class_name: 'my-sticky-class'
			});
		}, 1000);
	    @endif
	});

</script>

@stack('scripts')
