@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Müsahibəyə hazırlıq redaktə et - {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.interview-preparations.index', $subcategories->id) }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.interview-preparations.update', [$subcategories->id, $item->id]) }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="default_locale" value="{{ app()->getLocale() }}">

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
                            <div class="col-md-12">
                                <div class="mb-3 w-100">
                                    <label for="title_{{$language->code}}" class="form-label">Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        value="{{ $item->translate($language->code) ? $item->translate($language->code)->title : '' }}"
                                        placeholder="Başlıq daxil edin">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row gy-2 gx-4">
                    <div class="col-md-6">
                        <div class="form-group d-block w-100">
                            <label for="">Tip</label>
                            <select class="form-control" name="type" id="type" disabled>
                                <option value="1" {{ $item->type == 1 ? 'selected' : '' }}>Video dərslər</option>
                                <option value="2" {{ $item->type == 2 ? 'selected' : '' }}>Stuasiya</option>
                            </select>
                        </div>
                    </div>
                    




                <div class="col-md-6 video" style="{{ $item->type == 2 ? 'display: none;' : '' }}">
                    <label class="form-label">Video tipi</label>
                    <select class="form-control" name="video_type" id="video_type">
                        <option value="link" {{ $item->video_url && !Str::startsWith($item->video_url, 'video_uploads') ? 'selected' : '' }}>Link</option>
                        <option value="local" {{ $item->video_url && Str::startsWith($item->video_url, 'video_uploads') ? 'selected' : '' }}>Lokal fayl</option>
                    </select>
                </div>

                <div style="{{ $item->type == 2 ? 'display: none;' : '' }}" class="col-md-6 video {{ $item->video_url && Str::startsWith($item->video_url, 'video_uploads') ? 'd-none' : '' }}" id="video_link_box">
                    <label for="video_url" class="form-label">Video Link</label>
                    <input type="text" class="form-control" name="video_url"
                        id="video_url"
                        placeholder="Video link daxil edin"
                        value="{{ $item->video_url && !Str::startsWith($item->video_url, 'video_uploads') ? $item->video_url : '' }}">
                </div>

                <div style="{{ $item->type == 2 ? 'display: none;' : '' }}" class="col-md-6 video {{ $item->video_url && Str::startsWith($item->video_url, 'video_uploads') ? '' : 'd-none' }}" id="video_file_box">
                    <label for="video_file" class="form-label">Video Fayl</label>
                    <input type="file" class="form-control" name="video_url" id="video_file" accept="video/*">

                    @if($item->video_url && Str::startsWith($item->video_url, 'video_uploads'))
                        <p class="mt-2">Cari fayl: <a href="{{ asset('storage/'.$item->video_url) }}" target="_blank">Videonu aç</a></p>
                    @endif
                </div>




                    <!-- File section - will be shown when type=2 -->
                    <div class="col-md-12 file" style="{{ $item->type == 1 ? 'display: none;' : '' }}">
                        <div id="files-container">
                            @if($item->type == 2 && $files->count() > 0)
                                @foreach($files as $index => $file)
                                <div class="file-row mb-4 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Mövcud Fayl</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">{{ $file->original_name }}</span>
                                                    <a href="{{ asset('storage/' . $file->file_url) }}" target="_blank" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye"></i> Bax
                                                    </a>
                                                </div>
                                                <input type="hidden" name="file_ids[]" value="{{ $file->id }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Fayl Əvəz et (İstəyə bağlı)</label>
                                                <input type="file" name="update_files[{{ $index }}]" class="form-control">
                                                <small class="text-muted">Fayl dəyişdirmək istəmirsinizsə boş buraxın</small>
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
                                                        data-bs-target="#file-{{$language->code}}-{{ $index }}"
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
                                                    id="file-{{$language->code}}-{{ $index }}" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label>Fayl Adı ({{$language->code}})</label>
                                                                <input type="text" 
                                                                    name="file_titles[{{$language->code}}][{{ $index }}]" 
                                                                    class="form-control" 
                                                                    value="{{ $file->translate($language->code) ? $file->translate($language->code)->title : '' }}"
                                                                    placeholder="Fayl adını daxil edin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-file" data-id="{{ $file->id }}">Sil</button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="file-row mb-4 p-3 border rounded">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label>Fayl</label>
                                                <input type="file" name="new_files[0]" class="form-control">
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
                                                                    name="new_file_titles[{{$language->code}}][0]" 
                                                                    class="form-control" 
                                                                    placeholder="Fayl adını daxil edin">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-new-file" style="display: none;">Sil</button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-center my-3">
                            <button type="button" id="add-file" class="btn btn-info">
                                <i class="fas fa-plus"></i> Yeni Fayl Əlavə Et
                            </button>
                        </div>
                        
                        <div id="deleted-files-container">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="col-md-6 video" style="{{ $item->type == 2 ? 'display: none;' : '' }}">
                        <div class="">
                            <label for="">Örtük Şəkli</label>
                            @if($item->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" class="form-control">
                            <small class="text-muted">Boş buraxsanız, mövcud şəkil saxlanılacaq</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group d-block w-100">
                            <label for="">Tarix</label>
                            <input type="date" class="form-control" name="date" value="{{ $item->date?->format("Y-m-d") }}" />
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
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


@push("js")
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Type selection handling
        let type = document.getElementById("type");
        let videos = document.querySelectorAll(".video");
        let files = document.querySelectorAll(".file");

        type.addEventListener("change", function() {
            let value = this.value;

            if (value == 2) {
                videos.forEach(function(video) {
                    video.style.display = "none";
                });
                files.forEach(function(file) {
                    file.style.display = "block";
                });
            } else {
                videos.forEach(function(video) {
                    video.style.display = "block";
                });
                files.forEach(function(file) {
                    file.style.display = "none";
                });
            }
        });

        // File management (add/remove files)
        // File management (add/remove files)
        const filesContainer = document.getElementById('files-container');
        const deletedFilesContainer = document.getElementById('deleted-files-container');
        const addFileButton = document.getElementById('add-file');
        let fileCount = {{ isset($files) && $files->count() > 0 ? $files->count() : 1 }};
        let newFileIndex = 10000; // Start new files from a high index to avoid conflicts
        
        // Add new file
        if (addFileButton) {
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
                                data-bs-target="#file-{{$language->code}}-${newFileIndex}"
                                type="button" role="tab"
                                aria-selected="true">
                                {{$language->title}}
                            </button>
                        </li>
                    `;
                    
                    fileTabContentHtml += `
                        <div class="tab-pane fade show @if($loop->first) active @endif"
                            id="file-{{$language->code}}-${newFileIndex}" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Fayl Adı ({{$language->code}})</label>
                                        <input type="text" 
                                            name="new_file_titles[{{$language->code}}][${newFileIndex}]" 
                                            class="form-control" 
                                            placeholder="Fayl adını daxil edin">
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
                                <label>Yeni Fayl</label>
                                <input type="file" name="new_files[${newFileIndex}]" class="form-control" required>
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
                        <button type="button" class="btn btn-sm btn-danger remove-new-file">Sil</button>
                    </div>
                `;
                
                filesContainer.appendChild(fileRow);
                newFileIndex++;
                
                // Show all remove buttons if there are more than one file
                const fileRows = filesContainer.querySelectorAll('.file-row');
                if (fileRows.length > 1) {
                    document.querySelectorAll('.remove-file, .remove-new-file').forEach(button => {
                        button.style.display = 'inline-block';
                    });
                }
            });
        }
        
        // Remove existing file (mark for deletion)
        if (filesContainer) {
            filesContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    const fileId = e.target.getAttribute('data-id');
                    const fileRow = e.target.closest('.file-row');
                    
                    // Add a hidden input to track deleted files
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_files[]';
                    hiddenInput.value = fileId;
                    deletedFilesContainer.appendChild(hiddenInput);
                    
                    // Remove the file row from the UI
                    fileRow.remove();
                    
                    // Update remove buttons visibility
                    updateRemoveButtonsVisibility();
                }
            });
            
            // Remove new file (not yet saved)
            filesContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-new-file')) {
                    const fileRow = e.target.closest('.file-row');
                    fileRow.remove();
                    
                    // Update remove buttons visibility
                    updateRemoveButtonsVisibility();
                }
            });
        }
        
        // Helper function to update remove buttons visibility
        function updateRemoveButtonsVisibility() {
            const fileRows = filesContainer.querySelectorAll('.file-row');
            if (fileRows.length <= 1) {
                document.querySelectorAll('.remove-file, .remove-new-file').forEach(button => {
                    button.style.display = 'none';
                });
            } else {
                document.querySelectorAll('.remove-file, .remove-new-file').forEach(button => {
                    button.style.display = 'inline-block';
                });
            }
        }
        
        // Initialize remove buttons visibility
        updateRemoveButtonsVisibility();



        $(document).ready(function(){
            $("#video_type").on("change", function(){
                if($(this).val() === "link"){
                    $("#video_link_box").removeClass("d-none");
                    $("#video_file_box").addClass("d-none");
                } else {
                    $("#video_file_box").removeClass("d-none");
                    $("#video_link_box").addClass("d-none");
                }
            });
        });
    });
</script>
@endpush