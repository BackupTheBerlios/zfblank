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

/** \brief Base class for single database record in ZF_Blanks package.
    \zfb_read ActiveRow_Abstract
*/

abstract class ZfBlank_ActiveRow_Abstract extends
        Zend_Db_Table_Row_Abstract
{

    /** \var array $_dataMap
    \zfb_read ActiveRow_Abstract._dataMap */
    protected $_dataMap = array();

    /** \var boolean $_finishDataMap
    \brief Whether to check and complete \ref $_dataMap array automatically in 
    constructor.  */
    protected $_finishDataMap = true;

    /** \var array $_timestampFields
    \zfb_read ActiveRow_Abstract._timestampFields */
    protected $_timestampFields = array();

    /** \var array $_fieldDecorators
    \zfb_read ActiveRow_Abstract._fieldDecorators */
    protected $_fieldDecorators = array();

    /** \var ZfBlank_Form $_form
    \brief Associated form object.
    \see form()
    \see validateForm()
    \see setFromForm()
    */
    protected $_form = null;

    /** \zfb_read ActiveRow_Abstract..__construct */
    public function __construct (array $config = array())
    {
        if ($this->_finishDataMap || isset ($config['finishDataMap'])) {
            if (isset($config['table']) 
                && $config['table'] instanceof Zend_Db_Table_Abstract
            ) {
                $table = $config['table'];
            } elseif ($this->_tableClass !== null) {
                $table = $this->_getTableFromString($this->_tableClass);
            }

            $cols = $table->info('cols');
            $result = array();
            
            foreach ($cols as $colName) {
                if (($key=array_search($colName, $this->_dataMap)) !== FALSE) {
                    $result[$key] = $colName;
                } else {
                    $result[$this->_dataMapTransform($colName)] = $colName;
                }
            }
            
            $this->_dataMap = $result;
        }

        parent::__construct($config);
    }

    /** \zfb_read ActiveRow_Abstract..__call */
    public function __call ($name, array $arguments)
    {
        try {
            parent::__call($name, $arguments);
        } catch (Zend_Db_Table_Row_Exception $e) {
            $result = false;
            foreach (array ('set', 'get', 'is', 'unique') as $action) {
                if (substr_compare($name, $action, 0, strlen($action)) === 0) {
                    $result = true;
                    break;
                }
            }
            if (!$result) {
                throw new Zend_Db_Table_Row_Exception ($e->getMessage());
            }
            $var = strtolower(substr($name, strlen($action), 1))
                . substr($name, strlen($action)+1);
            if (!array_key_exists ($var, $this->_dataMap)) {
                throw new Zend_Db_Table_Row_Exception ($e->getMessage());
            }
            switch ($action) {
                case 'set':
                    $value = $arguments[0];

                    if (in_array($var, $this->_timestampFields)) {
                        if (!($value instanceof Zend_Date)) {
                            throw new Zend_Db_Table_Row_Exception (
                                "Value for $name() must be of class Zend_Date."
                            );
                        }

                        $this->$var = $value->getTimestamp();
                    } else {
                        $this->$var = $value;
                    }

                    return $this;
                    break;
                case 'get':
                    if (in_array($var, $this->_timestampFields)) {
                        return new Zend_Date((int)$this->$var);
                    } else {
                        if (isset($this->_fieldDecorators[$var])) {
                            return $this->_loadFieldDecorator($var)
                                        ->decorate($this->$var);
                        }
                        return $this->$var;
                    }
                    break;
                case 'is':
                    if (!in_array($this->$var,
                            array(null, 0, 1, '0', '1', true, false), true)
                    ) {
                        throw new Zend_Db_Table_Row_Exception
                                ($e->getMessage());
                    }
                    return $this->$var===null ? null : (bool)$this->$var;
                    break;
                case 'unique':
                    $value = isset($arguments[0]) ? $arguments[0] : $this->$var;
                    if ($value === null) return false;

                    if (($table = $this->getTable()) === null) {
                        throw new Zend_Db_Table_Row_Exception (
                            "Can't run $name() method on disconnected row."
                        );
                    }

                    $column = $this->_dataMap[$var];
                    $select = $table->select();
                    $select->from($table->info('name'), array(
                        'num' => "COUNT($column)"
                    ));
                    $select->where("$column = ?", $value);
                    return ! $table->fetchRow($select)->num;
                    break;
            }
        }
    }

    /** \brief Lazy load.
    \param string|mixed &$var if is string, treats it as class name and assigns
    new class instance to $var
    \return mixed: $var */
    protected function _lazyLoad (&$var)
    {
        if ($var === null) return null;

        if (is_string($var)) {
            $var = new $var;
        }

        return $var;
    }

    /** \brief Lazy load for a field decorator.
    \zfb_read ActiveRow_Abstract.._loadFieldDecorator */
    protected function _loadFieldDecorator ($field)
    {
        $decorator = $this->_lazyLoad($this->_fieldDecorators[$field]);
        $decorator->setRow($this)->setFieldName($field);
        return $decorator;
    }

    /** \brief Association Table Mapping (many-to-many) - get values.
    \zfb_read ActiveRow_Abstract..atmGetValues */
    public function atmGetValues ($valuesTable, $assocTable,
        $assocKeyColumn, $assocValueKeyColumn, $order = null,
        $keyField = 'id', $valueKeyField = 'id'
    ) {
        $select = $this->getTable()->atmSelect($this->$keyField, $valuesTable,
            $assocTable, $assocKeyColumn, $assocValueKeyColumn, $keyField,
            $valueKeyField
        );

        if ($order !== null) $select->order($order);

        return $valuesTable->fetchAll($select);
    }


    /** \brief Set or get a field decorator.
    \zfb_read ActiveRow_Abstract..fieldDecorator */
    public function fieldDecorator ($fieldName, $decorator = null) {
        $current = isset($this->_fieldDecorators[$fieldName])
                   ? $this->_loadFieldDecorator($fieldName) : null;

        if ($decorator !== null) {
            if ($decorator === false) {
                unset ($this->_fieldDecorators[$fieldName]);
            } else {
                $this->_fieldDecorators[$fieldName] = $decorator;
            }
        }

        return $current;
    }
            
    /** \brief Column name transformation (from **Zend_Db_Table_Row**).
    \zfb_read ActiveRow_Abstract.._transformColumn */
    protected function _transformColumn ($columnName) {
        if (array_key_exists($columnName, $this->_dataMap))
            return $this->_dataMap[$columnName];
        else
            return $columnName;
    }

    /** \brief Automatical column name to field name transformation
    algorithm.
    \zfb_read ActiveRow_Abstract.._dataMapTransform */
    protected function _dataMapTransform ($name)
    {
        $name = str_replace ('ID', 'Id', $name);
        $parts = explode('_', $name);

        if (count($parts) === 1) {
            $result = $parts[0];
        } else {
            $result = '';
            foreach ($parts as $part) {
                $result .= ucfirst(strtolower($part));
            }
        }

        $result = strtolower(substr($result, 0, 1)) . substr($result, 1);
        return $result;
    }

    /** \brief Add a field definition to data map.
    \param string $name field name
    \param string $columnName column name
    \see \ref datamap "Data Map"
    \return $this */
    public function addField ($name, $columnName) {
        $this->_dataMap[$name] = $columnName;
        return $this;
    }

    /** \brief Add several field definitions to data map.
    \param array $fields each element of an array is in form 
    _field_name_ => _column_name_
    \see \ref datamap "Data Map"
    \see addField()
    \return $this */
    public function addFields (array $fields) {
        foreach ($fields as $name=>$columnName) {
            $this->_dataMap[$name] = $columnName;
        }
        return $this;
    }

    /** \brief Remove one or more field definitions from data map, or clear data
    map.

    If the argument is not given, data map is cleared, otherwise requested
    fields are removed.

    \param string|array $fields name or array of field names to remove
    (optional)
    \see \ref datamap "Data Map"
    \return $this */
    public function removeFields ($fields = null) {
        if (is_string($fields)) {
            unset ($this->_dataMap[$fields]);
        } else if (is_array($fields)) {
            foreach ($fields as $field) {
                $this->removeFields($field);
            }
        } else if (null === $fields) {
            $this->_dataMap = array();
        }

        return $this;
    }

    /** \brief Get current data map.

    Returned array is described \ref datamap "here".
    \return array: data map */
    public function dataMap () {
        return $this->_dataMap;
    }
    
    /** \brief Check whether a field is present in data map.
    \param string $name field name
    \see \ref datamap "Data Map"
    \return boolean */
    public function hasField ($name) {
        return array_key_exists ($name, $this->_dataMap);
    }

    /** \brief Get DB column name corresponding to a field name
    \param string $name field name \see \ref datamap "Data Map"
    \return string: column name */
    public function columnName ($name)
    {
        if (!$this->hasField($name)) {
            throw new Zend_Exception ('Field name does not exist.');
        }

        return $this->_dataMap[$name];
    }

    /** \brief Get field name corresponding to a column name
    \param string $name column name \see \ref datamap "Data Map"
    \return string: field name or __null__ if there's no field for this column*/
    public function fieldName ($name)
    {
        $field = array_search($name, $this->_dataMap);
        return $field === false ? null : $field;
    }

    /** \brief Set several fields data.
    \zfb_read ActiveRow_Abstract..setValues */
    public function setValues (array $values) {
        foreach ($values as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (array_key_exists($key, $this->_dataMap)
                || method_exists($this, $method)
            ) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /** \brief Get all the object data.
    \zfb_read ActiveRow_Abstract..getValues */
    public function getValues () {
        $values = array();

        foreach ($this->_dataMap as $name=>$columnName) {
            $method = 'get' . ucfirst($name);
            $value = $this->$method();
            $values[$name] = $value;
        }

        return $values;
    }

    /** \brief Declare timestamp fields or get current timestamp fields list.
    \zfb_read ActiveRow_Abstract..timestampFields */
    public function timestampFields (array $fields = null) {
        if ($fields === null) {
            return $this->_timestampFields;
        } else {
            foreach ($fields as $name) {
                if (!array_key_exists($name, $this->_dataMap)) {
                    throw new Zend_Db_Table_Row_Exception (
                            "Incorrect field name: $name.");
                }
            }

            $this->_timestampFields = $fields;
            return $this;
        }
    }

    /** \brief Set form for further interaction with, or get current form.

    If called with argument given, set form for further interaction with it.

    \param ZfBlank_Form $form (optional)
    \see validateForm()
    \see setFromForm()
    \return ZfBlank_Form: previous (current) form object or __null__ */
    public function form (ZfBlank_Form $form = null) {
        $oldForm = $this->_form;

        if ($form !== null) {
            $this->_form = $form;
        }

        return $oldForm;
    }

    /** \brief Validate form.
    \zfb_read ActiveRow_Abstract..validateForm */
    public function validateForm (array $values,
            ZfBlank_Form $form = null) {
        if ($form === null && $this->_form === null)
            throw new Zend_Db_Table_Row_Exception ('No form to validate.');

        if ($form !== null) $this->form($form);

        return $this->_form->isValid($values);
    }

    /** \brief Import data from form.
    \zfb_read ActiveRow_Abstract..setFromForm */
    public function setFromForm (ZfBlank_Form $form = null) {
        if ($form === null && $this->form() === null)
            throw new Zend_Db_Table_Row_Exception (
                    'No form to set values from.');

        if ($form) $this->form($form);

        $this->setValues($this->form()->exportValuesArray());
        return $this;
    }

}

