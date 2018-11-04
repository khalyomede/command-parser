<?php
    namespace Khalyomede\Exception;

    use RuntimeException;

    class OptionWithoutValueException extends RuntimeException {
        protected $optionName;

        public function setOptionName(string $name): self {
            $this->optionName = $name;

            return $this;
        }

        public function getOptioNname(): string {
            return $this->optionName;
        }
    }
?>