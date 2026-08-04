/** Client-side filtering for the real Events Calendar archive. */
( () => {
	const form = document.querySelector( '#event-filters' );
	const list = document.querySelector( '#event-list' );
	const empty = document.querySelector( '#no-events' );
	if ( ! form || ! list || ! empty ) {
		return;
	}

	const cards = Array.from( list.querySelectorAll( '.event-card' ) );
	form.addEventListener( 'submit', ( event ) => {
		event.preventDefault();
		const search = form.elements.event_search.value.trim().toLowerCase();
		const location = form.elements.event_location.value;
		const type = form.elements.event_type.value;
		let visible = 0;
		cards.forEach( ( card ) => {
			const matches = ( ! search || card.dataset.eventTitle.includes( search ) ) &&
				( ! location || card.dataset.eventLocation === location ) &&
				( ! type || card.dataset.eventType === type );
			card.hidden = ! matches;
			if ( matches ) {
				visible += 1;
			}
		} );
		empty.hidden = visible !== 0;
	} );
} )();
