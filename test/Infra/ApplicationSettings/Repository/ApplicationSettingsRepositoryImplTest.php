<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Infra\ApplicationSettings\Repository;

use Tumugin\Potapota\Domain\Discord\DiscordGuildId;
use Tumugin\Potapota\Domain\Trello\TrelloSetting;
use Tumugin\Potapota\Infra\ApplicationSettings\Repository\ApplicationSettingsRepositoryImpl;
use Tumugin\Potapota\Test\BaseTestCase;

class ApplicationSettingsRepositoryImplTest extends BaseTestCase
{
    private ApplicationSettingsRepositoryImpl $applicationSettingsRepositoryImpl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->applicationSettingsRepositoryImpl = $this->make(
            ApplicationSettingsRepositoryImpl::class
        );
    }

    public function testCreateClickUpSettingMapByEnv(): void
    {
        $settingMap = $this->applicationSettingsRepositoryImpl->createClickUpSettingMapByEnv([
            'GUILD_ID_12345_CLICKUP_API_TOKEN' => 'token1',
            'GUILD_ID_12345_CLICKUP_LIST_ID' => 'list1',
            'GUILD_ID_67890_CLICKUP_API_TOKEN' => 'token2',
            'GUILD_ID_67890_CLICKUP_LIST_ID' => 'list2',
        ]);

        $firstSetting = $settingMap->getSettingByDiscordGuildId(DiscordGuildId::byString('12345'));
        $secondSetting = $settingMap->getSettingByDiscordGuildId(DiscordGuildId::byString('67890'));

        $this->assertSame('token1', $firstSetting->clickUpAPIToken->toString());
        $this->assertSame('list1', $firstSetting->clickUpListId->toString());
        $this->assertSame('token2', $secondSetting->clickUpAPIToken->toString());
        $this->assertSame('list2', $secondSetting->clickUpListId->toString());
    }

    public function testCreateTrelloSettingMapByEnv(): void
    {
        $settingMap = $this->applicationSettingsRepositoryImpl->createTrelloSettingMapByEnv([
            'GUILD_ID_12345_TRELLO_API_KEY' => 'apikey1',
            'GUILD_ID_12345_TRELLO_API_TOKEN' => 'apitoken1',
            'GUILD_ID_12345_TRELLO_LIST_ID' => 'listid1',
            'GUILD_ID_67890_TRELLO_API_KEY' => 'apikey2',
            'GUILD_ID_67890_TRELLO_API_TOKEN' => 'apitoken2',
            'GUILD_ID_67890_TRELLO_LIST_ID' => 'listid2',
        ]);

        /** @var TrelloSetting $firstSetting*/
        $firstSetting = $settingMap->getSettingByDiscordGuildId(DiscordGuildId::byString('12345'));
        /** @var TrelloSetting $secondSetting */
        $secondSetting = $settingMap->getSettingByDiscordGuildId(DiscordGuildId::byString('67890'));

        $this->assertSame('apikey1', $firstSetting->trelloAPIKey->toString());
        $this->assertSame('apitoken1', $firstSetting->trelloAPIToken->toString());
        $this->assertSame('listid1', $firstSetting->trelloListId->toString());

        $this->assertSame('apikey2', $secondSetting->trelloAPIKey->toString());
        $this->assertSame('apitoken2', $secondSetting->trelloAPIToken->toString());
        $this->assertSame('listid2', $secondSetting->trelloListId->toString());
    }
}
