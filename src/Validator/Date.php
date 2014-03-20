<?php
namespace Sirius\Validation\Validator;

class Date extends AbstractValidator
{

    const OPTION_FORMAT = 'format';
    
    protected static $defaultMessageTemplate = 'This input must be a date having the format {format}';

    protected $options = array(
    	'format' => 'Y-m-d'
    );

    function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = $value == date($this->options['format'], $this->getTimestampFromFormatedString($value, $this->options['format']));
        return $this->success;
    }
    
    protected function getTimestampFromFormatedString($string, $format)
    {
    	if (!date_timezone_get()) {
    		date_default_timezone_set('UTC');
    	}
    	$result = date_parse_from_format($format, $string);
    	return mktime((int) $result['hour'], (int) $result['minute'], (int) $result['second'], (int) $result['month'], (int) $result['day'], (int) $result['year']);
    }
}