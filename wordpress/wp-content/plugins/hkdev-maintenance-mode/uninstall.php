<?php
// Make sure uninstallation is triggered
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

 //Uninstall - removing plugin options
function hkdev_delete_plugin() {
    delete_option('hkdev_mm');
}
hkdev_delete_plugin();