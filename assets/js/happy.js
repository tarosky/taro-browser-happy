/*!
 * Browser Detector
 */

/* global TbhHappy:false */

const userAgent = window.navigator.userAgent.toLowerCase();

let isIe = false;
if ( -1 < userAgent.indexOf('msie') || -1 < userAgent.indexOf('trident') ) {
	isIe = true;
} else if ( location.href.match( /\ie=true/ ) ) {
	isIe = true;
}

if ( isIe ) {
	// Append Style.
	const style = document.createElement( 'link' );
	style.rel = 'stylesheet';
	style.href = TbhHappy.css;
	document.getElementsByTagName( 'head' )[ 0 ].appendChild( style );
	// Create element.
	const message = document.createElement( 'div' );
	message.classList.add( 'tbh-wrapper' );
	// Header.
	if ( TbhHappy.header.length ) {
		const header = document.createElement( 'header' );
		header.classList.add( 'tbh-header' );
		header.textContent = TbhHappy.header;
		message.appendChild( header );
	}
	// Main message.
	const body = document.createElement( 'div' );
	body.classList.add( 'tbh-body' );
	body.innerHTML = TbhHappy.message;
	message.appendChild( body );
	// Add Footer.
	const footer = document.createElement( 'footer' );
	footer.classList.add( 'tbh-footer' );
	message.appendChild( footer );
	// Close button.
	const closer = document.createElement( 'a' );
	closer.textContent = TbhHappy.close;
	closer.classList.add( 'tbh-close' );
	closer.href = '#';
	closer.addEventListener( 'click', function( event ) {
		event.preventDefault();
		document.getElementsByTagName( 'body' )[0].removeChild( message );
	} );
	footer.appendChild( closer );
	// URL link.
	if ( /^https?:/.test( TbhHappy.url ) ) {
		const a = document.createElement( 'a' );
		a.href = TbhHappy.url;
		a.textContent = TbhHappy.label;
		a.classList.add( 'tbh-link' );
		footer.appendChild( a );
		if ( 'navigate' === TbhHappy.type ) {
			setTimeout( function() {
				window.location.href = TbhHappy.url;
			}, TbhHappy.wait * 1000 );
		}
	}
	// Add message.
	document.getElementsByTagName( 'body' )[0].appendChild( message );

}
