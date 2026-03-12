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

        $this->assertResponseRedirects('http://localhost/login');
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

        // Créer une vraie image JPEG minimale pour le test
        $tmpFile = tempnam(sys_get_temp_dir(), 'test_image') . '.jpg';
        $img = imagecreatetruecolor(10, 10);
        imagejpeg($img, $tmpFile, 75);
        imagedestroy($img);
        $uploadedFile = new UploadedFile(
            $tmpFile,
            'tournament_test.jpg',
            'image/jpeg',
            null,
            true
        );

        $form = $crawler->selectButton('Créer')->form([
            'tournament[title]' => 'Tournoi de Test',
            'tournament[description]' => 'Description complète du tournoi de test',
            'tournament[tagline]' => 'Une tagline de test',
            'tournament[startAt]' => (new \DateTime('+1 day'))->format('Y-m-d\TH:i'),
            'tournament[endAt]' => (new \DateTime('+2 days'))->format('Y-m-d\TH:i'),
            'tournament[capacityGauge]' => '16',
        ]);

        $form['tournament[tournamentImage]']->upload($uploadedFile);

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

        $tmpFile2 = tempnam(sys_get_temp_dir(), 'test_image') . '.jpg';
        $img2 = imagecreatetruecolor(10, 10);
        imagejpeg($img2, $tmpFile2, 75);
        imagedestroy($img2);
        $uploadedFile = new UploadedFile(
            $tmpFile2,
            'tournament_test.jpg',
            'image/jpeg',
            null,
            true
        );

        $form = $crawler->selectButton('Créer')->form([
            'tournament[title]' => 'Tournoi de Test',
            'tournament[description]' => 'Description du tournoi',
            'tournament[tagline]' => 'Tagline',
            'tournament[startAt]' => (new \DateTime('+2 days'))->format('Y-m-d\TH:i'),
            'tournament[endAt]' => (new \DateTime('+1 day'))->format('Y-m-d\TH:i'), // Date de fin avant date de début
            'tournament[capacityGauge]' => '16',
        ]);

        $form['tournament[tournamentImage]']->upload($uploadedFile);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
    }
}
