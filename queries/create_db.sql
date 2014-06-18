DROP TABLE IF EXISTS ENTRY;
DROP TABLE IF EXISTS MAKE;
DROP TABLE IF EXISTS ELIGIBLE;
DROP TABLE IF EXISTS CAR;
DROP TABLE IF EXISTS MAINTENANCE;



CREATE TABLE CAR(
type varchar(128),
id varchar(128) NOT NULL,
PRIMARY KEY (id) 
);

CREATE TABLE MAINTENANCE(
name varchar(128),
id varchar(128) NOT NULL,
PRIMARY KEY (id)
);

CREATE TABLE ELIGIBLE(
car_id varchar(128),
maintenance_id varchar(128),
FOREIGN KEY (car_id) REFERENCES CAR(id),
FOREIGN KEY (maintenance_id) REFERENCES MAINTENANCE(id),
PRIMARY KEY(car_id,maintenance_id)
);

CREATE TABLE ENTRY(
id varchar(128) NOT NULL,
make varchar(128),
model varchar(128),
year int,
odo int,
type varchar(128),
maintenance varchar(128),
PRIMARY KEY(id),
FOREIGN KEY (type) REFERENCES CAR(id),
FOREIGN KEY (maintenance) REFERENCES MAINTENANCE(id)
);

CREATE TABLE MAKE(
make varchar(128),
model varchar(128),
type varchar(128),
PRIMARY KEY(make,model),
FOREIGN KEY(type) REFERENCES CAR(id)
);