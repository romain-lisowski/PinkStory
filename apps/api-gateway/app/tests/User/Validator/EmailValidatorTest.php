<?php

declare(strict_types=1);

namespace App\Test\User\Validator;

use App\User\Validator\Constraints\Email;
use App\User\Validator\Constraints\EmailValidator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 * @coversNothing
 */
final class EmailValidatorTest extends TestCase
{
    private Prophet $prophet;
    private Email $constraint;
    private EmailValidator $validator;
    private $context;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->constraint = new Email();

        $this->context = $this->prophet->prophesize(ExecutionContextInterface::class);

        $this->validator = new EmailValidator();
        $this->validator->initialize($this->context->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testValidateSucess(): void
    {
        // this method should not be called if everything is ok
        $this->context->buildViolation($this->constraint->message)->shouldNotBeCalled();

        $this->validator->validate('auth@yannissgarra.com', $this->constraint);

        $this->expectNotToPerformAssertions();
    }

    public function testValidateWrongEmailFormat(): void
    {
        $translator = $this->prophet->prophesize(TranslatorInterface::class);
        $translator->trans($this->constraint->message, [], Argument::type('string'))->shouldBeCalledOnce()->willReturn($this->constraint->message);
        $builder = new ConstraintViolationBuilder(new ConstraintViolationList(), $this->constraint, $this->constraint->message, [], false, 'email', null, $translator->reveal(), 'messages');
        $this->context->buildViolation($this->constraint->message)->shouldBeCalledOnce()->willReturn($builder);

        $this->validator->validate('wrong_email_format', $this->constraint);

        $this->expectNotToPerformAssertions();
    }

    public function testValidateWrongEmailDNS(): void
    {
        $translator = $this->prophet->prophesize(TranslatorInterface::class);
        $translator->trans($this->constraint->message, [], Argument::type('string'))->shouldBeCalledOnce()->willReturn($this->constraint->message);
        $builder = new ConstraintViolationBuilder(new ConstraintViolationList(), $this->constraint, $this->constraint->message, [], false, 'email', null, $translator->reveal(), 'messages');
        $this->context->buildViolation($this->constraint->message)->shouldBeCalledOnce()->willReturn($builder);

        $this->validator->validate('auth@yannnissgarra.com', $this->constraint);

        $this->expectNotToPerformAssertions();
    }
}
