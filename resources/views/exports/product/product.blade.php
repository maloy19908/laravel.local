<table border="1">
  <thead >
    <tr style="color: #fcf18f">
      @foreach ($rowMax as $row => $value)
        <th scope="col">{{ $row }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($products as $product)
      @php
        $fields = $product->productField->options;
        $cost = optional($product->nomenclature)
        ->nPrices?->where('client_id', $product->client_id)
        ->first()->cost ?? '';
      @endphp
      <tr>
        @foreach ($rowMax as $col => $row)
          {{-- ВЫВОД НУЖНЫХ ПОЛЕЙ --}}
          @if ($col == 'avitoid')
            <td>{{ $product->avito_id ?? '' }}</td>
          @elseif($col == 'id')
            <td>{{ $product->my_id ?? '' }}</td>
          @elseif($col == 'title')
            <td>{{ $product->productUniqTitle->title ?? '' }}</td>
          @elseif($col == 'price')
            <td @isset($product->nomenclature) style="color:green;font-weight:bold;" @endisset>
              {{ $cost ? $cost : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'pricetype')
            <td @isset($product->nPrice->priceType) style="color:green;font-weight:bold;" @endisset>
              {{ $product->nPrice?->priceType ? $product->nPrice->priceType : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'description')
            <td @isset($product->id) style="color:green;font-weight:bold;" @endisset>
              {{ $modifyDescriptions ? $modifyDescriptions[$product->id] : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'bagunits')
            <td @isset($product->nPrice->bagUnits) style="color:green;font-weight:bold;" @endisset>
              {{ $product->nPrice?->bagUnits ? $product->nPrice->bagUnits : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'bagvalue')
            <td @isset($product->nPrice->bagValue) style="color:green;font-weight:bold;" @endisset>
              {{ $product->nPrice?->bagValue ? $product->nPrice->bagValue : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'datebegin')
            <td @isset($product->dateBegin) style="color:green;font-weight:bold;" @endisset>
              {{ $product?->dateBegin ? $product->dateBegin : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'avitostatus')
            <td @if(isset($product->productStatus) && isset($product->dateBegin)) style="color:green;font-weight:bold;" @endif>
              {{ $product?->productStatus ? $product->productStatus : $fields[$col] ?? '' }}
            </td>
          @elseif($col == 'category')
            <td>
              {{ $product->category->parents->pluck('name', 'type')['category'] ?? '' }}
            </td>
          @elseif($col == 'goodstype')
            <td>
              {{ $product->category->parents->pluck('name', 'type')['goodstype'] ?? '' }}
            </td>
          @elseif($col == 'goodssubtype')
            <td>
              {{ $product->category->parents->pluck('name', 'type')['goodssubtype'] ?? '' }}
            </td>
          @elseif($col == 'bulkmaterialtype')
            <td>
              {{ $product->category->parents->pluck('name', 'type')['bulkmaterialtype'] ?? '' }}
            </td>
          @elseif($col == 'contactphone')
            <td>{{ $product->client->phone_personal ?? '' }}</td>
          @elseif($col == 'listingfee')
            <td @isset($productListingFee[$product->id]) style="color:green;font-weight:bold;" @endisset>
              {{ $productListingFee[$product->id] ?? 'Package' }}
            </td>
          @elseif($col == 'address')
            <td>
              @if (isset($product->town->parents))
                @foreach ($product->town->parents as $adr)
                  {{ $adr->name }} @if (!$loop->last)
                    ,
                  @endif
                @endforeach
              @else
                {{ $product->address_street ?? '' }}
              @endif
            </td>
            {{-- !ВЫВОД НУЖНЫХ ПОЛЕЙ --}}
          @else
            {{-- ВЫВОД ОСТАЛЬНЫХ ПОЛЕЙ --}}
            <td>{{ $fields[$col] ?? '' }}</td>
            {{-- !ВЫВОД ОСТАЛЬНЫХ ПОЛЕЙ --}}
          @endif
        @endforeach
      </tr>
    @endforeach
  </tbody>
</table>
