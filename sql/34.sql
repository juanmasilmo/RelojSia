-- agrego atributo a la tabla para chequear si carga manual o sincroniza con leu
ALTER TABLE articulos
  ADD COLUMN c_manual integer NOT NULL DEFAULT 0;
COMMENT ON COLUMN articulos.c_manual IS '0 - sincroniza leu 1 - carga manual';