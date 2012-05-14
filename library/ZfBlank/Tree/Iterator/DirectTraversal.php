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

    /**
    \brief Direct traversal iterator for ZfBlank_Tree.
    
    \zfb_read Tree_Iterator_DirectTraversal
    */  

class ZfBlank_Tree_Iterator_DirectTraversal implements Iterator
{
    /** \var ZfBlank_Tree $_startNode
    \brief Node from which iteration is started
    */ protected $_startNode;

    /** \var ZfBlank_Tree $_current
    \brief Current node \see current()
    */ protected $_current;

    /** \var integer $_depth
    \brief Depth of current node 
    */ protected $_depth = 0;

    /** \var integer $_depthShift
    \brief Current and previous depths difference
    */ protected $_depthShift = 0;

    /** \var integer $_rewindDepth
    \brief What depth must be set during rewind \see rewind()
    */
    protected $_rewindDepth = 0;

    /**
    \param ZfBlank_Tree $startNode start (root) node \see $_startNode
    */
    public function __construct (ZfBlank_Tree $startNode)
    {
        $this->_current = $this->_startNode = $startNode;
    }

    /** \brief Get current node.

    (Required by **Iterator** interface.)
    \return ZfBlank_Tree: current node
    */
    public function current ()
    {
        return $this->_current;
    }

    /**
    \brief Get the current node key (ID).

    (Required by **Iterator** interface.)
    \return mixed: the node key (ID)
    */
    public function key ()
    {
        return $this->_current->getId();
    }

    /**
    \brief Make current node the rewind point.

    \zfb_read Tree_Iterator_DirectTraversal..rewindPoint
    */
    public function rewindPoint()
    {
        $this->_rewindDepth = $this->getDepth();
        $this->_startNode = $this->_current;
        return $this;
    }

    /**
    \brief Rewind the iterator to the first node.

    (Required by **Iterator** interface.)
    \see rewindPoint()
    \return void
    */
    public function rewind ()
    {
        $this->setDepth($this->_rewindDepth);
        $this->_current = $this->_startNode;
    }

    /**
    \brief Check if the current position is valid.

    \zfb_read Tree_Iterator_DirectTraversal..valid
    */
    public function valid ()
    {
        return $this->_current !== null;
    }

    /**
    \brief Get current depth.

    Returns current depth inside the tree.
    \return integer: the depth
    */
    public function getDepth ()
    {
        return $this->_depth;
    }

    /**
    \brief Set current depth.
    \see getDepth()
    \return $this
    */
    public function setDepth($depth)
    {
        $this->_depthShift = $depth - $this->_depth;
        $this->_depth = $depth;
        return $this;
    }

    /**
    \brief Get the difference between current and previous depths.

    \return integer: **0** if depth has not changed, negative number if
    depth has decreased, and positive number if it has increased.
    */
    public function getDepthShift ()
    {
        return $this->_depthShift;
    }

    /**
    \brief Move the current position to the next node.

    (Required by **Iterator** interface.)
    \note This method is called after each **foreach** loop
    \return void
    */
    public function next()
    {
        $current = $this->current();

        if ($current->hasChildren()) {
            $this->setDepth($this->getDepth() + 1);
            $children = $current->childNodes();
            $this->_current = $children[0];
        } elseif (($next = $current->rightSibling()) !== null) {
            $this->_current = $next;
            $this->setDepth($this->getDepth());
        } else {
            $depth = $this->getDepth();
            while (($next = $current->rightSibling()) === null) {
                $depth--;
                $next = $current->parentNodeGet();

                if ($next === null || $next->getId() === null
                    || $depth === 0
                ) {
                    $next = null;
                    break;
                } 

                $current = $next;
            }

            $this->setDepth($depth);
            $this->_current = $next;
        }
    }

}
