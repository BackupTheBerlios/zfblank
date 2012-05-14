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

/** \brief Base table for ZfBlank_Tag
    \zfb_read DbTable_Tags
*/

class ZfBlank_DbTable_Tags extends ZfBlank_DbTable_Abstract
{

    protected $_rowClass = 'ZfBlank_Tag'; /**< \brief Row class name */

    /** \var string or ZfBlank_DbTable_Abstract $_itemsTable
    \brief Tagged items table or table class name. */
    protected $_itemsTable = 'ZfBlank_Items';

    /** \brief Many-to-many association table physical name. */
    protected $_assocTableName = 'ItemTags';

    /** \brief Tagged item foreign key column name in association table */
    protected $_assocItemColumn = 'Item';

    /** \brief Tag foreign key column name in association table */
    protected $_assocTagColumn = 'Tag';

    /** \brief Set many-to-many association table physical name.
    \param string $assocTable table name \return $this */
    public function setAssocTableName ($assocTable)
    {
        $this->_assocTableName = $assocTable;
        return $this;
    }

    /** \brief Get tags many-to-many association table physical name.
    \return string: table name */
    public function getAssocTableName ()
    {
        return $this->_assocTableName;
    }

    /** \brief Set tagged item foreign key column name in association table
    \param $assocItemColumn column name \return $this */
    public function setAssocItemColumn ($assocItemColumn)
    {
        $this->_assocItemColumn = $assocItemColumn;
        return $this;
    }

    /** \brief Get tagged item foreign key column name in association table
    \return string: column name */
    public function getAssocItemColumn ()
    {
        return $this->_assocItemColumn;
    }

    /** \brief Set tag foreign key column name in association table
    \param $assocTagColumn column name \return $this */
    public function setAssocTagColumn ($assocTagColumn)
    {
        $this->_assocTagColumn = $assocTagColumn;
        return $this;
    }

    /** \brief Get tag foreign key column name in association table
    \return string: column name */
    public function getAssocTagColumn ()
    {
        return $this->_assocTagColumn;
    }

    /** \brief Set or get instance of associated tagged items table.
    \param string|ZfBlank_DbTable_Abstract $table new table or table class name
    \return ZfBlank_DbTable: current or previous table */
    public function itemsTable ($table = null)
    {
        if ($table !== null) $this->_itemsTable = $table;
        return $this->_lazyLoad($this->_itemsTable);
    }

    /** \brief Get select object for items tagged by a tag.
    \note The table must have items and association tables set properly.
    \param mixed $id ID of the tag to select items for
    \return **Zend_Db_Select** select object */
    public function itemsSelect ($id)
    {
        return $this->atmSelect(
            $id,
            $this->itemsTable(),
            $this->_assocTableName,
            $this->_assocTagColumn,
            $this->_assocItemColumn
        );
    }

}
