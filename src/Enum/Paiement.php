<?php

namespace App\Enum;

enum Paiement: string {
    case CARTE = "carte";
    case PAYPAL = "paypal";
    case VIREMENT = "virement";
}