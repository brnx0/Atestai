<?php

namespace App\Services;

use App\Models\Projects\Sprint;
use Illuminate\Support\Facades\DB;

class SprintService
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Get sprints.
     *
     * @param int|string $sprintCod
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSprints($sprintCod)
    {
        return Sprint::select('*')
            ->where('COD_PROJETO', $sprintCod)
            ->orderBy('COD_VERSAO', 'DESC')
            ->get();
    }

    /**
     * Generate document for a specific sprint.
     *
     * @param int|string $sprintCod
     * @return string|false
     * @throws \Exception
     */
    public function createSprintDocument($sprintCod)
    {
        $dados = DB::connection('connectionSig')->select("
            SELECT 
                C.COD_CASO, 
                C.CAS_RESUMO, 
                replace(V.VER_NOME,'Sprint','') as Sprint,
                C.CAS_DESCRICAO
            FROM SUP_VERSAO V
            INNER JOIN 
                SUP_CASO C ON C.CAS_COD_VERSAO = V.COD_VERSAO
            WHERE 
                C.CAS_CAT = 2 AND V.COD_VERSAO = ?", [$sprintCod]
        );

        return $this->projectService->createDocument($dados, $sprintCod);
    }
}
