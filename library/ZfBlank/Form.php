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

/** \brief Base form class in ZF_Blanks package. Extends **Zend_Form**.
    \ingroup grp_form
    \see **Zend_Form**
*/

class ZfBlank_Form extends Zend_Form
        implements ZfBlank_Form_Interface
{

    /**
    \brief Export values to be set by ZfBlank_ActiveRow_Abstract::setFromForm()
    method.

    Returns result of **Zend_Form::getValues()**. Overload this method to make
    additional processing.
    \see ZfBlank_Form_Interface::exportValuesArray()
    \see **Zend_Form::getValues()**
    \return array
    */
    public function exportValuesArray() {
        return $this->getValues();
    }

}
