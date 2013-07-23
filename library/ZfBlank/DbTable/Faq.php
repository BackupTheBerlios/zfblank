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

/** \brief Frequently asked questions (ZfBlank_Faq) table
    \ingroup grp_faq
    \details
    Recommended DB schema (in MySql notation):

    \include zfb.faq.sql
*/

class ZfBlank_DbTable_Faq extends ZfBlank_DbTable_Items
{
    
    protected $_name = 'FAQ'; /**< \brief Physical table name */
    protected $_rowClass = 'ZfBlank_Faq'; /**< \brief Row class */
    protected $_categoriesTable = 'ZfBlank_DbTable_Categories';
    protected $_categoriesTableByName = 'FAQCategories';

}
