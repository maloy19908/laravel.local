<div>
<table class="table table-sm table-striped table-bordered table-hover">
        <thead>
            <tr class="d-flex">
                <td class="col-1">#</td>
                <td class="col-2">Имя</td>
                <td class="col">Цена</td>
                <td class="col">Ед. Измерения</td>
                <td class="col">измеить</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($prices as $price)
            {{-- Нужно поправить удалить этот модуль сделать другой --}}
            @livewire('prices.price-client-list', ['price' => $price, 'allFields' => false], key($price->id))
            @endforeach
        </tbody>
    </table>

    @if ($category_id)
        @if (count($products))
            
        @endif
    @endif

</div>