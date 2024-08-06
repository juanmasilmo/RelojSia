-- agrego atributo a la tabla para chequear si carga manual los registros por dia
ALTER TABLE usuarios
  ADD COLUMN carga_registro integer NOT NULL DEFAULT 0;
COMMENT ON COLUMN usuarios.carga_registro IS '0 - solo articulos 1 - carga registros + articulos';