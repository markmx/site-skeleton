<?php

namespace MarkMx\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkMxUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
