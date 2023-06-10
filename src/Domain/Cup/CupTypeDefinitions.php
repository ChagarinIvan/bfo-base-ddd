<?php

declare(strict_types=1);

namespace App\Domain\Cup;

use App\Domain\Cup\Group\CupGroup;
use App\Domain\Cup\Group\GroupAge;
use App\Domain\Cup\Group\GroupMale;

final readonly class CupTypeDefinitions
{
    /** @return CupTypeDefinition[] */
    public function all(): array
    {
        return [
            new CupTypeDefinition(CupType::ELITE, [
                CupGroup::create(GroupMale::Man, GroupAge::a21),
                CupGroup::create(GroupMale::Woman, GroupAge::a21),
            ]),
            new CupTypeDefinition(CupType::SPRINT, [
                CupGroup::create(GroupMale::Man, GroupAge::a21),
                CupGroup::create(GroupMale::Woman, GroupAge::a21),
            ]),
            new CupTypeDefinition(CupType::SKI, [
                CupGroup::create(GroupMale::Man, GroupAge::a21),
                CupGroup::create(GroupMale::Woman, GroupAge::a21),
            ]),
            new CupTypeDefinition(CupType::BIKE, [
                CupGroup::create(GroupMale::Man, GroupAge::a21),
                CupGroup::create(GroupMale::Woman, GroupAge::a21),
            ]),
            new CupTypeDefinition(CupType::MASTER, [
                CupGroup::create(GroupMale::Man, GroupAge::a35),
                CupGroup::create(GroupMale::Man, GroupAge::a40),
                CupGroup::create(GroupMale::Man, GroupAge::a45),
                CupGroup::create(GroupMale::Man, GroupAge::a50),
                CupGroup::create(GroupMale::Man, GroupAge::a55),
                CupGroup::create(GroupMale::Man, GroupAge::a60),
                CupGroup::create(GroupMale::Man, GroupAge::a65),
                CupGroup::create(GroupMale::Man, GroupAge::a70),
                CupGroup::create(GroupMale::Man, GroupAge::a75),
                CupGroup::create(GroupMale::Man, GroupAge::a80),
                CupGroup::create(GroupMale::Woman, GroupAge::a35),
                CupGroup::create(GroupMale::Woman, GroupAge::a40),
                CupGroup::create(GroupMale::Woman, GroupAge::a45),
                CupGroup::create(GroupMale::Woman, GroupAge::a50),
                CupGroup::create(GroupMale::Woman, GroupAge::a55),
                CupGroup::create(GroupMale::Woman, GroupAge::a60),
                CupGroup::create(GroupMale::Woman, GroupAge::a65),
                CupGroup::create(GroupMale::Woman, GroupAge::a70),
                CupGroup::create(GroupMale::Woman, GroupAge::a75),
                CupGroup::create(GroupMale::Woman, GroupAge::a80),
            ]),
            new CupTypeDefinition(CupType::YOUTH, [
                CupGroup::create(GroupMale::Man, GroupAge::a12),
                CupGroup::create(GroupMale::Man, GroupAge::a14),
                CupGroup::create(GroupMale::Man, GroupAge::a16),
                CupGroup::create(GroupMale::Man, GroupAge::a18),
                CupGroup::create(GroupMale::Woman, GroupAge::a12),
                CupGroup::create(GroupMale::Woman, GroupAge::a14),
                CupGroup::create(GroupMale::Woman, GroupAge::a16),
                CupGroup::create(GroupMale::Woman, GroupAge::a18),
            ]),
            new CupTypeDefinition(CupType::JUNIORS, [
                CupGroup::create(GroupMale::Man, GroupAge::a20),
                CupGroup::create(GroupMale::Woman, GroupAge::a20),
            ]),
        ];
    }
}
