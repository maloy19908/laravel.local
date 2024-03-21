<?php

namespace App\Exports;

use App\Models\Nomenclature;
use App\Models\Price;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PriceListClientExport implements FromView, WithEvents {
  private $client_id;

  public function __construct($client_id) {
    $this->client_id = $client_id;
  }

  public function registerEvents(): array {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:' . $sheet->getHighestColumn() . '1');
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
          'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
              'argb' => 'fcf18f',
            ],
          ],
        ]);
      },
    ];
  }
  public function view(): View {
    if (!$this->client_id) {
      return die('нет ID клиента');
    }
    return view('exports.priceList', [
      'nomenclatures' => Nomenclature::get(),
      'clientId' => $this->client_id,
    ]);
  }
}
