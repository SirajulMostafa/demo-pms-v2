<?php

include './config/config.php';
unset_session('admin_name');
unset_session('admin_email');
unset_session('admin_id');
session_destroy();

$link = base_url() . "index.php";
redirect($link);
?>