<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Payee;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('enteredDate', DateType::class)
            ->add('clearedDate', DateType::class)
            ->add('memo')
            ->add('credit', MoneyType::class, ['divisor' => 100, 'currency' => 'USD'])
            ->add('debit', MoneyType::class, ['divisor' => 100, 'currency' => 'USD'])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('payee', EntityType::class, [
                'class' => Payee::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
