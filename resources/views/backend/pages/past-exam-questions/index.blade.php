@extends('backend.layouts.layout')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }} - Keçmiş İmtahan Sualları</h4>
                    <div class="buttons d-flex align-items-center gap-1">
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-success d-flex align-items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span class="wmax">Geriyə qayıt</span>
                        </a>
                        <a href="{{ route('admin.subcategories.past-exam-questions.create', $subcategories->id) }}" class="d-flex gap-2 align-items-center btn btn-primary">
                            <i class="fas fa-plus"></i>
                            <span class="wmax">Yeni Suallar</span>
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