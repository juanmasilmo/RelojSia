ALTER TABLE categorias
  ADD COLUMN listar_reporte integer NOT NULL DEFAULT 0;

ALTER TABLE categorias
  ADD COLUMN presentismo integer NOT NULL DEFAULT 0;

ALTER TABLE categorias
  ADD COLUMN pasajes integer NOT NULL DEFAULT 0;