CREATE TABLE articulos
(
    id serial PRIMARY KEY,
    id_leu integer,    
    nro_articulo character varying NOT NULL,
    inciso character varying,
    descripcion character varying NOT NULL,
    cantidad_mensual integer,
    cantitad_anual integer,
    observacion character varying,
    inasistencias integer NOT NULL DEFAULT 0,
    licencias integer NOT NULL DEFAULT 0,
    retiro integer NOT NULL DEFAULT 0,
    tardanza integer NOT NULL DEFAULT 0,
    excluye_feria integer NOT NULL DEFAULT 0,
    tipo_licencias character varying,
    sin_fecha_fin integer NOT NULL DEFAULT 0
);


--creo el item
INSERT INTO items(descripcion, enlace, id_opcion, orden, estado, usuario_abm) VALUES ('Articulos', 'administracion/articulos', (select id from opciones where descripcion='Administrar'), 8, 1, 'admin');

--agrego el permiso para el admin
INSERT INTO grupos_items values (1,(select id from items where enlace='administracion/articulos'),'admin');


INSERT INTO articulos VALUES (1, 5, '313 BIS', '-', 'LICENCIA GREMIAL', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (2, 6, '316', '-', 'LICENCIA POR SERVICIO MILITAR', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (3, 9, '323', '-', 'PREMIO ESTIMULO', 5, 5, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (4, 26, '292', '-', 'LICENCIA POR FERIA ORDINARIA Y EXTRAORDINARIA', NULL, NULL, 'LOS MAGISTRADOS, FUNCIONARIOS Y EMPLEADOS DEL PODER JUDICIAL INCLUSIVE, LOS JUECES DE
PAZ DE TODAS LAS CATEGORÍAS Y EL PERSONAL DE SERVICIO Y MAESTRANZA, GOZARÁN DE LICENCIA DURANTE
EL PERÍODO COMPRENDIDO EN LAS FERIAS ORDINARIAS Y EXTRAORDINARIAS FIJADAS POR EL SUPERIOR
TRIBUNAL DE JUSTICIA, DE ACUERDO AL ARTÍCULO 116 DE LA LEY N° 651. DICHAS LICENCIAS SON
OBLIGATORIAS.
LOS JUECES Y FUNCIONARIOS QUE DURANTE EL RECESO JUDICIAL SALIERAN DEL LUGAR DE ASIENTO DEL
JUZGADO O TRIBUNAL, DEBERÁN COMUNICARLO AL SUPERIOR TRIBUNAL.
AC. 80/09 ESTABLECE QUE LA FERIA JUDICIAL DE VERANO SE EXTIENDE DESDE EL día 26 DE DICIEMBRE
HASTA EL 31 DE ENERO, AMBAS FECHAS INCLUSIVE, Y DE 8 A 11 EL HORARIO DE ATENCIÓN AL PÚBLICO.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (5, 21, '315', '-', 'LICENCIA EJERCICIO DE REPRESENTACIÓN EN EL INTERÉS PÚBLICO', 30, 60, 'LAS LICENCIAS A QUE SE REFIERE EL ART. ANTERIOR PODRÁN SER CONCEDIDAS CON GOCE DE SUELDO,
PERO POR UN TÉRMINO NO MAYOR DE UN AÑO, CUANDO EXISTAN PROBADAS RAZONES DE INTERÉS PÚBLICO,
EN GENERAL, O DEL PODER JUDICIAL, EN PARTICULAR, EN EL COMETIDO A CUMPLIR POR EL AGENTE O ÉSTE
ACTÚE REPRESENTANDO AL PAÍS, A LA PROVINCIA O AL PODER JUDICIAL.
EN ESTOS CASOS SE TENDRÁ EN CUENTA LAS CONDICIONES, TÍTULOS Y APTITUDES DEL AGENTE, EL
BENEFICIO QUE EL OTORGAMIENTO DE LA LICENCIA DEPARARÁ AL INTERÉS PÚBLICO Y SE DETERMINARÁN ASÍ
MISMO, LAS OBLIGACIONES DE AQUÉL A FAVOR DEL PODER JUDICIAL EN EL CUMPLIMIENTO DE LA MISIÓN.
EN EL SUPUESTO PREVISTO EN EL PRESENTE ARTÍCULO Y CUANDO LA LICENCIA NO EXCEDIERA DEL
TÉRMINO DE DOS MESES, LA MISMA NO PODRÁ OTORGARSE DURANTE LOS MESES DE NOVIEMBRE Y
DICIEMBRE.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (6, 17, '311', '3-b', 'LICENCIA POR ASUNTO FAMILIAR- FALLECIMIENTO DE PARIENTES Y CONSANGUÍNEOS AFINES DE PRIMER Y SEGUNDO GRADO', 2, NULL, 'LICENCIA POR ASUNTO FAMILIAR FALLECIMIENTOS DE PARIENTES AFINES Y DE PRIMER GRADO Y CONSANGUÍNEO Y AFINES DE SEGUNDO GRADO DOS DÍAS.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (7, 30, '1', NULL, 'LICENCIA ESPECIAL S/LEY PROVINCIAL N° I -132', 30, 180, 'ARTÍCULO 1.- ESTABLÉCESE RÉGIMEN DE LICENCIA ESPECIAL DE HASTA CIENTO OCHENTA (180) DÍAS 
CON GOCE ÍNTEGRO DE HABERES POR HIJO CON DISCAPACIDAD, EN LOS TÉRMINOS DEL ARTÍCULO 2 DE LA 
LEY XIX - Nº 23 (ANTES LEY 2707).', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (8, 27, '295', '-', 'LICENCIA MÉDICA POR CORTO TRATAMIENTO - ACORDADA 79/07', 20, 45, NULL, 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (9, 15, '299', '-', 'LICENCIAS POR ACCIDENTES DE TRABAJO Y ENFERMEDADES PROFESIONALES', 31, 365, 'EN CASO DE ENFERMEDAD PROFESIONAL CONTRAÍDA EN ACTO DE SERVICIO, O DE INCAPACIDAD
TEMPORARIA, ORIGINADA POR EL HECHO O EN OCASIÓN DEL TRABAJO O FUNCIÓN, SE CONCEDERÁ HASTA DOS
AÑOS DE LICENCIA CON GOCE DE HABERES, PRORROGABLES  EN IGUALES CONDICIONES POR UN AÑO MÁS.
SI DE CUALQUIERA DE ESTOS DOS CASOS SE DERIVARA UNA INCAPACIDAD PARCIAL PERMANENTE, DEBERÁ
ADECUARSE LAS TAREAS DEL AGENTE A SU NUEVO ESTADO.', 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (10, 29, '310', '-', 'LICENCIA POR ENFERMEDAD MIEMBRO GRUPO FAMILIAR (ACORDADA 10/08)', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (11, 22, '317', '-', 'DERECHO A LICENCIA EN CADA DECENIO', 30, 180, 'EN EL TRANSCURSO DEL SEGUNDO Y POSTERIORES DECENIOS, EL AGENTE PODRÁ USAR LICENCIA, SIN
REMUNERACIÓN, POR EL TÉRMINO DE SEIS MESES, FRACCIONABLES EN DOS PERÍODOS IGUALES O
DESIGUALES.  
EL TÉRMINO DE LICENCIA NO UTILIZADO EN UN DECENIO NO PUEDE SER ACUMULADO EN LOS
SUBSIGUIENTES Y ENTRE LA FINALIZACIÓN DE LA LICENCIA DEL DECENIO ANTERIOR Y EL COMIENZO DE LA
SIGUIENTE DEL POSTERIOR DEBERÁ TRANSCURRIR UN LAPSO DE DOS AÑOS COMO MÍNIMO.
PARA EL CÓMPUTO DE SERVICIOS NO SE TENDRÁ EN CUENTA LOS QUE HUBIESE PRESTADO EL AGENTE
EN OTROS ORGANISMOS, JUDICIALES O NO, DE LA NACIÓN O DE ÉSTA Y OTRAS PROVINCIAS.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (12, 23, '303', NULL, 'VISITA DOMICILIARIA', NULL, NULL, 'CUANDO EL EMPLEADO NO SE HALLARE EN SU DOMICILIO AL IR A EFECTUARSE EL EXAMEN MÉDICO INCURRIRÁ EN FALTA GRAVE Y SERÁ REPRIMIDO CON 
SUSPENSIÓN U OTRAS SANCIONES MÁS GRAVE EN CASO DE REINCIDENCIA, SALVO EL CASO DE QUE, POR URGENCIA ORIGINADA POR LA GRAVEDAD O 
ESPECIAL NATURALEZA DE LA ENFERMEDAD, HUBIESE SIDO NECESARIA LA INTERNACIÓN DEL AGENTE, CIRCUNSTANCIA QUE, ASIMISMO EL FACULTATIVO 
DEBERÁ COMPROBAR', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (13, 36, '0', NULL, 'LICENCIA ESPECIAL S/LEY NACIONAL N° 26.089', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'EXTRAORDINARIAS', 0);
INSERT INTO articulos VALUES (14, 37, '273', '-', 'JUECES DE PAZ. INASISTENCIAS Y AUTORIZACION PARA AUSENTARSE', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (15, 39, '313', '-', 'LICENCIA PARA EJERCER REPRESENTACIÓN EN ENTIDADES QUE NUCLEEN A PERSONAL DEL PODER JUDICIAL DEL R.P.J.-', 20, 20, 'El Superior Tribunal de Justicia podrá conceder licencia de hasta 20 días en el año calendario continuos o discontinuos , al Magistrado , funcionario o empleado que tuviere que ejercer  una representación  en nombre de los agentes del Poder Judicial, Esta licencia se otorgará con goce de haberes salvo que  la gestión o representación ya estuviere retribuida suficientemente por la entidad que la nuclea.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (16, 2, '311', '1-b', 'LICENCIA POR ASUNTO FAMILIAR - MATRIMONIO DE LOS HIJOS', 2, 2, 'MATRIMONIO DE LOS HIJOS DEL AGENTE', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (17, 47, '307 TER', 'a-', 'LICENCIA POR FALLECIMIENTO DEL NNYA', NULL, NULL, 'En caso de fallecimiento del niño, niña o adolescente se interrumpen las licencias anteriores.', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (18, 16, '311', '3-a', 'LICENCIA POR ASUNTO FAMILIAR- FALLECIMIENTO CONYUGE O FAMILIAR DIRECTO', 5, 5, 'FALLECIMIENTO DEL CÓNYUGE O PARIENTE CONSANGUÍNEO DEL PRIMER GRADO CINCO DIAS', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (19, 50, 'RES. 36/2022', '-', 'AUTORIZACION DE AISLAMIENTO - (RES. 36/2022)', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (20, 10, '272', '-', 'JUSTIFICACION DE INASISTENCIA', 2, 6, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (21, 11, '319', '-', 'LICENCIA POR EXAMEN', NULL, NULL, 'Para los que rindan fuera de Posadas es Por examen 3 dias como Maximo y 4veces al Año.-
Para rendir en la Ciudad de Posadas 1 día por examen sin limite anual .-', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (22, 12, '274', '-', 'INASISTENCIAS DE MAGISTRADOS Y FUNCIONARIOS', 2, 6, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (23, 8, '320', '-', 'LICENCIA PARA RENDIR EXÁMENES EN OTRAS FACULTADES E INSTITUTOS', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (24, 1, '311', '1-a', 'LICENCIA POR ASUNTO FAMILIAR-MATRIMONIO', 10, 10, 'MATRIMONIO DEL AGENTE', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (25, 31, '314', '-', 'LICENCIA PARA REALIZAR ESTUDIOS O ACTIVIDAD CULTURAL O DEPORTIVA EN EL PAÍS O EN EL EXTRANJERO', 30, 365, 'PODRÁ CONCEDERSE LICENCIA SIN GOCE DE HABERES POR EL TÉRMINO MÁXIMO DE DOS AÑOS AL
AGENTE QUE TENGA COMO MÍNIMO CINCO AÑOS DE ANTIGÜEDAD EN EL PODER JUDICIAL PROVINCIAL Y
DEBA REALIZAR ESTUDIOS, INVESTIGACIONES, TRABAJOS CIENTÍFICOS, TÉCNICOS, ARTÍSTICOS, O PARTICIPAR EN
CONGRESOS O CONFERENCIAS DE ESA ÍNDOLE EN EL PAÍS O EN EL EXTRANJERO.
IGUAL BENEFICIO PODRÁ OTORGARSE PARA MEJORAR LA PREPARACIÓN TÉCNICA O PROFESIONAL DEL
AGENTE O CUMPLIR ACTIVIDADES CULTURALES O DEPORTIVAS.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (26, 25, '305', '-', 'ALTA MEDICA', NULL, NULL, 'EN LOS CASOS DE LICENCIAS CONCEDIDA POR APLICACIÓN DEL ART. 296 EL AGENTE NO PODRÁ SER
REINCORPORADO A SU EMPLEO HASTA TANTO EL MÉDICO CORRESPONDIENTE NO OTORGUE EL CERTIFICADO DE
ALTA.
EL MISMO MÉDICO PODRÁ ACONSEJAR QUE AL REANUDAR SUS TAREAS SE LE ASIGNE AL AGENTE,
DURANTE UN LAPSO DETERMINADO, FUNCIONES ADECUADAS PARA COMPLETAR SU RESTABLECIMIENTO, O QUE
LAS MISMAS SE DESENVUELVAN EN UN LUGAR APROPIADO A ESA FINALIDAD.', 0, 0, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (27, 28, '296', '-', 'LICENCIA MED. POR LARGO TRATAMIENTO - ACORDADA 79/07', NULL, NULL, NULL, 0, 1, 0, 0, 1, 'MEDICAS', 0);
INSERT INTO articulos VALUES (28, 13, '295', '-', 'LICENCIA POR CAUSAL QUE IMPONGA CORTO TRATAMIENTO DE SALUD', 20, 45, 'PARA EL TRATAMIENTO DE AFECCIONES COMUNES O POR ACCIDENTE ACAECIDO FUERA DE SERVICIO SE
CONCEDERÁ HASTA CUARENTA Y CINCO DÍAS DE LICENCIA POR AÑO CALENDARIO, EN FORMA DISCONTINUA
CON PERCEPCIÓN ÍNTEGRA DE HABERES. TODA LICENCIA POR ESTA CAUSAL DE HASTA VEINTE  DÍAS 
CONTINUOS, SERÁ ACREDITADA CON EL CERTIFICADO DEL MÉDICO DE TRIBUNALES, DEL MÉDICO FORENSE,
DEL MÉDICO DE POLICÍA, DEL MÉDICO OFICIAL O DEL PARTICULAR, SEGÚN EL CASO (ART. 302) Y EN LOS
CASOS QUE EXCEDAN DE ESTE TÉRMINO SE DARÁ INTERVENCIÓN A LA JUNTA MÉDICA PREVISTA EN EL ART.
226 QUIEN DICTAMINARÁ SI CORRESPONDE ACORDAR DICHA LICENCIA POR EL RÉGIMEN ESTABLECIDOS EN
LOS ARTÍCULOS 296 Y 297.
PASADOS LOS CUARENTA Y CINCO DÍAS ESTA LICENCIA SERÁ SIN GOCE DE HABERES.', 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (29, 20, '310', '2', 'LICENCIA POR ENFERMEDAD DE UN MIEMBRO DEL GRUPO FAMILIAR -', 30, 40, 'INCISO 2° PARA CONSAGRARSE A LA ATENCIÓN DE UN MIEMBRO ENFERMO DEL GRUPO FAMILIAR
CONSTITUIDO EN EL HOGAR, HASTA CUARENTA díaS CORRIDOS, CONTINUOS O DISCONTINUOS, POR AÑO
CALENDARIO. A ESTE FIN EL AGENTE DEBERÁ EXPRESAR, POR DECLARACIÓN JURADA, QUE ES INDISPENSABLESU ASISTENCIA. ESTAS CAUSALES DE ENFERMEDAD DEBERÁN SER COMPROBADAS POR EL MÉDICO DE
TRIBUNALES O FORENSE CORRESPONDIENTE. EN LAS DEMÁS LOCALIDADES DEBERÁ RECURRIRSE A ESOS
EFECTOS  A LOS MÉDICOS DE SALUD PÚBLICA DE LA PROVINCIA RADICADOS EN EL LUGAR, O DE LA NACIÓN O
CORRESPONDIENTE PROVINCIA, SI EL AGENTE ESTUVIERE EN OTRA JURISDICCIÓN.', 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (30, 34, '293', NULL, 'COMPENSACION POR SERVICIOS PRESTADOS EN FERIA ORDINARIAS Y EXTRAORDINARIAS', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (31, 35, '278', NULL, 'INASISTENCIAS INJUSTIFICADAS - COMUNICACIONES AL SUPERIOR TRIBUNAL', NULL, NULL, NULL, 1, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (32, 14, '296', NULL, 'LICENCIA POR CAUSAL QUE IMPONGA LARGO TRATAMIENTO DE SALUD', NULL, 365, 'POR AFECCIONES QUE IMPONGAN LARGO TRATAMIENTO DE SALUD O POR MOTIVOS QUE ACONSEJEN LA
HOSPITALIZACIÓN O EL ALEJAMIENTO DEL AGENTE POR RAZONES DE PROFILAXIS O SEGURIDAD SE CONCEDERÁ
HASTA DOS AÑOS CORRIDOS DE LICENCIA, CON PERCEPCIÓN ÍNTEGRA DE HABERES, PREVIO DICTAMEN DE LA
JUNTA MÉDICA PREVISTA EN EL ART. 226 DE ESTE REGLAMENTO. AL VENCIMIENTO DE ESTE PLAZO, SUBSISTIENDO LA CAUSAL QUE DETERMINÓ LA LICENCIA, PREVIO
LOS RECONOCIMIENTOS MÉDICOS ANTES MENCIONADOS, SE CONCEDERÁ LA AMPLIACIÓN DE LA MISMA
HASTA POR UN AÑO, EN QUE EL AGENTE PERCIBIRÁ LA MITAD DE SU REMUNERACIÓN.
AL CUMPLIMIENTO DE LA PRÓRROGA SERÁ NUEVAMENTE RECONOCIDO POR LAS AUTORIDADES
MÉDICAS LAS QUE DETERMINARÁN, DE ACUERDO CON LA CAPACIDAD LABORATIVA DEL AGENTE, LAS
FUNCIONES QUE PODRÁ DESEMPEÑAR EN LA ADMINISTRACIÓN DE JUSTICIA. EN CASO DE INCAPACIDAD TOTAL
SE APLICARÁN LAS LEYES DE PREVISIÓN Y AYUDA SOCIAL PROVINCIALES CORRESPONDIENTES.', 0, 1, 0, 0, 1, 'MEDICAS', 0);
INSERT INTO articulos VALUES (33, 18, '310', '1', 'LICENCIA POR ENFERMEDAD DE UN MIEMBRO DEL GRUPO FAMILIAR', 20, 20, 'INCISO 1° EN CASO DE GRAVE ENFERMEDAD QUE PONGA EN PELIGRO LA VIDA DEL CÓNYUGE, HIJOS
PADRES O HERMANOS CONSANGUÍNEOS, DIEZ díaS CORRIDOS DE LICENCIA. SI ESA PERSONA SE ENCONTRARE
EN JURISDICCIÓN DE OTRA PROVINCIA Y EL AGENTE NECESITARE TRASLADARSE EL LUGAR, VEINTE díaS. AL
TÉRMINO DE ESTA LICENCIA EL AGENTE DEBERÁ ACREDITAR CON LOS PERTINENTES CERTIFICADOS MÉDICOS LA
CAUSAL INVOCADA.', 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (34, 38, 'LIC. ESP. SIT. P.', NULL, 'LICENCIA ESPECIAL - SITUACIONES PARTICULARES', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 1);
INSERT INTO articulos VALUES (35, 42, '307', 'a-', 'LICENCIA POR NACIMIENTO', NULL, 135, 'se acordará a la agente mujer progenitora gestante, licencia, con goce de sueldo, de ciento treinta y cinco (135) días, divididos en dos períodos, uno anterior de cuarenta y cinco (45) días y otro posterior al parto de noventa (90) días. Sin embargo, la interesada podrá solicitar la reducción del periodo previo hasta treinta días, en cuyo caso se extenderá proporcionalmente el período posterior. Este criterio se aplicará también cuando el parto se adelante respecto de la fecha prevista. En caso que el nacimiento se retrasare en relación a la fecha presunta fijada por el médico, el excedente se imputará al segundo período. En esta licencia el médico forense podrá limitarse a visar el certificado expedido por el médico particular u otro oficial.', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (36, 40, 'LIC. ESP. COVID', NULL, 'LICENCIA ESPECIAL - COVID 19 - AC. EXT. 02/2020', NULL, NULL, 'Como medida preventiva conceder licencia con goce de haberes a partir del día dela fecha todos los Magistrados, Funcionarios y agentes del Poder Judicial de la Provincia mayores de sesenta años de edad; embarazadas; con enfermedades de carácter oncológicas, pacientes inmuno deprimidos (diabéticos, pacientes con HIV,yEPOC- enfermedad pulmonar obstructiva crónica, personas con antecedentes cardíacos, personas con insuficiencia renal)con la debida acreditación del Cuerpo Médico Forense correspondiente', 0, 1, 0, 0, 0, 'MEDICAS', 0);
INSERT INTO articulos VALUES (37, 33, 'LIC. ESP.', NULL, 'LICENCIA ESPECIAL -ACORDADA 75/14-', NULL, NULL, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 1);
INSERT INTO articulos VALUES (38, 32, '254', '4', 'LICENCIA POR REPRESENTACIÓN POLÍTICA', NULL, NULL, 'No realizar actos de proselitismo político en los lugares de trabajo o con motivo u ocasión del desempeño de sus funciones. El agente que pretendiese ser o fuere electo para desempeñar cargos de representación política en el orden nacional o provincial o fuera designado miembro o Funcionario del Poder Ejecutivo o Legislativo de la Nación o de la Provincia, deberá solicitar licencia a partir del momento en que fuere nominado, la que será acordada sin percepción de haberes hasta quince (15) días después de finalizado su mandato. Si terminada la licencia el agente no se presentare a retomar su cargo, se considerará que hace abandono del mismo. Igual obligación rige para el agente que fuera nominado para ejercer una representación política en municipalidades de primera y segunda categorías, o fuere designado para ejercer funciones en las mismas.', 0, 1, 0, 0, 0, 'ORDINARIAS', 1);
INSERT INTO articulos VALUES (39, 24, '307', '-', 'LICENCIA POR MATERNIDAD', 30, 135, 'POR MATERNIDAD SE ACORDARÁ LICENCIA, CON GOCE DE SUELDO, DE CIENTO TREINTA Y CINCO (135)
DÍAS, DIVIDIDOS EN DOS PERÍODOS, UNO ANTERIOR DE CUARENTA Y CINCO (45) DÍAS Y OTRO POSTERIOR AL
PARTO DE NOVENTA (90) DÍAS. SIN EMBARGO, LA INTERESADA PODRÁ SOLICITAR LA REDUCCIÓN DEL PERIODO
PREVIO HASTA TREINTA DÍAS, EN CUYO CASO SE EXTENDERÁ PROPORCIONALMENTE EL PERÍODO POSTERIOR.
ESTE CRITERIO SE APLICARÁ TAMBIÉN CUANDO EL PARTO SE ADELANTE RESPECTO DE LA FECHA PREVISTA. EN
CASO QUE EL NACIMIENTO SE RETRASARE EN RELACIÓN A LA FECHA PRESUNTA FIJADA POR EL MÉDICO, EL
EXCEDENTE SE IMPUTARÁ AL SEGUNDO PERÍODO. EN LAS LICENCIAS POR MATERNIDAD EL MÉDICO FORENSE
PODRÁ LIMITARSE A VISAR EL CERTIFICADO EXPEDIDO POR EL MÉDICO PARTICULAR U OTRO OFICIAL.

Tomarse la  Licencia 30 o 45 dias Antes de la Fecha PPP', 0, 1, 0, 0, 1, 'MEDICAS', 0);
INSERT INTO articulos VALUES (40, 3, '311', '2-a', 'LICENCIA POR ASUNTO FAMILIAR- POR NACIMIENTO PARA LOS AGENTES PROGENITOR/A NO GESTANTE', 15, 15, 'De los hijos del agente varón, quince días, que serán utilizados a continuación de la fecha del nacimiento.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (41, 44, '307', 'a-2', 'LICENCIA POR NACIMIENTO - NACIMIENTO MÚLTIPLE', NULL, NULL, 'En caso de nacimientos múltiples el plazo se extenderá a ciento ochenta (180) días, divididos en dos períodos, uno anterior de cuarenta y cinco (45) días y otro posterior al parto de ciento treinta y cinco (135) días. Sin embargo, la interesada podrá solicitar la reducción del período previo hasta treinta (30) días, en cuyo caso se extenderá proporcionalmente el período posterior.', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (42, 43, '307', 'a-1', 'LICENCIA POR NACIMIENTO - PARTO PREMATURO', NULL, NULL, 'En caso de parto prematuro, se sumará a dicha licencia el número de días equivalentes a la diferencia entre la fecha de nacimiento y la fecha de alta médica del recién nacido.', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (43, 45, '307', 'b-', 'LICENCIA POR GUARDA O ADOPCIÓN ART 657 CCCN', NULL, NULL, 'Por guarda con fines de adopción o guarda del art. 657 del CCCN de niños, niñas y adolescentes: se acordará licencia, con goce de sueldo, de ciento cinco (105) días a partir del otorgamiento judicial de la guarda. En caso de guardas múltiples el plazo se extenderá a ciento cincuenta (150) días. Cuando haya más de un guardador y ambos/as sean agentes del poder judicial, los/las guardadores/ras deberán optar manifestando cuál de ellos/as va a hacer uso de esta licencia, correspondiéndole a el/la otro/a co-guardador/a la licencia por asunto familiar del art. 311 inc. 2), apartado b).

Texto según Ac. 19/21. Vigencia: a partir del 05/04/2021

Ac. 122/06 y modificatorias. Presentismo (Apéndice)', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (44, 48, '307 TER', 'b-', 'LICENCIA POR ADOPCIÓN O GUARDA DE NNYA CON DISCAPACIDAD', NULL, NULL, 'En caso de nacimiento o guarda con fines de adopción de niños, niñas y/o adolescentes con discapacidad, se sumarán a las licencias anteriores lo establecido en la Ley Provincial I  Nº 132 (antes Ley 4123)', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (45, 49, '311', '2-b', 'LICENCIA POR ASUNTO FAMILIAR - POR GUARDA', NULL, NULL, NULL, 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (46, 46, '307 BIS', '-', 'LICENCIA POR FALLECIMIENTO DE AGENTE PROGENITOR/A O GUARDADOR/A', NULL, NULL, 'En caso de fallecimiento del/de la agente que estuviere haciendo uso de las licencias anteriores, el otro progenitor/a o guardador/a del niño, niña o adolescente tendrá derecho a usufructuar el resto de la licencia.

Texto según Ac. 19/21. Vigencia: a partir del 05/04/2021', 0, 1, 0, 0, 1, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (47, 7, '318', '-', 'LICENCIA PARA RENDIR EXAMEN EN FACULTADES E INSTITUTOS DE CORRIENTES, CHACO Y FORMOSA', NULL, NULL, 'PARA RENDIR EXAMEN EN FACULTADES, INSTITUTOS O CASAS DE ESTUDIOS PERTENECIENTES A LA
UNIVERSIDAD DEL NORDESTE O A CUALQUIER OTRA UNIVERSIDAD OFICIAL O PRIVADA CON SEDE EN LAS
PROVINCIAS DE CORRIENTES, CHACO O FORMOSA Y SIEMPRE QUE LOS ORGANISMOS PRIMERAMENTE
MENCIONADOS NO ESTUVIESEN UBICADOS EN ESTA PROVINCIA DE MISIONES, LOS AGENTES GOZARÁN DE
LICENCIA REMUNERADA HASTA CUATRO VECES POR AÑO Y POR UN PERÍODO MÁXIMO DE CINCO díaS
LABORABLES CADA VEZ.', 0, 1, 0, 0, 0, 'ORDINARIAS', 0);
INSERT INTO articulos VALUES (48, 4, '312', '-', 'LICENCIA POR MOTIVOS PERSONALES', NULL, 8, NULL, 0, 1, 0, 0, 0, 'ORDINARIAS', 0);


ALTER TABLE articulos ADD COLUMN estado integer DEFAULT 1;