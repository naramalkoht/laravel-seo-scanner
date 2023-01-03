<?php 

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Vormkracht10\Seo\Checks\Configuration\NoFollowCheck;
use Vormkracht10\Seo\Checks\Configuration\NoIndexCheck;
use Vormkracht10\Seo\Checks\Configuration\RobotsCheck;

it('can perform the nofollow check with robots tag', function () {
    $check = new NoFollowCheck();
    $crawler = new Crawler();

    Http::fake([
        'vormkracht10.nl' => Http::response('', 200, ['X-Robots-Tag' => 'nofollow']),
    ]);

    $crawler->addHtmlContent(Http::get('vormkracht10.nl')->body());

    $this->assertFalse($check->check(Http::get('vormkracht10.nl'), $crawler));
});

it('can perform the nofollow check with robots metatag', function () {
    $check = new NoFollowCheck();
    $crawler = new Crawler();

    Http::fake([
        'vormkracht10.nl' => Http::response('<html><head><meta name="robots" content="nofollow"></head></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('vormkracht10.nl')->body());

    $this->assertFalse($check->check(Http::get('vormkracht10.nl'), $crawler));
});

it('can perform the nofollow check with googlebot metatag', function () {
    $check = new NoFollowCheck();
    $crawler = new Crawler();

    Http::fake([
        'vormkracht10.nl' => Http::response('<html><head><meta name="googlebot" content="nofollow"></head></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('vormkracht10.nl')->body());

    $this->assertFalse($check->check(Http::get('vormkracht10.nl'), $crawler));
});

it('can perform the noindex check with robots tag', function () {
    $check = new NoIndexCheck();
    $crawler = new Crawler();

    Http::fake([
        'vormkracht10.nl' => Http::response('', 200, ['X-Robots-Tag' => 'noindex']),
    ]);

    $crawler->addHtmlContent(Http::get('vormkracht10.nl')->body());

    $this->assertFalse($check->check(Http::get('vormkracht10.nl'), $crawler));
});

it('can perform the noindex check with robots metatag', function () {
    $check = new NoIndexCheck();
    $crawler = new Crawler();

    Http::fake([
        'vormkracht10.nl' => Http::response('<html><head><meta name="robots" content="noindex"></head></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('vormkracht10.nl')->body());

    $this->assertFalse($check->check(Http::get('vormkracht10.nl'), $crawler));
});

it('can perform the noindex check with googlebot metatag', function () {
    $check = new NoIndexCheck();
    $crawler = new Crawler();

    Http::fake([
        'vormkracht10.nl' => Http::response('<html><head><meta name="googlebot" content="noindex"></head></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('vormkracht10.nl')->body());

    $this->assertFalse($check->check(Http::get('vormkracht10.nl'), $crawler));
});

it('can perform the robots check', function () {
    $check = new RobotsCheck();

    Http::fake([
        'vormkracht10.nl/robots.txt' => Http::response('User-agent: Googlebot
            Disallow: /admin', 200),
    ]);

    $this->assertTrue($check->check(Http::get('vormkracht10.nl'), new Crawler()));
});

