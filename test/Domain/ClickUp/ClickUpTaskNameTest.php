<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\ClickUp;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Test\BaseTestCase;

class ClickUpTaskNameTest extends BaseTestCase
{
    /**
     * @dataProvider provideTestRemoveUrlsFromTaskNameStrings
     */
    public function testRemoveUrlsFromTaskName(string $beforeString, string $afterString): void
    {
        $clickUpTaskName = ClickUpTaskName::byString(
            $beforeString
        );
        $this->assertSame(
            $afterString,
            $clickUpTaskName->removeUrlsFromTaskName()->toString()
        );
    }

    /**
     * @phpstan-return array{0:string, 1:string}[]
     */
    private function provideTestRemoveUrlsFromTaskNameStrings(): array
    {
        return [
            [
                '藤宮めいは可愛いね https://appare-official.jp/member/contents/498613 あああ',
                '藤宮めいは可愛いね  あああ',
            ],
            [
                '藤宮めいは可愛いねhttps://appare-official.jp/member/contents/498613 あああ',
                '藤宮めいは可愛いね あああ',
            ],
            [
                '藤宮めいは可愛いねhttps://appare-official.jp/member/contents/498613 あああ',
                '藤宮めいは可愛いね あああ',
            ],
            [
                '月ちゃん ' .
                'https://twitter.com/anthurium_hisui/status/1495026047796281354?s=20&t=k0z-lPNjjNTv9qjMJz5WtQ' .
                ' もいいね',
                '月ちゃん  もいいね',
            ],
        ];
    }

    public function testRemoveNewLine(): void
    {
        $clickUpTaskName = ClickUpTaskName::byString(
            "藍井すず\n藤宮めい"
        );
        $this->assertSame(
            '藍井すず藤宮めい',
            $clickUpTaskName->removeNewLineFromTaskName()->toString()
        );
    }

    public function testShortenTaskName(): void
    {
        $clickUpTaskName = ClickUpTaskName::byString(
            '藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね'
        );
        $this->assertSame(
            '藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは可愛いね藤宮めいは',
            $clickUpTaskName->shortenTaskName()->toString()
        );
    }

    /**
     * @dataProvider provideTestAddMudaiTextIfEmptyToTaskName
     */
    public function testAddMudaiTextIfEmptyToTaskName(string $testString): void
    {
        $clickUpTaskName = ClickUpTaskName::byString($testString);
        $this->assertSame(
            '無題',
            $clickUpTaskName->addMudaiTextIfEmptyToTaskName()->toString()
        );
    }

    /**
     * @phpstan-return array{0:string}[]
     */
    private function provideTestAddMudaiTextIfEmptyToTaskName(): array
    {
        return [
            ['  '],
            ['　　'],
            [''],
        ];
    }

    public function testAddMudaiTextIfEmptyToTaskNameOnNotEmptyCase(): void
    {
        $clickUpTaskName = ClickUpTaskName::byString('アンスリューム大サーカス');
        $this->assertSame(
            'アンスリューム大サーカス',
            $clickUpTaskName->addMudaiTextIfEmptyToTaskName()->toString()
        );
    }
}
