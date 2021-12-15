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
	// Add element.
	const message = document.createElement( 'div' );
	message.classList.add( 'tbh-wrapper' );
	if ( TbhHappy.header.length ) {
		const header = document.createElement( 'header' );
		header.classList.add( 'tbh-header' );
		header.textContent = TbhHappy.header;
		message.appendChild( header );
	}
	const body = document.createElement( 'div' );
	body.classList.add( 'tbh-body' );
	body.innerHTML = TbhHappy.message;
	message.appendChild( body );
	if ( /^https?:/.test( TbhHappy.url ) ) {
		const footer = document.createElement( 'footer' );
		footer.classList.add( 'tbh-footer' );
		const a = document.createElement( 'a' );
		a.href = TbhHappy.url;
		a.textContent = TbhHappy.label;
		a.classList.add( 'tbh-link' );
		footer.appendChild( a );
		message.appendChild( footer );
		if ( 'navigate' === TbhHappy.type ) {
			setTimeout( function() {
				window.location.href = TbhHappy.url;
			}, TbhHappy.wait * 1000 );
		}
	}
	document.getElementsByTagName( 'body' )[0].appendChild( message );
}
