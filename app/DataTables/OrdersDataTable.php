<?php

namespace App\DataTables;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('customer', function ($row) {
                return $row->user?->name;
            })
            ->addColumn('action', function ($row) {
                $viewBtn = '<a href="' . route('admin.orders.show', $row->id) . '" class="btn btn-sm btn-info" title="Ətraflı"><i class="fas fa-eye"></i></a>';
//                $deleteBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete-btn" title="Sil"><i class="fas fa-trash"></i></a>';
                return '<div class="btn-group">' . $viewBtn  . '</div>';
            })
            ->addColumn("subCategory", function($row){
                return $row->subCategory?->title;
            })
            ->editColumn("duration_months", function($row){
                return $row->duration_months;
            })
            ->editColumn("status", function($row){
                return $row->status;
            })
            ->rawColumns(['status_label', 'action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->parameters([
                'scrollX' => true,
                'autoWidth' => false,
                'fixedColumns' => true,
                'columnDefs' => [
                   
                  
                ],
            ])
            ->buttons(
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('colvis')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::computed('customer', 'Müştəri')
                ->exportable(false)
                ->printable(false)
                ->searchable(true),

                
         
            Column::make('amount', 'Ümumi məbləğ'),
            Column::make('subCategory')->title('Kateqoriya'),
            Column::computed('order_number', 'Sifarişin nömrəsi'),
            Column::make('created_at', 'Yaradılma tarixi'),
            Column::make('duration_months', 'Ay dəyəri'),
            Column::make('status', 'Status'),
            Column::computed('action', 'Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
