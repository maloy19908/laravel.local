@foreach ($productsMax as $rowMax => $v)
  @if($rowMax == $row)
    <td>{{$value}}</td>
  @else
    <td>xx</td>
  @endif
@endforeach