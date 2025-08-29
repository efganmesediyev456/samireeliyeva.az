@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Qalereya Fotoğrafı Redaktə Et</h4>
                <div class="buttons">
                    <a href="{{ route('admin.galleryphotos.index') }}" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.galleryphotos.update', $item->id)}}" method="POST" id="saveForm" enctype="multipart/form-data">
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_{{$language->code}}" class="form-label">Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Başlıq daxil edin" value="{{  $item->translate($language->code)?->title }}">
                                </div>
                            </div>
                         

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug {{$language->code}}</label>
                                    <input type="text" class="form-control" name="slug[{{$language->code}}]"
                                        id="name[{{ $language->code }}]"
                                        placeholder="Slug daxil edin"
                                        value="{{  $item->translate($language->code)?->slug }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name_az" class="form-label">Seo Haqqında {{$language->code}}</label>
                                    <textarea class="form-control" name="seo_description[{{$language->code}}]"
                                        id="seo_description[{{ $language->code }}]"
                                        placeholder="Haqqında daxil edin">{{ $item->translate($language->code)?->seo_description}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label">Açar sözlər {{$language->code}}</label>
                                    <input type="text" class="form-control tagsview" name="seo_keywords[{{$language->code}}]"
                                        id="seo_keywords[{{ $language->code }}]"
                                        placeholder="Açar sözlər daxil edin"
                                        value="{{$item->translate($language->code)?->seo_keywords}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Əsas Şəkil</label>
                            <input type="file" name="image" class="form-control">
                            @if($item->image)
                                <img width="300" src="/storage/{{$item->image}}" alt="">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Multiple Media Files Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Qalereya Faylları</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="media_files" class="form-label">Fayllar Əlavə Et</label>
                                    <input type="file" name="media_files[]" class="form-control" multiple>
                                    <small class="text-muted">Birdən çox fayl seçə bilərsiniz</small>
                                </div>
                                
                                <!-- Existing Media Files -->
                                @if($item->media && $item->media->count() > 0)
                                <div class="mt-4">
                                    <h6>Mövcud Fayllar</h6>
                                    <div class="row">
                                        @foreach($item->media as $media)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="/storage/{{$media->file}}" class="card-img-top" alt="Media">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="delete_media[]" value="{{$media->id}}" id="delete_media_{{$media->id}}">
                                                            <label class="form-check-label" for="delete_media_{{$media->id}}">
                                                                Sil
                                                            </label>
                                                        </div>
                                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="media_order[{{$media->id}}]" value="{{$media->order}}" placeholder="Sıra">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
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