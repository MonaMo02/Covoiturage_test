---------------pwd for users is 123*************************************
INSERT INTO `user` (`id`, `password`, `login`, `nom`, `prenom`, `email`, `num_tel`, `matricule`, `compte`) VALUES
(26, '64a43b6ca15d128ac6a0679b39bc9c0', 'chai', 'fodil cherif', 'chaima', 'azrailkaya15@gmail.com', '0667856578', '202032050043', NULL),
(27, '64a43b6ca15d128ac6a0679b39bc9c07', 'mouna', 'Djaraoui', 'mouna', 'mounaa.dj@yahoo.com', '0768946578', '2121078654', NULL),
(28, '64a43b6ca15d128ac6a0679b39bc9c0', 'leila', 'alouache', 'leila', 'leilaaaAloua@hotmail.net', '0563354378', '2020794354', NULL),
(29, '64a43b6ca15d128ac6a0679b39bc9c0', 'Douaa', 'kadem', 'douaa', 'kadem.douaa.usthb@gmail.com', '0664548490', '1919310985', NULL),
(30, '64a43b6ca15d128ac6a0679b39bc9c0', 'yasmina', 'sad', 'yasmina', 'Sad.yasmina.com', '0654749248', '1919320578', NULL),
(31, '64a43b6ca15d128ac6a0679b39bc9c0', 'meriem', 'latif', 'meriem', 'meriemlatif@gmail.com', '0654749248', '2020320578', NULL),
(32, '64a43b6ca15d128ac6a0679b39bc9c0', 'ab_yasmine', 'aboura', 'yasmine', 'aboura2002@gmail.com', '0654749246', '1919320359', NULL),
(33, '64a43b6ca15d128ac6a0679b39bc9c0', 'kamilia', 'mazzouz', 'kamilia', 'kamz555@yahoo.com', '0654749257', '1919320167', NULL),
(35, '64a43b6ca15d128ac6a0679b39bc9c0', 'thanaa', 'djemmal', 'thanaa', 'sumo.2002@gmail.com', '0654749246', '1919320646', NULL),
(36, '64a43b6ca15d128ac6a0679b39bc9c0', 'lyna', 'atal', 'lyna', 'lyna222@gmail.com', '0654749276', '1919320349', NULL);


INSERT INTO `pilote` (`pilote_user_id`, `voiture_id`, `voiture_marque`, `voiture_annee`, `voiture_modele`, `voiture_couleur`, `photo`) VALUES
(30, 1, 'Toyota',2022, 'Camry', 'noir', '../photo_voiture/Toyota_Camry.jpg'),
(31, 2, 'Ford', 2023, 'Mustang', 'rouge', '../photo_voiture/Ford_Mustang.jpg'),
(32, 3, 'Mercedes-Benz', 2021, ' C-Class', 'noir', '../photo_voiture/Mercedes-Benz_C-Class.jpg'),
(33, 4, 'Honda', 2022, 'Civic', 'bleu', '../photo_voiture/Honda_Civic.jpg'),
(35, 5, 'Chevrolet', 2024, 'Silverado', 'rouge', '../photo_voiture/Chevrolet_Silverado.jpg'),
(36, 6, 'BMW', 2023, 'Série 3', 'rouge', '../photo_voiture/BMW_Série.jpg');


INSERT INTO `ville_depart` (`nom`, `locationlat`, `locationlon`) VALUES
('Binarytech, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', '36.7159845', '3.1879194'),
('Cité Universitaire pour filles Baya Hocine (CUB4), Boulevard Kada Rezig, Cité EPLF, Cité 324 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', '36.71314365', '3.1892323871532398'),
('Cité Universitaire pour filles Baya Hocine (CUB4), Cité Smail Yefsah, Cité EPLF, Cité 324 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', '36.71314365', '3.1892323871532398'),
('CNAS - CASNOS - CAMSP, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', '36.716109', '3.1872579'),
('Société générale Algérie, 1, Rue Rezig Kadda, Cité EPLF, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16042, Algérie', '36.7148763', '3.1891634'),
('Tram :Université de Bab Ezzouar (USTHB), Rue Amar El Adlani, Cité EPLF, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16042, Algérie', '36.7211427', '3.1794155'),
('Aéroport Biskra - Mohamed Khider, Oumache, Daïra Ourlal, Biskra, 07006, Algérie', '34.79417615', '5.73777415');



INSERT INTO `ville_arrivee` (`nom`, `destinationlat`, `destinationlon`) VALUES

('Hôtel Sheraton Oran, Boulevard du 19 Mars, Cité Seddikia, Oran, Daïra Oran, Oran, 31025, Algérie', '35.7152294', '-0.60999985'),
('Route de Bab Ezzouar, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', '36.7115939', '3.1710481'),
('Aéroport Biskra - Mohamed Khider, Oumache, Daïra Ourlal, Biskra, 07006, Algérie', '34.79417615', '5.73777415');

INSERT INTO `trajet` (`id`, `lieu_depart`, `destination`, `places_max`, `places_prises`, `date`, `pilote_user_id`, `heure_dep`, `prix`, `effectue`) VALUES
(3, 'CNAS - CASNOS - CAMSP, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', 'Hôtel Sheraton Oran, Boulevard du 19 Mars, Cité Seddikia, Oran, Daïra Oran, Oran, 31025, Algérie', 3, 2, '2024-03-04',30, '19:00',450,0),
(4, 'Société générale Algérie, 1, Rue Rezig Kadda, Cité EPLF, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16042, Algérie', 'Hôtel Sheraton Oran, Boulevard du 19 Mars, Cité Seddikia, Oran, Daïra Oran, Oran, 31025, Algérie', 3,0, '2024-03-04', 31, '19:00',1350,0),
(5, 'Cité Universitaire pour filles Baya Hocine (CUB4), Cité Smail Yefsah, Cité EPLF, Cité 324 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', 'Hôtel Sheraton Oran, Boulevard du 19 Mars, Cité Seddikia, Oran, Daïra Oran, Oran, 31025, Algérie', 3,2, '2024-03-04', 32, '19:00',1500, 0),
(6, 'Cité Universitaire pour filles Baya Hocine (CUB4), Boulevard Kada Rezig, Cité EPLF, Cité 324 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', 'Hôtel Sheraton Oran, Boulevard du 19 Mars, Cité Seddikia, Oran, Daïra Oran, Oran, 31025, Algérie', 3,3, '2024-03-04', 33, '19:00',1400,1),
(8, 'CNAS - CASNOS - CAMSP, Boulevard Kara Rezzik, Bab Ezzouar, Alger, 16042, Algérie', 'Aéroport Biskra - Mohamed Khider, Oumache, Daïra Ourlal, Biskra, 07006, Algérie', 3,2, '2024-03-07', 35, '13:00',2500,0),
(9, 'Aéroport Biskra - Mohamed Khider, Oumache, Daïra Ourlal, Biskra, 07006, Algérie', 'Route de Bab Ezzouar, cité Smail Yefsah 2068 lgts, Bab Ezzouar, Daïra Dar el-Beïda, Alger, 16033, Algérie', 3,2, '2024-03-10', 36, '15:00',2600,0);




INSERT INTO `trajet_passager` (`id_reserv`, `trajet_id`, `user_id`, `nb_places`) VALUES
('',8,33, 1),
('',9,33, 1),
('', 3,28,2),
('',8,28,1),
('',8,28,1),
('',5,27,2),
('', 6,26,3);






