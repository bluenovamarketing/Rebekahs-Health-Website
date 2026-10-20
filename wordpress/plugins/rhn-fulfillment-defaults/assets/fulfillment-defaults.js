( function () {
	'use strict';

	const BOXES = {
		small: {
			label: 'Small Box',
			length: 7.25,
			width: 7.25,
			height: 6.5,
			tareOz: 6,
		},
		large: {
			label: 'Large Box',
			length: 12.25,
			width: 12,
			height: 8.5,
			tareOz: 12,
		},
	};
	const baseWeightByOrder = new Map();
	const initializedPriorityInputs = new WeakSet();
	const pendingPriorityInputs = new WeakSet();

	function labelTextFor( input ) {
		if ( input.id ) {
			const directLabel = document.querySelector( 'label[for="' + CSS.escape( input.id ) + '"]' );
			if ( directLabel ) {
				return directLabel.textContent || '';
			}
		}

		const wrappingLabel = input.closest( 'label' );
		if ( wrappingLabel ) {
			return wrappingLabel.textContent || '';
		}

		return input.parentElement ? input.parentElement.textContent || '' : '';
	}

	function setReactValue( input, value ) {
		if ( ! input || input.disabled ) {
			return false;
		}

		const descriptor = Object.getOwnPropertyDescriptor( window.HTMLInputElement.prototype, 'value' );
		if ( descriptor && descriptor.set ) {
			descriptor.set.call( input, String( value ) );
		} else {
			input.value = String( value );
		}

		input.dispatchEvent( new Event( 'input', { bubbles: true } ) );
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		return true;
	}

	function findInput( dialog, name ) {
		const ariaMatch = dialog.querySelector( 'input[aria-label="' + name + '"]' );
		if ( ariaMatch ) {
			return ariaMatch;
		}

		const label = Array.from( dialog.querySelectorAll( 'label[for]' ) ).find( function ( candidate ) {
			return name.toLowerCase() === ( candidate.textContent || '' ).trim().toLowerCase();
		} );

		if ( label ) {
			return dialog.querySelector( '#' + CSS.escape( label.getAttribute( 'for' ) ) );
		}

		return null;
	}

	function findUnit( dialog ) {
		const selects = Array.from( dialog.querySelectorAll( 'select' ) );
		return selects.find( function ( select ) {
			return Array.from( select.options ).some( function ( option ) {
				return 'oz' === option.value || 'oz' === option.textContent.trim();
			} );
		} );
	}

	function toOunces( value, unit ) {
		switch ( unit ) {
			case 'lbs': return value * 16;
			case 'g': return value / 28.349523125;
			case 'kg': return value * 35.27396195;
			default: return value;
		}
	}

	function fromOunces( value, unit ) {
		switch ( unit ) {
			case 'lbs': return value / 16;
			case 'g': return value * 28.349523125;
			case 'kg': return value / 35.27396195;
			default: return value;
		}
	}

	function orderKey( dialog ) {
		const match = ( dialog.textContent || '' ).match( /Order\s+(\d+)/i );
		return match ? match[ 1 ] : window.location.href;
	}

	function productWeightInOunces( dialog ) {
		const headings = Array.from( dialog.querySelectorAll( 'h2, h3, h4' ) );
		const packageHeading = headings.find( function ( heading ) {
			return 'package' === ( heading.textContent || '' ).trim().toLowerCase();
		} );

		if ( ! packageHeading ) {
			return NaN;
		}

		const candidates = Array.from( dialog.querySelectorAll( '*' ) ).filter( function ( element ) {
			if ( element.children.length ) {
				return false;
			}

			const text = ( element.textContent || '' ).trim();
			if ( ! /^\d+(?:\.\d+)?\s*(?:oz|lbs|g|kg)$/i.test( text ) ) {
				return false;
			}

			return Boolean( element.compareDocumentPosition( packageHeading ) & Node.DOCUMENT_POSITION_FOLLOWING );
		} );

		if ( ! candidates.length ) {
			return NaN;
		}

		return candidates.reduce( function ( total, element ) {
			const match = ( element.textContent || '' ).trim().match( /^(\d+(?:\.\d+)?)\s*(oz|lbs|g|kg)$/i );
			return total + toOunces( Number.parseFloat( match[ 1 ] ), match[ 2 ].toLowerCase() );
		}, 0 );
	}

	function selectCustomPackageTab( dialog ) {
		const tabs = Array.from( dialog.querySelectorAll( '[role="tab"], button' ) );
		const tab = tabs.find( function ( candidate ) {
			return 'custom package' === ( candidate.textContent || '' ).trim().toLowerCase();
		} );
		if ( tab && 'true' !== tab.getAttribute( 'aria-selected' ) ) {
			tab.click();
		}
	}

	function applyBoxWhenReady( dialog, box, baseOz, fallbackUnit, attempt ) {
		const totalInput = findInput( dialog, 'Total shipment weight (with package)' );
		const lengthInput = findInput( dialog, 'Length' );
		const widthInput = findInput( dialog, 'Width' );
		const heightInput = findInput( dialog, 'Height' );
		if ( ! totalInput || ! lengthInput || ! widthInput || ! heightInput ) {
			return;
		}

		if ( totalInput.disabled || lengthInput.disabled || widthInput.disabled || heightInput.disabled ) {
			if ( attempt < 40 ) {
				window.setTimeout( function () {
					applyBoxWhenReady( dialog, box, baseOz, fallbackUnit, attempt + 1 );
				}, 250 );
			}
			return;
		}

		const currentUnitSelect = findUnit( dialog );
		const currentUnit = currentUnitSelect ? currentUnitSelect.value : fallbackUnit;
		const total = fromOunces( baseOz + box.tareOz, currentUnit );

		// WooCommerce recalculates rates as soon as a complete package changes.
		// Briefly invalidate height, update weight, then finish all dimensions so
		// React receives one complete package and starts only one rate request.
		setReactValue( heightInput, '' );
		setReactValue( totalInput, Number( total.toFixed( 2 ) ) );
		setReactValue( findInput( dialog, 'Length' ), box.length );
		setReactValue( findInput( dialog, 'Width' ), box.width );
		setReactValue( findInput( dialog, 'Height' ), box.height );
	}

	function applyBox( dialog, box ) {
		selectCustomPackageTab( dialog );

		window.setTimeout( function () {
			const totalInput = findInput( dialog, 'Total shipment weight (with package)' );

			if ( ! findInput( dialog, 'Length' ) || ! findInput( dialog, 'Width' ) || ! findInput( dialog, 'Height' ) || ! totalInput ) {
				return;
			}

			const unitSelect = findUnit( dialog );
			const unit = unitSelect ? unitSelect.value : 'oz';
			const key = orderKey( dialog );
			let baseOz = baseWeightByOrder.get( key );

			if ( ! Number.isFinite( baseOz ) ) {
				baseOz = productWeightInOunces( dialog );
				if ( ! Number.isFinite( baseOz ) ) {
					const current = Number.parseFloat( totalInput.value );
					baseOz = Number.isFinite( current ) ? toOunces( current, unit ) : 0;
				}
				baseWeightByOrder.set( key, baseOz );
				dialog.dataset.rhnBaseWeightOz = String( baseOz );
			}

			applyBoxWhenReady( dialog, box, baseOz, unit, 0 );

			const status = dialog.querySelector( '.rhn-fulfillment-defaults__status' );
			if ( status ) {
				status.textContent = box.label + ' applied. Confirm the packed shipment on the scale and correct the total weight if needed.';
			}
		}, 80 );
	}

	function addPackageShortcuts( dialog ) {
		if ( dialog.querySelector( '.rhn-fulfillment-defaults' ) ) {
			return;
		}

		const headings = Array.from( dialog.querySelectorAll( 'h2, h3, h4' ) );
		const packageHeading = headings.find( function ( heading ) {
			return 'package' === ( heading.textContent || '' ).trim().toLowerCase();
		} );

		if ( ! packageHeading ) {
			return;
		}

		const panel = document.createElement( 'div' );
		panel.className = 'rhn-fulfillment-defaults';
		panel.innerHTML =
			'<strong>Rebekah\'s approved boxes</strong>' +
			'<div class="rhn-fulfillment-defaults__buttons"></div>' +
			'<p class="rhn-fulfillment-defaults__status">Choose the box actually packed. Its tare weight will be added to the product weight.</p>';

		const buttons = panel.querySelector( '.rhn-fulfillment-defaults__buttons' );
		Object.keys( BOXES ).forEach( function ( key ) {
			const box = BOXES[ key ];
			const button = document.createElement( 'button' );
			button.type = 'button';
			button.className = 'button button-secondary';
			button.textContent = box.label + ' (' + box.length + ' × ' + box.width + ' × ' + box.height + ' in; +' + box.tareOz + ' oz)';
			button.addEventListener( 'click', function () {
				applyBox( dialog, box );
			} );
			buttons.appendChild( button );
		} );

		packageHeading.insertAdjacentElement( 'afterend', panel );
	}

	function defaultResidential( dialog ) {
		const checkbox = Array.from( dialog.querySelectorAll( 'input[type="checkbox"]' ) ).find( function ( input ) {
			return /residential address/i.test( labelTextFor( input ) );
		} );

		if ( ! checkbox ) {
			return;
		}

		if ( '1' !== dialog.dataset.rhnResidentialInitialized ) {
			dialog.dataset.rhnResidentialInitialized = '1';
			if ( ! checkbox.checked ) {
				checkbox.click();
			}
		}

		const control = checkbox.closest( '.purchase-label__residential-toggle' ) || checkbox.closest( '.components-base-control' ) || checkbox.parentElement;
		if ( control && ! dialog.querySelector( '.rhn-residential-note' ) ) {
			const note = document.createElement( 'span' );
			note.className = 'rhn-residential-note';
			note.textContent = 'Defaulted on. Uncheck for a business address.';
			control.insertAdjacentElement( 'afterend', note );
		}
	}

	function defaultPriorityMail( dialog ) {
		const priority = Array.from( dialog.querySelectorAll( 'input[type="radio"]' ) ).find( function ( input ) {
			const text = labelTextFor( input );
			return /priority mail/i.test( text ) && ! /express/i.test( text );
		} );

		if ( ! priority || initializedPriorityInputs.has( priority ) || pendingPriorityInputs.has( priority ) ) {
			return;
		}

		if ( priority.disabled ) {
			const retries = Number.parseInt( priority.dataset.rhnPriorityRetries || '0', 10 );
			if ( retries < 40 ) {
				priority.dataset.rhnPriorityRetries = String( retries + 1 );
				pendingPriorityInputs.add( priority );
				window.setTimeout( function () {
					pendingPriorityInputs.delete( priority );
					defaultPriorityMail( dialog );
				}, 250 );
			}
			return;
		}

		if ( priority.checked ) {
			initializedPriorityInputs.add( priority );
			return;
		}

		priority.click();
		pendingPriorityInputs.add( priority );
		window.setTimeout( function () {
			pendingPriorityInputs.delete( priority );
			if ( priority.isConnected && priority.checked ) {
				initializedPriorityInputs.add( priority );
				delete priority.dataset.rhnPriorityRetries;
				return;
			}

			const retries = Number.parseInt( priority.dataset.rhnPriorityRetries || '0', 10 );
			if ( retries < 40 ) {
				priority.dataset.rhnPriorityRetries = String( retries + 1 );
				defaultPriorityMail( dialog );
			}
		}, 300 );
	}

	function enhance() {
		const dialogs = Array.from( document.querySelectorAll( '[role="dialog"]' ) );
		dialogs.forEach( function ( dialog ) {
			if ( ! /create shipping label/i.test( dialog.textContent || '' ) ) {
				return;
			}

			addPackageShortcuts( dialog );
			defaultResidential( dialog );
			defaultPriorityMail( dialog );
		} );
	}

	const observer = new MutationObserver( enhance );
	observer.observe( document.documentElement, { childList: true, subtree: true } );
	enhance();
}() );

