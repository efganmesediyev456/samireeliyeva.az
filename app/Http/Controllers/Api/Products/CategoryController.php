<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\EssayExampleResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\InterviewPreparationResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\LittleVideoRolicResource;
use App\Http\Resources\PacketResource;
use App\Http\Resources\PastExamQuestionResource;
use App\Http\Resources\Products\CategoryResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\TopicResource;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\VideoResource;
use App\Models\About;
use App\Models\Category;
use App\Models\ExamCategory;
use App\Models\ExamStatus;
use App\Models\PastExamQuestion;
use App\Models\Subcategory;
use App\Models\Language;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\SubCategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::status()->order()->get());
    }


    public function category($slug)
    {
        try {
            $item = Category::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }
            return new CategoryResource($item);
        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function getSubCategories(Request $request, $slug)
    {
        try {
            
            $item = Category::status()->get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            

            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }
            $items = $item->subcategories()->status()->get();


            return new CategoryResource($item);


        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }



    public function subCategory(Request $request, $slug)
    {
        try {
            $item = Subcategory::status()->get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return new SubCategoryResource($item);
        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }



    public function getTopic(Request $request, $slug)
    {
        try {
            $item = Subcategory::status()->get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return new TopicResource($item->topics?->first());
        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    protected function getTypeName($type)
    {
        $types = [
            1 => 'Əmək məcəlləsi',
            2 => 'Təhsil qanunvericiliyi',
        ];

        return $types[$type] ?? ucfirst($type);
    }

     protected function getEssayTypeName($type)
    {
        $types = [
            1 => 'Video dərslər',
            2 => 'Mövzu',
        ];

        return $types[$type] ?? ucfirst($type);
    }

    


    protected function getGroupedItemsWithTypeNames($item)
    {
        return $item->videos->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getTypeName($type),
                'items' => VideoResource::collection($items),
            ];
        })->values();
    }

    public function getVideo(Request $request, $slug)
    {
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return $this->getGroupedItemsWithTypeNames($item);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    // public function getGroupedItemsWithTestsTypeNames($item)
    // {
    //     return $item->tests->groupBy('type')->map(function ($items, $type) {
                  

    //         return [
    //             'type' => $type,
    //             'type_name' => $this->getTypeName($type),
    //             'categories'=>$items->groupBy('exam_category_id')->map(function( $categoryItems, $category){
    //                 $category = ExamCategory::find($category);
    //                 return [
    //                     'category_id'=>$category->id,
    //                     'category_title'=>$category->title,
    //                     'items' => ExamResource::collection($categoryItems),
    //                 ];
    //             })->values()
    //         ];
    //     })->values();
    // }


    public function getGroupedItemsWithTestsTypeNames($item)
    {
        return $item->tests->groupBy('exam_status_id')->map(function ($items, $status) {

            $status = ExamStatus::find($status);

            return [
                'type' => $status?->id,
                'type_name' => $status?->title,
                'categories' => $items->groupBy('exam_category_id')->map(function ($categoryItems, $category) {
                    $category = ExamCategory::find($category);
                    return [
                        'category_id' => $category?->id,
                        'category_title' => $category?->title,
                        'items' => ExamResource::collection($categoryItems),
                    ];
                })->values()
            ];
        })->values();
    }


    public function getTests(Request $request, $slug)
    {
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return $this->getGroupedItemsWithTestsTypeNames($item);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function getLittleVideoRolics(Request $request, $slug)
    {
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return LittleVideoRolicResource::collection($item->littleVideoRolics);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


     protected function getWithinterviewPreparationTypeName($type)
    {
        $types = [
            1 => 'Video dərslər',
            2 => 'Situasiya',
        ];

        return $types[$type] ?? ucfirst($type);
    }

    public function getGroupedItemsWithinterviewPreparationsTypeNames($item){
        return $item->interviewPreparations->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getWithinterviewPreparationTypeName($type),
                'items'=>InterviewPreparationResource::collection($items->sortByDesc('id'))
            ];
        })->values();
    }

    public function getInterviewPreparations(Request $request, $slug)
    {
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return $this->getGroupedItemsWithinterviewPreparationsTypeNames($item);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function getGroupedItemsWithEssayExamplesTypeNames($item){
        return $item->essayExamples?->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getEssayTypeName($type),
                // 'type_name' => $this->getTypeName($type),
                'items' => EssayExampleResource::collection($items),
            ];
        })->values();
    }

      public function getEssayExamples(Request $request, $slug)
    {
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }

            return $this->getGroupedItemsWithEssayExamplesTypeNames($item);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function getGroupedItemsWithCriticalReadingsTypeNames($item){
        return $item->criticalReadings->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getWithinterviewPreparationTypeName($type),
                'items'=>InterviewPreparationResource::collection($items)
            ];
        })->values();
    }

    public function getCriticalReadings(Request $request, $slug){
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }
            return $this->getGroupedItemsWithCriticalReadingsTypeNames($item);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function getPastExamQuestions(Request $request, $slug){
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
           

            $pastexams=PastExamQuestion::where('subcategory_id',$item->id)->get();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }
            return PastExamQuestionResource::collection($pastexams);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
    
    public function getPackets(Request $request, $slug){
        try {
            $item = Subcategory::get()->filter(function ($q) use ($slug) {
                return $q->slug == $slug;
            })->first();
            if (!$item) {
                return $this->responseMessage('error', 'Dəyər tapılmadı', [], 400, null);
            }
            return PacketResource::collection($item->packets);

        } catch (\Exception $exception) {
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
    

}
