<?php

namespace App\Test\Controller;

use App\Entity\CategoryGroup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryGroupControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/category/group/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(CategoryGroup::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('CategoryGroup index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'category_group[name]' => 'Testing',
            'category_group[assigned]' => 'Testing',
            'category_group[activity]' => 'Testing',
            'category_group[id]' => 'Testing',
            'category_group[createdAt]' => 'Testing',
            'category_group[updatedAt]' => 'Testing',
            'category_group[owner]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new CategoryGroup();
        $fixture->setName('My Title');
        $fixture->setAssigned('My Title');
        $fixture->setActivity('My Title');
        $fixture->setId('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setOwner('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('CategoryGroup');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new CategoryGroup();
        $fixture->setName('Value');
        $fixture->setAssigned('Value');
        $fixture->setActivity('Value');
        $fixture->setId('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setOwner('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'category_group[name]' => 'Something New',
            'category_group[assigned]' => 'Something New',
            'category_group[activity]' => 'Something New',
            'category_group[id]' => 'Something New',
            'category_group[createdAt]' => 'Something New',
            'category_group[updatedAt]' => 'Something New',
            'category_group[owner]' => 'Something New',
        ]);

        self::assertResponseRedirects('/category/group/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getAssigned());
        self::assertSame('Something New', $fixture[0]->getActivity());
        self::assertSame('Something New', $fixture[0]->getId());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getOwner());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new CategoryGroup();
        $fixture->setName('Value');
        $fixture->setAssigned('Value');
        $fixture->setActivity('Value');
        $fixture->setId('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setOwner('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/category/group/');
        self::assertSame(0, $this->repository->count([]));
    }
}
