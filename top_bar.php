<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="javascript:void(0)"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <?php $store_info = get_store_info(); ?>
                <span class="m-r-sm text-muted welcome-message">Welcome to <?php echo $store_info->store_title; ?></span>
            </li>

            <?php
            $notification = get_notification();
            $count_notification = count($notification);
            ?>
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell"></i><span class="label label-danger"><?php echo $count_notification; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <?php if ($count_notification > 0): ?>
                        <?php foreach ($notification AS $notify): ?>
                            <li>
                                <a href="">
                                    <div>
                                        <i class="fa fa-warning fa-fw"></i> <?php echo $notify->medicine_name; ?>
                                        <span class="pull-right text-muted small">
                                            <?php if ($notify->medicine_quantity <= 10): ?>
                                                <span class="label label-warning-light">Low quantity</span>
                                            <?php else: ?>
                                                <span class="label label-danger">Expired</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endforeach; ?>
                        <li>
                            <div class="text-center link-block">
                                <a href="<?php echo base_url('views/medicine/stock_alert.php'); ?>">
                                    <strong>See all</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <a href="<?php echo base_url('logout.php'); ?>">
                    <i class="fa fa-sign-out"></i> Log out
                </a>
            </li>
        </ul>
    </nav>
</div>