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


class Admin_GeneralController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->ajaxContext()       
            ->addActionContext('admin-add', 'json')
            ->addActionContext('admin-delete', 'json')
            ->addActionContext('admin-edit', 'json')
            ->addActionContext('admin-submenu', 'json');

        /** [Menu Editor Init]*/
        $this->_helper->ajaxContext()       /* initializing AJAX context */
            ->addActionContext('menu-add', 'json')
            ->addActionContext('menu-delete', 'json')
            ->addActionContext('menu-edit', 'json')
            ->addActionContext('menu-submenu', 'json')
            ->initContext();
        /** [Menu Editor Init] */
    }

    /** [Menu Editor AJAX Actions] */
    public function menuAddAction()        // new node adding action
    {
        $post = $this->getRequest()->getPost();
        $table = new ZfBlank_DbTable_SiteMenu();

        $this->view->id = $table->addItem (
            isset ($post['parent']) ? $post['parent'] : null,
            $post['name'],
            $post['link'],
            ++$post['position']
        );

        //sends JSON with data:  "id": new_item_id
    }

    public function menuEditAction()       // node editing (saving) action
    {
        $post = $this->getRequest()->getPost();
        $table = new ZfBlank_DbTable_SiteMenu();

        $this->view->id = $table->edit (
            $post['id'],
            $post['name'],
            $post['link']
        );

        //sends JSON with data:  "id": item_id
    }

    public function menuSubmenuAction()     // node branching (creating submenu) action
    {
        $post = $this->getRequest()->getPost();
        $table = new ZfBlank_DbTable_SiteMenu();

        $this->view->id = $table->addItem (
            isset ($post['parent']) ? $post['parent'] : null,
            $post['name'],
            $post['link']
        );
        
        //sends JSON with data:  "id": new_item_id
    }

    public function menuDeleteAction()      // node(s) deleting action
    {
        $table = new ZfBlank_DbTable_SiteMenu();
        $table->deleteItems(explode(',', $this->getRequest()->getPost('ids')));
    }
    /** [Menu Editor AJAX Actions] */

    public function indexAction()
    {
    }

    public function optionsAction()
    {
    }

    public function menuAction()
    {
    }

    public function adminMenuAction()
    {
    }

    public function adminAddAction()    
    {
        $post = $this->getRequest()->getPost();
        $table = new Admin_Model_DbTable_AdminMenu();

        $this->view->id = $table->addItem (
            isset ($post['parent']) ? $post['parent'] : null,
            $post['name'],
            $post['link'],
            ++$post['position']
        );

    }

    public function adminEditAction()
    {
        $post = $this->getRequest()->getPost();
        $table = new Admin_Model_DbTable_AdminMenu();

        $this->view->id = $table->edit (
            $post['id'],
            $post['name'],
            $post['link']
        );
    }

    public function adminSubmenuAction()
    {
        $post = $this->getRequest()->getPost();
        $table = new Admin_Model_DbTable_AdminMenu();

        $this->view->id = $table->addItem (
            isset ($post['parent']) ? $post['parent'] : null,
            $post['name'],
            $post['link']
        );
    }

    public function adminDeleteAction()
    {
        $table = new Admin_Model_DbTable_AdminMenu();
        $table->deleteItems(explode(',', $this->getRequest()->getPost('ids')));
    }
}
