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

class Admin_Form_Page extends ZfBlank_Form
{

    public function init()
    {
        $this->setAction('/admin/pages/save')->setMethod('POST');

		$this->addElement('text', 'name', array(
			'label' => 'Name:',
			'required' => true,
			'filters' => array('StringTrim'),
		));


        $this->addElement('text', 'title', array(
            'label' => 'Title:',
            'filters' => array('StringTrim'),
        ));

        $this->addElement('text', 'description', array(
            'label' => 'Description:',
            'filters' => array('StringTrim'),
        ));

        $this->addElement('text', 'tags', array(
            'label' => 'Tags:',
            'filters' => array('StringTrim'),
        ));

        $storage = new Zend_Form_Element_Select('storage');
        $storage->setLabel('Storage Type:')
                ->setMultiOptions(array( 'db'=>'database', 'file'=>'file',));

        $newPage = new Zend_Form_Element_Hidden('new');
        $newPage->setValue(1)
                ->removeDecorator('label')->removeDecorator('HtmlTag');

        $this->addElements(array($storage, $newPage));

        $this->addElement('textarea', 'text', array(
            'label' => 'Text:',
            'required' => true,
        ));

        $this->addElement('submit', 'submit', array(
            'label' => 'Save',
            'ignore' => true,
        ));
    }

}
