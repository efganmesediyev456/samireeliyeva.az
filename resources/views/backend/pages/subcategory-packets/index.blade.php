@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="w-75">{{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }} - Paketlər</h4>
                <div class="buttons d-flex align-items-center  gap-2">
                    <a href="{{ route('admin.subcategories.index') }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                    <a href="{{ route('admin.subcategories.packets.create', $subcategories->id) }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span class="wmax">Yeni Paket</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush