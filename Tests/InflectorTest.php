<?php
/*
 * This file is a part of Solve framework.
 *
 * @author Alexandr Viniychuk <alexandr.viniychuk@icloud.com>
 * @copyright 2009-2014, Alexandr Viniychuk
 * created: 16.11.13 13:44
 */


namespace Solve\Utils\InflectorTests;

use Solve\Utils\Inflector;

require_once '../Inflector.php';

class InflectorTest extends \PHPUnit_Framework_TestCase {

    public function testTransliteration() {
        $this->assertEquals("Privet l'udi!", Inflector::transliterate("Привет люди!"), 'letter ju');
        $this->assertEquals("Horoshij", Inflector::transliterate("Хороший"), 'letter j');
        $this->assertEquals("Приветы :)", Inflector::transliterate("Privjety :)", "ru"), 'revert transliteration');
    }

    public function testPluralize() {
        $this->assertEquals('winners', Inflector::pluralize('winner'), 'simple pluralization');
        $this->assertEquals('men', Inflector::pluralize('man'), 'the \'man\' word');
        $this->assertEquals('people', Inflector::pluralize('people'), 'the exceptions word');
    }

    public function testSingularize() {
        $this->assertEquals('winner', Inflector::singularize('winners'), 'simple singularization');
        $this->assertEquals('man', Inflector::singularize('men'), 'the \'man\' word');
        $this->assertEquals('person', Inflector::singularize('people'), 'the exceptions word');
    }
    
    public function testSlugify() {
        $this->assertEquals('hello-world', Inflector::slugify('Hello world!'), 'removing chars and spaces');
        $this->assertEquals('hello-world', Inflector::slugify('_hello_!world'), 'underscore char at the begin');
        $this->assertEquals('privet-habr', Inflector::slugify('Привет хабр!'), 'russian slug');
    }

    public function testUnderscore() {
        $this->assertEquals('hello_world', Inflector::underscore('Hello world!'), 'underscore');
        $this->assertEquals('hello_world', Inflector::underscore('HelloWorld!'), 'underscore');
        $this->assertEquals('this_istext', Inflector::underscore('ThisISText'), 'underscore');
    }

    public function testCamlize() {
        $this->assertEquals('HelloWorld', Inflector::camelize('hello world'), 'camelize');
        $this->assertEquals('HelloBigWorld', Inflector::camelize('hello_ big   world'), 'camelize underscore and space');
    }

    public function testNumberToStringLong() {
        Inflector::setDefaultLanguage("ua");
        $this->assertEquals('сто тридцять три гривні 00 копійок', Inflector::priceToStringLong('133'), 'number to string ua simple');
        $this->assertEquals('сто тридцять три гривні', Inflector::priceToStringLong('133', false), 'number to string ua strip float');
        $this->assertEquals('один мильйон гривень 12 копійок', Inflector::priceToStringLong('1000000,12'), 'number to string ua big number');
        $this->assertEquals('сто двадцать три тысячи гривен 55 копеек', Inflector::priceToStringLong('123000,55', true, "uah", "ru"), 'number to string ru overwrite default language');
        Inflector::setDefaultLanguage("ru");
    }

    public function testNumberToStringShort() {
        Inflector::setDefaultLanguage("ru");
        $this->assertEquals('триста один руб. 21 коп.', Inflector::priceToStringShort('301,21', true, 'руб.'), 'short version');
        $this->assertEquals('пятьсот пятнадцать руб.', Inflector::priceToStringShort('515,21', false, 'руб.'), 'short version without float');
    }



}
