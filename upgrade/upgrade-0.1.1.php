<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_0_1_1($module)
{
    return $module->uninstallOverrides() && $module->installOverrides();
}
