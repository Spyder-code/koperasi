@if ($model->is_close == 0)
@permission($can_edit)
{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]) !!}
<a href="{{ $edit_url }}" class="btn btn-space btn-primary active btn-sm">Ubah</a> |
@endpermission
{{-- @permission($can_delete)
@endpermission --}}
{!! Form::submit('Hapus', ['class'=>'btn btn-space btn-danger active btn-sm']) !!}
{!! Form::close()!!}
@endif
