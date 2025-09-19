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
			<span class="logo-text">Validasi</span>


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
					<li class="nav-item <?php echo ($this->uri->segment(1) == 'leaderboard') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('leaderboard'); ?>">
							<i class="fa-solid fa-ranking-star"></i>
							<span>Leaderboard</span>
						</a>
					</li>

					<li class="nav-item <?php echo ($this->uri->segment(1) == 'admin') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="fas fa-check-circle"></i>
							<span>Validasi</span>
						</a>
					</li>
					<!-- <li class="nav-item <?php echo ($this->uri->segment(1) == 'pod') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('pod'); ?>">
							<i class="fa-solid fa-money-bill-transfer"></i>
							<span>Peyetoran POD</span>
						</a>
					</li> -->

					<li class="nav-item  <?php echo ($this->uri->segment(1) == 'pod') ? 'active' : ''; ?>">
						<a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
							href="#collapsePOD" role="button" aria-expanded="false" aria-controls="collapsePOD">

							<div class="d-flex align-items-center">
								<i class="fa-solid fa-money-bill-transfer"></i>
								<span>Penyetoran POD</span>
							</div>

							<i class="fas fa-chevron-down small"></i>
						</a>

						<div class="collapse <?php echo ($this->uri->segment(1) == 'pod') ? 'active' : ''; ?>"
							id="collapsePOD">
							<ul class="nav flex-column ms-3">
								<li class="nav-item">
									<a href="<?= base_url('pod/dashboard_pod') ?>"
										class="nav-link <?php echo ($this->uri->segment(1) == 'pod') ? 'active' : ''; ?>">
										<i class="bi bi-speedometer"></i> Dashboard POD
									</a>
								</li>
								<li class="nav-item">
									<a href="<?= base_url('pod') ?>"
										class="nav-link <?php echo ($this->uri->segment(2) == 'history') ? 'active' : ''; ?>">
										<i class="fas fa-money-check-alt"></i> List Penyetoran

									</a>
								</li>
							</ul>
						</div>
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