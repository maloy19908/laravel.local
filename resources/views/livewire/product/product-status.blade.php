<div>
    @if ($showForm)
        <button class="btn-close btn-sm" wire:click="$toggle('showForm')"></button>
        <form wire:submit.prevent="createDateBegin({{ $product->id }})">
            <input class="form-control" name="date" type="date" wire:model.defer="dateBegin"
                value="{{ $product->dateBegin ?? '' }}">
            @error('dateBegin')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-clock-history"></i></button>
            </div>
        </form>
    @else
        <span
            @if (!$product->dateBegin && $product->productStatus != 'Активно') class="badge data-status bg-danger" @else class="badge data-status bg-primary" @endif>
            <span class="badge bg-warning px-2 m-1 text-dark " wire:click="$toggle('showForm.{{ $product->id }}')"><i
                    class="bi-pencil-fill"></i></span>
            {{ $product->dateBegin ?? '' }}
            {{ $product->productStatus ?? '' }}
            @if ($product->dateBegin || $product->productStatus == 'Активно')
                <span class="badge bg-danger px-2 m-1" wire:click="removeDateBegin"><i
                        class="bi bi-x-lg"></i></span>
            @endif
        </span>
    @endif
</div>
