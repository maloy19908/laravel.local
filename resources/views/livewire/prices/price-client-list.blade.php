    <tr class="d-flex">
        <td class="col-1">
            @if($price->category_id) <i class="bi bi-tags-fill"></i> @endif
            @if(!$price->client) общ @endif
        </td>
        <td class="col-2">
            {{$price->name}}
        </td>
        <td class="col">
            @if($edit)
            <input type="text" wire:model="price.cost">
            @error('price.cost')<span class="text-danger">{{$message}}</span> @enderror
            @else
            <span>{{$price->cost}}</span>
            <a class="link-opacity-75-hover" wire:click="edit"><i class="bi bi-pencil-fill"></i></a>
            @endif
        </td>

        <td class="col">{{ $price->priceType }}</td>
        @if ($allFields)<p></p>
        <td class="col">{{ $price->bagUnits }}</td>
        <td class="col">{{ $price->bagValue }}</td>
        @endif
        <td class="col">
            @if ($edit)
                <a class="btn btn-sm btn-success" wire:click="store"><i class="bi bi-pencil-fill"></i> Сохранить</a>
            @else
                <a class="btn btn-sm btn-warning" wire:click="" href="{{route('price_edit',$price)}}"><i class="bi bi-pencil-fill"></i> Изменить</a>
            @endif
        </td>
        @if ($allFields)
        <td class="col">{{ $price->category->name ?? 'Общая' }}</td>
        <td class="col">{{ $price->client->name ?? 'Без клиента' }}</td>
        <td class="col align-self-end">
            <button class="btn btn-sm btn-danger" type="submit" wire:click="delete({{$price->id}})">
                <i class="bi bi-trash3"></i> Удалить
            </button>
        </td>
        @endif
    </tr>