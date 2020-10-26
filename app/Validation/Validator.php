<?php

//region Namespace
namespace App\Validation;
//endregion

//region Using
use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;
//endregion

class Validator
{
    protected $errors;

    public function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }

        //region Set Errors Session
        $_SESSION['errors'] = $this->errors;
        //endregion

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}