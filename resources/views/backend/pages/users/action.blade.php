<div class="dropdown">
    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Əməliyyatlar
    </button>
    <ul class="dropdown-menu">
        <li>
            <a href="{{ route('admin.users.edit', $row->id) }}" class="dropdown-item">
                <i class="fas fa-edit"></i> Redaktə et
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.exams.index', $row->id) }}" class="dropdown-item">
                <i class="fas fa-file-alt me-1"></i> Imtahanlara bax
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.subscriptions.index', $row->id) }}" class="dropdown-item">
                <i class="fas fa-file-alt me-1"></i> Abunə paketlərinə bax
            </a>
        </li>
        <li>
            <form action="{{ route('admin.users.destroy', $row->id) }}" method="POST"
                onsubmit="return confirm('Bu istifadəçini silmək istədiyinizə əminsiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item text-danger">
                    <i class="fas fa-trash"></i> Sil
                </button>
            </form>
        </li>
    </ul>
</div>
