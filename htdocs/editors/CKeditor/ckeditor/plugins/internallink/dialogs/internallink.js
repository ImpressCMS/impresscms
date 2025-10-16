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
        var resultsContainerId = CKEDITOR.tools.getNextId();
        var serviceBase = (window.ICMS_URL || '') + '/include/ajax/internallink.php';
        var csrfToken = window.ICMS_CSRF_TOKEN || '';

        var ALL_VALUE = '__all__';

        function renderResults(list){
            var cont = document.getElementById(resultsElId);
            if(!cont) return;
            cont.innerHTML = '';
            if(!list || !list.length){
                cont.innerHTML = '<div style="color:#666;padding:10px;">No results</div>';
                return;
            }
            var ul = document.createElement('ul');
            ul.style.listStyle = 'none';
            ul.style.margin = '0';
            ul.style.padding = '0';
            list.forEach(function(item){
                var li = document.createElement('li');
                li.style.padding = '6px 8px';
                li.style.cursor = 'pointer';
                li.style.borderBottom = '1px solid #eee';
                li.onmouseover = function(){ li.style.background = '#e8f4f8'; };
                li.onmouseout = function(){ li.style.background = 'transparent'; };
                li.onclick = function(){
                    selected = { title: item.title, url: item.url };
                    // Highlight selected item
                    var items = cont.querySelectorAll('li');
                    items.forEach(function(el){ el.style.background = 'transparent'; });
                    li.style.background = '#d0e8f2';
                };
                var modLabel = item.moduleName || item.module || '';
                li.textContent = modLabel ? (item.title + ' — [' + modLabel + ']') : item.title;
                ul.appendChild(li);
            });
            cont.appendChild(ul);
        }

        var doSearch = debounce(function(){
            var dialog = CKEDITOR.dialog.getCurrent();
            if(!dialog) return;
            var modSel = dialog.getContentElement('tab-basic', 'module_select');
            var itemsSel = dialog.getContentElement('tab-basic', 'items_select');
            var qEl = dialog.getContentElement('tab-basic', 'search_input');
            if(!modSel || !qEl) return;
            var mod = modSel.getValue();
            var q = qEl.getValue();
            if(!mod || !q || q.length < 3){ renderResults([]); return; }
            var selectedItems = [];
            if(itemsSel){
                var itemsVal = itemsSel.getValue();
                if(itemsVal && itemsVal.length){
                    selectedItems = itemsVal.split(',').filter(function(x){ return x.length > 0; });
                }
            }
            var itemsParam = (mod !== ALL_VALUE && selectedItems.length > 0) ? '&items=' + encodeURIComponent(selectedItems.join(',')) : '';
            var tokenParam = csrfToken ? '&token=' + encodeURIComponent(csrfToken) : '';
            ajax(serviceBase + '?action=search&module=' + encodeURIComponent(mod) + '&q=' + encodeURIComponent(q) + itemsParam + tokenParam, function(err, data){
                if(err){ renderResults([]); return; }
                renderResults(data && data.results ? data.results : []);
            });
        }, 300);

        return {
            title: 'Insert Internal Link',
            minWidth: 550,
            minHeight: 400,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Basic',
                    elements: [
                        {
                            type: 'select',
                            id: 'module_select',
                            label: 'Module',
                            items: [],
                            onChange: function(){
                                var dialog = CKEDITOR.dialog.getCurrent();
                                var modSel = dialog.getContentElement('tab-basic', 'module_select');
                                var itemsSel = dialog.getContentElement('tab-basic', 'items_select');
                                if(!itemsSel) return;
                                itemsSel.clear();
                                var mod = modSel.getValue();
                                // Handle "All Modules" special case: disable items selector
                                var inputEl = itemsSel && itemsSel.getInputElement ? itemsSel.getInputElement().$ : null;
                                if (mod === ALL_VALUE) {
                                    itemsSel.clear();
                                    if (inputEl) { inputEl.disabled = true; }
                                } else {
                                    if (inputEl) { inputEl.disabled = false; }
                                    modules.forEach(function(m){
                                        if(m.dirname === mod && m.items){
                                            m.items.forEach(function(item){
                                                itemsSel.add(item, item);
                                            });
                                        }
                                    });
                                }
                                doSearch();
                            }
                        },
                        {
                            type: 'select',
                            id: 'items_select',
                            label: 'Content Types (optional)',
                            items: [],
                            multiple: true,
                            size: 4,
                            onChange: function(){
                                doSearch();
                            }
                        },
                        {
                            type: 'text',
                            id: 'search_input',
                            label: 'Search',
                            onKeyUp: function(){
                                doSearch();
                            }
                        },
                        {
                            type: 'html',
                            id: 'results_container',
                            html: '<div id="' + resultsElId + '" style="border:1px solid #ccc;height:150px;overflow:auto;margin-top:8px;background:#fff;"></div>'
                        }
                    ]
                }
            ],
            onShow: function(){
                selected = { title: '', url: '' };
                var dialog = CKEDITOR.dialog.getCurrent();
                var modSel = dialog.getContentElement('tab-basic', 'module_select');
                var itemsSel = dialog.getContentElement('tab-basic', 'items_select');

                // Load modules once per open
                var tokenParam = csrfToken ? '&token=' + encodeURIComponent(csrfToken) : '';
                ajax(serviceBase + '?action=modules' + tokenParam, function(err, data){
                    if(!modSel) return;
                    modSel.clear();
                    if(itemsSel) itemsSel.clear();
                    if(err || !data || !data.modules){ return; }
                    modules = data.modules;
                    // Add special "All Modules" option at the top
                    modSel.add('-- All Modules --', ALL_VALUE);
                    modules.forEach(function(m){
                        modSel.add(m.name, m.dirname);
                    });
                    // Select "All Modules" by default
                    modSel.setValue(ALL_VALUE);
                });
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

