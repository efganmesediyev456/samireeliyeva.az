<?php

namespace App\Http\Resources;

use App\Models\InterviewPreparation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TopicResource;
use App\Http\Resources\LittleVideoRolicResource;
use App\Http\Resources\InterviewPreparationResource;
use App\Models\Order;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function seoKeywords($values)
    {
        $array = json_decode($values, true);
        $data = [];
        if (is_array($array) and count($array)) {
            foreach ($array as $key => $value) {
                $value['id'] = $key + 1;
                $data[] = $value;
            }
        }
        return $data;
    }

    public function toArray(Request $request): array
    {
        // Paginate products - you can adjust the page size (15) as needed
//        $paginatedProducts = $this->products()->paginate(15);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->getTranslatedSlugs($this),
            'seo_keywords' => $this->seoKeywords($this->seo_keywords),
            'seo_description' => $this->seo_description,
            'image' => url('storage/' . $this->image),
            'description' => $this->description,
            // 'topic'=>new TopicResource($this->topics?->first()),
            // 'videos'=> $this->getGroupedItemsWithTypeNames(),
            // 'littleVideoRolics' => LittleVideoRolicResource::collection($this->littleVideoRolics),
            // 'tests' => $this->getGroupedItemsWithTestsTypeNames(),
            // 'interviewPreparations'=>$this->getGroupedItemsWithinterviewPreparationsTypeNames(),
            // 'essayExamples'=>$this->getGroupedItemsWithEssayExamplesTypeNames(),
            // 'criticalReadings'=>$this->getGroupedItemsWithCriticalReadingsTypeNames(),
            // 'pastExamQuestions'=>PastExamQuestionResource::collection($this->pastExamQuestions),
            'hasActiveSubscription' => auth('api')->user() and auth('api')->user()->hasActiveSubscription($this),
            'hasTopic' => $this->topics()?->count() > 0,
            'topicIcon' => $this->topics()->first() ? url('/categoryicons/topic.svg') : null,
            'hasVideo' => $this->videos()?->count() > 0,
            'hasPastExamQuestion' => $this->pastExamQuestions?->count() > 0,
            'pastExamQuestionIcon' => $this->pastExamQuestions?->count() > 0 ? url('/categoryicons/video.svg') : null,
            'hasLittleVideoRolic' => $this->littleVideoRolics()?->count() > 0,
            'videoIcon' => $this->videos()->first() ? url('/categoryicons/video.svg') : null,
            'hasExam' => $this->tests()?->count() > 0,
            'examIcon' => $this->tests()->first() ? url('/categoryicons/exam.svg') : null,
            'hasEssayExample' => $this->essayExamples()?->count() > 0,
            'essayExampleIcon' => $this->essayExamples()?->first()?->thumbnail ? url('/categoryicons/essay.svg') : null,
            'hasInterviewPreparation' => $this->interviewPreparations()?->count() > 0,
            'interviewPreparationIcon' => $this->interviewPreparations()?->first() ? url('/categoryicons/interview.svg') : null,
            'hasCriticalReading' => $this->criticalReadings()?->count() > 0,
            'criticalReadingIcon' => $this->criticalReadings()?->first()?->thumbnail ? url('/categoryicons/critical.svg') : null,
            'littleVideoRolicIcon' => $this->littleVideoRolics()?->first() ? url('/categoryicons/video.svg') : null,
            'subscriptionsCount' => Order::where('subcategory_id', $this->id)->where('status', 'completed')->pluck('user_id')->unique()->count(),
        ];
    }
    private function getTranslatedSlugs(): array
    {
        $languages = Language::pluck('code')->toArray();

        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $this->translate($lang)->slug ?? null;
        }

        return $slugs;
    }

    protected function getTypeName($type)
    {
        $types = [
            1 => 'Əmək məcəlləsi',
            2 => 'Təhsil qanunvericiliyi',
        ];

        return $types[$type] ?? ucfirst($type);
    }


    protected function getWithinterviewPreparationTypeName($type)
    {
        $types = [
            1 => 'Əmək məcəlləsi',
            2 => 'Təhsil qanunvericiliyi',
        ];

        return $types[$type] ?? ucfirst($type);
    }


    protected function getGroupedItemsWithTypeNames()
    {
        return $this->videos->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getTypeName($type),
                'items' => VideoResource::collection($items),
            ];
        })->values();
    }



    public function getGroupedItemsWithEssayExamplesTypeNames()
    {
        return $this->essayExamples?->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getTypeName($type),
                'items' => EssayExampleResource::collection($items),
            ];
        })->values();
    }


    public function getGroupedItemsWithTestsTypeNames()
    {
        return $this->tests->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getTypeName($type),
                'items' => ExamResource::collection($items),
            ];
        })->values();
    }


    public function getGroupedItemsWithinterviewPreparationsTypeNames()
    {
        return $this->interviewPreparations->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getWithinterviewPreparationTypeName($type),
                'items' => InterviewPreparationResource::collection($items)
            ];
        })->values();
    }
    public function getGroupedItemsWithCriticalReadingsTypeNames()
    {
        return $this->criticalReadings->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'type_name' => $this->getWithinterviewPreparationTypeName($type),
                'items' => InterviewPreparationResource::collection($items)
            ];
        })->values();
    }
}
