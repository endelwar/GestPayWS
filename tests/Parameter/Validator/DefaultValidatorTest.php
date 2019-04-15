<?php

namespace EndelWar\GestPayWS\Parameter\Validator;

use EndelWar\GestPayWS\Data\Currency;

class DefaultValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultValidator
     */
    private $validator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->validator = new DefaultValidator();
    }

    /**
     * @dataProvider goodValuesProvider
     * @param $value
     */
    public function testIsValidGoodValues($value)
    {
        self::assertTrue($this->validator->isValid($value));
    }

    /**
     * @dataProvider badValuesProvider
     *
     * @param mixed $value
     */
    public function testIsValidBadValues($value)
    {
        self::assertFalse($this->validator->isValid($value));
    }

    public function testGetInvalidChars()
    {
        foreach ($this->validator->getInvalidCharsArray() as $badChar) {
            self::assertSame([ $badChar ], $this->validator->getInvalidChars('str' . $badChar . 'str'));
        }
    }

    public function badValuesProvider()
    {
        $data = [];
        foreach ($this->validator->getInvalidCharsArray() as $badChar) {
            $data[] = [$badChar];
            $data[] = ['str' . $badChar];
            $data[] = [$badChar . 'str'];
            $data[] = ['str' . $badChar . 'str'];
        }

        $data[] = ['/*this is a comment*/'];
        $data[] = ['str1&str2&str3'];

        return $data;
    }

    public function goodValuesProvider()
    {
        return [
            ['shopLogin' => 'GESPAY60861'],
            ['uicCode' => Currency::EUR],
            ['amount' => 1.23],
            ['shopTransactionId' => 123],
        ];
    }
}
