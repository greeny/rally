<?php declare(strict_types=1);

namespace Rally\Model\Enum;

enum RoleType: string
{

	case Racer = 'racer';
	case Passenger = 'passenger';
	case Technician = 'technician';
	case Manager = 'manager';
	case Photographer = 'photographer';

}
