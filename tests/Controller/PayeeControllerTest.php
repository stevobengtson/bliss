<?php

namespace App\Test\Controller;

use App\Entity\Payee;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PayeeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/payee/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Payee::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Payee index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'payee[name]' => 'Testing',
            'payee[id]' => 'Testing',
            'payee[createdAt]' => 'Testing',
            'payee[updatedAt]' => 'Testing',
            'payee[autoCategory]' => 'Testing',
            'payee[parentPayee]' => 'Testing',
            'payee[owner]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Payee();
        $fixture->setName('My Title');
        $fixture->setId('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setAutoCategory('My Title');
        $fixture->setParentPayee('My Title');
        $fixture->setOwner('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Payee');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Payee();
        $fixture->setName('Value');
        $fixture->setId('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setAutoCategory('Value');
        $fixture->setParentPayee('Value');
        $fixture->setOwner('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'payee[name]' => 'Something New',
            'payee[id]' => 'Something New',
            'payee[createdAt]' => 'Something New',
            'payee[updatedAt]' => 'Something New',
            'payee[autoCategory]' => 'Something New',
            'payee[parentPayee]' => 'Something New',
            'payee[owner]' => 'Something New',
        ]);

        self::assertResponseRedirects('/payee/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getId());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getAutoCategory());
        self::assertSame('Something New', $fixture[0]->getParentPayee());
        self::assertSame('Something New', $fixture[0]->getOwner());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Payee();
        $fixture->setName('Value');
        $fixture->setId('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setAutoCategory('Value');
        $fixture->setParentPayee('Value');
        $fixture->setOwner('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/payee/');
        self::assertSame(0, $this->repository->count([]));
    }
}
