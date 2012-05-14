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

/** \brief View-side wrapper for tree renderers.
    \ingroup grp_tree
    \see ZfBlank_Tree::render()
*/

class ZfBlank_View_Helper_RenderTree extends Zend_View_Helper_Abstract
{

    /** \brief Render the tree by given renderer.

    \zfb_read View_Helper_RenderTree..renderTree
    */
    public function renderTree ($tree, $method, $options = null)
    {
        if ($tree instanceof ZfBlank_DbTable_Abstract) {
            $tree = $tree->createRow();
        }

        $tree->loadTree();

        return $tree->render($method, $options);
    }

}
