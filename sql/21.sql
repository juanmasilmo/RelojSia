--creo el item
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Sincronizar Leu', 'administracion/sincronizar_leu', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/sincronizar_leu'),'admin');

ALTER TABLE calendario_agente ADD COLUMN leu integer;