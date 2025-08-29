@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Haqqımda</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.about.update', $item->id)}}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->iteration==1) active @endif" id="{{$language->code}}-tab" 
                            data-bs-toggle="tab" data-bs-target="#{{$language->code}}" type="button"
                            role="tab" aria-controls="{{$language->code}}" aria-selected="true">{{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->iteration==1) active @endif" id="{{$language->code}}"
                        role="tabpanel" aria-labelledby="{{$language->code}}-tab">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name_{{$language->code}}" class="form-label">Ad {{$language->code}}</label>
                                    <input type="text" class="form-control" name="name[{{$language->code}}]"
                                        id="name_{{$language->code}}"
                                        placeholder="Ad daxil edin"
                                        value="{{$item->getTranslation($language->code)?->name}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position_{{$language->code}}" class="form-label">Vəzifə {{$language->code}}</label>
                                    <input type="text" class="form-control" name="position[{{$language->code}}]"
                                        id="position_{{$language->code}}"
                                        placeholder="Vəzifə daxil edin"
                                        value="{{$item->getTranslation($language->code)?->position}}">
                                </div>
                            </div>

                            

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="biography_title_{{$language->code}}" class="form-label">Bioqrafiya Başlıq {{$language->code}}</label>
                                    <input type="text" class="form-control" name="biography_title[{{$language->code}}]"
                                        id="biography_title_{{$language->code}}"
                                        placeholder="Bioqrafiya başlıq daxil edin"
                                        value="{{$item->getTranslation($language->code)?->biography_title}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_{{$language->code}}" class="form-label">Başlıq {{$language->code}}</label>
                                    <input type="text" class="form-control" name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Başlıq daxil edin"
                                        value="{{$item->getTranslation($language->code)?->title}}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="biography_content_{{$language->code}}" class="form-label">Bioqrafiya Məzmun {{$language->code}}</label>
                            <textarea class="form-control ckeditor" name="biography_content[{{$language->code}}]"
                                id="biography_content_{{$language->code}}"
                                placeholder="Bioqrafiya məzmun daxil edin">{{$item->getTranslation($language->code)?->biography_content}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description_{{$language->code}}" class="form-label">Haqqında {{$language->code}}</label>
                            <textarea class="form-control ckeditor" name="description[{{$language->code}}]"
                                id="description_{{$language->code}}"
                                placeholder="Haqqında daxil edin">{{$item->getTranslation($language->code)?->description}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="seo_description_{{$language->code}}" class="form-label">Seo Haqqında {{$language->code}}</label>
                            <textarea class="form-control" name="seo_description[{{$language->code}}]"
                                id="seo_description_{{$language->code}}"
                                placeholder="Seo haqqında daxil edin">{{$item->getTranslation($language->code)?->seo_description}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="seo_keywords_{{$language->code}}" class="form-label">Açar sözlər {{$language->code}}</label>
                            <input type="text" class="form-control tagsview" name="seo_keywords[{{$language->code}}]"
                                id="seo_keywords_{{$language->code}}"
                                placeholder="Açar sözlər daxil edin"
                                value="{{$item->getTranslation($language->code)?->seo_keywords}}">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="published_books_count" class="form-label">Nəşr olunmuş kitab sayı</label>
                            <input type="number" class="form-control" name="published_books_count"
                                id="published_books_count"
                                placeholder="Kitab sayı daxil edin"
                                value="{{$item->published_books_count}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="certificates_count" class="form-label">Sertifikat sayı</label>
                            <input type="number" class="form-control" name="certificates_count"
                                id="certificates_count"
                                placeholder="Sertifikat sayı daxil edin"
                                value="{{$item->certificates_count}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="years_in_profession" class="form-label">İl peşə fəaliyyəti sayı</label>
                            <input type="number" class="form-control" name="years_in_profession"
                                id="years_in_profession"
                                placeholder="İl sayı daxil edin"
                                value="{{$item->years_in_profession}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Şəkil</label>
                                    <input type="file" name="image" class="form-control">
                                    @if($item->image)
                                        <img width="300" src="/storage/{{$item->image}}" alt="" class="mt-2">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Pdf</label>
                                    <input type="file" name="pdf" class="form-control">
                                    @if($item->pdf)
                                        <a class="btn btn-success" type="button" width="300" href="/storage/{{$item->pdf}}" alt="">Pdf bax</a>
                                    @endif
                                </div>
                            </div>
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