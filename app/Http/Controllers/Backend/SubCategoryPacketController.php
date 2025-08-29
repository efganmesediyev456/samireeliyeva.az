<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubCategoryPacketsDataTable;
use App\Http\Controllers\Controller;
use App\Models\SubCategoryPacket;
use App\Models\SubCategoryPacketItem;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class SubCategoryPacketController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = SubCategoryPacket::class;
    }

    public function index(Subcategory $subcategories, SubCategoryPacketsDataTable $dataTable)
    {
        return $dataTable->with('subcategory', $subcategories)->render('backend.pages.subcategory-packets.index', compact('subcategories'));
    }

    public function create(Subcategory $subcategories)
    {
        return view('backend.pages.subcategory-packets.create', compact('subcategories'));
    }

    public function store(Request $request, Subcategory $subcategories)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'title' => 'required|array',
                'duration_months' => 'required|integer|min:1',
                'item_titles' => 'required|array',
                'item_subtitles' => 'array',
                'item_prices' => 'required|array',
                'item_discount_prices' => 'array',
                'item_icons' => 'array',
            ]);
            
            // Create new packet
            $packet = new SubCategoryPacket();
            $data = $request->only(['duration_months', 'active']);
            $data['subcategory_id'] = $subcategories->id;
            
            $packet = $this->mainService->save($packet, $data);
            $this->mainService->createTranslations($packet, $request);
            
            // Create packet items
            if (isset($request->item_titles) && count($request->item_titles) > 0) {
                for ($i = 0; $i < count($request->item_titles[$request->default_locale]); $i++) {
                    $item = new SubCategoryPacketItem();
                    $item->packet_id = $packet->id;
                    $item->price = $request->item_prices[$i] ?? 0;
                    $item->discount_price = $request->item_discount_prices[$i] ?? null;
                    
                    // Upload icon if provided
                    if ($request->hasFile('item_icons') && isset($request->item_icons[$i]) && $request->item_icons[$i]) {
                        $item->icon = FileUploadHelper::uploadFile(
                            $request->item_icons[$i], 
                            "packet-icons", 
                            'icon_' . uniqid()
                        );
                    }
                    
                    $item->save();
                    
                    // Save translations for each item
                    foreach ($request->item_titles as $locale => $titles) {
                        if (isset($titles[$i]) && !empty($titles[$i])) {
                            $subtitle = isset($request->item_subtitles[$locale][$i]) ? $request->item_subtitles[$locale][$i] : null;
                            $chooseElement = isset($request->item_chooseElement[$locale][$i]) ? $request->item_chooseElement[$locale][$i] : null;
                            $unChooseElement = isset($request->item_unChooseElement[$locale][$i]) ? $request->item_unChooseElement[$locale][$i] : null;
                            
                            $item->translations()->create([
                                'locale' => $locale,
                                'title' => $titles[$i],
                                'subtitle' => $subtitle,
                                'chooseElement' => $chooseElement,
                                'unChooseElement' => $unChooseElement,
                            ]);
                        }
                    }
                }
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.subcategories.packets.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Subcategory $subcategories, SubCategoryPacket $packet)
    {
        return view('backend.pages.subcategory-packets.edit', compact('subcategories', 'packet'));
    }

    public function update(Request $request, Subcategory $subcategories, SubCategoryPacket $packet)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            
            // Validate request
            $request->validate([
                'title' => 'required|array',
                'duration_months' => 'required|integer|min:1',
                'existing_item_titles' => 'array',
                'existing_item_subtitles' => 'array',
                'existing_item_prices' => 'array',
                'existing_item_discount_prices' => 'array',
                'item_titles' => 'array',
                'item_subtitles' => 'array',
                'item_prices' => 'array',
                'item_discount_prices' => 'array',
                'item_icons' => 'array',
            ]);
            
            // Update packet
            $data = $request->only(['duration_months']);
            
            $packet = $this->mainService->save($packet, $data);
            $this->mainService->createTranslations($packet, $request);
            
            // Update existing items
            if ($request->existing_items) {
                foreach ($request->existing_items as $itemId) {
                    $item = SubCategoryPacketItem::find($itemId);
                    
                    if ($item) {
                        $item->price = $request->existing_item_prices[$itemId] ?? $item->price;
                        $item->discount_price = $request->existing_item_discount_prices[$itemId] ?? $item->discount_price;
                        
                        // Update icon if provided
                        if ($request->hasFile('update_item_icons.' . $itemId)) {
                            $item->icon = FileUploadHelper::uploadFile(
                                $request->file('update_item_icons.' . $itemId), 
                                "packet-icons", 
                                'icon_' . uniqid()
                            );
                        }
                        
                        $item->save();
                        
                        // Update translations
                        if (isset($request->existing_item_titles[$itemId])) {
                            foreach ($request->existing_item_titles[$itemId] as $locale => $title) {
                                if (!empty($title)) {
                                    $subtitle = isset($request->existing_item_subtitles[$itemId][$locale]) 
                                        ? $request->existing_item_subtitles[$itemId][$locale] 
                                        : null;

                                    $chooseElement = isset($request->existing_item_choose_elements[$itemId][$locale]) 
                                        ? $request->existing_item_choose_elements[$itemId][$locale] 
                                        : null;


                                    $unChooseElement = isset($request->existing_item_unchoose_elements[$itemId][$locale]) 
                                        ? $request->existing_item_unchoose_elements[$itemId][$locale] 
                                        : null;
                                    
                                    $translation = $item->translations()->where('locale', $locale)->first();
                                    
                                    if ($translation) {
                                       
                                        $translation->title = $title;
                                        $translation->subtitle = $subtitle;
                                        $translation->chooseElement = $chooseElement;
                                        $translation->unChooseElement = $unChooseElement;
                                        $translation->save();
                                        
                                    } else {
                                        $item->translations()->create([
                                            'locale' => $locale,
                                            'title' => $title,
                                            'subtitle' => $subtitle,
                                            'chooseElement' => $chooseElement,
                                            'unChooseElement' => $unChooseElement,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            // Delete items if requested
            if ($request->delete_items) {
                $itemIdsToDelete = explode(',', $request->delete_items);
                foreach ($itemIdsToDelete as $itemId) {
                    if (!empty($itemId)) {
                        $item = SubCategoryPacketItem::find($itemId);
                        if ($item) {
                            $item->delete();
                        }
                    }
                }
            }
            
            // Add new items
            if (isset($request->item_titles) && isset($request->item_titles[$request->default_locale]) && count($request->item_titles[$request->default_locale]) > 0) {
               
                for ($i = 0; $i < count($request->item_titles[$request->default_locale]); $i++) {
                    if (!empty($request->item_titles[$request->default_locale][$i])) {
                        $item = new SubCategoryPacketItem();
                        $item->packet_id = $packet->id;
                        $item->price = $request->item_prices[$i] ?? 0;
                        $item->discount_price = $request->item_discount_prices[$i] ?? null;
                        
                        // Upload icon if provided
                        if ($request->hasFile('item_icons') && isset($request->item_icons[$i]) && $request->item_icons[$i]) {
                            $item->icon = FileUploadHelper::uploadFile(
                                $request->item_icons[$i], 
                                "packet-icons", 
                                'icon_' . uniqid()
                            );
                        }
                        
                        $item->save();
                        // Save translations for each item
                        foreach ($request->item_titles as $locale => $titles) {
                            if (isset($titles[$i]) && !empty($titles[$i])) {
                                $subtitle = isset($request->item_subtitles[$locale][$i]) ? $request->item_subtitles[$locale][$i] : null;
                                $chooseElements = isset($request->item_choose_elements[$locale][$i]) ? $request->item_choose_elements[$locale][$i] : null;
                                $unChooseElements = isset($request->item_unchoose_elements[$locale][$i]) ? $request->item_unchoose_elements[$locale][$i] : null;
                              
                                $item->translations()->create([
                                    'locale' => $locale,
                                    'title' => $titles[$i],
                                    'subtitle' => $subtitle,
                                    'chooseElement'=>$chooseElements,
                                    'unChooseElement'=>$unChooseElements
                                ]);
                            }
                        }
                    }
                }
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yeniləndi', [], 200, route('admin.subcategories.packets.index', $subcategories->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function destroy(Subcategory $subcategories, SubCategoryPacket $packet)
    {
        try {
            DB::beginTransaction();
            
            // Delete all associated items first
            foreach ($packet->items as $item) {
                $item->delete();
            }
            
            // Then delete the main packet
            $packet->delete();
            
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}