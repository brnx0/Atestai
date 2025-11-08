<?php

namespace App\Http\Controllers\Projects;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Projects\Sprint;



class SprintsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($sprintCod){ 
        
       
        return Sprint::select('*')->where('VER_COD_PROJETO', $sprintCod)->orderBy('VER_VERSAO_SEQ', 'DESC')->get();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createArquivo($sprintCod){
        try {
        $dados = DB::connection('connectionSig')->select("
            SELECT 
            C.COD_CASO, 
            C.CAS_RESUMO, 
            replace(V.VER_NOME,'Sprint','') as Sprint,  
            A.UPR_ARQUIVO
            FROM SUP_VERSAO V
            INNER JOIN SUP_CASO C ON C.CAS_COD_VERSAO = V.COD_VERSAO
            LEFT JOIN SUP_UPLOAD_REQUISITO  A ON C.COD_CASO = A.COD_CASO
            WHERE C.CAS_CAT = 2 AND V.COD_VERSAO =".$sprintCod);
            $arquivo = new ProjectService();
            $caminhoCompletoArquivo = $arquivo->createDocument($dados);
            if (!file_exists($caminhoCompletoArquivo)) {
                return response('Arquivo não encontrado no servidor.', 404);
            }
            return Response::download($caminhoCompletoArquivo,'Nome_Download_Desejado.docx');

   
        //  return ProjectService::docJuntarAnexo($dados)  ;
        } catch (\Throwable $th) {
            return $th;
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $return = [ [
              "id"=> 1,
            "nome"=>'Joel'

        ],
        [
              "id"=> 1,
            "nome"=>'Joel'

        ]
          
        ];
        return $return;
 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
