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
*/

/** \brief Translate the tree to text with markup for items and containers.
    
    \zfb_read Tree_Renderer_NestedContainers
*/

class ZfBlank_Tree_Renderer_NestedContainers
    extends ZfBlank_Tree_Renderer_Abstract
{

    /** \var string $_rootContainerBegin
    \brief Markup for beginning of global container.
    */  protected $_rootContainerBegin = null;

    /** \var string $_rootContainerEnd
    \brief Markup for end of global container.
    */  protected $_rootContainerEnd = null;
    
    /** \var string $_containerBegin
    \brief Markup for begin of container.
    */  protected $_containerBegin = null;

    /** \var string $_containerEnd
    \brief Markup for end of container.
    */  protected $_containerEnd = null;

    /** \var string $_itemBegin
    \brief Markup for beginning of item.
    */  protected $_itemBegin = null;

    /** \var string $_itemEnd
    \brief Markup for end of item.
    */  protected $_itemEnd = null;

    /** \var string $_keyPattern
    \brief Pattern to replace with item key.
    */  protected $_keyPattern = '%%';

    /** \var string $_iteratorClass
    \brief Iterator class. 
    */  protected $_iteratorClass = 'ZfBlank_Tree_Iterator_DirectTraversal';

    /** \var string $_textField
    \brief Name of the node's field whose value will represent the node
    textually (item text).
    \see \ref datamap_tree "tree data map"
    */  protected $_textField = 'name';
    
    /** 
    \param array $options options that will be passed to setOptions() (optional)
    */
    public function __construct ($options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }

        if ($this->_rootContainerBegin === null)
            $this->_rootContainerBegin = $this->_containerBegin;

        if ($this->_rootContainerEnd === null)
            $this->_rootContainerEnd = $this->_containerEnd;

    }

    /** \brief Set name of data field to extract text from. 
    \param string $field field name
    \see $_textField \see \ref datamap_tree "tree data map" \return $this
    */
    public function setTextField($field)
    {
        $this->_textField = $field;
        return $this;
    }

    /** \brief Get name of data field to get text from.
    \see setTextField() \return string: field name
    */
    public function getTextField()
    {
        return $this->_textField;
    }

    /** \brief Set root (global) container begin marker.
    \param string $string the marker \return $this
    */
    public function setRootContainerBegin($string)
    {
        $this->_rootContainerBegin = $string;
        return $this;
    }

    /** \brief Get root container begin marker.
    \return string: the marker
    */
    public function getRootContainerBegin()
    {
        return $this->_rootContainerBegin;
    }

    /** \brief Set root (global) container end marker.
    \param string $string the marker
    \return $this
    */
    public function setRootContainerEnd($string)
    {
        $this->_rootContainerEnd = $string;
        return $this;
    }

    /** \brief Get root container end marker.
    \return string: the marker
    */
    public function getRootContainerEnd()
    {
        return $this->_rootContainerEnd;
    }


    /** \brief Key (ID) pattern substitution procedure.
    \param string $text text to process
    \param string $key replacement for pattern; if not passed, no replacements
    will be done (optional)
    \return string: processed text
    */
    protected function _substituteKey ($text, $key = null)
    {
        if ($key === null
            || ($pos = strpos($text, $this->_keyPattern)) === false
        ) return $text;

        $pat = $this->_keyPattern;
        $len = strlen($pat);

        do {
            $text = substr_replace ($text, $key, $pos, $len);
            $pos += $len;
            $pos = strpos($text, $pat, $pos);
        } while ($pos !== false);

        return $text;
    }

    /** \brief Set children container begin marker.
    \param string $string the marker
    */
    public function setContainerBegin($string)
    {
        $this->_containerBegin = $string;
        return $this;
    }

    /**
    \brief Get children container begin marker.
    \param string $key substitution for pattern in marker (optional)
    \return string: the marker
    */
    public function getContainerBegin($key = null)
    {
        return $this->_substituteKey($this->_containerBegin, $key);
    }

    /** \brief Set children container end marker.
    \param string $string the marker
    \return $this
    */
    public function setContainerEnd($string)
    {
        $this->_containerEnd = $string;
        return $this;
    }

    /** \brief Get children container end marker.
    \return string: the marker
    */
    public function getContainerEnd()
    {
        return $this->_containerEnd;
    }

    /** \brief Set item begin marker.
    \param string $string the marker
    */
    public function setItemBegin($string)
    {
        $this->_itemBegin = $string;
        return $this;
    }

    /**
    \brief Get item begin marker.
    \param string $key substitution for pattern in marker (optional)
    \return string: the marker
    */
    public function getItemBegin($key = null)
    {
        return $this->_substituteKey($this->_itemBegin, $key);
    }

    /** \brief Set item end marker.
    \param string $string the marker
    \return $this
    */
    public function setItemEnd($string)
    {
        $this->_itemEnd = $string;
        return $this;
    }

    /** \brief Get item end marker.
    \return string: the marker
    */
    public function getItemEnd()
    {
        return $this->_itemEnd;
    }

    /** \brief Set key (id) pattern.

    Set other pattern instead of \c %%
    \param string $keyPattern the pattern
    \return $this
    */
    public function setKeyPattern ($keyPattern)
    {
        $this->_keyPattern = $keyPattern;
        return $this;
    }

    /** \brief Get current key (id) pattern.
    \return string: the pattern
    */
    public function getKeyPattern ()
    {
        return $this->_keyPattern;
    }

    /** \brief Render the tree.
    \note At least the tree (setTree()) must be set before running this method.
    \return string: rendering result
    */
    public function render () 
    {
        if (($tree = $this->_tree) === null) return null;

        foreach (array('rootContainer', 'container', 'item') as $type) {
            foreach (array('Begin', 'End') as $role) {
                $property = '_' . $type . $role;
                if ($this->$property === null) $this->$property = '';
            }
        }
                
        $tree->iteratorClass($this->_iteratorClass);
        $iterator = $tree->getIterator();
        $textMethod = 'get' . ucfirst($this->getTextField()); 

        $output = $this->getRootContainerBegin();

        if (!$iterator->key()) $iterator->next();
        $emptyTree = true;

        if ($iterator->valid()) {
            $output .= $this->getItemBegin($key = $iterator->key()) 
                . $iterator->current()->$textMethod();
            $parentKey = $key;
            $iterator->next();
            $emptyTree = false;
        }

        while ($iterator->valid()) {
            $text = $iterator->current()->$textMethod();
            $key = $iterator->key();

            if (($shift = $iterator->getDepthShift()) == 0) {
                $output .= $this->getItemEnd($key)
                           . $this->getItemBegin($key)
                           . $text;
            } else {
                if ($shift === 1) {
                    $output .= $this->getContainerBegin($parentKey)
                               . $this->getItemBegin($key)
                               . $text;
                } else {
                    for ( ; $shift < 0 ; $shift++) {
                        $output .= $this->getItemEnd()
                                   . $this->getContainerEnd();
                    }

                    $output .= $this->getItemEnd() 
                               . $this->getItemBegin($key)
                               . $text;
                }
            }

            $parentKey = $key;
            $iterator->next();
        }

        $output .= (!$emptyTree ? $this->getItemEnd() : '')
                   . $this->getRootContainerEnd();

        return $output;
    }

}
