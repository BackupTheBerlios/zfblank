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


class Application_Form_Comment extends ZfBlank_Form
{

    public function init ()
    {
        $this->setMethod('POST');

        $this->addElement('text', 'author', array(
            'label' => 'Your name:',
            'required' => true,
            'filters' => array('StringTrim'),
        ));

        $this->addElement('text', 'title', array(
            'label' => 'Comment title:',
            'filters' => array('StringTrim'),
        ));

        $this->addElement('textarea', 'text', array(
            'label' => 'Comment:',
            'required' => true,
            'filters' => array('StringTrim'),
        ));

        $this->addElement('captcha', 'captcha', array(
            'label' => 'Enter code:',
            'required' => true,
            'ignore' => true,
            'captcha' => 'image',
        ));

        $this->getElement('captcha')->getCaptcha()
             ->setFont(APPLICATION_PATH . '/fonts/AHGBold.ttf');

        $this->addElement('submit', 'submit', array(
            'label' => 'Send',
            'ignore' => true,
        ));
    }

    public function setItemId ($id)
    {
        $this->setAction($this->getAction() . '/id/' . $id);
        return $this;
    }


}
