<?php
// -----
// Part of the "Reviews Updated" plugin by lat9
// Copyright (C) 2023, Vinos de Frutas Tropicales
//
// Last updated: v2.0.0
//
// -----
// If the plugin's processing is not enabled, no language overrides needed.
//
if (!isset($GLOBALS['guest_reviews_enabled'])) {
    return [];
}

// -----
// Otherwise, provide the override for the SUB_TITLE_REVIEW definition and add the
// couple of guest-related language definitions.
//
$defines = [
    'SUB_TITLE_FROM' => 'Your name will be displayed as the text you enter in <em>Your Name</em>. For your privacy, we suggest <b>not</b> using your full name.',
    'JS_REVIEW_NAME' => 'Your Name needs to have at least ' . REVIEW_NAME_MIN_LENGTH . ' characters.',
    'TEXT_REVIEW_NAME' => 'Your Name:',
];
return $defines;
