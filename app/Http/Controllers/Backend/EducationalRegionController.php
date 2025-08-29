<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\EducationalRegionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\EducationalRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\EducationalRegionSaveRequest;

class EducationalRegionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = EducationalRegion::class;
    }

    public function index(EducationalRegionsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.educational_regions.index');
    }

    public function create()
    {
        return view('backend.pages.educational_regions.create');
    }

    public function store(EducationalRegionSaveRequest $request)
    {
        try {
            $item = new EducationalRegion();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "educational_regions", 'region_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.educational_regions.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(EducationalRegion $item)
    {
        return view('backend.pages.educational_regions.edit', compact('item'));
    }

    public function update(EducationalRegionSaveRequest $request, EducationalRegion $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "educational_regions", 'region_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.educational_regions.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(EducationalRegion $item)
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