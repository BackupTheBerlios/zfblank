<?php

/*
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

class Application_Plugin_AdminLogin 
        extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch($request) {
        if ($request->getModuleName() == 'admin' && !(
                $request->getControllerName() == 'index' &&
                $request->getActionName() == 'login')) {
            $auth = Zend_Auth::getInstance();
            
            if (!$auth->hasIdentity() || $auth->getIdentity() != 'admin') {
                $request->setDispatched(true);
                $this->_response->setRedirect('/admin/index/login');
            }
        }
    }

}
