@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="w-75">Paket Düzənləmə - {{ $subcategories->translate() ? $subcategories->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.packets.index', $subcategories->id) }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.packets.update', [$subcategories->id, $packet->id]) }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration_months" class="form-label">Müddət (Ay)</label>
                            <input type="number" class="form-control" name="duration_months" id="duration_months" min="1" value="{{ $packet->duration_months }}" required>
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
                                        value="{{ $packet->translate($language->code)?->title }}"
                                        @if($loop->first) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr>
                <h5>Mövcud Xüsusiyyətlər</h5>
                
                @if($packet->items->count() > 0)
                <div class="existing-items mb-4">
                    @foreach($packet->items as $index => $item)
                    <div class="existing-item-row mb-4 p-3 border rounded">
                        <input type="hidden" name="existing_items[]" value="{{ $item->id }}">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Mövcud İkon</label>
                                    @if($item->icon)
                                    <div>
                                        <img src="{{ url('storage/' . $item->icon) }}" alt="Icon" class="img-thumbnail" style="max-height: 80px;">
                                    </div>
                                    @else
                                    <div class="text-muted">İkon yüklənməyib</div>
                                    @endif
                                </div>
                                
                                <div class="mb-3">
                                    <label>İkon Dəyişdir (Boş buraxsanız köhnə şəkil qalacaq)</label>
                                    <input type="file" name="update_item_icons[{{ $item->id }}]" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Qiymət</label>
                                    <input type="number" name="existing_item_prices[{{ $item->id }}]" class="form-control" step="0.01" min="0" value="{{ $item->price }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Endirimli Qiymət</label>
                                    <input type="number" name="existing_item_discount_prices[{{ $item->id }}]" class="form-control" step="0.01" min="0" value="{{ $item->discount_price }}">
                                    <small class="text-muted">Boş buraxsanız, endirim tətbiq edilməyəcək</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs mt-2 d-flex" role="tablist">
                                    @foreach($languages as $language)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if($loop->first) active @endif"
                                            data-bs-toggle="tab"
                                            data-bs-target="#existing-item-{{$language->code}}-{{$item->id}}"
                                            type="button" role="tab"
                                            aria-selected="true">
                                            {{$language->title}}
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                                
                                <div class="tab-content mt-2 d-block">
                                    @foreach($languages as $language)
                                    <div class="tab-pane fade show @if($loop->first) active @endif"
                                        id="existing-item-{{$language->code}}-{{$item->id}}" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Başlıq ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="existing_item_titles[{{ $item->id }}][{{$language->code}}]" 
                                                        class="form-control" 
                                                        placeholder="Xüsusiyyət başlığını daxil edin"
                                                        value="{{ $item->translate($language->code)?->title }}"
                                                        @if($loop->first) required @endif>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Alt Başlıq ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="existing_item_subtitles[{{ $item->id }}][{{$language->code}}]" 
                                                        class="form-control" 
                                                        placeholder="Xüsusiyyət alt başlığını daxil edin"
                                                        value="{{ $item->translate($language->code)?->subtitle }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="existing_item_choose_elements[{{ $item->id }}][{{$language->code}}]" 
                                                        class="form-control tagsview" 
                                                        placeholder=""
                                                        value="{{ $item->translate($language->code)?->chooseElement }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili olmayan elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="existing_item_unchoose_elements[{{ $item->id }}][{{$language->code}}]" 
                                                        class="form-control tagsview" 
                                                        placeholder=""
                                                        value="{{ $item->translate($language->code)?->unChooseElement }}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-sm btn-danger delete-existing-item" 
                                data-item-id="{{ $item->id }}">
                                Sil
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>Hələ ki xüsusiyyət əlavə edilməyib.</p>
                @endif

                <hr>
                <h5>Yeni Dəyərlər Əlavə Et</h5>
                
                <div id="items-container">
                    <!-- New items will be added here -->
                </div>

                <div class="text-center my-3">
                    <button type="button" id="add-item" class="btn btn-info">
                        <i class="fas fa-plus"></i> Yeni dəyət Əlavə Et
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
                
                <!-- Hidden input for storing items to be deleted -->
                <input type="hidden" name="delete_items" id="delete-items-input" value="">
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
        const deleteItemsInput = document.getElementById('delete-items-input');
        let itemCount = 0;
        let itemsToDelete = [];

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
                                        placeholder="Xüsusiyyət başlığını daxil edin"
                                        @if($loop->first) required @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Alt Başlıq ({{$language->code}})</label>
                                    <input type="text" 
                                        name="item_subtitles[{{$language->code}}][${itemCount}]" 
                                        class="form-control" 
                                        placeholder="Xüsusiyyət alt başlığını daxil edin">
                                </div>
                            </div>


                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_choose_elements[{{ $item->id }}][${itemCount}]" 
                                                        class="form-control tagsview item_choose_elementsAdd" 
                                                        placeholder="">
                                                </div>
                            </div>

                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>Seçili olmayan elementlər ({{$language->code}})</label>
                                                    <input type="text" 
                                                        name="item_unchoose_elements[{{ $item->id }}][${itemCount}]" 
                                                        class="form-control tagsview item_unchoose_elementsAdd" 
                                                        placeholder=""
                                                        >
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


        });
        
        // Remove new item
        itemsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
            }
        });
        
        // Handle existing item deletion
        document.querySelectorAll('.delete-existing-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                
                // Add item ID to the list of items to delete
                itemsToDelete.push(itemId);
                deleteItemsInput.value = itemsToDelete.join(',');
                
                // Hide the item row from the UI
                this.closest('.existing-item-row').style.display = 'none';
            });
        });
    });



</script>
@endpush