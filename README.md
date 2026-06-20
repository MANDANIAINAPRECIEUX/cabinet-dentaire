# cabinet-dentaire
# Dental Clinic Management System

Application web de gestion de cabinet dentaire développée en **PHP**, **MySQL** et **Tailwind CSS**.

Ce projet permet de gérer efficacement les activités quotidiennes d’un cabinet dentaire à travers une interface moderne, responsive et professionnelle.  
Il centralise les informations des patients, les recettes, les dépenses ainsi que les statistiques du cabinet sur des périodes journalières, mensuelles et annuelles.

## Fonctionnalités

- Enregistrement et gestion des patients
- Modification et suppression des fiches patients
- Gestion des recettes
- Gestion des dépenses
- Tableau de bord journalier
- Tableau de bord mensuel
- Tableau de bord annuel
- Calcul automatique des totaux et bénéfices
- Interface d’administration moderne - Tailwind CSS
- Authentification des utilisateurs
- Architecture propre et évolutive

## Modules principaux

### 1. Gestion des patients
Le système permet d’ajouter, consulter, modifier et supprimer les informations des patients.

Exemples d’informations enregistrées :
- Nom et prénom
- Sexe
- age
- diagnostique
- Adresse
- Antécédents médicaux
- Observations

### 2. Gestion des recettes
Le module des recettes permet d’enregistrer tous les paiements effectués par les patients.

Exemples :
- Consultation
- Détartrage
- Extraction
- Soins dentaires
- Prothèses
- Autres actes médicaux

### 3. Gestion des dépenses
Le module des dépenses permet de suivre toutes les charges du cabinet.

Exemples :
- Achat de matériel
- Paiement du loyer
- Factures d’électricité ou d’eau
- Salaires
- Maintenance
- Fournitures médicales

### 4. Tableau de bord
Le tableau de bord permet d’avoir une vue globale sur l’activité du cabinet.

#### Indicateurs journaliers
- Nombre de patients enregistrés aujourd’hui
- Total des recettes du jour
- Total des dépenses du jour
- Bénéfice du jour

#### Indicateurs mensuels
- Total des recettes du mois
- Total des dépenses du mois
- Bénéfice mensuel
- Nombre de nouveaux patients

#### Indicateurs annuels
- Total annuel des recettes
- Total annuel des dépenses
- Bénéfice annuel
- Evolution de l’activité

## Technologies utilisées

- PHP
- MySQL
- Tailwind CSS
- HTML5
- JavaScript
- Chart.js

## Objectif du projet

L’objectif de cette application est de digitaliser la gestion d’un cabinet dentaire afin de :

- centraliser les données
- améliorer le suivi administratif
- faciliter la gestion financière
- produire des statistiques utiles à la prise de décision
- offrir une interface moderne et professionnelle

## Structure du projet

```bash
project/
│
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
│
├── config/
├── public/
├── routes/
├── database/
└── README.md
```



### Prérequis

- PHP 8+
- MySQL
- Serveur local (XAMPP, Laragon ou WAMP)
- Composer (optionnel selon l’architecture)

### Étapes

1. Cloner le projet :
```bash
git clone https://github.com/votre-utilisateur/dental-clinic-management.git
```

2. Copier le projet dans le dossier du serveur local.

3. Créer une base de données MySQL.

4. Importer le fichier SQL du projet.

5. Configurer les accès à la base de données dans le fichier de configuration.

6. Lancer le serveur local et ouvrir le projet dans le navigateur.

## Sécurité

Le projet peut être amélioré avec les éléments suivants :
- hashage des mots de passe
- validation des formulaires
- protection CSRF
- gestion des rôles utilisateurs
- journalisation des actions
- sauvegarde des données

## Améliorations futures

- Gestion des rendez-vous
- Génération de factures PDF
- Export Excel/CSV
- Notifications par email ou SMS
- Gestion des utilisateurs et permissions
- Historique des soins dentaires
- Impression des rapports
- Version multi-cabinets

## Auteur

Projet développé dans le cadre de la conception d’un logiciel de gestion de cabinet dentaire en PHP from scratch avec Tailwind CSS.

## Licence

Ce projet est libre pour usage académique et personnel.
