(async()=>{
  const encodedSource=globalThis.REBEKAHS_SOURCE_HTML_BASE64;
  if(!encodedSource)throw new Error("Unable to load bundled original homepage sections");
  const sourceBytes=Uint8Array.from(atob(encodedSource),character=>character.charCodeAt(0));
  const sourceText=new TextDecoder().decode(sourceBytes);
  const sourceDoc=new DOMParser().parseFromString(sourceText,"text/html");

  function scopeSelector(selector){
    const clean=selector.trim();
    if(!clean)return clean;
    if(clean===":root"||clean==="html"||clean==="body")return ".source-b";
    if(clean.startsWith("html "))return ".source-b "+clean.slice(5);
    if(clean.startsWith("body "))return ".source-b "+clean.slice(5);
    return ".source-b "+clean;
  }

  function scopeRules(rules){
    return Array.from(rules).map(rule=>{
      if(rule.type===CSSRule.STYLE_RULE){
        return rule.selectorText.split(",").map(scopeSelector).join(",")+"{"+rule.style.cssText+"}";
      }
      if(rule.type===CSSRule.MEDIA_RULE){return "@media "+rule.conditionText+"{"+scopeRules(rule.cssRules)+"}";}
      if(typeof CSSRule.SUPPORTS_RULE!=="undefined"&&rule.type===CSSRule.SUPPORTS_RULE){return "@supports "+rule.conditionText+"{"+scopeRules(rule.cssRules)+"}";}
      return rule.cssText;
    }).join("\n");
  }

  const parserStyle=document.createElement("style");
  parserStyle.media="not all";
  parserStyle.textContent=Array.from(sourceDoc.querySelectorAll("style")).map(style=>style.textContent).join("\n");
  document.head.appendChild(parserStyle);
  const scopedStyle=document.createElement("style");
  scopedStyle.id="original-sections-scoped-styles";
  scopedStyle.textContent=scopeRules(parserStyle.sheet.cssRules);
  document.head.appendChild(scopedStyle);
  parserStyle.remove();

  function clone(selector){return sourceDoc.querySelector(selector)?.cloneNode(true);}
  function prepare(node){
    if(!node)return node;
    if(node.matches(".reveal"))node.classList.add("visible");
    node.querySelectorAll(".reveal").forEach(element=>element.classList.add("visible"));
    return node;
  }
  function addPreferredStore(root){
    const fields=root.querySelector(".fields");
    const button=fields?.querySelector("button");
    if(!fields||!button||fields.querySelector("select"))return;
    const select=document.createElement("select");
    select.setAttribute("aria-label","Preferred store");
    select.required=true;
    select.innerHTML='<option value="">Choose your preferred store</option><option>Lapeer</option><option>Grand Blanc</option><option>Clarkston</option><option>Lake Orion</option>';
    fields.insertBefore(select,button);
  }
  function addInjections(root){
    const box=root.querySelector(".partner-box");
    if(!box||box.querySelector("[data-injections]"))return;
    const link=document.createElement("a");
    link.className="partner";link.href="#";link.dataset.injections="true";
    link.innerHTML="<span>Shop Injections</span><span>→</span>";
    box.appendChild(link);
  }
  const mounts=document.querySelectorAll("[data-source-section]");
  mounts.forEach(mount=>{
    const name=mount.dataset.sourceSection;
    let section;
    if(name==="pathway"){
      section=clone(".pathway");
      const departmentsLink=section?.querySelector('a[href="#departments"]');
      if(departmentsLink)departmentsLink.href="in-store-products-mockup-v2.6.0.html";
    }
    if(name==="shipping"){
      section=clone(".shipping");
    }
    if(name==="events"){
      section=clone("section.events");
      section?.querySelector(".signup-combo")?.remove();
    }
    if(name==="journal")section=clone("section.journal");
    if(name==="newsletter"){
      section=clone("section.events");
      section?.classList.add("original-newsletter-section");
      section?.querySelector(".events-head")?.remove();
      section?.querySelector(".event-grid")?.remove();
      if(section)addPreferredStore(section);
    }
    if(name==="practitioner"){
      section=clone("section.practitioner");
      if(section)addInjections(section);
    }
    mount.classList.add("source-b");
    if(section)mount.appendChild(prepare(section));
  });
})();
