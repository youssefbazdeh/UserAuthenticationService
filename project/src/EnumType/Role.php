<?php
namespace App\EnumType;
enum Role: string
{
    case User = 'ROLE_USER';
    case Administrator = 'Administrator';
    case LDR = 'Leads Development Rep';
    case MM = 'Marketing Manager';
    case SM = 'Sales Manager';
    case SR = 'Sales Rep';


    public function getValue(): string
    {
        return $this->value;
    }
    
}