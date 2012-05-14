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

/** \brief Renders entire site menu. Extends Zend_View_Helper_Navigation_Menu.
    \zfb_read View_Helper_SiteMenu
*/

class ZfBlank_View_Helper_SiteMenu extends Zend_View_Helper_Navigation_Menu
{

    /** \brief Render site menu.
    \zfb_read View_Helper_SiteMenu..siteMenu
    */
    public function siteMenu ($table = null) {
        $menu = new ZfBlank_SiteMenu();
        
        $this->setContainer($menu->loadMenu(
            $table !== null ? new $table : null
        ));

        return $this;
    }

}
