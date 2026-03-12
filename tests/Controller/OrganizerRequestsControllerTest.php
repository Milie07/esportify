<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrganizerRequestsControllerTest extends WebTestCase
{
    // Tester la redirection en cas de Non connecté → redirect login
    public function testRequestRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('POST', '/player/request');

        $this->assertResponseRedirects('http://localhost/login');
    }

    // Tester le ROLE_PLAYER connecté → flash success + redirect player_space
    public function testPlayerCanSendOrganizerRequest(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(\App\Entity\Member::class);
        $player = $userRepository->findOneBy(['pseudo' => 'TomPlayer']);

        if (!$player) {
            $this->markTestSkipped('Fixture TomPlayer introuvable');
        }

        $client->loginUser($player);
        $client->request('POST', '/player/request');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    // Tester le ROLE_ORGANIZER → accès refusé
    public function testOrganizerCannotSendRequest(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(\App\Entity\Member::class);
        $organizer = $userRepository->findOneBy(['pseudo' => 'HugoOrga']);

        if (!$organizer) {
            $this->markTestSkipped('Fixture HugoOrga introuvable');
        }

        $client->loginUser($organizer);
        $client->request('POST', '/player/request');

        $this->assertResponseStatusCodeSame(403);
    }
}
