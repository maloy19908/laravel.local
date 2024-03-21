<div>
    @include('layouts.alerts')
    <div>
        @foreach ($groops as $group => $items)
        <div class="card m-2">
            <div class="card-header text-uppercase">{{ $group }}</div>
            @foreach ($items as $el)
                @php
                    $nomenclaturePrice = $el->nPriceForClient($client_id);
                @endphp
                <div class="border-bottom p-1">
                    <div class="row">
                        <div class="col-1">{{ $loop->iteration }}</div>
                        <div class="col-5">{{ $el->name }}</div>
                        <div class="col-3">
                            @if (empty($nomenclaturePrice))
                                <input class="form-control" type="text"
                                    wire:model.debounce.500ms="prices.{{ $el->id }}.cost">
                                @if (isset($prices[$el->id]) && !empty($prices[$el->id]))
                                    @include('livewire.nomenclature.inc.select')
                                @endif
                            @else
                                @if ($editingID == $el->id)
                                    <input type="text" wire:model.debounce.500ms="prices.cost">
                                    @include('livewire.nomenclature.inc.select')
                                @else
                                    {{ $nomenclaturePrice->cost }}
                                    ({{ $nomenclaturePrice->priceType }})
                                    <strong class="text-danger" wire:click="clearPrice({{ $el->id }})"><i
                                            class="bi bi-x-circle-fill"></i></strong>
                                @endif
                            @endif
                        </div>
                        <div class="col-3">
                            @if ($editingID == $el->id)
                                <button class="btn btn-sm btn-success" wire:click="updatePrice({{ $el->id }})"
                                    @empty($prices['cost']) disabled @endempty><i
                                        class="bi bi-floppy-fill"></i></button>
                            @else
                                <button class="btn btn-sm btn-success" wire:click="addPrice({{ $el->id }})"
                                    @empty($prices[$el->id]) disabled @endempty><i
                                        class="bi bi-floppy-fill"></i></button>
                            @endif
                            <button class="btn btn-sm btn-warning" wire:click="editPrice({{ $el->id }})"
                                @unless ($nomenclaturePrice) disabled @endunless><i
                                    class="bi bi-pencil-fill"></i></button>
                            @if (!isset($el->client_id))
                                <button class="btn btn-sm btn-danger" disabled><i class="bi bi-archive-fill"></i></button>
                            @else
                                <button class="btn btn-sm btn-danger"
                                    wire:click="deleteNomenclatureClient({{ $el->id }})"><i
                                        class="bi bi-archive-fill"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
