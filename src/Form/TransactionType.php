<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Payee;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entryDate')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('payee', EntityType::class, [
                'class' => Payee::class,
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('memo')
            ->add('credit')
            ->add('debit')
            ->add('cleared');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
