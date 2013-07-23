<?php

/*
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

class Application_Plugin_ModuleLayout extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $layout = Zend_Layout::getMvcInstance();

        $layoutsDir = $layout->getLayoutPath();

        if (file_exists($layoutsDir.DIRECTORY_SEPARATOR.$module.'.phtml')) {
            $layout->setLayout($module);
        } else {
            $layout->setLayout('layout');
        }
    }
}

