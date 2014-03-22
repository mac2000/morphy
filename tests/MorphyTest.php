<?php
namespace Mac\Morphy\Tests;

use Mac\Morphy\Morphy;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * @SuppressWarnings(StaticAccess)
 */
class MorphyTests extends PHPUnit_Framework_TestCase
{
    /**
     * @var Morphy
     */
    protected $morphy;

    /**
     * @var ReflectionMethod
     */
    protected $isRussian;

    /**
     * @var ReflectionMethod
     */
    protected $sanitize;

    /**
     * @var ReflectionMethod
     */
    protected $sanitizeGrammems;

    /**
     * @var ReflectionMethod
     */
    protected $unsanitize;

    /**
     * @var ReflectionMethod
     */
    protected $ucfirst;

    /**
     * @var ReflectionMethod
     */
    protected $isAllLettersUpperCase;

    /**
     * @var ReflectionMethod
     */
    protected $isFirstLetterUpperCase;

    public function setUp()
    {
        $this->isRussian = self::getMethod('isRussian');
        $this->sanitize = self::getMethod('sanitize');
        $this->sanitizeGrammems = self::getMethod('sanitizeGrammems');
        $this->unsanitize = self::getMethod('unsanitize');
        $this->ucfirst = self::getMethod('ucfirst');
        $this->isFirstLetterUpperCase = self::getMethod('isFirstLetterUpperCase');
        $this->isAllLettersUpperCase = self::getMethod('isAllLettersUpperCase');


        $this->morphy = new Morphy();
    }

