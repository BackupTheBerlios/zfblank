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

class Admin_PagesController extends Zend_Controller_Action
{

    protected $_table;

    public function init()
    {
        $this->_table = new ZfBlank_DbTable_PageContent();
    }

    public function indexAction()
    {
        $table = $this->_table;

        $select = $table->select()->from($table->info('name'), array(
                'Name', 'Title', 'TextStorage',
        ));

        $this->view->pages = $table->fetchAll($select);
    }

    public function editAction()
    {
        $table = $this->_table;
        $this->view->form = new Admin_Form_Page();

		if (($name = $this->getRequest()->getQuery('name')) !== null) {
            $page = $table->find($name)->getRow(0);
            $this->view->form->setDefaults($page->getValues())
                             ->setDefault('new', 0)
                             ->getElement('name')
                             ->setAttrib('readonly', 'readonly');
		}
    }

    public function saveAction()
    {
        $post = $this->getRequest()->getPost();
        $table = $this->_table;
        $form = new Admin_Form_Page();
        if ($form->isValid($post)) {
            if ($post['new']) {
                $page = $table->createRow();

                if (!$page->uniqueName($post['name'])) {
                    $form->getElement('name')
                         ->addError('The name given is already in use.');
                } else {
                    $page->setFromForm($form)->save();
                    $this->_redirect('/admin/pages/index');
                }
            } else {
                $table->find($post['name'])->getRow(0)->setFromForm($form)
                                                      ->save();
                $this->_redirect('/admin/pages/index');
            }
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $this->_table->find($this->getRequest()->getQuery('name'))
                     ->getRow(0)
                     ->deleteFile()
                     ->delete();
        $this->_redirect('/admin/pages/index');
    }

}
