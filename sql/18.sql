CREATE TABLE usuario_dependencias
(
  id_usuario integer references usuarios(id),
  id_dependencia integer references dependencias(id),
  usuario_abm character varying  
);

ALTER TABLE IF EXISTS usuario_dependencias
    ADD PRIMARY KEY (id_usuario, id_dependencia);