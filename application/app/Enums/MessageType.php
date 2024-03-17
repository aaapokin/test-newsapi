<?php

namespace App\Enums;

enum MessageType
{
    use TENUM;
    case failure;
    case info;
    case success;
}
