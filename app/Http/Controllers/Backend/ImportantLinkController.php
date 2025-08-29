<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ImportantLinksDataTable;
use App\Http\Controllers\Controller;
use App\Models\ImportantLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\ImportantLinkSaveRequest;

class ImportantLinkController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = ImportantLink::class;
    }

    public function index(ImportantLinksDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.important_links.index');
    }

    public function create()
    {
        return view('backend.pages.important_links.create');
    }

    public function store(ImportantLinkSaveRequest $request)
    {
        try {
            $item = new ImportantLink();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "important_links", 'link_image_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.important_links.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(ImportantLink $item)
    {
        return view('backend.pages.important_links.edit', compact('item'));
    }

    public function update(ImportantLinkSaveRequest $request, ImportantLink $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "important_links", 'link_image_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.important_links.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(ImportantLink $item)
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