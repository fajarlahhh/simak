<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'menu' => [[
		'icon' => 'fas fa-th-large',
		'title' => 'Dashboard',
		'url' => '/'
	],[
		'icon' => 'fas fa-mail-bulk',
		'title' => 'Data Surat',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/permintaanbarang',
			'title' => 'Permintaan Barang'
		],[
			'url' => '/proposal',
			'title' => 'Proposal'
		],[
			'url' => '/suratedaran',
			'title' => 'Surat Edaran'
		],[
			'url' => '/suratkeluar',
			'title' => 'Surat Keluar'
		],[
			'url' => '/suratkeputusan',
			'title' => 'Surat Keputusan'
		],[
			'url' => '/suratmasuk',
			'title' => 'Surat Masuk'
		],[
			'url' => '/suratperjanjian',
			'title' => 'Surat Perjanjian'
		],[
			'url' => '/undangan',
			'title' => 'Undangan'
		]]
	],[
		'icon' => 'fas fa-file-invoice-dollar',
		'title' => 'Pengelolaan Proposal',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/inputproposal',
			'title' => 'Input Proposal'
		],[
			'url' => '/verifikasiproposal',
			'title' => 'Verifikasi Proposal'
		],[
			'url' => '/validasiproposal',
			'title' => 'Validasi Proposal'
		],[
			'url' => '/pembatalanproposal',
			'title' => 'Pembatalan Proposal'
		]]
	],[
		'icon' => 'fas fa-cog',
		'title' => 'Setup',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datapenyimpanan',
			'title' => 'Data Penyimpanan'
		],[
			'url' => '/datapengguna',
			'title' => 'Data Pengguna'
		]]
	]]
];
