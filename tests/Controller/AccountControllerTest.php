<?php

namespace App\Test\Controller;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/account/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Account::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Account index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'account[nickName]' => 'Testing',
            'account[notes]' => 'Testing',
            'account[startingBalance]' => 'Testing',
            'account[balance]' => 'Testing',
            'account[type]' => 'Testing',
            'account[clearedBalance]' => 'Testing',
            'account[unclearedBalance]' => 'Testing',
            'account[id]' => 'Testing',
            'account[createdAt]' => 'Testing',
            'account[updatedAt]' => 'Testing',
            'account[owner]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Account();
        $fixture->setNickName('My Title');
        $fixture->setNotes('My Title');
        $fixture->setStartingBalance('My Title');
        $fixture->setBalance('My Title');
        $fixture->setType('My Title');
        $fixture->setClearedBalance('My Title');
        $fixture->setUnclearedBalance('My Title');
        $fixture->setId('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setOwner('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Account');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Account();
        $fixture->setNickName('Value');
        $fixture->setNotes('Value');
        $fixture->setStartingBalance('Value');
        $fixture->setBalance('Value');
        $fixture->setType('Value');
        $fixture->setClearedBalance('Value');
        $fixture->setUnclearedBalance('Value');
        $fixture->setId('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setOwner('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'account[nickName]' => 'Something New',
            'account[notes]' => 'Something New',
            'account[startingBalance]' => 'Something New',
            'account[balance]' => 'Something New',
            'account[type]' => 'Something New',
            'account[clearedBalance]' => 'Something New',
            'account[unclearedBalance]' => 'Something New',
            'account[id]' => 'Something New',
            'account[createdAt]' => 'Something New',
            'account[updatedAt]' => 'Something New',
            'account[owner]' => 'Something New',
        ]);

        self::assertResponseRedirects('/account/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNickName());
        self::assertSame('Something New', $fixture[0]->getNotes());
        self::assertSame('Something New', $fixture[0]->getStartingBalance());
        self::assertSame('Something New', $fixture[0]->getBalance());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getClearedBalance());
        self::assertSame('Something New', $fixture[0]->getUnclearedBalance());
        self::assertSame('Something New', $fixture[0]->getId());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getOwner());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Account();
        $fixture->setNickName('Value');
        $fixture->setNotes('Value');
        $fixture->setStartingBalance('Value');
        $fixture->setBalance('Value');
        $fixture->setType('Value');
        $fixture->setClearedBalance('Value');
        $fixture->setUnclearedBalance('Value');
        $fixture->setId('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setOwner('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/account/');
        self::assertSame(0, $this->repository->count([]));
    }
}
