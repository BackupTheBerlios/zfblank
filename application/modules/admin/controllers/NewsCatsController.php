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

class Admin_NewsCatsController extends Zend_Controller_Action
{

    private $_table;

    public function init()
    {
        $this->_helper->ajaxContext()       /* initializing AJAX context */
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

    public function fieldNamesAction ()     //field names sending action
    {
        $post = $this->getRequest()->getPost();
        $row = $this->_table->createRow();

        foreach (array_keys($row->dataMap()) as $name) {
            $this->view->$name = $name;
        }
        //sends JSON with data in form:   field_name: field_name
    }

    public function nodeValuesAction ()    //a node field values sending action
    {
        $post = $this->getRequest()->getPost();
        $row = $this->_table->find(intval($post['id']))->getRow(0);

        foreach ($row->getValues() as $name => $value) {
            $this->view->$name = $value;
        }
        //sends JSON with data in form:   field_name: field_value
    }

    public function addAction()        // new node adding action
    {
        $post = $this->getRequest()->getPost();
        $table = $this->_table;

        $parent = trim($post['parent']) == '' 
                ? $table->createRow()
                : $table->find(intval($post['parent']))->getRow(0);
            
        $parent->loadChildren();
        $child = $table->createRow();
        $child->setName($post['name']);
        $parent->addChild($child, intval($post['offset']), true);
        $this->view->id = $child->getId();
        //sends JSON with data:  "id": new_node_id
    }

    public function editAction()       // node editing (saving) action
    {
        $post = $this->getRequest()->getPost();
        $table = $this->_table;
        $id = intval($post['id']);

        $this->view->id = $this->_table->find($id)->getRow(0)
                                       ->setName($post['name'])->save();
        //sends JSON with data:  "id": node_id
    }

    public function branchAction()     // node branching (creating first child node) action
    {
        $post = $this->getRequest()->getPost();
        $table = $this->_table;
        $parent = trim($post['parent']) == '' ?  null : intval($post['parent']);
        
        $this->view->id = $table->createRow()->setParent($parent)
                                             ->setOffset(0)
                                             ->setName($post['name'])
                                             ->save();
    }

    public function deleteAction()      // node(s) deleting action
    {
        $node = $this->_table->find($id = $this->getRequest()->getPost('id'))
                             ->getRow(0)->loadTree();
        if ($node->getName() != 'Uncategorized') $node->delete();

        $news = new Application_Model_DbTable_News();
        
        foreach ($news->findForCategory($id) as $item) {
            $item->categorySetByName('Uncategorized');
            $item->save();
        }
    }

    public function indexAction()
    {
    }

}
