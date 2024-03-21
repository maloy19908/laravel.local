<div>
   @if ($clientOfUserID)
       @if ($loading)
           <div class="spinner-border spinner-border-sm" role="status">
               <span class="visually-hidden">Loading...</span>
           </div>
           <p class="text-warning">Идет формирование файла</p>
           <div wire:poll.5s="searchFile"></div>
       @else
           @if ($file)
               <button wire:click="download">скачать</button>
           @endif
           <div class="form-check">
               <input class="form-check-input" type="checkbox" wire:model="filter.active">
               <label class="form-check-label" for="flexCheckDefault">
                  active
               </label>
           </div>
           {{-- <div class="form-check">
               <input class="form-check-input" type="checkbox" wire:model="filter.arhive">
               <label class="form-check-label" for="flexCheckDefault">
                  arhive
               </label>
           </div> --}}
           <button class="btn btn-sm btn-success" type="submit" wire:loading.attr="disabled" wire:click="export"
               @if (!$filter) disabled @endif>
               <i class="bi bi-file-arrow-down"></i>
               <span>сформировать exel</span>
           </button>
       @endif
   @endif
</div>