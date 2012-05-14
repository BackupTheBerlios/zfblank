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

/** \brief Creates paginator for given Zend_Db_Table_Select object.
    \zfb_read View_Helper_PaginateSelect
*/

class ZfBlank_View_Helper_PaginateSelect extends Zend_View_Helper_Abstract
{

    /** \brief Create paginator for given Zend_Db_Table_Select object.
    
    \zfb_read View_Helper_PaginateSelect..paginateSelect */
    public function paginateSelect ($select) {
        $paginator = new Zend_Paginator (
            new Zend_Paginator_Adapter_DbTableSelect($select));
        $page = Zend_Controller_Front::getInstance()->getRequest()
            ->getQuery('page');

        $page = $page ? intval($page) : 1;
        $page = ($page !== 0) ? $page : 1;
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
}
