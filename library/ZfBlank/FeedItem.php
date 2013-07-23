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

/** \brief Feed item.
    \zfb_read FeedItem
*/

class ZfBlank_FeedItem extends ZfBlank_Article
{

    /** \var integer $_autoAbstractLength
    \brief Maximum length in characters of auto-generated abstract */
    protected $_autoAbstractLength = 500;

    /** \var string $_autoAbstractDots
    \brief Marks of incomplete last sentence in auto-generated abstract */
    protected $_autoAbstractDots = '...';

    /** \brief Set maximum length in characters of auto-generated abstract
    (default is 500) \param integer $length new length \return $this */
    public function autoAbstractLengthSet ($length)
    {
        $this->_autoAbstractLength = $autoAbstractLength;
        return $this;
    }

    /** \brief Get maximum length in characters of auto-generated abstract.
    \return integer: length */
    public function autoAbstractLengthGet ()
    {
        return $this->_autoAbstractLength;
    }

    /** \brief Set marks of incomplete last sentence in auto-generated
    abstract (default is '...') \param string $dots new mark \return $this */
    public function autoAbstractDotsSet ($dots)
    {
        $this->_autoAbstractDots = $autoAbstractDots;
        return $this;
    }

    /** \brief Get marks of incomplete last sentence in auto-generated
    abstract \return string: mark */
    public function autoAbstractDotsGet ()
    {
        return $this->_autoAbstractDots;
    }

    /** \brief Generate abstract from body text.
    \zfb_read FeedItem..generateAbstract */
    public function generateAbstract ($check = false)
    {
        if ($check && $this->abstract != '') return $this;

        $length = $this->_autoAbstractLength;
        $text = strip_tags($this->text);
        
        if (mb_strlen($text) < $length) {
            $this->abstract = $text;
            return $this;
        }

        $minLenght = $length / 4;
        $abstract = mb_substr ($text, 0, $length);
        $split = mb_split ('[.!?]', $abstract);
        $splitCnt = count ($split);
        $tail = $split[$splitCnt-1];
        $tailLen = mb_strlen ($tail);

        if ($splitCnt == 1 || $tailLen > $length - $minLenght) {
            $abstract = mb_substr ($abstract, 0, mb_strrpos($abstract, ' '));
            $abstract .= $this->_autoAbstractDots;
        } else {
            $abstract = mb_substr ($abstract, 0, $length - $tailLen);
        }

        $this->abstract = $abstract;
        return $this;
    }

}
