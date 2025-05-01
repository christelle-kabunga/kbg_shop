<?php
// Connexion à la base de données
require_once(__DIR__ . '/../../connexion/connexion.php');

// Inclusion de la classe Produit
require_once(__DIR__ . '/../classes/Categorie.php');

class CategorieController {
    private $db;

    public function __construct($connexion) {
        $this->db = $connexion;
    }

    public function ajouterCategorie(Categorie $categorie) {
        $req = $this->db->prepare("INSERT INTO categorie (nom) VALUES (?)");
        return $req->execute([$categorie->getNom()]);
    }

    public function getCategories() {
        $req = $this->db->query("SELECT * FROM categorie ORDER BY id DESC");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifierCategorie(Categorie $categorie) {
        try {
            $req = $this->db->prepare("UPDATE categorie SET nom = ? WHERE id = ?");
            return $req->execute([
                $categorie->getNom(),
                $categorie->getId()
            ]);
        } catch (Exception $e) {
            return false; // Retourne false en cas d'erreur
        }
    }
    public function supprimerCategorie($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM categorie WHERE id = :id"); // Utilisation de $this->db
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute(); // Retourne true si la suppression réussit
        } catch (Exception $e) {
            return false; // Retourne false en cas d'erreur
        }
    }
    public function getCategorieById($id) {
        $req = $this->db->prepare("SELECT * FROM categorie WHERE id = ?");
        $req->execute([$id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
}
