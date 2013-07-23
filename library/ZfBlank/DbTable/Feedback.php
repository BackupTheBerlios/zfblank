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

/** \brief Table for site users' feedbacks (ZfBlank_Feedback).
    \zfb_read DbTable_Feedback
*/

class ZfBlank_DbTable_Feedback extends ZfBlank_DbTable_Abstract
{

    protected $_name = 'Feedback'; /**< \brief Physical table name. */
    protected $_rowClass = 'ZfBlank_Feedback'; /**< \brief Row Class name */

    /** \var string or ZfBlank_DbTable_Users $_usersTable
    \brief Users table or table class; **null** if not used. */
    protected $_usersTable = null;

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


