## hcesrl/laravel-wizartisan

[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

## Installation

Install the package:
```bash
composer require hcesrl/laravel-wizartisan
```


## Usage

Create your wizard command extending the Wizartisan command. You must implement the `configureWizard` to add the 
steps to the wizard and the `finish` method that receives the validated data and completes the procedure.

```php
<?php
namespace App\Console\Commands;

use Wizartisan\Command;

class CustomCommand extends Command
{
	
	protected function configureWizard ()
	{
		/**
		 * Ask simple question with validation
		 */
		$this->askQuestion ( 'What is your name?' )
			 ->name ( 'name' )
			 ->mustValidate ( 'required' );
		
		/**
		 * Select option from given set
		 */
		$this->askQuestion ( 'Choose your option:' )
			 ->name ( 'option' )
			 ->chooseFrom ( [
								'option1' => 'Option 1',
								'option2' => 'Option 2',
								'option3' => 'Option 3',
							] );
		
		/**
		 * Ask a question with confirmation and hide the answer
		 */
		$this->askQuestion ( 'What is your secret password?' )
			 ->name ( 'password' )
			 ->hideAnswer ()
			 ->withConfirmation ( 'You must confirm your password' );
	}
	
	
	protected function finish ( $data = [] )
	{
		$this->doSomethingWithTheValidInputData ( $data );
		
		return 0;
	}
	
}
```