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

/** \brief Recursive iterator for ZfBlank_Tree.
    \ingroup grp_tree
    \details Implements **RecursiveIterator** interface from Standard PHP
    Library (SPL). \see **RecursiveIterator** \see ZfBlank_Tree
*/

class ZfBlank_Tree_Iterator_Recursive implements RecursiveIterator
{

    /**
    \brief Node from which iteration is started.
    \var ZfBlank_Tree $_startNode
    */
    protected $_startNode;

    /**
    \brief Current node
    \var ZfBlank_Tree $_current
    \see current()
    */
    protected $_current;

    /**
    \param ZfBlank_Tree $startNode start node
    \see $_startNode
    */
    public function __construct (ZfBlank_Tree $startNode)
    {
        $this->_current = $this->_startNode = $startNode;
    }

    /**
    \brief Get iterator for the current entry.
    
    Returns iterator for children of the current node pointed at the first
    child.

    (Required by **RecursiveIterator** interface.)
    \return ZfBlank_Tree_Iterator_Recursive: children iterator
    */
    public function getChildren ()
    {
        if (!$this->_current->hasChildren()) return null;

        $fchld = $this->_current->childNode(0);
        $fchld->iteratorClass(__CLASS__);
        return $fchld->getIterator();
    }

    /**
    \brief Check if the current node has children, so an iterator can be
    created.

    (Required by **RecursiveIterator** interface.)
    \see getChildren()
    \return boolean
    */
    public function hasChildren ()
    {
        return $this->_current->hasChildren();
    }

    /**
    \brief Get current node.

    (Required by **RecursiveIterator** interface.)
    \return ZfBlank_Tree: current node
    */
    public function current ()
    {
        return $this->_current;
    }

    /**
    \brief Get the current node key (ID).

    (Required by **RecursiveIterator** interface.)
    \return mixed: the node key (ID)
    */
    public function key ()
    {
        return $this->_current->id;
    }

    /**
    \brief Rewind the iterator to the first node.

    (Required by **RecursiveIterator** interface.)
    \return void
    */
    public function rewind ()
    {
        $this->_current = $this->_startNode;
    }

    /**
    \brief Check if the current position is valid.

    \zfb_read Tree_Iterator_Recursive..valid
    */
    public function valid ()
    {
        return $this->_current !== null;
    }

    /**
    \brief Move forward to the next node.
    
    (Required by **RecursiveIterator** interface.)
    \return void
    */
    public function next ()
    {
        $this->_current = $this->_current->rightSibling();
    }

}
