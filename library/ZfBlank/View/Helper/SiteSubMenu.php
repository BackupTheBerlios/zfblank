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

/** \brief Renders one branch of site menu.
    Extends Zend_View_Helper_Navigation_Menu.
    \ingroup grp_sitemenu
    \zfb_read View_Helper_SiteSubMenu
*/

class ZfBlank_View_Helper_SiteSubMenu extends Zend_View_Helper_Navigation_Menu
{

    /** \brief Render one site menu branch.

    \zfb_read View_Helper_SiteSubMenu..siteSubMenu
    */
    public function siteSubMenu ($parent = null, $table = null,
            $method = 'id')
    {
        $menu = new ZfBlank_SiteMenu();

        if ($parent !== null) {
            if (!$table) $table = new ZfBlank_DbTable_SiteMenu();

            switch ($method) {
                case 'name':
                    $menu->setId($table->findForName($parent)->getRow(0)->ID);
                    break;
                case 'link':
                    $menu->setId($table->findForLink($parent)->getRow(0)->ID);
                    break;
                case 'id':
                    $menu->setId($table->find($parent)->getRow(0)->ID);
                    break;
                default:
                    throw new Zend_Exception (
                        'Argument $method possible values are: '
                        . "'name', 'link', 'id'."
                    );
            }
        }

        $this->setContainer($menu->loadMenu($table, false));
        return $this;
            
    }

}
