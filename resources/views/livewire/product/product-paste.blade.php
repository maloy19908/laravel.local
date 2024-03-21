<div>
  @if ($copied)
  скопированных - {{count($copied)}}
    <div class="m-3">
      <button class="btn btn-success" wire:click=paste><i class="bi bi-clipboard-check-fill"></i></button>
      <button class="btn btn-danger" wire:click=clear><i class="bi bi-clipboard-x"></i></button>
    </div>
  @endif
</div>
