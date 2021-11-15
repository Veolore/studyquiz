########### Tabelle Kurse ######################### 
CREATE TABLE Kurse ( 
kursid	INTEGER AUTO_INCREMENT, 
kurs	TEXT, 
PRIMARY KEY(kursid) 
); 
 
########### Tabelle Fragen ######################### 
CREATE TABLE Fragen ( 
fragenid	INTEGER AUTO_INCREMENT, 
frage	TEXT, 
antwort1	TEXT, 
antwort2	TEXT, 
antwort3	TEXT, 
antwort4	TEXT, 
richtigeantwort	INTEGER, 
kursfs	INTEGER, 
FOREIGN KEY(kursfs) REFERENCES Kurse(kursid), 
PRIMARY KEY(fragenid) 
); 

########### Tabelle Kommentare ######################### 
CREATE TABLE Kommentare ( 
kommentarid	INTEGER AUTO_INCREMENT, 
kommentar	TEXT, 
fragefs INTEGER, 
FOREIGN KEY(fragefs) REFERENCES Fragen(fragenid),
PRIMARY KEY(kommentarid) 
); 

########### Tabelle Fragen Constraint geändert ######################### 
ALTER TABLE Fragen  
ADD CONSTRAINT   KursFS 
FOREIGN KEY (kursfs)  
REFERENCES Kurse (kursid) 
ON DELETE SET NULL; 

########### Tabelle Kommentare Constraint geändert ######################### 
ALTER TABLE Kommentare  
ADD CONSTRAINT   FragenFS  
FOREIGN KEY (fragefs)  
REFERENCES Fragen(fragenid) 
ON DELETE SET CASCADE;  