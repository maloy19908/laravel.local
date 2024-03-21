<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav me-auto">
        @section('menu_top')
          @if (Route::has('admin'))
            <a class="nav-link" href="{{ route('admin') }}">Admin <span class="sr-only"></span></a>
          @endif
          @if (Route::has('clients.index'))
            <a class="nav-link" href="{{ route('clients.index') }}">clients<span class="sr-only"></span></a>
          @endif
          @if (Route::has('category.index'))
            <a class="nav-link" href="{{ route('category.index') }}">category<span class="sr-only"></span></a>
          @endif
          @if (Route::has('towns.index'))
            <a class="nav-link" href="{{ route('towns.index') }}">towns<span class="sr-only"></span></a>
          @endif
          @if (Route::has('nomenclatures'))
            <a class="nav-link" href="{{ route('nomenclatures') }}">nomenclatures<span class="sr-only"></span></a>
          @endif
          @if (Route::has('price.index'))
            <a class="nav-link" href="{{ route('price.index') }}">prices<span class="sr-only"></span></a>
          @endif
          @if (Route::has('product.index'))
            <a class="nav-link" href="{{ route('product.index') }}">ProductsAvito<span class="sr-only"></span></a>
          @endif
          @if (Route::has('users.index'))
            <a class="nav-link" href="{{ route('users.index') }}">Users<span class="sr-only"></span></a>
          @endif
        @show
      </ul>
      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ms-auto">
        <!-- Authentication Links -->
        @guest
          @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
          @endif

          @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
              data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              <i class="bi bi-person-circle text-danger"></i>
              {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
