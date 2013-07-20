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

    /** \brief Site menu item. Extends **Zend_Navigation_Page_Uri**.
    
    \zfb_read SiteMenu
    */
class ZfBlank_SiteMenu extends Zend_Navigation_Page_Uri
{

    /** \brief Database table containing the menu (class name or instance).
    \var string or ZfBlank_DbTable_SiteMenu $_table
    \see ZfBlank_DbTable_SiteMenu
    \see getTable()
    */
    protected $_table = 'ZfBlank_DbTable_SiteMenu';

    /** \brief Default depth in menu tree.
    \var integer $_menuDepth
    */
    protected $_menuDepth = 0;

    /** \param array|Zend_Config $options options for parent constructor
    \see **Zend_Navigation_Page_Uri::__construct**
    */
    public function __construct ($options = null) {
        parent::__construct ($options);
        $this->_id = null;
    }

    /** \brief Lazy load and get table object.
    \see $_table
    \return ZfBlank_DbTable_SiteMenu
    */
    public function getTable () {
        if (is_string($this->_table)) {
            $tableclass = $this->_table;
            $this->_table = new $tableclass;
        }

        return $this->_table;
    }

    /** \brief Set new table object.
    \param string|ZfBlank_DbTable_SiteMenu $table table class name or instance
    \return $this
    */
    public function setTable ($table) {
        if (is_string($table)) {
            $tableclass = $table;
            $this->_table = new $tableclass;
        } else if (!($table instanceof ZfBlank_DbTable_SiteMenu)) {
            throw new Zend_Exception(
                'The argument is neither a string nor a valid table object.');
        } else {
            $this->_table = $table;
        }

        return $this;
    }

    /** \brief Set depth of this menu item.
    \param integer $depth the depth
    \return $this
    */
    public function setMenuDepth ($depth) {
        $this->_menuDepth = (int) $depth;
        return $this;
    }

    /** \brief Get depth of this menu item.
    \return integer: the depth
    */
    public function getMenuDepth () {
        return $this->_menuDepth;
    }
    
    /** \brief Set parent menu item
    \param Zend_Navigation_Container $parent the parent
    \return void
    */
    public function setParent (Zend_Navigation_Container $parent = NULL) {
        parent::setParent($parent);
        $this->setMenuDepth($parent->getMenuDepth() + 1);
    }

    /** \brief Load subtree or children of this menu item from database table.

    \zfb_read SiteMenu..loadMenu
    */
    public function loadMenu ($table = null, $recursion = true) {
        if ($table === null) $table = $this->getTable();

        $rows = $table->findForParent ($this->getId());

        if ($rows->count()) {
            $menus = array();
            
            foreach ($rows as $row) {
                $class = __CLASS__;
                $item = new $class;

                $item->setId($row->ID)
                     ->setOrder($row->Position)
                     ->setTitle($row->Name)
                     ->setLabel($row->Name)
                     ->setUri($row->Link);

                $menus[] = $item;
            }

            unset ($rows);

            if ($recursion) foreach ($menus as $item) {
                $item->loadMenu($table);
            }

            $this->addPages($menus);
        }

        return $this;
    }
    
    /*protected function _load () {
        $row = $this->getTable()->find($this->getId())->getRow(0);

        $this->setOrder($row->Position)
             ->setTitle($row->Name)
             ->setLabel($row->Name)
             ->setUri($row->Link)
             ->setMenuDepth($row->MenuDepth);

        return $this;
        
    }*/

    /** \brief Save item with or without its subtree to database table.

    \zfb_read SiteMenu..save
    */
    public function save ($table = null, $recursion = true) {
        if ($table === null) $table = $this->getTable();

        if ($this->getId() === null) {
            $row = $table()->createRow();
        } else {
            $row = $table()->find($id)->getRow(0);
        }

        $parent = $this->getParent();
        $row->ParentID = $parent === null ? null : $parent->getId();
        $row->Name = $this->getTitle();
        $row->Link = $this->getUri();
        $row->MenuDepth = $this->getMenuDepth();
        $row->Position = $this->getOrder();

        $row->save();
        $this->setId($row->ID);
        unset ($row);

        if ($recursion && $this->hasPages()) {
            foreach ($this->getPages() as $item) {
                $item->save($table);
            }
        }

        return $this->getId();
    }

}
