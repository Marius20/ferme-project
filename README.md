# 🚜 FarmManager - Système de Gestion d'Élevage Multi-Fermes

Application complète de gestion d'élevage développée avec **Symfony 7** et **TailwindCSS**, utilisant les **Live Components** pour une interface interactive moderne.

## ✨ Fonctionnalités

### 🏢 Gestion Multi-Fermes
- Création et gestion de plusieurs exploitations
- Informations complètes (coordonnées, SIRET, superficie)
- Suivi individualisé par ferme

### 🐄 Module Animaux
- **Bovins** : Vaches laitières, taureaux, veaux
- **Ovins** : Brebis, agnelage, engraissement
- **Caprins** : Chèvres laitières, reproduction
- **Porcins** : Truies, porcelets, cochons de chair
- **Équins** : Chevaux de travail et de loisir
- Suivi généalogique et sanitaire
- Modes reproduction/engraissement

### 🐓 Module Volailles Spécialisé
- Gestion par lots (poules pondeuses, poulets de chair)
- Suivi production d'œufs quotidienne
- Calcul taux de mortalité
- Gestion des races et âges
- Statistiques de production détaillées

### 📦 Gestion Stocks & Approvisionnement
- Inventaire aliments, médicaments, équipements
- Alertes seuils critiques automatiques
- Suivi dates de péremption
- Gestion fournisseurs et prix

### 💰 Finance & Suivi Économique
- Comptabilité recettes/dépenses par catégorie
- Analyses de rentabilité mensuelle/annuelle
- Graphiques d'évolution financière
- Rapports automatisés
- Calculs de marges et bénéfices

### 👥 Gestion Personnel
- Fiches employés complètes
- Postes, salaires, dates d'embauche
- Affectation multi-fermes

### 📊 Dashboard Interactif
- Vue d'ensemble temps réel
- Sélection dynamique des fermes
- Alertes automatiques
- Statistiques détaillées par type d'animal
- Indicateurs financiers

## 🛠️ Technologies Utilisées

- **Backend** : Symfony 7 (PHP 8.2+)
- **Frontend** : Symfony UX Live Components + Twig
- **Styling** : TailwindCSS avec composants personnalisés
- **Base de données** : Doctrine ORM (MySQL/PostgreSQL)
- **Build** : Webpack Encore
- **Architecture** : Repository Pattern, Entity Relations

## 🚀 Installation et Configuration

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- Serveur MySQL ou PostgreSQL

### Installation

```bash
# Cloner et installer les dépendances
composer install
npm install

# Configuration base de données
# Modifier DATABASE_URL dans .env
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name"

# Créer la base de données et les tables
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Charger les données d'exemple
php bin/console doctrine:fixtures:load

# Compiler les assets
npm run build

# Démarrer le serveur
symfony server:start
# ou
php -S 127.0.0.1:8000 -t public
```

## 📁 Structure du Projet

```
ferme-project/
├── src/
│   ├── Controller/          # Contrôleurs web
│   ├── Entity/              # Entités Doctrine
│   ├── Repository/          # Repositories avec requêtes métier
│   ├── Twig/Components/     # Live Components
│   └── DataFixtures/        # Données d'exemple
├── templates/
│   ├── base.html.twig       # Template de base
│   ├── dashboard/           # Vues dashboard
│   ├── animaux/            # Gestion animaux
│   ├── volailles/          # Module volailles
│   ├── stocks/             # Gestion stocks
│   ├── finances/           # Module financier
│   └── components/         # Templates des Live Components
├── assets/
│   ├── app.js              # Point d'entrée JavaScript
│   └── styles/app.css      # Styles TailwindCSS
└── webpack.config.js       # Configuration Encore
```

## 🗄️ Modèle de Données

### Entités Principales
- `Ferme` : Informations fermes
- `Animal` : Bovins, ovins, caprins, porcins, équins
- `Volaille` : Gestion spécialisée volailles par lots
- `Stock` : Inventaire et approvisionnements
- `Transaction` : Écritures financières
- `Employe` : Personnel
- `ProductionOeuf` : Production journalière œufs

### Relations
- Relations OneToMany entre Ferme et autres entités
- Suivi historique production et transactions
- Contraintes d'intégrité référentielle

## 🎯 Fonctionnalités Avancées

### Live Components
- Dashboard interactif avec mise à jour temps réel
- Sélection dynamique des fermes
- Affichage conditionnel des données

### Alertes Automatiques
- Stocks en dessous des seuils
- Produits proches de la péremption
- Taux de mortalité élevés

### Rapports et Analytics
- Évolution financière sur 6 mois
- Statistiques de production par type
- Analyses de rentabilité par activité

## 📊 Données d'Exemple Incluses

L'application inclut des fixtures complètes avec :
- 2 fermes d'exemple avec données réalistes
- Animaux et volailles avec historiques
- Transactions financières sur plusieurs mois
- Stocks avec alertes configurées
- Production d'œufs quotidienne
- Personnel avec postes et salaires

## 🔧 Commandes Utiles

```bash
# Mode développement - watch des assets
npm run dev

# Build production
npm run build

# Recharger les fixtures (⚠️ supprime les données)
php bin/console doctrine:fixtures:load --purge-with-truncate

# Nettoyer le cache
php bin/console cache:clear

# Vérifier la configuration
php bin/console about
```

## 📱 Interface Utilisateur

- **Design responsive** adapté mobile/desktop
- **Interface moderne** avec TailwindCSS
- **Navigation intuitive** par modules
- **Tableaux interactifs** avec filtres
- **Indicateurs visuels** (graphiques, badges, alertes)
- **Actions contextuelles** sur chaque élément

## 🚦 Roadmap

### Fonctionnalités à venir
- [ ] Module reproduction avec calendriers
- [ ] Planification des interventions vétérinaires  
- [ ] Gestion des parcelles et rotations
- [ ] Intégration comptable avancée
- [ ] API REST pour applications mobiles
- [ ] Notifications automatiques
- [ ] Exports PDF et Excel
- [ ] Gestion multi-utilisateurs avec permissions

## 📞 Support

Pour toute question ou problème :
- Consulter les logs dans `var/log/`
- Vérifier la configuration Doctrine
- S'assurer que le serveur de base de données est démarré

## 📄 Licence

Projet développé dans le cadre d'une démonstration technique Symfony 7.

---

*Système complet de gestion d'élevage moderne, évolutif et adaptable à tous types d'exploitations agricoles.*