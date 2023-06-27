<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Get Halal
 */

?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->

<!--	--><?php //get_template_part( 'template-parts/footer/footer-widgets' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav aria-label="<?php esc_attr_e( 'Secondary menu', 'gethalal' ); ?>" class="footer-navigation">
				<ul class="footer-navigation-wrapper">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'items_wrap'     => '%3$s',
							'container'      => false,
							'depth'          => 1,
							'link_before'    => '<span>',
							'link_after'     => '</span>',
							'fallback_cb'    => false,
						)
					);
					?>
				</ul><!-- .footer-navigation-wrapper -->
			</nav><!-- .footer-navigation -->
		<?php endif; ?>
		<div class="site-info">
			<div class="site-name">
                <div class="site-logo"><a href="<?php echo site_url() ?>"><img class="footer-logo" src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/footer_logo.png" alt="footer-logo"></a></div>
<!--                <div class="site-description">-->
<!--                    <span>--><?php //echo __('We Eat Halal', 'gethalal'); ?><!--</span>-->
<!--                </div>-->
			</div><!-- .site-name -->
<!--				--><?php
//				printf(
//					/* translators: %s: WordPress. */
//					esc_html__( 'Proudly powered by %s.', 'gethalal' ),
//					'<a href="' . esc_url( __( 'https://wordpress.org/', 'gethalal' ) ) . '">WordPress</a>'
//				);
//				?>
            <div class="footer-menu">
                <div class="footer-menu-title"><?php echo __('About GetHalal', 'gethalal') ?></div>
                <div class="footer-menus">
                    <div class="footer-sub-menu"><a href="<?php echo home_url('/about-us')?>"><?php echo __('About Us', 'gethalal') ?></a></div>
<!--                    <div class="footer-sub-menu"><a href="--><?php //echo home_url('/faq')?><!--">--><?php //echo __('F.A.Q', 'gethalal') ?><!--</a></div>-->
                    <div class="footer-sub-menu"><a href="<?php echo home_url('/jobs')?>"><?php echo __('Careers', 'gethalal') ?></a></div>
                    <div class="footer-sub-menu"><a href="<?php echo home_url('/imprint')?>"><?php echo __('Imprint', 'gethalal') ?></a></div>
                </div>
            </div>
            <div class="footer-menu">
                <div class="footer-menu-title"><?php echo __('Quick Help!', 'gethalal') ?></div>
                <div class="footer-menus">
                    <div class="footer-sub-menu"><a href="<?php echo home_url('/faq')?>"><?php echo __('F.A.Q', 'gethalal') ?></a></div>
                    <div class="footer-sub-menu"><a href="<?php echo home_url('/how-it-works')?>"><?php echo __('How it Works', 'gethalal') ?></a></div>
                </div>
            </div>
            <div class="footer-menu">
                <div class="footer-menu-title"><?php echo __('Secure payment with', 'gethalal') ?></div>
                <div class="footer-payment-menus">
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/visa_icon.png" alt="visa"></div>
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/master_icon.png" alt="mastercard"></div>
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/paypal_icon.png" alt="paypal"></div>
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/apple_pay_icon.png" alt="apple_pay"></div>
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/google_pay_icon.png" alt="google_pay"></div>
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/bank_transfer.png" alt="bank"></div>
                    <div class="app-payment-menu"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/cash_icon.png" alt="cash"></div>
                </div>
            </div>
            <div class="footer-menu">
                <div class="footer-menu-title"><?php echo __('Social Media', 'gethalal') ?></div>
                <div class="social-links">
                    <a href="https://www.facebook.com/GetHalalDE"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/facebook_icon.png" alt="facebook"></a>
                    <a href="https://tiktok.com/@gethalal.de"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/tiktok_icon.png" alt="tiktok"></a>
                    <a href="https://www.linkedin.com/company/gethalal/"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/linkdin_icon.png" alt="linkdin"></a>
                    <a href="https://www.instagram.com/gethalalde/"><img src="<?php echo esc_url( get_template_directory_uri() ) ?>/assets/images/insta_icon.png" alt="instagram"></a>
                </div>
            </div>
		</div>
        <!-- .site-info -->
        <div class="footer-divider wide-max-width"></div>
        <div class="site-copyright">
            <div class="copyright-text">Copyright © 2021-2022 GetHalal . All rights reserved</div>
            <div class="terms-links">
                <a href="<?php echo home_url('/privacy-policy') ?>"><span><?php echo __('Privacy Policy', 'gethalal') ?></span></a>
                <div class="copyright-divider"></div>
                <a href="<?php echo home_url('/terms-and-conditions') ?>"><span><?php echo __('Terms and Conditions', 'gethalal') ?></span></a>
                <div class="copyright-divider"></div>
                <a href="<?php echo home_url('/revocation') ?>"><span><?php echo __('Cancellation Policy', 'gethalal') ?></span></a>
            </div>
        </div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
