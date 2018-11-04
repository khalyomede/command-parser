<?php
    namespace Khalyomede\Exception;

    use RuntimeException;    

    class NotEnoughArgumentsException extends RuntimeException {
        protected $userNumberOfArgument;
        protected $requiredNumberOfArgument;

        public function setUserNumberOfArgument(int $count): self {
            $this->userNumberOfArgument = $count;

            return $this;
        }

        public function setRequiredNumberOfArgument(int $count): self {
            $this->requiredNumberOfArgument = $count;

            return $this;
        }

        public function getUserNumberOfArgument(): int {
            return $this->userNumberOfArgument;
        }

        public function getRequiredNumberOfArgument(): int {
            return $this->requiredNumberOfArgument;
        }
    }
?>