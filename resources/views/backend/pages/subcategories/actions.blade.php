<div class="dropdown">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Əməliyyatlar
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ $editRoute }}">
                <i class="fas fa-pen me-1"></i> Redaktə et
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $topicsRoute }}">
                <i class="fas fa-book-open me-1"></i> Mövzular
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $videosRoute }}">
                <i class="fas fa-video me-1"></i> Video dərslər
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $littleVideoRolicRoute }}">
                <i class="fas fa-film me-1"></i> Kiçik video roliklər
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $interviewPreparationRoute }}">
                <i class="fas fa-user-tie me-1"></i> Müsahibəyə hazırlıq
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $examCategoryRoute }}">
                <i class="fas fa-file-alt me-1"></i> İmtahanlar Kateqoriyaları
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $examStatusRoute }}">
                <i class="fas fa-file-alt me-1"></i> İmtahanlar statusları
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $examRoute }}">
                <i class="fas fa-file-alt me-1"></i> İmtahanlar
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $essayExamplesRoute }}">
                <i class="fas fa-book-open me-1"></i> Esse nümunələri
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $criticalReadingsRoute }}">
                <i class="fas fa-glasses me-1"></i> Tənqidi oxu
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $pastExamQuestionsRoute }}">
                <i class="fas fa-book-open me-1"></i> Əvvəlki ildə düşən suallar
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $packetsRoute }}">
                <i class="fas fa-box-open me-1"></i> Paketlər
            </a>
        </li>
        <li>
            <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Bu alt kateqoriyanı silmək istədiyinizə əminsiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item text-danger">
                    <i class="fas fa-trash-alt me-1"></i> Sil
                </button>
            </form>
        </li>
    </ul>
</div>
