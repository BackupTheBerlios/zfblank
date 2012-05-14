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

/**
    \brief Gateway for DB table containing site menu. Extends
    **Zend_Db_Table_Abstract**
    \zfb_read DbTable_SiteMenu
*/

class ZfBlank_DbTable_SiteMenu extends Zend_Db_Table_Abstract
{

    protected $_name = 'SiteMenu'; /**<
        \brief Physical DB table name. */

    protected $_idColumn = 'ID'; /**<
        \brief Name of column containing item ID. */

    protected $_parentIdColumn = 'ParentID'; /**<
        \brief Name of column containing parent ID. */

    protected $_positionColumn = 'Position'; /**<
        \brief Name of column containing position */

    protected $_depthColumn = 'MenuDepth'; /**<
        \brief Name of column containing depth. */

    protected $_nameColumn = 'Name'; /**<
        \brief Name of column containing name. */

    protected $_linkColumn = 'Link'; /**< \
        \brief Name of column containing link. */
    
    /**
    \brief Find all child menu items of a parent with given ID

    If parent id is **null**, find all 'toplevel menu items'.

    \param mixed $id parent id
    \return Zend_Db_Table_Rowset_Abstract: menu items ordered by position
    */
    public function findForParent ($id) {
        $parentCol = $this->_parentIdColumn;

        $expr = $id === null 
            ? "$parentCol IS NULL"
            : array("$parentCol = ?" => $id);

        $result = $this->fetchAll($expr, $this->_positionColumn);
        return $result;
    }
    
    /**
    \brief Find menu item with given name
    \param string $name node name
    \return Zend_Db_Table_Row_Abstract: row representing menu item
    */
    public function findForName ($name) {
        return $this->fetchAll(array($this->_nameColumn.' = ?' => $name));
    }

    /**
    \brief Find menu item with given link
    \param string $link link
    \return Zend_Db_Table_Row_Abstract
    */
    public function findForLink ($link) {
        return $this->fetchAll(array($this->_linkColumn.' = ?' => $link));
    }

    /**
    \brief Edit menu item with given ID.

    Allows to set new name and/or link for menu item.

    \param mixed $id menu item ID
    \param string $name new name
    \param string $link new link
    \return mixed: menu item ID
    */
    public function edit ($id, $name, $link) {
        $id = (int) $id;

        $data = array (
            $this->_nameColumn => $name,
            $this->_linkColumn => $link
        );

        $this->update($data, array($this->_idColumn.' = ?' => $id));
        return $id;
    }

    /**
    \brief Add new menu item to the table.
    \param Zend_Db_Table_Row_Abstract $parent parent item to which to add new
    item. If this parameter is **null**, new menu item is toplevel
    \param string $name name for new item
    \param string $link link for new item
    \param integer $position item position among other children or toplevel
    items (positions start from **1**). If this parameter is ommitted, it's
    set to **1**.
    \return mixed: ID of the new menu item
    */
    public function addItem ($parent, $name, $link, $position = null) {
        $parentCol = $this->_parentIdColumn;
        $positionCol = $this->_positionColumn;
        $idCol = $this->_idColumn;
        $depthCol = $this->_depthColumn;
        $linkCol = $this->_linkColumn;
        $nameCol = $this->_nameColumn;

        if ($parent !== null) {
            $parent = $this->find((int) $parent)->getRow(0);
        }

        $new = $this->createRow();
        $new->$parentCol = $parent === null ? null : $parent->$idCol;
        $new->$nameCol = $name;
        $new->$linkCol = $link;
        $new->$depthCol = $parent === null ? 1 : $parent->$depthCol + 1;
        
        $new->$positionCol = $position !== null ? (int) $position : 1;
    
        $select = $this->select()->where(   
            "$positionCol >= ?", $new->$positionCol);

        if ($parent === null) {
            $select->where("$parentCol IS NULL");
        } else {
            $select->where("$parentCol = ?", $parent->$idCol);
        }

        $subsequent = $this->fetchAll($select);

        foreach ($subsequent as $row) {
            $row->$positionCol = $row->$positionCol + 1;
            $row->save();
        }

        $new->save();
        return $new->$idCol;
    }

    /**
    \brief Delete menu items with given IDs from the table.
    \param array $ids: indexed array with menu item IDs to remove
    \return void
    */
    public function deleteItems (array $ids) {
        $ids_int = array();

        foreach ($ids as $id) $ids_int[] = (int) $id;

        $this->delete($this->_idColumn.' IN ('.implode(',', $ids_int).')');
    }
        
}
