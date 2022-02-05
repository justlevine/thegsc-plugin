<?php
/**
 * Configures Woocommerce PostTypes.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\PostTypes;

use WC_Order;

class Woocommerce {
	public function initialize() : void {
		// Remove Woocommerce.com Nag.
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

		// Order statuses.
		add_action( 'woocommerce_thankyou_cod', [ $this, 'set_unpaid_orders_as_on_hold' ], 10, 1 );
		add_action( 'woocommerce_thankyou_bacs', [ $this, 'set_unpaid_orders_as_on_hold' ], 10, 1 );
		add_action( 'woocommerce_thankyou_cheque', [ $this, 'set_unpaid_orders_as_on_hold' ], 10, 1 );
		add_action( 'woocommerce_thankyou_paypal', [ $this, 'set_paypal_orders_as_completed' ], 10, 1 );

		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'link_guest_email_to_customer' ], 11 );

		add_filter( 'gettext', [ $this, 'rename_wc_sort_code' ], 10, 3 );
	}

	/**
	 * Mark unpaid orders as on-hold
	 *
	 * @param int $order_id .
	 */
	public function set_unpaid_orders_as_on_hold( int $order_id ) : void {
		$order = new WC_Order( $order_id );
		$order->update_status( 'on-hold' );
	}

	/**
	 * Mark paypal orders as completed.
	 *
	 * @param int $order_id .
	 */
	public function set_paypal_orders_as_completed( int $order_id ) : void {
		$order = new \WC_Order( $order_id );
		$order->update_status( 'completed' );
	}

	/**
	 * Link guest emails to existing customers on checkout.
	 *
	 * @param int $order_id .
	 */
	public function link_guest_email_to_customer( int $order_id ) {
		if ( ! is_user_logged_in() ) {
			$order = new \WC_Order( $order_id );
			$user  = $order->get_user();

			if ( ! $user ) { // if no user is associated with the order.
				$order_email = $order->get_billing_email();

				$userdata = get_user_by( 'email', $order_email );

				// If no email match, check usernames.
				if ( ! isset( $userdata->ID ) ) {
					$userdata = get_user_by( 'login', $order_email );
				}
				// Set customer id to existing user.
				if ( isset( $userdata->ID ) && ! is_wp_error( $userdata->ID ) ) {
					$order->set_customer_id( $userdata->ID );
					update_post_meta( $order_id, '_customer_user', $userdata->ID );
				}
			}
		}
	}

	/**
	 * Rename Sort Code to Branch
	 *
	 * @param string $translation .
	 * @param string $text .
	 * @param string $domain .
	 */
	public function rename_wc_sort_code( $translation, $text, $domain ) {
		if ( 'woocommerce' === $domain ) {
			switch ( $text ) {
				case 'Sort code':
					$translation = 'Branch';
					break;
				case 'BIC':
					$translation = 'SWIFT';
			}
		}

		return $translation;
	}
}
