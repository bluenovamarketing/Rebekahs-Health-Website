
    const cards=[...document.querySelectorAll('.card')],filters=[...document.querySelectorAll('.filter')],search=document.querySelector('#search'),empty=document.querySelector('#empty');
    let category='all';
    function update(){const q=search.value.trim().toLowerCase();let shown=0;cards.forEach(card=>{const matchesCategory=category==='all'||card.dataset.category.includes(category);const matchesText=!q||card.textContent.toLowerCase().includes(q);const show=matchesCategory&&matchesText;card.hidden=!show;if(show)shown++});empty.classList.toggle('show',shown===0)}
    filters.forEach(button=>button.addEventListener('click',()=>{filters.forEach(item=>item.classList.remove('active'));button.classList.add('active');category=button.dataset.filter;update()}));
    search.addEventListener('input',update);
  