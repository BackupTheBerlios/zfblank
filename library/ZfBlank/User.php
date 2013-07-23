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

/** \brief User account.
    
    \zfb_read User
*/
class ZfBlank_User extends ZfBlank_ActiveRow_Abstract
{

    /** \brief Data Map.
    \see \ref datamap_user "Data Map" */
    protected $_dataMap = array (
        'id' => 'ID',
        'username' => 'UserName',
        'password' => 'Password',
        'fullName' => 'FullName',
        'regDate' => 'RegistrationDate',
    );

    protected $_timestampFields = array ('regDate');

    /** \brief Auth adapter credential treatment.
    \zfb_read User._credentialTreatment
    */  protected $_credentialTreatment = 'MD5(?)';

    /** \brief Prepare password to record to database.
    
    The preparation corresponds to the method specified by
    \ref $_credentialTreatment property.
    */
    protected function _preparePassword ($password)
    {
        return md5($password);
    }

    /** \brief Register new user.
    \zfb_read User..register
    */
    public function register ($values = null) {
        if ($values === null) {
            $this->setFromForm();
        } else {
            $this->setValues($values);
        }
        
        $table = $this->getTable();
        $db = $table->getAdapter();
        $select = $db->select()
                ->from($table->info('name'), array('num' => 'COUNT(*)'))
                ->where($this->columnName('username') . ' = ?',
                     $this->username
                );

        if ($db->fetchOne($select) != 0) return false;

        $this->setRegDate(new Zend_Date())->save();
        return true;
    }

    /** \brief User login.

    \zfb_read User..login
    */
    public function login ($values = null) {
        if ($values === null) {
            $this->setFromForm();
        } else {
            $this->setValues($values);
        }

        $table = $this->getTable();

        $adapter = new Zend_Auth_Adapter_DbTable(
            $table->getAdapter(),
            $table->info('name'),
            $this->columnName('username'),
            $this->columnName('password'),
            $this->_credentialTreatment
        );

        $adapter->setIdentity($this->username);
        $adapter->setCredential($this->password);
        $authResult = Zend_Auth::getInstance()->authenticate($adapter);

        return $authResult->isValid();
    }

    /** \brief User logout.
    
    \zfb_read User..logout
    */
    public function logout()
    {
        Zend_Auth::getInstance()->clearIdentity();
        return $this;
    }


}
    
