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

class NewsController extends Zend_Controller_Action
{

    public function indexAction ()
    {
        $this->view->table = new Application_Model_DbTable_News();
    }

    public function readAction ()
    {
        if (($id = $this->getRequest()->getParam('id')) === null)
            $this->_redirect('/news');

        $id = intval($id);
        $table = new Application_Model_DbTable_News();
        $this->view->item = $table->find($id)->getRow(0);
        $form = new Application_Form_NewsComment();
        $this->view->form = $form->setItemId($id);
        $this->view->comments = $table->commentsTable()->select()
                                      ->where('ID = ?', $id)
                                      ->order('PubTimeStamp DESC');
    }

    public function commentAction ()
    {
        $request = $this->getRequest();

        if (($id = $request->getParam('id')) === null)
            $this->_redirect('/news');

        $table = new Application_Model_DbTable_NewsComments();
        $comment = $table->createRow();
        $comment->form(new Application_Form_NewsComment());

        if ($comment->validateForm($request->getPost())) {
            $comment->setFromForm()->setItem($id)->save();
            $this->_redirect('/news/read/id/' . $id);
        }

        $this->view->form = $comment->form()->setItemId($id);
    }

    public function byTagAction ()
    {
        $table = new ZfBlank_DbTable_Tags(array('name' => 'NewsTags'));
        $table->setAssocTableName('NewsTagsAssoc')
              ->itemsTable('Application_Model_DbTable_News');

        $this->view->select = $table->itemsSelect(
            $this->getRequest()->getParam('id'))->order('PubTimeStamp DESC');
    }
        
    public function byCategoryAction ()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $table = new Application_Model_DbTable_News();
        $this->view->category = $table->categoriesTable()->find($id)->getRow(0);
        $this->view->select = $table->select()->where('CategoryID = ?', $id);
    }

    public function categoriesAction () {}

}
