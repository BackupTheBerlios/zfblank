<?php 

/** \file
    \author Serge V. Baumer \<baumer at users.berlios.de\>

    \section LICENSE

    Copyright (C) 2011,2012  Serge V. Baumer

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, version 3 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
*/

/** \brief Translate ZfBlank_Tree to DOM tree

    \zfb_read Tree_Renderer_Dom
*/

class ZfBlank_Tree_Renderer_Dom
    extends ZfBlank_Tree_Renderer_Abstract
{
    const CHILDREN_POSITION_BEGINNING = 'beginning'; /**< 
        \brief Children position inside parent: beginning */
    const CHILDREN_POSITION_END = 'end'; /**< 
        \brief Children position inside parent: end */

    //const CHILDREN_POSITION_CALLBACK = 'callback';

    /** \var string or array $_rootContainer
    \brief Root container element (tag) name.
    */  protected $_rootContainer = 'ul';

    /** \var string or array $_childrenContainer
    \brief Children container element (tag) name.
    */  protected $_childrenContainer = 'ul';

    /** \var array $_nodeTag
    \brief Element (tag) for single tree node.
    \see \ref earray "array description"
    */  protected $_nodeTag = array('li');

    /** \var string or array $_childrenPosition
    \brief Position of children inside parent.
    */  protected $_childrenPosition = self::CHILDREN_POSITION_END;

    /** \var string or array $_childSeparator
    \brief Children separator.
    */  protected $_childSeparator = null;

    /** \var boolean $_separatorAfterLast
    \brief Put separator after last child.
    */  protected $_separatorAfterLast = false;

    /** \var boolean $_separatorBeforeFirst
    \brief Put separator before first child.
    */  protected $_separatorBeforeFirst = false;

    /** \var string or array $_valueContainer
    \brief Container for item value.
    */  protected $_valueContainer = null;

    /** \var string or ZfBlank_ActiveRow_FieldDecorator $_valueSource
    \brief Source of an item's value.
    */  protected $_valueSource = '';

    /** \var callback $_valueCallback
    \brief Callback that returns item's value.
    */  protected $_valueCallback = null;

    /** \var DOMDocument $_document
    \brief DOM Document containing DOM tree.
    */  protected $_document = null;

    /** \var string $_iteratorClass
    \brief Iterator class. 
    */  protected $_iteratorClass = 'ZfBlank_Tree_Iterator_DirectTraversal';

    /** \brief Set the DOM document to create tree inside it.
    \note If document is not passed by this method, it will be created
    \param DOMDocument $document DOM document
    */
    public function setDocument (DOMDocument $document)
    {
        $this->_document = $document;
        return $this;
    }

    /** \brief Get the document set by setDocument()
    \return DOM Document: the document
    */
    public function getDocument ()
    {
        return $this->_document;
    }

    /** \brief Set root (global) container parameters.
    \param string|array $rootContainer The root of DOM tree tag name or
    \ref earray "element properties array"
    \return $this
    */
    public function setRootContainer ($rootContainer)
    {
        $this->_rootContainer = $rootContainer;
        return $this;
    }

    /** \brief Get root (global) container parameters.
    \return string|array: The root of DOM tree tag name or
    \ref earray "element properties array"
    */
    public function getRootContainer ()
    {
        return $this->_rootContainer;
    }

    /** \brief Set children container parameters.
    \param string|array $childrenContainer children container tag name or 
    \ref earray "element properties array"
    \return $this
    */
    public function setChildrenContainer ($childrenContainer)
    {
        $this->_childrenContainer = $childrenContainer;
        return $this;
    }

    /** \brief Get children container parameters.
    \return string|array: children container tag name or 
    \ref earray "element properties array"
    */
    public function getChildrenContainer ()
    {
        return $this->_childrenContainer;
    }

    /** \brief Set single item parameters.
    \param string|array $nodeTag item tag name or 
    \ref earray "element properties array"
    \return $this
    */
    public function setNodeTag ($nodeTag)
    {
        if (is_string($nodeTag)) {
            $nodeTag = array($nodeTag);
        }

        $nodeTag['recursion'] = true;
        $this->_nodeTag = $nodeTag;
        return $this;
    }

    /** \brief Get single item parameters.
    \return string|array: item tag name or 
    \ref earray "element properties array"
    */
    public function getNodeTag ()
    {
        $output = $this->_nodeTag;
        unset ($output['recursion']);
        return $output;
    }

    /** \brief Set children position inside parent.
    \param string $childrenPosition position of children container inside
    parent ('beginning' or 'end')
    \return $this
    */
    public function setChildrenPosition ($childrenPosition)
    {
        $this->_childrenPosition = $childrenPosition;
        return $this;
    }

    /** \brief Get children position inside parent.
    \return string: 'beginning' or 'end'
    */
    public function getChildrenPosition ()
    {
        return $this->_childrenPosition;
    }

    /** \brief Set children separator parameters.
    \param string|array $childSeparator children separator tag name or 
    \ref earray "element properties array"
    \return $this
    */
    public function setChildSeparator ($childSeparator)
    {
        $this->_childSeparator = $childSeparator;
        return $this;
    }

    /** \brief Get children separator parameters.
    \return string|array: children separator tag name or 
    \ref earray "element properties array"
    */
    public function getChildSeparator ()
    {
        return $this->_childSeparator;
    }

    /** \brief Whether to place children separator after last child.
    \param bolean $separatorAfterLast **true**: do place; **false**: don't
    place
    \return $this
    */
    public function setSeparatorAfterLast ($separatorAfterLast)
    {
        $this->_separatorAfterLast = $separatorAfterLast;
        return $this;
    }

    /** \brief If children separator must be placed after last child.
    \return boolean: **true**: yes; **false**: no
    */
    public function getSeparatorAfterLast ()
    {
        return $this->_separatorAfterLast;
    }

    /** \brief Whether to place children separator before first child.
    \param bolean $separatorBeforeFirst **true**: do place; **false**: don't
    place
    \return $this
    */
    public function setSeparatorBeforeFirst ($separatorBeforeFirst)
    {
        $this->_separatorBeforeFirst = $separatorBeforeFirst;
        return $this;
    }

    /** \brief If children separator must be placed before first child.
    \return boolean: **true**: yes; **false**: no
    */
    public function getSeparatorBeforeFirst ()
    {
        return $this->_separatorBeforeFirst;
    }


    /** \brief Set item value container parameters.
    \param string|array $valueContainer item value container tag name or 
    \ref earray "element properties array"
    \return $this */
    public function setValueContainer ($valueContainer)
    {
        $this->_valueContainer = $valueContainer;
        return $this;
    }

    /** \brief Get item value container parameters.
    \return string|array: item value container tag name or 
    \ref earray "element properties array"
    */
    public function getValueContainer ()
    {
        return $this->_valueContainer;
    }

    /** \brief Set source of an item value.
    
    \zfb_read Tree_Renderer_Dom..setValueSource
    */
    public function setValueSource ($valueSource)
    {
        $this->_valueSource = $valueSource;
        return $this;
    }

    /** \brief Get source of an item value.
    \see setValueSource()
    \return string|ZfBlank_ActiveRow_FieldDecorator: value source
    */
    public function getValueSource ()
    {
        return $this->_valueSource;
    }

    /** \brief Set value callback.
    
    \zfb_read Tree_Renderer_Dom..setValueCallback
    */
    public function setValueCallback ($callback)
    {
        $this->_valueCallback = $callback;
        return $this;
    }

    /** \brief Get value callback.  
    \see setValueCallback()
    \return callback: callback function
    */
    public function getValueCallback ()
    {
        return $this->_callback;
    }

    /** \var array $_parentStack
    \brief Stack of parent DOM nodes used by render().
    */  protected $_parentStack = array();

    /** \brief Push nodes to the parents stack.
    \param DOMElement $node parent node
    \param DOMElement $container container
    \see $_parentStack
    \return $this
    */
    protected function _psPush ($node, $container)
    {
        array_push($this->_parentStack, $node);
        array_push($this->_parentStack, $container);
        return $this;
    }

    /** \brief Pop parent node and container from the parents stack.
    \see $_parentStack
    \return DOMElement: parent node
    */
    protected function _psPop ()
    {
        array_pop($this->_parentStack);
        return array_pop($this->_parentStack);
    }

    /** \brief Get element from top of the parents stack.
    \see $_parentStack
    \return DOMElement: the node
    */
    protected function _psTop ()
    {
        end ($this->_parentStack);

        if (current($this->_parentStack) === null) prev ($this->_parentStack);

        return current($this->_parentStack);
    }

    /** \brief Create DOM Element by given parameters.

    \zfb_read Tree_Renderer_Dom.._makeElement
    */
    protected function _makeElement (DOMDocument $doc, $params,
        ZfBlank_ActiveRow_Abstract $row = null
    ) {
        if (is_string($params)) {
            return $doc->createElement($params);
        }

        $name = $params[0];

        if (array_key_exists('value', $params)) {
            $value = $params['value'];
            if ($value instanceof ZfBlank_ActiveRow_FieldDecorator) {
                if ($row === null) throw new Zend_Exception
                    ("A row object must be given as third parameter.");
                $value = $value->setRow($row)->decorate();
            }

            if (array_key_exists('valueCallback', $params)) {
                $callback = $params['valueCallback'];
                $elem = $doc->createElement($name);
                $elem->appendChild($callback($doc, $row));
            } elseif (array_key_exists('valueContainer', $params)) {
                $elem = $doc->createElement($name);
                $vcParams = $params['valueContainer'];
                $vcParams['value'] = $value;
                $vc = $this->_makeElement($doc, $vcParams, $row);
                $elem->appendChild($vc);
            } else {
                $elem = $doc->createElement($name, $value);
            }
        } else {
            $elem = $doc->createElement($name);
        }

        if (array_key_exists('attributes', $params)) {
            foreach($params['attributes'] as $name=>$value) {
                if ($value instanceof ZfBlank_ActiveRow_FieldDecorator) {
                    if ($row === null) throw new Zend_Exception
                        ("A row object must be given as third parameter.");
                    $elem->setAttribute($name, 
                        $value->setRow($row)->decorate()
                    );
                } else {
                    $elem->setAttribute($name, $value);
                }
            }
        }

        return $elem;

    }

    /** \brief Render the tree.
    \return DOMDocument: DOM document containing rendered tree.
    */
    public function render ()
    {
        if (($tree = $this->getTree()) === null)
            throw new Zend_Exception ("Here is no tree to render.");
            
        if ($this->_document === null) {
            $doc = new DOMDocument();
        } else {
            $doc = $this->_document;
        }
        
        $root = $doc->appendChild($this->_makeElement($doc,
            $this->_rootContainer
        ));

        $this->_psPush($doc, $root);
        $tree->iteratorClass($this->_iteratorClass);
        $i = $tree->getIterator();
        if (!$i->key()) $i->next();

        if ($i->valid()) {
            foreach ($i->rewindPoint() as $node) {
                for ($shift = $i->getDepthShift() ; $shift < 0 ; $shift++) {
                    $this->_psPop();
                }

                $param = $this->_nodeTag;
                $param['value'] = $this->_valueSource;

                if ($this->_valueCallback != null) {
                    $param['valueCallback'] = $this->_valueCallback;
                } elseif ($this->_valueContainer != null) {
                    $param['valueContainer'] = $this->_valueContainer;
                }

                $parent = $this->_psTop();

                if ($this->_childSeparator && $this->_separatorBeforeFirst
                    && $node->firstChild() 
                ) {
                    $parent->appendChild($this->_makeElement($doc,
                        $this->_childSeparator, $node
                    ));
                }

                $element = $this->_makeElement($doc, $param, $node);
                $parent->appendChild($element);

                if ($this->_childSeparator 
                    && (!$node->lastChild() || $this->_separatorAfterLast)
                ) {
                    $parent->appendChild($this->_makeElement($doc,
                        $this->_childSeparator, $node
                    ));
                }

                if ($node->hasChildren()) {
                    $container = $this->_makeElement($doc,
                        $this->_childrenContainer, $node
                    );

                    switch ($this->_childrenPosition) {
                        case self::CHILDREN_POSITION_BEGINNING:
                            $element->insertBefore($container,
                                $element->firstChild
                            );
                            break;
                        case self::CHILDREN_POSITION_END:
                            $element->appendChild($container);
                            break;
                        default:
                            throw new Zend_Exception("Bad children position.");
                            break;
                    }

                    $this->_psPush($element, $container);
                }
            }
        }
        return $doc;
    }

}
