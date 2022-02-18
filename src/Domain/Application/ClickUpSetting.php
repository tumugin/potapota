<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Application;

class ClickUpSetting
{
    public readonly ClickUpAPIToken $clickUpAPIToken;
    public readonly ClickUpListId $clickUpListId;

    public function __construct(ClickUpAPIToken $clickUpAPIToken, ClickUpListId $clickUpListId)
    {
        $this->clickUpAPIToken = $clickUpAPIToken;
        $this->clickUpListId = $clickUpListId;
    }
}
