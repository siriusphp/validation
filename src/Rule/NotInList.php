<?php
namespace Sirius\Validation\Rule;

class NotInList extends InList
{
    const OPTION_LIST = 'list';

    const MESSAGE = 'This input is one of the forbidden values';
    const LABELED_MESSAGE = '{label} is one of the forbidden values';

    protected $optionsIndexMap = array(
        0 => self::OPTION_LIST
    );

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        if (! isset($this->options['list'])) {
            $this->success = true;
        } else {
            if (is_array($this->options['list'])) {
                $this->success = ! in_array($value, $this->options['list']);
            }
        }

        return $this->success;
    }
}
