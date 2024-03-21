<div>
  @include('layouts.alerts')
  <div class="my-2">
    <h2>импорт цен</h2>
    <form method="post" enctype="multipart/form-data" action="{{ route('prices.import') }}">
      @csrf
      <input type="file" name="exel">
      <input id="import" class="btn btn-success" type="submit" value="Загрузить цены">
    </form>
  </div>
  <form>
    <select class="form-select form-select-sm mb-3" name="client" wire:model="client_id">
      <option value="">выбрать</option>
      @foreach ($clients as $client)
        <option value="{{ $client->id }}">{{ $client->name }}</option>
      @endforeach
    </select>
  </form>
  @foreach ($groops as $groop => $elements)
    <div class="table-responsive">
      @if ($groop == 'за мешок')
        <div class="bg-info text-center fs-6 py-1">{{ $groop }}</div>
      @endif
      <table class="table table-sm table-striped table-bordered table-hover">
        <thead>
          <tr class="d-flex">
            <td class="col-1">#</td>
            <td class="col-2">Имя</td>
            <td class="col">Цена</td>
            <td class="col">Ед. изм.</td>
            <td class="col">вес</td>
            <td class="col">шт</td>
            <td class="col">Изменить</td>
            <td class="col">Категория</td>
            <td class="col">Клиент</td>
            <td class="col align-self-end">удалить</td>
          </tr>
        </thead>
        @foreach ($elements as $el)
          <tr class="d-flex">
            <td class="col-1">{{ $el->id }}</td>
            <td class="col-2">{{ $el->name }}</td>
            <td class="col">
              @if ($edit && $target == $el->id)
                <input type="text" wire:model.defer="price.cost" wire:keydown.enter="store({{ $el->id }})">
              @else
                {{ $el->cost }}
                <a class="link-opacity-75" wire:click="update({{ $el->id }})"><i
                    class="bi bi-pencil-fill"></i></a>
              @endif
            </td>
            <td class="col">{{ $el->priceType }}</td>
            <td class="col">{{ $el->bagUnits }}</td>
            <td class="col">{{ $el->bagValue }}</td>
            <td class="col">
              @if ($edit && $target == $el->id)
                <a class="btn btn-sm btn-success" wire:click="store({{ $el->id }})"><i
                    class="bi bi-pencil-fill"></i>
                  Сохранить</a>
              @else
                <a href="{{ route('price_edit', [
                    'price' => $el,
                ]) }}"
                  class="btn btn-sm btn-warning" wire:click=""><i class="bi bi-pencil-fill"></i>
                  Изменить</a>
              @endif
            </td>
            <td class="col">{{ $el->category->name ?? 'Общая' }}</td>
            <td class="col">{{ $el->client->name ?? 'Без клиента' }}</td>

            <td class="col align-self-end">
              <button class="btn btn-sm btn-danger" wire:click="delete({{ $el->id }})">
                <i class="bi bi-trash3"></i> Удалить
              </button>
            </td>
          </tr>
        @endforeach
      </table>
    </div>
  @endforeach
  <a class="btn btn-primary"
    href="{{ route('price_create', [
        'client_id' => $client_id,
        'category_id' => request('category_id'),
    ]) }}">Добавить</a>
</div>
