<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Payee;
use App\Entity\Transaction;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType implements EventSubscriberInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entryDate', DateType::class, [
                'widget' => 'single_text',
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
            ->add('payee', EntityType::class, [
                'class' => Payee::class,
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('memo')
            ->add('credit', MoneyType::class, [
                'divisor' => 100,
                'currency' => 'USD',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('debit', MoneyType::class, [
                'divisor' => 100,
                'currency' => 'USD',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('cleared');

        $builder->addEventSubscriber($this);
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

    public function ensureOnlyCreditOrDebit(FormEvent $event): void
    {
        /** @var Transaction $transaction */
        $transaction = $event->getData();

        if (null !== $transaction->getCredit() && null !== $transaction->getDebit()) {
            $message = 'Only either credit or debit field must be set';
            throw new TransformationFailedException($message, 0, null, $message);
        }

        if (null === $transaction->getCredit() && null === $transaction->getDebit()) {
            $message = 'Either credit or debit field must be set';
            throw new TransformationFailedException($message, 0, null, $message);
        }
    }
}
