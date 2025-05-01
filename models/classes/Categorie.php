<?php

class Categorie {
    private $id;
    private $nom;

    public function __construct($nom, $id = null) {
        $this->id = $id;
        $this->nom = $nom;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }

    public function setNom($nom) { $this->nom = $nom; }

    public function toArray() {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
        ];
    }
}