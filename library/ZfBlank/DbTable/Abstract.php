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

/** \brief Base class for database table classes ([Table Data Gateway]
    (http://martinfowler.com/eaaCatalog/tableDataGateway.html))
    in the ZF_Blanks package.

    \zfb_read DbTable_Abstract
*/


abstract class ZfBlank_DbTable_Abstract extends Zend_Db_Table_Abstract
{

    protected $_rowClass = 'ZfBlank_ActiveRow';

    /** \var ZfBlank_ActiveRow_Abstract $_infoRow
    \brief Empty row object used for reference */
    protected $_infoRow = null;

    /** \brief Load and get \ref $_infoRow property.
    \throw Zend_Db_Table_Exception row class is not subclass of
    **ZfBlank_ActiveRow_Abstract**
    \return ZfBlank_ActiveRow_Abstract */
    protected function _infoRow () {
        if ($this->_infoRow === null) $this->_infoRow = $this->createRow();

        if (!($this->_infoRow instanceof ZfBlank_ActiveRow_Abstract)) {
            throw new Zend_Db_Table_Exception ("Row class is not Active Row");
        }

        return $this->_infoRow;
    }

    /** \zfb_read DbTable_Abstract..__call */
    public function __call ($name, $arguments) {
        if (substr_compare($name, 'findFor', 0, 7) == 0) {
            $var = strtolower(substr($name, 7, 1)) . substr($name, 8);
            return $this->findFor($var, $arguments[0]);
        } else if (substr_compare($name, 'countFor', 0, 8) == 0) {
            $var = strtolower(substr($name, 8, 1)) . substr($name, 9);
            return $this->countFor($var, $arguments[0]);
        } else {
            throw new Zend_Db_Table_Exception ("Method doesn't exist.");
        }
    }

    /** \brief Lazy load.
    \param string|mixed &$var if references string, treats it as class name
    and assigns new class instance to $var \return mixed: $var */
    protected function _lazyLoad (&$var)
    {
        if ($var === null) return null;

        if (is_string($var)) {
            $var = new $var;
        }

        return $var;
    }

    /** \brief Find rows in table by value of a data field.
    \zfb_read DbTable_Abstract..findFor */
    public function findFor ($field, $value) {
        $fields = $this->_infoRow()->dataMap();

        if (!array_key_exists($field, $fields))
            throw new Zend_Db_Table_Exception ("Field does not exist: $field.");
        
        $field = $fields[$field];
        $expr = $value === null ? "$field IS NULL"
                                : array ("$field = ?" => $value);

        return $this->fetchAll($expr);
    }

    /** \brief Count rows where а given field has given value.
    \zfb_read DbTable_Abstract..countFor */
    public function countFor ($field, $value)
    {
    	$fields = $this->_infoRow()->dataMap();

	    if (!array_key_exists($field, $fields))
            throw new Zend_Db_Table_Exception ("Field does not exist: $field.");
	
	    $column = $fields[$field];
	
	    $select = $this->select()
                       ->from($this->_name, array('cnt' => 'COUNT(*)'));

        if ( $value === null ) {
            $select->where("$column IS NULL"); 
        } else {
            $select->where("$column = ?", $value);
        }
	
        return $this->fetchRow($select)->cnt;
    }


    /** \brief Get column name from field name.
    \zfb_read DbTable_Abstract..columnName */
    public function columnName ($field)
    {
        return $this->_infoRow()->columnName($field);
    }

    /** \brief Association Table Mapping (many-to-many) - get select.
    \zfb_read ActiveRow_Abstract..atmSelect */
    public function atmSelect ($key, $valuesTable, $assocTable,
        $assocKeyColumn, $assocValueKeyColumn,
        $valueKeyField = 'id'
    ) {
        $db = $this->getAdapter();
        $valuesTableName = $valuesTable->info('name');
        $valueIdCol = $valuesTable->columnName($valueKeyField);

        $joinExpr = $db->quoteIdentifier("$assocTable.$assocValueKeyColumn")
                  . ' = '
                  . $db->quoteIdentifier("$valuesTableName.$valueIdCol");

        $select = $valuesTable->select()->setIntegrityCheck(false);
        $select->from($valuesTableName)
               ->joinInner($assocTable, $joinExpr, array());

        $select->where(
            $db->quoteIdentifier("$assocTable.$assocKeyColumn") . " = ?", $key
        );
        
        return $select;
    }

}
