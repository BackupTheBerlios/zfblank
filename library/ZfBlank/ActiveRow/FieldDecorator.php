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

/** 
    \brief Field output decorator for
    \ref ZfBlank_ActiveRow_Abstract "ActiveRow"
    \zfb_read ActiveRow_FieldDecorator
*/

class ZfBlank_ActiveRow_FieldDecorator
{

    /**
    \var ZfBlank_ActiveRow $_row
    \brief Row object to work with.
    */
    protected $_row = null;

    /**
    \var string $_fieldName
    \brief Name of the field to work with.
    */
    protected $_fieldName = null;

    /**
    \var string $_prefix
    \brief String to append before the field value.
    */
    protected $_prefix = null;

    /**
    \var string $_suffix
    \brief String to append after the field value.
    */
    protected $_suffix = null;

    /**
    \brief String to replace all the output with.
    \zfb_read ActiveRow_FieldDecorator._replacement
    */
    protected $_replacement = null;

    /**
    \param array $options options to be passed to setOptions() method
    \see setOptions()
    */
    public function __construct (array $options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /**
    \brief Set options.
    
    \zfb_read ActiveRow_FieldDecorator..setOptions
    */
    public function setOptions (array $options)
    {
        foreach ($options as $option=>$value) {
            $method = 'set' . ucfirst ($option);
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                throw new Zend_Exception ("Incorrect option: $option.");
            }
        }

        return $this;
    }

    /**
    \brief Bind with a row object.
    \param ZfBlank_ActiveRow_Abstract $row the row to bind with
    \see $_row
    \return $this
    */
    public function setRow (ZfBlank_ActiveRow_Abstract $row)
    {
        $this->_row = $row; 
        return $this;
    }

    /** 
    \brief Get binded row.
    \see setRow()
    \return ZfBlank_ActiveRow_Abstract: row object or **null**
    */
    public function getRow ()
    {
        return $this->_row;
    }

    /**
    \brief Set name of a field in the row object to decorate.
    \param string $name field name
    \see $_fieldName
    \return $this
    */
    public function setFieldName ($name)
    {
        $this->_fieldName = $name; 
        return $this;
    }

    /**
    \brief Get the name of decorated field
    \see setFieldName()
    \return string: field name or **null**
    */
    public function getFieldName ()
    {
        return $this->_fieldName;
    }

    /**
    \brief Set string that will be prepended to field value in output.
    \param string $prefix
    \see $_prefix
    \return $this
    */
    public function setPrefix ($prefix)
    {
        $this->_prefix = $prefix; 
        return $this;
    }

    /**
    \brief Get prefix string.
    \see setPrefix()
    \return string: prefix string or **null**
    */
    public function getPrefix ()
    {
        return $this->_prefix;
    }

    /**
    \brief Set string that will be appended to field value in output.
    \param string $suffix
    \see $_suffix
    \return $this
    */
    public function setSuffix ($suffix)
    {
        $this->_suffix = $suffix; 
        return $this;
    }

    /**
    \brief Get suffix string.
    \see setSuffix()
    \return string: suffix string or **null**
    */
    public function getSuffix ()
    {
        return $this->_suffix;
    }

    /**
    \brief Set string that to replace all the output.
    \zfb_read ActiveRow_FieldDecorator..setReplacement
    */
    public function setReplacement ($replacement)
    {
        $this->_replacement = $replacement; 
        return $this;
    }

    /**
    \brief Get replacement string.
    \see setReplacement()
    \return string: replacement string or **null**
    */
    public function getReplacement ()
    {
        return $this->_replacement;
    }

    /**
    \brief Get decorated field value.
    
    \zfb_read ActiveRow_FieldDecorator..decorate
    */
    public function decorate ($value = null)
    {
        if ($this->_replacement !== null) return $this->_replacement;

        if ($value !== null) {
            return $this->_prefix . $value . $this->_suffix;
        }

        if ($this->_row === null)
            throw new Zend_Exception ("Here is no row object.");

        if ($this->_fieldName === null)
            throw new Zend_Exception (
                "Here is no name of field to decorate.");

        $method = 'get' . ucfirst($this->_fieldName);
        $output = $this->_prefix . $this->_row->$method() . $this->_suffix;

        return $output;
     }

}
