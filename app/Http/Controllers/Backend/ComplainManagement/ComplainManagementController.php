<?php

namespace App\Http\Controllers\Backend\ComplainManagement;

use App\DataTables\LanguagesDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;
use App\Models\ComplaintManagement;

class ComplainManagementController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = ComplaintManagement::class;
    }

    public function index()
    {
        $item = ComplaintManagement::first();
        return view('backend.pages.complainmanagement.index', compact('item'));
    }

    public function update(Request $request,ComplaintManagement $item){
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "complainmanagement", 'complainmanagement');
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.complainmanagement.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }
}
