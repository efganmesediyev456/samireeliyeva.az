<?php

namespace App\DataTables;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Language;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($user) {
                $html = '<div class="d-flex gap-1">';
                // $properties = '<a href="'.route('admin.products.properties_old.index', ['id'=>$user->id]).'" class="btn  btn-sm fs-5"><i class="fas fa-plus"></i></a>';

                $edit = '<a href="'.route('admin.products.edit', $user->id).'" class="btn  btn-sm fs-5"><i class="fas fa-edit"></i></a>';
                $delete = '<form action="' . route('admin.products.destroy', $user->id) . '" method="POST" class="delete-form">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm fs-5 text-danger delete-btn"><i class="fas fa-trash"></i></button>
                            </form>';
                $html.= $edit.$delete;
                $html.='</div>';
                return $html;
            })
            ->addColumn('title', fn($item)=>$item->title)
            ->addColumn('is_seasonal', function($item) {
                $checked = $item->is_seasonal ? 'checked' : '';
                return '<div class="form-check form-switch">
                          <input class="form-check-input toggle-seasonal" type="checkbox" role="switch" data-id="'.$item->id.'" '.$checked.'>
                        </div>';
            })
            ->addColumn('is_special_offer', function($item) {
                $checked = $item->is_special_offer ? 'checked' : '';
                return '<div class="form-check form-switch">
                          <input class="form-check-input toggle-special" type="checkbox" role="switch" data-id="'.$item->id.'" '.$checked.'>
                        </div>';
            })
            ->addColumn('is_bundle', function($item) {
                $checked = $item->is_bundle ? 'checked' : '';
                return '<div class="form-check form-switch">
                          <input class="form-check-input toggle-bundle" type="checkbox" role="switch" data-id="'.$item->id.'" '.$checked.'>
                        </div>';
            })
            ->addColumn('pick_of_status', function($item) {
                $checked = $item->pick_of_status ? 'checked' : '';
                return '<div class="form-check form-switch">
                          <input class="form-check-input toggle-weekly" type="checkbox" role="switch" data-id="'.$item->id.'" '.$checked.'>
                        </div>';
            })
            ->rawColumns(['action', 'is_seasonal', 'is_special_offer', 'is_bundle', 'pick_of_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('title')->title('Ad'),
            Column::make('product_code')->title('Məhsulun kodu'),
            Column::make('price')->title('Qiymət'),
            Column::make('quantity')->title('Stokda say'),
            Column::make('is_seasonal')->title('Mövsüm təklifləri')->orderable(false)->searchable(false),
            Column::make('is_special_offer')->title('Sevindirən təkliflər')->orderable(false)->searchable(false),
            Column::make('is_bundle')->title('Birlikdə daha sərfəli')->orderable(false)->searchable(false),
            Column::make('pick_of_status')->title('Həftənin seçimləri')->orderable(false)->searchable(false),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::computed('action')
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
        return 'products_' . date('YmdHis');
    }
}
