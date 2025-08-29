<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PartnersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\PartnerSaveRequest;

class PartnerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Partner::class;
    }

    public function index(PartnersDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.partners.index');
    }

    public function create()
    {
        return view('backend.pages.partners.create');
    }

    public function store(PartnerSaveRequest $request)
    {
        try {
            $item = new Partner();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "partners", 'partner_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.partners.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Partner $item)
    {
        return view('backend.pages.partners.edit', compact('item'));
    }

    public function update(PartnerSaveRequest $request, Partner $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "partners", 'partner_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.partners.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Partner $item)
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