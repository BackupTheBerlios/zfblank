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

class Admin_FaqController extends Zend_Controller_Action
{

    public function indexAction ()
    {
    }

    public function categoryAction ()
    {
        if (($id = $this->getRequest()->getParam('id')) === null)
            $this->_redirect ('/admin/faq');

        $id = intval($id);
        $table = new ZfBlank_DbTable_Faq();
        $this->view->category = $id;
        $this->view->questions = $table->findForCategory($id);
    }

    public function editAction ()
    {
        $form = new Admin_Form_Faq();

        if (($id = $this->getRequest()->getParam('id')) !== null) {
            $table = new ZfBlank_DbTable_Faq();
            $form->setDefaults($table->find(intval($id))->getRow(0)
                                                        ->getValues());
            $form->setDefault('new', 0);
        }

        $this->view->form = $form;
    }

    public function saveAction ()
    {
        $post = $this->getRequest()->getPost();
        $table = new ZfBlank_DbTable_Faq();
        $faq = $post['new'] ? $table->createRow()
                            : $table->find($post['id'])->getRow(0);

        if ($faq->validateForm($post, new Admin_Form_Faq())) {
            $faq->setFromForm()->save();
            $this->_redirect('/admin/faq');
        }

        $this->view->form = $faq->form();
    }

    public function deleteAction ()
    {
        if (($id = $this->getRequest()->getParam('id')) === null)
            $this->_redirect('/admin/faq');

        $id = intval($id);
        $table = new ZfBlank_DbTable_Faq();
        $table->delete(array('ID = ?' => $id));
        $this->_redirect('/admin/faq/category/id/'.$this->getRequest()
                                                        ->getParam('category'));
    }

}
