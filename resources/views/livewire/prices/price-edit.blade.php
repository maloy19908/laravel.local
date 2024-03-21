<div>
  @include('layouts.alerts')
  <div class="d-flex justify-content-end">
    <a class="btn btn-danger mb-3 fs-6" wire:click="delete">
      <i class="bi bi-x"></i>
    </a>
  </div>
  <form wire:submit.prevent="save">
    <div class="input-group input-group-sm mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Имя</span>
      </div>
      <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
        wire:model.lazy="price.name">
    </div>
    <div class="input-group input-group-sm mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Цена</span>
      </div>
      <input type="text" class="form-control" aria-label="Sizing example input"
        aria-describedby="inputGroup-sizing-sm" wire:model.lazy="price.cost">
    </div>
    <select class="form-control mb-3" wire:model.lazy=price.goodsSubType>
      <option selected value="">Выбрать</option>
      @foreach ($fields as $el => $name)
        <option value="{{ $el }}">{{ $el }}</option>
      @endforeach
    </select>
    @if (!empty($price->goodsSubType))
      @if (is_array($fields[$price->goodsSubType]))
        <select wire:model.lazy="price.priceType" class="form-control mb-3">
          <option selected value="">Выбрать</option>
          @foreach ($fields[$price->goodsSubType] as $el => $name)
            <option value="{{ $el }}">
              @if (!is_array($name))
                {{ $name }}
              @else
                {{ $el }}
              @endif
            </option>
          @endforeach
        </select>
      @endif
    @endif
    @if (!empty($price->priceType) && isset($price->goodsSubType))
      @if (is_array($fields[$price->goodsSubType][$price->priceType]))
        <select wire:model.lazy="price.bagUnits" class="form-control mb-3">
          <option selected value="">Выбрать</option>
          @foreach ($fields[$price->goodsSubType][$price->priceType] as $el => $name)
            <option value="{{ $el }}">{{ $name }}</option>
          @endforeach
        @elseif($price->priceType == 'за упаковку')
          <input wire:model.lazy="price.bagValue" class="form-control mb-3" type="text" value=""
            placeholder="шт в упаковке">
        @else
          <button class="btn btn-success mb-3" type="submit">сохранить</button>
      @endif
      </select>
    @endif
    @if ($price->bagUnits != '' && isset($price->goodsSubType) && isset($price->priceType))
      <input wire:model.lazy="price.bagValue" class="form-control mb-3" type="text" value=""
        placeholder="количество">
    @endif
    @if (!empty($price->bagValue) && isset($price->goodsSubType) && isset($price->priceType))
      <button class="btn btn-success mb-3" type="submit">сохранить</button>
    @endif
    <a class="btn btn-primary mb-3" href="{{ $referer }}"><i class="bi bi-arrow-return-left"></i> Вернутся</a>
  </form>

</div>
