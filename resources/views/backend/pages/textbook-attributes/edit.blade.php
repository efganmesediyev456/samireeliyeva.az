@extends('backend.layouts.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Textbook Atributunu Redaktə Et - {{ $textbook->translate('az')->title ?? $textbook->id }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.textbooks.attributes.update', ['textbook'=>$textbook, 'item'=>$attribute]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                @foreach(['az' => 'Azərbaycan dili', 'en' => 'English', 'ru' => 'Русский'] as $locale => $label)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                                id="tab-{{ $locale }}"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-content-{{ $locale }}"
                                type="button"
                                role="tab"
                                aria-controls="tab-content-{{ $locale }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Tab content -->
            <div class="tab-content pt-3" id="languageTabsContent">
                @foreach(['az' => 'Azərbaycan dili', 'en' => 'English', 'ru' => 'Русский'] as $locale => $label)
                    <div class="tab-pane fade @if($loop->first) show active @endif"
                         id="tab-content-{{ $locale }}"
                         role="tabpanel"
                         aria-labelledby="tab-{{ $locale }}">
                         
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Key</label>
                                    <input type="text"
                                           name="{{ $locale }}_key"
                                           class="form-control @error($locale.'_key') is-invalid @enderror"
                                           value="{{ old($locale.'_key', $attribute->getTranslation($locale)->key ?? '') }}"
                                           >
                                    @error($locale.'_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Value</label>
                                    <input type="text"
                                           name="{{ $locale }}_value"
                                           class="form-control @error($locale.'_value') is-invalid @enderror"
                                           value="{{ old($locale.'_value', $attribute->getTranslation($locale)->value ?? '') }}"
                                           >
                                    @error($locale.'_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Buttons -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Yenilə</button>
                <a href="{{ route('admin.textbooks.attributes.index', $textbook) }}" class="btn btn-secondary">Geri qayıt</a>
            </div>
        </form>
    </div>
</div>
@endsection
