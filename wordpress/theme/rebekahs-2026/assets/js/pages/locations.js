
    document.querySelectorAll(".filter").forEach(function(button){button.addEventListener("click",function(){document.querySelectorAll(".filter").forEach(function(item){item.classList.remove("active")});button.classList.add("active");if(button.dataset.target!=="all"){document.getElementById(button.dataset.target).scrollIntoView({behavior:"smooth",block:"center"})}})});
  