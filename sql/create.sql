-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS covoiturage_db;

USE covoiturage_db;

-- Student Table
CREATE TABLE IF NOT EXISTS ETUDIANT (
    NUM_ETUDIANT INT NOT NULL,
    PRENOM VARCHAR(100) NOT NULL,
    NOM VARCHAR(100) NOT NULL,
    PRIMARY KEY (NUM_ETUDIANT)
);

-- Conducteur Table
CREATE TABLE IF NOT EXISTS CONDUCTEUR (
    NUM_CONDUCTEUR INT NOT NULL,
    PRIMARY KEY (NUM_CONDUCTEUR),
    FOREIGN KEY (NUM_CONDUCTEUR) REFERENCES ETUDIANT(NUM_ETUDIANT) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Passager Table
CREATE TABLE IF NOT EXISTS PASSAGER (
    NUM_PASSAGER INT NOT NULL,
    PRIMARY KEY (NUM_PASSAGER),
    FOREIGN KEY (NUM_PASSAGER) REFERENCES ETUDIANT(NUM_ETUDIANT) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Voiture Table
CREATE TABLE IF NOT EXISTS VOITURE (
    NUM_IMMATRICULE INT,
    NUM_CONDUCTEUR INT NOT NULL,
    TYPE_VOITURE VARCHAR(100),
    COULEUR VARCHAR(100), 
    ETAT VARCHAR(100),
    NBR_PASSAGER INT,
    PRIMARY KEY (NUM_IMMATRICULE),
    FOREIGN KEY (NUM_CONDUCTEUR) REFERENCES CONDUCTEUR(NUM_CONDUCTEUR) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT VOITURE_UNIQUE UNIQUE (NUM_IMMATRICULE, NUM_CONDUCTEUR)
);

-- Trajet Table
CREATE TABLE IF NOT EXISTS TRAJET (
    NUM_TRAJET INT AUTO_INCREMENT PRIMARY KEY, 
    NUM_IMMATRICULE INT NOT NULL,
    DATE_DEPART TIMESTAMP NOT NULL,
    DATE_ARRIVEE TIMESTAMP NOT NULL,
    ADRESSE_ARRIVEE VARCHAR(100) NOT NULL,
    CODE_POSTAL INT NOT NULL,
    NBR_ESCALES INT NOT NULL DEFAULT 0,
    PRIX_KILOMETRAGE INT NOT NULL,
    DISTANCE_TOTAL INT NOT NULL,
    DUREE_ESTIME INT NOT NULL,
    FOREIGN KEY (NUM_IMMATRICULE) REFERENCES VOITURE(NUM_IMMATRICULE) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Escale Table
CREATE TABLE IF NOT EXISTS ESCALE (
    NUM_ESCALE INT AUTO_INCREMENT PRIMARY KEY,
    NUM_TRAJET INT NOT NULL,
    ADRESSE VARCHAR(100) NOT NULL,
    CODE_POSTAL INT NOT NULL,
    HEURE_ARRIVEE TIMESTAMP NOT NULL,
    VALIDATION_ESCALE BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (NUM_TRAJET) REFERENCES TRAJET(NUM_TRAJET) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Proposition Table
CREATE TABLE IF NOT EXISTS PROPOSITION (
    NUM_ESCALE INT NOT NULL,
    NUM_PASSAGER INT NOT NULL,
    PRIMARY KEY  (NUM_ESCALE, NUM_PASSAGER),
    FOREIGN KEY (NUM_ESCALE) REFERENCES ESCALE(NUM_ESCALE) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (NUM_PASSAGER) REFERENCES PASSAGER(NUM_PASSAGER) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Reservation Table
CREATE TABLE IF NOT EXISTS RESERVATION (
    NUM_TRAJET INT NOT NULL,
    NUM_PASSAGER INT NOT NULL,
    VALIDATION_RESERVATION BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (NUM_TRAJET, NUM_PASSAGER),
    FOREIGN KEY (NUM_TRAJET) REFERENCES TRAJET(NUM_TRAJET) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (NUM_PASSAGER) REFERENCES PASSAGER(NUM_PASSAGER) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Evaluation Table
CREATE TABLE IF NOT EXISTS EVALUATION (
    NUM_ETUDIANT_EVALUE INT NOT NULL,
    NUM_TRAJET INT NOT NULL,
    NUM_ETUDIANT_EVALUATEUR INT NOT NULL,
    NOTE INT NOT NULL,
    CHECK (NOTE >=1 AND NOTE <=5),
    PRIMARY KEY  (NUM_ETUDIANT_EVALUE, NUM_ETUDIANT_EVALUATEUR, NUM_TRAJET),
    FOREIGN KEY (NUM_TRAJET) REFERENCES TRAJET(NUM_TRAJET) ON DELETE CASCADE,
    FOREIGN KEY (NUM_ETUDIANT_EVALUATEUR) REFERENCES ETUDIANT(NUM_ETUDIANT) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (NUM_ETUDIANT_EVALUE) REFERENCES ETUDIANT(NUM_ETUDIANT) ON DELETE CASCADE ON UPDATE CASCADE
);


-------Triggers------------------------------------
-- Change the delimiter to handle multiple statements
DELIMITER $$

-- Trigger for adding a new car and linking it to the driver if it doesn't exist
CREATE TRIGGER ajout_voiture
BEFORE INSERT ON VOITURE
FOR EACH ROW
BEGIN
    INSERT IGNORE INTO CONDUCTEUR(NUM_CONDUCTEUR)
    SELECT NEW.NUM_CONDUCTEUR
    WHERE NOT EXISTS (
        SELECT 1
        FROM CONDUCTEUR
        WHERE NUM_CONDUCTEUR = NEW.NUM_CONDUCTEUR
    );
END$$

-- Trigger for associating a car to a new trip if it doesn't exist
CREATE TRIGGER ajout_trajet_matricule
BEFORE INSERT ON TRAJET
FOR EACH ROW
BEGIN
    INSERT IGNORE INTO VOITURE(NUM_IMMATRICULE)
    SELECT NEW.NUM_IMMATRICULE
    WHERE NOT EXISTS (
        SELECT 1
        FROM VOITURE
        WHERE NUM_IMMATRICULE = NEW.NUM_IMMATRICULE
    );
END$$

-- Trigger to verify that the departure date is before the arrival date
CREATE TRIGGER ajout_trajet_dates
BEFORE INSERT ON TRAJET
FOR EACH ROW
BEGIN
    IF NEW.DATE_ARRIVEE < NEW.DATE_DEPART THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: DATE_ARRIVEE should be after DATE_DEPART';
    END IF;
END$$

-- Procedure to add an intermediate stop
/* CREATE PROCEDURE propo_escale(IN num_p INT, IN addr VARCHAR(100), IN code_p INT, IN num_t INT)
BEGIN
    DECLARE num_e INT;

    INSERT IGNORE INTO ESCALE(NUM_TRAJET, ADRESSE, CODE_POSTAL)
    VALUES (num_t, addr, code_p);

    SELECT NUM_ESCALE INTO num_e
    FROM ESCALE
    WHERE NUM_TRAJET = num_t AND ADRESSE = addr AND CODE_POSTAL = code_p
    LIMIT 1;

    IF num_e IS NOT NULL THEN
        INSERT IGNORE INTO PROPOSITION(NUM_PASSAGER, NUM_ESCALE)
        VALUES (num_p, num_e);
    END IF;
END$$ */

-- Trigger to add a Passenger
CREATE TRIGGER ajout_passager_reservation
BEFORE INSERT ON RESERVATION
FOR EACH ROW
BEGIN
    INSERT IGNORE INTO PASSAGER(NUM_PASSAGER)
    SELECT NEW.NUM_PASSAGER
    WHERE NOT EXISTS (
        SELECT 1
        FROM PASSAGER
        WHERE NUM_PASSAGER = NEW.NUM_PASSAGER
    );
END$$

CREATE TRIGGER ajout_passager_proposition
BEFORE INSERT ON PROPOSITION
FOR EACH ROW
BEGIN
    INSERT IGNORE INTO PASSAGER(NUM_PASSAGER)
    SELECT NEW.NUM_PASSAGER
    WHERE NOT EXISTS (
        SELECT 1
        FROM PASSAGER
        WHERE NUM_PASSAGER = NEW.NUM_PASSAGER
    );
END$$ 

-- Trigger to delete car 
CREATE TRIGGER delete_car
AFTER DELETE ON VOITURE
FOR EACH ROW
BEGIN
    DELETE FROM CONDUCTEUR
    WHERE NUM_CONDUCTEUR = OLD.NUM_CONDUCTEUR;
END$$ 


-- Trigger to update nbr_escale in escale upon validation
CREATE TRIGGER update_nbr_escale
AFTER UPDATE ON ESCALE
FOR EACH ROW
BEGIN
    DECLARE escale_count INT;

    -- Get the number of associated 'escale' where 'validation_escale' = true
    SELECT COUNT(*) INTO escale_count
    FROM ESCALE
    WHERE NUM_TRAJET = NEW.NUM_TRAJET AND VALIDATION_ESCALE = 1;

    -- Update 'nbre_escale' in the corresponding 'trajet' to the counted value
    UPDATE TRAJET
    SET NBR_ESCALES = escale_count
    WHERE NUM_TRAJET = NEW.NUM_TRAJET;
END$$

-- Trigger to Insert reservation after validating escale
CREATE TRIGGER valid_escale_resa
AFTER UPDATE ON ESCALE
FOR EACH ROW
BEGIN
    DECLARE num_e INT;
    DECLARE num_t INT;

    SELECT P.NUM_PASSAGER, E.NUM_TRAJET INTO num_e, num_t 
    FROM PROPOSITION P
    JOIN ESCALE E ON E.NUM_ESCALE = P.NUM_ESCALE
    WHERE E.NUM_ESCALE = NEW.NUM_ESCALE;

    IF NEW.VALIDATION_ESCALE = 1 THEN
        INSERT IGNORE INTO RESERVATION(NUM_PASSAGER, NUM_TRAJET, VALIDATION_RESERVATION)
        VALUES (num_e, num_t, 1)
        ON DUPLICATE KEY UPDATE VALIDATION_RESERVATION = 1;
    ELSE
        DELETE FROM RESERVATION
        WHERE NUM_PASSAGER = num_e AND NUM_TRAJET = num_t;
    END IF;
END$$

-- Trigger to limit booking number to car capacity
CREATE TRIGGER limit_booking_update
BEFORE UPDATE ON RESERVATION
FOR EACH ROW
BEGIN
    DECLARE nbre_p INT;
    DECLARE capacite INT;
    DECLARE matricule INT;

    IF NEW.VALIDATION_RESERVATION = 1 THEN
        SELECT COUNT(*) INTO nbre_p
        FROM RESERVATION R
        JOIN TRAJET T ON T.NUM_TRAJET = R.NUM_TRAJET
        JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
        WHERE R.VALIDATION_RESERVATION = 1 AND T.NUM_TRAJET = NEW.NUM_TRAJET;

        IF nbre_p >= 0 THEN
            SELECT V.NUM_IMMATRICULE INTO matricule
            FROM TRAJET T
            JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
            WHERE T.NUM_TRAJET = NEW.NUM_TRAJET;

            SELECT NBR_PASSAGER INTO capacite 
            FROM VOITURE
            WHERE NUM_IMMATRICULE = matricule;

            IF nbre_p >= capacite THEN 
                SIGNAL SQLSTATE '45000' 
                SET MESSAGE_TEXT = 'Car is at full capacity, cannot make reservation!';
            END IF;
        END IF;
    END IF;
END$$

CREATE TRIGGER limit_booking_insert
BEFORE INSERT ON RESERVATION
FOR EACH ROW
BEGIN
    DECLARE nbre_p INT;
    DECLARE capacite INT;
    DECLARE matricule INT;

    IF NEW.VALIDATION_RESERVATION = 1 THEN
        SELECT COUNT(*) INTO nbre_p
        FROM RESERVATION R
        JOIN TRAJET T ON T.NUM_TRAJET = R.NUM_TRAJET
        JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
        WHERE R.VALIDATION_RESERVATION = 1 AND T.NUM_TRAJET = NEW.NUM_TRAJET;

        IF nbre_p >= 0 THEN
            SELECT V.NUM_IMMATRICULE INTO matricule
            FROM TRAJET T
            JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
            WHERE T.NUM_TRAJET = NEW.NUM_TRAJET;

            SELECT NBR_PASSAGER INTO capacite 
            FROM VOITURE
            WHERE NUM_IMMATRICULE = matricule;

            IF nbre_p >= capacite THEN 
                SIGNAL SQLSTATE '45000' 
                SET MESSAGE_TEXT = 'Car is at full capacity, cannot make reservation!';
            END IF;
        END IF;
    END IF;
END$$

CREATE OR REPLACE TRIGGER modify_reservation_compl
BEFORE UPDATE ON RESERVATION
FOR EACH ROW
BEGIN
    DECLARE date_a TIMESTAMP;

    SELECT DATE_ARRIVEE INTO date_a
    FROM TRAJET T
    WHERE NUM_TRAJET = OLD.NUM_TRAJET;

    IF date_a <= CURRENT_DATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'You cannot modify the reservation. Trip is already completed.';
    END IF;
END$$

CREATE OR REPLACE TRIGGER modify_escale_compl
BEFORE UPDATE ON ESCALE
FOR EACH ROW
BEGIN
	DECLARE heure_a TIMESTAMP;
    SELECT T.DATE_ARRIVEE INTO heure_a 
    FROM TRAJET T
    JOIN ESCALE E ON T.NUM_TRAJET=E.NUM_ESCALE
    WHERE NUM_ESCALE=OLD.NUM_ESCALE;
    
    IF heure_a <= CURRENT_DATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'You cannot modify the stop. Trip is already completed.';
    END IF;
END$$

CREATE OR REPLACE TRIGGER modify_trajet_compl
BEFORE UPDATE ON TRAJET
FOR EACH ROW
BEGIN
    IF NEW.DATE_ARRIVEE <= CURRENT_DATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'You cannot modify the trip. Trip is already completed.';
    END IF;
END$$


CREATE TRIGGER check_car_association
BEFORE INSERT ON VOITURE
FOR EACH ROW
BEGIN
    DECLARE num_c INT;
    DECLARE c_plate INT;

    SELECT COUNT(NUM_IMMATRICULE), MAX(NUM_IMMATRICULE)
    INTO num_c, c_plate
    FROM VOITURE 
    WHERE NUM_CONDUCTEUR = NEW.NUM_CONDUCTEUR;

    IF num_c > 0 AND c_plate != NEW.NUM_IMMATRICULE THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'This driver is already associated with another car. The registered car plate doesn t match this one.';
    END IF;
END$$



-- Reset the delimiter to the default
DELIMITER ;
