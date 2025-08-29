<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\TopicFilesDataTable;
use App\Http\Controllers\Controller;
use App\Models\TopicFile;
use App\Models\TopicCategory;
use App\Models\Topic;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileUploadHelper;

class TopicFileController extends Controller
{
    public function index(Subcategory $subcategories, Topic $topic, TopicCategory $category, TopicFilesDataTable $dataTable)
    {
        return $dataTable->with('category', $category)->render('backend.pages.topic_files.index', compact('subcategories', 'topic', 'category'));
    }

    public function create(Subcategory $subcategories, Topic $topic, TopicCategory $category)
    {
        return view('backend.pages.topic_files.create', compact('subcategories', 'topic', 'category'));
    }

   
    public function store(Request $request, Subcategory $subcategories, Topic $topic, TopicCategory $category)
{
    try {
        DB::beginTransaction();
        
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:102400', // 100MB max file size
            'file_titles' => 'required|array',
            'default_locale' => 'required|string',
        ]);

        
        if ($request->hasFile('files')) {
            for ($i = 0; $i < count($request->file('files')); $i++) {


                $file = $request->file('files')[$i];
                
                $filePath = FileUploadHelper::uploadFile(
                    $file, 
                    "topic_files", 
                    'topic_file_' . uniqid()
                );
                
                $topicFile = TopicFile::create([
                    'topic_category_id' => $category->id,
                    'file_path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                
                foreach ($request->file_titles as $locale => $titles) {
                    if (isset($titles[$i]) && !empty($titles[$i])) {
                        $topicFile->translations()->create([
                            'locale' => $locale,
                            'title' => $titles[$i],
                        ]);
                    }
                }
            }
        }


        
        DB::commit();
        return $this->responseMessage('success', 'Fayllar uğurla yükləndi', [], 200, route('admin.subcategories.topics.categories.files.index', [$subcategories->id, $topic->id, $category->id]));
    } catch (\Exception $exception) {
        DB::rollBack();
        return $this->responseMessage('error', $exception->getMessage(), [], 500);
    }
}

    public function destroy(Subcategory $subcategories, Topic $topic, TopicCategory $category, TopicFile $file)
    {
        try {
            DB::beginTransaction();
            
            // Delete the file from storage
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            $file->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Fayl uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}