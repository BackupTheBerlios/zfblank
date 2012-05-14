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

/** \brief ZF_Blanks interface for forms that can interact with
    ZfBlank_ActiveRow.
    \ingroup grp_form
    \see ZfBlank_ActiveRow_Abstract
*/

interface ZfBlank_Form_Interface
{

    /** \brief Return data to be imported by ZfBlank_ActiveRow_Abstract.
    \zfb_read Form_Interface..exportValuesArray */
    public function exportValuesArray();

}
