<?php
namespace App\EnumType;
enum Groupe: string
{
    case Administrators = "Administrators";
    case Sales = "Sales";
    case Marketing = "Marketing";
    case MM = "Marketing Manager";
    case EM = "Executive Marketing";
    case SM = "Sales Manager";
    case ES = "Executive Sales";
    case PM = "Promotion Manager";
    case ED = "Executive Director";
}