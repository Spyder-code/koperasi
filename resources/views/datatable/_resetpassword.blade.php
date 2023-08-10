<a href="{{ $edit_url }}" class="btn btn-space btn-primary active btn-sm">Ubah</a> |
<a href="{{ $reset_url }}" class="btn btn-space btn-warning active btn-sm">Reset Password</a>
<form action="{{ route('user.destroy',$id) }}" method="post">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-space btn-danger btn-sm" onclick="return confirm('are you sure?')">Delete</button>
</form>
