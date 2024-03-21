<table border="1">
  <thead>
    <tr>
      <th scope="col">name</th>
      <th scope="col">cost</th>
      <th scope="col">priceType</th>
      <th scope="col">bagUnits</th>
      <th scope="col">bagValue</th>
      <th scope="col">category</th>
    </tr>
  </thead>
  <tbody>
    @foreach($nomenclatures as $nomenclature)
    @php
      $nPrice = $nomenclature->nPriceForClient($clientId);
    @endphp
    <tr>
      <td>{{$nomenclature->name}}</td>
      <td>{{$nPrice->cost ?? null}}</td>
      <td>{{$nPrice->priceType ?? null}}</td>
      <td>{{$nPrice->bagUnits ?? null}}</td>
      <td>{{$nPrice->bagValue ?? null}}</td>
      <td>{{$nomenclature->category ?? null}}</td>
    </tr>
    @endforeach
  </tbody>
</table>