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

/** \brief Page Content view helper. \see ZfBlank_PageContent
    \ingroup grp_pagecontent
*/

class ZfBlank_View_Helper_PageContent extends Zend_View_Helper_Abstract
{

    protected $_tableClass = 'ZfBlank_DbTable_PageContent'; /**< \brief
    default page content table */

    /** \brief Get named page content block from DB table.
    \zfb_read View_Helper_PageContent..pageContent */
    public function pageContent ($name, $table = null)
    {
        if ($table !== null) {
            if (is_string($table)) $table = new $table;

            if (!($table instanceof ZfBlank_DbTable_PageContent)) {
                throw new Zend_Exception ('Table must be Page Content table');
            }
        } else {
            $class = $this->_tableClass;
            $table = new $class;
        }

        $rows = $table->findFor('name', $name);

        if ($rows->count()) return $rows->getRow(0);

        return $table->createRow();
    }

}
