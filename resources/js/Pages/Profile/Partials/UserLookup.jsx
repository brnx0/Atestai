import React, { useState, useEffect } from 'react';
import Lookup from '@/Components/Lookup'
import axios from 'axios'; 

export default function UserLookup() {
    return(
            <Lookup placeholder="Escolha um Usuário"/>
    )
}

