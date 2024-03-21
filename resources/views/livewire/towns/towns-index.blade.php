<div>
  <div class="accordion" id="accordionTowns">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
          aria-expanded="true" aria-controls="collapseOne">
          Фильтр по городам
        </button>
      </h2>
      
      <div id="collapseOne" class="accordion-collapse collapse @if($isActive) show @endif" aria-labelledby="headingOne"
        data-bs-parent="#accordionTowns">
        <div class="accordion-body">
          <ul>
            @foreach ($towns as $town)
              <li>
                <label for="town_id.{{ $town->id }}">
                  <input id="town_id.{{ $town->id }}" type="checkbox" value="{{ $town->id }}"
                    name="parentTowns[{{ $town->id }}]" wire:model="parentTowns.{{ $town->id }}">
                  {{ $town->name }}
                </label>
                @if (isset($parentTowns[$town->id]) && $town->children->count() > 0)
                  <div class="ml-4">
                    @foreach ($town->children as $child)
                      <label class="ml-2">
                        <input type="checkbox" name="parentTowns[{{ $town->id }}][{{ $child->id }}]"
                          wire:model="parentTowns.{{ $town->id }}.{{ $child->id }}">
                        {{ $child->name }}
                      </label>
                      @if (isset($parentTowns[$town->id][$child->id]) && $child->children->count() > 0)
                        <div class="ml-6">
                          @foreach ($child->children as $grandchild)
                            <label class="ml-4">
                              <input type="checkbox"
                                name="parentTowns[{{ $town->id }}][{{ $child->id }}][{{ $grandchild->id }}]"
                                wire:model="parentTowns.{{ $town->id }}.{{ $child->id }}.{{ $grandchild->id }}">
                              {{ $grandchild->name }}
                            </label>
                          @endforeach
                        </div>
                      @endif
                    @endforeach
                  </div>
                @endif
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>
