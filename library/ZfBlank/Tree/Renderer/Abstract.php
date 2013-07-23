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
*/

/** \brief Base class for tree renderer in ZF_Blanks
    \ingroup grp_tree
*/

abstract class ZfBlank_Tree_Renderer_Abstract
    implements ZfBlank_Tree_Renderer_Interface
{

    /** \var ZfBlank_Tree $_tree
    \brief Associated tree (root node). */
    protected $_tree = null;

    /** \param array $options options that will be passed to setOptions()
    (optional) */
    public function __construct ($options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }

    }

    /** \brief Set options.

    \zfb_read Tree_Renderer_Abstract..setOptions */
    public function setOptions ($options)
    {
        foreach ($options as $option => $value) {
            $method = 'set' . ucfirst($option);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
            
    }
    
    /** \brief Set the tree to render by render().
    \param ZfBlank_Tree $tree root node of the tree
    \return $this
    */
    public function setTree(ZfBlank_Tree $tree)
    {
        $this->_tree = $tree;
        return $this;
    }

    /** \brief Get the tree set by setTree().
    \return ZfBlank_Tree: tree (root node)
    */
    public function getTree()
    {
        return $this->_tree;
    }

    /** \brief Render the tree. \remark Must be overloaded by subclasses. */
    public function render() {}
}
