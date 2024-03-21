<div>
    @include('layouts.alerts')
    <div>
        <div class="row">
            <form class="col-9 mb-3" wire:submit.prevent="addNPriceList">
                <div class="row">
                    <div class="col">
                        <input class="form-control form-control-sm" type="file" wire:model="file"
                            enctype="multipart/form-data">
                    </div>
                    <div class="col">
                        <button class="btn btn btn-sm btn-success" wire:loading.attr="disabled" type="submit"
                            :disabled="!file">Загрузить файл</button>
                    </div>
                </div>
            </form>
            <div class="col-3 mb-3">
                <button class="btn btn btn-sm btn-success" wire:click="downloadPriceList">скачать Прайс-лист</button>
            </div>
        </div>
        <div class="overflow-auto m-2" style="max-height: 300px;">
            @livewire('nomenclature.nomenclature-client', ['client_id' => $clientId])
        </div>
        @if ($show)
            <input type="text" wire:model.defer="nomenclatureName" placeholder="цена">
            <input type="text" wire:model.defer="nomenclatureCategory" placeholder="категория">
            <button class="btn btn btn-sm btn-success" wire:click="saveNomenclature">Сохранить</button>
        @endif
    </div>
    <button class="btn btn-sm btn-primary m-3" wire:click="toogleForm"><i class="bi bi-plus-circle"></i> Создать
        цену</button>
</div>
