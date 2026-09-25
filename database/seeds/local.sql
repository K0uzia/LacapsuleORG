INSERT INTO level (id, name) VALUES
  (1, 'utilisateur'),
  (2, 'moderateur'),
  (3, 'admin');

INSERT INTO statement (id, state) VALUES
  (1, 'actif'),
  (2, 'inactif');

INSERT INTO categories (id, name, level, rattachement) VALUES
  (1, 'Général', '1', 'racine'),
  (2, 'Windows', '1', 'racine'),
  (3, 'Linux', '1', 'racine');

INSERT INTO stucture (id, nom, adresse, codePostal, ville, mail, telephone, referent) VALUES
  (1, 'La Capsule', 'Manufacture des tabacs', '29600', 'Morlaix', 'contact@lacapsule.org', '0000000000', 'Admin');

-- mot de passe = password (sha512)
INSERT INTO users (id, dateInscription, nom, prenom, pseudo, mail, password, photo, recovery, token, id_level, id_statement)
VALUES (
  1,
  date('now'),
  'Admin',
  'Local',
  'admin',
  'admin@local.test',
  'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86',
  '',
  '',
  'local-dev-token',
  3,
  1
);

INSERT INTO ressources (id, date, title, subtitle, content, image, deroule, tuto, slug, id_categories, id_users, id_stucture) VALUES
(1, date('now'), 'Installer Linux Mint', 'Premiers pas', 'Guide pour installer Linux Mint en dual boot ou en machine principale. Ideal pour debuter avec un bureau Cinnamon.', '', '', '', 'installer-linux-mint', 3, 1, 1),
(2, date('now'), 'Sauvegarder ses fichiers', 'Cloud et disque externe', 'Bonnes pratiques de sauvegarde : disque externe, rsync, et services cloud. Pensez a la regle 3-2-1.', '', '', '', 'sauvegarder-ses-fichiers', 1, 1, 1),
(3, date('now'), 'Nettoyer Windows', 'Ordinateur lent', 'Utiliser le nettoyage de disque, desinstaller les logiciels inutiles et verifier Windows Update.', '', '', '', 'nettoyer-windows', 2, 1, 1),
(4, date('now'), 'Problemes d imprimante', 'Pilotes et connexions', 'Verifier cable, Wi-Fi, redemarrer, puis reinstaller le pilote constructeur sous Windows ou CUPS sous Linux.', '', '', '', 'problemes-imprimante', 1, 1, 1);
