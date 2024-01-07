<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickName')
            ->add('notes')
            ->add('startingBalance', MoneyType::class, ['divisor' => 100, 'currency' => 'USD'])
            ->add('balance', MoneyType::class, ['divisor' => 100, 'currency' => 'USD'])
            ->add('type', EnumType::class, ['class' => \App\Enums\AccountType::class])
            ->add('clearedBalance', MoneyType::class, ['divisor' => 100, 'currency' => 'USD'])
            ->add('unclearedBalance', MoneyType::class, ['divisor' => 100, 'currency' => 'USD'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
