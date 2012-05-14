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

// Controller for demo tree editor. AJAX editing methods return fake random IDs.

class TreeEditController extends Zend_Controller_Action
{

    private $_table;

    public function init()
    {
        $this->_helper->ajaxContext()
            ->addActionContexts(array(
                'field-names' => 'json',
                'node-values' => 'json',
                'add' => 'json',
                'delete' => 'json',
                'edit' => 'json',
                'branch' => 'json',
            ))->initContext();

        $this->_table = new ZfBlank_DbTable_Categories(array(
            'name' => 'NewsCategories',
        ));
    }

    public function fieldNamesAction ()
    {
        $post = $this->getRequest()->getPost();
        $row = $this->_table->createRow();

        foreach (array_keys($row->dataMap()) as $name) {
            $this->view->$name = $name;
        }
    }

    public function nodeValuesAction ()
    {
        $post = $this->getRequest()->getPost();
        $row = $this->_table->find(intval($post['id']))->getRow(0);

        foreach ($row->getValues() as $name => $value) {
            $this->view->$name = $value;
        }
    }

    public function addAction()
    {
        $this->view->id = rand (1, 10000);
    }

    public function editAction()
    {
        $this->view->id = (int) $this->getRequest()->getPost('id');
    }

    public function branchAction()
    {
        $post = $this->getRequest()->getPost();
        $this->view->id = rand (1, 10000);
    }

    public function deleteAction() {}

    public function indexAction() {}

}

