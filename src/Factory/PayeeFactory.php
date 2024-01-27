<?php

namespace App\Factory;

use App\Entity\Payee;
use App\Repository\PayeeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Payee>
 *
 * @method        Payee|Proxy                     create(array|callable $attributes = [])
 * @method static Payee|Proxy                     createOne(array $attributes = [])
 * @method static Payee|Proxy                     find(object|array|mixed $criteria)
 * @method static Payee|Proxy                     findOrCreate(array $attributes)
 * @method static Payee|Proxy                     first(string $sortedField = 'id')
 * @method static Payee|Proxy                     last(string $sortedField = 'id')
 * @method static Payee|Proxy                     random(array $attributes = [])
 * @method static Payee|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PayeeRepository|RepositoryProxy repository()
 * @method static Payee[]|Proxy[]                 all()
 * @method static Payee[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Payee[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Payee[]|Proxy[]                 findBy(array $attributes)
 * @method static Payee[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Payee[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Payee> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Payee> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Payee> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Payee> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Payee> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Payee> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Payee> random(array $attributes = [])
 * @phpstan-method static Proxy<Payee> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Payee> repository()
 * @phpstan-method static list<Proxy<Payee>> all()
 * @phpstan-method static list<Proxy<Payee>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Payee>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Payee>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Payee>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Payee>> randomSet(int $number, array $attributes = [])
 */
final class PayeeFactory extends ModelFactory
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
            'budget' => BudgetFactory::new(),
            'name' => self::faker()->text(255),
            'owner' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Payee $payee): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Payee::class;
    }
}
