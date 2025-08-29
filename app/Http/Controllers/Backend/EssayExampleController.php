<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\EssayExamplesDataTable;
use App\Http\Controllers\Controller;
use App\Models\EssayExample;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\EssayExampleSaveRequest;
use App\Models\EssayExampleFile;
use Illuminate\Support\Facades\Storage;

class EssayExampleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = EssayExample::class;
    }

    public function index(Subcategory $subcategories, EssayExamplesDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.essay-examples.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.essay-examples.create', compact('subcategories'));
    }

    public function store(EssayExampleSaveRequest $request, Subcategory $subcategories)
    {
        try {
            $item = new EssayExample();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['subcategory_id'] = $subcategories->id;
            unset($data['default_locale']);


            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = FileUploadHelper::uploadFile($request->file('thumbnail'), "essay-examples", 'video_thumbnail_' . uniqid());
            }

            
            if($request->video_type == 'local'){
                if ($request->hasFile('video_url')) {
                    $data['video_url'] = FileUploadHelper::uploadFile($request->file('video_url'), "video_uploads/videos", 'video_' . uniqid());
                }
            }

            unset($data['video_type']);



            $item = $this->mainService->save($item, $data);
            if ($request->file('files')) {
                foreach ($request->file('files') as $i => $file_url) {
                    $path = FileUploadHelper::uploadFile($file_url, "interview-preparations", 'file_' . uniqid());
                    $file = $item->files()->create([
                        'file_url' => $path
                    ]);

                    foreach ($request->file_titles as $locale => $titles) {
                        if (isset($titles[$i]) && !empty($titles[$i])) {
                            $file->translations()->create([
                                'locale' => $locale,
                                'title' => $titles[$i]
                            ]);
                        }
                    }
                }
            }
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.essay-examples.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, EssayExample $item)
    {
        $files = $item->files;
        return view('backend.pages.essay-examples.edit', compact('subcategories', 'item', 'files'));
    }

    public function update(EssayExampleSaveRequest $request, Subcategory $subcategories, EssayExample $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = FileUploadHelper::uploadFile($request->file('thumbnail'), "essay-examples", 'video_thumbnail_' . uniqid());
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

            


             // 1. Handle updates to existing files
            if (isset($request->file_ids) && count($request->file_ids) > 0) {
                foreach ($request->file_ids as $index => $fileId) {
                    $interviewFile = EssayExampleFile::findOrFail($fileId);
                    
                    // Update file if new one is uploaded
                    if ($request->hasFile('update_files') && isset($request->update_files[$index]) && $request->update_files[$index]) {
                        // Delete old file
                        if (Storage::exists('public/' . $interviewFile->file_url)) {
                            Storage::delete('public/' . $interviewFile->file_url);
                        }
                        
                        // Upload new file
                        $file = $request->file('update_files')[$index];
                        $filePath = FileUploadHelper::uploadFile(
                            $file, 
                            "interview-files", 
                            'file_' . uniqid()
                        );
                        
                        // Update file record
                        $interviewFile->file_url = $filePath;
                        $interviewFile->save();
                    }
                    
                    // Update translations
                    if (isset($request->file_titles)) {
                        foreach ($request->file_titles as $locale => $titles) {
                            if (isset($titles[$index])) {
                                $translation = $interviewFile->translations()
                                    ->where('locale', $locale)
                                    ->first();
                                    
                                if ($translation) {
                                    $translation->title = $titles[$index];
                                    $translation->save();
                                } else {
                                    $interviewFile->translations()->create([
                                        'locale' => $locale,
                                        'title' => $titles[$index],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            
            // 2. Add new files
            if ($request->hasFile('new_files')) {
                foreach ($request->file('new_files') as $index => $file) {
                    // Upload the file
                    $filePath = FileUploadHelper::uploadFile(
                        $file, 
                        "interview-files", 
                        'file_' . uniqid()
                    );
                    
                    // Create file record
                    $interviewFile = new EssayExampleFile();
                    $interviewFile->essay_example_id  = $item->id;
                    $interviewFile->file_url = $filePath;
                    $interviewFile->save();
                    
                    // Save translations for each file
                    if (isset($request->new_file_titles)) {
                        foreach ($request->new_file_titles as $locale => $titles) {
                            if (isset($titles[$index]) && !empty($titles[$index])) {
                                $interviewFile->translations()->create([
                                    'locale' => $locale,
                                    'title' => $titles[$index]
                                ]);
                            }
                        }
                    }
                }
            }
            
            // 3. Delete files marked for deletion
            if (isset($request->deleted_files) && count($request->deleted_files) > 0) {
                foreach ($request->deleted_files as $fileId) {
                    $fileToDelete = EssayExampleFile::find($fileId);
                    
                    if ($fileToDelete) {
                        // Delete file from storage
                        if (Storage::exists('public/' . $fileToDelete->file_url)) {
                            Storage::delete('public/' . $fileToDelete->file_url);
                        }
                        
                        // Delete translations
                        $fileToDelete->translations()->delete();
                        
                        // Delete file record
                        $fileToDelete->delete();
                    }
                }
            }
        



            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.subcategories.essay-examples.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, EssayExample $item)
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
