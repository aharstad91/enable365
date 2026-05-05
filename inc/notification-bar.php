<?php
/**
 * Notification bar — server-side render of the global announcement bar.
 *
 * Called directly from header.php immediately after <body>. Does not hook into
 * wp_body_open to avoid activating latent GTM4WP/Piwik handlers (see plan).
 *
 * Toggle-reset dismissal: each save bumps notification_bar_id (UUID v4) and
 * the bar re-shows for previously-dismissed visitors whose localStorage holds
 * the old id.
 *
 * @package Enable365
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the notification bar (or nothing, if disabled / empty / ACF unloaded).
 *
 * Emits a single <style>, the bar markup with a server-side --prehide class,
 * and a single inline IIFE that compares the bar id to localStorage and
 * either hides the bar pre-paint or unhides it.
 */
function enable365_render_notification_bar() {
	// Read raw wp_options rows. Bypasses ACFML's per-language wrapping on
	// get_field() — both text fields are designed to coexist (one per
	// language, picked by the render based on WPML current language) so the
	// values are language-agnostic at the storage layer.
	$enabled = get_option( 'options_notification_bar_enabled' );
	if ( ! $enabled ) {
		return;
	}

	$current_lang = apply_filters( 'wpml_current_language', null );
	$text_no      = (string) get_option( 'options_notification_bar_text_no', '' );
	$text_en      = (string) get_option( 'options_notification_bar_text_en', '' );
	$text         = ( $current_lang === 'en' ) ? $text_en : $text_no;

	if ( empty( trim( strip_tags( $text ) ) ) ) {
		return;
	}

	$theme = (string) get_option( 'options_notification_bar_theme', 'brand' );
	if ( ! in_array( $theme, array( 'brand', 'dark' ), true ) ) {
		$theme = 'brand';
	}

	$bar_id = (string) get_option( 'options_notification_bar_id', '' );
	if ( empty( $bar_id ) && function_exists( 'wp_generate_uuid4' ) ) {
		$bar_id = wp_generate_uuid4();
		update_option( 'options_notification_bar_id', $bar_id, false );
	}
	if ( empty( $bar_id ) ) {
		return;
	}

	$allowed_html = array(
		'a'      => array(
			'href'   => true,
			'title'  => true,
			'target' => true,
			'rel'    => true,
		),
		'strong' => array(),
		'em'     => array(),
		'br'     => array(),
		'span'   => array( 'class' => true ),
		'i'      => array(
			'class'       => true,
			'aria-hidden' => true,
		),
		'p'      => array( 'class' => true ),
	);
	$sanitized = wp_kses( (string) $text, $allowed_html );

	$is_english = ( $current_lang === 'en' );
	$aria_close = $is_english ? 'Close notification bar' : 'Lukk varslingsbar';
	$aria_label = $is_english ? 'Site announcement' : 'Sidevarsel';
	?>
	<style id="notification-bar-styles">
		.notification-bar { position: relative; width: 100%; padding: 12px 56px 12px 24px; box-sizing: border-box; font-size: 15px; line-height: 1.5; }
		.notification-bar--prehide { visibility: hidden; }
		.notification-bar__inner { max-width: 1200px; margin: 0 auto; }
		.notification-bar__inner p { margin: 0; }
		.notification-bar__inner a { color: inherit; text-decoration: underline; }
		.notification-bar__close { position: absolute; top: 50%; right: 6px; transform: translateY(-50%); background: transparent; border: 0; color: inherit; font-size: 24px; line-height: 1; cursor: pointer; padding: 0; min-width: 44px; min-height: 44px; outline: 2px solid transparent; outline-offset: 2px; border-radius: 2px; }
		.notification-bar__close:focus-visible { outline-color: #FFFFFF; }
		.notification-bar--brand { background: #AA1010; color: #FFFFFF; }
		.notification-bar--dark { background: #0D2538; color: #FFFFFF; }
		.notification-bar.is-dismissing { transform: scaleY(0); transform-origin: top; transition: transform 200ms ease-out; }
		@media (max-width: 640px) {
			.notification-bar { padding: 12px 56px 12px 16px; }
			.notification-bar__close { top: 4px; right: 4px; transform: none; }
		}
		@media (prefers-reduced-motion: reduce) {
			.notification-bar.is-dismissing { transition: none; transform: none; }
		}
		@media print { .notification-bar { display: none; } }
	</style>
	<div class="notification-bar notification-bar--<?php echo esc_attr( $theme ); ?> notification-bar--prehide" data-bar-id="<?php echo esc_attr( $bar_id ); ?>" role="region" aria-label="<?php echo esc_attr( $aria_label ); ?>">
		<div class="notification-bar__inner"><?php echo $sanitized; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses already applied with custom allowlist ?></div>
		<button type="button" class="notification-bar__close" aria-label="<?php echo esc_attr( $aria_close ); ?>">&times;</button>
	</div>
	<script id="notification-bar-script">
	(function () {
		try {
			var bar = document.querySelector('.notification-bar');
			if (!bar) return;
			var barId = bar.getAttribute('data-bar-id');
			var dismissedId = null;
			try { dismissedId = window.localStorage.getItem('enable365_bar_dismissed_id'); } catch (e) {}
			if (dismissedId && dismissedId === barId) {
				bar.style.display = 'none';
				return;
			}
			bar.classList.remove('notification-bar--prehide');
			var btn = bar.querySelector('.notification-bar__close');
			if (!btn) return;
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			btn.addEventListener('click', function () {
				try { window.localStorage.setItem('enable365_bar_dismissed_id', barId); } catch (e) {}
				var finalize = function () {
					bar.style.display = 'none';
					var main = document.querySelector('main');
					if (main) {
						if (!main.hasAttribute('tabindex')) main.setAttribute('tabindex', '-1');
						try { main.focus({ preventScroll: true }); } catch (e) { main.focus(); }
					}
				};
				if (reduceMotion) { finalize(); return; }
				bar.classList.add('is-dismissing');
				var done = false;
				var once = function () { if (done) return; done = true; finalize(); };
				bar.addEventListener('transitionend', once, { once: true });
				setTimeout(once, 350);
			});
		} catch (e) {}
	})();
	</script>
	<?php
}
