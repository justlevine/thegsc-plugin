<?php
/**
 * Configures The Events Calendar PostTypes.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\PostTypes;

class Events {
	public function initialize() : void {
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			define( 'TRIBE_DISABLE_TOOLBAR_ITEMS', true );
			define( 'TRIBE_HIDE_UPSELL', true );
			// add_action( 'add_meta_boxes_tribe_events', [ $this, 'add_gf_events_meta_box' ] );
			add_action( 'save_post', [ $this, 'save_gf_events_meta_box' ] );
			add_action( 'event_tickets_rsvp_tickets_generated', [ $this, 'set_custom_from_email' ] );
			add_action( 'event_tickets_woocommerce_tickets_generated', [ $this, 'set_custom_from_email' ] );
			add_filter( 'tribe_events_admin_show_cost_field', '__return_true', 100 );
		}
	}

	/**
	 * Add GF meta box to event edit page.
	 *
	 * @param WP_Post $post post object.
	 */
	public function add_gf_events_meta_box( $post ) {
		add_meta_box( 'gf_events_meta_box', 'Gravity Forms Shortcode', [ $this, 'gsc_gf_events_markup' ], 'tribe_events', 'normal', 'core', null );
	}

	/**
	 * Save GF form field to post meta
	 *
	 * @param int $postid post id.
	 */
	public function save_gf_events_meta_box( $postid ) {
		if ( ! isset( $_POST['meta-box-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['meta-box-nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $postid ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$form_id = '';
		if ( isset( $_POST['gf_shortcode'] ) ) {
			$form_id = sanitize_text_field( wp_unslash( $_POST['gf_shortcode'] ) );
		}
		update_post_meta( $postid, 'gf_shortcode', $form_id );

		$form_title = '';
		if ( isset( $_POST['gf_section_title'] ) ) {
			$form_title = sanitize_text_field( wp_unslash( $_POST['gf_section_title'] ) );
		}
		update_post_meta( $postid, 'gf_section_title', $form_title );
	}

	/**
	 * Hook the custom email functions
	 */
	public function set_custom_from_email() {
		// RSVP.
		add_filter( 'wp_mail_from', [ $this, 'tickets_email_from' ] );
		add_filter( 'wp_mail_from_name', [ $this, 'tickets_email_from_name' ] );
		// WooCommerce.
		add_filter( 'woocommerce_email_from_address', [ $this, 'tickets_email_from' ] );
		add_filter( 'woocommerce_email_from_name', [ $this, 'tickets_email_from_name' ] );
	}

	/**
	 * Set the tickets sender email address
	 */
	public function tickets_email_from() : string {
		// From Address.
		$address = 'noreply@thegsc.co.il'; // manually enter this.
		$address = sanitize_email( $address );
		return $address;
	}

	/**
	 * Set the tickets sender name
	 */
	public function tickets_email_from_name() : string {
		// From Name based on site settings, or manually enter this too.
		$name = sprintf( 'Tickets from %s', get_option( 'blogname' ) ); // e.g. Tickets from Cliff's Event Site.
		$name = esc_html( $name );

		return $name;
	}
}
