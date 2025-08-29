@extends('backend.layouts.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Textbook Atributu Əlavə Et - {{ $textbook->translate('az')->title ?? $textbook->id }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.textbooks.attributes.store', $textbook) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <h5>Azərbaycan dili</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Key</label>
                            <input type="text" name="az_key" class="form-control" value="{{ old('az_key') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="text" name="az_value" class="form-control" value="{{ old('az_value') }}" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <h5>English</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Key</label>
                            <input type="text" name="en_key" class="form-control" value="{{ old('en_key') }}" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="text" name="en_value" class="form-control" value="{{ old('en_value') }}" >
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <h5>Русский</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Key</label>
                            <input type="text" name="ru_key" class="form-control" value="{{ old('ru_key') }}" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="text" name="ru_value" class="form-control" value="{{ old('ru_value') }}" >
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Yadda saxla</button>
            <a href="{{ route('admin.textbooks.attributes.index', $textbook) }}" class="btn btn-secondary">Geri qayıt</a>
        </form>
    </div>
</div>
@endsection