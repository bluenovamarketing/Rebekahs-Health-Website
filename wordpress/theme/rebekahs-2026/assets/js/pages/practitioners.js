/** Filter the server-rendered practitioner records without replacing their markup. */
( () => {
	const grid = document.querySelector( '#grid' );
	const search = document.querySelector( '#search' );
	const category = document.querySelector( '#category' );
	const count = document.querySelector( '#count' );
	const empty = document.querySelector( '#empty' );
	if ( ! grid || ! search || ! category || ! count || ! empty ) {
		return;
	}

	const cards = Array.from( grid.querySelectorAll( '.card' ) );
	function render() {
		const query = search.value.toLowerCase().trim();
		const selected = category.value;
		let visible = 0;
		cards.forEach( ( card ) => {
			const categories = ( card.dataset.categories || '' ).split( '|' );
			const matches = ( ! query || card.dataset.search.includes( query ) ) && ( ! selected || categories.includes( selected ) );
			card.hidden = ! matches;
			if ( matches ) {
				visible += 1;
			}
		} );
		count.textContent = `Showing ${ visible } of ${ cards.length } practitioners & practices`;
		empty.hidden = visible !== 0;
	}

	search.addEventListener( 'input', render );
	category.addEventListener( 'change', render );
	grid.addEventListener( 'click', ( event ) => {
		const button = event.target.closest( '.expand' );
		if ( ! button || ! grid.contains( button ) ) {
			return;
		}

		const panel = document.getElementById( button.getAttribute( 'aria-controls' ) );
		if ( ! panel ) {
			return;
		}

		const open = button.getAttribute( 'aria-expanded' ) === 'true';
		button.setAttribute( 'aria-expanded', String( ! open ) );
		button.firstChild.textContent = open ? 'View full listing ' : 'Hide full listing ';
		button.querySelector( 'span' ).textContent = open ? '+' : '−';
		panel.hidden = open;
	} );
	render();
} )();
