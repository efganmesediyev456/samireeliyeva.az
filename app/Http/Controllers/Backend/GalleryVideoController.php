<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GalleryVideosDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\GalleryVideoSaveRequest;
use App\Models\GalleryVideo;
use App\Models\GalleryVideoMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class GalleryVideoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = GalleryVideo::class;
    }

    public function index(GalleryVideosDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.gallery-videos.index');
    }

    public function create()
    {
        return view('backend.pages.gallery-videos.create');
    }

    public function store(GalleryVideoSaveRequest $request)
    {
        try {
            $item = new GalleryVideo();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "gallery-videos", 'gallery_video_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "gallery-videos/media", 'media_' . uniqid());
                    GalleryVideoMedia::create([
                        'file' => $mediaPath,
                        'status' => 1, 
                        'order' => $index,
                        'gallery_video_id' => $item->id
                    ]);
                }
            }
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.gallery-videos.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(GalleryVideo $item)
    {
        return view('backend.pages.gallery-videos.edit', compact('item'));
    }

    public function update(GalleryVideoSaveRequest $request, GalleryVideo $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "gallery-videos", 'gallery_video_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            if ($request->has('delete_media') && is_array($request->delete_media)) {
                GalleryVideoMedia::whereIn('id', $request->delete_media)->delete();
            }
            
            if ($request->has('media_order') && is_array($request->media_order)) {
                foreach ($request->media_order as $mediaId => $order) {
                    GalleryVideoMedia::where('id', $mediaId)->update(['order' => $order]);
                }
            }
            
            if ($request->hasFile('media_files')) {
                $lastOrder = GalleryVideoMedia::where('gallery_video_id', $item->id)
                                ->max('order') ?? 0;
                
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "gallery-videos/media", 'media_' . uniqid());
                    
                    GalleryVideoMedia::create([
                        'file' => $mediaPath,
                        'status' => 1,
                        'order' => $lastOrder + $index + 1,
                        'gallery_video_id' => $item->id
                    ]);
                }
            }

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.gallery-videos.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(GalleryVideo $item)
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