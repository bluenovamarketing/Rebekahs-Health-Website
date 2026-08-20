
    const cards=[...document.querySelectorAll('.card')],search=document.querySelector('#search'),empty=document.querySelector('#empty');
    if(search&&empty){
      function update(){const q=search.value.trim().toLowerCase();let shown=0;cards.forEach(card=>{const show=!q||card.textContent.toLowerCase().includes(q);card.hidden=!show;if(show)shown++});empty.classList.toggle('show',shown===0)}
      search.addEventListener('input',update);
      update();
    }
