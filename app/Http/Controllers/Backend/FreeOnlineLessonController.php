<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\FreeOnlineLessonsDataTable;
use App\Http\Controllers\Controller;
use App\Models\FreeOnlineLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\FreeOnlineLessonSaveRequest;

class FreeOnlineLessonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = FreeOnlineLesson::class;
    }

    public function index(FreeOnlineLessonsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.free_online_lessons.index');
    }

    public function create()
    {
        return view('backend.pages.free_online_lessons.create');
    }

    public function store(FreeOnlineLessonSaveRequest $request)
    {
        try {
            $item = new FreeOnlineLesson();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "free_online_lessons", 'lesson_icon_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.free_online_lessons.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(FreeOnlineLesson $item)
    {
        return view('backend.pages.free_online_lessons.edit', compact('item'));
    }

    public function update(FreeOnlineLessonSaveRequest $request, FreeOnlineLesson $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "free_online_lessons", 'lesson_icon_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.free_online_lessons.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(FreeOnlineLesson $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}