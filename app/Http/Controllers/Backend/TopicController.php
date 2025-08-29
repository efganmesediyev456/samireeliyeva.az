<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\TopicsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\TopicSaveRequest;

class TopicController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Topic::class;
    }

    public function index(Subcategory $subcategories, TopicsDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.topics.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.topics.create', compact('subcategories'));
    }

    public function store(TopicSaveRequest $request, Subcategory $subcategories)
    {
        try {
            $item = new Topic();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['subcategory_id'] = $subcategories->id;
            
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "topics", 'topic_icon_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.topics.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, Topic $item)
    {
        return view('backend.pages.topics.edit', compact('subcategories', 'item'));
    }

    public function update(TopicSaveRequest $request, Subcategory $subcategories, Topic $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "topics", 'topic_icon_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.topics.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, Topic $item)
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