<li>{{ $childrens->name }}</li>
@if ($childrens->children)
<ul>
  @foreach ($childrens->childrens as $childrens)
    @include('towns.childrens', ['childrens' => $childrens])
  @endforeach
</ul>
@endif
