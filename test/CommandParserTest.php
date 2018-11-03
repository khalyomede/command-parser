<?php
    use PHPUnit\Framework\TestCase;

    use Khalyomede\CommandParser;

    class CommandParserTest extends TestCase {
        public function testShouldReturnAnArray() {
            $this->assertInternalType('array', CommandParser::parse([]));
        }
    }
?>