@permission($can_edit)
<a href="{{ $edit_url }}" class="btn btn-space btn-primary active btn-sm">Ubah</a>
@if ($can_edit=='edit-role')
    <form action="{{ route('role.destroy',$id) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-space btn-danger btn-sm" onclick="return confirm('are you sure?')">Delete</button>
    </form>
@endif
@endpermission

