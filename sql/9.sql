CREATE TABLE calendario_agente
(
  id SERIAL PRIMARY KEY,
  legajo integer,
  registro timestamp with time zone,
  registro_modificado timestamp with time zone,
  id_articulo integer references articulos(id),
  borrado integer,
  fecha_abm timestamp,
  usuario_abm character varying
);

--creo el item
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Calendario Agente', 'administracion/calendario_agente/listado', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/calendario_agente/listado'),'admin');
