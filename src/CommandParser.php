<?php
    namespace Khalyomede;

    use Khalyomede\Exception\OptionWithoutValueException;
    use Khalyomede\Exception\NotEnoughArgumentsException;

    class CommandParser {
        public static function parse(array $definition): array {
            global $argv;

            $arguments = $definition['arguments'] ?? [];
            $options = $definition['options'] ?? [];
            $flags = $definition['flags'] ?? [];

            $args = [];
            $opts = [];
            $flgs = [];

            $commands = $_SERVER['argv'] ?? $argv; 
            $toRemove = [];

            $ignoreArgumentCount = false;

            foreach( $flags as $flag ) {
                $sudoAndParts = explode('*', $flag);

                if( count($sudoAndParts) === 2 ) {
                    $ignoreArgumentCount = true;
                    $parts = $sudoAndParts[1];
                }
                else {
                    $parts = $sudoAndParts[0];
                }

                $parts = explode(':', $parts);
                $shortAndLong = $parts[0];
                $type = isset($parts[1]) ? trim($parts[1]) : null;
                $parts = explode(',', $shortAndLong);
                $long = trim($parts[0]);

                $short = isset($parts[1]) ? trim($parts[1]) : null;

                foreach( $commands as $command ) {
                    $longQuoted = preg_quote($long);
                    
                    $regexp = "/^(--$longQuoted)$/";

                    if( preg_match($regexp, $command) === 1 ) {
                        $toRemove[] = $command;

                        $flgs[$long] = true;
                    }
                }

            }

            foreach( $commands as $command ) {
                $regexp = "/^-[a-zA-Z]/";
                $beginsWithShortOptionOrArgument = preg_match($regexp, $command) === 1;

                if( $beginsWithShortOptionOrArgument === true ) {
                    $optionsOrFlags = str_split(ltrim($command, '-'));

                    foreach( $optionsOrFlags as $optionOrFlag ) {
                        foreach( $flags as $flag ) {
                            $sudoAndShortAndLong = explode('*', $flag);
                            $shortAndLong = null;

                            if( count($sudoAndShortAndLong) === 2 ) {
                                $ignoreArgumentCount = true;
                                $shortAndLong = $sudoAndShortAndLong[1];
                            }
                            else {
                                $shortAndLong = $sudoAndShortAndLong[0];
                            }

                            $shortAndLong = explode(':', $shortAndLong)[0];
                            $parts = explode(',', $shortAndLong);
                            $long = trim($parts[0]);
                            $short = isset($parts[1]) ? trim($parts[1]) : null;

                            if( $optionOrFlag === $short || $optionOrFlag === $long ) {
                                $flgs[$long] = true;
                                

                                $toRemove[] = $command;
                            }
                        }
                    }
                }
            }

            $commands = array_values(array_diff($commands, $toRemove));
            $commandCount = count($commands);

            $toRemove = [];

            foreach( $options as $option ) {
                $sudoAndParts = explode('*', $option);

                if( count($sudoAndParts) === 2 ) {
                    $ignoreArgumentCount = true;
                    $parts = $sudoAndParts[1];
                }
                else {
                    $parts = $sudoAndParts[0];
                }

                $parts = explode(':', $parts);
                $shortAndLong = $parts[0];
                $type = isset($parts[1]) ? trim($parts[1]) : null;
                $parts = explode(',', $shortAndLong);
                $long = trim($parts[0]);
                $short = isset($parts[1]) ? trim($parts[1]) : null;
                $commandFound = false;
                $commandValue = null;

                for( $i = 0; $i < $commandCount; $i++ ) {
                    $command = $commands[$i];

                    $longQuoted = preg_quote($long);

                    $regexp1 = "/^(--$longQuoted";
                    $regexp2 = "/^(--$longQuoted";

                    if( is_null($short) === false ) {
                        $shortQuoted = preg_quote($short);
                        
                        $regexp1 .= "|-$shortQuoted";
                        $regexp2 .= "|-$shortQuoted";
                    }

                    $regexp1 .= ")$/";
                    $regexp2 .= ")=(.+)$/";

                    if( preg_match($regexp1, $command) === 1 ) {
                        $nextIndex = $i + 1;

                        if( isset($commands[$nextIndex]) === true ) {
                            $nextCommand = $commands[$nextIndex];
                            
                            $toRemove[] = [$command, $nextCommand];

                            $commandFound = true;
                            $commandValue = $nextCommand;
                        }
                        else {
                            $exception = new OptionWithoutValueException("option $long has no value");
                            $exception->setOptionName($long);
                            
                            throw $exception;
                        }
                    }
                    else if( preg_match($regexp2, $command, $matches) === 1 ) {
                        $toRemove[] = $command;

                        $commandFound = true;
                        $commandValue = $matches[2];
                    }
                }

                if( $commandFound === true ) {
                    $opts[$long] = $commandValue;
                }
            }
            
            foreach( $toRemove as $removal ) {
                $commands = array_values($commands);
                $commandCount = count($commands);

                if( is_array($removal) === true && count($removal) === 2 ) {
                    [$first, $second] = $removal;

                    for( $i = 0; $i < $commandCount; $i++ ) {
                        $command = $commands[$i] ?? null;

                        if( $first === $command ) {
                            unset($commands[$i]);
                            unset($commands[$i + 1]);
                        }
                    }
                }
                else if( is_string($removal) === true ) {
                    for( $i = 0; $i < $commandCount; $i++ ) {
                        $command = $commands[$i];

                        if( $command === $removal ) {
                            unset($commands[$i]);
                        }
                    }
                }
            }

            array_shift($commands);

            $numberOfUserProvidedArguments = count($commands);
            $numberOfRequiredArguments = count($arguments);

            if( $ignoreArgumentCount === false && $numberOfUserProvidedArguments < $numberOfRequiredArguments ) {
                $exception = new NotEnoughArgumentsException("found $numberOfUserProvidedArguments arguments, but the command requires $numberOfRequiredArguments arguments");
                $exception->setUserNumberOfArgument($numberOfUserProvidedArguments);
                $exception->setRequiredNumberOfArgument($numberOfRequiredArguments);

                throw $exception;
            }

            $commands = array_slice($commands, 0, $numberOfRequiredArguments);

            foreach( $commands as $index => $command ) {
                $argumentName = $arguments[$index];

                $args[$argumentName] = $command;
            }
            
            return [
                'arguments' => $args,
                'options' => $opts,
                'flags' => $flgs
            ];
        }
    }
?>