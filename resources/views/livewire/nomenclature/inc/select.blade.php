<div>
  <select class="form-control form-select-sm mb-1" wire:model.lazy="prices.goodsSubType">
    <option selected value="">Выбрать</option>
    @foreach ($fields as $opt => $name)
      <option value="{{ $opt }}">{{ $opt }}</option>
    @endforeach
  </select>
          @error('prices.goodsSubType') <span class="text-danger">{{ $message }}</span> @enderror
  @if(!empty($prices['goodsSubType']))
    @if (is_array($fields[$prices['goodsSubType']]))
      <select wire:model.lazy="prices.priceType" class="form-control form-select-sm mb-1">
        <option selected value="">Выбрать</option>
        @foreach ($fields[$prices['goodsSubType']] as $opt => $name)
          <option value="{{ $opt }}">
            @if (!is_array($name))
              {{ $name }}
            @else
              {{ $opt }}
            @endif
          </option>
        @endforeach
      </select>
        @error('prices.priceType') <span class="text-danger">{{ $message }}</span> @enderror
    @endif
  @endif
 
  @if (!empty($prices['priceType']) && isset($prices['goodsSubType']))
    @if (is_array($fields[$prices['goodsSubType']][$prices['priceType']]))
      <select wire:model.lazy="prices.bagUnits" class="form-control form-select-sm mb-1">
        <option selected value="">Выбрать</option>
        @foreach ($fields[$prices['goodsSubType']][$prices['priceType']] as $opt => $name)
          <option value="{{ $opt }}">{{ $name }}</option>
        @endforeach
      @elseif($prices['priceType'] == 'за упаковку')
        <input wire:model.lazy="prices.bagValue" class="form-control form-control-sm mb-1" type="text"
          value="" placeholder="шт в упаковке">
    @endif
    </select>
  @endif
  @if (isset($prices['bagUnits']) &&
          $prices['bagUnits'] != '' &&
          isset($prices['goodsSubType']) &&
          isset($prices['priceType']))
    <input wire:model.lazy="prices.bagValue" class="form-control form-control-sm mb-1" type="text" value=""
      placeholder="количество">
  @endif
</div>
