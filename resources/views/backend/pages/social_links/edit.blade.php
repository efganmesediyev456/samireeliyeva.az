@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Sosial Bağlantılar</h4>
                <div class="buttons">
                    <a href="{{ route('admin.social_links.index') }}" class="btn btn-success">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.social_links.update', $item->id) }}" method="POST" id="saveForm" enctype="multipart/form-data">
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
                            <label for="title_{{$language->code}}" class="form-label">Başlıq ({{$language->code}})</label>
                            <input type="text" class="form-control"
                                name="title[{{$language->code}}]"
                                id="title_{{$language->code}}"
                                placeholder="Başlıq daxil edin" 
                                value="{{ $item->translate($language->code)?->title }}">
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mb-3 mt-3">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" class="form-control" name="url" id="url" value="{{ $item->url }}" placeholder="https://example.com" required>
                </div>
                
                <div class="mb-3 mt-3">
                    <label for="image" class="form-label">İkon şəkli</label>
                    @if($item->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$item->image) }}" alt="Social Link Image" style="max-height: 50px; max-width: 100%;">
                        </div>
                    @endif
                    <input type="file" class="form-control" name="image" id="image">
                    <small class="text-muted">Tövsiyə olunan ölçü: 32x32 piksel. Boş buraxsanız, köhnə şəkil saxlanılacaq.</small>
                </div>
                
                {{-- <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order" class="form-label">Sıralama</label>
                            <input type="number" class="form-control" name="order" id="order" value="{{ $item->order }}" min="0">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Aktiv</option>
                                <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Deaktiv</option>
                            </select>
                        </div>
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