# Cabinet Médical - Système de Gestion des Rendez-vous

Une application Laravel moderne pour la gestion des rendez-vous médicaux, offrant des interfaces distinctes pour les médecins et les patients, une recherche en temps réel, un support multilingue et une API REST.

## 📋 Fonctionnalités Principales
* **Rôles (RBAC) :** Espaces séparés pour `doctor` (gestion globale) et `patient` (gestion personnelle).
* **Rendez-vous :** Création, modification, suppression et recherche en temps réel (Axios).
* **Notifications :** Envoi automatique d'emails de confirmation lors de la création d'un rendez-vous.
* **Multilingue :** Support de l'anglais, l'espagnol, l'arabe et le français.
* **API REST :** Points d'accès pour l'intégration de systèmes tiers.

## 🚀 Installation

Suivez ces étapes pour configurer le projet localement :

1. **Cloner le dépôt :**
   ```bash
   git clone git@github.com:Zaidn4/medical-app.git
   cd cabinet-medical

2. **Installer les dépendances PHP et Node :**
    ```bash
    composer install
    npm install
    npm run build

3. **Configuration de l'environnement :**
    ```bash
    cp .env.example .env
    php artisan key:generate
Configurez vos accès base de données et votre serveur SMTP (ou MAIL_MAILER=log) dans le fichier .env.

4. **Migrations et Seeders :**
Cette commande va créer les tables et populer la base avec les données de test (utilisateurs, services, etc.).
    ```bash
    php artisan migrate --seed

5. **Lancer le serveur :**
    ```bash
    php artisan serve
L'application sera accessible sur http://localhost:8000.


## 🔐 Identifiants par Défaut (Seeders)
Une fois les seeders exécutés (php artisan migrate --seed), vous pouvez vous connecter avec les comptes de test suivants :
Rôle                              Email                      Mot de passe    
Médecin                     doctor@example.com                 password
Patient                     patient@example.com                password
(Note : Ajustez ces emails si vous avez défini d'autres valeurs exactes dans vos fichiers DatabaseSeeder.php)  


## 📡 Documentation de l'API REST
L'application expose une API pour interagir avec les rendez-vous.
1. Lister les rendez-vous:
    Endpoint : GET /api/appointments
    Description : Retourne la liste complète des rendez-vous au format JSON (incluant les relations : patient, docteur, service).
    Headers requis : Accept: application/json
    Exemple de réponse :
```bash
JSON[
{
"id": 1,
"patient_id": 2,
"doctor_id": 1,
"service_id": 1,
"appointment_date": "2026-05-15T10:00:00.000000Z",
"status": "confirmed",
"patient": { "id": 2, "name": "Jean Dupont" },
"doctor": { "id": 1, "name": "Dr. House" },
"service": { "id": 1, "name": "Consultation Générale" }
}
]

2. Créer un rendez-vous
*Endpoint : POST /api/appointmentsDescription : Crée un nouveau rendez-vous via une requête externe.
*Headers requis : Accept: application/json, Content-Type: application/jsonPayload (Corps de la requête) :

```bash
JSON{
    "patient_id": 2,
    "doctor_id": 1,
    "service_id": 1,
    "appointment_date": "2026-05-15 10:00:00",
    "status": "confirmed",
    "notes": "Première consultation"
}
Exemple de réponse (201 Created) :

```bash
JSON{
    "message": "Rendez-vous créé avec succès",
    "data": {
        "patient_id": 2,
        "doctor_id": 1,
        "service_id": 1,
        "appointment_date": "2026-05-15T10:00:00.000000Z",
        "status": "confirmed",
        "notes": "Première consultation",
        "id": 15
    }
}
