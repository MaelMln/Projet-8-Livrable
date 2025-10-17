<?php

namespace App\Enum;

enum EmployeeStatus: string
{
	case CDI = 'CDI';
	case CDD = 'CDD';
	case FREELANCE = 'Freelance';

	public function label(): string
	{
		return $this->value;
	}
}
