@if ($showForm)
  <tr>
    <td><input type="text" wire:model="nomenclatureName"><button wire:click="addNumenclature">сохранить</button></td>
  </tr>
@endif
</table>
<button class="btn btn-sm btn-primary m-3" wire:click="toogleForm">Добавить</button>
