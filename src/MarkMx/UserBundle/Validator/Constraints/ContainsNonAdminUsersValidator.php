<?php
namespace MarkMx\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsNonAdminUsersValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        foreach ($value as $user) {
            foreach ($user->getRoles() as $role) {
                if (strstr($role, 'ADMIN')) {
                    $this->context->addViolation($constraint->message, array('%string%' => $user->getUsername()));
                }
            }
        }
    }
}