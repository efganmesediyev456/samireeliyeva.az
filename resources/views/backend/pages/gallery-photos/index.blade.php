@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Qalereya şəkilləri</h4>
                <div class="buttons">
                    <a href="{{ route('admin.galleryphotos.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        <span class="wmax">Yenisini yarat</span>
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