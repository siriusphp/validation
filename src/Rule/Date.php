<?php
namespace Sirius\Validation\Rule;

class Date extends AbstractRule
{
    const OPTION_FORMAT = 'format';

    const MESSAGE = 'This input must be a date having the format {format}';

    const LABELED_MESSAGE = '{label} must be a date having the format {format}';

    protected $options = array(
        'format' => 'Y-m-d'
    );

    protected $optionsIndexMap = array(
        0 => self::OPTION_FORMAT
    );

    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = $value == date(
            $this->options['format'],
            $this->getTimestampFromFormatedString($value, $this->options['format'])
        );

        return $this->success;
    }

    protected function getTimestampFromFormatedString($string, $format)
    {
        $result = date_parse_from_format($format, $string);

        return mktime(
            (int) $result['hour'],
            (int) $result['minute'],
            (int) $result['second'],
            (int) $result['month'],
            (int) $result['day'],
            (int) $result['year']
        );
    }
}
