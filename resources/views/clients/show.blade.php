@extends('layouts.app')

@section('content')
    @include('layouts.alerts')
    <h1 class="text-center">
        Данные клиента {{ $client->name }}</h1>
    <div class="row mb-3">
        {{-- Левая сторона --}}
        <div class="col-4">
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between align-items-center bg-warning">
                    <strong>Имя:</strong>
                    <select class=" mx-2 form-select form-select-sm" name="clients" onchange="location = this.value;">
                        @foreach ($clients as $cl)
                            <option value="{{ route('clients.show', $cl->id) }}"
                                @if ($cl->id == $client->id) selected @endif>{{ $loop->index }}) {{ $cl->name }}
                                @isset($cl->recognize)
                                    --{{ $cl->recognize }}
                                @endisset
                            </option>
                        @endforeach
                    </select>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Личный телефон:</strong>{{ $client->phone_personal }}
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Личный телефон 2й:</strong>{{ $client->phone_personal_other }}
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Авито url:</strong><a href="{{ $client->avito_url }}">{{ $client->avito_url }}</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Личный сайт:</strong><a href="http://{{ $client->site }}">{{ $client->site }}</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>@email:</strong>{{ $client->email }}
                </li>
            </ul>
            <a class="btn btn-warning btn-sm" href="{{ route('clients.edit', $client->id) }}"><i
                    class="bi bi-pencil-fill"></i></a>
            {{-- Меню --}}
            <div class="bg-white shadow-sm p-3 rounded">
                @include('clients.menu.index')
            </div>
            {{-- Меню --}}
        </div>
        {{-- Левая сторона --}}
        {{-- Правая сторона --}}
        <div class="col-8">
            @livewire('product.product-export-for-client', [
                'clientOfUserID' => $client->id,
            ])
            <hr>
            <div class="bg-white shadow-sm p-3 rounded m-3">
                <p class="text-center text-bold">Сформировать отчет</p>
                <a class="btn btn-success" href="{{ route('products.export.exel', ['client_id' => $client->id]) }}"><i
                        class="bi bi-file-arrow-down"></i>Exel</a>
                <a class="btn btn-warning" href="{{ route('products.export.pdf', ['client_id' => $client->id]) }}"><i
                        class="bi bi-filetype-pdf"></i>PDF</a>
            </div>
            @livewire(
                'nomenclature.nomenclature-index',
                [
                    'clientId' => $client->id,
                ],
                key('nomenclature-index' . $client->id)
            )
            {{-- ШОРТКОДЫ --}}
            <a class="btn btn-primary"
                href="{{ route('shortcodes.create', [
                    'client_id' => $client,
                ]) }}">создать
                шорткод</a>
            @if (request('categoryId'))
                <a class="btn btn-primary"
                    href="{{ route('products.create', [
                        'client_id' => $client,
                        'category_id' => request('categoryId'),
                    ]) }}">создать
                    товар</a>
            @endif
            <div class="accordion my-3">
                <div class="accordion-item bg-white">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed p-3 bg-secondary text-white" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne-shortcode" aria-expanded="true"
                            aria-controls="collapseOne-shortcode">
                            Шорткоды
                        </button>
                    </h2>
                    <div id="collapseOne-shortcode" class="accordion-collapse collapse"
                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="d-flex flex-wrap flex-row mb-3 ">
                                @foreach ($shortcodes as $shortcode)
                                    <div class="p-2">&#91;{{$shortcode->name}}&#93;</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ШОРТКОДЫ --}}
            @if (request('categoryId'))
                @if (count($products))
                    @foreach ($products as $name => $uniq)
                        <div class="accordion my-3">
                            <div class="accordion-item bg-white">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed p-1" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $loop->index }}" aria-expanded="true"
                                        aria-controls="collapseOne-{{ $loop->index }}">
                                        {{ $name }}
                                    </button>
                                </h2>
                                <div id="collapseOne-{{ $loop->index }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>Массовое присвоение цены</strong>
                                        <form method="POST"
                                            action="{{ route('products.createNpriceLink', [
                                                'client_id' => $client->id,
                                                'category_id' => request('categoryId'),
                                            ]) }}">
                                            @method('POST')
                                            @csrf
                                            <select class="form-select form-select-sm my-2" size="3"
                                                name="product_id[]" multiple>
                                                @foreach ($uniq as $product)
                                                    <option value={{ $product->id }}>{{ $product->my_id }}</option>
                                                @endforeach
                                            </select>
                                            <select class="form-select form-select-sm my-2" name="nomenclature_id">
                                                <option value=-1>Убрать цену</option>
                                                @foreach ($nPrices as $nPrice)
                                                    <option value={{ $nPrice->nomenclature_id }}>
                                                        {{ $nPrice->nomenclature->name }}--{{ $nPrice->cost }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary btn-sm my-2" title="сохранить"><i
                                                    class="bi bi-save-fill"></i>
                                                Cохранить</button>
                                        </form>
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>avitoID</th>
                                                    <th>myID</th>
                                                    <th>Тайтл</th>
                                                    <th>Цена</th>
                                                    <th>Выбор цены</th>
                                                    <th colspan="2">Действие</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($uniq as $product)
                                                    <tr class="@if ($product->trashed()) bg-secondary @endif">
                                                        <td>{{ $product->avito_id }}</td>
                                                        <td>{{ $product->my_id }}</td>
                                                        <td>{{ $product->ProductUniqTitle->title }}</td>
                                                        <td class="text-danger fw-bold">{{ $product->price->cost ?? '' }}
                                                        </td>
                                                        <td>
                                                            <form class="d-flex" method="POST"
                                                                action="{{ route('products.createNpriceLink', [
                                                                    'client_id' => $client->id,
                                                                    'product_id' => [$product->id],
                                                                    'category_id' => request('categoryId'),
                                                                ]) }}">
                                                                @method('POST')
                                                                @csrf
                                                                <select class="form-select form-select-sm"
                                                                    name="nomenclature_id">
                                                                    <option value=-1>
                                                                        {{ $product->nomenclature_id ? 'Убрать цену' : 'Выбрать цену' }}
                                                                    </option>
                                                                    @foreach ($nPrices as $nPrice)
                                                                        <option value="{{ $nPrice->nomenclature_id }}"
                                                                            @if (isset($product->nomenclature_id) &&
                                                                                    $product->nomenclature_id == $nPrice->nomenclature_id &&
                                                                                    $nPrice->client_id == $product->client_id) selected @endif>
                                                                            {{ $nPrice->nomenclature->name }}--{{ $nPrice->cost }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button class="btn btn-primary btn-sm ms-1"
                                                                    title="сохранить"><i
                                                                        class="bi bi-save-fill"></i></button>
                                                            </form>
                                                        </td>

                                                        <td colspan="2" class="d-flex">
                                                            <a class="btn btn-warning btn-sm"
                                                                href="{{ route('products.edit', $product) }}"><i
                                                                    class="bi bi-pencil-fill" title="изменить"></i></a>
                                                        </td>
                                                        <td>@livewire('product.product-status', ['product' => $product->id], key('product-status-' . $product->id))
                                                        </td>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
        {{-- Правая сторона --}}
    </div>

@endsection
@section('scripts')
@endsection
