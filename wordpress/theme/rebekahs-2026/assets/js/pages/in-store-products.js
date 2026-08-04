
    document.querySelectorAll(".filter").forEach(button=>button.addEventListener("click",()=>{
      document.querySelectorAll(".filter").forEach(item=>{item.classList.remove("active");item.setAttribute("aria-pressed","false")});
      button.classList.add("active");button.setAttribute("aria-pressed","true");
      const value=button.dataset.filter;
      document.querySelectorAll(".category").forEach(card=>card.hidden=value!=="all"&&!card.dataset.kind.split(" ").includes(value));
    }));
    document.querySelector(".filter.active").setAttribute("aria-pressed","true");
  