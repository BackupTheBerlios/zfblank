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

/** \brief Translate the tree to array of indented strings.
    
    \zfb_read Tree_Renderer_IndentedStrings
*/

class ZfBlank_Tree_Renderer_IndentedStrings
    extends ZfBlank_Tree_Renderer_Abstract
{

    /** \var string $_textField
    \brief Name of the data field in every node whose value will represent
    it textually (string text). \see \ref datamap_tree "tree data map" */
    protected $_textField = 'name';

    /** \var string $_indentSeq
    \brief Symbol or string for indent. */
    protected $_indentSeq = "\t";

    const INDENT_SIDE_LEFT = 'left'; /**<\brief indents at left side */
    const INDENT_SIDE_RIGHT = 'right'; /**<\brief indents at right side */
    const INDENT_SIDE_BOTH = 'both'; /**<\brief indents at both sides */

    /** \var string $_indentSide
    \brief Where to place indents */
    protected $_indentSide = self::INDENT_SIDE_LEFT;

    /** \var string $_iteratorClass
    \brief Iterator class. */
    protected $_iteratorClass = 'ZfBlank_Tree_Iterator_DirectTraversal';

    /** \var boolean $_idKeys
    \brief Use node ID's as keys in rendered array. */
    protected $_idKeys = true;

    /** \brief Set name of data field to extract text from. 
    \param string $field field name
    \see $_textField \see \ref datamap_tree "tree data map" \return $this */
    public function setTextField($field)
    {
        $this->_textField = $field;
        return $this;
    }

    /** \brief Get name of data field used to get text from.
    \see setTextField() \return string: field name */
    public function getTextField()
    {
        return $this->_textField;
    }

    /** \brief Sets symbol sequence used for one indent.
    \param string $indentSeq indent symbol or string (default: \c "\t")
    \return $this */
    public function setIndentSeq ($indentSeq)
    {
        $this->_indentSeq = $indentSeq;
        return $this;
    }

    /** \brief Get indent sequence. \see setIndentSeq()
    \return string: indent symbol or string */
    public function getIndentSeq ()
    {
        return $this->_indentSeq;
    }

    /** \brief Where to place indents.
    \param string $indentSide 'right', 'left' or 'both' (default: \c 'left')
    \return $this */
    public function setIndentSide ($indentSide)
    {
        $sides = array (
            self::INDENT_SIDE_LEFT,
            self::INDENT_SIDE_RIGHT,
            self::INDENT_SIDE_BOTH,
        );

        if (!in_array($indentSide, $sides))
            throw new Zend_Exception (
                "Side may be only 'right', 'left' or 'both'.");

        $this->_indentSide = $indentSide;
        return $this;
    }

    /** \brief Get the side(s) indents are placed at.
    \return string: 'left', 'right' or 'both' */
    public function getIndentSide ()
    {
        return $this->_indentSide;
    }

    /** \brief Set whether to use nodes' IDs as keys in rendered array
    \param boolean $idKeys (default: **true**) \return $this */
    public function setIdKeys ($idKeys)
    {
        $this->_idKeys = $idKeys;
        return $this;
    }

    /** \brief If nodes' IDs are used as keys in rendered array.
    \return boolean */
    public function getIdKeys ()
    {
        return $this->_idKeys;
    }

    /** \brief Render the tree \return array: array of indented strings */
    public function render () 
    {
        if (($tree = $this->getTree()) === null)
            throw new Zend_Exception ("Here is no tree to render.");
            
        $tree->iteratorClass($this->_iteratorClass);
        $i = $tree->getIterator();
        if (!$i->key()) $i->next();
        $startDepth = $i->getDepth();
        $result = array();
        $get = 'get' . ucfirst($this->_textField);

        if ($i->valid()) {
            foreach ($i->rewindPoint() as $node) {
                $indent = str_repeat ($this->_indentSeq, 
                    $i->getDepth() - $startDepth
                );

                $string = '';

                if ($this->_indentSide == self::INDENT_SIDE_LEFT
                    || $this->_indentSide == self::INDENT_SIDE_BOTH
                ) {
                    $string .= $indent;
                }

                $string .= $node->$get();

                if ($this->_indentSide == self::INDENT_SIDE_RIGHT
                    || $this->_indentSide == self::INDENT_SIDE_BOTH
                ) {
                    $string .= $indent;
                }

                if ($this->_idKeys) {
                    $result[$node->id] = $string;
                } else {
                    $result[] = $string;
                }
            }

        }
        
        return $result;
    }   

}
