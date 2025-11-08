<?php

namespace App\Http\Controllers\Projects;
use App\Models\Projects\Project;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;


class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
    $projects = Project::select('*')->where('pes_cod_gerente',Auth::user()->pes_cod )->get();

    // Retorna a view Inertia
    return Inertia::render('Projects/Index', [
        'projects' => $projects,
    ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
