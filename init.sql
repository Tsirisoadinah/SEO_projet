CREATE TABLE Utilisateur(
   Id_Utilisateur SERIAL PRIMARY KEY,
   Nom VARCHAR(50),
   email VARCHAR(50),
   mdp VARCHAR(50)
);

CREATE TABLE Categorie(
   Id_Categorie SERIAL PRIMARY KEY,
   libelle VARCHAR(50)
);

CREATE TABLE articles(
   Id_articles SERIAL PRIMARY KEY,
   Titre VARCHAR(500),
   Introduction VARCHAR(100),
   Contenu TEXT,
   image VARCHAR(100),
   alt VARCHAR(255),
   creation TIMESTAMP,
   Id_Categorie INT,
   FOREIGN KEY(Id_Categorie) REFERENCES Categorie(Id_Categorie)
);

CREATE TABLE Journaliste(
   Id_Journaliste SERIAL PRIMARY KEY,
   Nom VARCHAR(50),
   date_embauche DATE,
   Actif BOOLEAN,
   image VARCHAR(100)
);

CREATE TABLE journaliste_article(
   Id_articles INT,
   Id_Journaliste INT,
   PRIMARY KEY(Id_articles, Id_Journaliste),
   FOREIGN KEY(Id_articles) REFERENCES articles(Id_articles),
   FOREIGN KEY(Id_Journaliste) REFERENCES Journaliste(Id_Journaliste)
);