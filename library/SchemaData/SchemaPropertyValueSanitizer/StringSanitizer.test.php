<?php

namespace Municipio\SchemaData\SchemaPropertyValueSanitizer;

use PHPUnit\Framework\TestCase;

class StringSanitizerTest extends TestCase
{
    /**
     * @testdox Sanitizes string
     */
    public function testSanitizeString()
    {
        $sanitizer = new StringSanitizer();
        $this->assertEquals('value', $sanitizer->sanitize('value', ['string']));
    }

    /**
     * @testdox Sanitizes array of strings
     */
    public function testSanitizeArrayOfStrings()
    {
        $sanitizer = new StringSanitizer();
        $this->assertEquals(['value1', 'value2'], $sanitizer->sanitize(['value1', 'value2'], ['string[]']));
    }

    /**
     * @testdox Calls inner sanitizer if not to be handled by this sanitizer
     */
    public function testCallsInnerSanitizer()
    {
        $sanitizer = new StringSanitizer();
        $this->assertNull($sanitizer->sanitize([], ['string']));
    }
}