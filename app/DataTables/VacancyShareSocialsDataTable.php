<?php

namespace App\DataTables;

use App\Models\VacancyShareSocial;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VacancyShareSocialsDataTable extends DataTable
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
                return view('backend.pages.vacancy-share-socials.action', compact('row'))->render();
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . asset('/storage/'.$row->image) . '" alt="Social Image" style="max-height: 50px;">';
                }
                return 'No Image';
            })
            ->editColumn('url', function ($row) {
                return '<a href="' . $row->url . '" target="_blank">' . $row->url . '</a>';
            })
            
            ->editColumn('order', function ($row) {
                return $row->order;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })

             ->addColumn('status', function ($item) {
                $checked = $item->status ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox" data-id="' . $item->id . '" ' . $checked . '>
                </div>';
            })

            ->rawColumns(['action', 'image', 'url', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VacancyShareSocial $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VacancyShareSocial $model): QueryBuilder
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
            ->setTableId('vacancy-share-socials-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy([0, 'desc'])
            ->selectStyleSingle()
            ->parameters([
                'pageLength' => 25, // 1 səhifədə 25 nəticə
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hamısı"]],
                'rowReorder' => [
                    'dataSrc' => 'id',
                ],
                'initComplete' => "function(settings, json) {
                    var table = this.api();
                    table.on('row-reorder', function (e, diff, edit) {
                        let data = [];
                        for (let i = 0; i < diff.length; i++) {
                            data.push({
                                id: table.row(diff[i].node).id(),
                                newPosition: diff[i].newData
                            });
                        }
                        if (data.length) {
                            $.ajax({
                                url: '" . route('admin.all.update-order') . "',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                    items: data,
                                    model:'" . addslashes(VacancyShareSocial::class) . "'
                                },
                                success: function (response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Uğurlu!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                },
                                error: function (xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Xəta!',
                                        text: 'Sıralama yenilənmədi'
                                    });
                                }
                            });
                        }
                    });


                    $(document).on('change', '.status-switch', function() {
                                var id = $(this).data('id');
                                var status = $(this).prop('checked') ? 1 : 0;
                                
                                $.ajax({
                                    url: '" . route('admin.update-status') . "',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                        id: id,
                                        status: status,
                                        model:'" . addslashes(VacancyShareSocial::class) . "'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Uğurlu!',
                                            text: response.message,
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Xəta!',
                                            text: 'Status yenilənmədi'
                                        });
                                        
                                        // Revert switch to original state if there's an error
                                        $(this).prop('checked', !status);
                                    }
                                });
                            });

                }",
            ])->buttons(
                Button::make('excel')->text('Excel-ə ixrac et'),
                Button::make('csv')->text('CSV-ə ixrac et'),
                Button::make('pdf')->text('PDF-ə ixrac et'),
                Button::make('print')->text('Çap et'),
                Button::make('colvis')->text('Sütunları göstər/gizlət'),
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
            Column::make('url'),
            Column::make('image'),
                Column::make('status')->title('Status')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
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
        return 'VacancyShareSocials_' . date('YmdHis');
    }
}