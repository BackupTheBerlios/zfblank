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

class Admin_Form_Login extends ZfBlank_Form
{

    public function init()
    {
        $this->setAction('/admin/index/login')->setMethod('POST');
        $this->addElement('text', 'username', array(
            'label' => 'Login:',
            'required' => true,
            'filters' => array('StringTrim'),
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password:',
            'required' => true,
        ));
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Enter',
        ));

        $this->loadDefaultDecorators();
        $this->addDecorator('formErrors', array('placement'=> 'prepend'));
    }

    public function exportValuesArray() {
        return $this->getValues();
    }

}

