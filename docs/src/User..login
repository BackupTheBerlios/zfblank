    Creates **Zend_Auth** identity for the user.
    Parameter is an array that have 'username' and 'password' 
    indexes with appropriate values. If this parameter is omitted, the array
    is exported from form that must be set by form() before calling this method
    and be capable to export an array with specified indexes. If there is no
    form, the values must be set already in the object data.
    \param array $values username and password values (optional)
    \see ZfBlank_Form::exportValuesArray()
    \see ZfBlank_ActiveRow_Abstract::form()
    \see ZfBlank_ActiveRow_Abstract::setFromForm()
    \return boolean **true**: login is successful; **false**: login is failed
