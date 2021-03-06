<?php
// $Id: FeedsSitemapParser.inc,v 1.3.2.2 2010/10/25 22:43:03 alexb Exp $

/**
 * A parser for the Sitemap specification http://www.sitemaps.org/protocol.php
 */
class FeedsSitemapParser extends FeedsParser {
  /**
   * Implementation of FeedsParser::parse().
   */
  public function parse(FeedsImportBatch $batch, FeedsSource $source) {
    // Set time zone to GMT for parsing dates with strtotime().
    $tz = date_default_timezone_get();
    date_default_timezone_set('GMT');
    // Yes, using a DOM parser is a bit inefficient, but will do for now
    $xml = new SimpleXMLElement($batch->getRaw());
    foreach ($xml->url as $url) {
      $item = array('url' => (string) $url->loc);
      if ($url->lastmod) {
        $item['lastmod'] = strtotime($url->lastmod);
      }
      if ($url->changefreq) {
        $item['changefreq'] = (string) $url->changefreq;
      }
      if ($url->priority) {
        $item['priority'] = (string) $url->priority;
      }
      $batch->items[] = $item;
    }
    date_default_timezone_set($tz);
  }

  /**
   * Implementation of FeedsParser::getMappingSources().
   */
  public function getMappingSources() {
    return array(
      'url' => array(
        'name' => t('Item URL (link)'),
        'description' => t('URL of the feed item.'),
      ),
      'lastmod' => array(
        'name' => t('Last modification date'),
        'description' => t('Last modified date as UNIX time GMT of the feed item.'),
      ),
      'changefreq' => array(
        'name' => t('Change frequency'),
        'description' => t('How frequently the page is likely to change.'),
      ),
      'priority' => array(
        'name' => t('Priority'),
        'description' => t('The priority of this URL relative to other URLs on the site.'),
      ),
    ) + parent::getMappingSources();
  }
}
