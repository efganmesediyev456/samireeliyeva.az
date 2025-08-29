<?php

namespace App\DataTables;

use App\Models\ExamQuestion;
use App\Models\Order;
use App\Models\UserExam;
use App\Models\UserExamStart;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubScriptionDatatable extends DataTable
{
    protected $subcategory;
    protected $exam;

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
            ->editColumn('created_at', function ($exam) {
                return $exam->created_at->format('d.m.Y H:i');
            })
             ->addColumn('user', function ($exam) {
                return $exam->user->name;
            })
             ->addColumn('subCategory', function ($exam) {
                return $exam->subCategory?->title;
            })
            ->addColumn('packageItem', function ($exam) {
                return $exam->packageItem?->title.' ('.$exam->packageItem->packet?->title.')';
            })
            ->addColumn('status', function ($exam) {
                return match ($exam->status) {
                        "pending" => 'Gözləmədə',
                        "completed" => 'Uğurlu ödəniş',
                        default => 'Unknown Type',
                };
            })
            ->editColumn('expires_at', function ($exam) {
                return $exam->expires_at->format('Y-m-d H:i:s');
            })
             ->editColumn('created_at', function ($exam) {
                return $exam->created_at->format('Y-m-d H:i:s');
            })
            ->setRowId('id');
    }

    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()->where('user_id', $this->user?->id)->where('status','completed');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('exam-questions-table')
            ->columns($this->getColumns())
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
                                    model:'".addslashes(ExamQuestion::class)."'
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
                                        model:'".addslashes(ExamQuestion::class)."'
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
            Column::make('user')->title('İstifadəçi adı'),
            Column::make('packageItem')->title('Abunə paketi'),
            Column::make('subCategory')->title('Abunə olduğu bölmə'),
            Column::make('status')->title('Status'),
            Column::make('expires_at')->title('Bitmə tarixi'),
            Column::make('created_at')->title('Yaradılma tarixi'),
           
        ];
    }

    protected function filename(): string
    {
        return 'ExamQuestions_' . date('YmdHis');
    }
}