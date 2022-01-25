# BileMo

**Projet pédagogique :**  construire une petite application de récupération d’offres d’emploi de Rennes, Bordeaux et Paris à partir de l’API de Pôle Emploi : pole-emploi.io ;

## Sommaire
1. __[Technologies utilisées](#Librairies-utilisées)__

2. __[Pré-requis](#Pré-requis)__

3. __[Télécharger le projet](#Télécharger-le-projet)__

4. __[Indiquer l'URL de la base de données](#Indiquer-l'URL-de-la-base-de-données)__

5. __[Créer une application sur l'API pole-emploi.io](#Créer-une-application-sur-l'API-pole-emploi.io)__

6. __[Installer les dépendances](#Installer-les-dépendances)__

7. __[Installer la base de données et les tables](#Installer-la-base-de-données-et-les-tables)__

8. __[Lancer le serveur de développement](#Lancer-le-serveur-de-développement)__

---

## Technologies utilisées

**PHP 8.1.0**

**Symfony 6.0**

## Pré-requis

PHP >= 8.0.2
Composer

## Télécharger le projet

Téléchargez ou clonez le projet ([voir la documentation GitHub](https://docs.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository)). 

## Indiquer l'URL de la base de données

Créez un fichier « .env.local » à la racine du projet et indiquez votre variable DATABASE_URL :

```bash
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

## Créer une application sur l'API pole-emploi.io

Créez un compte sur https://pole-emploi.io/inscription

Créez votre application sur https://pole-emploi.io/compte/application-creation

Copiez votre identifiant client et votre clé secrète dans le fichier « .env.local » à la racine du projet :

```bash
CLIENT_ID=...
CLIENT_SECRET=...
```
...

## Installer les dépendances

```bash
composer install
```

## Installer la base de données et les tables

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Lancer le serveur de développement

```bash
symfony server:start
```