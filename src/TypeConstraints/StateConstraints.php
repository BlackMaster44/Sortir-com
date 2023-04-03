<?php
namespace App\TypeConstraints;
class StateConstraints{
    const CREATED = 'created';
    const REG_OPEN = 'reg_open';
    const REG_CLOSED = 'reg_closed';
    const IN_PROGRESS = 'in_progress';
    const DONE = 'done';
    const CANCELED = 'canceled';
    const wordingState = [
        self::CREATED,
        self::REG_OPEN,
        self::REG_CLOSED,
        self::IN_PROGRESS,
        self::DONE,
        self::CANCELED];

}