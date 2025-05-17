<?php

class PaiementDette {
    private int $dette_id;
    private float $montant_paye;
    private string $date_paiement;
    private int $utilisateur_id;

    public function __construct(int $dette_id, float $montant_paye, string $date_paiement, int $utilisateur_id) {
        $this->dette_id = $dette_id;
        $this->montant_paye = $montant_paye;
        $this->date_paiement = $date_paiement;
        $this->utilisateur_id = $utilisateur_id;
    }

    public function getDetteId(): int { return $this->dette_id; }
    public function getMontantPaye(): float { return $this->montant_paye; }
    public function getDatePaiement(): string { return $this->date_paiement; }
    public function getUtilisateurId(): int { return $this->utilisateur_id; }
}
