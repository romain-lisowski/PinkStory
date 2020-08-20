<?php

declare(strict_types=1);

namespace App\Test\User\Validator;

use App\User\Validator\Constraints\PasswordStrenght;
use App\User\Validator\Constraints\PasswordStrenghtValidator;
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
final class PasswordStrenghtValidatorTest extends TestCase
{
    private Prophet $prophet;
    private PasswordStrenght $constraint;
    private PasswordStrenghtValidator $validator;
    private $context;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->constraint = new PasswordStrenght();

        $this->context = $this->prophet->prophesize(ExecutionContextInterface::class);

        $this->validator = new PasswordStrenghtValidator();
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

        $this->validator->validate('@Password2!', $this->constraint);

        $this->expectNotToPerformAssertions();
    }

    public function testValidateNotStrongEnough(): void
    {
        $translator = $this->prophet->prophesize(TranslatorInterface::class);
        $translator->trans($this->constraint->message, [], Argument::type('string'))->shouldBeCalledOnce()->willReturn($this->constraint->message);
        $builder = new ConstraintViolationBuilder(new ConstraintViolationList(), $this->constraint, $this->constraint->message, [], false, 'password', null, $translator->reveal(), 'messages');
        $this->context->buildViolation($this->constraint->message)->shouldBeCalledOnce()->willReturn($builder);

        $this->validator->validate('password', $this->constraint);

        $this->expectNotToPerformAssertions();
    }
}
