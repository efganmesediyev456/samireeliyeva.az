@extends('backend.layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>{{ $textbook->translate('az')->title ?? '' }} - Atributlar</h3>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.textbooks.attributes.create', $textbook) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i>
                    <span>Yeni Atribut Əlavə Et</span>
                </a>

                <a href="{{ route('admin.textbooks.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Geriyə qayıt</span>
                </a>
            </div>

        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Key (AZ)</th>
                        <th>Value (AZ)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attributes as $attribute)
                        @php
                            $azTranslation = $attribute->translate('az');
                        @endphp
                        <tr>
                            <td>{{ $azTranslation->key ?? '' }}</td>
                            <td>{{ $azTranslation->value ?? '' }}</td>
                            <td>
                                <a href="{{ route('admin.textbooks.attributes.edit', [$textbook, $attribute]) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.textbooks.attributes.destroy', [$textbook, $attribute]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Silmək istədiyinizdən əminsiniz?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
