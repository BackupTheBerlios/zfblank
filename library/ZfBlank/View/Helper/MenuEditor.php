<?php

/** \file
    \author Serge V. Baumer \<baumer at users.berlios.de\>

    \section LICENSE

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

/** \brief Renders jQuery JavaScript for site menu editor.
    \zfb_read View_Helper_MenuEditor
*/

class ZfBlank_View_Helper_MenuEditor extends Zend_View_Helper_Abstract
{

    /** \brief Get JavaScript for site menu editor.

    \zfb_read View_Helper_MenuEditor..menuEditor
    */
    public function menuEditor ($containerSelector, $editSaveUrl,
        $editAddUrl, $branchUrl, $deleteUrl, $nodeIdPrefix = 'sitemenu',
        $rootClass = 'navigation'
    ) {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'menuedit.tpl.js';
        $script = file_get_contents($file);

        $script = str_replace ('{CONTAINER_SELECTOR}',
                $containerSelector, $script);
        $script = str_replace ('{EDIT_SAVE_URL}', $editSaveUrl, $script);
        $script = str_replace ('{EDIT_ADD_URL}', $editAddUrl, $script);
        $script = str_replace ('{BRANCH_URL}', $branchUrl, $script);
        $script = str_replace ('{DELETE_URL}', $deleteUrl, $script);
        $script = str_replace ('{NODE_ID_PREFIX}', $nodeIdPrefix, $script);
        $script = str_replace ('{ROOT_CLASS}', $rootClass, $script);

        $output = "<script type=\"text/javascript\">\n".$script."\n</script>\n";
        return $output;
    }

}

