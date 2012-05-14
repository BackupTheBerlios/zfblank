<?php

/** \file
    \author Serge V. Baumer \<baumer at users.berlios.de\>

    \section LICENSE

    Copyright (C) 2011,2012  Serge V. Baumer

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

/** \brief Renders tree and jQuery JavaScript for tree editor.
    \zfb_read View_Helper_TreeEditor
*/

class ZfBlank_View_Helper_TreeEditor extends Zend_View_Helper_Abstract
{
    /** \brief Render tree with JavaScript for editing.
    \zfb_read View_Helper_TreeEditor..treeEditor */
    public function treeEditor ($tree, $containerClass, $fieldNamesUrl,
        $nodeValuesUrl, $editSaveUrl, $editAddUrl, $branchUrl, $deleteUrl,
        $titleField, array $textareas = null
    ) {
        $output = "<div class=\"$containerClass\">\n";
        $helper = new ZfBlank_View_Helper_RenderTree();

        $doc = $helper->renderTree($tree, 'dom', array(
            'valueSource' => new ZfBlank_ActiveRow_FieldDecorator(array(
                'fieldName' => 'name'
            )),
            'nodeTag' => array('li', 'attributes' => array( 'class' => 'node')),
            'valueContainer' => array('span',
                'attributes' => array(
                    'class' => 'node_attr',
                    'id' => new ZfBlank_ActiveRow_FieldDecorator(array(
                        'prefix' => 'node-',
                        'fieldName' => 'id'
                    )),
                ),
            ),
        ));

        $doc->formatOutput = true;
        $output .= $doc->saveHTML();
        $output .= "</div>\n";
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'treeedit.tpl.js';
        $script = file_get_contents($file);
        $script = str_replace ('{CONTAINER_SELECTOR}',
            '.' . $containerClass, $script);
        $script = str_replace ('{FIELD_NAMES_URL}', $fieldNamesUrl, $script);
        $script = str_replace ('{NODE_VALUES_URL}', $nodeValuesUrl, $script);
        $script = str_replace ('{EDIT_SAVE_URL}', $editSaveUrl, $script);
        $script = str_replace ('{EDIT_ADD_URL}', $editAddUrl, $script);
        $script = str_replace ('{BRANCH_URL}', $branchUrl, $script);
        $script = str_replace ('{DELETE_URL}', $deleteUrl, $script);
        $script = str_replace ('{TITLE_FIELD}', $titleField, $script);
        $script = str_replace ('{ROOT_CLASS}', '', $script);
        $script = str_replace ('{NODE_ID_PREFIX}', 'node', $script);

        if ($textareas !== null) {
            $textareas = "'" . implode("','", $textareas) . "'";
        } else {
            $textareas = '';
        }

        $script = str_replace ('{TEXTAREAS}', $textareas, $script);

        $output .= "<script type=\"text/javascript\">\n"
                   . $script . "\n</script>\n";
        return $output;
    }
}
