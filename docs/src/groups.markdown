\defgroup grp_root Main
\brief Base classes for majority of models. Start exploring ZF_Blanks docs
from here.
\details
ZfBlank_ActiveRow_Abstract is a kind of [Active Record]
(http://martinfowler.com/eaaCatalog/activeRecord.html) design pattern 
implementation, and it
extends Zend Framework class **Zend_Db_Table_Row_Abstract**.

ZfBlank_DbTable_Abstract is a kind of [Table Data Gateway]
(http://martinfowler.com/eaaCatalog/tableDataGateway.html) design pattern
implementation, and it extends Zend Framework class **Zend_Db_Table_Abstract**.

Both these classes together lay the foundations of extended functionality and
collaboration of \"table &ndash; row\" pairs in all submodules of this
module.  It's recommended to familiarize with them before proceeding.

ZfBlank_ActiveRow_FieldDecorator interacts with ZfBlank_ActiveRow_Abstract
as a \ref fielddecorators "field decorator".

\par Related Module:
\ref grp_form



\defgroup grp_simple Simple Concretizations
\ingroup grp_root
\brief Simple concretizations of abstract classes in
\ref grp_root 
\details
Table: ZfBlank_DbTable \n
Row: ZfBlank_ActiveRow



\defgroup grp_feedback Site Feedback
\ingroup grp_root
\brief Provides model for users' feedback on a site.
\details
Table: ZfBlank_DbTable_Feedback \n
Row: ZfBlank_Feedback

\par Related Module:
\ref grp_user



\defgroup grp_pagecontent Static Content
\ingroup grp_root
\brief Static content blocks identified by unique names.
\details 
Table: ZfBlank_DbTable_PageContent \n
Row: ZfBlank_PageContent \n
View helper: ZfBlank_View_Helper_PageContent

Lets static content to be brought out from view scripts to database or file.



\defgroup grp_tag Tag
\ingroup grp_root
\brief Tags to be attached to items.
\details
Table: ZfBlank_DbTable_Tags \n
Row: ZfBlank_Tag

Models that support tagging:
- \ref grp_item
- \ref grp_commentable
- \ref grp_article
- \ref grp_feed



\defgroup grp_tree Tree
\ingroup grp_root
\brief Generalization for tree-like structures.
\details
Table: ZfBlank_DbTable_Tree \n
Row: ZfBlank_Tree \n
Tree Iterators: names begin with ZfBlank_Tree_Iterator \n
Tree Renderers: names begin with ZfBlank_Tree_Renderer \n
View Helpers: names begin with ZfBlank_View_Helper

One tree is contained in one database table. Row class is a single tree node.
For basic info on iterators and renderers see ZfBlank_Tree documentation.

Interactive tree editor written in JavaScript/jQuery is provided as well.



\defgroup grp_category Category
\ingroup grp_tree
\brief Categories characterizing items and able to be organized in tree.
\details
Table: ZfBlank_DbTable_Categories \n
Row: ZfBlank_Category

Models that support categorization:
- \ref grp_item
- \ref grp_commentable
- \ref grp_article
- \ref grp_feed
- \ref grp_faq



\defgroup grp_comment Comment
\ingroup grp_tree
\brief User comments that can comment one another, i.e. be hierarchical
\details
Table: ZfBlank_DbTable_Comments \n
Row: ZfBlank_Comment

Models that support commenting:
- \ref grp_commentable
- \ref grp_article
- \ref grp_feed

\par Related Module:
\ref grp_user



\defgroup grp_item Item
\ingroup grp_tree
\brief Generalization for item able to be tagged, categorized and be a part
(node) in a tree structure.
\details
Table: ZfBlank_DbTable_Items \n
Row: ZfBlank_Item

\par Related Modules:
\ref grp_tag, \ref grp_category



\defgroup grp_commentable Commentable Item
\ingroup grp_item 
\brief Generalization for item able to be tagged, categorized, commented by
users and be a part (node) in a tree structure.
\details
Table: ZfBlank_DbTable_Commentable \n
Row: ZfBlank_Commentable

\par Related Module:
\ref grp_comment



\defgroup grp_article Article
\ingroup grp_commentable
\brief Article that can be tagged, categorized and commented by users
\details
Table: ZfBlank_DbTable_Articles \n
Row: ZfBlank_Article

\par Related Modules:
\ref grp_tag,
\ref grp_category,
\ref grp_comment (only row class is used),
\ref grp_user



\defgroup grp_feed Items Feed
\ingroup grp_article
\brief Generalization for feed of items (e.g. news) that can be tagged,
categorized, commented by users, and able to generate short descriptions
from body text.
\details
Table: ZfBlank_DbTable_Feed \n
Row: ZfBlank_FeedItem

\par Related Modules:
\ref grp_tag,
\ref grp_category,
\ref grp_comment (only row class is used),
\ref grp_user



\defgroup grp_faq FAQ
\ingroup grp_item
\brief Question &mdash; Answer for Frequently Asked Questions, able to be
categorized.
\details
Table: ZfBlank_DbTable_Faq \n
Row: ZfBlank_Faq

\par Related Module:
\ref grp_category



\defgroup grp_user User
\ingroup grp_root
\brief User account with registration and authentication functionality.
\details
Table: ZfBlank_DbTable_Users \n
Row: ZfBlank_User

\par Related Modules:
\ref grp_feedback,
\ref grp_comment,
\ref grp_article,
\ref grp_feed



\defgroup grp_sitemenu Site Menu
\brief Multi-level editable site menu.
\details
DB Table: ZfBlank_DbTable_SiteMenu \n
Menu Node: ZfBlank_SiteMenu \n
View Helpers: names begin with ZfBlank_View_Helper



\defgroup grp_form Form
\brief Web form class extended to interact with
\ref ZfBlank_ActiveRow_Abstract "ActiveRow"
\details

\par Related Module:
\ref grp_root 



\defgroup grp_view Miscellaneous View Helpers
\brief View helpers that are not parts of other modules.

