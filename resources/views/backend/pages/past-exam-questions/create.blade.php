@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Yeni Keçmiş İmtahan Sualları - {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.past-exam-questions.index', $subcategories->id) }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.past-exam-questions.store', $subcategories->id) }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                            id="{{$language->code}}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{$language->code}}"
                            type="button" role="tab"
                            aria-controls="{{$language->code}}"
                            aria-selected="true">
                            {{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->first) active @endif"
                        id="{{$language->code}}" role="tabpanel"
                        aria-labelledby="{{$language->code}}-tab">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 w-100">
                                    <label for="title_{{$language->code}}" class="form-label">Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Başlıq daxil edin">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3 w-100">
                                    <label for="subtitle_{{$language->code}}" class="form-label">Alt Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="subtitle[{{$language->code}}]"
                                        id="subtitle_{{$language->code}}"
                                        placeholder="Alt başlıq daxil edin">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr>
                <h5>Sual Faylları</h5>
                
                <div id="file-container">
                    <div class="file-item mb-4 p-3 border rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fayl</label>
                                    <input type="file" name="files[]" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
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
                                        <div class="form-group">
                                            <label>Fayl Başlığı ({{$language->code}})</label>
                                            <input type="text" name="file_titles[0][{{$language->code}}]" class="form-control" 
                                                placeholder="Fayl üçün başlıq daxil edin">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-danger remove-file" style="display: none;">Faylı Sil</button>
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
                                <i class="fas fa-save"></i>
                                <span>Yadda saxla</span>
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
        const fileContainer = document.getElementById('file-container');
        const addFileButton = document.getElementById('add-file');
        let fileCount = 1;

        // Add new file input
        addFileButton.addEventListener('click', function() {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item mb-4 p-3 border rounded';
            
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
                        <div class="form-group">
                            <label>Fayl Başlığı ({{$language->code}})</label>
                            <input type="text" name="file_titles[${fileCount}][{{$language->code}}]" class="form-control" 
                                placeholder="Fayl üçün başlıq daxil edin">
                        </div>
                    </div>
                `;
            @endforeach
            
            fileItem.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fayl</label>
                            <input type="file" name="files[]" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <ul class="nav nav-tabs file-tabs mt-2" role="tablist">
                            ${fileTabsHtml}
                        </ul>
                        
                        <div class="tab-content mt-2">
                            ${fileTabContentHtml}
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-2">
                    <button type="button" class="btn btn-sm btn-danger remove-file">Faylı Sil</button>
                </div>
            `;
            
            fileContainer.appendChild(fileItem);
            fileCount++;
            
            // Show all remove buttons if there are more than one file
            if (fileContainer.querySelectorAll('.file-item').length > 1) {
                document.querySelectorAll('.remove-file').forEach(button => {
                    button.style.display = 'inline-block';
                });
            }
        });
        
        // Remove file input
        fileContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-file')) {
                e.target.closest('.file-item').remove();
                
                // Hide all remove buttons if only one file is left
                if (fileContainer.querySelectorAll('.file-item').length <= 1) {
                    document.querySelectorAll('.remove-file').forEach(button => {
                        button.style.display = 'none';
                    });
                }
            }
        });
    });
</script>
@endpush