    /**
     * @param string $name method to return
     * @return ReflectionMethod
     */
    protected static function getMethod($name)
    {
        $class = new ReflectionClass('Mac\\Morphy\\Morphy');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testIsRussian()
    {
        $this->assertTrue($this->isRussian->invokeArgs($this->morphy, array('Киев')));
        $this->assertFalse($this->isRussian->invokeArgs($this->morphy, array('alert')));
    }

    public function testSanitize()
    {
        $this->assertEquals('ПРИВЕТ', $this->sanitize->invokeArgs($this->morphy, array(' ПриВет ')));
        $this->assertEquals('HELLO', $this->sanitize->invokeArgs($this->morphy, array(' HellO ')));
    }

    public function testUpperCaseFirstLetter()
    {
        $this->assertEquals('Привет', $this->ucfirst->invokeArgs($this->morphy, array('Привет')));
        $this->assertEquals('Hello', $this->ucfirst->invokeArgs($this->morphy, array('Hello')));
    }

    public function testIsFirstLetterUpperCase()
    {
        $this->assertTrue($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('Привет')));
        $this->assertTrue($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('ПРИВЕТ')));
        $this->assertTrue($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('Hello')));
        $this->assertTrue($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('HELLO')));

        $this->assertFalse($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('привет')));
        $this->assertFalse($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('пРИВЕТ')));
        $this->assertFalse($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('hello')));
        $this->assertFalse($this->isFirstLetterUpperCase->invokeArgs($this->morphy, array('hELLO')));
    }

    public function testIsAllLettersUpperCase()
    {
        $this->assertTrue($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('ПРИВЕТ')));
        $this->assertTrue($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('HELLO')));

        $this->assertFalse($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('привет')));
        $this->assertFalse($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('Привет')));
        $this->assertFalse($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('пРИВЕТ')));
        $this->assertFalse($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('hello')));
        $this->assertFalse($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('Hello')));
        $this->assertFalse($this->isAllLettersUpperCase->invokeArgs($this->morphy, array('hELLO')));
    }

    public function testThatUnsanitizeWillUpperCaseFirstLetter()
    {
        $this->assertEquals(
            array('Привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('Привет', array('привет')))
        );

        $this->assertEquals(
            array('Привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('Привет', array('Привет')))
        );

        $this->assertEquals(
            array('Привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('Привет', array('ПРИВЕТ')))
        );

        $this->assertEquals(
            array('Привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('Привет', array('пРИВЕТ')))
        );

        $this->assertEquals(
            array('Hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('Hello', array('hello')))
        );

        $this->assertEquals(
            array('Hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('Hello', array('Hello')))
        );

        $this->assertEquals(
            array('Hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('Hello', array('HELLO')))
        );

        $this->assertEquals(
            array('Hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('Hello', array('hELLO')))
        );
    }

    public function testThatUnsanitizeWillUpperCaseALLLetter()
    {
        $this->assertEquals(
            array('ПРИВЕТ'),
            $this->unsanitize->invokeArgs($this->morphy, array('ПРИВЕТ', array('привет')))
        );

        $this->assertEquals(
            array('ПРИВЕТ'),
            $this->unsanitize->invokeArgs($this->morphy, array('ПРИВЕТ', array('Привет')))
        );

        $this->assertEquals(
            array('ПРИВЕТ'),
            $this->unsanitize->invokeArgs($this->morphy, array('ПРИВЕТ', array('ПРИВЕТ')))
        );

        $this->assertEquals(
            array('ПРИВЕТ'),
            $this->unsanitize->invokeArgs($this->morphy, array('ПРИВЕТ', array('пРИВЕТ')))
        );

        $this->assertEquals(
            array('HELLO'),
            $this->unsanitize->invokeArgs($this->morphy, array('HELLO', array('hello')))
        );

        $this->assertEquals(
            array('HELLO'),
            $this->unsanitize->invokeArgs($this->morphy, array('HELLO', array('Hello')))
        );

        $this->assertEquals(
            array('HELLO'),
            $this->unsanitize->invokeArgs($this->morphy, array('HELLO', array('HELLO')))
        );

        $this->assertEquals(
            array('HELLO'),
            $this->unsanitize->invokeArgs($this->morphy, array('HELLO', array('hELLO')))
        );
    }

    public function testThatUnsanitizeWillLowerCaseALLLetter()
    {
        $this->assertEquals(
            array('привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('привет', array('привет')))
        );
        $this->assertEquals(
            array('привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('привет', array('Привет')))
        );
        $this->assertEquals(
            array('привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('привет', array('ПРИВЕТ')))
        );
        $this->assertEquals(
            array('привет'),
            $this->unsanitize->invokeArgs($this->morphy, array('привет', array('пРИВЕТ')))
        );

        $this->assertEquals(
            array('hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('hello', array('hello')))
        );
        $this->assertEquals(
            array('hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('hello', array('Hello')))
        );
        $this->assertEquals(
            array('hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('hello', array('HELLO')))
        );
        $this->assertEquals(
            array('hello'),
            $this->unsanitize->invokeArgs($this->morphy, array('hello', array('hELLO')))
        );
    }

    public function testRussianBase()
    {
        $this->assertEquals('фантастика', $this->morphy->base('фантастиках'));
        $this->assertEquals('фантастика', $this->morphy->base('фантастике'));
        $this->assertEquals('фантастика', $this->morphy->base('фантастики'));
    }

    public function testEnglishBase()
    {
        $this->assertEquals('alarm', $this->morphy->base('alarms'));
        $this->assertEquals('alarm', $this->morphy->base('alarms'));
    }

    public function testThatBaseShouldReturnNull()
    {
        $this->assertNull($this->morphy->base('xxx'));
    }

    public function testRussianAll()
    {
        $result = $this->morphy->all('киев');
        $this->assertContains('киев', $result);
        $this->assertContains('киеве', $result);
        $this->assertContains('киеву', $result);
    }

    public function testEnglishAll()
    {
        $result = $this->morphy->all('work');
        $this->assertContains('work', $result);
        $this->assertContains('works', $result);
        $this->assertContains('worked', $result);
        $this->assertContains('working', $result);
    }

    public function testThatAllShoulReturnNull()
    {
        $this->assertNull($this->morphy->all('xxx'));
    }

    public function testSanitizeGrammems()
    {
        $this->assertEquals(array('МР'), $this->sanitizeGrammems->invokeArgs($this->morphy, array(array('МР'))));
        $this->assertEquals(array('МР'), $this->sanitizeGrammems->invokeArgs($this->morphy, array(array('мр'))));
        $this->assertEquals(array('МР'), $this->sanitizeGrammems->invokeArgs($this->morphy, array(array(' Мр '))));

        $this->assertEmpty($this->sanitizeGrammems->invokeArgs($this->morphy, array(array('Hello'))));
    }

    public function testRussianCast()
    {
        $this->assertEquals(array('киеве'), $this->morphy->cast('киев', array('ЕД', 'ПР')));
        $this->assertEquals(array('америке'), $this->morphy->cast('америка', array('ЕД', 'ПР')));
        $this->assertEquals(array('США'), $this->morphy->cast('США', array('ЕД', 'ПР')));
        $this->assertEquals(array('Фантастике'), $this->morphy->cast('Фантастика', array('ЕД', 'ПР')));
        $this->assertNull($this->morphy->cast('xxx', array('ЕД', 'ПР')));
        $this->assertNull($this->morphy->cast('киев', array('xxx')));
    }

    public function testEnglishCast()
    {
        $this->assertNull($this->morphy->cast('Amarica', array('ЕД', 'ДТ')));
    }

    public function testWhere()
    {
        $this->assertEquals('киеве', array_shift($this->morphy->where('киев')));
        $this->assertEquals('оболони', array_shift($this->morphy->where('оболонь')));
        $this->assertEquals('троещине', array_shift($this->morphy->where('троещина')));
        $this->assertEquals('фантастике', array_shift($this->morphy->where('фантастика')));
    }
}
