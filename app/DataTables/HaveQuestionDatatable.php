<?php

namespace App\DataTables;

use App\Models\HaveQuestion;
use App\Models\PriceQuote;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HaveQuestiondatatable extends DataTable
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
            ->addColumn('file', function ($row) {
                if ($row->file_path) {
                    return '<a class="btn btn-success btn-sm" href="' . asset('/storage/'.$row->file_path) . '" alt="Brend Image" style="max-height: 50px;">Fayla Bax</a>';
                }
                return 'No fayl';
            })
            ->rawColumns(['file'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PriceQuote $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HaveQuestion $model): QueryBuilder
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
            ->setTableId('price-quotes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
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
            Column::make('name')->title('Ad'),
            Column::make('surname')->title('Soyad'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Nömrə'),
            Column::make('note')->title('Qeyd'),
            Column::make('created_at')->title('Yaradılma tarixi')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'HaveQuestion_' . date('YmdHis');
    }
}
