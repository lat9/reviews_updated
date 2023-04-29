<?php
// -----
// Part of the "Reviews Updated" plugin by lat9
// Copyright (C) 2023, Vinos de Frutas Tropicales
//
// Last updated: v2.0.0
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

$autoLoadConfig[200][] = [
  'autoType' => 'init_script',
  'loadFile' => 'init_reviews_updated_admin.php'
];
