<?php

namespace App\Services;

use App\Models\FieldTranslation;
use App\Models\Language;
use App\Models\RoadPass;
use App\Notifications\RoadPasses\RoadPassExpiredNotification;
use Flasher\Prime\Notification\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class MainService
{
    public $model;

    public function getAll()
    {
        return $this->model::orderBy('created_at', 'desc')->get();
    }

    public function save($item, array $data)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value))
                $item->$key = $value;
        }

        $item->save();
        return $item->fresh();
    }

    public function getById(int $id)
    {
        return $this->model::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $model = $this->getById($id);
        $model->update($data);
        return $model->fresh();
    }

    public function delete(int $id)
    {
        $this->model::destroy($id);
    }


    public function createTranslations($item, Request $request)
    {
        foreach (Language::all() as $lang) {
            $locale = $lang->code;

            foreach ($item->translatedAttributes as $transAttribute) {
                
                $value = $request->$transAttribute[$locale] ?? null;

                if ($transAttribute == 'slug') {
                    if (empty($value)) {
                        $value = $request->title[$locale] ?? null;
                    }

                    $value = Str::slug($value);

                    // Əgər slug boşdursa, konflikt yoxlamasından çıxar
                    if (!empty($value)) {
                        $conflict = $item->newQuery()
                            ->whereHas('translations', function ($query) use ($locale, $value) {
                                $query->where('locale', $locale)->where('slug', $value);
                            })
                            ->where('id', '!=', $item->id)
                            ->exists();

                        if ($conflict) {
                            abort(400, "Slug '$value' ($locale) artıq istifadə olunub.");
                        }
                    }
                }
                

                $item->translateOrNew($locale)->$transAttribute = $value;
            }
        }

        $item->save();
    }


}
