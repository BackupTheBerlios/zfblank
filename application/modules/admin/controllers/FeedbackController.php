<?php

/** \file
    \author Serge V. Baumer \<baumer at users.berlios.de\>

    \section LICENSE

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


class Admin_FeedbackController extends Zend_Controller_Action
{

    public function indexAction ()
    {
        $table = new ZfBlank_DbTable_Feedback();
        $select = $table->select();

        switch ($type = $this->getRequest()->getParam('type')) {
            case 'unanswered':
                $select->where('Reply IS NULL AND AnswerRequired IS TRUE')
                       ->order('MsgTimeStamp ASC');
                $this->view->msgstype = 'unanswered';
                break;
            case 'noanswer':
                $select->where('AnswerRequired IS FALSE')
                       ->order('MsgTimeStamp DESC');
                $this->view->msgstype = 'needing no answer';
                break;
            default:
                $select->order('MsgTimeStamp DESC');
                $this->view->msgstype = 'all';
                break;
        }

        $this->view->urltype = $type;
        $this->view->select = $select;

    }

    public function answerAction ()
    {
        $request = $this->getRequest();
        $table = new ZfBlank_DbTable_Feedback();
        
        if ($request->isPost()) {
            $post = $request->getPost();
            $msg = $table->find($post['id'])->getRow(0);

            if ($msg->validateForm($post, new Admin_Form_Feedback())) {
                $msg->setFromForm()->save();

                if ($post['sendAnswer']) {
                    $mail = new Zend_Mail('UTF-8');
                    $mail->addTo($msg->getAuthor(), $msg->getContact());
                    $mail->setFrom($this->_adminAddress, $this->_adminBot);
                    $mail->setSubject('Answer on your message');
                    $mailText = "Message text (TODO)";
                    $mail->setBodyText($mailText);
                    //$mail->send();
                }

                $this->_redirect('/admin/feedback/index/type/unanswered');
            }

            $this->view->form = $msg->form();
        } else {
            $msg = $table->find($request->getParam('id'))->getRow(0);
            $form = new Admin_Form_Feedback();
            $form->setDefaults($msg->getValues());
            $this->view->form = $form;
        }

    }

    public function deleteAction ()
    {
        $table = new ZfBlank_DbTable_Feedback();
        $request = $this->getRequest();
        $table->find($request->getParam('id'))->getRow(0)->delete();
        $this->_redirect('/admin/feedback/index'
            . (($type = $request->getParam('type')) ? '/type/' . $type : '')
            . '?page=' . $request->getQuery('page')
        );
    }

}
