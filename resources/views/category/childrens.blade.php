@foreach($childrens as $i => $item)
<ul class="list-group">
  <li class="list-group-item">
    <div class="d-flex">
      <div>{{$item->id}}--{{$item->name}}-{{$item->parent_id}}</div>
      <a class="btn btn-link btn-sm" href="{{ route('category.edit',$item)}}"><i class="bi bi-pencil-fill"></i></a>
{{--       <form method="POST" action="{{route('category.destroy',$item)}}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
      </form> --}}
    </div>
  @if(count($item->children))
    @include('category.childrens',['childrens' => $item->children])
    @endif
  </li>
</ul>
@endforeach