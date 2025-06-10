<!-- Sidebar -->

<div class="sidebar" data-background-color="white">
	<style>
		.link_active {
			background-color: #E9F5FE;
			margin: 10px;
			border-radius: 0.35rem;
			transition: background-color 0.3s;
		}

		.link_active a,
		.link_active a span,
		.link_active a i {
			color: #0C7FDA;
		}
	</style>
	<!-- Menambahkan Font Awesome via CDN -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

	<div class="sidebar-logo">
		<!-- Logo Header -->
		<div class="logo-header" data-background-color="white">
			<!-- <img src="public/img/supaneko.png" style="height:auto; max-width:100%; display:block;"> -->
			<img src="<?= base_url("public/img/camera.svg") ?>" style="height:50px; max-width:100%; display:block;">
			Validasi

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

	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<ul class="nav nav-secondary">

				<?php if (
					$role == "Koordinator"
					|| $role == "Admin"
					|| $role == "Super User"
					|| $role == "CS"
					|| $role == "CCC"
					|| $role == "BPS"
					|| $role == "HC"
					|| $role == "Kepala Cabang BDO2"
					|| $role == "Kepala Cabang"
					|| $role == "PAO"
					|| $role == "BBP"
				): ?>
					<!-- Jika password tidak cocok, nonaktifkan link -->
					<li class="nav-item <?php echo ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('dashboard'); ?>">
							<i class="bi bi-speedometer"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li class="nav-item <?php echo ($this->uri->segment(1) == 'admin') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="fas fa-check-circle"></i>
							<span>Validasi</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ($role == "Super User" || $role == "HC"): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'courier' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('courier'); ?>">
							<i class="fas fa-shipping-fast"></i>
							<span>Kurir</span>
						</a>
					</li>

				<?php endif; ?>
				<?php if ($role == "Super User"): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'users' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('users'); ?>">
							<i class="bi bi-person"></i>
							<span>Users</span>
						</a>
					</li>
				<?php endif; ?>


				<li class="nav-item" style="margin:10px">
					<a role="button" style="width: 100%;" class="btn btn-danger logout-btn"
						style="color:#FFFF; decoration:none;" href="<?php echo base_url('auth/logout'); ?>"><i
							class="bi bi-box-arrow-left" style="color:#fff;"></i>
						<p class="putih" style="color:#fff;">Logout</p>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- End Sidebar -->