<?php
/**
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Jul 29 Modified in v1.5.7a $
 */

define('NAVBAR_TITLE', 'Reviews');

define('SUB_TITLE_FROM', 'Written by: ');

//-bof-reviews_updated-lat9  *** 1 of 3 ***
if (zen_is_logged_in() && !zen_in_guest_checkout()) {
    define('SUB_TITLE_REVIEW', 'For your privacy, your name will be displayed as <b>%s %1.1s.</b> when the review is posted.');
} else {
    define('SUB_TITLE_REVIEW', 'Your name will be displayed as the text you enter in <em>Your Name</em>. For your privacy, we suggest <b>not</b> using your full name.');
}
//-eof-reviews_updated-lat9  *** 1 of 3 ***

define('SUB_TITLE_RATING', 'Choose a ranking for this item. 1 star is the worst and 5 stars is the best.');

//-bof-reviews_updated-lat9  *** 2 of 3 ***
define('TEXT_NOTES', 'Notes:');
//-eof-reviews_updated-lat9  *** 2 of 3 ***

define('TEXT_NO_HTML', '<strong>NOTE:</strong>  HTML tags are not allowed.');
define('TEXT_BAD', 'Worst');

define('TEXT_APPROVAL_REQUIRED', '<strong>NOTE:</strong>  Reviews require prior approval before they will be displayed');

define('EMAIL_REVIEW_PENDING_SUBJECT','Product Review Pending Approval: %s');
define('EMAIL_PRODUCT_REVIEW_CONTENT_INTRO','A Product Review for %s has been submitted and requires your approval.'."\n\n");
define('EMAIL_PRODUCT_REVIEW_CONTENT_DETAILS','Review Details: %s');

//-bof-reviews_updated-lat9  *** 3 of 3 ***
define('MESSAGE_REVIEW_SUBMITTED', 'Your review has been submitted.');
define('MESSAGE_REVIEW_SUBMITTED_APPROVAL', 'Your review has been submitted for approval.');
define('MESSAGE_REVIEW_WRITE_NEEDS_LOGIN', 'You need to sign into your account to write a review.');
define('MESSAGE_REVIEW_TEXT_MIN_LENGTH', 'Add a few more words to your review text. A review needs to have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.');

define('JS_REVIEW_NAME', 'Your Name needs to have at least ' . REVIEW_NAME_MIN_LENGTH . ' characters.');
define('TEXT_REVIEW_NAME', 'Your Name:');
define('TEXT_REVIEW_TEXT', 'Tell us what you think and share your opinions with others; please focus your comments on the product.');
//-eof-reviews_updated-lat9  *** 3 of 3 ***
