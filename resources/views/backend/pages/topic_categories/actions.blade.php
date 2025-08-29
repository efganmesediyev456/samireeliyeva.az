<div class="d-flex">
    <a href="{{ $editRoute }}" class="btn btn-sm btn-primary me-2" title="Düzəliş et">
        <i class="fas fa-edit"></i>
    </a>
    <a href="{{ $filesRoute }}" class="btn btn-sm btn-info me-2" title="Fayllar">
        <i class="fas fa-file"></i>
    </a>
    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Bu kateqoriyanı silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Sil">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>