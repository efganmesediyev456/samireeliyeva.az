<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ExamCategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Backend\ExamCategorySaveRequest;

class ExamCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = ExamCategory::class;
    }

    public function index(Subcategory $subcategories,ExamCategoriesDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.exam_categories.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.exam_categories.create', compact('subcategories'));
    }

    public function store(ExamCategorySaveRequest $request, Subcategory $subcategories)
    {
        try {
            $item = new ExamCategory();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['subcategory_id'] = $subcategories->id;

            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.exam-categories.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, ExamCategory $examCategory)
    {
        return view('backend.pages.exam_categories.edit', compact('examCategory','subcategories'));
    }

    public function update(ExamCategorySaveRequest $request,Subcategory $subcategories,  ExamCategory $examCategory)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');

            $examCategory = $this->mainService->save($examCategory, $data);
            $this->mainService->createTranslations($examCategory, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.exam-categories.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, ExamCategory $examCategory)
    {
        try {
            DB::beginTransaction();
            $examCategory->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}