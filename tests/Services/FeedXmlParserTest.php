<?php

namespace App\Tests\Services;

use App\Services\FeedXmlParser;
use PHPUnit\Framework\TestCase;

class FeedXmlParserTest extends TestCase
{
    /**
     * @dataProvider feedCategoryXmlProvider
     *
     * @param string $inputXml
     * @param string $expectedCategory
     */
    public function testGetCategoryNameFromXml(string $inputXml, string $expectedCategory)
    {
        $feedXmlParser = new FeedXmlParser();

        $categoryName = $feedXmlParser->getFeedCategoryNameFromXml($inputXml);

        $this->assertSame($expectedCategory, $categoryName);
    }

    public function feedCategoryXmlProvider()
    {
        return [
            'empty xml' => [
                'xml'              => '',
                'expectedCategory' => '',
            ],
            'xml with no category' => [
                'xml'              => '<?xml version="1.0"?><channel><notcategory>test</notcategory></channel>',
                'expectedCategory' => '',
            ],
            'category not inside channel' => [
                'xml'               => '<?xml version="1.0"?><channel></channel><category>wrong position</category>',
                'expectedFeedsData' => '',
            ],
            'category not a direct child of channel' => [
                'xml'               => '<?xml version="1.0"?><channel><channelchild><category>wrong position</category></channelchild></channel>',
                'expectedFeedsData' => '',
            ],
            'one category' => [
                'xml'              => '<?xml version="1.0"?><channel><category>test</category></channel>',
                'expectedCategory' => 'test',
            ],
            'multiple categories' => [
                'xml'              => '<channel><category>test1</category><category>test2</category></channel>',
                'expectedCategory' => 'test1',
            ],
        ];
    }

    /**
     * @param string $inputXml
     * @param array  $expectedFeedsData
     *
     * @dataProvider feedsDataXmlProvider
     */
    public function testGetFeedsDataFromXml(string $inputXml, array $expectedFeedsData)
    {
        $feedXmlParser = new FeedXmlParser();

        $feedsData = $feedXmlParser->getFeedsDataFromXml($inputXml);

        $this->assertSame($expectedFeedsData, $feedsData);
    }

    public function feedsDataXmlProvider()
    {
        return [
            'empty xml' => [
                'xml'               => '',
                'expectedFeedsData' => [],
            ],
            'xml with no items' => [
                'xml'               => '<?xml version="1.0"?><channel><notitem></notitem></channel>',
                'expectedFeedsData' => [],
            ],
            'items not inside channel' => [
                'xml'               => '<?xml version="1.0"?><channel><notitem></notitem></channel>',
                'expectedFeedsData' => [],
            ],
            'items not direct children of channel' => [
                'xml'               => '<?xml version="1.0"?><channel><subchannel><item>test</item></subchannel></channel>',
                'expectedFeedsData' => [],
            ],
            'one item' => [
                'xml'               => '<?xml version="1.0"?><channel><item>test</item></channel>',
                'expectedFeedsData' => ['<item>test</item>'],
            ],
            'multiple items' => [
                'xml'               => '<channel><item>test1</item><item>test2</item></channel>',
                'expectedFeedsData' => ['<item>test1</item>', '<item>test2</item>'],
            ],
        ];
    }
}
