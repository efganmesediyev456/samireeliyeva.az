<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ExamQuestionResultDatatable;
use App\DataTables\ExamResultDatatable;
use App\DataTables\TopicsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Topic;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\UserExam;
use App\Models\UserExamStart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\TopicSaveRequest;

class ExamResultController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = UserExamStart::class;
    }

    public function index(User $user, ExamResultDatatable $dataTable)
    {
        return $dataTable->with('user', $user)->render('backend.pages.exam_results.index', compact('user'));
    }

    public function show(User $user, Exam $exam, ExamQuestionResultDatatable $dataTable)
    {
        return $dataTable->with([
            'user' => $user,
            'exam' => $exam
        ])->render('backend.pages.exam_results.show', compact('user', 'exam'));
    }


    public function showQuestion(User $user, Exam $exam, UserExam $question){
        return view("backend.pages.exam_results.showQuestion", compact("question", 'exam','user'));
    }

    public function acceptQuestion(User $user, Exam $exam, UserExam $question, Request $request){
        $question->is_admin_correct = $request->is_admin_correct;
        $question->save();
        return redirect()->back()->withSuccess("Uğurla status yeniləndi!");
    }
}