<?php

/*
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


class Application_Model_DbTable_News extends ZfBlank_DbTable_Feed
{
    protected $_name = 'News';
    protected $_tagsTableByName = 'NewsTags';
    protected $_tagsAssocTableName = 'NewsTagsAssoc';
    protected $_categoriesTableByName = 'NewsCategories';
    protected $_commentsTable = 'Application_Model_DbTable_NewsComments';
}
