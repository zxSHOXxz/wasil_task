<?php

namespace App\DataTables;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class PropertiesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->editColumn('name', function (Property $property) {
                return view('pages/apps/property-management/columns/_property', compact('property'));
            })
            ->editColumn('status', function (Property $property) {
                $statusClasses = [
                    'available' => 'badge badge-success fs-7 fw-bold px-3 py-2',
                    'unavailable' => 'badge badge-danger fs-7 fw-bold px-3 py-2',
                ];
                $statusClass = $statusClasses[$property->status] ?? 'badge badge-primary fs-7 fw-bold px-3 py-2';
                return '<div class="d-flex justify-content-center"><span class="' . $statusClass . '">' . ucfirst($property->status) . '</span></div>';
            })
            ->editColumn('price_per_night', function (Property $property) {
                return '<div class="d-flex justify-content-end">
                    <div class="text-end">
                        <span class="fs-5 fw-bolder text-dark">$' . number_format($property->price_per_night, 2) . '</span>
                        <div class="fs-8 fw-semibold text-muted">per night</div>
                    </div>
                </div>';
            })
            ->editColumn('description', function (Property $property) {
                return '<div class="d-flex align-items-center">
                    <span class="text-gray-800 fs-6 fw-semibold" data-bs-toggle="tooltip" data-bs-placement="top" title="' . e($property->description) . '">'
                    . Str::limit($property->description, 50) . '</span>
                </div>';
            });

        if (auth()->user()->hasRole('admin')) {
            $dataTable->addColumn('action', function (Property $property) {
                return view('pages/apps/property-management/columns/_actions', compact('property'));
            });
        }

        return $dataTable
            ->rawColumns(['status', 'price_per_night', 'description'])
            ->setRowId('id')
            ->setRowClass(function () {
                return 'fw-semibold';
            })
            ->setRowAttr([
                'class' => 'border-bottom border-gray-200',
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Property $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('properties-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'card-header border-0 p-0 d-flex flex-column flex-md-row align-items-center justify-content-between'<'d-flex align-items-center'l><'d-flex align-items-center gap-2'>>" .
                "<'table-responsive'tr>" .
                "<'card-footer d-flex align-items-center justify-content-between py-6'<'text-muted'i><'pagination pagination-circle pagination-outline'p>>")
            ->addTableClass('table table-hover table-row-bordered gy-5 gs-7')
            ->setTableHeadClass('border-bottom border-gray-200 text-start text-gray-900 fw-bold fs-6 text-uppercase')
            ->parameters([
                'scrollX' => true,
                'ordering' => true,
                'pageLength' => 10,
                'language' => [
                    'lengthMenu' => '<select class="form-select form-select-sm form-select-solid">' .
                                    '<option value="10">10 rows</option>' .
                                    '<option value="25">25 rows</option>' .
                                    '<option value="50">50 rows</option>' .
                                    '<option value="100">100 rows</option>' .
                                '</select>',
                    'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
                    'paginate' => [
                        'first' => '<i class="ki-duotone ki-double-left fs-4"></i>',
                        'last' => '<i class="ki-duotone ki-double-right fs-4"></i>',
                        'next' => '<i class="ki-duotone ki-arrow-right fs-4"></i>',
                        'previous' => '<i class="ki-duotone ki-arrow-left fs-4"></i>'
                    ],
                ],
                'drawCallback' => 'function() {' . file_get_contents(resource_path('views/pages/apps/property-management/columns/_draw-scripts.js')) . '}',
                'order' => [[0, 'desc']],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')
                ->title('<span class="text-gray-900">#</span>')
                ->addClass('ps-9')
                ->width(50),
            Column::make('name')
                ->title('<span class="text-gray-900">PROPERTY</span>')
                ->addClass('min-w-300px ps-9'),
            Column::make('description')
                ->title('<span class="text-gray-900">DESCRIPTION</span>')
                ->addClass('min-w-200px'),
            Column::make('status')
                ->title('<span class="text-gray-900">STATUS</span>')
                ->addClass('text-center min-w-100px'),
            Column::make('price_per_night')
                ->title('<span class="text-gray-900">PRICE</span>')
                ->addClass('text-end min-w-125px pe-5'),
        ];

        if (auth()->user()->hasRole('admin')) {
            $columns[] = Column::computed('action')
                ->title('<span class="text-gray-900">ACTIONS</span>')
                ->addClass('text-center min-w-100px pe-9')
                ->exportable(false)
                ->printable(false)
                ->width(100);
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Properties_' . date('YmdHis');
    }
}
