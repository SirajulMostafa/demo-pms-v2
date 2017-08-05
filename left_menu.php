<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                        <h3><img src="../../public/images/logo_white.png" width="170px" /></h3>
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> 
                            <span class="block m-t-xs"> 
                                <strong class="font-bold"><?php echo get_session('admin_name'); ?><b class="caret"></b></strong>
                            </span>
                        </span> 
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?php echo base_url(); ?>views/account/profile.php">Profile</a></li>
                        <li><a href="<?php echo base_url('logout.php'); ?>">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <?php echo $store_info->store_name; ?>
                </div>
            </li>

            <?php if (get_session('admin_type') == 'Admin'): ?>
                <li id="menu1">
                    <a href="<?php echo base_url('views/dashboard/index.php'); ?>"><i class="fa fa-th"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li id="menu9">
                    <a href="<?php echo base_url('views/pos/index.php'); ?>"><i class="fa fa-cart-plus"></i> <span class="nav-label">Point Of Sales</span></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-medkit"></i> <span class="nav-label">Medicine</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li id="menu4"><a href="<?php echo base_url('views/medicine/add.php'); ?>"><i class="fa fa-plus-square"></i>Add Medicine</a></li>
                        <?php if ($page_id == 1): ?>
                            <li id="menu6"><a href=""><i class="fa fa-pencil-square"></i>Edit Medicine</a></li>
                        <?php endif; ?>
                        <?php if ($page_id == 3): ?>
                            <li id="menu14"><a href=""><i class="fa fa-warning"></i>Alert Medicines</a></li>
                        <?php endif; ?>
                        <li id="menu5"><a href="<?php echo base_url('views/medicine/list.php'); ?>"><i class="fa fa-list"></i>Medicine List</a></li>
                        <li id="menu3"><a href="<?php echo base_url('views/medicine_category/index.php'); ?>"><i class="fa fa-puzzle-piece"></i>Medicine Category</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-usd"></i> <span class="nav-label">Expenses</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li id="menu7"><a href="<?php echo base_url('views/expense/add.php'); ?>"><i class="fa fa-plus-square"></i>Add Expense</a></li>
                        <li id="menu8"><a href="<?php echo base_url('views/expense/list.php'); ?>"><i class="fa fa-list"></i>All Expenses</a></li>
                    </ul>
                </li>
                <li id="menu11">
                    <a href="<?php echo base_url('views/report/index.php'); ?>"><i class="fa fa-area-chart"></i> <span class="nav-label">Reports</span>  </a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Staffs</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li id="menu12"><a href="<?php echo base_url('views/staff/add.php'); ?>"><i class="fa fa-plus-square"></i>Add Staff</a></li>
                        <?php if ($page_id == 2): ?>
                            <li id="menu13"><a href=""><i class="fa fa-pencil-square"></i>Edit Staff</a></li>
                        <?php endif; ?>
                        <li id="menu10"><a href="<?php echo base_url('views/staff/list.php'); ?>"><i class="fa fa-list"></i>All Staffs</a></li>
                    </ul>
                </li>
                <li id="menu2">
                    <a href="<?php echo base_url('views/system/index.php'); ?>"><i class="fa fa-cogs"></i> <span class="nav-label">System Settings</span></a>
                </li>
            <?php else: ?>
                <li id="menu9">
                    <a href="<?php echo base_url('views/pos/index.php'); ?>"><i class="fa fa-cart-plus"></i> <span class="nav-label">Point Of Sales</span></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>