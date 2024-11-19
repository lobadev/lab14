<?php

require "init.php";

$products = $stripe->products->all();
foreach ($products as $product)
{
    // print_r($product);
    echo 'Product ID: ' . $product->id;
    echo "\nName: ";
    echo $product->name;
    echo "\nImage: ";
    echo array_pop($product->images);
    echo "\nPrice: ";
    // echo $product->default_price;
    $price = $stripe->prices->retrieve($product->default_price);
    // print_r($price);
    echo strtoupper($price->currency);
    echo ' ';
    echo number_format($price->unit_amount / 100, 2);
    echo "\n\n---------------------------------\n";
}