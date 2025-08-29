<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VideosDataTable;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\VideoSaveRequest;

class VideoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Video::class;
    }

    public function index(Subcategory $subcategories, VideosDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.videos.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.videos.create', compact('subcategories'));
    }

    public function store(VideoSaveRequest $request, Subcategory $subcategories)
    {
        try {
            $item = new Video();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['subcategory_id'] = $subcategories->id;
            
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = FileUploadHelper::uploadFile($request->file('thumbnail'), "videos", 'video_thumbnail_' . uniqid());
            }
            if($request->video_type == 'local'){
                if ($request->hasFile('video_url')) {
                    $data['video_url'] = FileUploadHelper::uploadFile($request->file('video_url'), "video_uploads/videos", 'video_' . uniqid());
                }
            }

            unset($data['video_type']);
            

            $item = $this->mainService->save($item, $data);

            
          

            $this->mainService->createTranslations($item, $request);
          

            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.videos.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, Video $item)
    {
        return view('backend.pages.videos.edit', compact('subcategories', 'item'));
    }

    public function update(VideoSaveRequest $request, Subcategory $subcategories, Video $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = FileUploadHelper::uploadFile($request->file('thumbnail'), "videos", 'video_thumbnail_' . uniqid());
            }

            if(!$request->video_url){
                unset($data['video_url']);
            }
           
            if($request->video_type == 'local'){
                if ($request->hasFile('video_url')) {
                    $data['video_url'] = FileUploadHelper::uploadFile($request->file('video_url'), "video_uploads/videos", 'video_' . uniqid());
                }
            }

            unset($data['video_type']);
            $item = $this->mainService->save($item, $data);

           
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.videos.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, Video $item)
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