<div id="product-index">
    @livewire('product.product-export-for-user')
    <h2>Поиск по товарам</h2>
    @unless (auth()->user()?->hasRole('admin'))
        <form method="post" enctype="multipart/form-data" action="{{ route('products.import', ['user' => auth()->id()]) }}">
            @csrf
            <input type="file" name="exel">
            <input id="import" class="btn btn-success" type="submit" value="Import" hidden>
        </form>
        <label class="btn btn-success mt-3" for="import">Загрузить Авито exel</label>
    @endunless

    <form class="justify-content-between mt-3 border border-primary rounded" wire:submit.prevent="search">
        @livewire('towns.towns-index')
        @php
            $inputFields = [
                ['name' => 'avitoId', 'placeholder' => 'искать по avitoId', 'wireModel' => 'query.avitoId'],
                ['name' => 'title', 'placeholder' => 'искать по тайтл', 'wireModel' => 'query.title'],
                ['name' => 'category', 'placeholder' => 'искать по Category', 'wireModel' => 'query.category'],
                ['name' => 'address', 'placeholder' => 'искать по Адресу текст', 'wireModel' => 'query.address'],
            ];
        @endphp
        <div class="d-flex flex-wrap align-items-center p-2">
            @foreach ($inputFields as $field)
                <div class="flex-fill my-1">
                    <input class="form-control form-control-sm" type="text" name="{{ $field['name'] }}"
                        placeholder="{{ $field['placeholder'] }}" wire:model.lazy="{{ $field['wireModel'] }}">
                </div>
            @endforeach
            <div class="flex-fill my-2">
                <select class="form-select form-select-sm " name="contactphone" wire:model.lazy="query.phone">
                    <option value="">пусто</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->phone_personal }}">{{ $client->phone_personal }}--{{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-fill my-2">
                <input type="submit" class="btn btn-primary btn-sm" type="submit" value="искать">
                @if (isset($query) || !empty($selected))
                    <input type="button" class="btn btn-danger btn-sm" wire:click="clearQuery()" value="очистить">
                @endif
            </div>
        </div>
    </form>
    <table class="table table-striped table-bordered table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col"><input type="checkbox" wire:model="selectedAll" /></th>
                <th scope="col">#</th>
                <th scope="col">AvitoId</th>
                <th scope="col">AvitoMyId</th>
                <th scope="col">Price</th>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">address</th>
                <th scope="col">phone_personal</th>
                <th scope="col">действие</th>
                <th scope="col">
                    <a href="#" wire:click.prevent="toggleStatus">статус</a>
                    @if ($showStatus)
                        <form wire:submit.prevent="filter">
                            @foreach ($statusOptions as $value => $label)
                                <div>
                                    <label>
                                        <input type="checkbox" wire:model="query.productStatus.{{ $value }}"
                                            value="{{ $label }}"> {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                            <button type="submit">Применить</button>
                        </form>
                    @endif
                </th>
                <th scope="col">скопировать</th>
                <th scope="col">listingFee</th>
            </tr>
        </thead>
        <tbody>
            Всего:{{ $products->total() }}
            @if (!empty($selected) || !empty(session()->has('copiedProducts')))
                <div class="col-3 m-3">
                    @if (session()->has('copiedProducts'))
                        <div>скопировано: {{ count(session('copiedProducts')) }}</div>
                    @endif
                    <button class="btn btn-sm btn-primary" wire:click="copy">скопировать</button>
                    @if (isset($query['phone']) && !empty($query['phone']))
                        <button class="btn btn-sm btn-success" wire:click="paste">вставить</button>
                    @endif
                    <button class="btn btn-sm btn-danger" wire:click="clearSession">очистить</button>
                </div>
                <div class="col-3 m-3">
                    <form>
                        <select class="form-select form-select-sm m-2" wire:model="status">
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-success btn-sm m-2" wire:click="changeSelected">применить</button>
                    </form>
                </div>
            @endif
            @foreach ($products as $product)
                @php

                    if (isset($showForm[$product->id]) && $showForm[$product->id]) {
                        $show = true;
                    } else {
                        $show = false;
                    }
                @endphp
                <tr @if ($product->trashed) class="table-danger" @endif>
                    <td class="col" scope="row"><input type="checkbox"
                            wire:model.live="selected.{{ $product->id }}" /></td>
                    <td class="col" scope="row">{{ $loop->iteration }}</td>
                    <td class="col">{{ $product->avito_id ?? '' }}</td>
                    <td class="col">{{ $product->my_id ?? '' }}</td>
                    <td class="col" style="color:green;font-weight:bold;">{{ $product?->cost ?? '' }}</td>
                    <td class="col">{{ $product->title ?? '' }}</td>
                    <td class="col">
                        {{ $product->category_name ?? '' }}
                    </td>
                    <td class="col">
                        @isset($product->address_street)
                            {{ $product->address_street }}
                        @endisset
                    </td>
                    <td class="col"><a
                            href="{{ route('clients.show', $product->client_id) }}">{{ $product->phone_personal ?? '' }}</a>
                    </td>
                    <td class="col">
                        <a class="badge bg-warning px-2 m-1 text-dark"
                            href="{{ route('products.edit', $product->id) }}" target="_blank"><i
                                class="bi bi-pencil-fill"></i>
                        </a>
                    </td>
                    @if ($product->trashed)
                        <td colspan="3" class=" text-uppercase">Удалено {{ $product->trashed }}</td>
                    @else
                        <td>
                            @if ($show)
                                <button class="btn-close btn-sm"
                                    wire:click="$toggle('showForm.{{ $product->id }}')"></button>
                                <form wire:submit.prevent="createDateBegin({{ $product->id }})">
                                    <input class="form-control" name="date" type="date"
                                        wire:model="dateBegin.{{ $product->id }}"
                                        value="{{ $product->dateBegin ?? '' }}">
                                    @error('dateBegin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary btn-sm" type="submit"><i
                                                class="bi bi-clock-history"></i></button>
                                    </div>
                                </form>
                            @else
                                <span
                                    @if (!$product->dateBegin && $product->productStatus != 'Активно') class="badge data-status bg-danger" @else class="badge data-status bg-primary" @endif>
                                    <span class="badge bg-warning px-2 m-1 text-dark "
                                        wire:click="$toggle('showForm.{{ $product->id }}')"><i
                                            class="bi-pencil-fill"></i></span>
                                    {{ $product->dateBegin ?? '' }}
                                    {{ $product->productStatus ?? '' }}
                                    @if ($product->dateBegin || $product->productStatus == 'Активно')
                                        <span class="badge bg-danger px-2 m-1"
                                            wire:click="confirmAction({{ $product->id }})"><i
                                                class="bi bi-x-lg"></i></span>
                                    @endif
                                </span>
                            @endif
                        </td>
                        <td>
                            <div wire:click="copy({{ $product->id }})" class="btn btn-outline-primary btn-sm"><i
                                    class="@if (in_array($product->id, $copiedProducts)) bi bi-clipboard-minus text-danger @else bi bi-clipboard-plus-fill @endif"></i>
                                <div class="spinner-border spinner-border-sm" role="status" wire:loading
                                    wire:target="copy({{ $product->id }})">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    wire:click="toggleListing({{ $product->id }})"
                                    @if ($product->listingFee == 'Package+Single') checked @endif>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $products->onEachSide(1)->links() }}
    </div>
</div>
@section('scripts')
    <script>
        Livewire.on('confirmAction', (id) => {
            if (confirm('Вы уверены, что хотите удалить запись?')) {
                Livewire.emit('removeDateBegin', id);
            }
        });
    </script>
@endsection
@section('style')
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        @media only screen and (max-width: 600px) {
            .table {
                border: 0;
            }

            .table thead {
                display: flex;
            }

            .table th {
                display: block;
            }
            .table tr {
                border-bottom: 1px solid #ddd;
                display: block;
                margin-bottom: 10px;
                width: 100%;
            }

            .table td {
                border-bottom: 0;
                display: block;
                text-align: left;
            }
            .table td a {
                padding: 10px;
                min-width: 70px;
            }

            .table td .data-status {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: baseline;
                padding: 10px;
            }
            .table td .data-status span{
                padding: 10px;
                min-width: 70px;
            }

            .table td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
            }

            
        }
    </style>
@endsection
