<?php

namespace App\Services;

use App\Entity\Job;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UpdateDatabase
{
    private $client;
    protected $entityManager;
    protected $token;
    protected $tokenExpireIn;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->token = null;
        $this->tokenExpireIn = null;
    }

    private function clearJobTable()
    {
        $connection = $this->entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('job'));
    }

    private function needGetToken()
    {
        return ($this->token && $this->tokenExpireIn >= new DateTime()) ? false : true;
    }

    private function getToken()
    {
        $response = $this->client->request('POST', 'https://entreprise.pole-emploi.fr/connexion/oauth2/access_token', [
            'headers' => [],
            'body' => [
                'grant_type' => 'client_credentials',
                'client_id' => $_ENV['CLIENT_ID'],
                'client_secret' => $_ENV['CLIENT_SECRET'],
                'scope' => 'application_' . $_ENV['CLIENT_ID'] . ' api_offresdemploiv2 o2dsoffre',
            ],
            'query' => [
                'realm' => '/partenaire',
            ],
        ]);
        $res = json_decode($response->getContent());
        $this->tokenExpireIn = date_add(new DateTime(), date_interval_create_from_date_string('1499 seconds'));
        return $res->access_token;
    }

    public function refreshJobTable()
    {
        $this->clearJobTable();

        if ($this->needGetToken()) {
            $this->token = $this->getToken();
        }

        $response = $this->client->request('GET', 'https://api.emploi-store.fr/partenaire/offresdemploi/v2/offres/search', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ],
            'query' => [
                'commune' => '33063,35238',
                'distance' => '0',
            ],
        ]);
        $offers = json_decode($response->getContent(),true)["resultats"];

        $response = $this->client->request('GET', 'https://api.emploi-store.fr/partenaire/offresdemploi/v2/offres/search', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ],
            'query' => [
                'departement' => '75'
            ],
        ]);

        $offers = [...$offers, ...json_decode($response->getContent(),true)["resultats"]];

        foreach ($offers as $offer) {
            $job = new Job();
            $job->setTitle($offer["intitule"]);
            $job->setDescription($offer["description"]);
            $job->setContractType($offer["typeContrat"]);
            $job->setContractNature($offer["natureContrat"]);
            $job->setCompany($offer["entreprise"]["nom"] ?? 'non renseigné');
            $job->setCity($offer["lieuTravail"]["libelle"]);
            $job->setUrl($offer["origineOffre"]["urlOrigine"]);
            $job->setCreationDate(date_create_from_format('Y-m-d\TH:i:s.\0\0\0\Z', $offer["dateCreation"]));
            $job->setUpdateDate(date_create_from_format('Y-m-d\TH:i:s.\0\0\0\Z', $offer["dateActualisation"]));
            $this->entityManager->persist($job);
        }
        $this->entityManager->flush();
    }
}
