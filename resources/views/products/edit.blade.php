@extends('layouts.app')
@section('content')
    <h1 class="text-center">Редактировать Авито товар</h1>
    @include('layouts.alerts')
    @php
        $opt = $product->productField->options;
    @endphp
    @if ($product->deleted_at)
        <p><span class="badge bg-danger">Удалено:{{ $product->deleted_at }}</span></p>
    @endif
    <p><b>AVITO ID:<i>{{ $product->avito_id }}</i></b></p>
    <p><b>Наш ID:<i>{{ $product->my_id }}</i></b></p>
    <form method="POST"
        action="{{ route('products.update', [
            'product' => $product,
            'category' => $product->category->id ?? '',
        ]) }}">
        @csrf
        @method('PUT')
        <input class="form-control my-3" type="text" value="{{ $product->my_id }}" placeholder="my_id" name="my_id">
        <div class="accordion" id="accordionTowns">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Города
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#accordionTowns">
                    <div class="accordion-body">
                        @livewire('towns.town-selection')
                    </div>
                </div>
            </div>
        </div>
        <p class="fs-2 text-center text-danger">Общие поля для всех одинаковых!</p>
        <input class="form-control my-3" type="text" value="{{ $product->ProductUniqTitle->title }}" placeholder="title"
            name="title">
        <textarea id="summernote" class="form-control" placeholder="description" name="description" rows="7">{{ $product->productField->options['description'] ?? '' }}</textarea>
        <select class="form-select my-3" name="category">
            @empty($product->category)
                <option value=0>-- без родителя --</option>
            @endempty
            @include('category._categories', ['category' => $product->category])
        </select>
        {{-- Не понятно нужен ли телефон и адрес который тянется из field --}}
        {{-- <input class="form-control my-3" type="text" placeholder="address" value="{{$opt->address ?? ''}}" name="address">
  <input class="form-control my-3" type="text" placeholder="contactphone" value="{{$opt->contactphone ?? ''}}" name="contactphone"> --}}
        <input class="btn btn-primary my-3" type="submit" value="сохранить">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="soft_delete" id="softDeleteCheckbox"
                {{ $product->trashed() ? 'checked' : '' }}>
            <label class="form-check-label" for="softDeleteCheckbox">
                удаленно
            </label>
        </div>
    </form>
@endsection
@section('scripts')
    <!-- Summernote CSS - CDN Link -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#summernote").summernote({
                height: 200,
                focus: true,
                onPaste: function(e){
                    e.preventDefault();
                    e.stopPropagation();
                },
                //toolbar: [],

            });
        });
    </script>
@endsection
