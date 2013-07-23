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

/** \brief Base table for ZfBlank_Item
    \zfb_read DbTable_Items
*/

class ZfBlank_DbTable_Items extends ZfBlank_DbTable_Tree
{

    protected $_rowClass = 'ZfBlank_Item'; /**< \brief Row class name */

    /** \var string or ZfBlank_DbTable_Abstract $_categoriesTable
    \brief Categories table or table class name. */
    protected $_categoriesTable = 'ZfBlank_DbTable_Categories';

    /** \var string $_categoriesTableByName
    \brief If is not **null**, create table using class from
    \ref $_categoriesTable and physical name from here. */
    protected $_categoriesTableByName = null;

    /** \var string or ZfBlank_DbTable_Tags $_tagsTable
    \brief Tags table or table class name.  */
    protected $_tagsTable = 'ZfBlank_DbTable_Tags';

    /** \var string $_tagsTableByName
    \brief If is not **null**, create table using class from
    \ref $_tagsTable and physical name from here. */
    protected $_tagsTableByName = null;

    /** \brief Tags many-to-many association table physical name.  */
    protected $_tagsAssocTableName = 'ItemTags';

    /** \brief item foreign key column name in association table */
    protected $_tagsAssocItemColumn = 'Item';

    /** \brief tag foreign key column name in association table */
    protected $_tagsAssocTagColumn = 'Tag';

    /** \brief Creates categories and tags tables if physical names are given
    and calls parent constructor.
    \param array $config see **Zend_Db_Table_Abstract::__construct** */
    public function __construct (array $config = array())
    {
        $db = $this->getAdapter();

        if (class_exists($this->_categoriesTable) 
            && $this->_categoriesTableByName !== null
        ) {
            $class = $this->_categoriesTable;

            $table = new $class(array(
                'db' => $db,
                'name' => $this->_categoriesTableByName,
            ));
            
            $table->itemTable($this);
            $this->_categoriesTable = $table;
        }

        if (class_exists($this->_tagsTable) 
            && $this->_tagsTableByName !== null
        ) {
            $class = $this->_tagsTable;
            
            $table = new $class(array(
                'db' => $db,
                'name' => $this->_tagsTableByName,
            ));

            $table->itemsTable($this);
            $this->_tagsTable = $table;
        }

        parent::__construct($config);
    }

    /** \brief Set tags many-to-many association table physical name.
    \param string $tagsAssocTable table name \return $this */
    public function setTagsAssocTableName ($tagsAssocTable)
    {
        $this->_tagsAssocTableName = $tagsAssocTable;
        return $this;
    }

    /** \brief Get tags many-to-many association table physical name.
    \return string: table name */
    public function getTagsAssocTableName ()
    {
        return $this->_tagsAssocTableName;
    }

    /** \brief Set item foreign key column name in association table
    \param $tagsAssocItemColumn column name \return $this */
    public function setTagsAssocItemColumn ($tagsAssocItemColumn)
    {
        $this->_tagsAssocItemColumn = $tagsAssocItemColumn;
        return $this;
    }

    /** \brief Get item foreign key column name in association table
    \return string: column name */
    public function getTagsAssocItemColumn ()
    {
        return $this->_tagsAssocItemColumn;
    }

    /** \brief Set tag foreign key column name in association table
    \param $tagsAssocTagColumn column name \return $this */
    public function setTagsAssocTagColumn ($tagsAssocTagColumn)
    {
        $this->_tagsAssocTagColumn = $tagsAssocTagColumn;
        return $this;
    }

    /** \brief Get tag foreign key column name in association table
    \return string: column name */
    public function getTagsAssocTagColumn ()
    {
        return $this->_tagsAssocTagColumn;
    }

    /** \brief Set or get instance of associated categories table.
    \param string|ZfBlank_DbTable_Abstract $table new table or table class name
    \return ZfBlank_DbTable: current or previous categories table */
    public function categoriesTable ($table = null)
    {
        if ($table !== null) $this->_categoriesTable = $table;
        $table = $this->_lazyLoad($this->_categoriesTable);
        $table->itemTable($this);
        return $table;
    }

    /** \brief Set or get instance of associated tags table.
    \param string|ZfBlank_DbTable_Abstract $table new table or table class name
    \return ZfBlank_DbTable: current or previous table */
    public function tagsTable ($table = null)
    {
        if ($table !== null) $this->_tagsTable = $table;
        $table = $this->_lazyLoad($this->_tagsTable);
        $table->itemsTable($this);
        $table->setAssocTableName($this->_tagsAssocTableName);
        return $table;
    }

}
