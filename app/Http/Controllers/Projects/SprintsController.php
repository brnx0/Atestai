<?php
namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\SprintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class SprintsController extends Controller
{
    protected $sprintService;

    public function __construct(SprintService $sprintService)
    {
        $this->sprintService = $sprintService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($sprintCod)
    { 
        return response()->json($this->sprintService->getSprints($sprintCod));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createArquivo($sprintCod)
    {
        try {
            $caminhoCompletoArquivo = $this->sprintService->createSprintDocument($sprintCod);

            if (!$caminhoCompletoArquivo || !file_exists($caminhoCompletoArquivo)) {
                return response('Arquivo não encontrado no servidor.', 404);
            }

            return Response::download($caminhoCompletoArquivo, 'Nome_Download_Desejado.docx');
            
        } catch (\Throwable $th) {
            Log::error('Erro ao criar arquivo da sprint.', [
                'sprintCod' => $sprintCod,
                'error' => $th->getMessage()
            ]);
            
            return response()->json(['message' => 'Ocorreu um erro ao processar o arquivo.', 'details' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $return = [ 
            [
                "id" => 1,
                "nome" => 'Joel'
            ],
            [
                "id" => 1,
                "nome" => 'Joel'
            ] 
        ];
        return response()->json($return);
    }
}