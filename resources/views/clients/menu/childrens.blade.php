@foreach($childrens as $catChild)
<ul class="nav flex-column ps-2">
  <li>
    <a href="{{route('Client.category',[
          'client'=>$client->id,
          'categoryId'=>$catChild->id,
          ])}}">{{$catChild->name}}</a>
    @if($catChild->products_count)
    <span class="badge rounded-pill bg-success">{{$catChild->products_count}}</span>
    @endif
    @if($catChild->children_count > 0)
    @include('clients.menu.childrens',['childrens' => $catChild->children()->withCount(['children', 'products' =>
    function ($query) use($client) {
    $query->where('client_id', $client->id);
    }])->get()])
    @endif
  </li>
</ul>
@endforeach