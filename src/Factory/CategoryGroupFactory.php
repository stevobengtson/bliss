<?php

namespace App\Factory;

use App\Entity\CategoryGroup;
use App\Repository\CategoryGroupRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CategoryGroup>
 *
 * @method        CategoryGroup|Proxy                     create(array|callable $attributes = [])
 * @method static CategoryGroup|Proxy                     createOne(array $attributes = [])
 * @method static CategoryGroup|Proxy                     find(object|array|mixed $criteria)
 * @method static CategoryGroup|Proxy                     findOrCreate(array $attributes)
 * @method static CategoryGroup|Proxy                     first(string $sortedField = 'id')
 * @method static CategoryGroup|Proxy                     last(string $sortedField = 'id')
 * @method static CategoryGroup|Proxy                     random(array $attributes = [])
 * @method static CategoryGroup|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CategoryGroupRepository|RepositoryProxy repository()
 * @method static CategoryGroup[]|Proxy[]                 all()
 * @method static CategoryGroup[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CategoryGroup[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CategoryGroup[]|Proxy[]                 findBy(array $attributes)
 * @method static CategoryGroup[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CategoryGroup[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<CategoryGroup> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<CategoryGroup> createOne(array $attributes = [])
 * @phpstan-method static Proxy<CategoryGroup> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<CategoryGroup> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<CategoryGroup> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<CategoryGroup> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<CategoryGroup> random(array $attributes = [])
 * @phpstan-method static Proxy<CategoryGroup> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<CategoryGroup> repository()
 * @phpstan-method static list<Proxy<CategoryGroup>> all()
 * @phpstan-method static list<Proxy<CategoryGroup>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<CategoryGroup>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<CategoryGroup>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<CategoryGroup>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<CategoryGroup>> randomSet(int $number, array $attributes = [])
 */
final class CategoryGroupFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'owner' => UserFactory::new(),
            'budget' => BudgetFactory::new(),
            'name' => self::faker()->text(255),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CategoryGroup $categoryGroup): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CategoryGroup::class;
    }
}
