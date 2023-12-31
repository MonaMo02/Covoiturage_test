
-- -----------------------------------------------------
-- Table user : pple who have accounts
-- -----------------------------------------------------
CREATE TABLE user (
  id INTEGER NOT NULL AUTOINCREMENT,
  password VARCHAR(100) NOT NULL,
  login VARCHAR(45) NOT NULL,
  nom VARCHAR(45) NOT NULL,
  prenom VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  num_tel VARCHAR(10) NOT NULL,
  matricule VARCHAR(10) NOT NULL,  
  PRIMARY KEY (id))
ENGINE = InnoDB; 

CREATE TABLE ville_depart (
    id integer AUTOINCREMENT primary key,
    nom varchar(100),
    -- add more columns as needed
    PRIMARY KEY (nom)
);
CREATE TABLE ville_arrivee (
    id integer AUTOINCREMENT primary key,
    nom varchar(100),
    -- add more columns as needed
    PRIMARY KEY (nom)
);





-- -----------------------------------------------------
-- Table pilote : users who chose : poster tjaret, 
-- -----------------------------------------------------
CREATE TABLE pilote (
  pilote_user_id INTEGER NOT NULL,
  voiture_id INTEGER NOT NULL, 
  voiture_marque VARCHAR(45) NOT NULL,
  voiture_annee INTEGER NOT NULL,
  voiture_modele VARCHAR(45) NOT NULL,
  voiture_couleur VARCHAR(45) NOT NULL,
  photo LONGTEXT NULL,
  PRIMARY KEY (pilote_user_id),
  CONSTRAINT fk_pilote_user
    FOREIGN KEY (pilote_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION 
    ON UPDATE NO ACTION);
    
-- a pilote can have many voitures


-- -----------------------------------------------------
-- Table trajet
-- -----------------------------------------------------
CREATE TABLE trajet (
  id INTEGER NOT NULL AUTO_INCREMENT,
  lieu_depart VARCHAR(45) NOT NULL,
  destination VARCHAR(45) NOT NULL,
  places_max INTEGER NOT NULL,
  places_prises INTEGER NOT NULL,
  date VARCHAR(45) NOT NULL,
  pilote_user_id INTEGER NOT NULL,
  heure_dep INTEGER NOT NULL,
  prix DECIMAL(5) NOT NULL,
  effectue TINYINT(1) NOT NULL,
  PRIMARY KEY (id, pilote_user_id),
  CONSTRAINT fk_trajet_pilote1
    FOREIGN KEY (pilote_user_id)
    REFERENCES pilote (pilote_user_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    );


-- -----------------------------------------------------
-- Table trajet_passager
-- -----------------------------------------------------
CREATE TABLE trajet_passager (
  trajet_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  position VARCHAR(45) NOT NULL,
  nb_places INTEGER NOT NULL,
  proximité DECIMAL(10) NOT NULL, /*distanceentre depart pilote n passager position*/
  PRIMARY KEY (trajet_id, user_id),
  CONSTRAINT fk_trajet_has_user_trajet1
    FOREIGN KEY (trajet_id)
    REFERENCES trajet (id)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT fk_trajet_has_user_user1
    FOREIGN KEY (user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);





-- -----------------------------------------------------
-- Table transaction
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS transaction (
  id INTEGER NOT NULL AUTO_INCREMENT,
  credit_user_id INTEGER NOT NULL,
  debit_user_id INTEGER NOT NULL,
  somme DECIMAL(10) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_credit
    FOREIGN KEY (credit_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_debit
    FOREIGN KEY (debit_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table messagerie
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS messagerie (
  id INTEGER NOT NULL AUTO_INCREMENT,
  expediteur_user_id INTEGER NOT NULL,
  destinataire_user_id INTEGER NOT NULL,
  titre VARCHAR(45) NOT NULL,
  date DATETIME NOT NULL,
  message LONGTEXT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_expediteur
    FOREIGN KEY (expediteur_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_destinataire
    FOREIGN KEY (destinataire_user_id)
    REFERENCES user (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

