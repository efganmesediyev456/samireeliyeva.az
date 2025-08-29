@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Yeni Müsahibəyə hazırlıq - {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.interview-preparations.index', $subcategories->id) }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.interview-preparations.store', $subcategories->id) }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
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
                            <select class="form-control" name="type" id="type">
                                    <option value="1">Video dərslər</option>
                                    <option value="2">Stuasiya</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 video">
                            <label class="form-label">Video tipi</label>
                            <select class="form-control" name="video_type" id="video_type">
                                <option value="link">Link</option>
                                <option value="local">Lokal fayl</option>
                            </select>
                        </div>

                        <div class="col-md-6 video" id="video_link_box">
                            <label for="video_url" class="form-label">Video Link</label>
                            <input type="text" class="form-control" name="video_url" id="video_url"
                                placeholder="Video link daxil edin">
                        </div>

                        <div class="col-md-6 d-none video" id="video_file_box">
                            <label for="video_file" class="form-label">Video Fayl</label>
                            <input type="file" class="form-control" name="video_url" id="video_file" accept="video/*">
                        </div>
                   

                    <!-- File section - will be shown when type=2 -->
                    <div class="col-md-12 file" style="display: none">
                        <div id="files-container">
                            <div class="file-row mb-4 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Fayl</label>
                                            <input type="file" name="files[0]" class="form-control">
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
                                    <button type="button" class="btn btn-sm btn-danger remove-file" style="display: none;">Sil</button>
                                </div>
                            </div>
                        </div>

                        <div class="text-center my-3">
                            <button type="button" id="add-file" class="btn btn-info">
                                <i class="fas fa-plus"></i> Yeni Fayl Əlavə Et
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6 video">
                        <div class="">
                            <label for="">Örtük Şəkli</label>
                            <input type="file" name="thumbnail" class="form-control">
                            <small class="text-muted">Video üçün örtük şəkli yükləyin</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group d-block w-100">
                            <label for="">Tarix</label>
                            <input type="date" class="form-control" name="date" />
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
                            <label>Fayl</label>
                            <input type="file" name="files[${fileCount}]" class="form-control">
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

         $("#video_type").on("change", function() {
                    if ($(this).val() === "link") {
                        $("#video_link_box").removeClass("d-none");
                        $("#video_file_box").addClass("d-none");
                    } else {
                        $("#video_file_box").removeClass("d-none");
                        $("#video_link_box").addClass("d-none");
                    }
                });

    });
</script>
@endpush