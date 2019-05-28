<?php

namespace Wizartisan\Tests;


use Wizartisan\Question;


class QuestionTest extends TestCase
{

    /**
     * @var Question
     */
    protected $question;


    protected function setUp (): void
    {
        parent::setUp ();

        $this->question = new Question;
    }


    public function testHideAnswer ()
    {
        $this->assertInstanceOf ( Question::class, $this->question->hideAnswer () );
        $this->assertEquals ( true, $this->question->secret );
    }


    public function testWithConfirmation ()
    {
        $this->assertInstanceOf ( Question::class, $this->question->withConfirmation ( 'Confirm text' ) );
        $this->assertEquals ( 'Confirm text', $this->question->confirm );
    }


    public function testMustValidate ()
    {
        $rule = 'required';

        $this->assertInstanceOf ( Question::class, $this->question->mustValidate ( $rule ) );
        $this->assertIsArray ( $this->question->rules );
        $this->assertTrue ( in_array ( $rule, $this->question->rules ) );
    }


    public function testChooseFrom ()
    {
        $options = [
            'foo' => 'Foo',
            'bar' => 'Bar',
        ];

        $this->assertInstanceOf ( Question::class, $this->question->chooseFrom ( $options ) );
        $this->assertIsArray ( $this->question->options );
        $this->assertEquals ( $options, $this->question->options );
    }

}