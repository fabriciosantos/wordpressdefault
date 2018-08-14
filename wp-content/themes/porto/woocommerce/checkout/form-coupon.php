<?php
/**
 * Checkout coupon form
 *
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$porto_woo_version = porto_get_woo_version_number();
$checkout_ver = porto_checkout_version();

if ( ! (version_compare($porto_woo_version, '2.5', '<') ? WC()->cart->coupons_enabled() : wc_coupons_enabled()) ) {
	return;
}
?>

<?php if( 'v2' == $checkout_ver ): ?>
	<div class="accordion mb-3">
		<div class="card card-default">
<?php endif; ?>

			<?php
			if ( empty( WC()->cart->applied_coupons ) ) {
				$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'porto' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'porto' ) . '</a>' );
				if( 'v2' == $checkout_ver ){
					?>
					<div class="card-header arrow">
						<h2 class="card-title m-0">
							<a class="accordion-toggle" data-toggle="collapse" href="#panel-cart-discount" ><?php _e('DISCOUNT CODE', 'porto'); ?></a>
						</h2>
					</div>
				<?php 
				}else{
					wc_print_notice( $info_message, 'notice' );
				}
			}
			?>

			<?php if( 'v2' == $checkout_ver ): ?>
				<div id="panel-cart-discount" class="accordion-body collapse">
					<div class="card-body">
			<?php endif; ?>

						<form class="checkout_coupon" method="post" style="display:none">

							<p class="form-row form-row-first">
								<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'porto' ); ?>" id="coupon_code" value="" />
							</p>

							<p class="form-row form-row-last">
								<button type="submit" class="btn btn-default" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'porto' ); ?>"><?php esc_html_e( 'Apply Coupon', 'porto' ); ?></button>
							</p>

							<div class="clear"></div>
						</form>

			<?php if( 'v2' == $checkout_ver ): ?>
					</div>
				</div>
		</div>
	</div>
<?php endif; ?>