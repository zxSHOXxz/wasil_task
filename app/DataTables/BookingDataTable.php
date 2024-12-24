<?php

namespace App\DataTables;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;

class BookingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $dataTable = (new EloquentDataTable($query))
            ->editColumn('user_id', function (Booking $booking) {
                return '<div class="d-flex align-items-center">
                    <span class="text-gray-800 fs-6 fw-semibold">' . $booking->user->name . '</span>
                </div>';
            })
            ->editColumn('property_id', function (Booking $booking) {
                return '<div class="d-flex align-items-center">
                    <span class="text-gray-800 fs-6 fw-semibold">' . $booking->property->name . '</span>
                </div>';
            })
            ->editColumn('total_amount', function (Booking $booking) {
                return '<div class="d-flex justify-content-end">
                    <div class="text-end">
                        <span class="fs-5 fw-bolder text-dark">$' . number_format($booking->total_amount, 2) . '</span>
                    </div>
                </div>';
            })
            ->editColumn('start_date', function (Booking $booking) {
                return '<div class="d-flex align-items-center">
                    <span class="text-gray-800 fs-6 fw-semibold">' . $booking->start_date->format('Y-m-d') . '</span>
                </div>';
            })
            ->editColumn('end_date', function (Booking $booking) {
                return '<div class="d-flex align-items-center">
                    <span class="text-gray-800 fs-6 fw-semibold">' . $booking->end_date->format('Y-m-d') . '</span>
                </div>';
            })
            ->addColumn('update_status', function (Booking $booking) {
                return view('pages/apps/booking-management/columns/_status', compact('booking'));
            });

        if (auth()->user()->hasRole('admin')) {
            $dataTable->addColumn('action', function (Booking $booking) {
                return view('pages/apps/booking-management/columns/_actions', compact('booking'));
            });
        }

        return $dataTable->rawColumns([
            'user_id',
            'property_id',
            'total_amount',
            'start_date',
            'end_date',
            'update_status',
            'action'
        ])
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
    public function query(Booking $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'property'])
            ->select('bookings.*');

        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }

        if ($this->request()->has('search') && !empty($this->request()->get('search')['value'])) {
            $searchValue = $this->request()->get('search')['value'];

            $query->where(function ($q) use ($searchValue) {
                $q->whereHas('user', function ($userQuery) use ($searchValue) {
                    $userQuery->where('name', 'like', "%{$searchValue}%");
                })
                    ->orWhereHas('property', function ($propertyQuery) use ($searchValue) {
                        $propertyQuery->where('name', 'like', "%{$searchValue}%");
                    })
                    ->orWhere('start_date', 'like', "%{$searchValue}%")
                    ->orWhere('end_date', 'like', "%{$searchValue}%")
                    ->orWhere('total_amount', 'like', "%{$searchValue}%");
            });
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('bookings-table')
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
                'drawCallback' => 'function() {' . file_get_contents(resource_path('views/pages/apps/booking-management/columns/_draw-scripts.js')) . '}',
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
            Column::make('user_id')
                ->title('<span class="text-gray-900">USER</span>')
                ->addClass('min-w-200px ps-9'),
            Column::make('property_id')
                ->title('<span class="text-gray-900">PROPERTY</span>')
                ->addClass('min-w-200px'),
            Column::make('start_date')
                ->title('<span class="text-gray-900">START DATE</span>')
                ->addClass('min-w-125px'),
            Column::make('end_date')
                ->title('<span class="text-gray-900">END DATE</span>')
                ->addClass('min-w-125px'),
            Column::make('total_amount')
                ->title('<span class="text-gray-900">TOTAL</span>')
                ->addClass('text-end min-w-125px'),
            Column::computed('update_status')
                ->title('<span class="text-gray-900">UPDATE</span>')
                ->addClass('text-center min-w-100px pe-9')
                ->exportable(false)
                ->printable(false)
                ->width(100),
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
        return 'Bookings_' . date('YmdHis');
    }
}
