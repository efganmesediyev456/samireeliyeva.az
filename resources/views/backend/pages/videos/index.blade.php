@extends('backend.layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>
                            {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }} - Video dərslər
                        </h4>
                        <div class="d-flex align-items-center ">
                            <a href="{{ route('admin.subcategories.index') }}" class="gap-2 btn btn-info me-2 d-flex align-items-center">
                                <i class="fas fa-arrow-left"></i>
                                <span class="wmax">Geriyə qayıt</span>
                            </a>
                            <a href="{{ route('admin.subcategories.videos.create', $subcategories->id) }}" class="gap-1 btn btn-success d-flex align-items-center">
                                <i class="fas fa-plus"></i>
                                <span class="wmax">Yeni Video dərs</span>
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