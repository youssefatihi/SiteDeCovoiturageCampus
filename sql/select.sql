--CONSULTATION ET STATISTIQUE

-- ============================================================================================
--  
--  |   |   |   |   |   |   REQUETES DE CONSULTATION
--  
-- ============================================================================================


-- ============================================================================================
--  |  INFORMATIONS SUR LES CONDUCTEURS ET PASSAGERS 
-- ============================================================================================

-- Toutes les infos sur Les conducteurs
SELECT * FROM ETUDIANT
WHERE NUM_ETUDIANT IN (SELECT NUM_ETUDIANT FROM ETUDIANT
                        INTERSECT
                        SELECT NUM_CONDUCTEUR FROM CONDUCTEUR);

-- Toutes les infos sur Les passagers
SELECT * FROM ETUDIANT
WHERE NUM_ETUDIANT IN (SELECT NUM_ETUDIANT FROM ETUDIANT
                        INTERSECT
                        SELECT NUM_PASSAGER FROM PASSAGER);


-- ============================================================================================
--  |  La liste des véhicules disponibles pour un jour donné pour une ville donnée
-- ============================================================================================
SELECT VOITURE.*
FROM VOITURE v 
JOIN TRAJET t ON v.NUM_IMMATRICULE = t.NUM_IMMATRICULE
JOIN ESCALE e ON e.NUM_TRAJET = t.NUM_TRAJET
WHERE t.DATE_DEPART /*date*/ AND ville IN (t.ADRESSE_ARRIVEE, e.ADRESSE);

-- ============================================================================================
--  |  Les trajets proposés dans un intervalle de jours donné, la liste des villes renseignées entre le campus et une ville donnée
-- ============================================================================================
SELECT TRAJET.*
FROM  TRAJET t 
WHERE DATE_DEPART BETWEEN date1 AND date2;


-- la liste des villes renseignées entre le campus et une ville donnée ?

-- ============================================================================================
--  |  Les trajets pouvant desservir une ville donnée dans un intervalle de temps
-- ============================================================================================
SELECT TRAJET.*
FROM TRAJET t
JOIN ESCALE e ON t.TRAJET = e.ESCALE
/* Ajouter duree estimée à escale ? */
WHERE ville IN (t.ADRESSE_ARRIVEE, e.ADRESSE) AND e.DUREE_ESTIME = duree;




-- ============================================================================================
--  
--  |   |   |   |   |   |   REQUETES DE STATISTIQUES
--  
-- ============================================================================================


-- ============================================================================================
--  |  Moyenne des passagers sur l’ensemble des trajets effectués
-- ============================================================================================
SELECT AVG(p.NUM_PASSAGER) AS Moyenne_Passagers
FROM PASSAGER p
JOIN RESERVATION r ON r.NUM_PASSAGER = p.NUM_PASSAGER
-- Inclure les escales ?
WHERE VALIDATION_RESERVATION = 'TRUE';


-- ============================================================================================
--  |  Moyenne des distances parcourues en covoiturage par jour
-- ============================================================================================
SELECT  AVG(subquery.Distances_Jour) AS Moyenne_Distance_Jour
FROM (
    SELECT t.DATE_DEPART, SUM(t.DISTANCE_TOTAL) AS Distances_Jour
    FROM TRAJET t
    GROUP BY t.DATE_DEPART
) AS subquery;

SELECT AVG(distances_par_jour.distance_total) AS moyenne_distance
FROM (
    SELECT DATE(DATE_DEPART) AS jour, SUM(DISTANCE_TOTAL) AS distance_total
    FROM TRAJET
    GROUP BY jour
) AS distances_par_jour;


-- ============================================================================================
--  |  Moyenne des distances parcourues en covoiturage par jour ?????
-- ============================================================================================


-- ============================================================================================
--  |  Classement des meilleurs conducteurs d’aprés les avis
-- ============================================================================================
SELECT e.NOM, e.PRENOM, eval.NOTE AS avis
FROM CONDUCTEUR c
JOIN ETUDIANT e ON c.NUM_CONDUCTEUR = e.NUM_ETUDIANT
JOIN EVALUATION eval ON eval.NUM_ETUDIANT_EVALUE = c.NUM_CONDUCTEUR
ORDER BY eval.note DESC;


