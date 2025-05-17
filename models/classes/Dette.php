<?php

class Dette {
    private ?int $id;
    private string $client;
    private float $montant;
    private string $date_dette;
    private int $utilisateur_id;
    public float $montant_restant; // Montant restant à payer

    public function __construct(?int $id, string $client, float $montant, string $date_dette, int $utilisateur_id, float $montant_restant) {
        $this->id = $id;
        $this->client = $client;
        $this->montant = $montant;
        $this->date_dette = $date_dette;
        $this->utilisateur_id = $utilisateur_id;
        $this->montant_restant = $montant_restant;
    }    

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getClient(): string {
        return $this->client;
    }

    public function getMontant(): float {
        return $this->montant;
    }

    public function getDateDette(): string {
        return $this->date_dette;
    }

    public function getUtilisateurId(): int {
        return $this->utilisateur_id;
    }

    // Setters
    public function setClient(string $client): void {
        $this->client = $client;
    }

    public function setMontant(float $montant): void {
        $this->montant = $montant;
    }

    public function setDateDette(string $date_dette): void {
        $this->date_dette = $date_dette;
    }

    public function setUtilisateurId(int $utilisateur_id): void {
        $this->utilisateur_id = $utilisateur_id;
    }
    public function setMontantRestant(float $montant_restant): void {
        $this->montant_restant = $montant_restant;
    }
}
