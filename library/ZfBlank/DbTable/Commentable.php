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

/** \brief Base table for ZfBlank_Commentable 
    \zfb_read DbTable_Commentable 
*/

class ZfBlank_DbTable_Commentable extends ZfBlank_DbTable_Items
{

    protected $_rowClass = 'ZfBlank_Commentable'; /**< \brief Row class */

    /** \var string or ZfBlank_DbTable_Comments $_commentsTable
    \brief Comments table or table class name. */
    protected $_commentsTable = 'ZfBlank_DbTable_Comments';

    /** \brief Set or get instance of associated comments table.
    \param string|ZfBlank_DbTable_Abstract $table new table or table class name
    \return ZfBlank_DbTable: current or previous categories table */
    public function commentsTable ($table = null)
    {
        if ($table !== null) $this->_commentsTable = $table;
        $table = $this->_lazyLoad($this->_commentsTable);
        $table->itemTable($this);
        return $table;
    }

}
