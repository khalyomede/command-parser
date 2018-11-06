<?php
    use PHPUnit\Framework\TestCase;

    use Khalyomede\CommandParser;
    use Khalyomede\Exception\NotEnoughArgumentsException;    
    use Khalyomede\Exception\OptionWithoutValueException;

    class CommandParserTest extends TestCase {
        public function testShouldReturnAnArray() {
            $_SERVER['argv'] = ['foo'];

            $actual = CommandParser::parse([]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => []];

            $this->assertInternalType('array', $expected);
        }

        public function testShouldReturnAnEmptyArrayIfThereIsNoArguments() {
            $_SERVER['argv'] = ['foo'];

            $actual = CommandParser::parse([]);
            $expected = ['arguments' => [],'options' => [],'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAFlag() {
            $_SERVER['argv'] = ['foo', '--help'];

            $actual = CommandParser::parse(['flags' => ['help']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => ['help' => true]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAFlagThatHaveAShortVersion() {
            $_SERVER['argv'] = ['foo', '--help'];

            $actual = CommandParser::parse(['flags' => ['help,h']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => ['help' => true]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAFlagAmongMany() {
            $_SERVER['argv'] = ['foo', '--help'];

            $actual = CommandParser::parse([
                'flags' => ['help', 'quiet', 'version']
            ]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAFlagThatHaveAShortVersionAmongMany() {
            $_SERVER['argv'] = ['foo', '--help'];

            $actual = CommandParser::parse([
                'flags' => ['help,h', 'quiet', 'version']
            ]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAFlagThatHaveAShortVersionAmongManyFlagThatAlsoHaveShortVersions() {
            $_SERVER['argv'] = ['foo', '--help'];

            $actual = CommandParser::parse([
                'flags' => ['help,h', 'quiet,q', 'version,v']
            ]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleFlags() {
            $_SERVER['argv'] = ['foo', '--help', '--version'];

            $actual = CommandParser::parse(['flags' => ['help', 'version']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleFlagsWithTheirShortVersions() {
            $_SERVER['argv'] = ['foo', '--help', '--version'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleFlagsAmongMany() {
            $_SERVER['argv'] = ['foo', '--help', '--version'];

            $actual = CommandParser::parse(['flags' => ['help', 'quiet', 'version']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleFlagsWithTheirShortVersionsAmongMany() {
            $_SERVER['argv'] = ['foo', '--help', '--version'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'quiet', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleFlagsAmongManyAllWithTheirShortVersions() {
            $_SERVER['argv'] = ['foo', '--help', '--version'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'quiet,q', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAShortFlag() {
            $_SERVER['argv'] = ['foo', '-h'];

            $actual = CommandParser::parse(['flags' => ['help,h']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => ['help' => true]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAShortFlagAmongMany() {
            $_SERVER['argv'] = ['foo', '-q'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'quiet,q', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'quiet' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAShortFlagAmongManyThatDoNotHaveShortVersions() {
            $_SERVER['argv'] = ['foo', '-q'];

            $actual = CommandParser::parse(['flags' => ['help', 'quiet,q', 'version']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'quiet' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleShortFlags() {
            $_SERVER['argv'] = ['foo', '-hv'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleShortFlagsAmongMany() {
            $_SERVER['argv'] = ['foo', '-hv'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'quiet,q', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnMultipleShortFlagsAmongManyThatDoNotHaveShortVersions() {
            $_SERVER['argv'] = ['foo', '-hv'];

            $actual = CommandParser::parse(['flags' => ['help,h', 'quiet', 'version,v']]);
            $expected = ['arguments' => [], 'options' => [], 'flags' => [
                'help' => true,
                'version' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnArgument() {
            $_SERVER['argv'] = ['readfile', './composer.json'];

            $actual = CommandParser::parse(['arguments' => ['path']]);
            $expected = ['arguments' => [
                'path' => './composer.json'
            ], 'options' => [], 'flags' => []];

            $this->assertEquals($expected, $actual);
        }

        public function testShouldReturnMultipleArguments() {
            $_SERVER['argv'] = ['gcc', 'main.c', 'main.o'];

            $actual = CommandParser::parse(['arguments' => ['program', 'output']]);
            $expected = ['arguments' => [
                'program' => 'main.c',
                'output' => 'main.o'
            ], 'options' => [], 'flags' => []];

            $this->assertEquals($expected, $actual);
        }

        public function testShouldThrowAnExceptionIfThereIsNotEnoughArguments() {
            $_SERVER['argv'] = ['gcc', 'main.c'];

            $this->expectException(NotEnoughArgumentsException::class);

            $actual = CommandParser::parse(['arguments' => ['program', 'output']]);
        }

        public function testShouldThrowAnExceptionMessageIfThereIsNotEnoughArguments() {
            $_SERVER['argv'] = ['gcc', 'main.c'];

            $this->expectExceptionMessage("found 1 arguments, but the command requires 2 arguments");

            $actual = CommandParser::parse(['arguments' => ['program', 'output']]);
        }

        public function testShouldReturnTheCorrectNumberOfUserArgumentIfThereIsNotEnoughArgument() {
            $_SERVER['argv'] = ['gcc', 'main.c'];

            try {
                CommandParser::parse(['arguments' => ['program', 'output']]);
            }
            catch( NotEnoughArgumentsException $exception ) {
                $this->assertEquals($exception->getUserNumberOfArgument(), 1);
            }
        }

        public function testShouldReturnTheCorrectNumberOfRequiredArgumentIfThereIsNotEnoughArgument() {
            $_SERVER['argv'] = ['gcc', 'main.c'];

            try {
                CommandParser::parse(['arguments' => ['program', 'output']]);
            }
            catch( NotEnoughArgumentsException $exception ) {
                $this->assertEquals($exception->getRequiredNumberOfArgument(), 2);
            }
        }

        public function testShouldThrowAnExceptionIfAnOptionDoesNotHaveAValue() {
            $_SERVER['argv'] = ['readfile', '--max-length'];

            $this->expectException(OptionWithoutValueException::class);

            CommandParser::parse(['options' => ['max-length']]);
        }

        public function testShouldThrowAnExceptionMessageIfAnOptionDoesNotHaveAValue() {
            $_SERVER['argv'] = ['readfile', '--max-length'];

            $this->expectExceptionMessage("option max-length has no value");

            CommandParser::parse(['options' => ['max-length']]);
        }

        public function testShouldReturnTheOptionNameInTheExceptionIfItHasNoValue() {
            $_SERVER['argv'] = ['readfile', '--max-length'];

            try {
                CommandParser::parse(['options' => ['max-length']]);
            }
            catch( OptionWithoutValueException $exception ) {
                $this->assertEquals($exception->getOptionName(), 'max-length');
            }
        }

        public function testShouldReturnAnOption() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12'];

            $actual = CommandParser::parse(['options' => ['max-length']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionWithAShortName() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12'];

            $actual = CommandParser::parse(['options' => ['max-length,m']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionSetWithEqual() {
            $_SERVER['argv'] = ['readfile', '--max-length=12'];

            $actual = CommandParser::parse(['options' => ['max-length']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionWithAShortNameSetWithEqual() {
            $_SERVER['argv'] = ['readfile', '--max-length=12'];

            $actual = CommandParser::parse(['options' => ['max-length,m']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionAmongMany() {
            $_SERVER['argv'] = ['readfile', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length', 'convert']]);
            $expected = ['arguments' => [], 'options' => [
                'convert' => 'json'
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionWithAShortNameAmongMany() {
            $_SERVER['argv'] = ['readfile', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length', 'convert,c']]);
            $expected = ['arguments' => [], 'options' => [
                'convert' => 'json'
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionAmongManyThatAllHaveShortNames() {
            $_SERVER['argv'] = ['readfile', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c']]);
            $expected = ['arguments' => [], 'options' => [
                'convert' => 'json'
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionSetWithEqualAmongMany() {
            $_SERVER['argv'] = ['readfile', '--convert=json'];

            $actual = CommandParser::parse(['options' => ['max-length', 'convert']]);
            $expected = ['arguments' => [], 'options' => [
                'convert' => 'json'
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnAnOptionSetWithEqualAmongManyThatAllHaveShortNames() {
            $_SERVER['argv'] = ['readfile', '--convert=json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c']]);
            $expected = ['arguments' => [], 'options' => [
                'convert' => 'json'
            ], 'flags' => []];
            
            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOption() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length', 'convert']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionWithShortNames() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,m']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionSetWithEqual() {
            $_SERVER['argv'] = ['readfile', '--max-length=12', '--convert=json'];

            $actual = CommandParser::parse(['options' => ['max-length', 'convert']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionSetWithEqualWithTheirShortNames() {
            $_SERVER['argv'] = ['readfile', '--max-length=12', '--convert=json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }
        
        public function testShouldReturnManyOptionAmongMany() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length', 'convert', 'engine']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionWithTheirShortNamesAmongMany() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c', 'engine']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionSetWithEqualWithTheirShortNamesAmongMany() {
            $_SERVER['argv'] = ['readfile', '--max-length=12', '--convert=json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c', 'engine']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionNamesAmongManyWithAllTheirShortNames() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--convert', 'json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c', 'engine,e']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnManyOptionSetWithEqualNamesAmongManyWithAllTheirShortNames() {
            $_SERVER['argv'] = ['readfile', '--max-length=12', '--convert=json'];

            $actual = CommandParser::parse(['options' => ['max-length,m', 'convert,c', 'engine,e']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12',
                'convert' => 'json'
            ], 'flags' => []];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnOptionAndFlag() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnOptionAndFlagWithItsShortName() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet,q']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnOptionAndFlags() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet', '--skip-not-found'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet', 'skip-not-found']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true,
                'skip-not-found' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnOptionAndFlagsWithTheirShortNames() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet', '--skip-not-found'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet,q', 'skip-not-found,s']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true,
                'skip-not-found' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShoudReturnOptionAndFlagsAmongMany() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet', '--skip-not-found'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet', 'skip-not-found', 'recursive']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true,
                'skip-not-found' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnOptionAndFlagsWithTheirFlagsAmongMany() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet', '--skip-not-found'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet,q', 'skip-not-found,s', 'recursive']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true,
                'skip-not-found' => true
            ]];

            $this->assertEquals($actual, $expected);
        }

        public function testShouldReturnOptionAndFlagsAmongManyWithTheirFlags() {
            $_SERVER['argv'] = ['readfile', '--max-length', '12', '--quiet', '--skip-not-found'];

            $actual = CommandParser::parse(['options' => ['max-length'], 'flags' => ['quiet,q', 'skip-not-found,s', 'recursive,r']]);
            $expected = ['arguments' => [], 'options' => [
                'max-length' => '12'
            ], 'flags' => [
                'quiet' => true,
                'skip-not-found' => true
            ]];

            $this->assertEquals($actual, $expected);
        }
    }
?>