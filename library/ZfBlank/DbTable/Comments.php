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

/** \brief Base table for comments (ZfBlank_Comment)
    \zfb_read DbTable_Comments
*/

class ZfBlank_DbTable_Comments extends ZfBlank_DbTable_Tree
{

    protected $_rowClass = 'ZfBlank_Comment'; /**< \brief Row class */

    /** \var string or **Zend_Db_Table_Abstract** $_itemTable
    \brief commented items table or table class name */
    protected $_itemTable = 'ZfBlank_DbTable_Commentable';

    /** \var string or ZfBlank_DbTable_Users $_usersTable
    \brief Users table or table class; **null** if not used. */
    protected $_usersTable = null;

    /** \brief Set/get items table.
    \zfb_read DbTable_Commentable..itemTable */
    public function itemTable ($table = null)
    {
        if ($table !== null) $this->_itemTable = $table;
        return $this->_lazyLoad($this->_itemTable);
    }

    /** \brief Get users table if supported.
    \return ZfBlank_DbTable_Users: table object or **null** */
    public function getUsersTable ()
    {
        if ($this->_usersTable !== null) {
            return $this->_lazyLoad($this->_usersTable);
        }

        return null;
    }

}
