@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Kateqoriya Düzəliş - {{ $topic->translate() ? $topic->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.topics.categories.index', [$subcategories->id, $topic->id]) }}" class="btn btn-success d-flex align-items-center gap-1">
                        <i class="fas fa-arrow-left"></i>
                        <span>Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.topics.categories.update', [$subcategories->id, $topic->id, $category->id]) }}" method="POST" id="saveForm">
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
                                        placeholder="Başlıq daxil edin"
                                        value="{{ $category->translate($language->code)?->title }}"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>



                <div class="row">
                    <div class="col-md-6">
                       <div class="form-group d-block w-100">
                        <label for="">Status</label>
                        <select class="form-control" name="type">
                            @foreach(\App\Enums\TopicCategoryStatusEnum::cases() as $enum)
                                <option @selected($enum->value==$category->type) value="{{ $enum->value }}">{{ $enum->toString() }}</option>
                            @endforeach
                        </select>
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