import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Select from 'react-select'



function Lookup({ apiUrl, placeholder = 'Select', selectedItem, onSelect }) {
    const options = [
  { value: 'chocolate', label: 'Chocolate' },
  { value: 'strawberry', label: 'Strawberry' },
  { value: 'vanilla', label: 'Vanilla' }
    ]
return(
    
  <Select placeholder={placeholder} options={options} />

)
    
    
}

export default Lookup;