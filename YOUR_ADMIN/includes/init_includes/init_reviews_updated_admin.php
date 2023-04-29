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

// -----
// If either of the plugin's configuration values aren't yet present, insert them
// into the database at this point.
//
// - REVIEWS_BY_GUESTS, added to Configuration :: Product Info
// - REVIEW_NAME_MIN_LENGTH, added to Configuration :: Minimum Values
//
if (!defined('REVIEWS_BY_GUESTS') || !defined('REVIEW_NAME_MIN_LENGTH')) {
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Enable product reviews by guests?', 'REVIEWS_BY_GUESTS', '0', 'Identifies whether (1) or not (0, the default) your store allows guests to write reviews.', 18, 63, now(), NULL, 'zen_cfg_select_option([\'1\', \'0\'],'),

            ('Product Review Write - Guest Reviewer Name', 'REVIEW_NAME_MIN_LENGTH', '5', 'Minimum length of a guest reviewer\'s name', 2, 14, now(), NULL, NULL)"
    );
}
