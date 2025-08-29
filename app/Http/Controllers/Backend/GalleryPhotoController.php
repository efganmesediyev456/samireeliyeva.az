<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\GalleryPhotosDataTable;
use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\GalleryPhotoMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\GalleryPhotoSaveRequest;

class GalleryPhotoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = GalleryPhoto::class;
    }

    public function index(GalleryPhotosDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.gallery-photos.index');
    }

    public function create()
    {
        return view('backend.pages.gallery-photos.create');
    }

    public function store(GalleryPhotoSaveRequest $request)
    {
        try {
            $item = new GalleryPhoto();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "gallery-photos", 'gallery_photo_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "gallery-photos/media", 'media_' . uniqid());
                    GalleryPhotoMedia::create([
                        'file' => $mediaPath,
                        'status' => 1, 
                        'order' => $index,
                        'gallery_photo_id' => $item->id
                    ]);
                }
            }
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.galleryphotos.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(GalleryPhoto $item)
    {
        return view('backend.pages.gallery-photos.edit', compact('item'));
    }

    public function update(GalleryPhotoSaveRequest $request, GalleryPhoto $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "gallery-photos", 'gallery_photo_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            if ($request->has('delete_media') && is_array($request->delete_media)) {
                GalleryPhotoMedia::whereIn('id', $request->delete_media)->delete();
            }
            
            if ($request->has('media_order') && is_array($request->media_order)) {
                foreach ($request->media_order as $mediaId => $order) {
                    GalleryPhotoMedia::where('id', $mediaId)->update(['order' => $order]);
                }
            }
            
            if ($request->hasFile('media_files')) {
                $lastOrder = GalleryPhotoMedia::where('gallery_photo_id', $item->id)
                                ->max('order') ?? 0;
                
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "gallery-photos/media", 'media_' . uniqid());
                    
                    GalleryPhotoMedia::create([
                        'file' => $mediaPath,
                        'status' => 1,
                        'order' => $lastOrder + $index + 1,
                        'gallery_photo_id' => $item->id
                    ]);
                }
            }

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.galleryphotos.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(GalleryPhoto $item)
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