<?php
namespace App\Http\Controllers\Projects;
use App\Models\Projects\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller{
    public function sanitizeUtf8($data){
        if (is_array($data) || $data instanceof \Illuminate\Support\Collection) {
            $cleaned_data = [];
            foreach ($data as $key => $value) {
                $cleaned_data[$key] = $this->sanitizeUtf8($value);
            }
            return $cleaned_data;
        }
        if (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'auto');
        }
        return $data;
    }

    public function index(){
        $projects = Project::select('COD_PROJETO','PRO_NOME','PRO_DATA','PRO_DATA_CONCLUSAO')
            ->where('pes_cod_gerente',Auth::user()->pes_cod )
            ->get();
        $dadosTratados = $this->sanitizeUtf8($projects->toArray());
        return Inertia::render('Projects/Index', [
            'projects' => $dadosTratados
        ]);
    }

}
