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
	render();
} )();
