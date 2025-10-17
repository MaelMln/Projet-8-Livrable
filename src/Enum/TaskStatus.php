<?php
namespace App\Enum;

enum TaskStatus: string
{
	case TODO = 'todo';
	case DOING = 'doing';
	case DONE = 'done';

	public function label(): string
	{
		return match($this) {
			self::TODO => 'To Do',
			self::DOING => 'Doing',
			self::DONE => 'Done',
		};
	}
}