
INSERT INTO CAR VALUES('diesel',uuid());
INSERT INTO CAR VALUES('gas',uuid());
INSERT INTO CAR VALUES('electric',uuid());

SELECT id INTO @idCar1 FROM CAR WHERE type='diesel';
SELECT id INTO @idCar2 FROM CAR WHERE type='gas';
SELECT id INTO @idCar3 FROM CAR WHERE type='electric';

INSERT INTO MAINTENANCE VALUES('oil change',uuid());
INSERT INTO MAINTENANCE VALUES('tire rotation',uuid());
INSERT INTO MAINTENANCE VALUES('battery change',uuid());
INSERT INTO MAINTENANCE VALUES('belt change',uuid());

SELECT id INTO @idMain1 FROM MAINTENANCE WHERE name='oil change';
SELECT id INTO @idMain2 FROM MAINTENANCE WHERE name='tire rotation';
SELECT id INTO @idMain3 FROM MAINTENANCE WHERE name='battery change';
SELECT id INTO @idMain4 FROM MAINTENANCE WHERE name='belt change';

INSERT INTO ELIGIBLE VALUES(@idCar1,@idMain1);
INSERT INTO ELIGIBLE VALUES(@idCar1,@idMain2);
INSERT INTO ELIGIBLE VALUES(@idCar1,@idMain4);
INSERT INTO ELIGIBLE VALUES(@idCar2,@idMain1);
INSERT INTO ELIGIBLE VALUES(@idCar2,@idMain2);
INSERT INTO ELIGIBLE VALUES(@idCar2,@idMain4);
INSERT INTO ELIGIBLE VALUES(@idCar3,@idMain2);
INSERT INTO ELIGIBLE VALUES(@idCar3,@idMain4);
INSERT INTO ELIGIBLE VALUES(@idCar3,@idMain3);

INSERT INTO MAKE VALUES('audi','s3',@idCar1);
INSERT INTO MAKE VALUES('audi','s4',@idCar1);
INSERT INTO MAKE VALUES('bmw','x6',@idCar1);
INSERT INTO MAKE VALUES('bmw','m6',@idCar2);
INSERT INTO MAKE VALUES('mazda','protege',@idCar2);
INSERT INTO MAKE VALUES('mazda','cx-5',@idCar2);
INSERT INTO MAKE VALUES('subaru','wrx',@idCar2);
INSERT INTO MAKE VALUES('subaru','legacy',@idCar2);
INSERT INTO MAKE VALUES('honda','civic',@idCar2);
INSERT INTO MAKE VALUES('honda','accord',@idCar2);
INSERT INTO MAKE VALUES('toyota','prius',@idCar2);
INSERT INTO MAKE VALUES('toyota','camry',@idCar2);
INSERT INTO MAKE VALUES('nissan','altima',@idCar2);
INSERT INTO MAKE VALUES('nissan','gt-r',@idCar1);
INSERT INTO MAKE VALUES('tesla','model s',@idCar3);
INSERT INTO MAKE VALUES('tesla','model x',@idCar3);
