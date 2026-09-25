#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: categories
#------------------------------------------------------------

CREATE TABLE categories(
        id           Int  Auto_increment  NOT NULL ,
        level        Varchar (255) NOT NULL ,
        rattachement Varchar (255) NOT NULL
	,CONSTRAINT categories_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: level
#------------------------------------------------------------

CREATE TABLE level(
        id   Int  Auto_increment  NOT NULL ,
        name Varchar (255) NOT NULL
	,CONSTRAINT level_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: statement
#------------------------------------------------------------

CREATE TABLE statement(
        id    Int  Auto_increment  NOT NULL ,
        state Varchar (50) NOT NULL
	,CONSTRAINT statement_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: users
#------------------------------------------------------------

CREATE TABLE users(
        id              Int  Auto_increment  NOT NULL ,
        dateInscription Date NOT NULL ,
        nom             Varchar (255) NOT NULL ,
        prenom          Varchar (255) NOT NULL ,
        pseudo          Varchar (255) NOT NULL ,
        mail            Varchar (255) NOT NULL ,
        password        Varchar (255) NOT NULL ,
        photo           Varchar (255) NOT NULL ,
        recovery        Varchar (255) NOT NULL ,
        token           Varchar (255) NOT NULL ,
        id_level        Int NOT NULL ,
        id_statement    Int NOT NULL
	,CONSTRAINT users_PK PRIMARY KEY (id)

	,CONSTRAINT users_level_FK FOREIGN KEY (id_level) REFERENCES level(id)
	,CONSTRAINT users_statement0_FK FOREIGN KEY (id_statement) REFERENCES statement(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: stucture
#------------------------------------------------------------

CREATE TABLE stucture(
        id         Int  Auto_increment  NOT NULL ,
        nom        Varchar (255) NOT NULL ,
        adresse    Varchar (255) NOT NULL ,
        codePostal Varchar (255) NOT NULL ,
        ville      Varchar (255) NOT NULL ,
        mail       Varchar (255) NOT NULL ,
        telephone  Varchar (255) NOT NULL ,
        referent   Varchar (255) NOT NULL
	,CONSTRAINT stucture_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ressources
#------------------------------------------------------------

CREATE TABLE ressources(
        id            Int  Auto_increment  NOT NULL ,
        date          Date NOT NULL ,
        title         Varchar (255) NOT NULL ,
        subtitle      Varchar (255) NOT NULL ,
        content       Text NOT NULL ,
        image         Varchar (255) NOT NULL ,
        deroule       Varchar (255) NOT NULL ,
        tuto          Varchar (255) NOT NULL ,
        id_categories Int NOT NULL ,
        id_users      Int NOT NULL ,
        id_stucture   Int NOT NULL
	,CONSTRAINT ressources_PK PRIMARY KEY (id)

	,CONSTRAINT ressources_categories_FK FOREIGN KEY (id_categories) REFERENCES categories(id)
	,CONSTRAINT ressources_users0_FK FOREIGN KEY (id_users) REFERENCES users(id)
	,CONSTRAINT ressources_stucture1_FK FOREIGN KEY (id_stucture) REFERENCES stucture(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: commentaires
#------------------------------------------------------------

CREATE TABLE commentaires(
        id            Int  Auto_increment  NOT NULL ,
        comment       Text NOT NULL ,
        date          Date NOT NULL ,
        id_users      Int NOT NULL ,
        id_ressources Int NOT NULL
	,CONSTRAINT commentaires_PK PRIMARY KEY (id)

	,CONSTRAINT commentaires_users_FK FOREIGN KEY (id_users) REFERENCES users(id)
	,CONSTRAINT commentaires_ressources0_FK FOREIGN KEY (id_ressources) REFERENCES ressources(id)
)ENGINE=InnoDB;

