-- Adminer 4.8.1 PostgreSQL 16.4 (Debian 16.4-1.pgdg120+1) dump

DROP TABLE IF EXISTS "praticien";
CREATE TABLE "public"."praticien" (
    "nom" character varying NOT NULL,
    "prenom" character varying NOT NULL,
    "adresse" text NOT NULL,
    "telephone" character varying NOT NULL,
    "specialite_id" character varying NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "specialite";
CREATE TABLE "public"."specialite" (
    "label" character varying NOT NULL,
    "description" text NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);


-- 2024-10-20 15:06:12.022713+00