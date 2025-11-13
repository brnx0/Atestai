import { use, useState } from "react";
import Sprints from "./Sprints";



export default function Main({projects}){
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isCodProject, setIsCodProject] = useState()
    const closeModal = ()=> setIsModalOpen(false);



    return (
        
        <>
         <Sprints 
                show={isModalOpen} // Passa o estado de visibilidade
                onClose={closeModal} // Passa a função de fechar
                projectCod={isCodProject}
            ></Sprints>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-4">
                {projects.map(project => (
                   
                    <div 
                        key={project.COD_PROJETO} 
                        className="bg-white border border-gray-100 rounded-xl shadow-2xl shadow-gray-200/50 hover:shadow-indigo-400/30 transition-all duration-300 transform hover:-translate-y-1 flex flex-col min-h-full"
                    > 
                        {/* Bloco de Destaque Superior (Gradiente Elegante) */}
                        <div className="p-6 pb-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-t-xl">
                            <div className="flex justify-between items-center">
                                {/* Ícone representando o Projeto */}
                                <svg className="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                
                                {/* Botão de Ação/Edição */}
                                <button className="text-sm border border-white/50 px-3 py-1 rounded-full hover:bg-white/20 transition">
                                    Ações
                                </button>
                            </div>

                            {/* NOME DO PROJETO (Dinâmico) */}
                            <h3 className="mt-1 text-2xl font-bold">
                                {project.PRO_NOME}
                            </h3>
                        </div>

                        {/* Corpo Principal do Card - Detalhes e Métricas */}
                        <div className="p-6 flex flex-col flex-grow">
                            
                            {/* Status/Progresso (Estilizado) */}
                            <div className="mb-4">
                                <span className="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                    Em Andamento
                                </span>
                            </div>
                            
                            {/* Métricas (Similar a Datas/Números) */}
                            <div className="space-y-3 text-sm text-gray-700 flex-grow">
                                <div className="flex justify-between border-b border-gray-100 pb-2">
                                    <span className="font-medium text-gray-500">Início:</span>
                                    <span>{new Date(project.PRO_DATA).toLocaleDateString()}</span>
                                </div>
                                <div className="flex justify-between border-b border-gray-100 pb-2">
                                    <span className="font-medium text-gray-500">Prazo:</span>
                                    <span>{ new Date(project.PRO_DATA_CONCLUSAO).toLocaleDateString()}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="font-medium text-gray-500">Tarefas Concluídas:</span>
                                    <span className="font-bold text-green-600"></span>
                                </div>
                            </div>
                            
                        </div>
                        
                        {/* Rodapé com Link de Detalhes */}
                        <div className="p-4 bg-gray-50 border-t border-gray-100" onClick={()=>{setIsModalOpen(true);setIsCodProject(project.COD_PROJETO)}}>
                            
           
                            <label className="text-indigo-600 font-semibold hover:text-indigo-800 text-sm flex items-center justify-between transition">
                                Ver Detalhes
                                <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </label>
                            
                        </div>
                        
                    </div>
                ))}
            </div>
        </>
      

    );

}