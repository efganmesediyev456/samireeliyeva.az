<?php
namespace App\Http\Controllers\Backend;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\ExamBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamBannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = ExamBanner::class;
    }

    public function index()
    {
        $item = ExamBanner::first();
        if(is_null($item)){
            $item = ExamBanner::create([]);
        }
        return view('backend.pages.exam_banner.index', compact('item'));
    }

    public function update(Request $request, ExamBanner $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');

            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "exam_banner", 'exam_banner_' . uniqid());
            }
            
            if ($request->hasFile('exam_online_image')) {
                $data['exam_online_image'] = FileUploadHelper::uploadFile($request->file('exam_online_image'), "exam_banner", 'exam_banner_' . uniqid());
            }
            

            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.exam_banner.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}