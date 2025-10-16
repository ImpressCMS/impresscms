(function(){
    function ajax(url, cb){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function(){
            if(xhr.readyState === 4){
                try{ cb(null, JSON.parse(xhr.responseText)); }catch(e){ cb(e); }
            }
        };
        xhr.send();
    }

    function debounce(fn, delay){
        var t; return function(){
            var args = arguments, ctx = this;
            clearTimeout(t); t = setTimeout(function(){ fn.apply(ctx, args); }, delay);
        };
    }

    CKEDITOR.dialog.add('internallinkDialog', function(editor){
        var selected = { title: '', url: '' };
        var modules = [];
        var resultsElId = CKEDITOR.tools.getNextId();
        var moduleSelectId = CKEDITOR.tools.getNextId();
        var itemsSelectId = CKEDITOR.tools.getNextId();
        var searchInputId = CKEDITOR.tools.getNextId();
        var serviceBase = (window.ICMS_URL || '') + '/include/ajax/internallink.php';
        var csrfToken = window.ICMS_CSRF_TOKEN || '';

        function renderResults(list){
            var cont = document.getElementById(resultsElId);
            if(!cont) return;
            cont.innerHTML = '';
            if(!list || !list.length){
                cont.innerHTML = '<div style="color:#666;">No results</div>';
                return;
            }
            var ul = document.createElement('ul');
            ul.style.listStyle = 'none';
            ul.style.margin = '0';
            ul.style.padding = '0';
            list.forEach(function(item){
                var li = document.createElement('li');
                li.style.padding = '4px 6px';
                li.style.cursor = 'pointer';
                li.onmouseover = function(){ li.style.background = '#eef'; };
                li.onmouseout = function(){ li.style.background = 'transparent'; };
                li.onclick = function(){ selected = { title: item.title, url: item.url }; };
                li.textContent = item.title;
                ul.appendChild(li);
            });
            cont.appendChild(ul);
        }

        var doSearch = debounce(function(){
            var modSel = document.getElementById(moduleSelectId);
            var itemsSel = document.getElementById(itemsSelectId);
            var qEl = document.getElementById(searchInputId);
            if(!modSel || !qEl) return;
            var mod = modSel.value;
            var q = qEl.value;
            if(!mod || !q || q.length < 3){ renderResults([]); return; }
            var selectedItems = [];
            if(itemsSel){
                for(var i = 0; i < itemsSel.options.length; i++){
                    if(itemsSel.options[i].selected){
                        selectedItems.push(itemsSel.options[i].value);
                    }
                }
            }
            var itemsParam = selectedItems.length > 0 ? '&items=' + encodeURIComponent(selectedItems.join(',')) : '';
            var tokenParam = csrfToken ? '&token=' + encodeURIComponent(csrfToken) : '';
            ajax(serviceBase + '?action=search&module=' + encodeURIComponent(mod) + '&q=' + encodeURIComponent(q) + itemsParam + tokenParam, function(err, data){
                if(err){ renderResults([]); return; }
                renderResults(data && data.results ? data.results : []);
            });
        }, 300);

        return {
            title: 'Insert Internal Link',
            minWidth: 500,
            minHeight: 300,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Basic',
                    elements: [
                        { type: 'html', id: 'module', html: '<label for="' + moduleSelectId + '">Module</label><br/><select id="' + moduleSelectId + '" style="width:100%"></select>' },
                        { type: 'html', id: 'items', html: '<label for="' + itemsSelectId + '">Content Types (optional)</label><br/><select id="' + itemsSelectId + '" multiple style="width:100%;height:80px;"></select><div style="font-size:11px;color:#666;margin-top:4px;">Hold Ctrl/Cmd to select multiple</div>' },
                        { type: 'html', id: 'search', html: '<label for="' + searchInputId + '">Search</label><br/><input id="' + searchInputId + '" type="text" style="width:100%" placeholder="Type at least 3 characters" />' },
                        { type: 'html', id: 'results', html: '<div id="' + resultsElId + '" style="border:1px solid #ccc;height:150px;overflow:auto;margin-top:8px;"></div>' }
                    ]
                }
            ],
            onShow: function(){
                selected = { title: '', url: '' };
                // Load modules once per open
                var tokenParam = csrfToken ? '?token=' + encodeURIComponent(csrfToken) : '';
                ajax(serviceBase + '?action=modules' + tokenParam, function(err, data){
                    var sel = document.getElementById(moduleSelectId);
                    var itemsSel = document.getElementById(itemsSelectId);
                    if(!sel) return;
                    sel.innerHTML = '';
                    if(itemsSel) itemsSel.innerHTML = '';
                    if(err || !data || !data.modules){ return; }
                    modules = data.modules;
                    modules.forEach(function(m){
                        var opt = document.createElement('option');
                        opt.value = m.dirname;
                        opt.textContent = m.name;
                        sel.appendChild(opt);
                    });
                });
                var modSel = document.getElementById(moduleSelectId);
                if(modSel){
                    modSel.onchange = function(){
                        var itemsSel = document.getElementById(itemsSelectId);
                        if(!itemsSel) return;
                        itemsSel.innerHTML = '';
                        var mod = modSel.value;
                        modules.forEach(function(m){
                            if(m.dirname === mod && m.items){
                                m.items.forEach(function(item){
                                    var opt = document.createElement('option');
                                    opt.value = item;
                                    opt.textContent = item;
                                    itemsSel.appendChild(opt);
                                });
                            }
                        });
                        doSearch();
                    };
                }
                var qEl = document.getElementById(searchInputId);
                if(qEl){ qEl.onkeyup = doSearch; }
                var itemsSel = document.getElementById(itemsSelectId);
                if(itemsSel){ itemsSel.onchange = doSearch; }
            },
            onOk: function(){
                if(!selected.url){ return; }
                var sel = editor.getSelection();
                var selectedText = sel && sel.getSelectedText ? sel.getSelectedText() : '';
                var text = selectedText && selectedText.trim().length ? selectedText : selected.title || selected.url;
                editor.insertHtml('<a href="' + CKEDITOR.tools.htmlEncode(selected.url) + '">' + CKEDITOR.tools.htmlEncode(text) + '</a>');
            }
        };
    });
})();

