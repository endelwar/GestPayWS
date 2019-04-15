<?php

namespace EndelWar\GestPayWS\Parameter\Validator\Test;

use EndelWar\GestPayWS\Parameter\Validator\ApikeyValidator;

class ApikeyValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ApikeyValidator
     */
    private $validator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->validator = new ApikeyValidator();
    }

    /**
     * @dataProvider goodValuesProvider
     *
     * @param string $value
     */
    public function testIsValidGoodValues($value)
    {
        self::assertTrue($this->validator->isValid($value));
    }

    /**
     * @dataProvider badValuesProvider
     *
     * @param string $value
     */
    public function testIsValidBadValues($value)
    {
        self::assertFalse($this->validator->isValid($value));
    }

    /**
     * @dataProvider badInvalidCharsProvider
     *
     * @param string $value
     * @param array $invalidChars
     */
    public function testGetInvalidCharsBadValues($value, $invalidChars)
    {
        self::assertSame($invalidChars, $this->validator->getInvalidChars($value));
    }

    /**
     * @dataProvider goodValuesProvider
     *
     * @param $value
     */
    public function testGetInvalidCharsGoodValues($value)
    {
        self::assertSame([], $this->validator->getInvalidChars($value));
    }

    public function goodValuesProvider()
    {
        return [
            ['R0VTUEFZNjA4NjEjI0VzZXJjZW50ZSBUZXN0IyMyMC8wOC8yMDE4IDExOjQzOjQ5'],
            ['R0VTUEFZNjA4NjEjI0VzZXJjZW50ZSBUZXN0IGRpIHRlc3RpbmcjIzE4LzAyLzIwMTkgMDk6NTk6NTU='],
            [''],
            [null]
        ];
    }

    public function badValuesProvider()
    {
        return [
            [['a']],
            [new \stdClass()],
            ['@invalidcharatbegin'],
            ['invalidcharatend#'],
            ['R#VTUEFZNjA4NjEjI0VzZXJjZW50ZSBUZXN0IGRpIGRhbGxhIGxhbmEjIzE4LzAyLzIwMTkgMDk6NTk6NTU@'],
        ];
    }

    public function badInvalidCharsProvider()
    {
        return [
            ['@invalidcharatbegin', ['@']],
            ['invalidcharatend#', ['#']],
            ['R#VTUEFZNjA4NjEjI0VzZXJjZW50ZSBUZXN0§GRpIGRhbGxhIGxhbmEjIzE4LzAyLzIwMTkgMDk6NTk6@@@@', ['#', '§', '@']],
        ];
    }
}
