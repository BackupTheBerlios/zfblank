<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $table = new ZfBlank_DbTable_PageContent();
        $this->view->page = $table->find('index')->getRow(0);
    }

}

