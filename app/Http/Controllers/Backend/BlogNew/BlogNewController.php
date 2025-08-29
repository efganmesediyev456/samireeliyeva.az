<?php

namespace App\Http\Controllers\Backend\BlogNew;

use App\DataTables\BlogNewsDataTable;
use App\DataTables\CertificatesDataTable;
use App\DataTables\LanguagesDataTable;
use App\DataTables\ProductsDataTable;
use App\DataTables\TeamsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\BlogNewMedia;
use App\Models\Certificate;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\BlogNew\BlogNewSaveRequest;
use App\Http\Requests\Backend\Products\ProductSaveRequest;
use App\Models\BlogNew;
use App\Models\Category;
use App\Models\Product;
use App\Models\Team;

class BlogNewController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = BlogNew::class;
    }

    public function index(BlogNewsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.blognews.index');
    }

    public function create()
    {
        return view('backend.pages.blognews.create');
    }

    public function store(BlogNewSaveRequest $request)
    {
        try {
            $item = new BlogNew();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "blognews", 'blognews_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "blognews/media", 'media_' . uniqid());
                    BlogNewMedia::create([
                        'file' => $mediaPath,
                        'status' => 1, 
                        'order' => $index,
                        'blog_new_id' => $item->id
                    ]);
                }
            }
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.blognews.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function edit(BlogNew $item)
    {
        return view('backend.pages.blognews.edit', compact('item'));
    }

    public function update(BlogNewSaveRequest $request, BlogNew $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "blognews", 'blognews_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);


            if ($request->has('delete_media') && is_array($request->delete_media)) {
                BlogNewMedia::whereIn('id', $request->delete_media)->delete();
            }
            
            if ($request->has('media_order') && is_array($request->media_order)) {
                foreach ($request->media_order as $mediaId => $order) {
                    BlogNewMedia::where('id', $mediaId)->update(['order' => $order]);
                }
            }
            
            if ($request->hasFile('media_files')) {
                $lastOrder = BlogNewMedia::where('blog_new_id', $item->id)
                                ->max('order') ?? 0;
                
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "blognews/media", 'media_' . uniqid());
                    
                    BlogNewMedia::create([
                        'file' => $mediaPath,
                        'status' => 1, // Default status active
                        'order' => $lastOrder + $index + 1, // Continue ordering after existing files
                        'blog_new_id' => $item->id
                    ]);
                }
            }

            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.blognews.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function delete(BlogNew $item)
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
