<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Invoice\Model\InvoiceDAO;
use Modules\Invoice\Model\InvoiceService;
use Modules\Product\Model\ProductDAO;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InvoiceDAO::factory()
            ->count(100)
            ->create();

        foreach (InvoiceService::getAll() as $index => $invoice) {
            InvoiceService::addInvoiceItem($invoice, ProductDAO::find(($index + 1) * 2), 2.0);
            InvoiceService::addInvoiceItem($invoice, ProductDAO::find(($index + 1) * 2 + 1), 10.0);
        }
    }
}
