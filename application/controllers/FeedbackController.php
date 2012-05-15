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

class FeedbackController extends Zend_Controller_Action
{ 
    private $_mailAdminAddress = 'admin@example.com';
    private $_mailAdmin = 'Site Admin';
    private $_mailBotAddress = 'bot@example.com';
    private $_mailBot = 'Mail Bot';

    public function indexAction ()
    {
    }

    public function sendAction ()
    {
        $table = new ZfBlank_DbTable_Feedback ();
        $msg = $table->createRow();

        if ($msg->validateForm($this->getRequest()->getPost(), 
            new Application_Form_Feedback()
        )) {
            $msg->setFromForm()->setTime(new Zend_Date())->save();
            $mail = new Zend_Mail('UTF-8');
            $mail->addTo($this->_mailAdminAddress, $this->_mailAdmin);
            $mail->setFrom($this->_mailBotAddress, $this->_mailBot);
            $mail->setSubject(
                'Message received from ' . $msg->getAuthor() . '.'
            );
            $mailText = 'ID: ' . $msg->getId() . "\n"
                . 'Date/Time: ' . $msg->getTime() . "\n"
                . 'User: ' . $msg->getAuthor() . "\n"
                . 'e-mail: ' . $msg->getContact() . "\n"
                . 'Answer required: '.($msg->isAnswerRequired()?'yes':'no')."\n"
                . "\n"
                . $msg->getMessage() . "\n";
            $mail->setBodyText($mailText);
            //$mail->send();
            $this->_redirect('/feedback/confirm');

        }

        $this->view->form = $msg->form();

    }

    public function confirmAction () {}
}
