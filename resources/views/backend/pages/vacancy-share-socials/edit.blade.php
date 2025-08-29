@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Səhifə Paylaşım Sosial</h4>
                <div class="buttons">
                    <a href="{{ route('admin.vacancy-share-socials.index') }}" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.vacancy-share-socials.update', $item->id) }}" method="POST" id="saveForm" enctype="multipart/form-data">
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
                        
                        <div class="mb-3">
                            <label for="title_{{$language->code}}" class="form-label">Adı ({{$language->code}})</label>
                            <input type="text" class="form-control"
                                name="title[{{$language->code}}]"
                                id="title_{{$language->code}}"
                                placeholder="Sosial şəbəkə adı daxil edin" 
                                value="{{ $item->getTranslation($language->code)->title }}">
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mb-3 mt-3">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" class="form-control" name="url" id="url" 
                           value="{{ $item->url }}" placeholder="https://example.com">
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">İkon/Şəkil</label>
                    @if($item->image)
                        <div class="mb-2">
                            <img src="{{ asset('/storage/'.$item->image) }}" alt="Social Image" style="max-height: 50px;">
                        </div>
                    @endif
                    <input type="file" class="form-control" name="image" id="image">
                    <small class="text-muted">Tövsiyə olunan ölçü: 32x32 piksel</small>
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