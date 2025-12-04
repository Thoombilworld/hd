<?php
require __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/core/HomepageBuilder.php';
$theme = new HS_Theme();
include HS_THEME_PATH . '/home.php';
