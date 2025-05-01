<?php
class Stock {
    private ?int $id;
    private int  $produit_id;
    private string $type_mouvement;   // 'entrée' ou 'sortie'
    private int  $quantite;
    private string $date_mouvement;
    private ?int $utilisateur;

    public function __construct(
        int $produit_id,
        string $type_mouvement,
        int $quantite,
        string $date_mouvement,
        ?int $utilisateur = null,
        ?int $id = null
    ){
        $this->id            = $id;
        $this->produit_id    = $produit_id;
        $this->type_mouvement= $type_mouvement;
        $this->quantite      = $quantite;
        $this->date_mouvement= $date_mouvement;
        $this->utilisateur   = $utilisateur;
    }

    // GETTERS utilisés par le contrôleur
    public function getId(): ?int            { return $this->id; }
    public function getProduitId(): int      { return $this->produit_id; }
    public function getTypeMouvement(): string { return $this->type_mouvement; }
    public function getQuantite(): int       { return $this->quantite; }
    public function getDateMouvement(): string { return $this->date_mouvement; }
    public function getUtilisateur(): ?int   { return $this->utilisateur; }

    // SETTERS (facultatif)
    public function setId(int $id): void               { $this->id = $id; }
    public function setProduitId(int $p): void         { $this->produit_id = $p; }
    public function setTypeMouvement(string $t): void  { $this->type_mouvement = $t; }
    public function setQuantite(int $q): void          { $this->quantite = $q; }
    public function setDateMouvement(string $d): void  { $this->date_mouvement = $d; }
    public function setUtilisateur(?int $u): void      { $this->utilisateur = $u; }
}
