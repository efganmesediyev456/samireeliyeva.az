<?php

namespace App\DataTables;

use App\Models\ExamQuestion;
use App\Models\UserExam;
use App\Models\UserExamStart;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExamResultDatatable extends DataTable
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
            ->addColumn('action', function ($exam) {
                return view('backend.pages.exam_results.actions', [
                    'showRoute' => route('admin.users.exams.results.show', 
                                     ['user'=>$this->user->id, 'exam'=>$exam->exam_id]),
                ]);
            })
            ->editColumn('user_id', function ($exam) {
                return $exam->user ? $exam->user->name : 'N/A';
            })
            ->editColumn('exam_id', function ($exam) {
                return $exam->exam ? $exam->exam->title : 'N/A';
            })
             ->addColumn('percentage', function ($exam) {
                $userExams=UserExam::where('user_id', $this->user->id)->where('exam_id', $exam->exam_id);
                $userCorrectQuestions=$userExams->whereHas("examQuestionOption",function($q){
                    return $q->where('is_correct', 1);
                })->orWhere('is_admin_correct', 1)->get()->count();
                $percentage = $exam->exam?->questions?->count() > 0 ? ($userCorrectQuestions / $exam->exam?->questions?->count()) * 100 : 0;
                $percentage = number_format($percentage);
                return $percentage.'%';
            })
        
            ->editColumn('created_at', function ($exam) {
                return $exam->created_at->format('d.m.Y H:i');
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    public function query(UserExamStart $model): QueryBuilder
    {
        return $model->newQuery()->where('user_id', $this->user?->id);
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
            Column::make('user_id')->title('İstifadəçi'),
            Column::make('exam_id')->title('İmtahan'),
            Column::make('percentage')->title('Faiz'),
            // Column::make('options_count')->title('Cavab variantları sayı'),
            // Column::make('type')->title('Tipi'),
            // Column::make('points')->title('Xal'),
            // Column::make('position')->title('Sıra'),
                // Column::make('status')->title('Status')
                // ->exportable(false)
                // ->printable(false)
                // ->width(60)
                // ->addClass('text-center'),
            Column::computed('action')->title('Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'ExamQuestions_' . date('YmdHis');
    }
}