<a class="btn btn-primary btn-sm my-3" href="{{route('category.create')}}">Создать категорию</a>
@foreach($categories->where('parent_id',0) as $category)
<ul class="nav flex-column font-bold">
  <li>
    <strong><a href="{{route('Client.category',[
      'client'=>$client->id,
      'categoryId'=>$category->id,
      ])}}">{{$category->name}}</a></strong>
    @if($category->products_count)
    <span class="badge rounded-pill bg-success">{{$category->products_count}}</span>
    @endif
    @if($category->children_count > 0)
    @include('clients.menu.childrens',['childrens' =>$category->children()->withCount(['children', 'products' =>
    function ($query) use($client) {
    $query->where('client_id', $client->id);
    }])->get()])
    @endif
  </li>
</ul>
@endforeach