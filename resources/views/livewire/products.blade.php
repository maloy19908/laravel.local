<div>
  @livewire('product.product-export-for-user')
  <h2>Поиск по товарам</h2>

  @unless(auth()->user()?->hasRole('admin'))
  <form method="post" enctype="multipart/form-data" action="{{ route('products.import', ['user' => $user]) }}">
    @csrf
    <input type="file" name="exel">
    <input id="import" class="btn btn-success" type="submit" value="Import" hidden>
  </form>
  <label class="btn btn-success mt-3" for="import">Загрузить Авито exel</label>
  @endunless

  <a class="btn btn-success mt-3" href="{{ route('products.export.exel') }}">Сформировать файл экспорт</a>

  <form class="justify-content-between mt-3 border border-primary rounded" wire:submit.prevent=search method="get">
    @livewire('towns.towns-index')
    <div class="d-flex">
      @php
      $inputFields = [
      ['name' => 'avitoId', 'placeholder' => 'искать по avitoId', 'wireModel' => 'search.avitoId'],
      ['name' => 'title', 'placeholder' => 'искать по тайтл', 'wireModel' => 'search.title'],
      ['name' => 'category', 'placeholder' => 'искать по Category', 'wireModel' => 'search.category'],
      ['name' => 'address_street', 'placeholder' => 'искать по Адресу текст', 'wireModel' => 'search.address_street'],
      ];
      @endphp
      @foreach ($inputFields as $field)
      <div class=" m-2">
        <input class="form-control form-control-sm" type="text" name="{{ $field['name'] }}"
          placeholder="{{ $field['placeholder'] }}" wire:model.lazy="{{ $field['wireModel'] }}">
      </div>
      @endforeach

      <div class=" m-2">
        <select class="form-select form-select-sm" name="contactphone" wire:model.lazy="search.contactphone">
          <option value="">пусто</option>
          @foreach ($clients as $client)
          <option value="{{ $client->phone_personal }}">{{ $client->phone_personal }}--{{ $client->name }}</option>
          @endforeach
        </select>
      </div>

      <input type="submit" class="btn btn-primary btn-sm mb-2" type="submit" value="искать">

      @if ($search)
      <button class="btn btn-danger btn-sm mx-2 mb-2" wire:click="resetFilter">сброс</button>
      @endif
    </div>
  </form>

  @if (isset($search['contactphone']) && !empty($search['contactphone']))
  @livewire('product.product-paste')
  @endif
  Всего:{{$products->total()}}
  @empty(!$selected)
  <div>
    <form class="" wire:submit.prevent="changeSelected()">
      <select class="form-select form-select-sm m-2" wire:model="status">
        @foreach ($statusOptions as $status)
        <option value="{{$status}}">{{$status}}</option>
        @endforeach
      </select>
      <button class="btn btn-success btn-sm m-2" type="submit">применить</button>
    </form>
  </div>
  @endempty
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
          <a href="#" wire:click.prevent="toggleFilter">статус</a>
          @if ($showFilter)
          <form wire:submit.prevent="filter">
            @foreach ($statusOptions as $value => $label)
            <div>
              <label>
                <input type="checkbox" wire:model="search.productStatus.{{ $value }}" value="{{ $label }}"> {{ $label }}
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
      @if (count($products))
      @foreach ($products as $product)
      @php
      $cost =
      optional($product->nomenclature)
      ->nPrices?->where('client_id', $product->client_id)
      ->first()->cost ?? '';
      @endphp
      <tr @if ($product->deleted_at) class="table-secondary" @endif>
        <td class="col" scope="row"><input type="checkbox" wire:model.debounce.500ms="selected.{{ $product->id }}" />
        </td>
        <td class="col" scope="row">{{ $loop->iteration }}</td>
        <td class="col">{{ $product->avito_id }}</td>
        <td class="col">{{ $product->my_id }}</td>
        <td class="col" style="color:green;font-weight:bold;">
          {{ $cost }}
        </td>
        <td class="col">{{ $product->productUniqTitle->title }}</td>
        <td class="col">
          @isset($product->category->parents)
          <span class="badge bg-primary text-wrap">{{$product->category->name}}</span>
          @endisset
        </td>
        <td class="col-2">
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
        <td class="col-1">{{ $product->client->phone_personal }}</td>
        <td class="col-0">
          <div class="d-flex">
            <a class="btn btn-sm btn-warning mx-1" wire:click="edit({{ $product->id }})"><i
                class="bi bi-pencil-fill"></i></a>
            {{-- <a class="btn btn-sm btn-danger mx-1" wire:click="delete({{ $product->id }})"><i
                class="bi bi-trash3-fill"></i></a> --}}
            @if($product->trashed())
            <span class="badge bg-danger m-1 px-1">удалено !</span>
            @endif
          </div>
        </td>
        <td>@livewire('product.product-status', ['product' => $product->id], key('product-status-' . $product->id))
        </td>
        <td>@livewire('product.product-copy', ['product' => $product->id], key('product-copy-' . $product->id))
        </td>
        <td>
          @if(!$product->trashed())
          @livewire('product.product-listing', ['product' => $product->id], key('product-listing-' . $product->id))
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <ul class="pagination">
    {{ $products->links() }}
  </ul>

  @else
  Нет ничего
  @endif

</div>