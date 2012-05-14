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

class Admin_Form_Faq extends ZfBlank_Form
{

    public function init ()
    {
        $this->setMethod('POST')->setAction('/admin/faq/save');
        
        $this->addElement('text', 'id', array(
            'label' => 'ID:',
            'readonly' => true,
            'filters' => array('StringTrim'),
        ));

        $category = new Zend_Form_Element_Select('category');
        $cats = new ZfBlank_DbTable_Categories(array('name'=>'FAQCategories'));
        $cats = $cats->createRow()->loadTree()->render('IndentedStrings',
            array ('indentSeq' => '- - ')
        );
        $category->addMultiOptions($cats);

        $new = new Zend_Form_Element_Hidden('new');
        $new->setValue(1)->setIgnore(true)->removeDecorator('label')
                                          ->removeDecorator('HtmlTag');

        $this->addElements(array($category->setLabel('Category:'), $new));

        $this->addElement('text', 'question', array(
            'label' => 'Question:',
            'required' => true,
        ));

        $this->addElement('textarea', 'answer', array(
            'label' => 'Answer:',
            'required' => true,
        ));

        $this->addElement('submit', 'submit', array(
            'label' => 'Save',
            'ignore' => true,
        ));

    }

}
