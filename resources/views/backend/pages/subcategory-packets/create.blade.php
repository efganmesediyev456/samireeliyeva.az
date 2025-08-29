@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="w-75">Yeni Paket - {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }}</h4>
                <div class="buttons d-flex align-items-center">
                    <a href="{{ route('admin.subcategories.packets.index', $subcategories->id) }}" class="btn btn-success align-items-center d-flex gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.packets.store', $subcategories->id) }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration_months" class="form-label">Müddət (Ay)</label>
                            <input type="number" class="form-control" name="duration_months" id="duration_months" min="1" value="1" required>
                        </div>
                    </div>
                </div>

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
                                    <label for="title_{{$language->code}}" class="form-label">Paket Başlığı ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Paket başlığını daxil edin" 
                                        @if($loop->first) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr>
                <h5>Paket Xüsusiyyətləri</h5>
                
                <div id="items-container">
                    <div class="item-row mb-4 p-3 border rounded">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>İkon</label>
                                    <input type="file" name="item_icons[0]" class="form-control">
                                    <small class="text-muted">İkon şəkli (istəyə bağlı)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Qiymət</label>
                                    <input type="number" name="item_prices[0]" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Endirimli Qiymət</label>
                                    <input type="number" name="item_discount_prices[0]" class="form-control" step="0.01" min="0">
                                    <small class="text-muted">Boş buraxsanız, endirim tətbiq edilməyəcək</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs item-tabs mt-2" role="tablist">
                                    @foreach($languages as $language)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if($loop->first) active @endif"
                                            data-bs-toggle="tab"
                                            data-bs-target="#item-{{$language->code}}-0"
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
                                        id="item-{{$language->code}}-0" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Başlıq ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_titles[{{$language->code}}][0]" 
                                                        class="form-control" 
                                                        placeholder=""
                                                        @if($loop->first) required @endif>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Alt Başlıq ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_subtitles[{{$language->code}}][0]" 
                                                        class="form-control" 
                                                        placeholder="">
                                                </div>
                                            </div>



                                             <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_chooseElement[{{$language->code}}][0]" 
                                                        class="form-control tagsview" 
                                                        placeholder=""
                                                        value="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili olmayan elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_unChooseElement[{{$language->code}}][0]" 
                                                        class="form-control tagsview" 
                                                        placeholder=""
                                                        value="">
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-danger remove-item" style="display: none;">Sil</button>
                        </div>
                    </div>
                </div>

                <div class="text-center my-3">
                    <button type="button" id="add-item" class="btn btn-info">
                        <i class="fas fa-plus"></i> Yeni dəyər Əlavə Et
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
        const itemsContainer = document.getElementById('items-container');
        const addItemButton = document.getElementById('add-item');
        let itemCount = 1;

        // Add new item
        addItemButton.addEventListener('click', function() {
            const itemRow = document.createElement('div');
            itemRow.className = 'item-row mb-4 p-3 border rounded';
            
            let itemTabsHtml = '';
            let itemTabContentHtml = '';
            
            @foreach($languages as $language)
                itemTabsHtml += `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                            data-bs-toggle="tab"
                            data-bs-target="#item-{{$language->code}}-${itemCount}"
                            type="button" role="tab"
                            aria-selected="true">
                            {{$language->title}}
                        </button>
                    </li>
                `;
                
                itemTabContentHtml += `
                    <div class="tab-pane fade show @if($loop->first) active @endif"
                        id="item-{{$language->code}}-${itemCount}" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Başlıq ({{$language->code}})</label>
                                    <input type="text" 
                                        name="item_titles[{{$language->code}}][${itemCount}]" 
                                        class="form-control" 
                                        placeholder=""
                                        @if($loop->first) required @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Alt Başlıq ({{$language->code}})</label>
                                    <input type="text" 
                                        name="item_subtitles[{{$language->code}}][${itemCount}]" 
                                        class="form-control" 
                                        placeholder="">
                                </div>
                            </div>


                             <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_chooseElement[{{$language->code}}][${itemCount}]" 
                                                        class="form-control tagsview" 
                                                        placeholder=""
                                                        value="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili olmayan elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_unChooseElement[{{$language->code}}][${itemCount}]" 
                                                        class="form-control tagsview" 
                                                        placeholder=""
                                                        value="">
                                                </div>
                                            </div>






                        </div>
                    </div>
                `;
            @endforeach
            
            itemRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>İkon</label>
                            <input type="file" name="item_icons[${itemCount}]" class="form-control">
                            <small class="text-muted">İkon şəkli (istəyə bağlı)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Qiymət</label>
                            <input type="number" name="item_prices[${itemCount}]" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label>Endirimli Qiymət</label>
                            <input type="number" name="item_discount_prices[${itemCount}]" class="form-control" step="0.01" min="0">
                            <small class="text-muted">Boş buraxsanız, endirim tətbiq edilməyəcək</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs item-tabs mt-2" role="tablist">
                            ${itemTabsHtml}
                        </ul>
                        
                        <div class="tab-content mt-2">
                            ${itemTabContentHtml}
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-2">
                    <button type="button" class="btn btn-sm btn-danger remove-item">Sil</button>
                </div>
            `;
            
            itemsContainer.appendChild(itemRow);
            itemCount++;

             $(itemRow).find('.tagsview').each(function() {
                new Tagify(this);
            });
            
            // Show all remove buttons if there are more than one item
            if (itemsContainer.querySelectorAll('.item-row').length > 1) {
                document.querySelectorAll('.remove-item').forEach(button => {
                    button.style.display = 'inline-block';
                });
            }
        });
        
        // Remove item
        itemsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
                
                // Hide all remove buttons if only one item is left
                if (itemsContainer.querySelectorAll('.item-row').length <= 1) {
                    document.querySelectorAll('.remove-item').forEach(button => {
                        button.style.display = 'none';
                    });
                }
            }
        });
    });
</script>
@endpush