-- ============================================================================================
--  |  Classement des villes selon le nombre de trajets qui les dessert
-- ============================================================================================
SELECT ville, COUNT(*) AS nombre_trajets
FROM (
    SELECT VILLE_DEPART AS ville FROM TRAJET
    UNION ALL
    SELECT ADRESSE_ARRIVEE AS ville FROM TRAJET
) AS villes
GROUP BY ville
ORDER BY nombre_trajets DESC;


---------- Autres SELECT QUERIES utilisées
SELECT * FROM TRAJET WHERE DATE_DEPART BETWEEN ? AND ? AND ADRESSE_ARRIVEE = ?;
SELECT * FROM TRAJET;
SELECT t.* FROM TRAJET t JOIN VOITURE v ON t.NUM_IMMATRICULE = v.NUM_IMMATRICULE WHERE v.NUM_CONDUCTEUR = ? ORDER BY $order_by;
SELECT VALIDATION_RESERVATION FROM RESERVATION WHERE NUM_PASSAGER = ? AND NUM_TRAJET = ?;
SELECT VALIDATION_ESCALE FROM ESCALE E JOIN PROPOSITION P ON P.NUM_ESCALE = E.NUM_ESCALE  WHERE P.NUM_PASSAGER = ? AND P.NUM_ESCALE = ?;
SELECT * FROM EVALUATION WHERE NUM_ETUDIANT_EVALUE = ? AND NUM_TRAJET = ? AND NUM_ETUDIANT_EVALUATEUR = ?;
SELECT * FROM VOITURE WHERE NUM_IMMATRICULE = ?;
SELECT * FROM CONDUCTEUR C WHERE NUM_CONDUCTEUR = ?;
SELECT NUM_IMMATRICULE, TYPE_VOITURE, ETAT, COULEUR, NBR_PASSAGER FROM VOITURE WHERE NUM_CONDUCTEUR = ?;
SELECT * FROM TRAJET WHERE NUM_TRAJET = ?;

SELECT E.NUM_ETUDIANT, E.NOM, E.PRENOM, T.NUM_TRAJET, R.VALIDATION_RESERVATION, AVG(EV.NOTE) AS NOTE, T.DATE_ARRIVEE 
                    FROM ETUDIANT E 
                    JOIN PASSAGER P ON P.NUM_PASSAGER = E.NUM_ETUDIANT
                    JOIN RESERVATION R ON R.NUM_PASSAGER = P.NUM_PASSAGER
                    JOIN TRAJET T ON T.NUM_TRAJET = R.NUM_TRAJET
                    JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
                    LEFT JOIN EVALUATION EV ON EV.NUM_ETUDIANT_EVALUE = P.NUM_PASSAGER
                    WHERE V.NUM_CONDUCTEUR = ?
                    GROUP BY E.NUM_ETUDIANT, E.NOM, E.PRENOM
                    ORDER BY EV.NOTE DESC;

SELECT * FROM ESCALE WHERE NUM_ESCALE = ?;

SELECT E.NUM_ETUDIANT, E.NOM, E.PRENOM, ES.NUM_ESCALE, ES.VALIDATION_ESCALE, AVG(EV.NOTE) AS NOTE FROM ETUDIANT E 
                JOIN PASSAGER P ON P.NUM_PASSAGER = E.NUM_ETUDIANT
                JOIN PROPOSITION PROP ON PROP.NUM_PASSAGER = P.NUM_PASSAGER
                JOIN ESCALE ES ON ES.NUM_ESCALE = PROP.NUM_ESCALE
                JOIN TRAJET T ON T.NUM_TRAJET = ES.NUM_TRAJET
                JOIN VOITURE V ON V.NUM_IMMATRICULE = T.NUM_IMMATRICULE
                LEFT JOIN EVALUATION EV ON EV.NUM_ETUDIANT_EVALUE = P.NUM_PASSAGER
                WHERE V.NUM_CONDUCTEUR = ?
                GROUP BY E.NUM_ETUDIANT, E.NOM, E.PRENOM
                ORDER BY EV.NOTE DESC;

SELECT * FROM TRAJET WHERE NUM_TRAJET=?;

SELECT E.NOM, E.PRENOM FROM ETUDIANT E
                        JOIN CONDUCTEUR C ON C.NUM_CONDUCTEUR = E.NUM_ETUDIANT
                        JOIN VOITURE V ON C.NUM_CONDUCTEUR = V.NUM_CONDUCTEUR
                        JOIN TRAJET J ON J.NUM_IMMATRICULE = V.NUM_IMMATRICULE
                        WHERE J.NUM_TRAJET=?;