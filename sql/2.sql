CREATE TABLE estados
(
  id SERIAL PRIMARY KEY,
  descripcion character varying,
  letra character varying,
  color character varying,
  usuario_abm character varying  
);

--creo el item
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Estados', 'administracion/estados', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/estados'),'admin');