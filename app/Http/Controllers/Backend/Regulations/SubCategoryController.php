<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\Subcategoriesdatatable;
use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\SubcategorySaveRequest;

class SubcategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Subcategory::class;
    }

    public function index(Subcategoriesdatatable $dataTable)
    {
        return $dataTable->render('backend.pages.subcategories.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('backend.pages.subcategories.create', compact('categories'));
    }

    public function store(SubcategorySaveRequest $request)
    {
        try {
            $item = new Subcategory();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "subcategories", 'subcategory_' . uniqid());
            }
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "subcategories", 'subcategory_icon_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $item)
    {
        $categories = Category::all();
        return view('backend.pages.subcategories.edit', compact('item', 'categories'));
    }

    public function update(SubcategorySaveRequest $request, Subcategory $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "subcategories", 'subcategory_' . uniqid());
            }
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "subcategories", 'subcategory_icon_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Subcategory $item)
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