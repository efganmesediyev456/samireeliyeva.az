@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>İmtahan Düzəliş - {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.exams.index', $subcategories->id) }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.exams.update', [$subcategories->id, $item->id]) }}" method="POST" id="saveForm">
                @csrf
                @method('PUT')

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                            id="{{$language->code}}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{$language->code}}"
                            type="button" role="tab"
                            aria-controls="{{$language->code}}"
                            aria-selected="true">
                            {{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->first) active @endif"
                        id="{{$language->code}}" role="tabpanel"
                        aria-labelledby="{{$language->code}}-tab">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 w-100">
                                    <label for="title_{{$language->code}}" class="form-label">Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Başlıq daxil edin"
                                        value="{{ $item->translate($language->code)?->title }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug {{$language->code}}</label>
                                    <input type="text" class="form-control" name="slug[{{$language->code}}]"
                                        id="name[{{ $language->code }}]"
                                        placeholder="Slug daxil edin"
                                        value="{{  $item->translate($language->code)?->slug }}">
                                </div>

                            </div>

                            <div class="col-md-12">
                                <div class="mb-3 w-100">
                                    <label for="subtitle_{{$language->code}}" class="form-label">Alt Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="subtitle[{{$language->code}}]"
                                        id="subtitle_{{$language->code}}"
                                        placeholder="Alt Başlıq daxil edin"
                                        value="{{ $item->translate($language->code)?->subtitle }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 w-100">
                                    <label for="megasubtitle_{{$language->code}}" class="form-label">Mega Alt Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="megasubtitle[{{$language->code}}]"
                                        id="megasubtitle_{{$language->code}}"
                                        placeholder="Mega Alt Başlıq daxil edin"
                                        value="{{ $item->translate($language->code)?->megasubtitle }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Müddəti (dəq)</label>
                            <input type="number" class="form-control" name="duration" id="duration" 
                                   placeholder="Müddəti daxil edin" value="{{ $item->duration }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                                <div class="form-group d-block">
                                    <label for="">İmtahan kateqoriyası</label>
                                    <select  class="form-control form-select" name="exam_category_id">
                                        <option value="">Seçin</option>
                                        @foreach ($examCategories as $category)
                                            <option @selected($category->id == $item->exam_category_id ) value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>

                     <div class="row">
                            <div class="col-md-6">
                                <div class="">
                                    <label for="">Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                    <img width="150" src="/storage/{{ $item->icon }}" alt="">
                                </div>
                        </div>

                        

                        <div class="col-md-6">
                                <div class="form-group d-block">
                                    <label for="">İmtahan statusu</label>
                                    <select  class="form-control form-select" name="exam_status_id">
                                        <option value="">Seçin</option>
                                        @foreach ($examStatuses as $category)
                                            <option @selected($category->id == $item->exam_status_id ) value="{{ $category->id }}">{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                     </div>


                </div>


                {{-- <div class="col-md-6">
                    <div class="form-group d-block w-100">
                     <label for="">Status</label>
                     <select class="form-control" name="type">
                         @foreach(\App\Enums\TopicCategoryStatusEnum::cases() as $enum)
                             <option @selected($enum->value == $item->type) value="{{ $enum->value }}">{{ $enum->toString() }}</option>
                         @endforeach
                     </select>
                    </div>
                </div> --}}

                <div class="row mt-2">
                    <div class="d-flex justify-content-end">
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-success">
                                <i class="fas fa-save"></i>
                                <span>Yadda saxla</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection