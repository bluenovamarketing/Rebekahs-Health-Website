/**
 * Hydrate the six approved homepage sections bundled in the homepage source.
 * The visual markup remains faithful to the approved mockup; mockup-only links
 * are converted to their WordPress destinations.
 */
( async () => {
	const encodedSource = globalThis.REBEKAHS_SOURCE_HTML_BASE64;
	const mounts = document.querySelectorAll( '[data-source-section]' );
	if ( ! encodedSource || ! mounts.length ) {
		return;
	}

	const sourceBytes = Uint8Array.from( atob( encodedSource ), ( character ) => character.charCodeAt( 0 ) );
	const sourceText = new TextDecoder().decode( sourceBytes );
	const sourceDoc = new DOMParser().parseFromString( sourceText, 'text/html' );

	function scopeSelector( selector ) {
		const clean = selector.trim();
		if ( ! clean ) {
			return clean;
		}
		if ( clean === ':root' || clean === 'html' || clean === 'body' ) {
			return '.source-b';
		}
		if ( clean.startsWith( 'html ' ) || clean.startsWith( 'body ' ) ) {
			return '.source-b ' + clean.slice( 5 );
		}
		return '.source-b ' + clean;
	}

	function scopeRules( rules ) {
		return Array.from( rules ).map( ( rule ) => {
			if ( rule.type === CSSRule.STYLE_RULE ) {
				return rule.selectorText.split( ',' ).map( scopeSelector ).join( ',' ) + '{' + rule.style.cssText + '}';
			}
			if ( rule.type === CSSRule.MEDIA_RULE ) {
				return '@media ' + rule.conditionText + '{' + scopeRules( rule.cssRules ) + '}';
			}
			if ( typeof CSSRule.SUPPORTS_RULE !== 'undefined' && rule.type === CSSRule.SUPPORTS_RULE ) {
				return '@supports ' + rule.conditionText + '{' + scopeRules( rule.cssRules ) + '}';
			}
			return rule.cssText;
		} ).join( '\n' );
	}

	const parserStyle = document.createElement( 'style' );
	parserStyle.media = 'not all';
	parserStyle.textContent = Array.from( sourceDoc.querySelectorAll( 'style' ) ).map( ( style ) => style.textContent ).join( '\n' );
	document.head.appendChild( parserStyle );

	const scopedStyle = document.createElement( 'style' );
	scopedStyle.id = 'original-sections-scoped-styles';
	scopedStyle.textContent = scopeRules( parserStyle.sheet.cssRules );
	document.head.appendChild( scopedStyle );
	parserStyle.remove();

	function clone( selector ) {
		return sourceDoc.querySelector( selector )?.cloneNode( true );
	}

	function prepare( node ) {
		if ( ! node ) {
			return node;
		}
		if ( node.matches( '.reveal' ) ) {
			node.classList.add( 'visible' );
		}
		node.querySelectorAll( '.reveal' ).forEach( ( element ) => element.classList.add( 'visible' ) );
		node.querySelectorAll( 'img' ).forEach( ( image ) => {
			image.loading = 'lazy';
			image.decoding = 'async';
		} );
		return node;
	}

	function setLink( root, selector, href ) {
		const link = root?.querySelector( selector );
		if ( link ) {
			link.href = href;
		}
	}

	function preparePathway( section ) {
		setLink( section, 'a[href="#departments"]', '/in-store-products/' );
		setLink( section, 'a[href="#practitioners"]', '#practitioners' );
		setLink( section, 'a[href="#events"]', '/events/' );
		setLink( section, 'a[href="#locations"]', '/locations/' );
	}

	function prepareShipping( section ) {
		setLink( section, 'a[href="#locations"]', '/locations/' );
		section?.querySelectorAll( 'a' ).forEach( ( link ) => {
			if ( /store details/i.test( link.textContent ) ) {
				link.href = '/locations/';
			}
		} );
	}

	function prepareEvents( section ) {
		section?.querySelector( '.signup-combo' )?.remove();
		section?.querySelectorAll( 'a' ).forEach( ( link ) => {
			if ( /all events/i.test( link.textContent ) ) {
				link.href = '/events/';
			}
		} );
	}

	function prepareJournal( section ) {
		section?.querySelectorAll( 'a' ).forEach( ( link ) => {
			if ( /wellness blog/i.test( link.textContent ) ) {
				link.href = '/blog/';
			}
		} );
	}

	function prepareNewsletter( section ) {
		section?.classList.add( 'original-newsletter-section' );
		section?.querySelector( '.events-head' )?.remove();
		section?.querySelector( '.event-grid' )?.remove();
		const formSource = document.querySelector( '#rhn-newsletter-form-source' );
		const placeholderForm = section?.querySelector( 'form' );
		if ( formSource && placeholderForm ) {
			const liveForm = document.createElement( 'div' );
			liveForm.className = 'combo-form rhn-newsletter-form';
			liveForm.innerHTML = formSource.innerHTML;
			placeholderForm.replaceWith( liveForm );
			formSource.remove();
			return;
		}
		const fields = section?.querySelector( '.fields' );
		const button = fields?.querySelector( 'button' );
		if ( fields && button && ! fields.querySelector( 'select' ) ) {
			const select = document.createElement( 'select' );
			select.setAttribute( 'aria-label', 'Preferred store' );
			select.required = true;
			select.innerHTML = '<option value="">Choose your preferred store</option><option>Lapeer</option><option>Grand Blanc</option><option>Clarkston</option><option>Lake Orion</option>';
			fields.insertBefore( select, button );
		}
		const form = section?.querySelector( 'form' );
		if ( form ) {
			form.removeAttribute( 'onsubmit' );
			form.addEventListener( 'submit', ( event ) => event.preventDefault() );
		}
	}

	function preparePractitioner( section ) {
		const links = section?.querySelectorAll( '.partner-box a' ) || [];
		const destinations = [ '/shop-fullscript/', '/shop-designs-for-health/', '/shop-lifewave/' ];
		links.forEach( ( link, index ) => {
			if ( destinations[ index ] ) {
				link.href = destinations[ index ];
			}
			if ( index === 2 ) {
				const label = link.querySelector( 'span' );
				if ( label ) {
					label.textContent = 'Shop Wellness Patches';
				}
			}
		} );
		const box = section?.querySelector( '.partner-box' );
		if ( box && ! box.querySelector( '[data-injections]' ) ) {
			const link = document.createElement( 'a' );
			link.className = 'partner';
			link.href = '/peptides-injectables/';
			link.dataset.injections = 'true';
			link.innerHTML = '<span>Shop Injections</span><span aria-hidden="true">→</span>';
			box.appendChild( link );
		}
	}

	mounts.forEach( ( mount ) => {
		const name = mount.dataset.sourceSection;
		let section;
		if ( name === 'pathway' ) {
			section = clone( '.pathway' );
			preparePathway( section );
		} else if ( name === 'shipping' ) {
			section = clone( '.shipping' );
			prepareShipping( section );
		} else if ( name === 'events' ) {
			section = clone( 'section.events' );
			prepareEvents( section );
		} else if ( name === 'journal' ) {
			section = clone( 'section.journal' );
			prepareJournal( section );
		} else if ( name === 'newsletter' ) {
			section = clone( 'section.events' );
			prepareNewsletter( section );
		} else if ( name === 'practitioner' ) {
			section = clone( 'section.practitioner' );
			preparePractitioner( section );
		}

		mount.classList.add( 'source-b' );
		if ( section ) {
			mount.appendChild( prepare( section ) );
		}
	} );
} )();
