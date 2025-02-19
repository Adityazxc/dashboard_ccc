<div class="main-header">
	<div class="main-header-logo">
		<!-- Logo Header -->
		<div class="logo-header" data-background-color="dark">

			<div class="nav-toggle">
				<button class="btn btn-toggle toggle-sidebar">
					<i class="gg-menu-right"></i>
				</button>
				<button class="btn btn-toggle sidenav-toggler">
					<i class="gg-menu-left"></i>
				</button>
			</div>
			<button class="topbar-toggler more">
				<i class="gg-more-vertical-alt"></i>
			</button>

		</div>
		<!-- End Logo Header -->
	</div>
	<!-- Navbar Header -->
	<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">

		<div class="container-fluid">
			<nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">

			</nav>

			<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">


				<li class="nav-item topbar-user dropdown hidden-caret">
					<?php
					$account_name = $this->session->userdata('account_name');
					$role = $this->session->userdata('role');

					echo '<li class="nav-item dropdown no-arrow">';
					echo '<span class="mr-2 d-none d-lg-inline text-gray-600 small"> Hi, ' . $account_name . '<b> (' . $role . ' )</b></span>';
					echo '<li class="nav-item dropdown no-arrow">';

					// if($username =='')
					?>

				</li>
			</ul>
		</div>
	</nav>
	<!-- End Navbar -->
</div>