# Moodoo

Moodoo est une application web développée avec le framework Symfony. Ce projet a été créé spécifiquement pour tester les capacités de l'IA Qwen3 dans le développement d'applications web modernes.

## À propos de ce projet

Ce projet est un blog fonctionnel avec une interface d'administration complète. Il a été conçu comme un cas de test pour évaluer les compétences de l'IA Qwen3 dans plusieurs domaines :

- Génération de code PHP/Symfony
- Création d'interfaces utilisateur avec Tailwind CSS
- Mise en place de systèmes de sécurité
- Gestion de bases de données avec Doctrine

## Fonctionnalités

### Interface publique
- Page d'accueil avec liste des articles
- Pages de détail des articles
- Système de commentaires
- Navigation par catégories

### Interface d'administration
- Tableau de bord
- Gestion des articles (CRUD)
- Gestion des catégories (CRUD)
- Modération des commentaires
- Authentification sécurisée

## Technologies utilisées

- **Backend** : PHP 8.2+, Symfony 7
- **Frontend** : Tailwind CSS, DaisyUI, Font Awesome
- **Base de données** : SQLite (développement), compatible MySQL/PostgreSQL
- **Authentification** : Symfony Security Component
- **ORM** : Doctrine ORM

## Installation

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/votre-compte/moodoo.git
   cd moodoo
   ```

2. Installez les dépendances :
   ```bash
   composer install
   npm install
   ```

3. Configurez les variables d'environnement :
   ```bash
   cp .env .env.local
   # Modifiez .env.local avec vos paramètres
   ```

4. Créez la base de données et les tables :
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. Chargez les données de test (optionnel) :
   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. Générez les assets :
   ```bash
   npm run build
   ```

7. Démarrez le serveur de développement :
   ```bash
   symfony serve
   # ou
   php bin/console server:run
   ```

## Utilisation de l'administration

Pour accéder à l'interface d'administration, vous devez d'abord créer un utilisateur administrateur :

```bash
php bin/console app:create-admin admin@example.com motdepasse
```

Ensuite, rendez-vous sur `/admin` et connectez-vous avec vos identifiants.

## Déploiement

Ce projet peut être déployé sur n'importe quel hébergeur supportant PHP 8.2+ et Symfony 7.
