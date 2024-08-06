CREATE TABLE calendario_anual
(
  id SERIAL PRIMARY KEY,
  id_estado integer references estados(id),
  fecha_inicio timestamp,
  fecha_fin timestamp NULL,
  usuario_abm character varying,
  CONSTRAINT fecha_inicio UNIQUE (fecha_inicio)
);
