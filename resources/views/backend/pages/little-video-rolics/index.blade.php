@extends('backend.layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>
                            {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }} - Kiçik video roliklər
                        </h4>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('admin.subcategories.index') }}" class="gap-2 btn btn-info me-2 d-flex align-items-center">
                                <i class="fas fa-arrow-left"></i>
                                <span class="" style="width:max-content">Geriyə qayıt</span>
                            </a>
                            <a href="{{ route('admin.subcategories.little-video-rolics.create', $subcategories->id) }}" class="gap-2 btn btn-success d-flex align-items-center">
                                <i class="fas fa-plus"></i>
                                <span style="width: max-content">Yeni Video</span>
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