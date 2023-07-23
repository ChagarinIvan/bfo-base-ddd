<?php

declare(strict_types=1);

namespace App\Domain\Person;

use App\Domain\AggregatedRoot;
use App\Domain\Club\ClubId;
use App\Domain\Rank\RankId;
use App\Domain\Shared\Collection;
use App\Domain\Shared\Impression;
use App\Domain\Shared\Metadata;
use DateTimeImmutable;

final class Person extends AggregatedRoot
{
    private readonly Collection $payments;

    private ?RankId $activeRankId = null;

    public function __construct(
        PersonId $id,
        private PersonInfo $info,
        private ?ClubId $clubId,
        private readonly Metadata $attributes,
        Impression $impression,
    ) {
        parent::__construct($id, $impression);
        $this->payments = Collection::empty();
    }

    public function updateInfo(PersonInfo $info, Impression $impression): void
    {
        $this->info = $info;
        $this->updated = $impression;
    }

    public function changeClub(?ClubId $clubId, Impression $impression): void
    {
        $this->clubId = $clubId;
        $this->updated = $impression;
    }

    public function activateRank(?RankId $rankId, Impression $impression): void
    {
        $this->activeRankId = $rankId;
        $this->updated = $impression;
    }

    public function info(): PersonInfo
    {
        return $this->info;
    }

    public function clubId(): ?ClubId
    {
        return $this->clubId;
    }

    public function activeRankId(): ?RankId
    {
        return $this->activeRankId;
    }

    public function attributes(): Metadata
    {
        return $this->attributes;
    }

    public function federationMemberOnDate(DateTimeImmutable $date): bool
    {
        $year = (int) $date->format('Y');
        /** @var PersonPayment|null $payment */
        $payment = $this->payments->first(static fn (PersonPayment $payment) => $payment->year === $year);

        return $payment && $payment->payedAt <= $date;
    }
}
