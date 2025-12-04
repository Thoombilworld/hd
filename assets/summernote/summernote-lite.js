(function(window){
  function init(selector, opts){
    opts = opts || {};
    var uploadUrl = opts.uploadUrl || '/admin/upload/image.php';
    var theme = opts.theme || 'light';
    var nodes = document.querySelectorAll(selector || '.summernote');
    nodes.forEach(function(textarea){
      if(textarea.dataset.hsSummernote==='1') return;
      textarea.dataset.hsSummernote='1';
      var wrapper = document.createElement('div');
      wrapper.className = 'hs-summernote-wrapper' + (theme==='dark' ? ' dark' : '');
      var toolbar = document.createElement('div');
      toolbar.className = 'hs-summernote-toolbar';
      var editor = document.createElement('div');
      editor.className = 'hs-summernote-editor';
      editor.contentEditable = true;
      editor.innerHTML = textarea.value || '';
      var status = document.createElement('div');
      status.className = 'hs-summernote-status';
      status.innerHTML = '<span class="hs-status-msg">Clean HTML enabled</span>';
      var toggle = document.createElement('label');
      toggle.className = 'hs-summernote-toggle';
      toggle.innerHTML = '<input type="checkbox" class="hs-dark-toggle" '+(theme==='dark'?'checked':'')+'> Dark mode';
      status.appendChild(toggle);
      wrapper.appendChild(toolbar);
      wrapper.appendChild(editor);
      wrapper.appendChild(status);
      textarea.style.display='none';
      textarea.parentNode.insertBefore(wrapper, textarea.nextSibling);

      var btns = [
        {cmd:'bold',icon:'B'},
        {cmd:'italic',icon:'I'},
        {cmd:'underline',icon:'U'},
        {cmd:'insertUnorderedList',icon:'• List'},
        {cmd:'createLink',icon:'Link',prompt:true},
        {cmd:'image',icon:'Image'},
        {cmd:'video',icon:'Video'},
        {cmd:'codeview',icon:'{}'},
        {cmd:'clean',icon:'Clean'}
      ];
      btns.forEach(function(btn){
        var b=document.createElement('button');
        b.type='button';
        b.className='hs-summernote-btn';
        b.textContent=btn.icon;
        b.addEventListener('click',function(){
          if(btn.cmd==='image'){return handleImage();}
          if(btn.cmd==='video'){return insertVideo();}
          if(btn.cmd==='codeview'){return toggleCode();}
          if(btn.cmd==='clean'){editor.innerHTML = window.hsCleanHtml ? window.hsCleanHtml(editor.innerHTML) : editor.innerHTML; sync();return;}
          if(btn.prompt){
            var url=prompt('Enter URL');
            if(url){ document.execCommand(btn.cmd,false,url); }
          } else {
            document.execCommand(btn.cmd,false,null);
          }
          sync();
        });
        toolbar.appendChild(b);
      });

      var codeMode=false;
      function toggleCode(){
        codeMode=!codeMode;
        if(codeMode){
          editor.classList.add('hs-summernote-code');
          editor.textContent = editor.innerHTML;
        } else {
          editor.classList.remove('hs-summernote-code');
          editor.innerHTML = editor.textContent;
        }
        sync();
      }

      function insertVideo(){
        var url = prompt('Enter video embed URL (YouTube, Vimeo)');
        if(!url) return;
        var iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.width='100%';
        iframe.height='360';
        iframe.allowFullscreen=true;
        editor.appendChild(iframe);
        sync();
      }

      function handleImage(){
        var input=document.createElement('input');
        input.type='file';
        input.accept='image/*';
        input.addEventListener('change',function(){
          if(!input.files || !input.files[0]) return;
          var fd=new FormData();
          fd.append('file', input.files[0]);
          fetch(uploadUrl,{method:'POST',body:fd,credentials:'include'})
            .then(function(res){return res.json();})
            .then(function(resp){
              if(resp && resp.url){
                var img=document.createElement('img');
                img.src=resp.url;
                editor.appendChild(img);
                sync();
              } else {
                alert(resp.error || 'Upload failed');
              }
            })
            .catch(function(){alert('Upload failed');});
        });
        input.click();
      }

      function sync(){
        var html = window.hsCleanHtml ? window.hsCleanHtml(editor.innerHTML) : editor.innerHTML;
        textarea.value = html;
      }

      editor.addEventListener('input', sync);
      editor.addEventListener('blur', sync);
      editor.addEventListener('paste', function(e){
        setTimeout(function(){
          editor.innerHTML = window.hsCleanHtml ? window.hsCleanHtml(editor.innerHTML) : editor.innerHTML;
          sync();
        },10);
      });

      var darkToggle = toggle.querySelector('.hs-dark-toggle');
      darkToggle.addEventListener('change', function(){
        if(darkToggle.checked){wrapper.classList.add('dark');} else {wrapper.classList.remove('dark');}
      });

      var form = textarea.closest('form');
      if(form){
        form.addEventListener('submit', sync);
      }
    });
  }

  window.initSummernote = init;
})(window);
