<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PastExamQuestionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\PastExamQuestion;
use App\Models\PastExamQuestionItem;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\PastExamQuestionSaveRequest;

class PastExamQuestionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = PastExamQuestion::class;
    }

    public function index(Subcategory $subcategories, PastExamQuestionsDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.past-exam-questions.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.past-exam-questions.create', compact('subcategories'));
    }

    public function store(Request $request, Subcategory $subcategories)
    {
        try {
            $item = new PastExamQuestion();
            DB::beginTransaction();
            
            $data = $request->except('_token', '_method', 'exam_files', 'file_titles');
            $data['subcategory_id'] = $subcategories->id;
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $fileUpload) {
                    $path = FileUploadHelper::uploadFile($fileUpload, "past-exam-questions", 'past_exam_' . uniqid());
                    
                    $fileItem = new PastExamQuestionItem();
                    $fileItem->past_exam_question_id = $item->id;
                    $fileItem->file = $path;
                    $fileItem->save();
                    
                    // Save translations for each file
                    if (isset($request->file_titles[$index])) {
                        foreach ($request->file_titles[$index] as $locale => $title) {
                            if (!empty($title)) {
                                $fileItem->translations()->create([
                                    'locale' => $locale,
                                    'title' => $title
                                ]);
                            }
                        }
                    }
                }
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.past-exam-questions.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, PastExamQuestion $item)
    {
        return view('backend.pages.past-exam-questions.edit', compact('subcategories', 'item'));
    }

    public function update(Request $request, Subcategory $subcategories, PastExamQuestion $item)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->except('_token', '_method', 'exam_files', 'file_titles', 'existing_files', 'existing_file_titles', 'update_files', 'delete_files');
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);


            
            // Handle existing files updates
            if ($request->existing_files) {
                foreach ($request->existing_files as $fileId) {
                    $fileItem = PastExamQuestionItem::find($fileId);
                    
                    if ($fileItem) {
                        // Update file if a new one is provided
                        if ($request->hasFile('update_files.' . $fileId)) {
                            $path = FileUploadHelper::uploadFile($request->file('update_files.' . $fileId), "past-exam-questions", 'exam_file_' . uniqid());
                            $fileItem->file = $path;
                            $fileItem->save();
                        }
                        
                        // Update translations
                        if (isset($request->existing_file_titles[$fileId])) {
                            foreach ($request->existing_file_titles[$fileId] as $locale => $title) {
                                if (!empty($title)) {
                                    $translation = $fileItem->translations()->where('locale', $locale)->first();
                                    
                                    if ($translation) {
                                        $translation->title = $title;
                                        $translation->save();
                                    } else {
                                        $fileItem->translations()->create([
                                            'locale' => $locale,
                                            'title' => $title
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // Delete files if requested
            if ($request->delete_files) {
                $fileIdsToDelete = explode(',', $request->delete_files);
                foreach ($fileIdsToDelete as $fileId) {
                    if (!empty($fileId)) {
                        $fileItem = PastExamQuestionItem::find($fileId);
                        if ($fileItem) {
                            $fileItem->delete();
                        }
                    }
                }
            }
            
            // Add new files
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $fileUpload) {
                    $path = FileUploadHelper::uploadFile($fileUpload, "past-exam-questions", 'exam_file_' . uniqid());
                    
                    $fileItem = new PastExamQuestionItem();
                    $fileItem->past_exam_question_id = $item->id;
                    $fileItem->file = $path;
                    $fileItem->save();
                    
                    // Save translations for each file
                    if (isset($request->file_titles[$index])) {
                        foreach ($request->file_titles[$index] as $locale => $title) {
                            if (!empty($title)) {
                                $fileItem->translations()->create([
                                    'locale' => $locale,
                                    'title' => $title
                                ]);
                            }
                        }
                    }
                }
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.past-exam-questions.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, PastExamQuestion $item)
    {
        try {
            DB::beginTransaction();
            // Delete all associated files first
            foreach ($item->items as $fileItem) {
                $fileItem->delete();
            }
            // Then delete the main item
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}