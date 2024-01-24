<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Payee;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayeeType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $builder
            ->add('name')
            ->add('linkCategory', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) use ($user) : QueryBuilder {
                    // TODO: Get budget id
                    return $er->createQueryBuilder('c')
                        ->where("c.owner = :owner")
                        ->setParameter(":owner", $user->getId()->toRfc4122())
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payee::class,
        ]);
    }
}
