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

/** \brief Base tag class in ZF_Blanks
    \zfb_read Tag
*/

class ZfBlank_Tag extends ZfBlank_ActiveRow_Abstract
                  implements Zend_Tag_Taggable
{

    /** \var array $_dataMap
    \brief Data Map \see \ref datamap_tree "Data Map" */
    protected $_dataMap = array (
        'id' => 'ID',
        'title' => 'Name',
        'weight' => 'Weight',
    );

    /** \brief Get tag title
    \zfb_read Tag..getTitle */
    public function getTitle ()
    {
        return $this->title;
    }

    /** \brief Get tag weight
    \zfb_read Tag..getWeight */
    public function getWeight ()
    {
        return $this->weight;
    }

    /** \brief Set parameter
    \zfb_read Tag..setParam */
    public function setParam ($name, $value)
    {
        return $this->setValues(array($name => $value));
    }

    /** \brief Get parameter
    \zfb_read Tag..getParam */
    public function getParam ($name)
    {
        return $this->$name;
    }
        
    /** \brief Count tag weight from association table 
    \zfb_read Tag..countWeight */
    public function countWeight ()
    {
        $table = $this->getTable();
        $db = $table->getAdapter();
        $assoc = $table->getAssocTableName();
        $column = $db->quoteIdentifier($table->getAssocTagColumn());

        $select = $db->select()
                     ->from($assoc, array('num' => "COUNT($column)"))
                     ->where("$column = ?", $this->id);

        $this->weight = $db->fetchOne($select);
        return $this;
    }

    /** \brief Get items tagged by this tag.
    \note The object must be connected to tags table having items and 
    association tables set properly.
    \param string $order how to order items; identical to
    **Zend_Db_Table::order()** method parameter (optional)
    \return **Zend_Db_Table_Rowset_Abstract** items */
    public function items ($order = null)
    {
        $table = $this->getTable();
        $select = $table->itemsSelect($this->id);

        if ($order !== null) $select->order($order);
    
        return $table->itemsTable()->fetchAll($select);

    }

}
