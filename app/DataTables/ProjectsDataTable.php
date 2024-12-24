<?php

namespace App\DataTables;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProjectsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function (Project $project) {
                return view('pages/apps.projects.columns._project', compact('project'));
            })
            ->editColumn('completion_date', function (Project $project) {
                return date($project->completion_date);
            })
            ->editColumn('status', function (Project $project) {
                return sprintf('<span class="badge badge-info fw-bold">%s</span>', $project->status == 1 ? 'active' : 'inactive');
            })
            ->editColumn('tags', function (Project $project) {
                return $project->tags->map(function ($tag) {
                    return sprintf('<span class="badge badge-info">%s</span>', $tag->title);
                })->implode(' ');
            })

            ->addColumn('action', function (Project $project) {
                return view('pages/apps.projects.columns._actions', compact('project'));
            })
            ->rawColumns(['status', 'tags', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Project $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('projects-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12'tr>><'d-flex justify-content-between'<'col-sm-12 col-md-5'i><'d-flex justify-content-between'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-center text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/projects/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->addClass('text-center'),

            Column::make('name')
                ->addClass('text-center'),

            Column::make('description')
                ->addClass('text-center'),

            Column::make('status')
                ->addClass('text-center'),

            Column::make('completion_date')
                ->addClass('text-center'),


            Column::make('tags')
                ->addClass('text-center')
                ->title('Tags'),

            Column::computed('action')
                ->addClass('text-center')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Projects_' . date('YmdHis');
    }
}
