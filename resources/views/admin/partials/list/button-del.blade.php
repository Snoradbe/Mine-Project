<form action="{{$route}}" method="post" onsubmit="return confirm('Вы действительно хотите удалить {{($name ?? '')}}?')">
    @csrf @method('DELETE')

    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i> Удалить</button>
</form>
