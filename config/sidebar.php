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
		'icon' => 'fas fa-database',
		'title' => 'Data Master',
		'url' => 'javascript:;',
		'id' => 'datamaster',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/databidang',
			'id' => 'databidang',
			'title' => 'Data Bidang'
        ],[
			'url' => '/datajabatan',
			'id' => 'datajabatan',
			'title' => 'Data Jabatan'
        ],[
			'url' => '/dataopd',
			'id' => 'dataopd',
			'title' => 'Data OPD'
        ],[
			'url' => '/gambar',
			'id' => 'gambar',
			'title' => 'Gambar'
        ]]
	],[
		'icon' => 'fas fa-share-alt-square',
		'title' => 'Disposisi',
		'id' => 'disposisi',
		'url' => '/disposisi'
	],[
		'icon' => 'fas fa-check-double',
		'title' => 'Review',
		'id' => 'review',
		'url' => '/review'
	],[
		'icon' => 'fas fa-cog',
		'title' => 'Setup',
		'url' => 'javascript:;',
		'id' => 'setup',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/datapengguna',
			'id' => 'datapengguna',
			'title' => 'Data Pengguna'
        ]]
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
		],/*[
			'url' => '/sk',
			'id' => 'sk',
			'title' => 'Surat Keputusan'
		],*/[
			'url' => '/pengantar',
			'id' => 'pengantar',
			'title' => 'Surat Pengantar'
		],[
			'url' => '/tugas',
			'id' => 'tugas',
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
		'icon' => 'fas fa-palette',
		'title' => 'Template',
		'url' => 'javascript:;',
		'id' => 'template',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/kopsurat',
			'id' => 'kopsurat',
			'title' => 'Kop Surat'
        ],[
			'url' => '/penomoran',
			'id' => 'penomoran',
			'title' => 'Penomoran'
        ],[
			'url' => '/salam',
			'id' => 'salam',
			'title' => 'Salam'
        ],[
			'url' => '/tembusan',
			'id' => 'tembusan',
			'title' => 'Tembusan'
        ]]
	],[
		'icon' => 'fas fa-search',
		'title' => 'Tracking',
		'url' => 'javascript:;',
		'id' => 'tracking',
		'caret' => true,
		'sub_menu' => [[
			'url' => '/trackingsuratkeluar',
			'id' => 'trackingsuratkeluar',
			'title' => 'Surat Keluar'
        ],[
			'url' => '/trackingsuratmasuk',
			'id' => 'trackingsuratmasuk',
			'title' => 'Surat Masuk'
        ]]
	]]
];
