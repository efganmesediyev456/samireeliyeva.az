@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Yeni Fayllar - {{ $category->translate() ? $category->translate()->title : 'N/A' }}</h4>
                <div class="buttons d-flex align-items-center">
                    <a href="{{ route('admin.subcategories.topics.categories.files.index', [$subcategories->id, $topic->id, $category->id]) }}" class="btn btn-success align-items-center d-flex gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.topics.categories.files.store', [$subcategories->id, $topic->id, $category->id]) }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="default_locale" value="{{ app()->getLocale() }}">

                <div id="files-container">
                    <div class="file-row mb-4 p-3 border rounded">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Fayl</label>
                                    <input type="file" name="files[0]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs file-tabs mt-2" role="tablist">
                                    @foreach($languages as $language)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if($loop->first) active @endif"
                                            data-bs-toggle="tab"
                                            data-bs-target="#file-{{$language->code}}-0"
                                            type="button" role="tab"
                                            aria-selected="true">
                                            {{$language->title}}
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                                
                                <div class="tab-content mt-2">
                                    @foreach($languages as $language)
                                    <div class="tab-pane fade show @if($loop->first) active @endif"
                                        id="file-{{$language->code}}-0" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label>Fayl Adı ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="file_titles[{{$language->code}}][0]" 
                                                        class="form-control" 
                                                        placeholder="Fayl adını daxil edin"
                                                        @if($loop->first) required @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-danger remove-file" style="display: none;">Sil</button>
                        </div>
                    </div>
                </div>

                <div class="text-center my-3">
                    <button type="button" id="add-file" class="btn btn-info">
                        <i class="fas fa-plus"></i> Yeni Fayl Əlavə Et
                    </button>
                </div>

                <div class="row mt-3">
                    <div class="d-flex justify-content-end">
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-success">
                                <i class="fas fa-upload"></i>
                                <span>Yüklə</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filesContainer = document.getElementById('files-container');
        const addFileButton = document.getElementById('add-file');
        let fileCount = 1;

        // Add new file
        addFileButton.addEventListener('click', function() {
            const fileRow = document.createElement('div');
            fileRow.className = 'file-row mb-4 p-3 border rounded';
            
            let fileTabsHtml = '';
            let fileTabContentHtml = '';
            
            @foreach($languages as $language)
                fileTabsHtml += `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                            data-bs-toggle="tab"
                            data-bs-target="#file-{{$language->code}}-${fileCount}"
                            type="button" role="tab"
                            aria-selected="true">
                            {{$language->title}}
                        </button>
                    </li>
                `;
                
                fileTabContentHtml += `
                    <div class="tab-pane fade show @if($loop->first) active @endif"
                        id="file-{{$language->code}}-${fileCount}" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Fayl Adı ({{$language->code}})</label>
                                    <input type="text" 
                                        name="file_titles[{{$language->code}}][${fileCount}]" 
                                        class="form-control" 
                                        placeholder="Fayl adını daxil edin"
                                        @if($loop->first) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            @endforeach
            
            fileRow.innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Fayl</label>
                            <input type="file" name="files[${fileCount}]" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs file-tabs mt-2" role="tablist">
                            ${fileTabsHtml}
                        </ul>
                        
                        <div class="tab-content mt-2">
                            ${fileTabContentHtml}
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-2">
                    <button type="button" class="btn btn-sm btn-danger remove-file">Sil</button>
                </div>
            `;
            
            filesContainer.appendChild(fileRow);
            fileCount++;
            
            // Show all remove buttons if there are more than one file
            if (filesContainer.querySelectorAll('.file-row').length > 1) {
                document.querySelectorAll('.remove-file').forEach(button => {
                    button.style.display = 'inline-block';
                });
            }
        });
        
        // Remove file
        filesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-file')) {
                e.target.closest('.file-row').remove();
                
                // Hide all remove buttons if only one file is left
                if (filesContainer.querySelectorAll('.file-row').length <= 1) {
                    document.querySelectorAll('.remove-file').forEach(button => {
                        button.style.display = 'none';
                    });
                }
            }
        });
    });
</script>
@endpush