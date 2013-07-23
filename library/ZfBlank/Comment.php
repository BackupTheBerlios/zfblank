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

/** \brief Base class for comment.
    \zfb_read Comment
*/

class ZfBlank_Comment extends ZfBlank_Tree
{

    /** \var array $_dataMap
    \see \ref datamap_comment "Data Map" */
    protected $_dataMap = array (
        'id' => 'ID',
        'item' => 'ItemID',
        'author' => 'Author',
        'title' => 'Title',
        'text' => 'Body',
        'parent' => 'ParentID',
        'offset' => 'ChildOffset',
        'time' => 'PubTimeStamp',
    );

    /** \var array $_timestampFields
    \brief Timestamp fields. */
    protected $_timestampFields = array('time');

    /** \brief Get commented item.
    \return ZfBlank_Commentable: item instance or **null** */
    public function item ()
    {
        $rows = $this->getTable()
                     ->itemTable()
                     ->findFor('id', $this->item);

        return $rows->count() ? $rows->getRow(0) : null;
    }

    /** \brief Get author name from users table if supported.
    \return string: author name or **null** */
    public function authorName ()
    {
        if (($users = $this->getTable()->getUsersTable()) !== null) {
            return $users->findFor('author', $this->author)
                         ->getRow(0)
                         ->getName();
        }

        return null;
    }

}
