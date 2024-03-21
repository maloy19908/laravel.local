<div>
    @include('layouts.alerts')
    <div>
        <form wire:submit.prevent="addNPriceList">
            <input type="file" wire:model="file"enctype="multipart/form-data">
            <button wire:loading.attr="disabled" type="submit" :disabled="!file">Загрузить файл</button>
        </form>
        @foreach ($groops as $group => $items)
            <div class="card m-2">
                <div class="card-header text-uppercase">{{ $group }}</div>
                @foreach ($items as $el)
                    <li class="list-group-item m-1 px-2 border-bottom">
                        <div class="row">
                            @if ($editId == $el->id)
                                <div class="col-7">
                                    <form wire:submit.prevent="update({{ $el->id }})">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">Имя</span>
                                            <input class="form-control form-control-sm" type="text" wire:model="name">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">Категория</span>
                                            <input class="form-control form-control-sm" type="text" wire:model="category">
                                            <button class="btn btn-sm btn-success">Сохранить</button>
                                            <button class="btn btn-sm btn-warning" wire:click="close">отмена</button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="col-1">{{ $loop->iteration }}</div>
                                <div class="col-3">{{ $el->name }}</div>
                                <div class="col-2">{{ $el->category }}</div>
                                <div class="col-2">
																	<div class="input-group">
                                    <button class="btn btn-sm btn-warning" wire:click="edit({{ $el->id }})">
                                        <i class="bi bi-pencil-fill"></i> Изменить
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="remove({{ $el->id }})">
                                        <i class="bi bi-trash3"></i> Удалить
                                    </button>
																	</div>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </div>
        @endforeach
        @if ($showForm)
            <div class="col-3">
                <input class="form-control form-control-sm m-1" type="text" wire:model="nomenclature.name"
                    wire:keydown.enter="save()" placeholder="имя">
                <input class="form-control form-control-sm m-1" type="text" wire:model="nomenclature.category"
                    wire:keydown.enter="save()" placeholder="категория">
                <button class="btn btn-sm btn-success m-1" wire:click="save()"><i class="bi bi-save"></i>
                    сохранить</button>
            </div>
        @endif
        <button class="btn btn-sm btn-secondary" wire:click.prevent="add">Добавить</button>

    </div>
</div>
