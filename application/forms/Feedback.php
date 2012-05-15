<?php

/** \file
    \author Serge V. Baumer \<baumer at users.berlios.de\>

    \section LICENSE

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

class Application_Form_Feedback extends ZfBlank_Form
{

    public function init ()
    {
        $this->setMethod('POST')->setAction('/feedback/send');

        $this->addElement('text', 'author', array(
            'label' => 'Your name:',
            'required' => true,
            'filters' => array('StringTrim'),
        ));

        $this->addElement('text', 'contact', array(
            'label' => 'Your e-mail:',
            'required' => true,
            'filters' => array('StringTrim'),
            'validators' =>array('EmailAddress')
        ));

        $this->addElement('textarea', 'message', array(
            'label' => 'Feedback or question:',
            'required' => true,
            'attribs' => array('style' => 'height: 7em'),
            'filters' => array('StringTrim'),
        ));

        $this->addElement('checkbox', 'answerRequired', array(
            'label' => 'Is answer required?',
            'checked' => false,
        ));
        $this->getElement('answerRequired')
            ->removeDecorator('HtmlTag')
            ->addDecorator('HtmlTag', array('tag' => 'p'))
            ->getDecorator('label')->setOptions(array(
                'tag' => 'label',
                'placement' => 'append'
            ));

        $this->addElement('captcha', 'captcha', array(
            'label' => 'Enter code:',
            'required' => true,
            'ignore' => true,
            'wordlen' => 5,
            'captcha' => 'figlet',
        ));

        $this->getElement('captcha')->getCaptcha()->setWordlen(5);

        $this->addElement('submit', 'submit', array(
            'label' => 'Submit',
            'ignore' => true,
        ));

    }

}
