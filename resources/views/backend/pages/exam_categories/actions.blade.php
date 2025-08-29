{{-- resources/views/backend/pages/exam_categories/actions.blade.php --}}

<div class="btn-group" role="group">
    <a href="{{ $editRoute }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
    
    <form action="{{ $deleteRoute }}" method="POST" class="d-inline" 
          onsubmit="return confirm('Bu elementi silmək istədiyinizə əminsiniz?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>