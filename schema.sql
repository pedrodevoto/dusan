-- Adminer 3.7.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '-03:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `accidentes`;
CREATE TABLE `accidentes` (
  `accidentes_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`accidentes_id`),
  KEY `poliza_id` (`poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `accidentes_asegurado`;
CREATE TABLE `accidentes_asegurado` (
  `accidentes_asegurado_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `accidentes_asegurado_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `accidentes_asegurado_documento` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `accidentes_asegurado_nacimiento` date NOT NULL,
  `accidentes_asegurado_domicilio` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accidentes_asegurado_actividad` int(11) NOT NULL,
  `accidentes_asegurado_suma_asegurada` decimal(10,2) NOT NULL,
  `accidentes_asegurado_gastos_medicos` decimal(10,2) NOT NULL,
  `accidentes_asegurado_beneficiario` tinyint(1) NOT NULL,
  `accidentes_asegurado_beneficiario_nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accidentes_asegurado_beneficiario_documento` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accidentes_asegurado_beneficiario_nacimiento` date DEFAULT NULL,
  PRIMARY KEY (`accidentes_asegurado_id`),
  KEY `accidentes_asegurado_actividad` (`accidentes_asegurado_actividad`),
  KEY `poliza_id` (`poliza_id`),
  CONSTRAINT `accidentes_asegurado_ibfk_1` FOREIGN KEY (`accidentes_asegurado_actividad`) REFERENCES `asegurado_actividad` (`asegurado_actividad_id`),
  CONSTRAINT `accidentes_asegurado_ibfk_2` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `accidentes_clausula`;
CREATE TABLE `accidentes_clausula` (
  `accidentes_clausula_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `accidentes_clausula_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `accidentes_clausula_cuit` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `accidentes_clausula_domicilio` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`accidentes_clausula_id`),
  KEY `poliza_id` (`poliza_id`),
  CONSTRAINT `accidentes_clausula_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `asegurado_actividad`;
CREATE TABLE `asegurado_actividad` (
  `asegurado_actividad_id` int(11) NOT NULL AUTO_INCREMENT,
  `asegurado_actividad_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`asegurado_actividad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor`;
CREATE TABLE `automotor` (
  `automotor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `automotor_marca_id` int(11) NOT NULL,
  `modelo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `castigado` tinyint(1) NOT NULL DEFAULT '0',
  `patente` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `automotor_tipo_id` int(11) NOT NULL,
  `uso` enum('Particular','Comercial','Comercial / Particular') COLLATE utf8_unicode_ci NOT NULL,
  `ano` smallint(5) unsigned NOT NULL,
  `automotor_carroceria_id` int(11) NOT NULL,
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
  `equipo_rastreo_id` int(11) unsigned DEFAULT NULL,
  `equipo_rastreo_pedido_id` int(11) DEFAULT NULL,
  `micro_grabado` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cupon_vintrak` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cupon_vintrak_fecha` date DEFAULT NULL,
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
  `seguro_cobertura_tipo_id` int(11) NOT NULL,
  `franquicia` mediumint(8) unsigned DEFAULT NULL,
  `seguro_cobertura_tipo_limite_rc_id` int(11) NOT NULL,
  `servicio_grua` tinyint(3) unsigned DEFAULT NULL,
  `valor_vehiculo` mediumint(8) unsigned NOT NULL,
  `valor_gnc` mediumint(8) unsigned NOT NULL,
  `valor_accesorios` mediumint(8) unsigned NOT NULL,
  `valor_total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`automotor_id`),
  UNIQUE KEY `poliza_id` (`poliza_id`) USING BTREE,
  KEY `automotor_tipo_id` (`automotor_tipo_id`),
  KEY `automotor_carroceria_id` (`automotor_carroceria_id`),
  KEY `equipo_rastreo_id` (`equipo_rastreo_id`),
  KEY `equipo_rastreo_pedido_id` (`equipo_rastreo_pedido_id`),
  CONSTRAINT `automotor_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`),
  CONSTRAINT `automotor_ibfk_3` FOREIGN KEY (`automotor_tipo_id`) REFERENCES `automotor_tipo` (`automotor_tipo_id`),
  CONSTRAINT `automotor_ibfk_4` FOREIGN KEY (`automotor_carroceria_id`) REFERENCES `automotor_carroceria` (`automotor_carroceria_id`),
  CONSTRAINT `automotor_ibfk_5` FOREIGN KEY (`equipo_rastreo_pedido_id`) REFERENCES `equipo_rastreo_pedido` (`equipo_rastreo_pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_accesorio`;
CREATE TABLE `automotor_accesorio` (
  `automotor_accesorio_id` int(11) NOT NULL AUTO_INCREMENT,
  `automotor_id` int(10) unsigned NOT NULL,
  `automotor_accesorio_cantidad` int(11) NOT NULL,
  `automotor_accesorio_detalle` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `automotor_accesorio_valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`automotor_accesorio_id`),
  KEY `automotor_id` (`automotor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_carroceria`;
CREATE TABLE `automotor_carroceria` (
  `automotor_carroceria_id` int(11) NOT NULL AUTO_INCREMENT,
  `automotor_carroceria_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`automotor_carroceria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_gnc_foto`;
CREATE TABLE `automotor_gnc_foto` (
  `automotor_gnc_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `automotor_gnc_foto_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `automotor_gnc_foto_thumb_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `automotor_gnc_foto_width` int(11) NOT NULL,
  `automotor_gnc_foto_height` int(11) NOT NULL,
  PRIMARY KEY (`automotor_gnc_foto_id`),
  KEY `poliza_id` (`poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_marca`;
CREATE TABLE `automotor_marca` (
  `automotor_marca_id` int(11) NOT NULL AUTO_INCREMENT,
  `automotor_marca_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`automotor_marca_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_micrograbado_foto`;
CREATE TABLE `automotor_micrograbado_foto` (
  `automotor_micrograbado_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `automotor_micrograbado_foto_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `automotor_micrograbado_foto_thumb_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `automotor_micrograbado_foto_width` int(11) NOT NULL,
  `automotor_micrograbado_foto_height` int(11) NOT NULL,
  PRIMARY KEY (`automotor_micrograbado_foto_id`),
  KEY `poliza_id` (`poliza_id`),
  CONSTRAINT `automotor_micrograbado_foto_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_tipo`;
CREATE TABLE `automotor_tipo` (
  `automotor_tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `automotor_tipo_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`automotor_tipo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `automotor_tipo_carroceria`;
CREATE TABLE `automotor_tipo_carroceria` (
  `automotor_tipo_carroceria_id` int(11) NOT NULL AUTO_INCREMENT,
  `automotor_tipo_id` int(11) NOT NULL,
  `automotor_carroceria_id` int(11) NOT NULL,
  PRIMARY KEY (`automotor_tipo_carroceria_id`),
  KEY `automotor_tipo_id` (`automotor_tipo_id`),
  KEY `automotor_carroceria_id` (`automotor_carroceria_id`),
  CONSTRAINT `automotor_tipo_carroceria_ibfk_1` FOREIGN KEY (`automotor_tipo_id`) REFERENCES `automotor_tipo` (`automotor_tipo_id`) ON DELETE CASCADE,
  CONSTRAINT `automotor_tipo_carroceria_ibfk_2` FOREIGN KEY (`automotor_carroceria_id`) REFERENCES `automotor_carroceria` (`automotor_carroceria_id`) ON DELETE CASCADE
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


DROP TABLE IF EXISTS `cliente_foto`;
CREATE TABLE `cliente_foto` (
  `cliente_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(10) unsigned NOT NULL,
  `cliente_foto_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_foto_thumb_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_foto_width` int(11) NOT NULL,
  `cliente_foto_height` int(11) NOT NULL,
  PRIMARY KEY (`cliente_foto_id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `cliente_foto_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `cobertura_tipo`;
CREATE TABLE `cobertura_tipo` (
  `cobertura_tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `cobertura_tipo_nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cobertura_tipo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `combinado_familiar`;
CREATE TABLE `combinado_familiar` (
  `combinado_familiar_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `combinado_familiar_domicilio_calle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_domicilio_nro` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_domicilio_piso` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_domicilio_dpto` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_domicilio_localidad` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_domicilio_cp` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_lote` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_valor_tasado` decimal(10,2) DEFAULT NULL,
  `combinado_familiar_prorrata_obj_esp` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_inc_edif` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_inc_edif_rep` tinyint(3) unsigned DEFAULT NULL,
  `combinado_familiar_inc_mob` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_ef_personales` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_rc_inc` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_cristales` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_responsabilidad_civil` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_danios_agua` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_jugadores_golf` decimal(10,2) unsigned DEFAULT NULL,
  `combinado_familiar_inc_edif_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_inc_mob_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_ef_personales_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_rc_inc_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_tv_aud_vid_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_obj_esp_prorrata_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_equipos_computacion_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_film_foto_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_cristales_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_responsabilidad_civil_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_danios_agua_flag` tinyint(3) unsigned NOT NULL,
  `combinado_familiar_jugadores_golf_flag` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`combinado_familiar_id`),
  UNIQUE KEY `poliza_id` (`poliza_id`),
  CONSTRAINT `combinado_familiar_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `combinado_familiar_equipos_computacion`;
CREATE TABLE `combinado_familiar_equipos_computacion` (
  `combinado_familiar_equipos_computacion_id` int(11) NOT NULL AUTO_INCREMENT,
  `combinado_familiar_id` int(11) NOT NULL,
  `combinado_familiar_equipos_computacion_cantidad` int(11) NOT NULL,
  `combinado_familiar_equipos_computacion_producto` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_equipos_computacion_marca` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_equipos_computacion_serial` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_equipos_computacion_valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`combinado_familiar_equipos_computacion_id`),
  KEY `combinado_familiar_id` (`combinado_familiar_id`),
  CONSTRAINT `combinado_familiar_equipos_computacion_ibfk_1` FOREIGN KEY (`combinado_familiar_id`) REFERENCES `combinado_familiar` (`combinado_familiar_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `combinado_familiar_film_foto`;
CREATE TABLE `combinado_familiar_film_foto` (
  `combinado_familiar_film_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `combinado_familiar_id` int(11) NOT NULL,
  `combinado_familiar_film_foto_cantidad` int(11) NOT NULL,
  `combinado_familiar_film_foto_producto` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_film_foto_marca` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_film_foto_serial` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_film_foto_valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`combinado_familiar_film_foto_id`),
  KEY `combinado_familiar_id` (`combinado_familiar_id`),
  CONSTRAINT `combinado_familiar_film_foto_ibfk_1` FOREIGN KEY (`combinado_familiar_id`) REFERENCES `combinado_familiar` (`combinado_familiar_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `combinado_familiar_obj_esp_prorrata`;
CREATE TABLE `combinado_familiar_obj_esp_prorrata` (
  `combinado_familiar_obj_esp_prorrata_id` int(11) NOT NULL AUTO_INCREMENT,
  `combinado_familiar_id` int(11) NOT NULL,
  `combinado_familiar_obj_esp_prorrata_cantidad` int(11) NOT NULL,
  `combinado_familiar_obj_esp_prorrata_producto` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_obj_esp_prorrata_marca` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_obj_esp_prorrata_serial` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_obj_esp_prorrata_valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`combinado_familiar_obj_esp_prorrata_id`),
  KEY `combinado_familiar_id` (`combinado_familiar_id`),
  CONSTRAINT `combinado_familiar_obj_esp_prorrata_ibfk_1` FOREIGN KEY (`combinado_familiar_id`) REFERENCES `combinado_familiar` (`combinado_familiar_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `combinado_familiar_tv_aud_vid`;
CREATE TABLE `combinado_familiar_tv_aud_vid` (
  `combinado_familiar_tv_aud_vid_id` int(11) NOT NULL AUTO_INCREMENT,
  `combinado_familiar_id` int(11) NOT NULL,
  `combinado_familiar_tv_aud_vid_cantidad` int(11) NOT NULL,
  `combinado_familiar_tv_aud_vid_producto` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_tv_aud_vid_marca` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `combinado_familiar_tv_aud_vid_serial` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `combinado_familiar_tv_aud_vid_valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`combinado_familiar_tv_aud_vid_id`),
  KEY `combinado_familiar_id` (`combinado_familiar_id`),
  CONSTRAINT `combinado_familiar_tv_aud_vid_ibfk_1` FOREIGN KEY (`combinado_familiar_id`) REFERENCES `combinado_familiar` (`combinado_familiar_id`) ON DELETE CASCADE
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
  `contacto_country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contacto_lote` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `cuota_fe_pago` datetime DEFAULT NULL,
  `cuota_recibo` int(10) unsigned DEFAULT NULL,
  `cuota_pfc` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`cuota_id`),
  UNIQUE KEY `poliza_id` (`poliza_id`,`cuota_nro`),
  UNIQUE KEY `poliza_id_2` (`poliza_id`,`cuota_periodo`),
  UNIQUE KEY `cuota_recibo` (`cuota_recibo`),
  CONSTRAINT `cuota_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `email_log`;
CREATE TABLE `email_log` (
  `email_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type_id` int(11) NOT NULL,
  `poliza_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  `email_log_to` text COLLATE utf8_unicode_ci NOT NULL,
  `email_log_timestamp` datetime NOT NULL,
  PRIMARY KEY (`email_log_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `poliza_id` (`poliza_id`),
  CONSTRAINT `email_log_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE NO ACTION,
  CONSTRAINT `email_log_ibfk_2` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `email_type`;
CREATE TABLE `email_type` (
  `email_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`email_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `endoso`;
CREATE TABLE `endoso` (
  `endoso_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `endoso_fecha_pedido` date NOT NULL,
  `endoso_tipo_id` int(11) NOT NULL,
  `endoso_cuerpo` text COLLATE utf8_unicode_ci,
  `endoso_numero` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endoso_fecha_compania` date DEFAULT NULL,
  `endoso_completo` tinyint(1) NOT NULL,
  PRIMARY KEY (`endoso_id`),
  KEY `poliza_id` (`poliza_id`),
  KEY `endoso_tipo_id` (`endoso_tipo_id`),
  CONSTRAINT `endoso_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE,
  CONSTRAINT `endoso_ibfk_2` FOREIGN KEY (`endoso_tipo_id`) REFERENCES `endoso_tipo` (`endoso_tipo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `endoso_foto`;
CREATE TABLE `endoso_foto` (
  `endoso_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `endoso_id` int(11) NOT NULL,
  `endoso_foto_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `endoso_foto_thumb_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `endoso_foto_width` int(11) NOT NULL,
  `endoso_foto_height` int(11) NOT NULL,
  PRIMARY KEY (`endoso_foto_id`),
  KEY `endoso_id` (`endoso_id`),
  CONSTRAINT `endoso_foto_ibfk_1` FOREIGN KEY (`endoso_id`) REFERENCES `endoso` (`endoso_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `endoso_tipo`;
CREATE TABLE `endoso_tipo` (
  `endoso_tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `endoso_tipo_grupo_id` int(11) NOT NULL,
  `endoso_tipo_nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`endoso_tipo_id`),
  KEY `endoso_tipo_grupo_id` (`endoso_tipo_grupo_id`),
  CONSTRAINT `endoso_tipo_ibfk_1` FOREIGN KEY (`endoso_tipo_grupo_id`) REFERENCES `endoso_tipo_grupo` (`endoso_tipo_grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `endoso_tipo_grupo`;
CREATE TABLE `endoso_tipo_grupo` (
  `endoso_tipo_grupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `endoso_tipo_grupo_nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`endoso_tipo_grupo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `equipo_rastreo`;
CREATE TABLE `equipo_rastreo` (
  `equipo_rastreo_id` int(11) NOT NULL AUTO_INCREMENT,
  `equipo_rastreo_nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`equipo_rastreo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `equipo_rastreo_pedido`;
CREATE TABLE `equipo_rastreo_pedido` (
  `equipo_rastreo_pedido_id` int(11) NOT NULL AUTO_INCREMENT,
  `equipo_rastreo_pedido_nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`equipo_rastreo_pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `incendio_edificio`;
CREATE TABLE `incendio_edificio` (
  `incendio_edificio_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `incendio_edificio_domicilio_calle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `incendio_edificio_domicilio_nro` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `incendio_edificio_domicilio_piso` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incendio_edificio_domicilio_dpto` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incendio_edificio_domicilio_localidad` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `incendio_edificio_domicilio_cp` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `incendio_edificio_country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incendio_edificio_lote` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `incendio_edificio_valor_tasado` decimal(10,2) DEFAULT NULL,
  `incendio_edificio_inc_edif` decimal(10,2) unsigned NOT NULL,
  `incendio_edificio_inc_edif_rep` tinyint(3) unsigned NOT NULL,
  `incendio_edificio_inc_mob` decimal(10,2) unsigned DEFAULT NULL,
  `incendio_edificio_rc_inc` decimal(10,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`incendio_edificio_id`),
  UNIQUE KEY `poliza_id` (`poliza_id`),
  CONSTRAINT `incendio_edificio_ibfk_1` FOREIGN KEY (`poliza_id`) REFERENCES `poliza` (`poliza_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `poliza`;
CREATE TABLE `poliza` (
  `poliza_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(10) unsigned NOT NULL,
  `cliente_id` int(10) unsigned NOT NULL,
  `subtipo_poliza_id` int(10) unsigned NOT NULL,
  `productor_seguro_id` int(10) unsigned NOT NULL,
  `poliza_estado_id` int(11) NOT NULL,
  `poliza_numero` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poliza_renueva_num` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `poliza_vigencia` enum('Mensual','Bimestral','Trimestral','Cuatrimestral','Semestral','Anual','Otra') COLLATE utf8_unicode_ci NOT NULL,
  `poliza_vigencia_dias` int(11) DEFAULT NULL,
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
  `poliza_observaciones` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`poliza_id`),
  KEY `subtipo_poliza_id` (`subtipo_poliza_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `productor_seguro_id` (`productor_seguro_id`),
  KEY `poliza_estado_id` (`poliza_estado_id`),
  CONSTRAINT `poliza_ibfk_1` FOREIGN KEY (`subtipo_poliza_id`) REFERENCES `subtipo_poliza` (`subtipo_poliza_id`),
  CONSTRAINT `poliza_ibfk_4` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`),
  CONSTRAINT `poliza_ibfk_5` FOREIGN KEY (`productor_seguro_id`) REFERENCES `productor_seguro` (`productor_seguro_id`),
  CONSTRAINT `poliza_ibfk_6` FOREIGN KEY (`poliza_estado_id`) REFERENCES `poliza_estado` (`poliza_estado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `poliza_estado`;
CREATE TABLE `poliza_estado` (
  `poliza_estado_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_estado_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`poliza_estado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `poliza_foto`;
CREATE TABLE `poliza_foto` (
  `poliza_foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `poliza_id` int(10) unsigned NOT NULL,
  `poliza_foto_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `poliza_foto_thumb_url` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `poliza_foto_width` int(11) NOT NULL,
  `poliza_foto_height` int(11) NOT NULL,
  PRIMARY KEY (`poliza_foto_id`),
  KEY `poliza_id` (`poliza_id`)
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
  `sucursal_id` int(11) NOT NULL,
  `productor_seguro_codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`productor_seguro_id`),
  UNIQUE KEY `code` (`productor_id`,`seguro_id`,`productor_seguro_codigo`) USING BTREE,
  KEY `seguro_id` (`seguro_id`),
  KEY `productor_id` (`productor_id`) USING BTREE,
  KEY `sucursal_id` (`sucursal_id`),
  CONSTRAINT `productor_seguro_ibfk_1` FOREIGN KEY (`productor_id`) REFERENCES `productor` (`productor_id`),
  CONSTRAINT `productor_seguro_ibfk_2` FOREIGN KEY (`seguro_id`) REFERENCES `seguro` (`seguro_id`),
  CONSTRAINT `productor_seguro_ibfk_3` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursal` (`sucursal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `seguro`;
CREATE TABLE `seguro` (
  `seguro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seguro_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seguro_email_siniestro` text COLLATE utf8_unicode_ci,
  `seguro_email_emision` text COLLATE utf8_unicode_ci,
  `seguro_email_emision_vida` text COLLATE utf8_unicode_ci,
  `seguro_email_endosos` text COLLATE utf8_unicode_ci,
  `seguro_email_rastreador` text COLLATE utf8_unicode_ci,
  `seguro_email_fotos` text COLLATE utf8_unicode_ci,
  `seguro_cuit` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seguro_direccion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seguro_localidad` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seguro_cp` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`seguro_id`),
  UNIQUE KEY `seguro_nombre` (`seguro_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `seguro_cobertura_tipo`;
CREATE TABLE `seguro_cobertura_tipo` (
  `seguro_cobertura_tipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `seguro_id` int(10) unsigned NOT NULL,
  `seguro_cobertura_tipo_nombre` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `seguro_cobertura_tipo_limite_rc_id` int(11) DEFAULT NULL,
  `seguro_cobertura_tipo_gruas` int(11) DEFAULT NULL,
  `seguro_cobertura_tipo_anios_de` int(11) DEFAULT NULL,
  `seguro_cobertura_tipo_anios_a` int(11) DEFAULT NULL,
  PRIMARY KEY (`seguro_cobertura_tipo_id`),
  KEY `seguro_id` (`seguro_id`),
  CONSTRAINT `seguro_cobertura_tipo_ibfk_1` FOREIGN KEY (`seguro_id`) REFERENCES `seguro` (`seguro_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `seguro_cobertura_tipo_limite_rc`;
CREATE TABLE `seguro_cobertura_tipo_limite_rc` (
  `seguro_cobertura_tipo_limite_rc_id` int(11) NOT NULL AUTO_INCREMENT,
  `seguro_cobertura_tipo_limite_rc_valor` int(11) NOT NULL,
  PRIMARY KEY (`seguro_cobertura_tipo_limite_rc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `subtipo_poliza`;
CREATE TABLE `subtipo_poliza` (
  `subtipo_poliza_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_poliza_id` int(10) unsigned NOT NULL,
  `subtipo_poliza_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtipo_poliza_tabla` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `subtipo_poliza_polizadet_auto` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`subtipo_poliza_id`),
  UNIQUE KEY `subtipo_poliza_tabla` (`subtipo_poliza_tabla`),
  KEY `tipo_poliza_id` (`tipo_poliza_id`),
  CONSTRAINT `subtipo_poliza_ibfk_1` FOREIGN KEY (`tipo_poliza_id`) REFERENCES `tipo_poliza` (`tipo_poliza_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE `sucursal` (
  `sucursal_id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sucursal_direccion` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sucursal_telefono` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sucursal_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`sucursal_id`)
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


DROP TABLE IF EXISTS `usuario_sucursal`;
CREATE TABLE `usuario_sucursal` (
  `usuario_sucursal_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) unsigned NOT NULL,
  `sucursal_id` int(11) NOT NULL,
  PRIMARY KEY (`usuario_sucursal_id`),
  UNIQUE KEY `sucursal_id_usuario_id` (`sucursal_id`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `usuario_sucursal_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `usuario_sucursal_ibfk_5` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursal` (`sucursal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2013-11-15 13:51:46