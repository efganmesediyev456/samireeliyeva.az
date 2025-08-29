@extends('backend.layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="w-50">
                            {{ $exam->translate() ? $exam->translate()->title : 'N/A' }} - Suallar
                        </h4>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.subcategories.exams.index', $subcategories->id) }}" class="btn btn-info me-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>İmtahanlara qayıt</span>
                            </a>
                            <a href="{{ route('admin.subcategories.exams.questions.create', [$subcategories->id, $exam->id]) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i>
                                <span>Yeni Sual</span>
                            </a>


                            {{-- examQuestionRoute --}}
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