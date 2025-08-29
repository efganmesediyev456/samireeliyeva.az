<div class="d-flex">
    <a href="{{ $downloadUrl }}" class="btn btn-sm btn-success me-2"  title="Endir">
        <i class="fas fa-eye"></i>
    </a>
    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('Bu faylı silmək istədiyinizə əminsiniz?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Sil">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>