@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Vakansiya Banner</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.vacancy-banner.update', $item->id)}}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->iteration==1) active @endif" id="{{$language->code}}-tab" data-bs-toggle="tab" data-bs-target="#{{$language->code}}" type="button"
                            role="tab" aria-controls="{{$language->code}}" aria-selected="true">{{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->iteration==1) active @endif" id="{{$language->code}}"
                        role="tabpanel" aria-labelledby="{{$language->code}}-tab">

                        <div class="mb-3">
                            <label for="title" class="form-label">Başlıq {{$language->code}}</label>
                            <input type="text" class="form-control" name="title[{{$language->code}}]"
                                id="name[{{ $language->code }}]"
                                placeholder="Başlıq daxil edin"
                                value="{{$item->getTranslation('title', $language->code, true)}}">
                        </div>

                       

                        <div class="mb-3">
                            <label for="description" class="form-label">Təsvir {{$language->code}}</label>
                            <textarea class="form-control ckeditor" name="description[{{$language->code}}]"
                                id="description[{{ $language->code }}]"
                                placeholder="Təsvir daxil edin">{{$item->getTranslation('description', $language->code, true)}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="full_name_az" class="form-label">Seo Təsvir {{$language->code}}</label>
                            <textarea class="form-control" name="seo_description[{{$language->code}}]"
                                id="seo_description[{{ $language->code }}]"
                                placeholder="SEO təsvir daxil edin">{{$item->getTranslation('seo_description', $language->code, true)}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="seo_keywords" class="form-label">Açar sözlər {{$language->code}}</label>
                            <input type="text" class="form-control tagsview" name="seo_keywords[{{$language->code}}]"
                                id="seo_keywords[{{ $language->code }}]"
                                placeholder="Açar sözlər daxil edin"
                                value="{{$item->getTranslation('seo_keywords', $language->code, true)}}">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label for="">Banner Şəkil</label>
                    <input type="file" name="image" class="form-control">
                    @if($item->image)
                        <div class="mt-2">
                            <img width="300" src="{{'/storage/'.$item->image}}" alt="Banner Image">
                        </div>
                    @endif
                </div>

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