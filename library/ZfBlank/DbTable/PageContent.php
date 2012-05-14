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
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/** \brief Static page content table.
    \zfb_read DbTable_Pages
*/

class ZfBlank_DbTable_PageContent extends ZfBlank_DbTable_Abstract
{

    protected $_name = 'PageContent'; /**< \brief Physical table name */
    protected $_rowClass = 'ZfBlank_PageContent'; /**< \brief Row class name */
    protected $_textPath = '../texts'; /**< \brief Path of content files */

    /** \brief Set path to content files.
    \param string $textPath path to files with content
    \see $_textPath \return $this */
    public function setTextPath ($textPath)
    {
        $this->_textPath = $textPath;
        return $this;
    }

    /** \brief Get content files path.
    \see setTextPath() \return string: the path */
    public function getTextPath ()
    {
        return $this->_textPath;
    }


}

