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

// Controller for menu edit demo. AJAX edit actions return fake random IDs.

class MenuEditController extends Zend_Controller_Action
{

    public function init ()
    {
        $this->_helper->ajaxContext()
            ->addActionContext('add', 'json')
            ->addActionContext('delete', 'json')
            ->addActionContext('edit', 'json')
            ->addActionContext('submenu', 'json')
            ->initContext();
    }

    public function addAction()
    {
        $this->view->id = rand (1, 10000);
    }

    public function editAction()
    {
        $this->view->id = rand (1, 10000);
    }

    public function submenuAction()
    {
        $this->view->id = rand (1, 10000);
    }

    public function deleteAction() {}
    
    public function indexAction () {}

}
