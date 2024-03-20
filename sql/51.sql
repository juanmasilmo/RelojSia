--creo la opcion
INSERT INTO opciones (titulo, descripcion, icono, orden, estado, usuario_abm) VALUES ('Reportes', 'Reportes', 'fas fa-cog', 1, 1, 'admin');
--agrego el permiso para el adminn
INSERT INTO grupos_opciones (id_grupo, id_opcion,usuario_abm)  VALUES (1,(select id from opciones where titulo='Reportes'),'admin');

--creo el item
INSERT INTO items (descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Inasistencias', 'reportes/inasistencias', (select id from opciones where titulo='Reportes'), 1, 1, 'admin');
--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='reportes/inasistencias'),'admin');

ALTER TABLE articulos
  ADD COLUMN desc_pasajes integer DEFAULT 1;
COMMENT ON COLUMN articulos.desc_pasajes IS '-- 0 No descuenta -- 1  descuenta pasajes';
