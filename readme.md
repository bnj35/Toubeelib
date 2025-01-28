## Toubelib

### Participant

AUGER Benjamin

### Lancer le projet

```sh
docker compose up 
```
### Instructions

1. Aller sur le port d'Adminer (localhost:8080)
2. Renseigner les données avec les scripts présents dans `setup_database.sql`
3. La gateway se trouve sur le port 6086

### Routes disponibles

#### Micro-service Praticien
- `GET /praticiens/{id}` : Récupérer un praticien par ID
- `GET /praticiens` : Récupérer tous les praticiens
- `POST /praticiens` : Créer un nouveau praticien
  ```json
  {
    "email": "email@example.com",
    "nom": "Nom",
    "prenom": "Prenom",
    "adresse": "Adresse",
    "tel": "0123456789",
    "specialite": "SpecialiteID"
  }
  ```

#### Micro-service RDV
- `GET /rdvs/{id}` : Récupérer un RDV par ID
- `POST /rdvs` : Créer un nouveau RDV
```json
{
  "date": "2023-10-10 10:00:00",
  "duree": 30,
  "praticienId": "PraticienID",
  "patientId": "PatientID",
  "specialite": "Specialite"
}
```
- `GET /praticiens/{praticien_id}/planning` : Récupérer le planning d'un praticien

#### Micro-service Authentification
- `POST /signin` : Connexion
```json
{
  "email": "email@example.com",
  "password": "password"
}
```
- `GET /refresh` : Rafraîchir le token
- `POST /register` : Inscription
```json
{
  "email": "email@example.com",
  "password": "password",
  "nom": "Nom",
  "prenom": "Prenom"
}
```
- `GET /validate` : Valider le token



