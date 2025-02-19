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

	<div class="sidebar-logo">
		<!-- Logo Header -->
		<div class="logo-header" data-background-color="white">

			<a href="<?= base_url('admin') ?>" class="logo">
				<img src="public/img/supaneko.png" style="height:auto; max-width:100%; display:block;">
			</a>
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

				<?php if ($role == 'Upper'): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="bi bi-speedometer"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'product' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('product'); ?>">
							<i class="fas fa-tshirt"></i>
							<span>Product</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'selling' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('selling'); ?>">
							<i class="fa fa-shopping-cart"></i>
							<span>Order</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'stock' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('stock'); ?>">
							<i class="fas fa-warehouse"></i>
							<span>Stock</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'spending' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('spending'); ?>">
							<i class="bi bi-cash"></i>
							<span>Spending</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'users' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('users'); ?>">
							<i class="fa fa-user"></i>
							<span>Users</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'reset_password' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('reset_password'); ?>">
							<i class="fa fa-key"></i>
							<span>Reset Password</span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ($role == 'Admin'): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="bi bi-speedometer"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'product' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('product'); ?>">
							<i class="fas fa-tshirt"></i>
							<span>Product</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'selling' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('selling'); ?>">
							<i class="fa fa-shopping-cart"></i>
							<span>Order</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'stock' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('stock'); ?>">
							<i class="fas fa-warehouse"></i>
							<span>Stock</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'reset_password' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('reset_password'); ?>">
							<i class="fa fa-key"></i>
							<span>Reset Password</span>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($role == 'Production'): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="bi bi-speedometer"></i>
							<span>Dashboard</span>
							<!-- <?php echo '<<span class="mr-2 d-none d-lg-inline text-gray-600 small"> hehe ' . $role . ' f</small>'; ?> -->
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'product' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('product'); ?>">
							<i class="fas fa-tshirt"></i>
							<span>Product</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'selling' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('selling'); ?>">
							<i class="fa fa-shopping-cart"></i>
							<span>Order</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'stock' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('stock'); ?>">
							<i class="fas fa-warehouse"></i>
							<span>Stock</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'reset_password' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('reset_password'); ?>">
							<i class="fa fa-key"></i>
							<span>Reset Password</span>
						</a>
					</li>
					<!-- <li
						class="nav-item <?php echo ($this->uri->segment(1) == 'Spending' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('selling'); ?>">
							<i class="fa fa-money"></i>
							<span>Spending</span>
						</a>
					</li>							 -->
				<?php endif; ?>
				<?php if ($role == 'Finance'): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="bi bi-speedometer"></i>
							<span>Dashboard</span>
							<!-- <?php echo '<<span class="mr-2 d-none d-lg-inline text-gray-600 small"> hehe ' . $role . ' f</small>'; ?> -->
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'product' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('product'); ?>">
							<i class="fas fa-tshirt"></i>
							<span>Product</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'selling' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('selling'); ?>">
							<i class="fa fa-shopping-cart"></i>
							<span>Order</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'reset_password' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('reset_password'); ?>">
							<i class="fa fa-key"></i>
							<span>Reset Password</span>
						</a>
					</li>
					<!-- <li
						class="nav-item <?php echo ($this->uri->segment(1) == 'Spending' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('selling'); ?>">
							<i class="fa fa-money"></i>
							<span>Spending</span>
						</a>
					</li>							 -->
				<?php endif; ?>
				<?php if ($role == 'Marketing'): ?>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('admin'); ?>">
							<i class="bi bi-speedometer"></i>
							<span>Dashboard</span>
							<!-- <?php echo '<<span class="mr-2 d-none d-lg-inline text-gray-600 small"> hehe ' . $role . ' f</small>'; ?> -->
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'product' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('product'); ?>">
							<i class="fas fa-tshirt"></i>
							<span>Product</span>
						</a>
					</li>
					<li
						class="nav-item <?php echo ($this->uri->segment(1) == 'reset_password' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
						<a href="<?php echo base_url('reset_password'); ?>">
							<i class="fa fa-key"></i>
							<span>Reset Password</span>
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