<div>
<ul>
  @foreach ($towns as $town)
    <li>
      <label>
      <input type="radio" wire:model="selectedTown" name="parent_id" value="{{ $town->id }}">
      {{ $town->name }}</label>
      @if ($selectedTown == $town->id)
        @if($town->children->isNotEmpty())
          @livewire('towns.town-childrens', ['towns' => $town->children], key($town->id))
        @endif
      @endif
    </li>
  @endforeach
</ul>
</div>
