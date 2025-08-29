<?php

namespace App\DataTables;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VacanciesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                $html = '<div class="d-flex justify-content-center">';
                $edit = '<a href="' . route('admin.vacancies.edit', $item->id) . '" class="btn btn-sm btn-primary mx-1"><i class="fas fa-edit"></i></a>';
                $delete = '<form action="' . route('admin.vacancies.destroy', $item->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Silmək istədiyinizə əminsiniz?\')"><i class="fas fa-trash"></i></button>
                            </form>';
                $html .= $edit . $delete;
                $html .= '</div>';
                return $html;
            })
            ->addColumn('vacancy_title', fn($item) => $item->vacancy_title)
            ->addColumn('vacancy_location', fn($item) => $item->vacancy_location)
            ->setRowId('id');
    }

    public function query(Vacancy $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('vacancies-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print')
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('vacancy_title')->title('Vakansiya adı'),
            Column::make('vacancy_location')->title('Ünvan'),
            Column::make('vacany_start_at')->title('Başlama tarixi'),
            Column::make('vacany_expired_at')->title('Bitmə tarixi'),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'vacancies_' . date('YmdHis');
    }
}