<?php

namespace EndelWar\GestPayWS\Parameter\Validator;

class ParameterValidatorFactory
{
    /**
     * @param string $parameterName
     *
     * @return ParameterValidatorInterface
     */
    public static function getValidator($parameterName)
    {
        $parameterName = strtolower($parameterName);

        switch ($parameterName) {
            case 'apikey':
                return new ApikeyValidator();
                break;
            case 'custominfo':
                return new CustomInfoValidator();
                break;
            default:
                return new DefaultValidator();
        }
    }
}
