\mainpage ZF_Blanks Documentation


\section Introduction

ZF_Blanks is a model-space library that works in Zend Framework environment. It
presents a number of models commonly used in website development, such as user
account, article, user feedback and comments and the like, and also some
generalizations, such as trees. Complete list can be found on the
<a href="modules.html">modules</a> page.
Some models go with view helpers and other auxiliaries.

Datasets in models can be varied without extending a class, along with data
access and manipulation methods.
So, the model classes are more focused on providing the functionality specific
to the model rather than defining the complete set of data.

\subsection Concepts

Functionality of the package and the majority of models in it is based on tandem
of table and row classes.
Table class extends **Zend_Db_Table_Abstract** and is a sort of
[table data gateway] (http://martinfowler.com/eaaCatalog/tableDataGateway.html).
Row class extends **Zend_Db_Table_Row_Abstract** and is a sort of
[active record] (http://martinfowler.com/eaaCatalog/activeRecord.html).

ZF_Blanks tends to conform to the \"thin controller &ndash; fat model\"
principle
which recommends to take business logic away from controller as much as
possible. In this library, logic is distributed among models and view helpers.
The models tend to provide a "reasonable maximum" of functionality, so
individual features may be used or not, depending on concrete project needs.

\section directories Directory Structure

Directory structure inside the archive imitates directory structure of a normal
Zend Framework project. The library itself is at `library/ZfBlank`.
Other directories are described below.

Directory        | Description
:--------------- | :------------------------------------------------------------
\c docs          | Miscellaneous stuff for Doxygen documentor used to generate this documentation.
\c application   | The example application. Website demonstrating use of the library.
\c public        | Usual starting point for the example application.
\c scripts       | Installation scripts for the example application.
\c schema        | Database table schemas used by example application.
\c data          | Initial data used by example application installation script.
\c texts         | Example application static texts (see \ref grp_pagecontent)

\section install Installation

\subsection installfiles Files

As can be seen from the \ref directories above, if you are not installing the
example application along with the library, then the only thing you need to copy
to your project from the archive is `library/ZfBlank` directory tree.
To inform your application about ZF_Blanks
library add this settings to your `application.ini` file:

    includePaths.library = APPLICATION_PATH "/../library"
    autoloaderNamespaces.zfblank = "ZfBlank"
    resources.view.helperPath.ZfBlank_View_Helper = APPLICATION_PATH "/../library/ZfBlank/View/Helper"

\subsection installdb Database

You will need to create database tables for the models you will use.
Reference schemas are given in documentation for every table class.
There are files in the directories `schema` and `docs/src` whose names begin
with `zfb.` prefix and end with `.sql`.
They contain copies of that SQL code. All the schemas was written for MySQL and
may need correction for using with another DB management systems.
If you tweak some table definitions, keep in mind the warnings
written in \ref ZfBlank_ActiveRow_Abstract "ActiveRow" class description.

\subsection installex Example Application

If you install example application for some purposes, you can initialize
database by running `scripts/dbinit.php` script, for example, with shell
command:

    php -f scripts/dbinit.php

Also setup your database settings in `application/configs/application.ini` file.
Note that there is dummy settings in \c [development] section.

Compiled HTML documentation is not included in the archive.
If you have Doxygen installed, you can compile the documentation with the
command:

    doxygen docs/doxygen

The documentation will appear in `public/docs` directory.

\section docsguide Guide to The Documentation

All classes in ZF_Blanks are grouped into <a href="modules.html">modules</a>.
A module contains its classes and also can contain another modules.
Thus, modules form some kind of hierarchy and the rule is that in contained
(child) module there are some classes that extend classes in his parent
module. For example: module \ref grp_root contains module \ref grp_tag and
class ZfBlank_Tag in \ref grp_tag module extends ZfBlank_ActiveRow_Abstract in
\ref grp_root module. Every module's title page lists classes and modules it
contains. Complete inheritance diagrams for all classes can be found on
<a href="inherits.html">graphical class hierarchy</a> page.

It's recommended to start reading from the \ref grp_root module because its
classes lay the basis for the majority of classes in ZF_Blanks.

\section todosection Todo

Here is project-wide TODO list.

\todo Test, catch bugs.
\todo Create unit tests.
\todo Optimizations for speed.
\todo Improve documentation.
\todo I18N.
\todo Search facilities (maybe, integrate with Zend_Search_Lucene).

