-- Adminer 3.7.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '-03:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `automotor`;
CREATE TABLE `automotor` (
  `automotor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `marca` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `modelo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `patente` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` enum('Automotor','Pickup A','Pickup B','Moto','Acoplado','Batán') COLLATE utf8_unicode_ci NOT NULL,
  `uso` enum('Particular','Comercial','Comercial / Particular') COLLATE utf8_unicode_ci NOT NULL,
  `ano` smallint(5) unsigned NOT NULL,
  `carroceria` enum('Sedan 2 puertas','Sedan 3 puertas','Sedan 4 puertas','Sedan 5 puertas','Rural 3 puertas','Rural 5 puertas','Berlina 3 puertas','Berlina 5 puertas','Break') COLLATE utf8_unicode_ci NOT NULL,
  `combustible` enum('Nafta','Diesel') COLLATE utf8_unicode_ci NOT NULL,
  `0km` tinyint(3) unsigned NOT NULL,
  `importado` tinyint(3) unsigned NOT NULL,
  `nro_motor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nro_chasis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chapa` enum('Bueno','Regular','Malo') COLLATE utf8_unicode_ci DEFAULT NULL,
  `pintura` enum('Bueno','Regular','Malo') COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_pintura` enum('Común','Bicapa','Tricapa') COLLATE utf8_unicode_ci DEFAULT NULL,
  `tapizado` enum('Tela','Pana','Cuero') COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accesorios` tinyint(3) unsigned NOT NULL,
  `zona_riesgo` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL,
  `prendado` tinyint(3) unsigned NOT NULL,
  `acreedor_rs` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acreedor_cuit` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `infoauto` tinyint(3) unsigned NOT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `alarma` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `corta_corriente` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `corta_nafta` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `traba_volante` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `matafuego` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tuercas` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `equipo_rastreo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `micro_grabado` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `antena` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `estereo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parlantes` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `aire` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cristales_electricos` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `faros_adicionales` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cierre_sincro` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `techo_corredizo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `direccion_hidraulica` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `frenos_abs` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `airbag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cristales_tonalizados` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gps` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cubiertas_medidas` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cubiertas_marca` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cubiertas_desgaste_di` tinyint(3) unsigned NOT NULL,
  `cubiertas_desgaste_dd` tinyint(3) unsigned NOT NULL,
  `cubiertas_desgaste_ti` tinyint(3) unsigned NOT NULL,
  `cubiertas_desgaste_td` tinyint(3) unsigned NOT NULL,
  `cubiertas_desgaste_1ei` tinyint(3) unsigned DEFAULT NULL,
  `cubiertas_desgaste_1ed` tinyint(3) unsigned DEFAULT NULL,
  `cubiertas_desgaste_auxilio` tinyint(3) unsigned NOT NULL,
  `nro_oblea` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nro_regulador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca_regulador` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca_cilindro` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `venc_oblea` date DEFAULT NULL,
  `nro_tubo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cobertura_tipo` enum('A','B','B1','C','C1','D','Selecta','Plata','Oro') COLLATE utf8_unicode_ci NOT NULL,
  `franquicia` mediumint(8) unsigned DEFAULT NULL,
  `limite_rc` enum('$500.000','$3.000.000','$10.000.000') COLLATE utf8_unicode_ci NOT NULL,
  `servicio_grua` tinyint(3) unsigned DEFAULT NULL,
  `valor_vehiculo` mediumint(8) unsigned NOT NULL,
  `valor_gnc` mediumint(8) unsigned NOT NULL,
  `valor_accesorios` mediumint(8) unsigned NOT NULL,
  `valor_total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`automotor_id`),
  UNIQUE KEY `poliza_id` (`poliza_id`) USING BTREE,
  CONSTRAINT `automotor_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `cliente_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cliente_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_nacimiento` date NOT NULL,
  `cliente_sexo` enum('F','M') COLLATE utf8_unicode_ci NOT NULL,
  `cliente_tipo_doc` enum('Pasaporte','LC','LE','DNI') COLLATE utf8_unicode_ci NOT NULL,
  `cliente_nro_doc` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_nacionalidad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_cf` enum('Consumidor Final','Responsable Inscripto','Monotribustista','Excento') COLLATE utf8_unicode_ci NOT NULL,
  `cliente_registro` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente_reg_vencimiento` date DEFAULT NULL,
  `cliente_reg_tipo` enum('Profesional','Particular','Motos','Carga','B1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente_cuit` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`cliente_id`),
  KEY `cliente_nombre` (`cliente_nombre`),
  KEY `cliente_nro_doc` (`cliente_nro_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `contacto`;
CREATE TABLE `contacto` (
  `contacto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` int(10) unsigned NOT NULL,
  `contacto_tipo` enum('Particular','Laboral') COLLATE utf8_unicode_ci NOT NULL,
  `contacto_domicilio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contacto_nro` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `contacto_piso` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacto_dpto` varchar(3) COLLATE utf8_unicode_ci DEFAULT '0',
  `contacto_localidad` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contacto_cp` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `contacto_telefono1` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacto_telefono2` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacto_default` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`contacto_id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `cuota`;
CREATE TABLE `cuota` (
  `cuota_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `cuota_nro` tinyint(3) unsigned NOT NULL,
  `cuota_periodo` date NOT NULL,
  `cuota_monto` decimal(10,2) unsigned NOT NULL,
  `cuota_vencimiento` date NOT NULL,
  `cuota_estado` enum('1 - No Pagado','2 - Pagado','3 - Anulado') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1 - No Pagado',
  `cuota_fe_pago` date DEFAULT NULL,
  `cuota_recibo` int(10) unsigned DEFAULT NULL,
  `cuota_pfc` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`cuota_id`),
  UNIQUE KEY `poliza_id` (`poliza_id`,`cuota_nro`),
  UNIQUE KEY `poliza_id_2` (`poliza_id`,`cuota_periodo`),
  UNIQUE KEY `cuota_recibo` (`cuota_recibo`),
  CONSTRAINT `cuota_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `poliza`;
CREATE TABLE `poliza` (
  `poliza_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` int(10) unsigned NOT NULL,
  `subtipo_poliza_id` int(10) unsigned NOT NULL,
  `productor_seguro_id` int(10) unsigned NOT NULL,
  `poliza_estado` enum('Pendiente','En Vigencia','A Renovar','Renovada','Finalizada') COLLATE utf8_unicode_ci NOT NULL,
  `poliza_anulada` tinyint(3) unsigned NOT NULL,
  `poliza_numero` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poliza_renueva_num` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poliza_vigencia` enum('Bimestral','Semestral','Anual') COLLATE utf8_unicode_ci NOT NULL,
  `poliza_validez_desde` date NOT NULL,
  `poliza_validez_hasta` date NOT NULL,
  `poliza_cuotas` enum('Mensual','Total') COLLATE utf8_unicode_ci NOT NULL,
  `poliza_cant_cuotas` tinyint(3) unsigned NOT NULL,
  `poliza_fecha_solicitud` date DEFAULT NULL,
  `poliza_fecha_emision` date DEFAULT NULL,
  `poliza_fecha_recepcion` date DEFAULT NULL,
  `poliza_fecha_entrega` date DEFAULT NULL,
  `poliza_correo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `poliza_entregada` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `poliza_prima` decimal(10,2) unsigned DEFAULT NULL,
  `poliza_premio` decimal(10,2) unsigned NOT NULL,
  `poliza_medio_pago` enum('Tarjeta de Crédito','Débito Bancario','Cuponera','Directo') COLLATE utf8_unicode_ci NOT NULL,
  `poliza_pago_detalle` blob,
  `poliza_ajuste` tinyint(3) unsigned DEFAULT NULL,
  `poliza_recargo` decimal(5,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`poliza_id`),
  KEY `subtipo_poliza_id` (`subtipo_poliza_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `productor_seguro_id` (`productor_seguro_id`),
  CONSTRAINT `poliza_ibfk_1` FOREIGN KEY (`subtipo_poliza_id`) REFERENCES `subtipo_poliza` (`subtipo_poliza_id`),
  CONSTRAINT `poliza_ibfk_4` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`),
  CONSTRAINT `poliza_ibfk_5` FOREIGN KEY (`productor_seguro_id`) REFERENCES `productor_seguro` (`productor_seguro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `productor`;
CREATE TABLE `productor` (
  `productor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productor_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `productor_iva` enum('CF','RI') COLLATE utf8_unicode_ci NOT NULL,
  `productor_cuit` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `productor_matricula` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `productor_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `productor_telefono` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`productor_id`),
  UNIQUE KEY `productor_cuit` (`productor_cuit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `productor_seguro`;
CREATE TABLE `productor_seguro` (
  `productor_seguro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `productor_id` int(10) unsigned NOT NULL,
  `seguro_id` int(10) unsigned NOT NULL,
  `productor_seguro_codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`productor_seguro_id`),
  UNIQUE KEY `code` (`productor_id`,`seguro_id`,`productor_seguro_codigo`) USING BTREE,
  KEY `seguro_id` (`seguro_id`),
  KEY `productor_id` (`productor_id`) USING BTREE,
  CONSTRAINT `productor_seguro_ibfk_1` FOREIGN KEY (`productor_id`) REFERENCES `productor` (`productor_id`),
  CONSTRAINT `productor_seguro_ibfk_2` FOREIGN KEY (`seguro_id`) REFERENCES `seguro` (`seguro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `seguro`;
CREATE TABLE `seguro` (
  `seguro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seguro_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seguro_email_siniestro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seguro_email_emision` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`seguro_id`),
  UNIQUE KEY `seguro_nombre` (`seguro_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `subtipo_poliza`;
CREATE TABLE `subtipo_poliza` (
  `subtipo_poliza_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_poliza_id` int(10) unsigned NOT NULL,
  `subtipo_poliza_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtipo_poliza_tabla` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`subtipo_poliza_id`),
  UNIQUE KEY `subtipo_poliza_tabla` (`subtipo_poliza_tabla`),
  KEY `tipo_poliza_id` (`tipo_poliza_id`),
  CONSTRAINT `subtipo_poliza_ibfk_1` FOREIGN KEY (`tipo_poliza_id`) REFERENCES `tipo_poliza` (`tipo_poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `tipo_poliza`;
CREATE TABLE `tipo_poliza` (
  `tipo_poliza_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_poliza_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`tipo_poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usuario_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_acceso` enum('administrativo','master','deshabilitado') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'deshabilitado',
  `usuario_usuario` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_clave` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_cambioclave` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usuario_reseteado` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `usuario_usuario` (`usuario_usuario`),
  UNIQUE KEY `usuario_email` (`usuario_email`),
  KEY `usuario_clave` (`usuario_clave`),
  KEY `usuario_acceso` (`usuario_acceso`),
  KEY `usuario_nombre` (`usuario_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2013-06-26 11:57:45