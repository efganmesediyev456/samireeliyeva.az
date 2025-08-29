<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\AdvertisementsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\AdvertisementSaveRequest;

class AdvertisementController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Advertisement::class;
    }

    public function index(AdvertisementsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.advertisements.index');
    }

    public function create()
    {
        return view('backend.pages.advertisements.create');
    }

    public function store(AdvertisementSaveRequest $request)
    {
        try {
            $item = new Advertisement();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "advertisements", 'advertisement_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.advertisements.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Advertisement $item)
    {
        return view('backend.pages.advertisements.edit', compact('item'));
    }

    public function update(AdvertisementSaveRequest $request, Advertisement $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "advertisements", 'advertisement_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.advertisements.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Advertisement $item)
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