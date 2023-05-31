--creo el item
<<<<<<< HEAD
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Calendario Anual', 'administracion/calendario/listado', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/calendario/listado'),'admin');
=======
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Calendario Anual', 'administracion/calendario_anual', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/calendario_anual'),'admin');
>>>>>>> 2f401a1b1dd71b01619d7c06e1dc7f8b608b9fd9
