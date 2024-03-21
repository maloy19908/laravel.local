<div>
    <select class="form-control form-select-sm mb-1" wire:model.lazy=price.goodsSubType>
      <option selected value="">Выбрать</option>
      @foreach ($fields as $el => $name)
        <option value="{{ $el }}">{{ $el }}</option>
      @endforeach
    </select>
    @if (!empty($price['goodsSubType']))
      @if (is_array($fields[$price['goodsSubType']]))
        <select wire:model.lazy="price.priceType" class="form-control form-select-sm mb-1">
          <option selected value="">Выбрать</option>
          @foreach ($fields[$price['goodsSubType']] as $el => $name)
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
    @if (!empty($price['priceType']) && isset($price['goodsSubType']))
      @if (is_array($fields[$price['goodsSubType']][$price['priceType']]))
        <select wire:model.lazy="price.bagUnits" class="form-control form-select-sm mb-1">
          <option selected value="">Выбрать</option>
          @foreach ($fields[$price['goodsSubType']][$price['priceType']] as $el => $name)
            <option value="{{ $el }}">{{ $name }}</option>
          @endforeach
        @elseif($price['priceType'] == 'за упаковку')
          <input wire:model.lazy="price.bagValue" class="form-control form-control-sm mb-1" type="text" value=""
            placeholder="шт в упаковке">
        @else
          <button class="btn btn-sm btn-success mb-1" wire:click="savePriceType">сохранить</button>
      @endif
      </select>
    @endif
    @if (isset($price['bagUnits']) && $price['bagUnits'] != '' && isset($price['goodsSubType']) && isset($price['priceType']))
      <input wire:model.lazy="price.bagValue" class="form-control form-control-sm mb-1" type="text" value=""
        placeholder="количество">
    @endif
    @if (!empty($price['bagValue']) && isset($price['goodsSubType']) && isset($price['priceType']))
      <button class="btn btn-sm btn-success  mb-1" wire:click="savePriceType">сохранить</button>
    @endif
</div>