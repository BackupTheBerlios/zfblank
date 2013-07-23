/*
    Copyright (C) 2011-2013  Serge V. Baumer

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, version 3 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$(function () {

    mainContainer = '{CONTAINER_SELECTOR}';
    fieldNamesUrl = '{FIELD_NAMES_URL}';
    nodeValuesUrl = '{NODE_VALUES_URL}';
    editSaveUrl = '{EDIT_SAVE_URL}';
    editAddUrl = '{EDIT_ADD_URL}';
    branchUrl = '{BRANCH_URL}';
    deleteUrl = '{DELETE_URL}';
    titleField = '{TITLE_FIELD}';
    nodeIdPrefix = '{NODE_ID_PREFIX}';
    rootClass = '{ROOT_CLASS}';
    textareas = new Array ({TEXTAREAS});
    buttonError = 'FAILED: Press again or quit';
    editorWidth = 400;
    editorPosition = new Array('center', 100);

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

    function isTextarea (name) {
        for (idx in textareas) {
            if (name === textareas[idx]) return true;
        }
        return false;
    }
    
    function populateEditor (editor, values) {
        var table = editor.find('table');

        for (idx in fieldNames) {
            var field = fieldNames[idx];
            var row = $('<tr></tr>');
            row.append($('<td></td>').text(field + ': '));
            
            if (isTextarea(field)) {
                var elem = $('<textarea></textarea>');
                if (values !== undefined && values[field] !== undefined) {
                    elem.append(values[field]);
                }
            } else {
                var elem = $('<input></input>');
                if (values !== undefined && values[field] !== undefined) {
                    elem.val(values[field]);
                }
            }
            
            if (field == 'id' || field == 'parent' || field == 'offset') {
                elem.attr('readonly', 'readonly');
            }
            
            elem.attr('id', field);
            row.append($('<td></td>').append(elem));
            table.append(row);
        }

        return editor;
    }

    function populateWithData (editor, id) {
        $.ajax({
            'type': 'POST',
            'url': nodeValuesUrl,
            'dataType': 'json',
            'data': {
                'format': 'json',
                'id': id
             },
            'success': function (data) {
                var values = new Array()
                for (idx in data) values[idx] = data[idx];
                populateEditor(editor, values);
            }
        });
    }
    
    function createEditor () {
        var editor = $('<div title="Node Editor"><form><table></table></form></div>');
        editor.dialog({
            'autoOpen': false,
            'position': editorPosition,
            'width': editorWidth
        });

        return editor;
    }

    //get numeric id from attribute 'id'
    function getId (item) {
        var id = item.find('.node_attr').attr('id').split('-');
        return id[1];
    }

    function setId (item, id) {
        item.find('.node_attr').attr('id', nodeIdPrefix + '-' + id);
    }

    //numeric id of parent node
    function getParentId (item) {
        var parentItem = item.parents('.node');
        parentId = parentItem.length ? getId(parentItem) : null;
        return parentId;
    }

    function extractData (editor) {
        var data = {};

        editor.find('input,textarea').each(function() {
            var idx = $(this).attr('id');
            data[idx] = $(this).val();
        });

        return data;
    }

    function destroyEditor (editor) {
        editor.dialog('destroy');
        editor.remove();
    }

    function editorSave (editor, url, node) {
        var data = extractData(editor);

        if (node) var attributes = node.find('.node_attr').first();

        $.ajax({
            'type': 'POST',
            'url': (url + '/format/json'),
            'data': data,
            'success': function (rdata) {
                if (attributes) {
                    attributes.text(data[titleField]);
                    attributes.attr('id', nodeIdPrefix+'-'+rdata['id']);
                }
                destroyEditor (editor);
            },
            'error': function () { alert(buttonError); }
        });
    }

    function edit (node) {
        removeButtons(node);
        var id = getId (node);
        var editor = createEditor();
        populateWithData(editor, id);

        editor.dialog({'buttons': {
            'Save': function (ev) {
                editorSave (editor, editSaveUrl, node);
            },
            'Cancel': function () { destroyEditor (editor); }
        }})

        editor.dialog('open');
    }

    function newNodeValues (parentId, offset) {
        var values = new Array();

        for (idx in fieldNames) {
            var fld = fieldNames[idx];
            switch (fld) {
                case 'parent':
                    values[fld] = parentId;
                    break;
                case 'offset':
                    values[fld] = offset;
                    break;
                default:
                    values[fld] = '';
            }
        }

        return values;
    }
        
    function neighbour (node, relation) {
        removeButtons(node);
        var parentId = getParentId (node);
        var offset = node.index();
        if (relation == 'after') offset++;
        var neighbor = newNode();
        var editor = createEditor();
        populateEditor (editor, newNodeValues (parentId, offset));

        editor.dialog({'buttons': {
            'Save': function (ev) {
                editorSave (editor, editAddUrl, neighbor);
            },
            'Cancel': function () {
                destroyEditor (editor);
                neighbor.remove();
            }
        }});

        if (relation == 'before') {
            node.before(neighbor);
        } else {
            node.after(neighbor);
        }

        editor.dialog('open');
    }

    function branch (node) {
        removeButtons(node);
        var parentId = getId (node);
        var child = newNode();
        var editor = createEditor();
        populateEditor (editor, newNodeValues(parentId, "0"));

        editor.dialog({'buttons': {
            'Save': function (ev) {
                editorSave (editor, branchUrl, child);
            },
            'Cancel': function () {
                destroyEditor (editor);
                child.closest('ul').remove();
            }
        }});

        var container = $('<ul></ul>');
        container.append(child);
        node.append(container);

        editor.dialog('open');
    }


    //create first node in empty tree
    function firstItem() {
        editor = createEditor();
        populateEditor (editor);

        editor.dialog({
            'buttons': {
                'Save': function (ev) {
                    var data = extractData($(this));

                    $.ajax({
                        'type': 'POST',
                        'url': branchUrl + '/format/json',
                        'data': data,
                        'success': function (rdata) {
                            var id = rdata['id'];
                            var item = newNode();
                            var attributes = item.children('.node_attr');
                            attributes.text(data[titleField]);
                            attributes.attr('id', nodeIdPrefix+'-'+id);
                            var container = $('<ul></ul>').append(item);

                            if (rootClass !== '') {
                                container.addClass(rootClass);
                            }
                            $(mainContainer).append(container);
                            editor.dialog('destroy');
                            editor.remove();
                        },
                        'error': function () { alert(buttonError); }
                    });
                }
            }
        });

        editor.dialog('open');
    }


    //remove item with descendants
    function destroy (item) {
        var button = item.find('.delete')
        var parentId = getParentId (item);
        var id = getId(item);
        //var ids = getId(item); //comma separated list
        //item.find('.node').each(function() {
            //ids = ids.toString() + ',' + getId($(this)).toString();
        //});
        
        $.ajax({
            'type': 'POST',
            'url': deleteUrl,
            'dataType': 'text',
            'data': {
                'format': 'json',
                'id': id
            },
            'success': function () {
                var container = item.closest('ul');
                item.remove();

                if (!container.children('.node').length) {
                    container.remove();
                } 

                if (!$(mainContainer).children().length) {
                    firstItem();
                }
            },
            'error': function () {
                button.text(buttonError);
            }
        });
    }

    //'click' problem bypass
    function mouseDownUpHandle (button, handler) {
        $(button).live('mousedown', function(ev) {
            $(this).unbind('mouseup').mouseup(handler)
            return false;
        });
    }

    function initTreeEditor () {
        mouseDownUpHandle('.add_after', function(ev) {
            neighbour ($(this).closest('.node'), 'after');
        });

        mouseDownUpHandle('.add_before', function(ev) {
            neighbour ($(this).closest('.node'), 'before');
        });

        mouseDownUpHandle('.edit', function(ev) {
            edit ($(this).closest('.node'));
        });

        mouseDownUpHandle('.branch', function(ev) {
            branch ($(this).closest('.node'));
        });

        mouseDownUpHandle('.delete', function(ev) {
            destroy ($(this).closest('.node'));
        });

        mouseDownUpHandle('.close', function(ev) {
            removeButtons();
        });

        if (!$(mainContainer+'> ul').children().length) {
            firstItem();
        } else {
            $('.node_attr').css('cursor', 'pointer');
        }
    }

    //retrieving tree node field names
    fieldNames = new Array();

    $.ajax({
        'type': 'POST',
        'url': fieldNamesUrl,
        'dataType': 'json',
        'data': { 'format': 'json' },
        'success': function (data) {
            //fieldNames = new Array();
            //obj = $.parseJSON(data);
            for (idx in data) {
                fieldNames.push(data[idx]);
            }
            initTreeEditor();
        },
        'error' : function () {
            alert ("Couldn't get field names. Please, reload the page.");
        }
    });
});
