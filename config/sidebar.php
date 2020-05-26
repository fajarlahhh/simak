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
		'id' => 'dashboard',
		'url' => '/'
	],[
		'icon' => 'fas fa-cog',
		'title' => 'Setup',
		'url' => 'javascript:;',
		'id' => 'setup',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datajabatan',
			'id' => '/datajabatan',
			'title' => 'Data Jabatan'
        ],[
			'url' => '/datapengguna',
			'id' => '/datapengguna',
			'title' => 'Data Pengguna'
        ],]
	],[
		'icon' => 'fas fa-paper-plane',
		'title' => 'Surat Keluar',
		'id' => 'datasurat',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/edaran',
			'id' => 'edaran',
			'title' => 'Edaran'
		],[
			'url' => '/sk',
			'id' => 'sk',
			'title' => 'Surat Keputusan'
		],[
			'url' => '/suratpengantar',
			'id' => 'suratpengantar',
			'title' => 'Surat Pengantar'
		],[
			'url' => '/surattugas',
			'id' => 'surattugas',
			'title' => 'Surat Tugas'
		],[
			'url' => '/undangan',
			'id' => 'undangan',
			'title' => 'Undangan'
		]]
	],[
		'icon' => 'fas fa-inbox',
		'title' => 'Surat Masuk',
		'id' => 'suratmasuk',
		'url' => '/suratmasuk'
	],[
		'icon' => 'fas fa-file-alt',
		'title' => 'Template',
		'id' => 'template',
		'url' => 'javascript:;',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/templateedaran',
			'id' => 'templateedaran',
			'title' => 'Edaran'
		],[
			'url' => '/templatesk',
			'id' => 'templatesk',
			'title' => 'Surat Keputusan'
		],[
			'url' => '/templatesuratpengantar',
			'id' => 'templatesuratpengantar',
			'title' => 'Surat Pengantar'
		],[
			'url' => '/templatesurattugas',
			'id' => 'templatesurattugas',
			'title' => 'Surat Tugas'
		],[
			'url' => '/templateundangan',
			'id' => 'templateundangan',
			'title' => 'Undangan'
		]]
	]]
];
