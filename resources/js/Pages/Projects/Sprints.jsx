import React, { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import axios from 'axios';

// ------------------------------------------------------------------
// 1. LÓGICA DO PORTAL (Função auxiliar interna)
// Garante que o conteúdo seja renderizado diretamente no body do HTML
// ------------------------------------------------------------------
function Portal({ children }) {
    const [mounted, setMounted] = useState(false);

    useEffect(() => {
        // Usa o useEffect para garantir que só seja montado no cliente
        setMounted(true);
    }, []);

    if (!mounted) {
        return null;
    }

    // Retorna o conteúdo usando createPortal para renderizar no document.body
    return createPortal(
        children,
        document.body 
    );
}

async function querySprints(projectCod){
    const dados = await axios.get(route('sprint.index',{sprintCod:projectCod }))
    return dados.data
}
async function  gerarArquivo(codSprint){
    axios.get(
        route('sprint.make',{sprintCod:codSprint}),{responseType: 'blob'}).
        then(response =>{       
            console.log(response.headers['content-disposition'])
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'nome_do_seu_arquivo.docx');
            document.body.appendChild(link);
            link.click(); 
            ink.remove();
            window.URL.revokeObjectURL(url);
    })  
}

// ------------------------------------------------------------------
// 2. COMPONENTE PRINCIPAL (O Modal de Sprints)
// ------------------------------------------------------------------
export default function Sprints ({ show, onClose, children,projectCod }) {
    if (!show) {
        return null;
    }
    
    const [sprints, setSprints] = useState([]);
    const [isLoading, setIsLoading] = useState(true);


    useEffect(() => {
        const loadData = async () => {
            setIsLoading(true);
            try {
                // AQUI é onde chamamos a função assíncrona
                const data = await querySprints(projectCod); 
                setSprints(data); // Armazena o resultado
            } catch (error) {
                // ... tratamento de erro
            } finally {
                setIsLoading(false); // Finaliza o carregamento
            }
        };
        if (projectCod) {
            loadData();
        }
    }, [projectCod]);

    return (
        <Portal> 
    
            <div 
                className="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 transition-opacity duration-300 overflow-y-auto"
                onClick={onClose} // Fecha ao clicar no overlay
            >
                
                {/* 2. Container Principal do Conteúdo */}
                <div 
                    className="bg-white rounded-xl shadow-2xl max-w-5xl w-full m-4 transform transition-all duration-500"
                    onClick={(e) => e.stopPropagation()} // Impede o fechamento ao clicar no conteúdo
                >
                    
                    <div class="mx-auto px-4 p-4"> 
                        <h1 class="text-3xl font-extrabold text-gray-800 mb-8 border-b-2 border-indigo-500 pb-2">
                            🚀 Detalhe das Sprints 
                        </h1>
                        {isLoading ?(
                            <div class="flex flex-col space-y-6">
                                Carregando...
                            </div>
                        ):
                        (
                            <div class="flex flex-col space-y-6">
                                {sprints.map(sprint =>(
                               
                   
                                
                                <div  className={`w-full bg-white rounded-xl shadow-lg p-6 border-l-8 border-${sprint.VER_LIBERA=='S'?'green':'red'}-500`}>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                        <span className={`mr-3 ${sprint.VER_LIBERA=='S'?'text-green':'text-red'}-500 ` }>
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </span>
                                        Sprint: <span class={``}>{sprint.VER_NOME}  </span>
                                    </h2>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm text-gray-600 border-t pt-4">
                                        <div>
                                            <span class="font-medium text-gray-800">Liberada </span>
                                            <span className={`block px-2 py-1 mt-1 bg-${sprint.VER_LIBERA=='S'?'green':'red'}-100 text-black-800 font-semibold rounded-lg text-center`} >{sprint.VER_LIBERA=='S'?'Sim':'Não'}</span>
                                        </div>
                                        
                                        <div>
                                            <span class="font-medium text-gray-800">Data de Início </span>
                                            <span class="block mt-1 text-gray-500 italic">{ new Date(sprint.VER_DATA).toLocaleDateString()}</span>
                                        </div>


                                        <div>
                                            <span class="font-medium text-gray-800">Previsão de Data  </span>
                                            <span class="block mt-1 text-gray-700">{new Date(sprint.VER_PREV_DATA).toLocaleDateString()}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-800">Ação</span>
                                            <button class='text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800' 
                                            onClick={
                                                ()=>gerarArquivo(sprint.COD_VERSAO)

                                            }>Gerar Documento</button>
                                        </div>
                                    </div>
                                </div>
                            ))} 

                        </div>
                        )}
                    </div>  
                </div>
            </div>
        </Portal>
    );
}