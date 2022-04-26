<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Domain\Trello;

class TrelloSetting
{
    public readonly TrelloAPIKey $trelloAPIKey;
    public readonly TrelloAPIToken $trelloAPIToken;
    public readonly TrelloListId $trelloListId;

    public function __construct(TrelloAPIKey $trelloAPIKey, TrelloAPIToken $trelloAPIToken, TrelloListId $trelloListId)
    {
        $this->trelloAPIKey = $trelloAPIKey;
        $this->trelloAPIToken = $trelloAPIToken;
        $this->trelloListId = $trelloListId;
    }
}
