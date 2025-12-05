<?php

namespace App\Enum;

enum StatutPaiement: string {
    case ENATTENTE = "en attente";
    case ECHOUE = "echoué";
    case REUSSI = "reussi";
}