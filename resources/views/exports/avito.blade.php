<table>
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">AvitoId</th>
      <th scope="col">AvitoMyId</th>
      <th scope="col">Title</th>
      <th scope="col">Price</th>
      <th scope="col">Category</th>
      <th scope="col">GoodsType</th>
      <th scope="col">GoodsSubType</th>
      <th scope="col">BulkMaterialType</th>
      <th scope="col">Address</th>
      <th scope="col">Contactphone</th>
    </tr>
  </thead>
  <tbody>
    @foreach($products as $product)
      <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$product->avito_id}}</td>
        <td>{{$product->avito_my_id}}</td>
        <td>{{$product->field->title}}</td>
        <td>{{$product->price->cost ?? 'не заполнено'}}</td>
        <td>{{json_decode($product->field->options)->category ?? ''}}</td>
        <td>{{json_decode($product->field->options)->goodstype ?? ''}}</td>
        <td>{{json_decode($product->field->options)->goodssubtype ?? ''}}</td>
        <td>{{json_decode($product->field->options)->bulkmaterialtype ?? ''}}</td>
        <td>{{json_decode($product->field->options)->address ?? ''}}</td>
        <td>{{$product->client->phone_personal ?? ''}}</td>
      </tr>
    @endforeach
  </tbody>
</table>