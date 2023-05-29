CREATE TABLE circunscripciones (
    id serial PRIMARY KEY,
    descripcion character varying,
    codigo integer DEFAULT 0 NOT NULL
);

INSERT INTO circunscripciones (id, descripcion, codigo) VALUES (2, 'PRIMERA', 1);
INSERT INTO circunscripciones (id, descripcion, codigo) VALUES (3, 'SEGUNDA', 2);
INSERT INTO circunscripciones (id, descripcion, codigo) VALUES (4, 'TERCERA', 3);
INSERT INTO circunscripciones (id, descripcion, codigo) VALUES (5, 'CUARTA', 4);
INSERT INTO circunscripciones (id, descripcion, codigo) VALUES (1, 'SIN CIRCUNSCRIPCION', 6);
INSERT INTO circunscripciones (id, descripcion, codigo) VALUES (6, 'QUINTA', 5);


CREATE TABLE localidades (
    id serial PRIMARY KEY,
    descripcion character varying,
    usuario_abm character varying,
    id_circunscripcion integer references circunscripciones(id)
);

INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (1, 'SIN LOCALIDAD', NULL, 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (465, 'FRACRAN', NULL, 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (485, 'PUERTO ROSARIO', NULL, 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (494, 'ALMIRANTE BROWN', NULL, 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (565, 'PUERTO BOSSETTI', NULL, 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (575, 'PUERTO MINERAL', NULL, 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (3, 'SIERRA DE SAN JOSE', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (4, 'GUAYABERA', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (6, 'CUÑA PIRU', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (7, 'ARISTOBULO DEL VALLE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (8, 'ALBA POSSE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (12, 'AZARA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (16, 'CAMPO GRANDE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (17, 'CAMPO RAMON', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (18, 'CAMPO VIERA', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (20, 'CAPIOVI', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (24, 'COMANDANTE ANDRESITO', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (25, 'COLONIA ALBERDI', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (28, 'PUERTO NARANJITO', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (29, 'PUERTO LONDERO', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (30, 'SAN ISIDRO', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (31, 'COLONIA OASIS', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (33, 'COLONIA DELICIA', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (35, 'COLONIA VICTORIA', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (36, 'COLONIA WANDA', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (37, 'CONCEPCION DE LA SIERRA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (43, 'ELDORADO', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (46, 'GARUHAPE', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (91, 'SAN PEDRO', 'admin', 6);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (44, 'FNO. AMEGHINO', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (52, 'GUARANI', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (55, 'LAS VERTIENTES', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (56, 'SAN ALBERTO', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (60, 'LOS HELECHOS', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (65, 'OBERA', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (66, 'SANTA RITA', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (69, 'PUERTO ESPAÑA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (70, 'PUERTO GISELA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (71, 'PANAMBI', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (74, 'PIÑALITO NORTE', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (75, 'PIÑALITO SUR', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (76, 'PUERTO ESPERANZA', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (77, 'PUERTO IGUAZU', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (78, 'PUERTO LEONI', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (79, 'PUERTO LIBERTAD', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (81, 'PUERTO RICO', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (82, 'RUIZ DE MONTOYA', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (84, 'COLONIA CAAGUAZU', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (85, 'SAN ANTONIO', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (42, 'EL SOBERBIO', 'admin', 6);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (88, 'TRES ESQUINAS', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (89, 'SAN JOSE', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (94, 'SANTA MARIA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (95, 'SANTIAGO DE LINIERS', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (97, 'TRES CAPONES', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (98, 'MONTEAGUDO', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (99, 'SALTO ENCANTADO', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (41, 'EL ALCAZAR', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (83, 'COLONIA GUARANI', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (5, '9 DE JULIO', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (32, 'COLONIA AURORA', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (93, 'SANTA ANA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (80, 'PUERTO PIRAY', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (87, 'SAN JAVIER', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (51, 'GOBENADOR ROCA', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (23, 'CERRO CORA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (11, 'ARROYO DEL MEDIO', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (19, 'CANDELARIA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (15, 'CAA - YARI', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (45, 'FACHINAL', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (49, 'GENERAL URQUIZA', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (59, 'LORETO', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (61, 'MARTIRES', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (27, 'PARADA LEIS', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (73, 'PROFUNDIDAD', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (53, 'HIPOLITO YRIGOYEN', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (50, 'GOBERNADOR LOPEZ', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (68, 'COLONIA YABEBIRI', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (26, 'GOBERNADOR LANUSSE', 'admin', 4);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (67, 'SAN FRANCISCO DE ASIS', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (90, 'SAN MARTIN', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (96, 'SANTO PIPO', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (64, 'OLEGARIO VICTOR ANDRADE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (39, 'DOS ARROYOS', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (13, 'BERNARDO DE IRIGOYEN', 'admin', 6);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (92, 'SAN VICENTE', 'admin', 6);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (63, 'MONTECARLO', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (21, 'CARAGUATAY', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (38, 'CORPUS', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (54, 'ITACARUARE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (62, 'MOJON GRANDE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (58, 'LEANDRO N. ALEM', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (14, 'BONPLAND', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (9, 'ALMAFUERTE', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (22, 'CERRO AZUL', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (263, 'LIBERTADOR GENERAL SAN MARTIN', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (330, 'SAN JUAN DE LA SIERRA', 'admin', 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (335, 'PICADA FINLANDESA', 'admin', 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (353, 'COLONIA GISELA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (368, 'PUERTO BEMBERG', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (381, 'MACHADIÑO', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (406, 'PICADA YAPEYU', 'admin', 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (407, 'DOS HERMANAS', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (410, 'INVERNADA GRANDE', 'admin', 1);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (2, '25 DE MAYO', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (445, 'PICADA SARGENTO CABRAL', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (48, 'GENERAL ALVEAR', 'admin', 3);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (34, 'COLONIA POLANA', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (10, 'APOSTOLES', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (47, 'GARUPA', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (57, 'JARDIN AMERICA', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (72, 'POSADAS', 'admin', 2);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (40, 'DOS DE MAYO', 'admin', 6);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (419, 'POZO AZUL', 'admin', 6);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (86, 'SAN IGNACIO', 'admin', 5);
INSERT INTO localidades (id, descripcion, usuario_abm, id_circunscripcion) VALUES (186, 'BUENOS AIRES', 'admin', 1);
-------


CREATE TABLE dependencias
(
    id serial PRIMARY KEY,
    descripcion character varying,
    id_localidad integer references localidades(id),
    estado integer DEFAULT 1,
    padre integer DEFAULT 0,
    usuario_abm character varying,
    id_leu integer,
    direccion character varying
);

INSERT INTO items (descripcion,enlace,id_opcion, orden, usuario_abm) VALUES ('Dependencias','administracion/dependencias',1,7, 'admin');
INSERT INTO grupos_items values(1,(select id from items where enlace='administracion/dependencias'));

-------

CREATE TABLE categorias
(
    id serial PRIMARY KEY,
    descripcion character varying,
    cod_categoria integer,
    descripcion_tipo_categoria character varying,
    id_leu integer,
    estado integer DEFAULT 1,
    usuario_abm character varying
);

INSERT INTO items (descripcion,enlace,id_opcion, orden, usuario_abm) VALUES ('Categorias','administracion/categorias',1,8, 'admin');
INSERT INTO grupos_items values(1,(select id from items where enlace='administracion/categorias'));


-------


CREATE TABLE personas
(
    id serial PRIMARY KEY,
    apellido character varying,
    nombres character varying,
    legajo character varying,    
    correo character varying,
    activo integer DEFAULT 1,
    id_dependencia integer references dependencias(id),
    estado integer DEFAULT 1,
    usuario_abm character varying,
    id_leu integer,
    nro_documento integer DEFAULT 0,
    fecha_nacimiento date,
    domicilio character varying ,
    id_categoria integer references categorias(id)
);
INSERT INTO items (descripcion,enlace,id_opcion, orden, usuario_abm) VALUES ('Personas','administracion/personas',1,9, 'admin');
INSERT INTO grupos_items values(1,(select id from items where enlace='administracion/personas'));