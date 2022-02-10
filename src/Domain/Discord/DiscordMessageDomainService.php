<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Discord;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTask;
use Tumugin\Potapota\Domain\Exceptions\NotImplementedException;

class DiscordMessageDomainService
{
    public function createDiscordDraftMessageByClickUpTask(ClickUpTask $clickUpTask): DiscordDraftMessage
    {
        throw new NotImplementedException('DiscordMessageDomainService not implemented');
    }
}
