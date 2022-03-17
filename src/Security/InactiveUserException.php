<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;



class InactiveUserException extends AccountStatusException
{
    public function getMessageKey()
    {
        return "Account is disabled.";
    }

}