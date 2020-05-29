<!-- begin #sidebar -->
<div id="sidebar" class="sidebar">
	<!-- begin sidebar scrollbar -->
	<div data-scrollbar="true" data-height="100%">
		<!-- begin sidebar user -->
		<ul class="nav">
			<li class="nav-profile">
				<a href="javascript:;" data-toggle="nav-profile">
					<div class="cover with-shadow"></div>
					<img src="/assets/img/user/user.png" alt="" class="image"/>
					<div class="info">
						<b class="caret pull-right"></b>
						{{ $nama_pegawai }}
                        <small>{{ Auth::user()->pengguna_id }}</small>
                        <br><span class="badge bg-blue-darker">{{ ucFirst(Auth::user()->getRoleNames()[0]) }}</span>
					</div>
				</a>
			</li>
			<li>
				<ul class="nav nav-profile">
					<li><a href="javascript:;" onclick="help()"><i class="fa fa-question-circle"></i> Bantuan</a></li>
					<li><a href="/logout"><i class="fa fa-sign-out-alt"></i> Log Out</a></li>
				</ul>
			</li>
		</ul>
		<!-- end sidebar user -->
		<!-- begin sidebar nav -->
		<ul class="nav">
			<li class="nav-header">Navigation</li>
			@php
				$currentUrl = (Request::path() != '/') ? '/'. explode('/', Request::path())[0] : '/';

				function renderSubMenu($value, $currentUrl) {
					$subMenu = '';
					$GLOBALS['sub_level'] += 1 ;
					$GLOBALS['active'][$GLOBALS['sub_level']] = '';
					$currentLevel = $GLOBALS['sub_level'];
					foreach ($value as $key => $menu) {
						if(Auth::user()->can(substr($menu['url'], 1)) || Auth::user()->getRoleNames()[0] == 'super-admin'){
							$GLOBALS['subparent_level'] = '';

							$subSubMenu = '';
							$hasSub = (!empty($menu['sub_menu'])) ? 'has-sub' : '';
							$hasCaret = (!empty($menu['sub_menu'])) ? '<b class="caret pull-right"></b>' : '';
							$hasTitle = (!empty($menu['title'])) ? $menu['title'] : '';
							$hasHighlight = (!empty($menu['highlight'])) ? '<i class="fa fa-paper-plane text-theme m-l-5"></i>' : '';

							if (!empty($menu['sub_menu'])) {
								$subSubMenu .= '<ul class="sub-menu">';
								$subSubMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
								$subSubMenu .= '</ul>';
							}

							$active = ($currentUrl == $menu['url']) ? 'active' : '';

							if ($active) {
								$GLOBALS['parent_active'] = true;
								$GLOBALS['active'][$GLOBALS['sub_level'] - 1] = true;
							}
							if (!empty($GLOBALS['active'][$currentLevel])) {
								$active = 'active';
							}
							$subMenu .= '
								<li class="'. $hasSub .' '. $active .'">
									<a href="'. (Auth::user()->can(strtolower($hasTitle)) || Auth::user()->role('super-admin')? $menu['url']: '#') .'">'. $hasCaret . $hasTitle . $hasHighlight .'</a>
									'. $subSubMenu .'
								</li>
							';
						}
					}
					return $subMenu;
				}

				foreach (config('sidebar.menu') as $key => $menu) {
					if(Auth::user()->can(strtolower($menu['id'])) || Auth::user()->getRoleNames()[0] == 'super-admin' || $menu['id'] == 'dashboard'){
						$GLOBALS['parent_active'] = '';

						$hasSub = (!empty($menu['sub_menu'])) ? 'has-sub' : '';
						$hasCaret = (!empty($menu['caret'])) ? '<b class="caret"></b>' : '';
						$hasIcon = (!empty($menu['icon'])) ? '<i class="'. $menu['icon'] .'"></i>' : '';
						$hasImg = (!empty($menu['img'])) ? '<div class="icon-img"><img src="'. $menu['img'] .'" /></div>' : '';
						$hasLabel = (!empty($menu['label'])) ? '<span class="label label-theme m-l-5">'. $menu['label'] .'</span>' : '';
						$hasTitle = (!empty($menu['title'])) ? '<span>'. $menu['title'] . $hasLabel .'</span>' : '';
						$hasBadge = (!empty($menu['badge'])) ? '<span class="badge pull-right">'. $menu['badge'] .'</span>' : '';

						$subMenu = '';

						if (!empty($menu['sub_menu'])) {
							$GLOBALS['sub_level'] = 0;
							$subMenu .= '<ul class="sub-menu">';
							$subMenu .= renderSubMenu($menu['sub_menu'], $currentUrl);
							$subMenu .= '</ul>';
						}
						$active = ($currentUrl == $menu['url']) ? 'active' : '';
						$active = (empty($active) && !empty($GLOBALS['parent_active'])) ? 'active' : $active;
						echo '
							<li class="'. $hasSub .' '. $active .'">
								<a href="'. $menu['url'] .'">
									'. $hasImg .'
									'. $hasBadge .'
									'. $hasCaret .'
									'. $hasIcon .'
									'. $hasTitle .'
								</a>
								'. $subMenu .'
							</li>
						';
					}
				}
			@endphp
			<!-- begin sidebar minify button -->
			<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fas fa-angle-double-left"></i></a></li>
			<!-- end sidebar minify button -->
		</ul>
		<!-- end sidebar nav -->
	</div>
	<!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->

@push('scripts')
<script>
	function help() {
        Swal.fire({
            icon: 'info',
            title: 'Bantuan',
            text: "IT {{ env('APP_COMPANY') }}"
        });
	}
</script>
@endpush
