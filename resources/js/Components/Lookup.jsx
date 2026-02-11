import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Select from 'react-select'
import AsyncSelect from 'react-select/async';

/**
 * @typedef {object} UserData
 * @property {number} PES_COD - O ID único do utilizador (será o 'value' no select).
 * @property {string} PES_NOME - O nome do utilizador (será o 'label' no select).

 */
/**
 * @typedef {object} SelectOption
 * @property {number | string} value - O valor único da opção.
 * @property {string} label - O texto visível da opção.
 */

function Lookup({ apiUrl, placeholder = 'Select', selectedItem, onSelect }) {

    const [isLoading, setIsLoading] = useState(true);
    const [options, setOptions] = useState([]);
    const [optionSelectd, setOptionSelectd] = useState(null);

    const fetch = async ()=>{
        setIsLoading(true);
        try {
            const response = await axios.get(route(apiUrl));
            const data = await response.data;
            /** @type {SelectOption[]} */
            const formattedOptions = data.map(user =>({
                value: user.PES_COD,
                label: user.PES_NOME
            }));
            setOptions(formattedOptions);
        } catch (error) {
            console.error("Erro ao buscar utilizadores:", error);
        } finally{
            setIsLoading(false)
        }
    };
    useEffect(()=>{
        fetch();
    },[])
    const handleChange = (selectedOption) => {
        setOptionSelectd(selectedOption);
        console.log("Utilizador Selecionado:", selectedOption);
    };
    
    return(
        <Select 
            id='usrSig'
            placeholder={placeholder} 
            options={options} onChange={handleChange}  
            value={optionSelectd} loadingMessage={() => "A carregar utilizadores..."} 
            noOptionsMessage={() => "Nenhum usuário encontrado."}
        />
    )
}

export default Lookup;