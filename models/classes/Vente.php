<?php
class Vente {
    public function __construct(
        private int $produit_id,
        private int $quantite,
        private float $prix_unitaire,
        private string $date_vente,
        private int $utilisateur = 0,
        private ?int $id = null
    ) {}

    // GETTERS
    public function getId()          { return $this->id; }
    public function getProduitId()   { return $this->produit_id; }
    public function getQuantite()    { return $this->quantite; }
    public function getPrixU()       { return $this->prix_unitaire; }
    public function getTotal()       { return $this->quantite * $this->prix_unitaire; }
    public function getDateVente()   { return $this->date_vente; }
    public function getUtilisateur() { return $this->utilisateur; }
}
