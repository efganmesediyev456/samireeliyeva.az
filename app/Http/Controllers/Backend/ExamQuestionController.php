<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ExamQuestionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Backend\ExamQuestionSaveRequest;

class ExamQuestionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = ExamQuestion::class;
    }

    public function index(Subcategory $subcategories, Exam $exam, ExamQuestionsDataTable $dataTable)
    {
        return $dataTable->with(['subcategory' => $subcategories, 'exam' => $exam])
                         ->render('backend.pages.exam-questions.index', 
                                 compact('subcategories', 'exam'));
    }

    public function create(Subcategory $subcategories, Exam $exam)
    {
        return view('backend.pages.exam-questions.create', compact('subcategories', 'exam'));
    }

    public function store(ExamQuestionSaveRequest $request, Subcategory $subcategories, Exam $exam)
    {
        try {
            DB::beginTransaction();
            
            $questionData = $request->except('_token', '_method', 'options');
            $questionData['exam_id'] = $exam->id;
            
            $question = new ExamQuestion();
            $question = $this->mainService->save($question, $questionData);
            $this->mainService->createTranslations($question, $request);
            
            if (isset($request->options) && is_array($request->options)) {
                foreach ($request->options as $index => $optionData) {
                    $option = new ExamQuestionOption();
                    $option->question_id = $question->id;
                    $option->is_correct = isset($optionData['is_correct']) ? true : false;
                    // $option->position = $index + 1;
                    $option->save();
                    
                    foreach ($optionData['texts'] as $locale => $text) {
                        $option->translations()->create([
                            'locale' => $locale,
                            'option_text' => $text
                        ]);
                    }
                }
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, 
                route('admin.subcategories.exams.questions.index', [$subcategories->id, $exam->id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, Exam $exam, ExamQuestion $question)
    {
        $question->load('options.translations');
        return view('backend.pages.exam-questions.edit', 
                   compact('subcategories', 'exam', 'question'));
    }

    public function update(ExamQuestionSaveRequest $request, Subcategory $subcategories, Exam $exam, ExamQuestion $question)
    {
        try {
            DB::beginTransaction();
            
            $questionData = $request->except('_token', '_method', 'options');
            $question = $this->mainService->save($question, $questionData);
            $this->mainService->createTranslations($question, $request);
            
            $question->options()->delete();
            
            if (isset($request->options) && is_array($request->options)) {
                foreach ($request->options as $index => $optionData) {
                    $option = new ExamQuestionOption();
                    $option->question_id = $question->id;
                    $option->is_correct = isset($optionData['is_correct']) ? true : false;
                    // $option->position = $index + 1;
                    $option->save();
                    
                    foreach ($optionData['texts'] as $locale => $text) {
                        $option->translations()->create([
                            'locale' => $locale,
                            'option_text' => $text
                        ]);
                    }
                }
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, 
                route('admin.subcategories.exams.questions.index', [$subcategories->id, $exam->id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, Exam $exam, ExamQuestion $question)
    {
        try {
            DB::beginTransaction();
            $question->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}