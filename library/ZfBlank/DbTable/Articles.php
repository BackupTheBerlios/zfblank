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

/** \brief Table for articles (ZfBlank_Article)
    \zfb_read DbTable_Articles
*/

class ZfBlank_DbTable_Articles extends ZfBlank_DbTable_Commentable
{

    protected $_name = 'Articles'; /**< \brief Physical table name */

    protected $_rowClass = 'ZfBlank_Article'; /**< \brief Row class */

    /** \var string or ZfBlank_DbTable_Comments $_commentsTable
    \brief Comments table or table class name. */
    protected $_commentsTable = 'ZfBlank_DbTable_ArticleComments';

    /** \var string or ZfBlank_DbTable_Users $_usersTable
    \brief Users table or table class; **null** if not used. */
    protected $_usersTable = null;

     /** \brief Categories table physical name */
    protected $_categoriesTableByName = 'ArticleCategories';

     /** \brief Tags table physical name */
    protected $_tagsTableByName = 'ArticleTags';

    /** \brief Tags many-to-many association table physical name. */
    protected $_tagsAssocTableName = 'ArticleTagsAssoc';

    /** \brief Get users table if supported.
    \return ZfBlank_DbTable_Users: table object or **null** */
    public function getUsersTable ()
    {
        if ($this->_usersTable !== null) {
            return $this->_lazyLoad($this->_usersTable);
        }

        return null;
    }
}
