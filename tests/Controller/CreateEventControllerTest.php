<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateEventControllerTest extends WebTestCase
{
    public function testCreateEventPageRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/events/create');

        $this->assertResponseRedirects('/login');
    }

    public function testCreateEventPageAccessibleForOrganizer(): void
    {
        $client = static::createClient();

        // Simuler une authentification en tant qu'organisateur
        $userRepository = static::getContainer()->get('doctrine')->getRepository(\App\Entity\Member::class);
        $testUser = $userRepository->findOneBy(['pseudo' => 'HugoOrga']);

        if ($testUser) {
            $client->loginUser($testUser);
            $client->request('GET', '/events/create');

            $this->assertResponseIsSuccessful();
        } else {
            $this->markTestSkipped('Utilisateur de test HugoOrga introuvable');
        }
    }

    public function testCreateEventWithValidData(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(\App\Entity\Member::class);
        $testUser = $userRepository->findOneBy(['pseudo' => 'HugoOrga']);

        if (!$testUser) {
            $this->markTestSkipped('Utilisateur de test HugoOrga introuvable');
            return;
        }

        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/events/create');

        // Créer une fausse image pour le test
        $image = tempnam(sys_get_temp_dir(), 'test_image');
        file_put_contents($image, 'fake image content');
        $uploadedFile = new UploadedFile(
            $image,
            'tournament_test.jpg',
            'image/jpeg',
            null,
            true
        );

        $form = $crawler->selectButton('submit')->form([
            'tournament_type[title]' => 'Tournoi de Test',
            'tournament_type[description]' => 'Description complète du tournoi de test',
            'tournament_type[tagline]' => 'Une tagline de test',
            'tournament_type[startAt]' => (new \DateTime('+1 day'))->format('Y-m-d\TH:i'),
            'tournament_type[endAt]' => (new \DateTime('+2 days'))->format('Y-m-d\TH:i'),
            'tournament_type[capacityGauge]' => '16',
        ]);

        $form['tournament_type[tournamentImage]']->upload($uploadedFile);

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testCreateEventWithInvalidDates(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get('doctrine')->getRepository(\App\Entity\Member::class);
        $testUser = $userRepository->findOneBy(['pseudo' => 'HugoOrga']);

        if (!$testUser) {
            $this->markTestSkipped('Utilisateur de test HugoOrga introuvable');
            return;
        }

        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/events/create');

        $image = tempnam(sys_get_temp_dir(), 'test_image');
        file_put_contents($image, 'fake image content');
        $uploadedFile = new UploadedFile(
            $image,
            'tournament_test.jpg',
            'image/jpeg',
            null,
            true
        );

        $form = $crawler->selectButton('submit')->form([
            'tournament_type[title]' => 'Tournoi de Test',
            'tournament_type[description]' => 'Description du tournoi',
            'tournament_type[tagline]' => 'Tagline',
            'tournament_type[startAt]' => (new \DateTime('+2 days'))->format('Y-m-d\TH:i'),
            'tournament_type[endAt]' => (new \DateTime('+1 day'))->format('Y-m-d\TH:i'), // Date de fin avant date de début
            'tournament_type[capacityGauge]' => '16',
        ]);

        $form['tournament_type[tournamentImage]']->upload($uploadedFile);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
    }
}
