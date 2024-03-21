@isset($categories)
  @foreach ($categories as $categoryItem )
  <option value="{{$categoryItem->id ?? ''}}" @isset($category->id)
    @if($category->parent_id == $categoryItem->id)
    selected
    @endif
    @if($category->id == $categoryItem->id)
    selected
    disabled
    @endif
    @endisset
    >
    {{$categoryItem->id}}-{{$categoryItem->name ?? ''}} --- {{$categoryItem->parent_id}}
  </option>
  @isset($categoryItem->children)
  @include('category._categories',[
  'categories'=>$categoryItem->children,
  ])
  @endisset
  @endforeach
@endisset
