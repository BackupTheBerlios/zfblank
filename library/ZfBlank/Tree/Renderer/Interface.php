<?php 

    /**
    \file
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

    /** \brief Interface for tree renderer in ZF_Blanks.

    \zfb_read Tree_Renderer_Interface
    */

interface ZfBlank_Tree_Renderer_Interface
{
    
    /** \brief Associate with the tree to render.
    \param ZfBlank_Tree $tree tree root */
    public function setTree(ZfBlank_Tree $tree);
    /** \brief Get the associated tree.
    \see setTree()
    \return ZfBlank_Tree: tree root */
    public function getTree();
    /** \brief Render the tree set previously by setTree().
    \return mixed result data */
    public function render();

}

