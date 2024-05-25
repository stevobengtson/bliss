<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Payee;
use App\Entity\Transaction;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, [
                'currency' => 'USD',
            ])
            ->add('payee', EntityType::class, [
                'class' => Payee::class,
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $repository): QueryBuilder {
                    return $repository->createQueryBuilder('category')
                        ->leftJoin('category.categoryGroup', 'categoryGroup')
                        ->orderBy('categoryGroup.name', 'ASC')
                        ->addOrderBy('category.name', 'ASC');
                },
                'group_by' => function (Category $category): string {
                    return $category->getCategoryGroup()->getName();
                },
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('entryDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('cleared')
            ->add('memo');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }

    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubScribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'ensureOnlyCreditOrDebit',
        ];
    }
}
