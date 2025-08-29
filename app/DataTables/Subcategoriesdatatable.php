<?php

namespace App\DataTables;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class Subcategoriesdatatable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                return view('backend.pages.subcategories.actions', compact('item'), [
                    'editRoute' => route('admin.subcategories.edit', $item->id),
                    'deleteRoute' => route('admin.subcategories.destroy', $item->id),
                    'topicsRoute' => route('admin.subcategories.topics.index', $item->id),
                    'videosRoute' => route('admin.subcategories.videos.index', $item->id),
                    'littleVideoRolicRoute' => route('admin.subcategories.little-video-rolics.index', $item->id),
                    'interviewPreparationRoute' => route('admin.subcategories.interview-preparations.index', $item->id),
                    'examRoute' => route('admin.subcategories.exams.index', $item->id),
                    'examCategoryRoute' => route('admin.subcategories.exam-categories.index', $item->id),
                    'examStatusRoute' => route('admin.subcategories.exam-statuses.index', $item->id),
                    'essayExamplesRoute' => route('admin.subcategories.essay-examples.index', $item->id),
                    'criticalReadingsRoute' => route('admin.subcategories.critical-readings.index', $item->id),
                    'pastExamQuestionsRoute' => route('admin.subcategories.past-exam-questions.index', $item->id),
                     'packetsRoute' => route('admin.subcategories.packets.index', $item->id),
                ]);
            })
            ->editColumn('image', function ($item) {
                if ($item->image) {
                    return '<img src="' . asset("storage/{$item->image}") . '" width="100">';
                }
                return 'N/A';
            })
            ->addColumn('title', function ($item) {
                return $item->translate() ? $item->translate()->title : 'N/A';
            })
            ->addColumn('category', function ($item) {
                return $item->category ? ($item->category->translate() ? $item->category->translate()->title : 'N/A') : 'N/A';
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
             ->addColumn('status', function($item) {
                $checked = $item->status ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox" data-id="'.$item->id.'" '.$checked.'>
                </div>';
            })
            ->rawColumns(['action', 'image', 'icon', 'status'])
            ->setRowId('id');
    }

    public function query(Subcategory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subcategories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
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
                                url: '".route('admin.all.update-order')."',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                    items: data,
                                    model:'".addslashes(Subcategory::class)."'
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
                                    url: '".route('admin.update-status')."',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                        id: id,
                                        status: status,
                                        model:'".addslashes(Subcategory::class)."'
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
            ]) ->buttons(
                Button::make('excel')->text('Excel-ə ixrac et'),
                Button::make('csv')->text('CSV-ə ixrac et'),
                Button::make('pdf')->text('PDF-ə ixrac et'),
                Button::make('print')->text('Çap et'),
                Button::make('colvis')->text('Sütunları göstər/gizlət'),
            );
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('image')->title('Şəkil'),
            Column::make('title')->title('Başlık'),
            Column::make('category')->title('Kateqoriya'),
            Column::make('icon')->title('İkon'),
              Column::make('status')->title('Status')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
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
        return 'Subcategories_' . date('YmdHis');
    }
}