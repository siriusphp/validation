#### 2.0.0

- added the `data_key:label` feature
- implemented default rule messages in the `RuleFactory` class. This way, if you have a custom error message for the `required` fields, you don't have to provide it to all the required fields, just set it up once in the `RuleFactory`

#### 1.2.4

- added PHP7 to Travis CI
- fixed bug with validating non-required elements (issue #17)

#### 1.2.2

- fixed bug with the 'between' validator (pull request #16)
- added support for PHP 5.3 (tests fail but due to the short array syntax, not the inherent code in the library)
- improved documentation on how to use complex validators (ie: that have dependencies)

#### 1.2.1

- fixed bug with the required rule not working properly on empty strings

#### 1.2.0

- improved the code base on Scrutinizer CI suggestions
- removed the ValidatableTrait trait

#### 1.1.0

- Added HHVM to Travis CI
- Renamed Validator\* classes into Rule\* classes (breaking change if you used custom rule classes)
- Renamed ValidatorFactory to RuleFactory (breaking change)
