ALTER TABLE "utente" ADD "algoritmo" CHARACTER VARYING (8) DEFAULT '' NOT NULL;
ALTER TABLE "utente" ADD "salt"  CHARACTER VARYING (8) DEFAULT '' NOT NULL;