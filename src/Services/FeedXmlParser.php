<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class FeedXmlParser
{
    /**
     * @param string $xml
     *
     * @return string
     */
    public function getFeedCategoryNameFromXml(string $xml)
    {
        $crawler  = new Crawler($xml);
        $category = $crawler->filter('channel > category');

        return $category->count() ? $category->text() : '';
    }

    /**
     * @param string $xml
     *
     * @return array|string[]
     */
    public function getFeedsDataFromXml(string $xml)
    {
        $crawler = new Crawler($xml);
        $feeds   = $crawler->filter('channel > item');

        $feedsData = [];

        foreach ($feeds as $feed) {
            $feedsData[] = $feed->ownerDocument->saveXML($feed);
        }

        return $feedsData;
    }

    /**
     * @param string $url
     * @return false|string
     */
    public function getXmlFromUrl(string $url)
    {
        return file_get_contents($url);
    }
}
