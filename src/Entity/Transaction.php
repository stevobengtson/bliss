<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    use TrackedEntity;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $enteredDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $clearedDate = null;

    #[ORM\Column(length: 2048, nullable: true)]
    private ?string $memo = null;

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $credit = '0';

    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $debit = '0';

    public function __construct()
    {
        $this->id = new Ulid();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getEnteredDate(): ?\DateTimeInterface
    {
        return $this->enteredDate;
    }

    public function setEnteredDate(\DateTimeInterface $enteredDate): static
    {
        $this->enteredDate = $enteredDate;

        return $this;
    }

    public function getClearedDate(): ?\DateTimeInterface
    {
        return $this->clearedDate;
    }

    public function setClearedDate(?\DateTimeInterface $clearedDate): static
    {
        $this->clearedDate = $clearedDate;

        return $this;
    }

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): static
    {
        $this->memo = $memo;

        return $this;
    }

    public function getCredit(): string
    {
        return $this->credit;
    }

    public function setCredit(string $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getDebit(): string
    {
        return $this->debit;
    }

    public function setDebit(string $debit): static
    {
        $this->debit = $debit;

        return $this;
    }
}
