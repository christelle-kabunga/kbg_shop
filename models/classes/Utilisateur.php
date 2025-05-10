<?php
class Utilisateur {
    private int $id;
    private string $noms;
    private string $email;
    private string $mot_de_passe;
    private string $role;
    private int $actif;

    public function __construct($id = 0, $noms = "", $email = "", $mot_de_passe = "", $role = "vendeur", $actif = 1) {
        $this->id = $id;
        $this->noms = $noms;
        $this->email = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->role = $role;
        $this->actif = $actif;
    }

    // Getters & setters
    public function getId(): int { return $this->id; }
    public function getNoms(): string { return $this->noms; }
    public function getEmail(): string { return $this->email; }
    public function getMotDePasse(): string { return $this->mot_de_passe; }
    public function getRole(): string { return $this->role; }
    public function getActif(): int { return $this->actif; }

    public function setNoms(string $n): void { $this->noms = $n; }
    public function setEmail(string $e): void { $this->email = $e; }
    public function setMotDePasse(string $m): void { $this->mot_de_passe = $m; }
    public function setRole(string $r): void { $this->role = $r; }
    public function setActif(int $a): void { $this->actif = $a; }
}
