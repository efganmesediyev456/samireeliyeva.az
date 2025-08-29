<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\TextbooksDataTable;
use App\Http\Controllers\Controller;
use App\Models\Textbook;
use App\Models\TextbookAttribute;
use App\Models\TextbookMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\TextbookSaveRequest;

class TextbookController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Textbook::class;
    }

    public function index(TextbooksDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.textbooks.index');
    }

    public function create()
    {
        return view('backend.pages.textbooks.create');
    }


    public function edit(Textbook $item)
    {
        return view('backend.pages.textbooks.edit', compact('item'));
    }



    // app/Http/Controllers/Backend/TextbookController.php

    public function store(TextbookSaveRequest $request)
    {
        try {
            $item = new Textbook();
            DB::beginTransaction();
            $data = $request->except('_token', '_method', 'attributes');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "textbooks", 'textbook_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);

            // Save attributes
            if ($request->has('attributes')) {
                foreach ($request->attributes as $langCode => $attributes) {
                    foreach ($attributes as $attribute) {
                        if (!empty($attribute['key']) && !empty($attribute['value'])) {
                            TextbookAttribute::create([
                                'textbook_id' => $item->id,
                                'key' => $attribute['key'],
                                'value' => $attribute['value'],
                                'language_code' => $langCode
                            ]);
                        }
                    }
                }
            }

            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "textbooks/media", 'media_' . uniqid());
                    TextbookMedia::create([
                        'file' => $mediaPath,
                        'status' => 1,
                        'order' => $index,
                        'textbook_id' => $item->id
                    ]);
                }
            }
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.textbooks.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function update(TextbookSaveRequest $request, Textbook $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method', 'attributes');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "textbooks", 'textbook_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            // Update attributes
            $item->attributes()->delete(); 

            if ($request->get('attributes')) {
                foreach ($request->get('attributes') as $langCode => $attributes) {
                    foreach ($attributes as $attribute) {
                        if (!empty($attribute['key']) && !empty($attribute['value'])) {
                            
                            TextbookAttribute::create([
                                'textbook_id' => $item->id,
                                'key' => $attribute['key'],
                                'value' => $attribute['value'],
                                'language_code' => $langCode
                            ]);
                        }
                    }
                }
            }

            if ($request->has('delete_media') && is_array($request->delete_media)) {
                TextbookMedia::whereIn('id', $request->delete_media)->delete();
            }

            if ($request->has('media_order') && is_array($request->media_order)) {
                foreach ($request->media_order as $mediaId => $order) {
                    TextbookMedia::where('id', $mediaId)->update(['order' => $order]);
                }
            }

            if ($request->hasFile('media_files')) {
                $lastOrder = TextbookMedia::where('textbook_id', $item->id)
                    ->max('order') ?? 0;

                foreach ($request->file('media_files') as $index => $mediaFile) {
                    $mediaPath = FileUploadHelper::uploadFile($mediaFile, "textbooks/media", 'media_' . uniqid());

                    TextbookMedia::create([
                        'file' => $mediaPath,
                        'status' => 1,
                        'order' => $lastOrder + $index + 1,
                        'textbook_id' => $item->id
                    ]);
                }
            }

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.textbooks.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Textbook $item)
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