<select class="form-control" name="parent_id">
  <option value=0>-- без родителя --</option>
  @isset($towns)
    @foreach ($towns as $parent )
      <option value="{{$parent->id ?? ''}}" 
        @isset($parent->id)
          @if($parent->id == request('parent_id'))
          selected
          @endif
          @if($parent->id == request('parent_id'))
          selected
          disabled
          @endif
        @endisset
        @isset($town_id)
          @if($parent->id == $town_id)
            selected
          @endif
          @if($parent->id == $town_id)
            selected
            disabled
          @endif
        @endisset
        >
        {{$parent->id}}-{{$parent->name ?? ''}}
      </option>
      @endforeach
  @endisset
</select>