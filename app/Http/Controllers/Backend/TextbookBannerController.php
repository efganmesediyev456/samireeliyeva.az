<?php
namespace App\Http\Controllers\Backend;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\TextbookBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TextbookBannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = TextbookBanner::class;
    }

    public function index()
    {
        $item = TextbookBanner::first();
        return view('backend.pages.textbook_banner.index', compact('item'));
    }

    public function update(Request $request, TextbookBanner $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');

            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "textbook_banner", 'textbook_banner_' . uniqid());
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.textbook_banner.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}