<?php
declare(strict_types=1);

namespace Sirius\Validation;

interface ValidatorInterface
{
    /**
     * @param string|array<string,mixed> $selector
     * @param string|callable $name
     * @param string|array<mixed,mixed> $options
     * @param string $messageTemplate
     * @param string $label
     *
     * @throws \InvalidArgumentException
     */
    public function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null): self;

    /**
     * @param string $selector
     *            data selector
     * @param mixed $name
     *            rule name or true if all rules should be deleted for that selector
     * @param mixed $options
     *            rule options, necessary for rules that depend on params for their ID
     *
     * @return self
     */
    public function remove($selector, $name = true, $options = null): self;

    /**
     * @param array<string,mixed> $data
     */
    public function validate(array $data = []): bool;
}
