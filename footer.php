	</main><!-- #main -->

	<!-- ─── FOOTER ──────────────────────────────────────────────── -->
	<footer id="colophon" class="footer" role="contentinfo">
		<div class="container">
			<div class="footer__grid">

				<!-- Col 1: Brand -->
				<div class="footer__brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<img
							src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.svg' ) ); ?>"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							class="footer__logo"
							width="120"
							height="60"
							loading="lazy"
						>
					</a>
					<p class="footer__tagline">
						<?php esc_html_e( 'Aerospace-grade cleaning and protection sealants for firearms, optics, and tactical gear. 2A strong. American made.', 'doubletap' ); ?>
					</p>
				</div>

				<!-- Col 2: Products -->
				<div class="footer__col">
					<h3 class="footer__heading"><?php esc_html_e( 'Products', 'doubletap' ); ?></h3>
					<ul class="footer__list" role="list">
						<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=cleaners' ) ); ?>"><?php esc_html_e( 'Cleaners'); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=sealants' ) ); ?>"><?php esc_html_e( 'Sealants'); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=lubricant' ) ); ?>"><?php esc_html_e( 'Lubricant'); ?></a></li>
						<!--<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=protectant' ) ); ?>"><?php esc_html_e( 'Protectant'); ?></a></li>-->
						<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=wipes' ) ); ?>"><?php esc_html_e( 'Wipes'); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=targets' ) ); ?>"><?php esc_html_e( 'Targets'); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/shop/?product_cat=kits' ) ); ?>"><?php esc_html_e( 'Kits'); ?></a></li>
					</ul>
				</div>

				<!-- Col 3: Information -->
				<div class="footer__col">
					<h3 class="footer__heading"><?php esc_html_e( 'Information', 'doubletap' ); ?></h3>
					<ul class="footer__list" role="list">
						<li><a href="<?php echo esc_url( home_url( '/information/' ) ); ?>"><?php esc_html_e( 'How It Works', 'doubletap' ); ?></a></li>
						<li><a href="https://www.doubletapprotect.com/instructions/">How to Apply</a></li>
						<li><a href="<?php echo esc_url( home_url( '/information/#safety-data' ) ); ?>"><?php esc_html_e( 'Safety Data Sheets', 'doubletap' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop All', 'doubletap' ); ?></a></li>
					</ul>
				</div>

				<!-- Col 4: Contact -->
				<div class="footer__col">
					<h3 class="footer__heading"><?php esc_html_e( 'Contact', 'doubletap' ); ?></h3>
					<ul class="footer__list" role="list">
						<li>
							<a href="tel:3172367701">317-236-7701</a>
						</li>
						<li>
							<a href="mailto:info@doubletapprotect.com">info@doubletapprotect.com</a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'doubletap' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/contact/?subject=Merchant+Kit+Inquiry' ) ); ?>"><?php esc_html_e( 'Dealer Inquiries', 'doubletap' ); ?></a>
						</li>
					</ul>
				</div>

			</div><!-- .footer__grid -->

			<!-- Bottom bar -->
			<div class="footer__bottom">
				<p class="footer__copy">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'doubletap' ); ?> <br> <a href="https://www.steerpoint.com"> Website by SteerPoint</a>
				</p>

				<div class="footer__social" aria-label="<?php esc_attr_e( 'Social media links', 'doubletap' ); ?>">
					<!-- Instagram — update href with actual handle -->
					<a href="https://www.instagram.com/doubletap_1911/" target="_blank" rel="noopener noreferrer" title="<?php esc_attr_e( 'Instagram', 'doubletap' ); ?>">
						<?php echo doubletap_get_icon( 'instagram' ); ?>
					</a>
					<!-- Facebook — update href with actual page -->
					<a href="https://www.facebook.com/doubletapprotect/" target="_blank" rel="noopener noreferrer" title="<?php esc_attr_e( 'Facebook', 'doubletap' ); ?>">
						<?php echo doubletap_get_icon( 'facebook' ); ?>
					</a>
				</div>
			</div>

		</div><!-- .container -->
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
