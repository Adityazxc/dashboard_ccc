<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <?php switch ($role) {
        case "CCC":
            $home = "ccc";
            break;
        case "Agen":
            $home = "agen";
            break;
        case "Marketing":
            $home = "marketing";
            break;
        case "Finance":
            $home = "finance";
            break;
        case "CS":
            $home = "cs";
            break;
        case "Admin";
            $home = "admin";
            break;
        case "Kacab";
            $home = "finance";
            break;
        default:
            echo "tidak ada role";
    } ?>
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url($home); ?>">
        <img src="<?= base_url('public/img/voucher.png') ?>" alt="E-Voucher Image" width="30" height="30">
        <div class="sidebar-brand-text mx-3">E-Voucher </div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
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
    <!-- CCC Role -->
    <?php if ($role == 'CCC'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'ccc' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('ccc'); ?>">
                    <i class="bi bi-shop"></i>
                    <span>Dashboard Retail</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'ccc_corp' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('ccc_corp'); ?>">
                    <i class="bi bi-building"></i>
                    <span>Dashboard Corporate</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'ccc' && $this->uri->segment(2) == 'view_add_data') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('ccc/view_add_data'); ?>">
                <i class="bi bi-plus-lg"></i>
                <span>Add Data Retail</span>
            </a>
        </li>

        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'ccc_corp' && $this->uri->segment(2) == 'view_add_data') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('ccc_corp/view_add_data'); ?>">
                <i class="bi bi-plus-lg"></i>
                <span>Add Data Corporate</span>
            </a>
        </li>

        <!-- Redeem Voucher Link -->
    <?php endif; ?>
    <!-- Agen Role -->
    <?php if ($role == 'Agen'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'agen' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('agen'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </li>

        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'agen' && $this->uri->segment(2) == 'redeem') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('agen/redeem'); ?>">
                <i class="bi bi-cash"></i>
                <span>Reedem Voucher</span>
            </a>
        </li>
    <?php endif; ?>
    <!-- Marketing Role -->
    <?php if ($role == 'Marketing'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'marketing' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('marketing'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </li>

        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'marketing' && $this->uri->segment(2) == 'send_email') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('marketing/send_email'); ?>">
                <i class="bi bi-send"></i>
                <span>Send Email</span>
            </a>
        </li>
        <!-- Redeem Voucher Link -->
    <?php endif; ?>

    <!-- Fianance Role -->
    <?php if ($role == 'Finance'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'finance' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('finance'); ?>">
                    <i class="bi bi-shop"></i>
                    <span>Dashboard Retail</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'finance_corp' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('finance_corp'); ?>">
                    <i class="bi bi-building"></i>
                    <span>Dashboard Corporate</span>
                </a>
            </div>
        </li>
        <!-- Redeem Voucher Link -->
    <?php endif; ?>
    <?php if ($role == 'Kacab'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'finance' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('finance'); ?>">
                    <i class="bi bi-shop"></i>
                    <span>Dashboard Retail</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'finance_corp' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('finance_corp'); ?>">
                    <i class="bi bi-building"></i>
                    <span>Dashboard Corporate</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard User</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'user_log') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('admin/user_log'); ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>User Log</span>
                </a>
            </div>
        </li>
        <!-- Redeem Voucher Link -->
    <?php endif; ?>
    <!-- CS Role -->
    <?php if ($role == 'CS'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'cs' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('cs'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </li>

        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'cs' && $this->uri->segment(2) == 'send_email') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('cs/send_email'); ?>">
                <i class="bi bi-send"></i>
                <span>Send Email</span>
            </a>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'cs' && $this->uri->segment(2) == 'report') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('cs/report'); ?>">
                <i class="bi bi-flag"></i>
                <span>Report</span>
            </a>
        </li>
        <!-- Redeem Voucher Link -->
    <?php endif; ?>

    <!-- Admin Role -->
    <?php if ($role == 'Admin'): ?>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == '') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard User</span>
                </a>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'user_log') ? 'link_active' : ''; ?>">
            <div>
                <a class="nav-link" href="<?php echo base_url('admin/user_log'); ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>User Log</span>
                </a>
            </div>
        </li>

        <!-- CCC Page -->
        <li class="nav-item ">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#cccPage" aria-expanded="true"
                aria-controls="collapseUtilities">
                <i class="bi bi-bank"></i>
                <span>CCC Page</span>
            </a>
            <div id="cccPage" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">CCC</h6>
                    <a class="collapse-item" href="<?php echo base_url('ccc'); ?>">
                        <i class="bi bi-shop"></i>
                        <span>Dashboard Retail</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('ccc_corp'); ?>">
                        <i class="bi bi-building"></i>
                        <span>Dashboard Corporate</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('ccc/view_add_data'); ?>">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add Data Retail</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('ccc_corp/view_add_data'); ?>">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add Data Corporate</span>
                    </a>
                </div>
            </div>
        </li>

        <!-- Marketing Page -->
        <li class="nav-item ">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#marketingPage" aria-expanded="true"
                aria-controls="collapseUtilities">
                <i class="bi bi-megaphone"></i>
                <span>Marketing Page</span>
            </a>
            <div id="marketingPage" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Marketing</h6>
                    <a class="collapse-item" href="<?php echo base_url('marketing'); ?>">
                        <i class="bi bi-shop"></i>
                        <span>Dashboard Retail</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('marketing/send_email'); ?>">
                        <i class="bi bi-send"></i>
                        <span>Send Email</span>
                    </a>
                </div>
            </div>
        </li>


        <!-- CS Page -->
        <li class="nav-item ">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#csPage" aria-expanded="true"
                aria-controls="collapseUtilities">
                <i class="bi bi-headset"></i>
                <span>CS Page</span>
            </a>
            <div id="csPage" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">CS</h6>
                    <a class="collapse-item" href="<?php echo base_url('cs'); ?>">
                        <i class="bi bi-shop"></i>
                        <span>Dashboard Retail</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('cs/send_email'); ?>">
                        <i class="bi bi-send"></i>
                        <span>Send Email</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('cs/report'); ?>">
                        <i class="bi bi-flag"></i>
                        <span>Report</span>
                    </a>
                </div>
            </div>
        </li>
        <!-- Finance Page -->
        <li class="nav-item ">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#financePage" aria-expanded="true"
                aria-controls="collapseUtilities">
                <i class="fas fa-coins"></i>
                <span>Finance Page</span>
            </a>
            <div id="financePage" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Finance</h6>
                    <a class="collapse-item" href="<?php echo base_url('finance'); ?>">
                        <i class="bi bi-shop"></i>
                        <span>Dashboard Retail</span>
                    </a>
                    <a class="collapse-item" href="<?php echo base_url('finance_corp'); ?>">
                        <i class="bi bi-building"></i>
                        <span>Dashboard Corporate</span>
                    </a>
                </div>
            </div>
        </li>
        <li
            class="nav-item <?php echo ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'redeem') ? 'link_active' : ''; ?>">
            <a class="nav-link" href="<?php echo base_url('agen/redeem'); ?>">
                <i class="bi bi-cash"></i>
                <span>Reedem Voucher</span>
            </a>
        </li>
        <!-- Redeem Voucher Link -->
    <?php endif; ?>


    <!-- Add Data Link -->
    <li class="nav-item" style="margin:10px">
        <a role="button" style="width: 100%;" class="btn btn-danger logout-btn" style="color:#FFFF; decoration:none;"
            href="<?php echo base_url('auth/logout'); ?>"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </li>

</ul>