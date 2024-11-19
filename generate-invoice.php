<?php

require "init.php";

$customer_id = 'cus_RCf3hkLIq1M2As';
$invoice = $stripe->invoices->create([
    'customer' => $customer_id
]);

$products = $stripe->products->all();
foreach ($products as $product)
{
    $stripe->invoiceItems->create([
        'customer' => $customer_id,
        'price' => $product->default_price,
        'invoice' => $invoice->id
    ]);
}

$stripe->invoices->finalizeInvoice($invoice->id);
$invoice = $stripe->invoices->retrieve($invoice->id);

print_r($invoice->hosted_invoice_url);
print_r($invoice->invoice_pdf);