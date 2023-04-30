<?php
// -----
// Part of the "Reviews Updated" plugin by lat9
// Copyright (C) 2023, Vinos de Frutas Tropicales
//
// Last updated: v2.0.0
//
class zcObserverReviewsUpdated extends base
{
    protected
        $in_product_reviews_write_page = false,
        $guest_customer_id = false;

    public function __construct()
    {
        global $current_page_base;

        // -----
        // If "Reviews Updated" is not installed or disabled or the customer's not currently on the
        // 'product_reviews_write' page, nothing further to be done.
        //
        if (!defined('REVIEWS_BY_GUESTS') || REVIEWS_BY_GUESTS === '0' || !defined('REVIEW_NAME_MIN_LENGTH') || $current_page_base !== FILENAME_PRODUCT_REVIEWS_WRITE) {
            return;
        }

        // -----
        // Additionally, if a non-guest customer's currently logged in, the page will processed 'normally'.
        //
        if (zen_is_logged_in() && !zen_in_guest_checkout()) {
            return;
        }

        // -----
        // If currently in guest-checkout, save the guest-customer's pseudo-customer_id.
        //
        if (zen_in_guest_checkout()) {
            $this->guest_customer_id = $_SESSION['customer_id'] ?? false;
        }

        // -----
        // Otherwise, attach to the various events needed to enable a product's review to
        // be written by a guest-customer.
        //
        $this->attach(
            $this,
            [
                'NOTIFY_SEFU_INTERCEPT',

                'NOTIFY_ZEN_IS_LOGGED_IN',

                'NOTIFY_HEADER_START_PRODUCT_REVIEWS_WRITE',
                'NOTIFY_REVIEWS_WRITE_CAPTCHA_CHECK',
                'NOTIFY_HEADER_END_PRODUCT_REVIEWS_WRITE',
            ]
        );
    }

    // -----
    // This observer has registered to watch for the following events ONLY IF it's
    // currently installed and enabled and the session is not associated with a
    // non-guest, logged-in customer!
    //
    public function update (&$class, $eventID, $p1, &$p2, &$p3, &$p4, &$p5, &$p6, &$p7)
    {
        switch ($eventID) {
            // -----
            // The *first* request on the 'product_reviews_write' page indicate that a
            // customer is logged in.  That's the one issued by the header_php.php file
            // to determine whether a customer needs to log in to write a review.
            //
            // Once that override's been processed, detach from this event so that the
            // product-pricing function(s) will provide the correct output without PHP issues.
            //
            case 'NOTIFY_ZEN_IS_LOGGED_IN':
                if ($this->in_product_reviews_write_page === true) {
                    $p2 = true;
                    $this->detach($this, ['NOTIFY_ZEN_IS_LOGGED_IN']);
                }
                break;

            // -----
            // Any zen_redirect requests on-page, make sure that the bogus, i.e. set to 0,
            // $_SESSION['customer_id'] is removed and any guest-related 'customer_id' is
            // restored.
            //
            case 'NOTIFY_SEFU_INTERCEPT':
                if (empty($_SESSION['customer_id'])) {
                    if ($this->guest_customer_id !== false) {
                        $_SESSION['customer_id'] = $this->guest_customer_id;
                    } else {
                        unset($_SESSION['customer_id']);
                    }
                }
                break;

            // -----
            // At the start of the 'product_reviews_write' page's header_php.php
            // processing, indicate that we're now in the page-related processing
            // so the the zen_is_logged_in notifications will 'activate'.
            //
            // Set a global variable (used by the plugin's language-file
            // override for the page) so that it's aware that the language-related
            // definitions should be set for the page's template use; also used by
            // the template-overrides associated with this plugin.
            //
            // Set a bogus, i.e. 0, customer_id in the session so that the page's
            // header_php.php processing (pulling customer information from the database
            // won't result in a PHP or MySQL error.  This value will be removed
            // at the end of the page's processing or upon any zen_redirect issued
            // during the page's processing.
            //
            case 'NOTIFY_HEADER_START_PRODUCT_REVIEWS_WRITE':
                $this->in_product_reviews_write_page = true;
                $GLOBALS['guest_reviews_enabled'] = true;
                $_SESSION['customer_id'] = 0;
                break;

            // -----
            // Issued by the page's header_php.php when a review has been submitted and
            // the submitted fields are being checked for validity.  Ensure that the
            // 'review_name' field has sufficient characters.
            //
            // At this point, we'll *assume* that the remaining checks on the form's
            // data will be successful and add the customers_firstname, customers_lastname,
            // and customers_email_address fields to the $customers object for use in the
            // review's database storage and email processing.
            //
            case 'NOTIFY_REVIEWS_WRITE_CAPTCHA_CHECK':
                global $review_name, $msgStack, $error, $customer;

                $review_name = $_POST['review_name'] ?? '';
                $review_name = strip_tags($review_name);
                if (strlen($review_name) < REVIEW_NAME_MIN_LENGTH) {
                    $error = true;
                    $messageStack->add('review_text', JS_REVIEW_NAME, 'error');
                }
                $customer->fields = [
                    'customers_firstname' => $review_name,
                    'customers_lastname' => '',
                    'customers_email_address' => 'Not supplied',
                ];
                break;

            // -----
            // Initialize the $review_name variable, used by the page's template override,
            // and remove the bogus customer_id set to prevent PHP/MySQL errors during the page's
            // header processing.
            //
            case 'NOTIFY_HEADER_END_PRODUCT_REVIEWS_WRITE':
                global $review_name;

                $review_name = strip_tags($review_name ?? '');
                if (empty($_SESSION['customer_id'])) {
                    if ($this->guest_customer_id !== false) {
                        $_SESSION['customer_id'] = $this->guest_customer_id;
                    } else {
                        unset($_SESSION['customer_id']);
                    }
                }
                break;

            default:
                break;
        }
    }
}
