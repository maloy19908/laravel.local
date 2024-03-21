<div class="input-group input-group-sm mb-1">
  <div class="input-group-prepend">
    <span class="input-group-text">Имя</span>
  </div>
  <input type="text" class="form-control" value="{{$category->name ?? ''}}" name="name">
</div>
<div class="input-group input-group-sm mb-1">
  <select class="form-control" name="parent_id">
    <option value="0">-- без родительской категории --</option>
    @include('category._categories')
  </select>
</div>
<button type="submit" class="btn btn-primary">Сохранить</button>
