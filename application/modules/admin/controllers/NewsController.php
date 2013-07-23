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

class Admin_NewsController extends Zend_Controller_Action
{

    protected $_table;

    public function init()
    {
        $this->_table = new Application_Model_DbTable_News();
    }

    public function indexAction()
    {
        $this->view->table = $this->_table;
    }

    public function editAction()
    {
        $this->view->form = $form = new Admin_Form_NewsItem();

        if (($id = $this->getRequest()->getParam('id')) !== null) {
            $item = $this->_table->find($id)->getRow(0);
            $form->setDefaults($item->getValues());
            $form->setDefault('tags', implode(',', $item->getTags()));
            $form->setDefault('new', 0);
            $form->setPage($this->getRequest()->getQuery('page'));
        }

    }

    public function saveAction ()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $page = $request->getQuery('page');
        $table = $this->_table;
        $item = $post['new']
              ? $this->_table->createRow()->setTime(new Zend_Date())
              : $this->_table->find($post['id'])->getRow(0);

        if ($item->validateForm($post, new Admin_Form_NewsItem())) {
            $item->setFromForm()->generateAbstract(true)->save();
            $this->_redirect('/admin/news?page=' . $page);
        }

        $this->view->form = $item->form()->setPage($page);
    }

    public function deleteAction ()
    {
        $request = $this->getRequest();
        $page = '?page=' . $request->getQuery('page');

        if (($id = $request->getParam('id')) === null)
            $this->_redirect('/admin/news' . $page);

        $id = intval($id);
        $this->_table->delete(array('ID = ?' => $id));

        $this->_redirect(
            '/admin/news/category/id/' . $request->getParam('category') . $page
        );
    }

}
