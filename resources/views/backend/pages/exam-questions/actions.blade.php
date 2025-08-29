<div class="d-flex">
    <a href="{{ $editRoute }}" class="btn btn-sm btn-info me-2">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>