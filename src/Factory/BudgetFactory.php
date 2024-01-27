<?php

namespace App\Factory;

use App\Entity\Budget;
use App\Repository\BudgetRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Budget>
 *
 * @method        Budget|Proxy                     create(array|callable $attributes = [])
 * @method static Budget|Proxy                     createOne(array $attributes = [])
 * @method static Budget|Proxy                     find(object|array|mixed $criteria)
 * @method static Budget|Proxy                     findOrCreate(array $attributes)
 * @method static Budget|Proxy                     first(string $sortedField = 'id')
 * @method static Budget|Proxy                     last(string $sortedField = 'id')
 * @method static Budget|Proxy                     random(array $attributes = [])
 * @method static Budget|Proxy                     randomOrCreate(array $attributes = [])
 * @method static BudgetRepository|RepositoryProxy repository()
 * @method static Budget[]|Proxy[]                 all()
 * @method static Budget[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Budget[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Budget[]|Proxy[]                 findBy(array $attributes)
 * @method static Budget[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Budget[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Budget> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Budget> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Budget> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Budget> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Budget> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Budget> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Budget> random(array $attributes = [])
 * @phpstan-method static Proxy<Budget> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Budget> repository()
 * @phpstan-method static list<Proxy<Budget>> all()
 * @phpstan-method static list<Proxy<Budget>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Budget>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Budget>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Budget>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Budget>> randomSet(int $number, array $attributes = [])
 */
final class BudgetFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
            'owner' => UserFactory::new(),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Budget $budget): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Budget::class;
    }
}
