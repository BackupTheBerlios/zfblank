<?php

    /**
    \file
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

    /** \brief User accounting table.
    
    \zfb_read DbTable_Users
    */

class ZfBlank_DbTable_Users extends ZfBlank_DbTable_Abstract
{

    protected $_name = 'Users'; /**< \brief Physical table name */
    protected $_rowClass = 'ZfBlank_User'; /**< \brief Row Class name */

    /** \brief Identify the user currently logged in (if any).

    \zfb_read DbTable_Users..identify
    */
    public function identify() 
    {
        Zend_Auth::getInstance()->getIdentity();
    }
}

