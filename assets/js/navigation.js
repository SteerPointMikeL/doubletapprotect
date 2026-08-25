/**
 * Double Tap Protect — Primary Navigation
 * Handles: mobile hamburger toggle, submenu open on tap, click-outside to close.
 * Assumes markup produced by wp_nav_menu( 'primary' ) + header.php wrapper.
 */
(function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var toggle = document.querySelector( '.nav__toggle' );
		var menu   = document.querySelector( '.nav__links' );

		if ( ! toggle || ! menu ) { return; }

		// ─── Mobile hamburger ────────────────────────────────────────────
		toggle.addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			var open = menu.classList.toggle( 'is-open' );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );

		// Close when clicking outside the nav
		document.addEventListener( 'click', function ( e ) {
			if ( ! menu.classList.contains( 'is-open' ) ) { return; }
			if ( e.target.closest( '.nav' ) ) { return; }
			menu.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		} );

		// Close after tapping any link on mobile
		menu.addEventListener( 'click', function ( e ) {
			if ( window.innerWidth > 1024 ) { return; }
			if ( e.target.tagName !== 'A' ) { return; }
			// Don't close if it's a parent with # href (user is expanding)
			if ( e.target.getAttribute( 'href' ) === '#' ) {
				e.preventDefault();
				var li = e.target.parentElement;
				li.classList.toggle( 'is-submenu-open' );
				return;
			}
			menu.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		} );

		// ─── Close mobile menu if viewport resizes to desktop ───────────
		var resizeTimer;
		window.addEventListener( 'resize', function () {
			clearTimeout( resizeTimer );
			resizeTimer = setTimeout( function () {
				if ( window.innerWidth > 1024 ) {
					menu.classList.remove( 'is-open' );
					toggle.setAttribute( 'aria-expanded', 'false' );
				}
			}, 150 );
		} );
	} );
})();
