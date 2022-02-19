<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\ClickUp;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Test\BaseTestCase;

class ClickUpTaskNameTest extends BaseTestCase
{
    public function testRemoveUrlsFromTaskName(): void
    {
        $clickUpTaskName = ClickUpTaskName::byString(
            '藤宮めいは可愛いね https://appare-official.jp/member/contents/498613 あああ'
        );
        $this->assertSame(
            '藤宮めいは可愛いね  あああ',
            $clickUpTaskName->removeUrlsFromTaskName()->toString()
        );
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
}
