(function(window){
  function clean(html){
    if(!html) return '';
    var div=document.createElement('div');
    div.innerHTML=html;
    var tags=div.getElementsByTagName('*');
    for(var i=tags.length-1;i>=0;i--){
      var el=tags[i];
      el.removeAttribute('style');
      el.removeAttribute('class');
      if(el.tagName.toLowerCase()==='b') replaceTag(el,'strong');
      if(el.tagName.toLowerCase()==='i') replaceTag(el,'em');
      if(el.tagName.toLowerCase()==='div') replaceTag(el,'p');
      if(el.textContent.trim()==='' && !el.querySelector('img')){
        el.parentNode.removeChild(el);
      }
    }
    return div.innerHTML.trim();
  }
  function replaceTag(el,newTag){
    var n=document.createElement(newTag);
    while(el.firstChild){n.appendChild(el.firstChild);}
    el.parentNode.replaceChild(n,el);
  }
  window.hsCleanHtml=clean;
})(window);
