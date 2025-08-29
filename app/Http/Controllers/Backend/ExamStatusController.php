<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ExamCategoriesDataTable;
use App\DataTables\ExamStatusesDataTable;
use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\ExamStatus;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Backend\ExamCategorySaveRequest;

class ExamStatusController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = ExamStatus::class;
    }

    public function index(Subcategory $subcategories,ExamStatusesDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.exam_statuses.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.exam_statuses.create', compact('subcategories'));
    }

    public function store(ExamCategorySaveRequest $request, Subcategory $subcategories)
    {
        try {
            $item = new ExamStatus();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['subcategory_id'] = $subcategories->id;

            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.exam-statuses.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, ExamStatus $examStatus)
    {
        return view('backend.pages.exam_statuses.edit', compact('examStatus','subcategories'));
    }

    public function update(ExamCategorySaveRequest $request,Subcategory $subcategories,  ExamStatus $examStatus)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');

            $examStatus = $this->mainService->save($examStatus, $data);
            $this->mainService->createTranslations($examStatus, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.exam-statuses.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, ExamStatus $examStatus)
    {
        try {
            DB::beginTransaction();
            $examStatus->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}