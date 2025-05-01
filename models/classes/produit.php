<?php

class Produit {
    private ?int $id;
    private string $nom;
    private float $prix;
    private int $quantite;
    private string $categorie;
    private int $seuil;

    public function __construct(string $nom, string $categorie, int $seuil, float $prix, int $quantite, ?int $id = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->quantite = $quantite;
        $this->categorie = $categorie;
        $this->seuil = $seuil;
    }
    
    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrix(): float { return $this->prix; }
    public function getQuantite(): int { return $this->quantite; }
    public function getCategorie(): string { return $this->categorie; }
    public function getSeuil(): int { return $this->seuil; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setPrix(float $prix): void { $this->prix = $prix; }
    public function setQuantite(int $quantite): void { $this->quantite = $quantite; }
    public function setCategorie(string $categorie): void { $this->categorie = $categorie; }
    public function setSeuil(int $seuil): void { $this->seuil = $seuil; }
}
