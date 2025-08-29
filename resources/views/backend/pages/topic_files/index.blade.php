@extends('backend.layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>
                            {{ $category->translate() ? $category->translate()->title : 'N/A' }} - Fayllar
                        </h4>
                        <div>
                            <a href="{{ route('admin.subcategories.topics.categories.index', [$subcategories->id, $topic->id]) }}" class="btn btn-info me-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Geriyə qayıt</span>
                            </a>
                            <a href="{{ route('admin.subcategories.topics.categories.files.create', [$subcategories->id, $topic->id, $category->id]) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i>
                                <span>Yeni Fayllar</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush