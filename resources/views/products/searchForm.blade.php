<h2>Поиск по товарам</h2>
<form class="d-flex justify-content-between mt-3 border border-primary rounded" action="{{route('products.index')}}" method="get">
  <div class=" m-2">
    <input class="form-control form-control-sm" type="text" name="my_id" placeholder="искать по нашему id">
  </div>
  <div class=" m-2">
    <input class="form-control form-control-sm" type="text" name="title" placeholder="искать по тайтл">
  </div>
  <div class=" m-2">
    <input class="form-control form-control-sm" type="text" name="category" placeholder="искать по Category">
  </div>
  <div class=" m-2">
    <input class="form-control form-control-sm" type="text" name="address" placeholder="искать по address">
  </div>
  <div class=" m-2">
    <input class="form-control form-control-sm" type="text" name="contactphone" placeholder="искать по contactphone">
  </div>
  <input type="submit" class="btn btn-primary" type="submit" value="искать">
</form>