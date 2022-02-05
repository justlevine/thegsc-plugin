<?php
/**
 * Configures The Events Calendar PostTypes.
 *
 * @package TheGSC
 * @since 0.0.1
 */

namespace TheGSC\PostTypes;

use TheGSC\Interfaces\Hookable;
use WP_Post;

/**
 * Class - Events
 */
class Events implements Hookable {
	/**
	 * {@inheritDoc}
	 */
	public function initialize() : void {
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			// phpcs:ignore
			add_action( 'add_meta_boxes_tribe_events', [ $this, 'add_gf_events_meta_box' ] );
			add_action( 'save_post', [ $this, 'save_gf_events_meta_box' ] );
			add_action( 'event_tickets_rsvp_tickets_generated', [ $this, 'set_custom_from_email' ] );
			add_action( 'event_tickets_woocommerce_tickets_generated', [ $this, 'set_custom_from_email' ] );
			add_filter( 'tribe_events_admin_show_cost_field', '__return_true', 100 );
		}
	}

	/**
	 * Add GF meta box to event edit page.
	 */
	public function add_gf_events_meta_box() : void {
		add_meta_box( 'gf_events_meta_box', 'Gravity Forms Shortcode', [ $this, 'gsc_gf_events_markup' ], 'tribe_events', 'normal', 'core', null );
	}

	/**
	 * Add GF form to event.
	 *
	 * @param WP_Post $post the post object.
	 */
	public function gsc_gf_events_markup( WP_Post $post ) : void {
		$current_id    = get_post_meta( $post->ID, 'gf_shortcode', true );
		$current_title = get_post_meta( $post->ID, 'gf_section_title', true );
		wp_nonce_field( basename( __FILE__ ), 'meta-box-nonce' );
		?>

		<div>
			<label for="gf_section_title">Section Title</label>
			<input name="gf_section_title" type="text" value="<?php echo esc_attr( $current_title ); ?>">
			<label for="gf_shortcode">Gravity Form ID</label>
			<input name="gf_shortcode" type="number" value="<?php echo esc_attr( $current_id ); ?>">
			<br />
		</div>
		<?php
	}

	/**
	 * Save GF form field to post meta
	 *
	 * @param int $postid post id.
	 */
	public function save_gf_events_meta_box( $postid ) : void {
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
	public function set_custom_from_email() : void {
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
