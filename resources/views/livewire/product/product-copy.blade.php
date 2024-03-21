<div>
  @if ($thisCopy)
  <div wire:click="copy" class="btn btn-outline-danger btn-sm"><i class="bi bi-clipboard-minus"></i></div>
  @else
  <div wire:click="copy" class="btn btn-outline-primary btn-sm"><i class="bi bi-clipboard-plus-fill"></i></div>
  @endif
</div>