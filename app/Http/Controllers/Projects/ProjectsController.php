<?php
namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Traits\SanitizesUtf8;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProjectsController extends Controller
{
    use SanitizesUtf8;

    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $projects = $this->projectService->getProjectsForUser(Auth::user()->pes_cod);
        
        $dadosTratados = $this->sanitizeUtf8($projects->toArray());
        
        return Inertia::render('Projects/Index', [
            'projects' => $dadosTratados
        ]);
    }
}
