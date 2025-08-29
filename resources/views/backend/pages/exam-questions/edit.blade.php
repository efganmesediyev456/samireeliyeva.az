@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="w-50 ">Sual Düzəliş - {{ $exam->translate() ? $exam->translate()->title : 'N/A' }}</h4>
                <div class="buttons">
                    <a href="{{ route('admin.subcategories.exams.questions.index', [$subcategories->id, $exam->id]) }}" class="btn btn-success d-flex  align-items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="wmax">Geriyə qayıt</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subcategories.exams.questions.update', [$subcategories->id, $exam->id, $question->id]) }}" method="POST" id="saveForm">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="points">Xal</label>
                            <input type="number" class="form-control" name="points" id="points" value="{{ $question->points }}" min="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="position">Sıra</label>
                            <input type="number" class="form-control" name="position" id="position" value="{{ $question->position }}" min="0">
                        </div>
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">Tip</label>
                            <select name="type" id="type" class="form-control" disabled>
                                <option value="1" {{ $question->type == 1 ? 'selected' : '' }}>Qapalı</option>
                                <option value="2" {{ $question->type == 2 ? 'selected' : '' }}>Açıq sual</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h5 class="mt-3 mb-3">Sual Mətni</h5>
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
                                    <textarea class="form-control"
                                        name="question_text[{{$language->code}}]"
                                        rows="3"
                                        placeholder="Sual mətnini daxil edin ({{$language->code}})">{{ $question->translate($language->code)?->question_text }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h5 class="mt-4 mb-3" @if($question->type==2) style="display: none;" @endif>Cavab Variantları <button type="button" class="btn btn-sm btn-primary ms-2" id="addOption">+ Variant əlavə et</button></h5>
                
                <div id="optionsContainer">
                    <!-- Options will be added here dynamically -->
                    @foreach($question->options as $optionIndex => $option)
                    <div class="option-item card p-3 mb-3" data-index="{{ $optionIndex }}">
                        <div class="d-flex justify-content-between mb-2">
                            <h6>Variant {{ chr(65 +  $optionIndex) }}</h6>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                        name="options[{{ $optionIndex }}][is_correct]" 
                                        id="isCorrect{{ $optionIndex }}"
                                        {{ $option->is_correct ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isCorrect{{ $optionIndex }}">
                                        Doğru cavab
                                    </label>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger remove-option ms-2">Sil</button>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($languages as $languageIndex => $language)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $languageIndex === 0 ? 'active' : '' }}"
                                    id="option{{ $optionIndex }}-{{ $language->code }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#option{{ $optionIndex }}-{{ $language->code }}"
                                    type="button" role="tab"
                                    aria-selected="{{ $languageIndex === 0 ? 'true' : 'false' }}">
                                    {{ $language->title }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content mt-2">
                            @foreach($languages as $languageIndex => $language)
                            <div class="tab-pane fade show {{ $languageIndex === 0 ? 'active' : '' }}"
                                id="option{{ $optionIndex }}-{{ $language->code }}" role="tabpanel">
                                <div class="form-group">
                                    <textarea 
                                        class="form-control"
                                        name="options[{{ $optionIndex }}][texts][{{ $language->code }}]"
                                        rows="2"
                                        placeholder="Variant mətnini daxil edin ({{ $language->code }})">{{ $option->translate($language->code)?->option_text }}</textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row mt-4">
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




    document.addEventListener('DOMContentLoaded', function() {
        const optionsContainer = document.getElementById('optionsContainer');
        const addOptionBtn = document.getElementById('addOption');
        const languages = @json($languages);
        let optionCount = {{ count($question->options) }};

        function addOption() {
            const optionDiv = document.createElement('div');
            optionDiv.className = 'option-item card p-3 mb-3';
            optionDiv.dataset.index = optionCount;

            const letter = String.fromCharCode(65 + optionCount);


            let tabsHtml = `
                <div class="d-flex justify-content-between mb-2">
                    <h6>Variant ${letter}</h6>
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="options[${optionCount}][is_correct]" id="isCorrect${optionCount}">
                            <label class="form-check-label" for="isCorrect${optionCount}">
                                Doğru cavab
                            </label>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-option ms-2">Sil</button>
                    </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
            `;

            languages.forEach((language, index) => {
                tabsHtml += `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ${index === 0 ? 'active' : ''}"
                            id="option${optionCount}-${language.code}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#option${optionCount}-${language.code}"
                            type="button" role="tab"
                            aria-selected="${index === 0 ? 'true' : 'false'}">
                            ${language.title}
                        </button>
                    </li>
                `;
            });

            tabsHtml += `</ul><div class="tab-content mt-2">`;

            languages.forEach((language, index) => {
                tabsHtml += `
                    <div class="tab-pane fade show ${index === 0 ? 'active' : ''}"
                        id="option${optionCount}-${language.code}" role="tabpanel">
                        <div class="form-group">
                            <textarea 
                                class="form-control"
                                name="options[${optionCount}][texts][${language.code}]"
                                rows="2"
                                placeholder="Variant mətnini daxil edin (${language.code})"></textarea>
                        </div>
                    </div>
                `;
            });

            tabsHtml += `</div>`;

            optionDiv.innerHTML = tabsHtml;
            optionsContainer.appendChild(optionDiv);

            // Add event listener to remove button
            optionDiv.querySelector('.remove-option').addEventListener('click', function() {
                optionDiv.remove();
                updateOptionNumbers();
            });

            optionCount++;
        }

        function updateOptionNumbers() {
            const optionItems = optionsContainer.querySelectorAll('.option-item');
            optionItems.forEach((item, index) => {
                const title = item.querySelector('h6');
                title.textContent = `Variant ${index + 1}`;
            });
        }

        // Set up the existing options
        document.querySelectorAll('.remove-option').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.option-item').remove();
                updateOptionNumbers();
            });
        });

        // Add option button event
        addOptionBtn.addEventListener('click', addOption);
    });
</script>
@endpush