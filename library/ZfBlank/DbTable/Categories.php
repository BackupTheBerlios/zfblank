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

/** \brief Base table class for categories (ZfBlank_Category).
    \zfb_read DbTable_Categories
*/
class ZfBlank_DbTable_Categories extends ZfBlank_DbTable_Tree
{
    
    protected $_rowClass = 'ZfBlank_Category'; /**< \brief Row class */

    /** \var string or **Zend_Db_Table_Abstract** $_itemTable
    \brief categorized items table or table class name */
    protected $_itemTable = 'ZfBlank_DbTable_Items';

    /** \brief Set/get categorized items table.
    \zfb_read Category..itemTable */
    public function itemTable ($table = null)
    {
        if ($table !== null) $this->_itemTable = $table;
        return $this->_lazyLoad($this->_itemTable);
    }

}
