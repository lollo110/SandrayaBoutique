<?php

namespace App\Enum;

enum Statut: string {
    case ENATTENTE = " en attente";
    case PAYEE = "payée";
    case EXPEDIEE = "expediée";
    case ANNULEE = "annulée";
}