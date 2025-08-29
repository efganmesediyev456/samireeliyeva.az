<?php

namespace App\DataTables;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TopicsDataTable extends DataTable
{
    protected $subcategory;

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->$k = $v;
            }
            return $this;
        }
        
        $this->$key = $value;
        return $this;
    }

    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                return view('backend.pages.topics.actions', compact('item'), [
                    'editRoute' => route('admin.subcategories.topics.edit', [$this->subcategory->id, $item->id]),
                    'deleteRoute' => route('admin.subcategories.topics.destroy', [$this->subcategory->id, $item->id]),
                    'categoriesRoute' => route('admin.subcategories.topics.categories.index', [$this->subcategory->id, $item->id]),

                ]);
            })
            ->addColumn('title', function ($item) {
                return $item->translate() ? $item->translate()->title : 'N/A';
            })
            ->addColumn('subtitle', function ($item) {
                return $item->translate() ? $item->translate()->subtitle : 'N/A';
            })
            ->editColumn('icon', function ($item) {
                if ($item->icon) {
                    return '<img src="' . asset("storage/{$item->icon}") . '" width="50">';
                }
                return 'N/A';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->rawColumns(['action', 'icon'])
            ->setRowId('id');
    }

    public function query(Topic $model): QueryBuilder
    {
        return $model->newQuery()->where('subcategory_id', $this->subcategory->id);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('topics-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('title')->title('Başlıq'),
            Column::make('subtitle')->title('Alt Başlıq'),
            Column::make('icon')->title('İkon'),
            Column::make('created_at')->title('Tarix'),
            Column::computed('action')->title('Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Topics_' . date('YmdHis');
    }
}