<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\TopicCategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Models\TopicCategory;
use App\Models\Topic;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Backend\TopicCategorySaveRequest;

class TopicCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = TopicCategory::class;
    }

    public function index(Subcategory $subcategories, Topic $topic, TopicCategoriesDataTable $dataTable)
    {
        return $dataTable->with('topic', $topic)->render('backend.pages.topic_categories.index', compact('subcategories', 'topic'));
    }

    public function create(Subcategory $subcategories, Topic $topic)
    {
        return view('backend.pages.topic_categories.create', compact('subcategories', 'topic'));
    }

    public function store(TopicCategorySaveRequest $request, Subcategory $subcategories, Topic $topic)
    {
    
        try {
            $item = new TopicCategory();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['topic_id'] = $topic->id;
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.topics.categories.index', [$subcategories->id, $topic->id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, Topic $topic, TopicCategory $category)
    {
        return view('backend.pages.topic_categories.edit', compact('subcategories', 'topic', 'category'));
    }

    public function update(TopicCategorySaveRequest $request, Subcategory $subcategories, Topic $topic, TopicCategory $category)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            $category = $this->mainService->save($category, $data);
            $this->mainService->createTranslations($category, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.topics.categories.index', [$subcategories->id, $topic->id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, Topic $topic, TopicCategory $category)
    {
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}