<tr class="@if($groopName=='в мешках') table-warning @endif">
  @php
    $priceCost = $nomenclature->nPriceForClient($clientId);
  @endphp
  <td class="col">{{ $iteration}}</td>
  <td class="col">{{ $nomenclature->name }}</td>
  <td class="col">
    @if (isset($priceCost))
      @if (!$edit)
        {{ $priceCost->cost ?? '' }}
      @else
        <input type="text" wire:model.live="priceType.cost">
       @error('priceType.cost') <div class="text-danger">{{ $message }}</div> @enderror
        @include('livewire.nomenclature.inc.price-type-selector')
      @endif
    @else
    <input type="text" wire:model.live="priceType.cost">
      @if(isset($priceType['cost']))
      @include('livewire.nomenclature.inc.price-type-selector')
      @endif
    @endif
  </td>
    <td class="col">
      <button class="btn btn-sm btn-success" wire:click="addPrice"@empty($priceType['cost']) disabled @endempty ><i
        class="bi bi-floppy-fill"></i></button>
      <button class="btn btn-sm btn-warning" wire:click="editPrice" @if(!isset($priceCost->cost)) disabled @endif><i
        class="bi bi-pencil-fill"></i></button>
      @if(!isset($nomenclature->client_id))
      <button class="btn btn-sm btn-danger" disabled><i class="bi bi-archive-fill"></i></button>
      @else
      <button class="btn btn-sm btn-danger" wire:click="deleteNomenclature"><i class="bi bi-archive-fill"></i></button>
      @endif
    </td>
</tr>
