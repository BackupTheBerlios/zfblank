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

/** \brief Base class for tree-like structures in ZF_Blanks.
    
    \zfb_read Tree
*/

class ZfBlank_Tree
    extends ZfBlank_ActiveRow_Abstract
    implements IteratorAggregate
{

    /** \var array $_dataMap
    \see \ref datamap_tree "Data Map" */
    protected $_dataMap = array (
        'id' => 'ID',
        'parent' => 'ParentID',
        'offset' => 'ChildOffset',
        'name' => 'Name',
    );


    /** \var string $_iteratorClass
    \brief Default class name for iterator.
    \see getIterator() \see iteratorClass() */
    protected $_iteratorClass = 'ZfBlank_Tree_Iterator_DirectTraversal';

    /** \var ZfBlank_Tree $_parent
    \brief Parent node.  \see parentNodeSet() */
    protected $_parent = null;

    /** \var array $_children
    \brief Child nodes.
    \see childNodes(), childNode(), countChildNodes(), addChild() */
    protected $_children = array();

    /** \var boolean $_treeLoaded
    \brief Shows whether tree loading procedure was performed on this node.
    \see loadTree() */
    protected $_treeLoaded = false;
    
    /** \brief Set parent node.
    \note Don't confuse with setParent() that sets parent ID in object's data.
    \param ZfBlank_Tree $node parent node
    \return $this */
    public function parentNodeSet (ZfBlank_Tree $node)
    {
        $this->_parent = $node;
        return $this;
    }

    /** \brief Get parent node.
    \note Don't confuse with getParent() that returns parent ID from object's
    data.
    \return ZfBlank_Tree: parent node */
    public function parentNodeGet ()
    {
        return $this->_parent;
    }

    /** \brief Get all child nodes.
    \return array: child nodes */
    public function childNodes ()
    {
        return $this->_children;
    }

    /** \brief Get a child node by offset.
    \param integer $offset child offset among children. **0** (zero) is first.
    \return ZfBlank_Tree: child node or **null** */
    public function childNode ($offset)
    {
        return isset($this->_children[$offset])
            ? $this->_children[$offset]
            : null;
    }

    /** \brief Get number of children.
    \return integer: number of children */
    public function countChildNodes ()
    {
        return count($this->_children);
    }

    /** \brief Is this node the first child of its parent.
    \return boolean: **true** if there is no parent or if it's the first child; 
    **false** otherwise */
    public function firstChild () 
    {
        if (($parent = $this->parentNodeGet()) === null) return true;
        return $this->offset == 0;
    }

    /** \brief Is this node the last child of its parent.
    \return boolean: **true** if there is no parent or if it's the last child; 
    **false** otherwise */
    public function lastChild ()
    {
        if (($parent = $this->parentNodeGet()) === null) return true;
        return $this->offset == $parent->countChildNodes() - 1;
    }

    /** \brief Add a child node.

    \zfb_read Tree..addChild */
    public function addChild (ZfBlank_Tree $node, $offset = null,
        $hard = false
    ) {
        $node->setParent($this->id)->parentNodeSet($this);

        if ($offset !== null && $this->hasChildren()) {
            if ($offset >= ($cnt = count($this->_children))) {
                $node->setOffset($cnt);
                $this->_children[] = $node;
            } else {
                $new = array();
                $node->setOffset($offset);
                
                foreach ($this->_children as $index=>$child) {
                    if ($index >= $offset) {
                        if ($index === $offset) {
                            $new[] = $node;
                        }
                        $child->setOffset($child->offset + 1);
                        if ($hard) $child->save();
                    }

                    $new[] = $child;
                }
                
                $this->_children = $new;
            }
        } else {
            $this->_children[] = $node;
        }

        if ($hard) $node->save();
        return $node;
    }

    /** \brief Get key (ID) of the node.

    Check whether $node is node object and extract the key.
    \param mixed|ZfBlank_Tree $node
    \return mixed node key (ID) */
    protected function _nodeKey ($node)
    {
        if ($node instanceof ZfBlank_Tree) {
            $key = $node->id;
        } else {
            $key = $node;
        }

        return $key;
    }

    /** \brief Remove a child node.

    \param ZfBlank_Tree|mixed $node the node to remove itself, or it's ID
    \return ZfBlank_Tree|mixed: the same as given by parameter */
    public function removeChild ($node)
    {
        $key = $this->_nodeKey($node);

        $removed = false;

        foreach ($this->_children as $index => $child) {
            if ($child->id == $key) {
                unset ($this->_children[$index]);
                $removed = true;
                break;
            }
        }


        if ($removed) {
            $this->_children = array_values ($this->_children);

            foreach ($this->_children as $index => $child) {
                $child->setOffset($index);
            }
        }

        return $node;
    }
            

    /** \brief Check whether the node has children.
    \return boolean: **true** if the node has children, **false** otherwise */
    public function hasChildren ()
    {
        return !empty ($this->_children);
    }

    /** \brief Check whether node has specified child.
    \param ZfBlank_Tree|mixed $node child node or it's ID
    \return boolean: **true** if given node is a child, **false** otherwise */
    public function hasChild ($node)
    {
        $key = $this->_nodeKey($node);

        foreach ($this->_children as $child) {
            if ($child->id == $key) return true;
        }

        return false;
    }

    /** \brief Get sibling node.
    \param integer $off sibling's offset from this node (may be negative)
    \return ZfBlank_Tree: sibling node or **null** */
    protected function _getSibling ($off)
    {
        if (!$this->_parent) return null;
        
        $nodes = $this->_parent->childNodes ();
        $offset = $this->offset + $off;

        if (isset ($nodes[$offset])) {
            return $nodes[$offset];
        }

        return null;
    }

    /** \brief Get left neighbour node
    \return ZfBlank_Tree: left neighbour node or **null** */
    public function leftSibling ()
    {
        return $this->_getSibling(-1);
    }

    /** \brief Get right neighbour node
    \return ZfBlank_Tree: right left neighbour node or **null** */
    public function rightSibling ()
    {
        return $this->_getSibling(1);
    }

    /** \brief Load tree from database.

    If tree is already loaded does nothing.
    See ZfBlank_Tree class description for details and examples.
    \param array $parentIdx parent index; you don't need to pass this parameter
    manually; it's used in recursive calls (optional)
    \return $this */
    public function loadTree (array $parentIdx = null)
    {
        if ($this->_treeLoaded) return $this;

        if ($parentIdx === null) {
            $table = $this->getTable();
            $rows = $table->fetchAll($table->select()
                ->from($table->info('name'), array(
                    $this->columnName('id'),
                    $this->columnName('parent')
                ))
            );

            $parentIdx = array ();

            foreach ($rows as $row) {
                $parentIdx[$row->id] = $row->parent;
            }

            unset ($rows);
        }

        if (!count (array_keys ($parentIdx, $this->id)))
            return $this;

        $table = $this->getTable();
        $id = $this->id;
        $field = $this->columnName('parent');
        $expr = $id === null ? "$field IS NULL" : array ("$field = ?" => $id);
        $children = $table->fetchAll($expr, $this->columnName('offset'));

        foreach ($children as $child) {
            $child->loadTree($parentIdx);
            $this->addChild($child);
        }
        
        $this->_treeLoaded = true;
        return $this;
    }

    /** \brief Load child nodes from database.

    Loads direct children of this node without recursively loading their
    children and so on. 
    \note If the node has children already, they will be removed.
    \return $this */
    public function loadChildren ()
    {
        $table = $this->getTable();
        $id = $this->id;
        $field = $this->columnName('parent');
        $expr = $id === null ? "$field IS NULL" : array ("$field = ?" => $id);
        $children = $table->fetchAll($expr, $this->columnName('offset'));

        $this->_children = array();

        foreach ($children as $child) {
            $this->_children[] = $child;
        }

        return $this;
    }

    /** \brief Get iterator for the tree.

    \zfb_read Tree..getIterator */
    public function getIterator ()
    {
        $iteratorClass = $this->_iteratorClass;
        $iterator = new $iteratorClass($this);
        return $iterator;
    }

    /** \brief Set or get iterator class.

    \zfb_read Tree..iteratorClass */
    public function iteratorClass ($class = null)
    {
        $oldClass = $this->_iteratorClass;
        if ($class !== null) $this->_iteratorClass = $class;
        return $oldClass;
    }

    /** \brief Render the tree to another data model.

    \zfb_read Tree..render */
    public function render ($type, array $options = null)
    {
        $rendererClass = 'ZfBlank_Tree_Renderer_' . ucfirst($type);

        if (!class_exists ($rendererClass)) 
            throw new Zend_Exception("Wrong render type: $type");

        $renderer = new $rendererClass($options);
        $renderer->setTree($this);
        
        return $renderer->render();
    }

    /** \brief Deletion of subtree when delete() is called on some node.

    Overrides **Zend_Db_Table_Row_Abstract::_delete()**.
    \return void */
    protected function _delete ()
    {
        $table = $this->getTable();
        $this->loadTree();
        $ids = array();
        $this->_iteratorClass = 'ZfBlank_Tree_Iterator_DirectTraversal';
        $i = $this->getIterator();
        $i->next();

        if ($i->valid()) {
            foreach ($i->rewindPoint() as $node) $ids[] = $node->id;
            $db = $table->getAdapter();
            $column = $this->columnName('id');
            $table->delete($db->quoteInto("$column IN (?)", $ids));
        }

        if (($parent = $this->_parent) === null) {
            if (($pid = $this->parent) === null) {
                $parent = $table->createRow();
            } else {
                $parent = $table->find($pid)->getRow(0);
            }

            $parent->loadChildren();
        }

        $parent->removeChild($this);
        foreach ($parent->childNodes() as $child) $child->save();
    }

}
