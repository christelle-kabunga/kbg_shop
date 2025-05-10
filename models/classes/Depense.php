<?php
class Depense {
    private ?int $id;
    private string $libelle;
    private float $montant;
    private string $date;
    private string $auteur;

    public function __construct(?int $id, string $libelle, float $montant, string $date, string $auteur) {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->montant = $montant;
        $this->date = $date;
        $this->auteur = $auteur;
    }

    public function getId(): ?int { return $this->id; }
    public function getLibelle(): string { return $this->libelle; }
    public function getMontant(): float { return $this->montant; }
    public function getDate(): string { return $this->date; }
    public function getAuteur(): string { return $this->auteur; }
}
