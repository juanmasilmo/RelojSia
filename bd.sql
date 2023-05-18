/**
 * Opciones
 */
CREATE TABLE opciones
(
  id serial PRIMARY KEY,
  titulo character varying,
  descripcion character varying,
  icono character varying,
  orden integer,
  estado integer DEFAULT 1,
  usuario_abm character varying
);


/**
 * Items
 */
CREATE TABLE items
(
  id serial PRIMARY KEY,
  descripcion character varying,
  enlace character varying,
  id_opcion integer references opciones(id),
  orden integer,
  estado integer DEFAULT 1,
  usuario_abm character varying
);



/**
 * Grupos
 */
CREATE TABLE grupos
(
  id serial PRIMARY KEY,
  descripcion character varying,
  estado integer DEFAULT 1,  
  usuario_abm character varying
);

INSERT INTO grupos (id,descripcion,usuario_abm) VALUES (1,'Administrador', 'admin');

/**
 * Usuarios
 */
CREATE TABLE usuarios
(
  id serial PRIMARY KEY,
  usuario character varying UNIQUE,
  nombre_apellido character varying,
  clave character varying,
  estado integer DEFAULT 1,
  id_grupo integer references grupos(id),
  activo integer DEFAULT 1,
  fecha_alta timestamp with time zone DEFAULT now(),
  fecha_baja timestamp with time zone,
  usuario_abm character varying
);

INSERT INTO usuarios (usuario,nombre_apellido,id_grupo,clave, usuario_abm) VALUES ('admin','admin',1,'$2y$10$SFTR98e2ru4O4Kf8HexwAOG9jeH9kO9Fc0JDaSHiIFbMrN.Vv19Vi', 'admin');

/**
 * Grupo - Items
 */
CREATE TABLE grupos_items
(
  id_grupo integer NOT NULL,
  id_item integer NOT NULL,
  usuario_abm character varying,
  CONSTRAINT grupos_items_pk PRIMARY KEY (id_grupo, id_item),
  CONSTRAINT grupos_items_id_grupo_fkey FOREIGN KEY (id_grupo)
    REFERENCES grupos (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT grupos_items_id_item_fkey FOREIGN KEY (id_item)
    REFERENCES items (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
);

/**
 * Grupo - Opciones
 */
CREATE TABLE grupos_opciones
(
  id_grupo integer NOT NULL,
  id_opcion integer NOT NULL,
  usuario_abm character varying,
  CONSTRAINT grupos_opciones_pk PRIMARY KEY (id_grupo, id_opcion),
  CONSTRAINT grupos_opciones_id_grupo_fkey FOREIGN KEY (id_grupo)
  REFERENCES grupos (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT grupos_opciones_id_opcion_fkey FOREIGN KEY (id_opcion)
  REFERENCES opciones (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION
);

INSERT INTO opciones VALUES (1, 'Tablas Maestras', 'Administrar', 'fas fa-cog', 1, 1, 'admin');
INSERT INTO items VALUES (1, 'Usuarios', 'administracion/usuarios', 1, 1, 1, 'admin');
INSERT INTO items VALUES (2, 'Items', 'administracion/items', 1, 3, 1, 'admin');
INSERT INTO items VALUES (3, 'Grupos', 'administracion/grupos', 1, 4, 1, 'admin');
INSERT INTO items VALUES (4, 'Opciones de Grupos', 'administracion/opciones_grupos', 1, 5, 1, 'admin');
INSERT INTO items VALUES (5, 'Items de Grupos', 'administracion/items_grupos', 1, 6, 1, 'admin');
INSERT INTO items VALUES (6, 'Opciones', 'administracion/opciones', 1, 2, 1, 'admin');


INSERT INTO grupos_opciones VALUES (1,1);
INSERT INTO grupos_items VALUES(1,1);
INSERT INTO grupos_items VALUES(1,2);
INSERT INTO grupos_items VALUES(1,3);
INSERT INTO grupos_items VALUES(1,4);
INSERT INTO grupos_items VALUES(1,5);
INSERT INTO grupos_items VALUES(1,6);