<?php

declare(strict_types=1);

namespace Tumugin\Potapota\Test\Domain\ClickUp;

use Tumugin\Potapota\Domain\ClickUp\ClickUpTaskName;
use Tumugin\Potapota\Test\BaseTestCase;

class ClickUpTaskNameTest extends BaseTestCase
{
    public function testRemoveUrlsFromTitle(): void
    {
        $clickUpTaskName = ClickUpTaskName::byString(
            '藤宮めいは可愛いね https://appare-official.jp/member/contents/498613 あああ'
        );
        $this->assertSame(
            '藤宮めいは可愛いね  あああ',
            $clickUpTaskName->removeUrlsFromTitle()->toString()
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
