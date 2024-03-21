<table border="1">
  <thead>
    <tr>
      <td>avito_id</td>
      <td>my_id</td>
      <td>dateBegin</td>
      <td>productStatus</td>
    </tr>
  </thead>
  <tbody>
    @foreach ($filtered as $item)
      <tr>
        <td>{{ $item->avito_id }}</td>
        <td>{{ $item->my_id }}</td>
        <td>{{ $item->dateBegin }}</td>
        <td>{{ $item->productStatus }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
