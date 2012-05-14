<?php

class PageController extends Zend_Controller_Action
{

    public function showAction()
    {
        $this->view->page = $this->getRequest()->getParam('name');
    }

}
