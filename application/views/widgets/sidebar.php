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

		/* Sembunyikan teks logo saat sidebar minimize */
		.sidebar.minimized .logo-text {
			display: none;
		}
	</style>
	<script>
		$(document).ready(function () {
			$('.toggle-sidebar, .sidenav-toggler').on('click', function () {
				$('.sidebar').toggleClass('minimized');
			});
		});
	</script>


	<!-- Menambahkan Font Awesome via CDN -->


	<!-- Untuk Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<!-- Untuk Font Awesome 4 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


	<div class="sidebar-logo">
		<!-- Logo Header -->
		<div class="logo-header" data-background-color="white">
			<!-- <img src="public/img/supaneko.png" style="height:auto; max-width:100%; display:block;"> -->
			<img src="<?= base_url("public/img/camera.svg") ?>" style="height:50px; max-width:100%; display:block;">
			<span class="logo-text">DASHBOARD CCC</span>


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
					|| $role == "POD"
					|| $role == "Admin BDO2"
					|| $role == "Koordinator BDO2"
				): ?>
					<!-- FIRST MILE -->
					<li class="nav-header text-muted small mt-3 ms-2">FIRST MILE</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'first_mile' && !$this->uri->segment(2)) ? 'active' : ''; ?>">
						<a href="<?= base_url('first_mile'); ?>">
							<i class="fas fa-home"></i>
							<span>Dashboard</span>
						</a>
					</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'first_mile' && $this->uri->segment(2) == 'import') ? 'active' : ''; ?>">
						<a href="<?= base_url('first_mile/import'); ?>">
							<i class="fas fa-upload"></i>
							<span>Import</span>
						</a>
					</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'first_mile' && $this->uri->segment(2) == 'status_shipment_fm') ? 'active' : ''; ?>">
						<a href="<?= base_url('first_mile/status_shipment_fm'); ?>">
							<i class="fas fa-truck"></i>
							<span>Status</span>
						</a>
					</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'first_mile' && $this->uri->segment(2) == 'performance_shipment_fm') ? 'active' : ''; ?>">
						<a href="<?= base_url('first_mile/performance_shipment_fm'); ?>">
							<i class="fas fa-chart-bar"></i>
							<span>Performance</span>
						</a>
					</li>

					<li class="nav-item <?= ($this->uri->segment(1) == 'customers_fm') ? 'active' : ''; ?>">
						<a href="<?= base_url('customers_fm'); ?>">
							<i class="fas fa-user"></i>
							<span>Customers</span>
						</a>
					</li>


					<!-- LAST MILE -->
					<li class="nav-header text-muted small mt-4 ms-2">LAST MILE</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'last_mile' && !$this->uri->segment(2)) ? 'active' : ''; ?>">
						<a href="<?= base_url('last_mile'); ?>">
							<i class="fas fa-home"></i>
							<span>Dashboard</span>
						</a>
					</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'last_mile' && $this->uri->segment(2) == 'import') ? 'active' : ''; ?>">
						<a href="<?= base_url('last_mile/import'); ?>">
							<i class="fas fa-upload"></i>
							<span>Import</span>
						</a>
					</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'last_mile' && $this->uri->segment(2) == 'status_shipment_lm') ? 'active' : ''; ?>">
						<a href="<?= base_url('last_mile/status_shipment_lm'); ?>">
							<i class="fas fa-truck"></i>
							<span>Status</span>
						</a>
					</li>

					<li
						class="nav-item <?= ($this->uri->segment(1) == 'last_mile' && $this->uri->segment(2) == 'performance_shipment_lm') ? 'active' : ''; ?>">
						<a href="<?= base_url('last_mile/performance_shipment_lm'); ?>">
							<i class="fas fa-chart-bar"></i>
							<span>Performance</span>
						</a>
					</li>

					<li class="nav-item <?= ($this->uri->segment(1) == 'customers_lm') ? 'active' : ''; ?>">
						<a href="<?= base_url('customers_lm'); ?>">
							<i class="fas fa-user"></i>
							<span>Customers</span>
						</a>
					</li>

				<?php endif; ?>


				<?php if ($role == "Super User"): ?>
					<li class="nav-header text-muted small mt-4 ms-2">Setting</li>
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