<?php

namespace App\DataTables;

use App\Models\BannerDetail;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BannerDetailsDataTable extends DataTable
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
            ->addColumn('action', function ($row) {
                return view('backend.pages.banner_details.action', compact('row'))->render();
            })
            ->addColumn('icon', function ($row) {
                if ($row->icon) {
                    return '<img src="' . asset('/storage/'.$row->icon) . '" alt="Icon" style="max-height: 50px;">';
                }
                return 'No Icon';
            })
            ->addColumn('status', function ($row) {
                return $row->status == 1 ? 
                    '<span class="badge bg-success">Aktiv</span>' : 
                    '<span class="badge bg-danger">Deaktiv</span>';
            })
            ->editColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('subtitle', function ($row) {
                return $row->subtitle;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->rawColumns(['action', 'icon', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BannerDetail $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BannerDetail $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('order', 'asc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('brends-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
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
            Column::make('title'),
            Column::make('subtitle'),
            Column::make('icon'),
            Column::make('order'),
            Column::make('status'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
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
        return 'BannerDetails_' . date('YmdHis');
    }
}