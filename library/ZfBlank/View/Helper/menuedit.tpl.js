$(function () {

    editorContainer = '{CONTAINER_SELECTOR}';
    editSaveUrl = '{EDIT_SAVE_URL}';
    editAddUrl = '{EDIT_ADD_URL}';
    branchUrl = '{BRANCH_URL}';
    deleteUrl = '{DELETE_URL}';
    nodeIdPrefix = '{NODE_ID_PREFIX}';
    rootClass = '{ROOT_CLASS}';
    buttonError = 'FAILED: Press again or quit';

    //destroy button bar
    function removeButtons (item) {
        if (item) {
            item.find('.button_bar').remove();
        } else {
            $('.button_bar').remove();
        }
    }

    //create button bar
    $('.node_attr').live('click', function (ev) {
        removeButtons();
        $('.edit_close').mousedown().mouseup();
        item = $(this).closest('.node');
        buttonBar = $('<span class="button_bar"></span>');

        var buttons = {
            'add_before': 'Add Before',
            'add_after': 'Add After',
            'branch': 'Branch',
            'edit': 'Edit',
            'delete': 'Delete',
            'close': 'X'
        };

        if (item.find('ul').length) {
            delete buttons["branch"];
        }

        for (prop in buttons) {
            button = $('<span></span>').addClass(prop);
            button.css('cursor', 'pointer');
            button.text(buttons[prop]);
            buttonBar.append(button);
            buttonBar.append(' ');
        }

        $(this).after(buttonBar);
        var edit = $('.button_bar');
        edit.css('margin', '0px 10px').css('padding', '0px');

    });

    function newNode () {
        var item = $('<li class="node"><span class="node_attr"></span></li>');
        item.find('span').css('cursor', 'pointer');
        return item;
    }

    function createEditor () {
        var editor = $('<span class="editor">Title: <input type="text" class="item_title" /> Link: <input type="text" class="item_link" /> <span class="edit_save">Save</span> <span class="edit_close" title="Close">X</span></span>');
        editor.find('span').css('cursor', 'pointer');

        return editor;
    }

    //get numeric id from attribute 'id'
    function getId (item) {
        var id = item.find('.node_attr').attr('id').split('-');
        return id[1];
    }

    //numeric id of parent node
    function getParentId (item) {
        var parentItem = item.parents('.node').first();
        parentId = parentItem.length ? getId(parentItem) : null;
        return parentId;
    }

    //item editor
    function edit (item, relation) {
        var id = getId (item);
        removeButtons(item);
        var editor = createEditor();

        var subj = relation == 'self' ? item : newNode();

        var attributes = subj.children('.node_attr');
        attributes.css('display', 'none');

        if (relation == 'self') {
            editor.find('.item_title').val(attributes.text());
            editor.find('.item_link').val(attributes.attr('title'));
        }

        editor.find('.edit_save').mousedown(function(ev) {
            $(this).unbind('mouseup').mouseup(function(ev) {
                var editor = $(this).closest('.editor');
                var item = editor.closest('.node');
                var attributes = item.children('.node_attr');
                var title = editor.find('.item_title');
                var link = editor.find('.item_link');

                var data = {
                    'format': 'json',
                    'name': title.val(),
                    'link': link.val()
                };

                switch (relation) {
                    case 'self':
                        url = editSaveUrl;
                        data['id'] = getId(item);
                        break;
                    case 'before':
                        url = editAddUrl;
                        data['position'] = item.index()
                        break;
                    case 'after':
                        url = editAddUrl;
                        data['position'] = item.index()
                        break;
                    case 'branch':
                        url = branchUrl;
                        break;
                }

                if (relation != 'self') {
                    parentId = getParentId(item)
                    if (parentId !== null) data['parent'] = parentId;
                }

                var savebutton = $(this);

                $.ajax({
                    'type': 'POST',
                    'url': url,
                    'data': data,
                    'success': function (rdata) {
                        var id = rdata['id'];
                        attributes.text(data['name']);
                        attributes.attr('title', data['link']);
                        if (relation != 'self') {
                            attributes.attr('id', nodeIdPrefix+'-'+id);
                        }
                        attributes.css('display', 'inline');
                        editor.remove();
                    },
                    'error': function () { savebutton.text(buttonError); }
                });
            });
            return false;
        });

        editor.find('.edit_close').mousedown(function(ev) {
            $(this).unbind('mouseup').mouseup(function() {
                var editor = $(this).closest('.editor');
                var attributes = editor.siblings('.node_attr');

                switch (relation) {
                    case 'self':
                        attributes.css('display', 'inline');
                        editor.remove();
                        break;
                    case 'before':
                    case 'after':
                        editor.closest('.node').remove();
                        break;
                    case 'branch':
                        editor.closest('ul').remove();
                        break;
                }
            });
            return false;
        });

        attributes.after(editor);

        switch (relation) {
            case 'before': 
                item.before(subj);
                break;
            case 'after':
                item.after(subj);
                break;
            case 'branch':
                branch = $('<ul></ul>');
                branch.append(subj);
                item.append(branch);
                break;
        }

    }

    //create first node in empty tree
    function firstItem() {
        editor = createEditor();
        editor.find('.edit_close').remove();

        editor.find('.edit_save').mousedown(function(ev) {
            $(this).unbind('mouseup').mouseup(function(ev) {
                var editor = $(this).closest('.editor');
                var title = editor.find('.item_title').val();
                var link = editor.find('.item_link').val();
                var savebutton = $(this);

                $.ajax({
                    'type': 'POST',
                    'url': branchUrl,
                    'data': {
                        'format': 'json',
                        'name': title,
                        'link': link
                    },
                    'success': function (rdata) {
                        var id = rdata['id'];
                        var item = newNode();
                        var attributes = item.children('.node_attr');
                        attributes.text(title);
                        attributes.attr('title', link);
                        attributes.attr('id', nodeIdPrefix+'-'+id);
                        var container = $('<ul></ul>').append(item);

                        if (rootClass !== '')
                            container.addClass(rootClass);

                        editor.replaceWith(container);
                    },
                    'error': function () { savebutton.text(buttonError); }
                });
            });
            return false;
        });
        
        $(editorContainer).append(editor);
    }


    //remove item with descendants
    function destroy (item) {
        var button = item.find('.delete')
        var parentId = getParentId (item);
        var ids = getId(item); //comma separated list
        item.find('.node').each(function() {
            ids = ids.toString() + ',' + getId($(this)).toString();
        });
        $.ajax({
            'type': 'POST',
            'url': deleteUrl,
            'data': {
                'format': 'json',
                'ids': ids
            },
            'success': function () {
                var container = item.closest('ul');
                item.remove();

                if (!container.children('.node').length) {
                    container.remove();
                }

                if (!$(editorContainer).children().length) {
                    firstItem();
                }
            },
            'error': function () {
                button.text(buttonError);
            }
        });
    }

    
    if ($(editorContainer).children().length) {
        //class for single node
        $(editorContainer + ' li').addClass('node');

        //transforming links to spans
        $(editorContainer).find('a').each(function (index, item) {
            var title = $(this).text();
            var link = $(this).attr('href');
            var id = $(this).attr('id');
            var span = $('<span class="node_attr"></span>');
            span.text(title);
            span.attr('id', id);
            span.attr('title', link);
            span.css('cursor', 'pointer');
            $(this).replaceWith(span);
        });
    } else {
        firstItem();
    }

    /* attaching handlers to button bar buttons */

    function mouseDownUpHandle (button, handler) { //'click' problem bypass
        $(button).live('mousedown', function(ev) {
            $(this).unbind('mouseup').mouseup(handler)
            return false;
        });
    }

    mouseDownUpHandle('.add_after', function(ev) {
        edit ($(this).closest('.node'), 'after');
    });

    mouseDownUpHandle('.add_before', function(ev) {
        edit ($(this).closest('.node'), 'before');
    });

    mouseDownUpHandle('.edit', function(ev) {
        edit ($(this).closest('.node'), 'self');
    });

    mouseDownUpHandle('.branch', function(ev) {
        edit ($(this).closest('.node'), 'branch');
    });

    mouseDownUpHandle('.delete', function(ev) {
        destroy ($(this).closest('.node'));
    });

    mouseDownUpHandle('.close', function(ev) {
        removeButtons();
    });

});
