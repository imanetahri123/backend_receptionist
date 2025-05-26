# API de Gestion de Réceptionniste - Documentation

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-^9.19-FF2D20.svg)

Une API RESTful complète pour la gestion des réceptionnistes, patients et rendez-vous dans un environnement médical.

## Table des matières

- [Vue d'ensemble](#vue-densemble)
- [Installation](#installation)
- [Configuration](#configuration)
- [Structure de la Base de données](#structure-de-la-base-de-données)
- [Documentation API](#documentation-api)
  - [Endpoints de Profil Réceptionniste](#endpoints-de-profil-réceptionniste)
  - [Endpoints de Gestion des Patients](#endpoints-de-gestion-des-patients)
  - [Endpoints de Rendez-vous (RendezVous)](#endpoints-de-rendez-vous-rendezvous)
  - [Endpoints d'Appointments (API alternative)](#endpoints-dappointments-api-alternative)

## Vue d'ensemble

Cette API backend fournit toutes les fonctionnalités nécessaires pour gérer un système de réception médicale, incluant la gestion des profils des réceptionnistes, la gestion des patients et la planification des rendez-vous.

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_REPO] receptionist-backend
   cd receptionist-backend
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configuration de la base de données**
   ```bash
   # Éditer le fichier .env avec les informations de connexion
   php artisan migrate --seed
   ```

5. **Démarrer le serveur de développement**
   ```bash
   php artisan serve
   ```

## Structure de la Base de données

### Tables principales

- **receptionists**: Stocke les informations sur les réceptionnistes
- **patients**: Stocke les informations sur les patients
- **rendez_vous**: Gestion des rendez-vous (version française)
- **appointments**: Gestion des rendez-vous (version anglaise alternative)

## Documentation API

### Endpoints de Profil Réceptionniste

#### Obtenir le Profil Réceptionniste

```
GET /api/profile
```

Récupère les informations du profil du réceptionniste (actuellement le premier réceptionniste).

**Réponse (200 OK):**
```json
{
  "name": "John Doe",
  "email": "john.doe@exemple.com",
  "phone": "+33123456789",
  "role": "Réceptionniste",
  "joinDate": "2025-01-01",
  "photo": "http://localhost:8000/uploads/profiles/photo_123456.jpg"
}
```

**Erreur (404 Not Found):**
```json
{
  "error": "Aucun réceptionniste trouvé"
}
```

#### Mettre à jour le Profil Réceptionniste

```
PUT /api/profile
```

Met à jour les informations du profil du réceptionniste.

**Corps de la Requête:**
```json
{
  "name": "John Doe",
  "email": "john.doe@exemple.com",
  "phone": "+33123456789"
}
```

**Réponse (200 OK):**
```json
{
  "success": true,
  "message": "Profil mis à jour avec succès"
}
```

**Erreurs (400 Bad Request):**
```json
{
  "error": "Le champ nom est obligatoire"
}
```

#### Changer le Mot de Passe

```
PUT /api/profile/password
```

Modifie le mot de passe du réceptionniste.

**Corps de la Requête:**
```json
{
  "newPassword": "nouveauMotDePasse123"
}
```

**Réponse (200 OK):**
```json
{
  "success": true,
  "message": "Mot de passe changé avec succès"
}
```

**Erreurs (400 Bad Request):**
```json
{
  "error": "Le mot de passe doit contenir au moins 6 caractères"
}
```

#### Mettre à jour la Photo de Profil

```
POST /api/profile/photo
```

Met à jour la photo de profil du réceptionniste.

**Corps de la Requête (multipart/form-data):**
- `photo`: Fichier image (jpeg, png, jpg, gif, max 2MB)

**Réponse (200 OK):**
```json
{
  "success": true,
  "photo": "http://localhost:8000/uploads/profiles/photo_123456.jpg",
  "message": "Photo mise à jour avec succès"
}
```

**Erreurs (400 Bad Request):**
```json
{
  "error": "Fichier non valide. Formats acceptés : jpeg, png, jpg, gif (max 2MB)",
  "details": {
    "photo": ["Le fichier doit être une image", "Le fichier ne doit pas dépasser 2048 kilooctets"]
  }
}
```

### Endpoints de Gestion des Patients

#### Lister Tous les Patients

```
GET /api/patients
```

Récupère la liste de tous les patients.

**Réponse (200 OK):**
```json
[
  {
    "id": 1,
    "name": "Patient Test",
    "email": "patient@example.com",
    "phone": "0123456789",
    "dob": "1990-01-01",
    "nationality": "Française",
    "blood_group": "O+",
    "marital_status": "Célibataire",
    "gender": "Homme",
    "address": "123 Rue de Test, 75000 Paris",
    "photo": "/storage/patients/photo123.jpg",
    "status": "Actif",
    "created_at": "2025-01-01T12:00:00.000000Z",
    "updated_at": "2025-01-01T12:00:00.000000Z"
  },
  // ...plus de patients
]
```

#### Créer un Patient

```
POST /api/patients
```

Crée un nouveau patient.

**Corps de la Requête (multipart/form-data):**
- `name`: Nom du patient (obligatoire)
- `email`: Email (obligatoire, unique)
- `phone`: Numéro de téléphone (optionnel)
- `dob`: Date de naissance (optionnel, format YYYY-MM-DD)
- `nationality`: Nationalité (optionnel)
- `blood_group`: Groupe sanguin (optionnel)
- `marital_status`: État civil (optionnel)
- `gender`: Genre (optionnel)
- `address`: Adresse (optionnel)
- `status`: Statut (optionnel)
- `photo`: Photo (optionnel, fichier image)

**Réponse (201 Created):**
```json
{
  "id": 1,
  "name": "Patient Test",
  "email": "patient@example.com",
  "phone": "0123456789",
  "dob": "1990-01-01",
  "nationality": "Française",
  "blood_group": "O+",
  "marital_status": "Célibataire",
  "gender": "Homme",
  "address": "123 Rue de Test, 75000 Paris",
  "photo": "/storage/patients/photo123.jpg",
  "status": "Actif",
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

#### Obtenir les Détails d'un Patient

```
GET /api/patients/{id}
```

Récupère les informations détaillées d'un patient spécifique.

**Paramètres:**
- `id`: ID du patient

**Réponse (200 OK):**
```json
{
  "id": 1,
  "name": "Patient Test",
  "email": "patient@example.com",
  "phone": "0123456789",
  "dob": "1990-01-01",
  "nationality": "Française",
  "blood_group": "O+",
  "marital_status": "Célibataire",
  "gender": "Homme",
  "address": "123 Rue de Test, 75000 Paris",
  "photo": "/storage/patients/photo123.jpg",
  "status": "Actif",
  "created_at": "2025-01-01T12:00:00.000000Z",
  "updated_at": "2025-01-01T12:00:00.000000Z"
}
```

**Erreur (404 Not Found):**
```json
{
  "message": "No query results for model [App\\Models\\Patient] 999"
}
```

#### Mettre à Jour un Patient

```
PUT /api/patients/{id}
```

Met à jour les informations d'un patient existant.

**Paramètres:**
- `id`: ID du patient

**Corps de la Requête (multipart/form-data):**
*Les mêmes champs que la création, tous optionnels*

**Réponse (200 OK):**
```json
{
  "id": 1,
  "name": "Patient Test Modifié",
  "email": "patient.modified@example.com",
  // ...autres champs mis à jour
  "updated_at": "2025-01-02T12:00:00.000000Z"
}
```

#### Supprimer un Patient

```
DELETE /api/patients/{id}
```

Supprime un patient du système.

**Paramètres:**
- `id`: ID du patient

**Réponse (204 No Content):**
*Pas de contenu dans la réponse en cas de succès*

### Endpoints de Rendez-vous (RendezVous)

#### Lister les Rendez-vous

```
GET /api/rendez_vous
```

Récupère la liste des rendez-vous avec pagination, filtrage et recherche.

**Paramètres de requête:**
- `date`: Filtre par date (format YYYY-MM-DD)
- `statut`: Filtre par statut ('À Venir', 'En Cours', 'Terminé', 'Annulé')
- `search`: Recherche par nom ou prénom du patient
- `page`: Numéro de page pour la pagination

**Réponse (200 OK):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "nom_patient": "Dupont",
      "prenom_patient": "Jean",
      "date_heure": "2025-05-26 09:00:00",
      "type": "Consultation",
      "statut": "À Venir",
      "rappel": "Apporter les résultats d'analyse",
      "created_at": "2025-05-20T10:30:00.000000Z",
      "updated_at": "2025-05-20T10:30:00.000000Z"
    },
    // ...plus de rendez-vous
  ],
  "first_page_url": "http://localhost:8000/api/rendez_vous?page=1",
  "from": 1,
  "last_page": 5,
  "last_page_url": "http://localhost:8000/api/rendez_vous?page=5",
  "links": [
    // ...liens de pagination
  ],
  "next_page_url": "http://localhost:8000/api/rendez_vous?page=2",
  "path": "http://localhost:8000/api/rendez_vous",
  "per_page": 10,
  "prev_page_url": null,
  "to": 10,
  "total": 50
}
```

#### Créer un Rendez-vous

```
POST /api/rendez_vous
```

Crée un nouveau rendez-vous.

**Corps de la Requête:**
```json
{
  "nom_patient": "Dupont",
  "prenom_patient": "Jean",
  "date_heure": "2025-05-26 09:00:00",
  "type": "Consultation",
  "statut": "À Venir",
  "rappel": "Apporter les résultats d'analyse"
}
```

**Réponse (201 Created):**
```json
{
  "message": "Rendez-vous ajouté avec succès",
  "rendez_vous": {
    "nom_patient": "Dupont",
    "prenom_patient": "Jean",
    "date_heure": "2025-05-26 09:00:00",
    "type": "Consultation",
    "statut": "À Venir",
    "rappel": "Apporter les résultats d'analyse",
    "updated_at": "2025-05-25T10:30:00.000000Z",
    "created_at": "2025-05-25T10:30:00.000000Z",
    "id": 51
  }
}
```

#### Obtenir les Détails d'un Rendez-vous

```
GET /api/rendez_vous/{id}
```

Récupère les informations détaillées d'un rendez-vous spécifique.

**Paramètres:**
- `id`: ID du rendez-vous

**Réponse (200 OK):**
```json
{
  "id": 1,
  "nom_patient": "Dupont",
  "prenom_patient": "Jean",
  "date_heure": "2025-05-26 09:00:00",
  "type": "Consultation",
  "statut": "À Venir",
  "rappel": "Apporter les résultats d'analyse",
  "created_at": "2025-05-20T10:30:00.000000Z",
  "updated_at": "2025-05-20T10:30:00.000000Z"
}
```

#### Mettre à Jour un Rendez-vous

```
PUT /api/rendez_vous/{id}
```

Met à jour les informations d'un rendez-vous existant.

**Paramètres:**
- `id`: ID du rendez-vous

**Corps de la Requête:**
```json
{
  "nom_patient": "Dupont",
  "prenom_patient": "Jean",
  "date_heure": "2025-05-26 10:00:00",
  "type": "Suivi",
  "statut": "En Cours",
  "rappel": "Modification de l'horaire"
}
```

**Réponse (200 OK):**
```json
{
  "message": "Rendez-vous mis à jour avec succès",
  "rendez_vous": {
    "id": 1,
    "nom_patient": "Dupont",
    "prenom_patient": "Jean",
    "date_heure": "2025-05-26 10:00:00",
    "type": "Suivi",
    "statut": "En Cours",
    "rappel": "Modification de l'horaire",
    "created_at": "2025-05-20T10:30:00.000000Z",
    "updated_at": "2025-05-25T11:15:00.000000Z"
  }
}
```

#### Supprimer un Rendez-vous

```
DELETE /api/rendez_vous/{id}
```

Supprime un rendez-vous du système.

**Paramètres:**
- `id`: ID du rendez-vous

**Réponse (200 OK):**
```json
{
  "message": "Rendez-vous supprimé avec succès"
}
```

### Endpoints d'Appointments (API alternative)

#### Lister Tous les Appointments

```
GET /api/appointments
```

Récupère la liste de tous les rendez-vous (version API alternative en anglais).

**Réponse (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "time": "2025-05-26 09:00:00",
      "patient": "Jean Dupont",
      "type": "Consultation",
      "status": "upcoming",
      "status_label": "À Venir",
      "reminder": "Apporter les résultats d'analyse",
      "created_at": "20/05/2025 10:30",
      "updated_at": "20/05/2025 10:30"
    },
    // ...plus d'appointments
  ]
}
```

#### Créer un Appointment

```
POST /api/appointments
```

Crée un nouveau rendez-vous (version API alternative).

**Corps de la Requête:**
```json
{
  "time": "2025-05-26 09:00:00",
  "patient": "Jean Dupont",
  "type": "Consultation",
  "status": "upcoming",
  "reminder": "Apporter les résultats d'analyse"
}
```

**Réponse (201 Created):**
```json
{
  "success": true,
  "data": {
    "time": "2025-05-26 09:00:00",
    "patient": "Jean Dupont",
    "type": "Consultation",
    "status": "upcoming",
    "status_label": "À Venir",
    "reminder": "Apporter les résultats d'analyse",
    "updated_at": "25/05/2025 10:30",
    "created_at": "25/05/2025 10:30",
    "id": 51
  },
  "message": "Rendez-vous créé avec succès."
}
```

#### Obtenir les Détails d'un Appointment

```
GET /api/appointments/{id}
```

Récupère les informations détaillées d'un rendez-vous spécifique (version API alternative).

**Paramètres:**
- `id`: ID du rendez-vous

**Réponse (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "time": "2025-05-26 09:00:00",
    "patient": "Jean Dupont",
    "type": "Consultation",
    "status": "upcoming",
    "status_label": "À Venir",
    "reminder": "Apporter les résultats d'analyse",
    "created_at": "20/05/2025 10:30",
    "updated_at": "20/05/2025 10:30"
  }
}
```

#### Mettre à Jour un Appointment

```
PUT /api/appointments/{id}
```

Met à jour les informations d'un rendez-vous existant (version API alternative).

**Paramètres:**
- `id`: ID du rendez-vous

**Corps de la Requête:**
```json
{
  "time": "2025-05-26 10:00:00",
  "type": "Suivi",
  "status": "completed"
}
```

**Réponse (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "time": "2025-05-26 10:00:00",
    "patient": "Jean Dupont",
    "type": "Suivi",
    "status": "completed",
    "status_label": "Terminé",
    "reminder": "Apporter les résultats d'analyse",
    "created_at": "20/05/2025 10:30",
    "updated_at": "25/05/2025 11:15"
  },
  "message": "Rendez-vous mis à jour."
}
```

#### Supprimer un Appointment

```
DELETE /api/appointments/{id}
```

Supprime un rendez-vous du système (version API alternative).

**Paramètres:**
- `id`: ID du rendez-vous

**Réponse (200 OK):**
```json
{
  "success": true,
  "message": "Rendez-vous supprimé avec succès."
}
```



