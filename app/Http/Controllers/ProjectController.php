<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectsDataTable;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create project', ['only' => ['create', 'store']]);
        $this->middleware('can:read project', ['only' => ['show', 'index']]);
        $this->middleware('can:edit project', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete project', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProjectsDataTable $dataTable)
    {
        return $dataTable->render('pages/apps.projects.list');
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
    public function show(Project $project)
    {
        return view('pages/apps.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
