<?php
namespace MarkMx\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ContainsNonAdminUsers extends Constraint
{
    public $message = 'User "%string%" having admin privileges may not be included.';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
