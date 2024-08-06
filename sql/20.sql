-- ALTER TABLE calendario_agente 
--     ADD COLUMN id_dependencia int references dependencias(id)

--creo el item
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Carga Novedades', 'administracion/carga_novedades', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/carga_novedades'),'admin');
