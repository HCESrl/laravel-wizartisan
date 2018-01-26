<?php

namespace Wizartisan;


use Illuminate\Console\Command as IlluminateCommand;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;


abstract class Command extends IlluminateCommand
{
	
	/**
	 * @var \Illuminate\Validation\Validator
	 */
	protected $validator;
	
	/**
	 * @var array|Question[]
	 */
	protected $questions = [];
	
	
	/**
	 * @param ValidationFactory $validator
	 * @return mixed
	 */
	public function handle ( ValidationFactory $validator )
	{
		$this->validator = $validator->make ( [], [] );
		
		return $this->finish ( $this->getInputData () );
	}
	
	
	/**
	 * @return void
	 */
	abstract protected function configureWizard ();
	
	
	/**
	 * @param array $data
	 * @return mixed
	 */
	abstract protected function finish ( $data = [] );
	
	
	/**
	 * @param string $text
	 * @return Question
	 */
	protected function askQuestion ( $text )
	{
		$question = new Question( [ 'text' => $text, 'command' => $this ] );
		
		$this->questions[] = $question;
		
		return $question;
	}
	
	
	/**
	 * @param Question $question
	 * @return mixed
	 */
	private function promptQuestion ( Question $question )
	{
		$rules = $question->get ( 'rules', [] );
		
		$this->validator->setRules ( [ $question->name => $rules ] );
		
		do
		{
			$value = $question->prompt ();
			
			if ( empty( $value ) and $question->allow_empty )
			{
				$fails = false;
			} else
			{
				$data = [ $question->name => $value ];
				
				if ( in_array ( 'confirmed', $rules ) )
				{
					$data[ "{$question->name}_confirmation" ] = $value;
				}
				
				$this->validator->setData ( $data );
				
				if ( $fails = $this->validator->fails () )
				{
					foreach ( $this->validator->errors ()->all () as $error )
					{
						$this->error ( $error );
					}
				}
			}
		} while ( $fails );
		
		return $value;
	}
	
	
	/**
	 * @return array
	 */
	private function getInputData ()
	{
		$this->configureWizard ();
		
		$output = [];
		
		foreach ( $this->questions as $question )
		{
			$value = $this->promptQuestion ( $question );
			
			$output = Arr::set ( $output, $question->name, $value );
		}
		
		return $output;
	}
	
}