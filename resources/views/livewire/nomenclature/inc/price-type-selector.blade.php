<div>
  <select class="form-control form-select-sm mb-1" wire:model.lazy="priceType.goodsSubType">
    <option selected value="">Выбрать</option>
    @foreach ($fields as $el => $name)
      <option value="{{ $el }}">{{ $el }}</option>
    @endforeach
  </select>
          @error('priceType.goodsSubType') <span class="text-danger">{{ $message }}</span> @enderror
  @if(!empty($priceType['goodsSubType']))
    @if (is_array($fields[$priceType['goodsSubType']]))
      <select wire:model.lazy="priceType.priceType" class="form-control form-select-sm mb-1">
        <option selected value="">Выбрать</option>
        @foreach ($fields[$priceType['goodsSubType']] as $el => $name)
          <option value="{{ $el }}">
            @if (!is_array($name))
              {{ $name }}
            @else
              {{ $el }}
            @endif
          </option>
        @endforeach
      </select>
        @error('priceType.priceType') <span class="text-danger">{{ $message }}</span> @enderror
    @endif
  @endif
  @if (!empty($priceType['priceType']) && isset($priceType['goodsSubType']))
    @if (is_array($fields[$priceType['goodsSubType']][$priceType['priceType']]))
      <select wire:model.lazy="priceType.bagUnits" class="form-control form-select-sm mb-1">
        <option selected value="">Выбрать</option>
        @foreach ($fields[$priceType['goodsSubType']][$priceType['priceType']] as $el => $name)
          <option value="{{ $el }}">{{ $name }}</option>
        @endforeach
      @elseif($priceType['priceType'] == 'за упаковку')
        <input wire:model.lazy="priceType.bagValue" class="form-control form-control-sm mb-1" type="text"
          value="" placeholder="шт в упаковке">
    @endif
    </select>
  @endif
  @if (isset($priceType['bagUnits']) &&
          $priceType['bagUnits'] != '' &&
          isset($priceType['goodsSubType']) &&
          isset($priceType['priceType']))
    <input wire:model.lazy="priceType.bagValue" class="form-control form-control-sm mb-1" type="text" value=""
      placeholder="количество">
  @endif
</div>
