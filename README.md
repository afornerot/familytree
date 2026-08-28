# FamilyTree

Application web d'arbre généalogique développée avec Symfony et Docker.

## Fonctionnalités

- Arbre généalogique interactif (D3.js)
- Gestion des individus (ajout, modification, suppression)
- Liens parentaux (père/mère/enfant)
- Mariages multiples
- Ancêtres lointains
- Galerie d'images et conteneur de fichiers (BnineFilesBundle)
- Photo de profil avec crop
- Recherche d'individus
- Gestion des droits (admin / lecture seule)

## Technologies

- **Backend** : Symfony 7.3 / PHP 8.4
- **Frontend** : Bootstrap 5 (Darkly), D3.js, jQuery, Select2
- **Base de données** : MariaDB
- **Conteneurisation** : Docker / Docker Compose
- **Upload** : BnineFilesBundle + OneupUploaderBundle

## Installation

```bash
git clone <url>
docker compose up -d
sudo chmod a+w uploads -R
```

L'application est accessible sur `http://localhost:8031`.

## Utilisation

- `/arbre` : Arbre généalogique interactif
- `/individu` : Liste des individus
- `/individu/{id}` : Fiche détaillée d'un individu
- `/admin/individu` : Gestion des individus (admin)

## Droits d'accès

| Route | Accès |
|-------|-------|
| `/arbre` | Public |
| `/individu` | Public |
| `/individu/{id}` | Public |
| `/admin/*` | Admin |

## Structure

```
src/
├── Controller/     # Contrôleurs (Arbre, Individu, Mariage, etc.)
├── Entity/         # Entités Doctrine (Individu, Mariage)
├── Form/           # Formulaires (IndividuType, MariageType)
├── Repository/     # Repositories
└── Security/       # Voters et authentification
```
