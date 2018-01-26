<?php

namespace Wizartisan;


use Illuminate\Support\Fluent;


/**
 * @property Command $command
 * @property string  $name
 * @property string  $text
 * @property boolean $secret
 * @property boolean $allow_empty
 * @property string  $confirm
 * @property array   $options
 * @property boolean $multiple
 * @property mixed   $default
 * @property array   $rules
 * @method Question name ( string $name )
 * @method Question default ( string $string )
 * @method Question rules ( array $rules )
 * @method Question confirm ( string $confirm )
 * @method Question secret ( bool $secret = true )
 * @method Question options ( array $options )
 * @method Question multiple ( bool $multiple = true )
 */
class Question extends Fluent
{
	
	
	/**
	 * @return Question
	 */
	public function hideAnswer ()
	{
		$this->secret = true;
		return $this;
	}
	
	
	/**
	 * @param string $text
	 * @return Question
	 */
	public function withConfirmation ( $text )
	{
		$this->confirm = $text;
		return $this;
	}
	
	
	/**
	 * @param array|string $rules
	 * @return Question
	 */
	public function mustValidate ( $rules )
	{
		$this->rules = is_array ( $rules ) ? $rules : func_get_args ();
		return $this;
	}
	
	
	/**
	 * @param array $options
	 * @param bool  $multiple
	 * @return Question
	 */
	public function chooseFrom ( array $options, $multiple = false )
	{
		$this->options = $options;
		$this->multiple = $multiple;
		return $this;
	}
	
	
	/**
	 * @return mixed|string
	 */
	public function prompt ()
	{
		if ( $this->options )
		{
			return $this->askChoice ();
		}
		
		$value = $this->ask ();
		
		return $this->confirm ? $this->askConfirm ( $value ) : $value;
	}
	
	
	/**
	 * @return string
	 */
	protected function askChoice ()
	{
		return $this->command->choice ( $this->text, $this->options, $this->default, null, $this->multiple );
	}
	
	
	/**
	 * @param string $text
	 * @return string
	 */
	protected function ask ( $text = null )
	{
		if ( $this->secret )
		{
			return $this->command->secret ( $text ?: $this->text );
		}
		return $this->command->ask ( $text ?: $this->text, $this->default );
	}
	
	
	/**
	 * @param mixed $value
	 * @return mixed
	 */
	protected function askConfirm ( $value )
	{
		do
		{
			$confirmValue = $this->ask ( $this->confirm );
			
			if ( $value !== $confirmValue )
			{
				$this->command->error ( 'Wrong confirm value. Try again' );
			}
			
		} while ( $value !== $confirmValue );
		
		return $value;
	}
	
	
}