<?php

/**
    \file
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

/** \brief Website static content.
    \zfb_read Page
*/

class ZfBlank_PageContent extends ZfBlank_ActiveRow_Abstract
{

    /** \brief Data Map.
    \see \ref datamap_pagecontent "Data Map" */
    protected $_dataMap = array (
        'name' => 'Name',
        'storage' => 'TextStorage',
        'title' => 'Title',
        'description' => 'Description',
        'text' => 'Body',
        'tags' => 'Tags',
    );

    /** \brief Name transformation.

    Removes spaces and directory separators from the name and lowercases it.
    \param string $name \return string: resulting name */
    protected function _transformName ($name)
    {
        $name = strtolower($name);
        $name = strtr($name, "/\\", '  ');
        $name = str_replace(DIRECTORY_SEPARATOR, '', $name);
        $name = str_replace(' ', '', $name);
        return $name;
    }

    /** \brief Set page name.
    \remark Performs name transformation using _transformName() before setting
    it. \param string $name page name \return $this */
    public function setName($name)
    {
        $this->name = $this->_transformName($name);
        return $this;
    }


    /** \brief Construct full filename for content.
    \return string: filename with path */
    protected function _filename()
    {
        return $this->getTable()->getTextPath() . DIRECTORY_SEPARATOR
                                                . $this->name;
    }

    /** \brief Get content.
    \return mixed: content from DB or file depending on _storage_
    specified
    \see ZfBlank_DbTable_PageContent::setTextPath()
    */
    public function getText()
    {
        switch ($this->storage) {
            case 'db':
                return $this->text;
            case 'file':
                $filename = $this->_filename();

                if (file_exists($filename)) {
                    return file_get_contents($filename);
                }

                return null;
        }
    }

    /** \brief Overloaded method that saves content to file if _storage_ is 'file'.
    \return mixed: result of parent's save() call
    \see **Zend_Db_Table_Row_Abstract::save()**
    */
    public function save ()
    {
        if ($this->storage == 'file') {
            file_put_contents (
                $this->_filename(),
                $this->text
            );
            $this->text = null;
        }

        return parent::save();
    }

    /** \brief Delete content file if exists. \return $this */
    public function deleteFile()
    {
        $file = $this->_filename();
        if (file_exists($file)) unlink($file);
        return $this;
    }
}
