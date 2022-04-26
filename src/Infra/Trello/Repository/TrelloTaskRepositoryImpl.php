<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Infra\Trello\Repository;

use Trello\Client;
use Tumugin\Potapota\Domain\ApplicationSettings\ApplicationSettings;
use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Trello\TrelloDraftTask;
use Tumugin\Potapota\Domain\Trello\TrelloSetting;
use Tumugin\Potapota\Domain\Trello\TrelloTask;
use Tumugin\Potapota\Domain\Trello\TrelloTaskId;
use Tumugin\Potapota\Domain\Trello\TrelloTaskRepository;
use Tumugin\Potapota\Domain\Trello\TrelloTaskUrl;

class TrelloTaskRepositoryImpl implements TrelloTaskRepository
{
    private ApplicationSettings $applicationSettings;

    public function __construct(ApplicationSettings $applicationSettings)
    {
        $this->applicationSettings = $applicationSettings;
    }

    public function createTrelloTask(DiscordGuildId $discordGuildId, TrelloDraftTask $trelloDraftTask): TrelloTask
    {
        /**
         * @var TrelloSetting $setting
         */
        $setting = $this->applicationSettings->trelloSettingMap->getSettingByDiscordGuildId($discordGuildId);

        $client = new Client();
        $client->authenticate(
            $setting->trelloAPIKey->toString(),
            $setting->trelloAPIToken->toString(),
            Client::AUTH_URL_CLIENT_ID
        );

        $result = $client->cards()->create([
            'idList' => $setting->trelloListId->toString(),
            'name' => $trelloDraftTask->trelloTaskName->toString(),
            'due' => $trelloDraftTask->trelloTaskDueDate?->toIso8601String(),
            'desc' => $trelloDraftTask->trelloTaskDescription->toString(),
        ]);

        return new TrelloTask(
            TrelloTaskId::byString($result['id']),
            $trelloDraftTask->trelloTaskName,
            $trelloDraftTask->trelloTaskDescription,
            $trelloDraftTask->trelloTaskDueDate,
            TrelloTaskUrl::byString($result['url'])
        );
    }
}
