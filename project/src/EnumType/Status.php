<?php
namespace App\EnumType;
enum Status: string
{
    case Enabled = "Enabled";
    case Disabled = 'Disabled';
}