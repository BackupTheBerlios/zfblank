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

/** \brief Base class for taggable and categorizable item.
    \zfb_read Item */

class ZfBlank_Item extends ZfBlank_Tree
{
    /** \var array $_dataMap
    \brief Data Map \see \ref datamap_item "Data Map" */
    protected $_dataMap = array (
        'id' => 'ID',
        'parent' => 'ParentID',
        'offset' => 'ChildOffset',
        'category' => 'CategoryID',
    );

    /** \var Zend_Db_Table_Rowset_Abstract $_tagsCache
    \brief Tags cache. */
    protected $_tagsCache = null; 

    /** \brief Get item's category name.
    \zfb_read Item..categoryName */
    public function categoryName ()
    {
        $rowset = $this->getTable()->categoriesTable()
                       ->find($this->category);
        return ($rowset->count() == 0) ?  null : $rowset->getRow(0)->getName();
    }

    /** \brief Set category by it's name instead of ID
    \zfb_read Item..categorySetByName */
    public function categorySetByName($name)
    {
        $cats = $this->getTable()->categoriesTable()->findFor('name', $name);
        if (!$cats->count()) return false;
        return $this->category = $cats->getRow(0)->id;
    }

    /** \brief Get item's tags as array of tag names.
    \return array: tags */
    public function getTags ()
    {
        $tags = array ();
        foreach ($this->tagsRowset() as $tag) $tags[] = $tag->title;
        return $tags;
    }

    /** \brief Get item's tags as rowset.
    \return **Zend_Db_Table_Rowset_Abstract**: tags */
    public function tagsRowset ()
    {
        if ($this->_tagsCache === null) {

            $table = $this->getTable();

            $this->_tagsCache = $this->atmGetValues(
                $table->tagsTable(),
                $table->getTagsAssocTableName(),
                $table->getTagsAssocItemColumn(),
                $table->getTagsAssocTagColumn()
            );
        }

        return $this->_tagsCache;
    }

    /** \brief Get item's tag list. \return **Zend_Tag_ItemList**: tag list */
    public function tagList ()
    {
        $tags = $this->tagsRowset();
        $tagList = new Zend_Tag_ItemList();
        foreach ($tags as $tag) $tagList[] = $tag;
        return $tagList;
    }

    /** \brief Add new tag(s).
    \param array|string $tags Tag(s) name or array of names. \return $this */
    public function addTags ($tags)
    {
        $this->_tagsCache = null;
        $table = $this->getTable();
        $tagsTbl = $table->tagsTable();
        $assocTbl = $table->getTagsAssocTableName();
        $assocItem = $table->getTagsAssocItemColumn();
        $assocTag = $table->getTagsAssocTagColumn();
        $db = $table->getAdapter();

        if (is_string($tags)) $tags = array($tags);
        if (!$this->id) $this->save();

        foreach ($tags as $title) {
            $rows = $tagsTbl->findFor('title', $title);

            if ($rows->count()) {
                $tag = $rows->getRow(0);
            } else {
                $tag = $tagsTbl->createRow()->setTitle($title);
                $tag->save();
            }
            
            $db->insert($assocTbl, array(
                $assocItem => $this->id,
                $assocTag => $tag->id
            ));

            $tag->countWeight()->save();
        }

        return $this;
    }

    /** \brief Remove tag(s).
    \param array|string $tags Tag(s) name or array of names. \return $this */
    public function removeTags ($tags)
    {
        $this->_tagsCache = null;
        $table = $this->getTable();
        $tagsTbl = $table->tagsTable();
        $assocTbl = $table->getTagsAssocTableName();
        $db = $table->getAdapter();
        $assocItem = $db->quoteIdentifier($table->getTagsAssocItemColumn());
        $assocTag = $db->quoteIdentifier($table->getTagsAssocTagColumn());
        $id = $db->quote($this->id);

        if (is_string($tags)) $tags = array($tags);

        foreach ($tags as $title) {
            
            $rows = $tagsTbl->findFor('title', $title);

            if ($rows->count()) {
                $tag = $rows->getRow(0);
                $tagId = $db->quote($tag->id);
                $where = "$assocItem = $id AND $assocTag = $tagId";

                if ($db->delete($assocTbl, $where)) {
                    if ($tag->countWeight()->weight == 0) {
                        $tag->delete();
                    } else {
                        $tag->save();
                    }
                }
            }
        }

        return $this;
    }

    /** \brief Remove all tags. \see removeTags() \return $this */
    public function clearTags ()
    {
        $this->removeTags($this->getTags());
        return $this;
    }

    /** \brief Set tags. \param array $tags tag names \return $this */
    public function setTags (array $tags)
    {
        $this->clearTags()->addTags($tags);
        return $this;
    }

}
