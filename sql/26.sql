
-- VERSIONADO --
CREATE TABLE sistema_versiones(
  id serial PRIMARY KEY,
  tag character varying,
  descripcion character varying,
  fecha timestamp DEFAULT now(),
  usuario_abm character varying
);

--creo el item
INSERT INTO items(descripcion, enlace, id_opcion, orden,usuario_abm) VALUES ('Versiones', 'administracion/versiones', (select id from opciones where descripcion='Administrar'), 17, 'admin');

--agrego el permiso para el admin
insert into grupos_items values (1,(select id from items where enlace='administracion/versiones'),'admin');

-- INSTRUCTIVO --
CREATE TABLE instructivo
(   
    id SERIAL PRIMARY KEY, -- primary key column
    titulo CHARACTER VARYING NOT NULL,
    descripcion CHARACTER VARYING,
    url_video CHARACTER VARYING,
    url_documento CHARACTER VARYING
);

-- Inserts nueva opcion
INSERT INTO opciones(descripcion,orden,estado,icono,usuario_abm) VALUES ('Instructivo',17,1,'fa fa-book','admin');
-- relaciono la opcion nueva con el grupo admin
INSERT INTO grupos_opciones (id_grupo, id_opcion,usuario_abm) values (1,(select id from opciones where descripcion='Instructivo'),'admin');
-- Se crea el primer items del instrucctivo
INSERT INTO items(descripcion, enlace, id_opcion, orden,usuario_abm) VALUES ('Listado', 'administracion/instructivo', (select id from opciones where descripcion='Instructivo'), 1, 'admin');
-- Se relaciona el items al admin
INSERT INTO grupos_items VALUES (1,(select id from items where descripcion='Listado'),'admin');