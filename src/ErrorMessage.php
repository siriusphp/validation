<?php
declare(strict_types=1);

namespace Sirius\Validation;

class ErrorMessage
{
    protected string $template = 'Invalid';

    /**
     * @var array<string, mixed>
     */
    protected array $variables = [];

    /**
     * @param array<string, mixed> $variables
     */
    public function __construct(string $template = '', array $variables = [])
    {
        $this->setTemplate($template)
            ->setVariables($variables);
    }

    public function setTemplate(string $template): self
    {
        $template = trim($template);
        if ($template) {
            $this->template = $template;
        }

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param array<string, mixed> $variables
     */
    public function setVariables(array $variables = []): self
    {
        foreach ($variables as $k => $v) {
            $this->variables[$k] = $v;
        }

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    public function __toString()
    {
        $result = $this->template;
        foreach ($this->variables as $k => $v) {
            if (strpos($result, "{{$k}}") !== false) {
                $result = str_replace("{{$k}}", (string)$v, (string)$result);
            }
        }

        return $result;
    }
}
