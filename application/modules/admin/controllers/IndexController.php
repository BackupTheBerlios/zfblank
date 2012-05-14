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

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $table = new ZfBlank_DbTable_Users();
            $user = $table->createRow();
            if ($user->validateForm($request->getPost(), 
                    new Admin_Form_Login())) {
                if ($user->login()) {  // it's only one registered user yet :)
                    $this->_redirect('/admin/index');
                } else {
                    $user->form()->addError('Login failed.');
                }
            }
            $this->view->form = $user->form();
        } else {
            $this->view->form = new Admin_Form_Login();
        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/admin/index/login/');
    }


}


