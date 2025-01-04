<?php 
session_start();
if(isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    print_r($cart);
} else {
    $cart = [];
}