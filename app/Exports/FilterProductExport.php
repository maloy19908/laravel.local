<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FilterProductExport implements FromView {
  protected $filtered;
  public function __construct($filtered) {
    $this->filtered = $filtered;
  }

  public function view(): View {
    return view('exports.product.filtered', [
      'filtered' => $this->filtered,
    ]);
  }
}
