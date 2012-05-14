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

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype() {
        $this->bootstrap('view');
        $this->getResource('view')->doctype('XHTML1_TRANSITIONAL');
    }

    protected function _initDate() {
        date_default_timezone_set('UTC');
    }

    protected function _initDbCharset() {
        $this->bootstrap('db');
        $this->getResource('db')->query('SET CHARACTER SET utf8');
        $this->getResource('db')->query('SET NAMES utf8');
    }

    protected function _initModuleLoader() {
        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Admin_',
            'basePath' => APPLICATION_PATH . '/modules/admin',
            'resourceTypes' => array (
                'form' => array(
                    'path' => 'forms',
                    'namespace' => 'Form'
                )
            )
        ));
        return $loader;
    }

    protected function _initCustomPlugins() {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_AdminLogin());
        $front->registerPlugin(new Application_Plugin_ModuleLayout());
        $front->registerPlugin(new Application_Plugin_Pages());
    }

    protected function _initDebugLog() {
        $log = new Zend_Log(new Zend_Log_Writer_Stream('/tmp/zfblog'));
        Zend_Registry::set('log', $log);
    }

}

