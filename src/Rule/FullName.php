<?php
namespace Sirius\Validation\Rule;

class FullName extends AbstractRule
{
    const MESSAGE = 'This input is not a valid full name (first name and last name)';
    const LABELED_MESSAGE = '{label} is not a valid full name (first name and last name)';

    /**
     * This is not going to work with Asian names, http://en.wikipedia.org/wiki/Chinese_name.
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;

        $names = explode(' ', $value);

        // Each name must be at least 2 characters long.
        foreach ($names as $name) {
            if (mb_strlen($name) < 2) {
                return $this->success = false;
            }
        }

        // Name cannot be longer shorter than 6 characters.
        return $this->success = mb_strlen($value) >= 6;
    }
}
