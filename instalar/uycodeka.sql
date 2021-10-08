-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 02-05-2017 a las 18:52:36
-- Versión del servidor: 10.0.23-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `uycodeka`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albalinea`
--

CREATE TABLE `albalinea` (
  `codalbaran` int(11) NULL DEFAULT '0',
  `numlinea` int(4) NULL,
  `codfamilia` int(3) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `codigo` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `cantidad` float NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `precio` float NULL DEFAULT '0',
  `importe` float NULL DEFAULT '0',
  `dcto` tinyint(4) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albalineap`
--

CREATE TABLE `albalineap` (
  `codalbaran` varchar(20) NULL DEFAULT '0',
  `codproveedor` int(5) NULL DEFAULT '0',
  `numlinea` int(4) NULL,
  `codfamilia` int(3) DEFAULT NULL,
  `codigo` varchar(15) DEFAULT NULL,
  `cantidad` float NULL DEFAULT '0',
  `precio` float NULL DEFAULT '0',
  `importe` float NULL DEFAULT '0',
  `dcto` tinyint(4) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albalineaptmp`
--

CREATE TABLE `albalineaptmp` (
  `codalbaran` int(11) NULL DEFAULT '0',
  `numlinea` int(4) NULL,
  `codfamilia` int(3) DEFAULT NULL,
  `codigo` varchar(15) DEFAULT NULL,
  `cantidad` float NULL DEFAULT '0',
  `precio` float NULL DEFAULT '0',
  `importe` float NULL DEFAULT '0',
  `dcto` tinyint(4) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albalineatmp`
--

CREATE TABLE `albalineatmp` (
  `codalbaran` int(11) NULL DEFAULT '0',
  `numlinea` int(4) NULL,
  `codfamilia` int(3) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `codigo` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `cantidad` float NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `precio` float NULL DEFAULT '0',
  `importe` float NULL DEFAULT '0',
  `dcto` tinyint(4) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albaranes`
--

CREATE TABLE `albaranes` (
  `codalbaran` int(11) NULL,
  `codfactura` int(11) NULL DEFAULT '0',
  `tipo` int(1) DEFAULT NULL,
  `fecha` date NULL DEFAULT '0000-00-00',
  `fechaentrega` date DEFAULT NULL,
  `lugar` varchar(300) DEFAULT NULL,
  `solicitado` varchar(150) DEFAULT NULL,
  `iva` tinyint(4) NULL DEFAULT '0',
  `codcliente` int(5) DEFAULT '0',
  `moneda` int(2) DEFAULT NULL,
  `estado` varchar(1) CHARACTER SET utf8 DEFAULT '1',
  `totalalbaran` float NULL,
  `descuento` int(2) NULL DEFAULT '0',
  `observacion` varchar(350) DEFAULT NULL,
  `codformapago` int(6) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albaranesp`
--

CREATE TABLE `albaranesp` (
  `codalbaran` varchar(20) NULL DEFAULT '0',
  `codproveedor` int(5) NULL DEFAULT '0',
  `codfactura` varchar(20) DEFAULT NULL,
  `fecha` date NULL DEFAULT '0000-00-00',
  `iva` tinyint(4) NULL DEFAULT '0',
  `estado` varchar(1) DEFAULT '1',
  `moneda` int(2) NULL,
  `totalalbaran` float NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albaranesptmp`
--

CREATE TABLE `albaranesptmp` (
  `codalbaran` int(11) NULL,
  `fecha` date NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Temporal de albaranes de proveedores para controlar acceso s';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albaranestmp`
--

CREATE TABLE `albaranestmp` (
  `codalbaran` int(11) NULL,
  `fecha` date NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Temporal de albaranes para controlar acceso simultaneo';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `codarticulo` int(10) NULL,
  `codfamilia` int(5) NULL,
  `plancuentac` varchar(13) NULL,
  `plancuentav` varchar(13) NULL,
  `referencia` varchar(20) NULL,
  `descripcion` text NULL,
  `impuesto` float NULL,
  `codproveedor1` int(5) NULL,
  `codproveedor2` int(5) NULL,
  `descripcion_corta` varchar(30) NULL,
  `codubicacion` int(3) NULL,
  `stock` int(10) NULL,
  `stock_minimo` int(8) NULL,
  `aviso_minimo` varchar(1) NULL DEFAULT '0',
  `datos_producto` varchar(200) NULL,
  `fecha_alta` date NULL DEFAULT '0000-00-00',
  `fecha_vencimiento` date  NULL,
  `codembalaje` int(3) NULL,
  `unidades_caja` int(8) NULL,
  `comision` int(2) DEFAULT NULL,
  `precio_ticket` varchar(1) NULL DEFAULT '0',
  `modificar_ticket` varchar(1) NULL DEFAULT '0',
  `observaciones` text NULL,
  `precio_compra` float(10,2) DEFAULT NULL,
  `precio_almacen` float(10,2) DEFAULT NULL,
  `precio_tienda` float(10,2) DEFAULT NULL,
  `precio_pvp` float(10,2) DEFAULT NULL,
  `precio_iva` float(10,2) DEFAULT NULL,
  `moneda` int(2) NULL,
  `codigobarras` varchar(55) NULL,
  `imagen` varchar(200) NULL,
  `sector` varchar(250) NULL,
  `pasillo` varchar(40) NULL,
  `modulo` varchar(50) NULL,
  `estante` varchar(50) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Articulos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artiviaja`
--

CREATE TABLE `artiviaja` (
  `codartiviaja` int(11) NULL,
  `tipo` int(1) NULL,
  `fecha` date NULL,
  `codcliente` int(5) NULL,
  `estado` varchar(1) NULL DEFAULT '0',
  `transportista` varchar(250) DEFAULT NULL,
  `vehiculo` varchar(250) DEFAULT NULL,
  `chofer` varchar(250) DEFAULT NULL,
  `destino` varchar(250) DEFAULT NULL,
  `observacion` varchar(350) NULL,
  `fechaenvio` date NULL DEFAULT '0000-00-00',
  `fecharentrega` date NULL DEFAULT '0000-00-00',
  `hora` varchar(5) DEFAULT '00:00',
  `emitido` int(1) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='articulos en transito';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artiviajalinea`
--

CREATE TABLE `artiviajalinea` (
  `codartiviaja` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de articulos en transito';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artiviajalineatmp`
--

CREATE TABLE `artiviajalineatmp` (
  `codartiviaja` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Temporal de linea de articulos en transito';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artiviajatmp`
--

CREATE TABLE `artiviajatmp` (
  `codartiviaja` int(11) NULL,
  `fecha` date NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='temporal de articulos en transito';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artpro`
--

CREATE TABLE `artpro` (
  `codarticulo` varchar(15) NULL,
  `codfamilia` int(3) NULL,
  `codproveedor` int(5) NULL,
  `precio` float NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autofactulinea`
--

CREATE TABLE `autofactulinea` (
  `codautofactura` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `codservice` int(6) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL,
  `moneda` int(2) NULL,
  `precio` float NULL,
  `importe` float NULL,
  `dcto` tinyint(4) NULL,
  `dctopp` tinyint(4) DEFAULT NULL,
  `comision` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de auto facturas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autofactulineatmp`
--

CREATE TABLE `autofactulineatmp` (
  `codautofactura` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `codservice` int(6) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL,
  `moneda` int(2) NULL,
  `precio` float NULL,
  `importe` float NULL,
  `dcto` tinyint(4) NULL,
  `dctopp` tinyint(4) DEFAULT NULL,
  `comision` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de auto facturas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autofacturas`
--

CREATE TABLE `autofacturas` (
  `codautofactura` int(11) NULL,
  `tipo` int(1) NULL,
  `fecha` date NULL,
  `iva` tinyint(4) NULL,
  `codcliente` int(5) NULL,
  `estado` varchar(1) NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `tipocambio` decimal(7,4) NULL,
  `descuento` int(2) NULL,
  `totalfactura` float NULL,
  `observacion` varchar(350) NULL,
  `codformapago` int(6) DEFAULT NULL,
  `emitida` int(1) DEFAULT '0',
  `enviada` int(1) DEFAULT NULL,
  `borrado` varchar(1) NULL DEFAULT '0',
  `semanafacturacion` int(1) DEFAULT NULL,
  `diafacturacion` int(1) DEFAULT NULL,
  `activa` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='facturas de ventas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `codcliente` int(5) NULL,
  `nombre` varchar(45) NULL,
  `apellido` varchar(50) NULL,
  `empresa` varchar(100) NULL,
  `nif` varchar(12) NULL,
  `tiponif` int(2) NULL,
  `plancuenta` varchar(13) NULL,
  `codpais` int(4) NULL,
  `direccion` varchar(50) NULL,
  `codprovincia` int(2) NULL DEFAULT '0',
  `localidad` varchar(35) NULL,
  `codformapago` int(2) NULL DEFAULT '0',
  `codentidad` int(2) NULL DEFAULT '0',
  `cuentabancaria` varchar(20) NULL,
  `agencia` varchar(35) DEFAULT NULL,
  `recepciondia` varchar(15) DEFAULT NULL,
  `recepcionhora` varchar(10) NULL,
  `recepcioncontacto` varchar(60) NULL,
  `pagodia` varchar(15) DEFAULT NULL,
  `pagohora` varchar(10) DEFAULT NULL,
  `pagocontacto` varchar(60) DEFAULT NULL,
  `codpostal` varchar(5) NULL,
  `telefono` varchar(14) NULL,
  `telefono2` varchar(14) DEFAULT NULL,
  `fax` varchar(14) DEFAULT NULL,
  `movil` varchar(14) NULL,
  `email` varchar(35) NULL,
  `email2` varchar(35) DEFAULT NULL,
  `web` varchar(45) NULL,
  `tipo` tinyint(2) NULL,
  `contrasenia` varchar(100) NULL,
  `sessionid` varchar(100) NULL,
  `secQ` tinyint(4) DEFAULT '0',
  `secA` varchar(50) DEFAULT NULL,
  `codusuarios` int(6) DEFAULT NULL,
  `service` int(2) NULL,
  `horas` int(3) DEFAULT NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobros`
--

CREATE TABLE `cobros` (
  `id` int(11) NULL,
  `codfactura` int(11) NULL,
  `codcliente` int(5) NULL,
  `importe` float NULL,
  `moneda` int(2) NULL,
  `codformapago` int(2) NULL,
  `numdocumento` int(10) NULL,
  `fechacobro` date NULL DEFAULT '0000-00-00',
  `observaciones` text NULL,
  `resguardo` int(1) DEFAULT NULL,
  `tipocambio` decimal(7,4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Cobros de facturas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `coddatos` int(1) NULL,
  `nombre` varchar(255) COLLATE latin1_spanish_ci NULL,
  `razonsocial` varchar(255) COLLATE latin1_spanish_ci NULL,
  `nif` varchar(13) COLLATE latin1_spanish_ci NULL,
  `direccion` varchar(255) COLLATE latin1_spanish_ci NULL,
  `provincia` int(6) DEFAULT NULL,
  `pais` int(6) DEFAULT NULL,
  `telefono1` varchar(255) COLLATE latin1_spanish_ci NULL,
  `telefono2` varchar(255) COLLATE latin1_spanish_ci NULL,
  `fax` varchar(255) COLLATE latin1_spanish_ci NULL,
  `web` varchar(255) COLLATE latin1_spanish_ci NULL,
  `mailv` varchar(255) COLLATE latin1_spanish_ci NULL,
  `maili` varchar(255) COLLATE latin1_spanish_ci NULL,
  `facebook` varchar(255) COLLATE latin1_spanish_ci NULL,
  `twitter` varchar(255) COLLATE latin1_spanish_ci NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NULL,
  `fecha` date NULL,
  `login` int(2) NULL,
  `cabezalmail` int(2) NULL,
  `piemail` int(2) NULL,
  `emailname` varchar(200) COLLATE latin1_spanish_ci NULL,
  `emailsend` varchar(200) COLLATE latin1_spanish_ci NULL,
  `emailpass` varchar(200) COLLATE latin1_spanish_ci NULL,
  `emailhost` varchar(200) COLLATE latin1_spanish_ci NULL,
  `emailssl` int(1) NULL,
  `emailpuerto` int(3) NULL,
  `emailbody` longtext COLLATE latin1_spanish_ci,
  `efemailname` varchar(200) COLLATE latin1_spanish_ci NULL,
  `efemailsend` char(200) COLLATE latin1_spanish_ci NULL,
  `efemailpass` char(200) COLLATE latin1_spanish_ci NULL,
  `efemailhost` char(200) COLLATE latin1_spanish_ci NULL,
  `efemailssl` int(1) NULL,
  `efemailaut` int(1) DEFAULT NULL,
  `efemailpuerto` int(3) NULL,
  `efemailtipo` int(1) DEFAULT NULL,
  `smsfactura` int(1) NULL,
  `smsservice` int(1) NULL,
  `papel` varchar(20) COLLATE latin1_spanish_ci NULL,
  `impresorareporte` varchar(50) COLLATE latin1_spanish_ci NULL,
  `servidorreporte` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `impresorafactura` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `servidorfactura` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `lugarmon` int(1) DEFAULT NULL,
  `logofactura` int(1) DEFAULT NULL,
  `modelo` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `reporte` int(1) NULL,
  `serveraux` varchar(250) COLLATE latin1_spanish_ci DEFAULT NULL,
  `bancos` longtext COLLATE latin1_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;


--
-- Estructura de tabla para la tabla `elementos`
--

CREATE TABLE `elementos` (
  `codelemento` int(6) NULL,
  `codpresupuesto` int(6) NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NULL,
  `texto` longtext COLLATE latin1_spanish_ci NULL,
  `fecha` date NULL,
  `orden` int(2) NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `embalajes`
--

CREATE TABLE `embalajes` (
  `codembalaje` int(3) NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Embalajes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidades`
--

CREATE TABLE `entidades` (
  `codentidad` int(2) NULL,
  `nombreentidad` varchar(50) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Entidades Bancarias';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `codequipo` int(5) NULL,
  `codcliente` int(5) NULL,
  `fecha` date NULL,
  `service` int(2) NULL,
  `numero` varchar(15) COLLATE latin1_spanish_ci NULL,
  `descripcion` varchar(100) COLLATE latin1_spanish_ci NULL,
  `detalles` longtext COLLATE latin1_spanish_ci NULL,
  `alias` varchar(50) COLLATE latin1_spanish_ci NULL,
  `borrado` int(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Equipos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factulinea`
--

CREATE TABLE `factulinea` (
  `codfactura` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `codservice` int(6) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL,
  `moneda` int(2) NULL,
  `precio` float NULL,
  `importe` float NULL,
  `dcto` tinyint(4) NULL,
  `dctopp` tinyint(4) DEFAULT NULL,
  `comision` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de facturas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factulineap`
--

CREATE TABLE `factulineap` (
  `codfactura` varchar(20) NULL DEFAULT '',
  `codproveedor` int(5) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `cantidad` float NULL,
  `precio` float NULL,
  `importe` float NULL,
  `dcto` tinyint(4) NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de facturas de proveedores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factulineaptmp`
--

CREATE TABLE `factulineaptmp` (
  `codfactura` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `cantidad` float NULL,
  `precio` float NULL,
  `importe` float NULL,
  `dcto` tinyint(4) NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de facturas de proveedores temporal';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factulineatmp`
--

CREATE TABLE `factulineatmp` (
  `codfactura` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `codservice` int(6) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL,
  `moneda` int(2) NULL,
  `precio` float NULL,
  `importe` float NULL,
  `dcto` tinyint(4) NULL,
  `dctopp` tinyint(4) DEFAULT NULL,
  `comision` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Temporal de linea de facturas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturanota`
--

CREATE TABLE `facturanota` (
  `facturanotaid` int(6) NULL,
  `codfactura` int(6) DEFAULT NULL,
  `codncredito` int(6) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `codfactura` int(11) NULL,
  `tipo` int(1) NULL,
  `fecha` date NULL,
  `iva` tinyint(4) NULL,
  `codcliente` int(5) NULL,
  `estado` varchar(1) NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `tipocambio` decimal(7,4) NULL,
  `descuento` int(2) NULL,
  `totalfactura` float NULL,
  `observacion` varchar(350) NULL,
  `fechavencimiento` varchar(50) NULL,
  `codformapago` int(6) DEFAULT NULL,
  `emitida` int(1) DEFAULT '0',
  `enviada` int(1) DEFAULT NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='facturas de ventas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturasp`
--

CREATE TABLE `facturasp` (
  `codfactura` varchar(20) NULL DEFAULT '',
  `codproveedor` int(5) NULL,
  `fecha` date NULL,
  `iva` tinyint(4) NULL,
  `estado` varchar(1) NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `tipo` int(2) NULL,
  `totalfactura` float NULL,
  `fechapago` date NULL DEFAULT '0000-00-00',
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='facturas de compras a proveedores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturasptmp`
--

CREATE TABLE `facturasptmp` (
  `codfactura` int(11) NULL,
  `fecha` date NULL,
  `moneda` int(2) NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='temporal de facturas de proveedores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturastmp`
--

CREATE TABLE `facturastmp` (
  `codfactura` int(11) NULL,
  `fecha` date NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='temporal de facturas a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias`
--

CREATE TABLE `familias` (
  `codfamilia` int(5) NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `imagen` varchar(200) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='familia de articulos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formapago`
--

CREATE TABLE `formapago` (
  `codformapago` int(2) NULL,
  `nombrefp` varchar(40) NULL,
  `dias` int(3) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Forma de pago';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foto`
--

CREATE TABLE `foto` (
  `oid` int(6) NULL,
  `fotoname` varchar(30) COLLATE latin1_spanish_ci NULL,
  `fototype` varchar(30) COLLATE latin1_spanish_ci NULL,
  `fotosize` int(11) NULL,
  `fotocontent` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `foto`
--

INSERT INTO `foto` (`oid`, `fotoname`, `fototype`, `fotosize`, `fotocontent`) VALUES
(1, 'logo.png', 'image/png', 37039, 0x89504e470d0a1a0a0000000d4948445200000150000000ad080200000069f1b0fd000000097048597300000ec400000ec401952b0e1b0000000774494d4507de010a010c1ae628c26a0000001d69545874436f6d6d656e7400000000004372656174656420776974682047494d50642e6507000020004944415478daecbd79982cd9551f78967b6f2c9959556fe9d7eff526b5ba5b086d084948e011020618b03096c1b28d80cf680cde06d9d818fc79c10bc68c3d300c18b0f1207fc81206316213c2b84180b52124d1686f754b6a75b77a5fde52af2a9788b8f79e73e68fa8aa97af2ab3fa3dd16a2d9de78b7e1d1519191919797ff79cf33bcb45338395ac64254f0ea1d52358c94a56805fc94a56b202fc4a56b29215e057b29295ac00bf9295ac6405f895ac64252bc0af64252b59017e252b59c90af02b59c94a56805fc94a56b202fc4a56b29215e057b29215e057b29295ac00ffa412353305330003c862aa0000fd9fba5b5e68662202002066290268068996fa0b80184002c80630ed620630000005cb06d920674daa0a0039ebea89afe489115c95c72e05bd2a2222e22ebe410d8800007a9c33f3deb9000046a68004b3a8e6e9de071f3dbb79fef4e9d31b478fa5b6bbe1694f39757c0325073205f11c00401588485589484c1957f3ef4a56807fc225ab38da0766936cec5cafeb9176909925abaab2040e64ac199a0c7ff4c10fffd1873e348edd7406274f9e6ca613cd691018d3f455dff1caab8e0d0b0000909c354b280a008829fa1070f5e857b202fc132f068000660626664644d0eb7953500044200433d855fe069ab291f377df77ee756ffccd967c0e210288baf1787c647d6d361dafd585370d6ca3aaf8deeff8d64160060003cb11d903614c123caf1efe4a5680ff2c00de54117447939b653143f0dc2b7632005141620048aa25524678fd6ffdeeedf73e286e90c19d3bbb559625605205350bde13111938479edd3198fce5977ff3b5571c71262190e4cc2ef4b3cc4a56b202fc13eebd1b10ee1af30006a4000620006d34646cba7cd7a7eebeffc1879d73c3b5d1739f72d3dbfee4968fdc7bdfb9ac934e529747451d27b3509919b07302d8345d51d6aa361a8d8efa9c66dbffeceffdef01c01b00aa2a18c2ca875fc90af09f4de0e794901c3067805984b7def291db6ebbedf4b9cd7a3810a4988488d07195f324c6b076e4a1339b140a42849c2a179244552dcb5255b341f0e56c3623a2a20a036fd7aed77ffbdbbe39cda6c3ba02c57e8e59c94a56807fc281ae3dfa04100d68dac97bdeffc13f7eef9f6e85636dd70161146ddae88ac0ce4f66d38d9a995c3b8d4c9e88538aae243349eaab22c41811d413c71847a351dbb60dfb2b86452dd36f7ac98b5ef4cc1b41b3aab20bab27bf9215e03f3b3e3c9a8266e33003f8b19fffcd49db02c0d9a6dd8bd25d08d75dbcbff7e7c133f776c44381b011fcb132fc9def7c392b38056600503000543003336007405121f414a219e205434055019476e20504003b27c06ec240cf0a200882002840d9ffd4d8c71d048000118c76e8833d1601211b1882dbbbe04a5680ff029698247804a04f3ef0e82ffcdaefb4ae3e7de6dca993274e6f8d0f47f225023e9906c2610835d9973def39713641c865f05ff5152f400007e010526cbdf7aa40cc3a9720850066bbf41e6a8ff3fe75bc1035c8fd99a00859fa73b2da148a9c33a2394f4488482212bb3c1c78022350420330224240034470abc1b002fc9342c3cfba282efcbb9ffef9095633014454e900dc2178be44b403008273884570ce51f09c728b443e30ce26571e3f76fd35a7bef285cf71000cc090012c83ef014c00a6006ad4eb79d2de01994f103203438992534a2a80880e1d2882997956554453cd396744742e0040462172ccec1010944c19804081eb25f6cf6a8cac00ff05245dd76151fccc6bffbf87a772b649e0bc4892ae2d8aea70545f2ae68dcc8c994308599311222211d5ccde21493c312cffea377fe34600c82d33027a33eb35f9ae0ddf1bedd65f770f812226228caee9726b42819431a50446c17bee9a1ef0cc4c44ecd0cc62971d86aca68062c8808eb160624272b62fd7f08299b19215e0bf9034fccd6f7befcdef7e9f96eb5b4d47448e910c8c701f9ef761fb12318fbc93878fc0de7b333451441444523d75c551ea264f3b75fc2f7ccd570c18c0d25e860f2000d04e9ebf9903b7877655c822226266b9237288018d4d2ce79c19c9392799fa994544528a88e89c33902a67315474860e90cd4c25816851522f8e69e7166c05f8cf6f59057e17480bf0b6f7bc6f74f4e4d9ad49198a411150cd715886f67d04de41993f0d1101d47bf6de032a133830075620124028cb871e3ed364b8f7a1d31fbafd938200e8a503c900c6004e550d811090faff0100a4ac5d8c6d8a4945c0a04c5c6690699e6cbad9f808d3c80cb6261a671a67902369f608818c215a6a3b86c4aad41a4e0027c8330e82b57539753945c9294b16b35d1670259fbfb2626516c8efbef3fdbcb6717a3a2b470344998cc783e146565b82defd987f4c0d6fc9c4b457b029a5c0819d0380c2518c4da84a619f1dbded4f3ef025cfb95105b8740490010080c971efcf9b299a9949b628594c0dcc9011512d422606f261405cb50e67065b54c7b679f49133a74f9f16d18d8df593a74ed47599921e3d316400a780b983dc21289223f689cd10c054d548d1133333d10af22b93fe0b4b7eec97df7cdf99ed8950d6c41a07bec802493db32cc4f9e59af4c19c5806425716e3f178301820408c91488baaeeda8488a5c3131bc38d926b4f8f6e4f4073c97ceaf8f1a75d73d575274e0cab00009dc4acaa0a0a86884608460ae684111190361bb9f5ae07fee8b63b6e7ff0f4f94e02d644c4e45455448811d1626c8f80de70cd89177ed175cfbafec4a9a343ef308b66050e0666aa8a6a00d0fb050ec9bb9561b802fce7a36802f4318bf78ca020004466f0fdfff957ba2ea6ac464c446606a00ca808cbbcf7c507a91011e738e71c42685374ce99098832733f5ff4e5b100e0bd4f6a066066845838aebcf30468824521b131b3500da7adac797ac933af7bfed3aedcd672ade076b2ed7cd502bb4018db12d37639bae5d6477fff4f6efdf87da75bf04551b009e44ed82f26299b76ede8b149d349ee6accffdb973ffb2b9e71dd4dd78ceae999b6380a00b99d55de45237301732a4ac7886ecfa5efeb88567a7f05f8cf7dc48b012065c96cea9801f99efb1ff9a937bf7521e0979176cb30af297befbdf74dec98d9cc420822e23c2262d7752925efbd734e55cd8cc1983d30a19a9979c2e0c0119fdd9a14d5908aa21d8faf58af9e7fd3535efc9c67158c1abb362bb9b2f2a8a9994581b5e1473fb5f51f5ffbc664d45165ae34f60486909d59c2c5d578a5e3f1b4e9c48e1e3f9a66634eb36325ddf4d46bbee92fbef4eab83d2c085d717692ebba0addf92d2d4735335260265cd1782bc07ffe8858067449d51331645151f577de73dffffb7bef3e0878877438e00fbe5430a59c01c0cc9c7349858810b9699abaaebdf7fd4b7d48dc7b4f6c39e79412187aef7d709625c7ae282af175dbc535d6679eaaffc24bbf22c66caef4da1a7ae7c3743ca906f57de7db9ff8c5df3a93caa8c8ec911d00e49c34f7b9bd986149f96d6cd907632786598554c0840c3ce9b36f7acafff10dcf5cab6a234249d99747b0db8aca48819d63a69575bf02fce70de04590c9000934754d280a03f7be5b6fffa5777c28c6149300bb4b07fcc1e3668888cc0c887bc066e6a2a8638c314666f6de8b88aa7aef9bd886109c73d617d0edd2046c967326695ff5975f766a402564334ce8117154f064329961fd7b7ff289fff19e5ba7c631250b6ba0d934138067dc0bddf7d75c3008407be7a2ed3af201d0395fa4249427014c7df94f5ff1e2675fb33e85622d6fa5ace62a422482c08e997985f915e03f3fc420a61c8203539004eca751efbeffe15f78cbbb16025ef1521df81d939e3c229a19192022332280aa2a44e75cafde77af0fcc5c660400b59e166744342011c1c1c8b75bdff6b297de70e546d32611299c03e93a23f63e92fbd73ff586b3307a78dc956570d009148cc828086a66a2908cd4a058864c72923ae748720c21b45d625f298089980f1bbabd29e58b6fbaea1fbeec8b8b6ac8d225a53e6f9f0998d9d38abaffbc91277b582e38a7d98808c8a7a8be70b34e2fc4cf2fc4db2e1c5808f2851341304bb165c0ba2aba668aa6274e9c78fa8d375ecfddb5d75e7be448dd75d0b62d11f51c5e4324a2aaeabd2382cdede9dd9fbaf79efbefbbe3dcf839d79fbcfee85066b3ac08541a239a38577cf24cf77fbdf6d7a2db184f67c3827de19a685e3b30305351352424c7cc04a896175bf43106e79dc39c3a55a9023bb626a62eac1db1f1232d0f827df4be73dff3b36ff9b97ff02d3ea7e0cb7e2213054301000f2b3dbfd2f09f0f267d5f44020693f1b85a1bb500ffe50dbf73dff66c9f8647b49ea5bf2c0dbf666d8cf1ca2b8e7fe5577cf975571f238038ebca32b4dd565dd766d6b62d2286107a83bfb602d10015d40cc1904521a9b8419d67b17290db865d4965716ed28dd68a9f7cc31fde76cfe9b1d5e64ad4e851c6b369510f6897384044744c44aa9073a6250e7760d7a68888884644a9ed98c8392792263038565ad7b6540c9aa6d9f0f0733ff0ad95240010b0de78718c81dd2a56b702fce7be450f0090b332011245801fffcfbfd851d118c69852d6cb32e90f62fed5dffaf555111c41e128b7d340889a1c71e2b5de6f6766e738671111e75c42630232134d6666c8c80c88218e85caa41c083de4ac96abea67feeb6fbee34c1d2c7b8b513482e350406e0b880d14bdcb2022264a289e11cd92f112334fd45091a4efd747006aa0c60e39c748057091daf19140d2cdac3efafa577f9da2c14ecdae398292bd77bc2aa55d01fe731df0a802a040dc01fdf86b7e7553201aa5d8ee33d4fb1da7a008428a8ec830b802a3e52e17254649e8b94969adae5ff4c537bdf0fa6bae1eb88eac4bb118ac458024eac94fc793e170e825aa6a5209659152426015f1de93cdccc0cc00999915484444a4a6d8d15a82102c95985a5fffc82fbdfd7da7a148db3bec20c81cc9679fe98c695faffdd2dffb1a9c6e02600a1b2ec5368475d8f27e0400607be53d66b8282de90b47140024c7be61b98831f7bd09051015cc0c09e7baa57d0ed8404f6a1f1efbff1405e8e6b7df328951d1a514f729ed0b61b6ba6cdbb6e65245b34a9bdb6a5043815341f275c5f495cfb9ee25cfbcb6e2dc0a9e0f03c8530a0351d76c4d06a573dcac8dd07473064360c7de4735f20518b147119dd03a227802268811728acc5cd53ee7d6625b0755709b5affc21bffe75d0f6d4a8b58863d8a618ffc7b02246d3ef2a65beef996e71ce1b026d3491b8643994dc0ad335c94634c887325765f78129379cfb4dbaa88186127ff080180800d773c47ec9b0cac48bbcfb20f9f233bd71a4483f77ce856ac37bac924e51442097365307bf8df9a4d02b2242d5cc12c1da4699c72e1dae45f70edc637bdf0d9c7cad046a1d146306d66e70a3aea1962bb75e258319d8e0dab69aa88874ab92c9c00dc75d73dd3598bc845514cc7937a7434b6b3d2f1c92b8e9d383a706590ae4dcdec8c5b3f3a28f3746b2bd97bef3dffaebb3633f9b57267a10c0040a4b990db671c6205e96b6f7eefb3af7bd98dc78d9951ad9b9da7e357a5143d3b24dc29ddfd4237f1d9b30248963ea889730d8272142025c700b0c313ad4cfacf01c900ae0178c39bfff0a3f73ebad9c4aa2a4093a15b68d2fb22780ee3eda9272686e041f26c6d7df0aaaf7efef1e3d78e9b5415a160988eb7b90c091188a06d87841e1cd6e57beedcfce33b1fbafdc1d322c5438f3c3299cdd0bbe1702d264164cf2eb70d1130014ab214ebe04e9d3c71f2e489e3a5bee8d9d7536c6ef9d83d6f7ecf1d53370a842e6dabab7683077d270cede986cfb4ed58616ea9a638fec57ff42d81bd4b53ab07a737b7d6cba20a85e3be5e77a7f5ef177cbf8c7e05b1bdefa82244fe428702534202809453707e05f8cfae0b968cfcf9083ffef3af6bdd701cb3a4667d50c56c0b01dfb66d28aa7234387ffedc91614dd3f14b5ff0a55ff3e5cf2e27e723ba8ec22c45ef3020e62e57d5a04863196ebcf3539bbff5dedb3ff0897beb62d89e3b7b6a7d7db39d7aef0dd0086312e742cfa82bb93edca52a007d972904807527e3adf32e780c7567cec8498e750859754781d0050acdfa66759fd9c796d187e4aabffd92ebbeeaa6932736ca07da70ac90aeeb8aa2e8116f4f827e781a3b60a27e491133ec7f338394cd39b79b7c65b66be97f2e3c8e27b5496f80aa500520a2d1683039b7ed5dd1b591fc45a5ef7b805f5bdb5090e9d6d9e3eb7590ee957fe55b6eb862383b3ddd1c6c9426dc9d3bb9313cdba6732d9fdc583bf7d0d67bb69bd7bcf6b5108ef3f004a44163ba7ed5d5f79f7be848b5d1c6ae4bb2b6b6e69d9a68d7b62114a61dbb6008660848e8d80cb3c8388aad9f6ab904cd94664edbba1ab46260d22b76c227941a0b653d699acaf11bde75c79f7fdef5e7a3dba8603699117b111334c7f864e87e499ec1108014cc9008204b4603f25e012427474c4460a62ac4f8b9c0da3db9493b7408d045ab3c3ff0f0c3aea80580d9db22b423624c02b93912fc0d47d7feea377dad7479ebfc646d6310bbce0f8b365ef1f0783a2cca19d92fbff563bff9b60f74615d0737109b5acbae29423539b7b5168e9c6b6c30d8a80634e9baae4b6528cac13a224a34957e892b3314ccc6c44cc83e18fbb66bbc03930e1dc5d80a0477a18fdd4eadfe1363af75b1a9ebba9d6d42b571f39fdef9bf3cf7e9c7a9155f9899a88a506fd5f737f304440d3e7b1a83c57acf1077cc2a760490540a62761efb2700a89a99fde7c22d3fd933ed44b40c64928eac8f369b5c5555ec3a3ed0d662c73c63aa30bcf08b6ef8ba2ffb129a7400c96ab729b391efbaf39bb51fc530f895b7ddf6e6777da82b87b362586a74ccaaa86225d5966c509531b647caa29b6da1e3d2747de86333612530c95c9b011310119a4a8a88121c25a3343bbf51d75dd784aa52852e89f704f6c41175170d1a249358d4ebdc9c7be3bbe15b5ef28c786e4283a3026622d934ec36d336b32f644d8fd8257de4f4f90f7ce4d6db3ef189b68da3d1e8daab4e952ebef8452f3a79f498aa3231a07eeed4183dc9e3f06a600afca33ffb1a1b9d7a74dc2268209b39e79256ec01b433eb444745c502a09b7fff3b5eb14eb960a7aa883203ee8ac1a81bc762f4d107e38ffcdc7f83c115e4436c6665e0bc1b1b5f345416f4cc58f65b2c338ffb509c9911439f45d7fbf0bd3b7919cfe1d0cf5df46a36575337235f259157bde4fa973fef3a29d7729a79ef5317ebbaf4c408d0b6b3b22c2127600611700c406240480ac0bbe1e9ddee5980d82f0ab07707174d668fdbd46119004432bb2022bdeeeda30afdd2dd30b758b86a5672088aaa4490b3b20bc9a08df0ba37dd3c9bcd9a3602103aee0b1f72ce48a2591ce84d4fbdfa2f7ee3d78d0239c8600250982a32e5acecc8fa9a68e615e09f20e9522cbccb80f79cd9fe855fbb39fb7a7b3a0b4c5478e8b2a91a00965c55559c4c0bc47ffc1daf989a15850d603669a20d8f150a7eebd1331b27feef9ffec5fbc739faf50821a5545741bad676ebcf97b5c499b37b2f32c82fd101de7b0b92f5b11f3353cd8fbb093d3f4810d1da59b97ea5365b02967df9f441f7af5ff532c781acf5dec7b675ce05b7e3be026206820bb4fd0ef00d80976178b7c67e8ff97b7c8bee45849973ce00e09c3380d9acadebd27649d079626e67864bc979afaa46ae5578c36ffef7d367361b2890091163d6b68d49b2635f14053b1211b64c3992745ffe822ff9da97bc28c7aef685e84e65e485b933e73e2d7205f82740c30382364dc3d5e0675fffab672325705d1696c8ecbc2f52ce46d2b5db03b27ff8bddfedce8d7d557666161c817a836e96ba4caffecfbfd10950a8c5281b148e533793d885727888ae9ed79fcb5a655d22148961571de9212cfdb2eb1fa2e117be34621a77c8da32e3988a20db3ffd7d7fed6adf4ec41111a839e772ce840600de7bed3317d59c27503155e74852a46a1d115d1f583cf0c3c0c554ffe318dedb433802c49843d88fb7bd42e65dd4f70b7e80329d99b4bff41bbf2dbedc9a7545353a7ffefcd9cdcdb66dd51000b2a9880180a67cf2c4156b83d259a49caa00ffe87bbf2748f6ec00a00f67f4ff3ec1e18c2737e00d4c849c19b8ed0c3ff95f5eafc5da56972147e73cbb72369b6c8c0635a757bcec6baf18d5dea95768133511d70b07d0fef21fdef2df3ffae866742184763a2144ef1d13a86a511429ca21ca79c111d485af223c86d587643d387b0d4fe43e8d59e3909bdc77029b0a144e22514ed54697d3d73f6df8bd5ff7c5c57023c6a8aace11012262ce39e74ca11211004072aa8aa04424a9235f2122131181432234dcb1e93fb3bf7b4ae23d0340df1d1c10534e44204afdf1bc6b8c18802a04045133c68737a7bffae6ff115d796e1ac7b3767b7b3b25c9a688c8e40d7722a3441c63e7d9151ec9e4a9575dd94cb63cd3bf7af5df885d1c56016ca739a22ad013ebde3fd9018f00801abb868bc1b9567ff6f56fa0722d0cebf3dbd3c9aca97de173f7ed2ffff34fbd628dd2ac95c6dcd0a90da46ba8fcc937fdf12df76f4d9380505d150ed4248948120b65d5a54c6c8780fca0865f86b7a53e3cee027b77cda9de87477c7cdcc2651a5e8853d7ad5715423e338de5e0c8d3fce43fbdfa9bb79aae578c7d69505fa897522a9c4faa8aa4c006c00c488006163bb2fe8b98c3be272ef4596b9fe15f5e0120ce1a227245050086a000192066387dfaccd9cdf37dab020562e6a6ddfad2e73ee72d6f7bf7ddf7dedfaaabd78fdffe89bb0570369b3133330b584a39c60800ccdcf7fc37333461d2a36b6ba36159053fccb37ff8bddfcd0090a3734e4588bd2a3c91907f52035eb364b5101c809a66a5b025f09ad7ff5a4be4caa22ceb40fce2677ef18da7ae18386b9b891b8eac9978eaac3ef6af5ef77bb7dcd338ae4794052201765d0366eb1b47c7939910b95080a6cbd2f002762916f83ecd6f66807d8e1d1c9e69f7699b8efb7cf889d9f1dab54d1a4fe368380074c33cf98fdfff2d85c69c33b3ef1d8c59d78610cac263b30dc4c02e0902a189c676e63d1b95bb2bea40afde1d1222fad003ffe2bbdde300feecbfbb74440c40fd625d82100d3e7cdbc73e78eb1d629ab3aa2ab9606659c5143274553568da980580ca87cf9c9b4cbb9ca4accbb66d7771eef63854ef707b32aeaa2aa5e49924c7e0f89a6baee27672f2d8daf77ce75fad093447760e14b39a7b022b8b9fec4d2c0d281b3830c4dc4e667eb83e5378e0a1736f7de73bd68e6c3cfd861b6ebafa9a81c3cdf156b55677ad1b70db85ea877ff97fbeeb13a78f8f46dc750801398a48599649252725c7de174dd779c6c724ed602e8a2e971b5ddbad4b3390394be1714bad5da6e13b2a46ed690da38687854d35b666f482ebaff9a1bffcdcde06ee52acebba8d5115c45490832f152cb65d59b80049bb66b0564f22ef18253a57aa04e0d98888912e78d17d72fee30478916486480e085a85b7bde7fd777cea3e2a0a314a29f594bb28a8421f542c3d9edfdc0e652506f7dc7b3ffbd0c4ce714829ed11aeaa9a73def15c4407c3e1f6f676360bc1cf26db1b6b23227adad557365b67bff315dffcecebaf72a0922373f904e7df3dc95b5ca5041e11c880b40302006f48282008e7a6d3aaf0984455b1f00dc8d178e6d1fadaeffb899b1f3dd71c1d153177ad245ff270b831994e45769495634c2979c279d37a19e6e74d7ac5253de79698e87b26bd815c000cdae586e50e3743168c10372ab7ee86faf896dfe0f6e151b019d42670e310bef405cf5f5f3ff2e15b6f9dcca621943e84a669002084a09220b6d75d75ecba2bd6ae1895373df5a44bb1bfd31ef07bc139b2d89bca9edd8e570f8f2be077e790f7befff65befb87b9a05aa3a1a8cb7266ddb0221224d67edac6b111911d75d88399177d3b633c29473362502c49d7e84a872c15421820429a550d65d8a66e63c754debbd7bdeb39e1eb737431efff3bfffb71c2880e624c4c5caa47f823d79dbaf6fc13252ca66dd76a2d072e525afe7b3e7aa2b7ff8677fa5593b79f7235bd2e6b550a8a526b78ecb85fe39cc01f870257fb8c9fd781dbf5cd2ee728561c104616646b8f0b3aebef2c49fbb61ed952ff9628e5b51abc8c114c85a051685dea51f7a02939c81fce280f5612c775f9bae606408c8fd9194737002f02b6f7ecb569ba331f97a737bbc796e2beef22017ddfc9cec3bb8b7df972ded3b78f0fce3c3f2f8fa2048fb0ffee6776d549ef7ca0a092567f6047dfccf39989be21f5f1a7f05f8c50345104e9f3dbf36280cb9c9e09c0b9adef4debb3e7e7af303773fbcd5aa47766a62f9caabaf7af4e1d3b028e476b980bfac838f23e0ff2c2efde180df01002d66fb8bb84d838df3db5bdff4e2677dd7573f77983781c3cc552c11291891e54c2a83ba04809824785e3c412ff9beaa6066cc68006dd75645d91fbf6fbb79fbbbdefda9071e3e79edf50f9f1b9f3eb3a9805dd701b9392a6431b6171e9cc7fc216ff494af3d7565a1dda98dc1dffeae6f3301e61d8b7e2731518cd0084945b244e78b5d325f1f2f2e7305f845269f42d334148a28b91b9f3f71fcd8668b0f8ef19fffd46b8e5e73dd271f3a5dd543cb3228c2b48b5d9662aed8661e72087a3845ff9900f667b16aa5a7dc2f1df0a80665d5242d414669eb152ffd92af7be133024cc12866c90a753dcc39963ec4ae190c06fb2ef298dfd4002467c70e34033931e844274df3a677de3299ceaab5230f9fd97ce4ec6616ebd70241a385caf910e4c36e55728ff90b5f79d175aadaafd5e591619166db5eb3f75ec4665dfbaca7dd90737ef6339ef1a2173c93012cab670250b5f9044adbdbffb3fcc44ff2b09ce1c5f527fdfe2ca9c40985eafcb4bb6a63d86c9d3d431bdfff936f3879e333eebaf7be59974783ba9b6d9b28b8027c819216420effcc68fff40ed2929f54112e6b4259769d65e4e2def9fb06d5fce75efc52b06eb37096fdda762ad70bf78c8df84f5ef5f52145ef99111f3d73eef815c7bb360507c1f13c19794838734f12640f046a00a8592cf80ee0756f7cd31887860ccc777eea1e1f4a44eebace7b2f293fa6965ea8ed9799f4fb2e952506a6a75e7b755df0747bab280a23ee6286d496c15b4acef2377cf5577dc50b9fe501724c2ef88323760ff92bc03f3e8037b3ad2e550c6d96683e80488e1f78b0fdf15f7f7723514447f5a06b268547e75c9b0cd0e11c677631a9b614e79f210ddf1fb95cc01faeb12f63e2505b68f92f3b7f84d2887486a11cc536598a839207a5fb67dff695c786a5b3ae2e8ba6cd2232288267212e2ed57b0700800caa3106760008c41dc27ffbaddf9d885275e55d77ddd5a5ccbe00809e6c371004de07d4834afba0f69e47fb3e9dbfef8d0c0826c341e510466bc3f3db634056b0c2112947320400002000494441542316de91446fa944fda11ffc7b0cc0003dedcfbb25f77f760b6e0578dc377472ce1df26c7b8bd917f5a0cdfae0b9d98ffec2afdf35f5478eac91618a313847909bd88852550d4cf3324d8b9783f9cf96497f8900bef0dc96b0e5f3e75fcab88a6daa368ecc62a7cd6cc8ea2d87d19187b62637d6ddbff8fe6fc3edd9d09321c46ca3aa986d6f6e6c1cbd44ef7de7fa9602b2a922b010bef7d63b3f7cd7bd33b147379bc964c2e49df39bdb5b9e6830184c6763647fb8263fc4d49ff7e1e7f7e74f4305228c5d33ac07d3d9d88088bdaa76715a55956679d6336e6249dde47cd0f82fffc9f795177fcdbd7a9e15e01f37c0ab6a8c312aa62c7555c618675cfec42fdf7cfbc38d15d56c72bef2034d0200c9623dac822b265b933e1ff31241f86960fe334dda2d03fca76dd25f22e05ba8d91a4f49b394c570366b536e8eaed5d308d7d4f9475ffd970a911823fb92cc343565288868cfa07d4cc0f7b51222a6c4d36c6ffcedb78c8d31d477ddf7209881515faca6aa6ad900407119d9beccd45fe8c3ef7be38583889eb9691a9334180c6297a36400ac0a777ebc1d42986e8f6f78eab5eb95b738bbeeaa2b5ffd6d2f2f8a62ef531e17d26eb578c0fe2940550313b2179140f689bbefbfe391e9b6526c27656090ae2a4a002aab41dba5ededed327800ed37449bdfdfd73f63e1fe1e440f4ede871f5cf8f6c3e5b2b5c1655ee7317d96fd26bd4d4b4868661c9a0c588c7cbd36c938a1d1f90edefc963fce3913a0887439f54dbb77fbf65deacf0940866008eff9d3f719bbe1e8c827efbc07183218159c24036146892a70f9e9c8cbd623592662368dad0bbe1aad8da78d8011912fc26c36abab2120af1f3976ef030f9ede9af87af8a9871e397dfa749fc5303f81f646fe0af09f9679d3175edb4ecb3151e8043ac5891232a92a437ad387cec68423c42179164fe8526e1d2b4a2e900be7132822233291eb772e6c066840800438bf7f105487034c110e6e7d0067e1b6f07c4500a6851b33d3aef0ae101180aa662250cdcca89afb231e61e1a6a846a6a8fd0ef46e2803d1850dd1f6b64ca4e401834362486c3307ca806bdc48caafffe0f4eecd30f22db6915d81e05ad5a8a63bc93a80d0e7e4e8de6626663237e7468044440a70fad1736078f6ecd932784466604dba365c6bdb8ed03b5f24454300c283dbdec359385fef254a1e32a1f7073d62410e0d724c21849d5920250e5e4ccd4001908beded69d32433ff1fdff05bbeaaac135020e60460060c0c069232585f36ac31469beb1a70f884b8d2f017e983fec773c4901369bafb4cbce3cebbcb7ac0d209d24234ee56a6e241b5fc9823e060f7fbc3f5ffe3a5baf79be8fd8a76aaaa2abbd27b8cbdc5dbd7c3f4d41111f5c3ebe07650c52d08c51d68fe7dd022b076b6e9aaa3d6fd3f6f7ce32caed11a0702f21732ff7652f2cc74c78ea67eebe7d9bd3fa533306f80e7c7dd2c2b54f5d9e94ccb2aa5545545eae26c362d4360104b91412ee5f11eb2dcd0216f5ff895f7de5e384f68cc8488ceb998d2b9f3db83d15a67f89afff6eb5a7004f8f87d0fbce58fdefdfe8f7f32136400f0aec92a066a14424000ec9729ddb5ff97ad14ec9ef4209fcb6cdd1d3e2a5d401bd6c59b6efe60124535ce33a9d750bb3dfefd924c599c7326e7cf59fa46dd3dfda297dc3c7276f5c94e43d4cb41fbb253092f74e0d875362f0cd39c3333c7184308fdfeb2c1c417f5aae9e921b4038503f3df77ef95f9665855e55aad0dce4cbbf0da5b3ef99d5f7632b161ee880b5515100076dc2f8a8506a00607b51a22b0dbc9b4b9ed637780f36d96498cca5638d7348d2f42559493adeda2f4c7d6d7ce6c9e41ef1ff3b92d247a0f89fe5c0ab712dbc6008150341545216093b6451ffc68edfd1fffe48ffecc6b3637cf6562705e53c6940221987efdd77ecd4b5ff2e505424ee21da964620f87f5295a01fee2df4655a52742d588c4b07ae7c71eae87ebb3d974ddd9d8d8f78f122f959c3b184779acfd4be2f60e36dbbb2c9f73e1f1b98639ba67a3ea5c8bae5ebdef0ca6652cbded5fb167df13b8c4192aa7340c881cf28c6efee05ddffd554fd7bc2d10cc40c1920a220aeef489138098040e94f401009b2239639c6c6d05c298d2ba672a8b24962cf5046d515739e7735bdb83c128263b7811f8b4caf3f7f7083a34a246cca5f74974960400d8bb661aefbae7fe415d5c71e2aa07ce9d1e0c360c4814a275c3b5754ddd7436f9ed3f78fb5bdef647373de5eabff55daf0400e660600747c88aa59f6771d574b7dac960d6a594f38eb7a6f18173d3effdc50f796b08f4a8eb1ec98382f2fec0dbe165704b8eef6b69f8d85cfdc573f6a7877638b4e3cd1ee045a48765dfa0cacc9c73bd3d2f22fd2c20cb59fdf98e5d17407ec9d4fd4ef5916ab25c42352958b6d3175d03ffeee5cf6f465790243303b5de8dd2dd8613baa458286a2c7d207446d4251cc7f4d6b7bfebe377dedd16b5880dd6d69b59276644d4b6ed7038886db7908ddf9b0a0f0fd7cd87e86051fa0d2c4ace3733c85d51144d17c5702f6a109bf64b9ffb9cf1e6d9663a9ecc664d9b6206d134aaabe1a07cfad39ffec803f7e7763a08ee4815fee90fbcda23e025c4ed574d2c7758b494b54d59548d10911cd91bdffe915ffed37305764e3befb835df7b4f17dcb6c7ac7bbd04c05f8aed670b0f2ecfb8fa348a67e6cd9c0b6a1fcdcc420829a51042d7757d2b385b5e6fbf378e2f52321727a85fc20d516db3ad54ba60ced5d66dff87eff95ae050844cbd05bf4b3fe9c539a7fb9e803a2140cbe68853121f2a4388193e717af3ad6f7fc7f97123c8c041899b2eaa6a205a188a43ba1069bf08b78ab02426bf18f0736d0bf6ae6666d246729c732e429554524a5555755de7b365699da3a66954a92a0702d2c5a973dc34cd89e3c7bb6676f2d846451820fdc8bffcc1f20035733003ffc9dd0003a4ef21a1a6294a52eb01af0084ee875ef7871f7f747a6454c964b3f16bb5d32c7326133c7652cd3e0b7699669efbf3318a6de658ab4fa713dea747eff52366b7615effd98b01cf44fbe8a2c3fd4933597887adc048535b176e2bc661085329dde64fbefadb01da1d8ab44f7adfc920e8f168072fa58c39a6615948ea0a5f6e8fc7e560386b1a5f961cf863773ff2d677ffc943e7b65b4157568a845997017e5f2ecdcecea2b8fdbc15308ffc3dc01fd4f06cdc9f4c0c5d4e29a5decc94590a258fa7dbc37a2449db69ab94d993a4cccc7d35d1f5d75e0bb92bd902e1bffbc1bf1b42d89bb8171af62b961ef641c8cc98fcf9c9f45367c6c7d6ea66362b1d5931821c6d314f8f7b319bbd3ff7edecdb5fe88d2f8bb12f638097b96a9f1e993f3f04f7b9dc7d47c794d205af7ec9361fbb9abfecb21b58768788d80c86553b9b16b82632e53c7627b7a6937de4bfaaeef0acfdb2d4bb8196bd7dcce88c248a29c6dc71e026b7e03174631d8f6fb8fad8dff8f66f7ed9d77f7555105892d41c4eb3ef23e497a52dcc7faf7d23e1e057ee8f24d56c868ebb3639a4baae7bb89665d9a558d45593a382394f755d3353d85da3d287faf63beedc1ccf32f1e664f6f0c30fcf66b3c7301e9fdc0d30fa8a3633c049d3f4ebb43bf6d06efec627d26fbdebc3b99d16d57a379b7ace520c38e7c58f92e95294ea219cd92ec5250b4f5e98f186888ab4d47659e84a2ca79f960c035a78c2f29c7c5d78b585f73fd7e7e62081cff3766f9f1d40443ff33dffabd78ed2d8312afa688cbe5400473c69e283e7361f3e7bbe6de320944747f5b0ac9e71ed280b764a7d9f6c34034d2839b99a1066d606c8c7d4cf147ffee63fb8e7cc36600d81b7275ba57735ba9c5223c90f2a6df36366dd1d52637321e5768f14982708d414683efd1e4d76777602a560c6cc3b4d41bb8881249b18985960b2d43de71937a5b6c9cdd67ff83f7fc883518ec00ce80eb2a46ea5dee1c0ba2d4539b8e7be0fc718fbd493aaaa344f9366b72c6ebc14ea0a30ffc46da17e40043c749dd5654c01a25d62006ee70a76b9d6becd81701f87b8f0ec25618545b3c0c53efe3ecb4276bd09d8450aa8ea47ee7af0c6a79c3a3a3a9e006619b61af8e4dd0f7df0c3b7def2a92d74c13864403360443625d0edd45e35ac9e77ddf117dd70f58d576d0c2ba798b2b3a2d94c1ba36a6a358707bbc9fae8c8df7cf9cbdef08e777de4a3f7c64e8f1e3ddab6ed78da78e2c1607476bc390cf5634e91f354fc1ecfb7efd585b43f22c05c7483406d2e9cdb5b0736e75030734a099019091044b2e67ce75d771dd9581bb8e2c31ffbd4f39ff1546002a4c523e7c9bd7aac01a1988a61d3b56064aa4cce07f77dffe9b7cf434dd24d5b299c076db01c4096857a5bc09638e776297a7ecfb45b16b5e225e1ba839a7699cfbcf3117a192bdb1c3230142fcb4c58fab98a0bde35ef05ec6b4751b767fedc977de953afb9ead1d3673e70fb27ef3b33e63024c7394700520355500444e39eb3cb68ce5959006801f16947aaaf7ece8d2f7ed6b598a6e3f34d3d58574611e1d29ddf9e1e5d5b7bfdefbce3de474e6f369142a562684664e449babcac89cd211d3216e8ffbd6f77b18617c383eaddcc4cf20e75b2cb8c981919a82a3089f6cc5c2a98623379d6173f63b6bdf5b46b4efcc0dff9eb6ea70df6020370a5e177f8cc3d97d5ccc0e1b48de23d9b16455114459a75b3765afbbdf2cc8bf4f63e65bf909c5b68a2ef29debe731be263b3ee179be80b6c155ca8f7ed30d5bc3c3166319e69e954b0b895d5b2cfdd5b9767dfc96aba9b368b7b380180545df1eedbee7bf787ef04e656a8a50162404586446068c8080c6008026226b90a9862394db50b48e1630fb5ef7bf0d6f807b7fddb57bef8b9c78f8fb7b3512c3409d260186816ffda5f78e96fbfe58f6ebff7e1713664470493f1f6baab0ec647fe2c24687f89854dcae7d53b9aea1e71309716d18f525195a48660a6e6599032a071f8c41d772601c7acaa7d5c7565d2ef8f6fef3dca9d876bf09e8fdc7b7ed6b9414110bb6c29491578ad1ce6a65b883d5ea62de7cb63edc2abc47489ccf96eab2c5bec7fd8122a0e97a4795c4273cb43b4fd638f6fb4b993e7a7015aacfcf72ca08b2d4d469ed7ed7bda7eaa2e01806406a2108681c0ac9b4dc5170ae07867f50bb16c96c5b88ac1559e0b3753e9ba5672765943a41fffafbfefa0fbf77ff73b4f06b7255b0e85c7d29523cef2addff0925f78e3efc6b3637361d2b66b6b6bed74b2b732d42156d8c208e5b23f17d01cbbe9828888a66a6a0044b497fb742115aa0f43eee44a9819755d276677df73ef53afbaaa419ecf82ecd3a2572cfd01db72cf5e2202800e8b2bafbac67b5f381ead0d425998d9647bfb90bab783ac2c111123f6e5320448b0f727a001dafcfece9125575b2a047bd7b9e8832e8e1aec8b262c9385d7df77e78fb15de26d2f89745cd86724c6bdafd67f7adfb6da7bef8b8088a96b533b656d8701d6030e49bd769c2718b7398e2be986285446a42cada63150eb4a0c212887e9b43a35a5a3ffe2a7df70f7d47cb18eeab11a186237ddd6367feb377eed8084a4750c9a625dd787fce29725cbc213043ab76f8704657bfe12d93379e7c939174200a0ba1e4c668d885465487379870733355626fd8e7a41c25d43dddeffd18f6f4d5bd168206ddb66e381a3e170a8492ed11b9fb77097ad2777508b122d21dbf0f222eacbbbb85ee6bb2ed2d88fadd696d9ba7d3ae3e1dce241cdb92f1266661503482b297a5fa0f7292503028449a38046ecd9973b0633202305c72231a72dd6988d12140a6bca2eb5db6bae6cd6f9875ff7ab7ff36bbef2f9cf3a35b118da666d584f27b3f56af4d7ffca5ffa4fbff42ba3f5a3dbdb2d66408fcb14f5c1fbdf730c0ff6eaa0bdc424b383cce5ce9755eb15fbbe8bec317681dd2446301311dc8534b31f56c39c3a8f669a11184001f8e0635f017ef7f78079328c913d41f2cc519928982491c8e8962d1775b0760a0070498d4d9f73bedf73de75bef73905004b6b759691f4b884a5d7c782fafed18c8b27a9e5c1013c3c8c77c08e9df363f775bc3540b838ea6e461a99211b19829207ef8520c566583851cd686a19d4508dd8152e4469bb28490a764347a49a593b80d95182ed32cf3a18e8f00d6f7fcf60f055379e1cc2009b595384809237eaf0ca577ceb6ffcee1f7a471e43867c2973dc41cc2f63e9e767019c5b36134d0d71e1052f003e04eb3a764c04a6da2fd7139bb6aa2a4fe88c0ae7009496e5ed7fee73698b1eb3822980ea2e6d2e0aa21730a2b65bb3690600d15206b1be4c5a0d444101040c410d04408dc4c87b6f66065990bc45c72570cd6942d6992b9d2a03a2ed04908dd008908009d555e66b259f0c8c101def98eb5a801608ce901440cc044189328a2fc83b40c81e010dc0d8b9da415705744caa6a48e43c109ba147d76f64d45787f5a5f52da07947eccc4cb32010b8602e00995816e900729f10af82607e51ad3710022812a04374688c9950098160372fc0d094c0188176628de8f63603ee37c17ec938eafd93795b9f101429031bfa1002983ae794984d410d0c913c92376004226440e7d83372ff65738e09b2abbc5195a102aa4c81625368e7a52d10c484180351000c04557021b051960c8c543af498d13a4671ce392eda6a844db76691036fe2e0a77ef30f4f5bb09912392dead6e250c75f74a4dea8d71503b90ed1e6ebf9fb92fbde589e4fbb9aff770fb4f3afee336bf66aabf70af87597a0d9f5081d01a3515fa7dc97338c67634798634788394787406845e06ffc86af6b8c3b5ffde00ffffb87b75ae817143d5844f87911969b2fdbea3ded94c4b99d272b29b373800a2660bd3eec1f6eff000dccef4c13443dfb9b5444a4f24e010d703c6d777c2444d5fcb3bf77eb6df79eeb0c2b129384d59a9a519e017a432022eb839c6aa0860029b5ce394025d3e05d5f8f8c26eceb9cb3f67d9490b220023917b2a69cb389fae0bcf77d720533930ab960803ba5e99a09ac0c01524344ceede49ff41c1600b8e0a7e3494ea92c6a7461dae5268a3257ce21a298aaed6653ab6816724bca3ffb555b712fd8db37e3dbd530da4792acc79f6a2e9d8345b9fd7b0be0ec53e68c20e8444d25174c9263590db258279855b59f1d7a2d6780040a02009a0d818aa230d0aeeb52ea1c98f71cbcf744ce53704c0c6820ba6b6c9b21eee4a800408c7927887fb14fc3480cb9aaaad3dbf1cc76eb09dce4a17ffb8f5f558d3771b09ec5b4990ccac103d3f45fdffcfb5ba22cb2cf1cdbcd20a6c31b57ef0be3e96e81f3455937aaaaf9423cd2e65e97deb4c9174700855de8bace31b7ed2cb033b3f1f6d6f1e3c79ff7bce7dd71fbad234f01d23fffc1ef3fb151776d5b96e5e719e00ffa9002200a9e0000528cde111299aa82eb912e0622165c3f1d48e8fbfd23e4ac0ae21cf5c10f06edddbd69dbd91c93fc73377fe8a3f76f46f4c12241d630c822415a2caabe330411396250cb5d4c31d66e5a9645703e146e5457210442232272b4bd351ecf9a94a14bd045050845514bbfbcb3f302a6961d1981a844c4a18898896372a42936045a97c5752786ccecbd77c444c8bb86a0031111444e8a8f9e1bdfffc8d94ea91aad35b3ccbe40cf194c2d2388037100d9c27272bd2f39a31d6bba77244cbdf760da4ec65d332e3d5f79c591a3c736304ff748bd792bd17069374c2467c012bbca73e5dd68ed681be59db7df9bc414761a51f555318896a5712198b2640000cb1972e7c04e5e5186e0aa5078478c18fa55a90dc831ee15ea9179e21e7549d3def899a72f30c953ae3e355c5bbf6fb3fb835b6edb6ed5dae98dd71cff37af7cc9d9ed0987d2b32bb49b36b35f7fe707dff7d0a482345f30d7fbd839ef2ccb7db048662fca7b784ffb1eedfd0adf0bd06e068a3ddae772f2c5cc44414542702262598aa24831765d2792d786f589634749934cb7fff53ffd81ab8eafe1e7970fbfb00640532226001600f4411044e19e7bee7f60bbdbdcdc4a92832f4524e5aef0a1ae4b767aed35575d7de278e1c803a10198424c50b28ab0433441c2ddd218d09c40c45735a664c02a60028eb84bd6777f92dca6b60b846b6559aed54fbfee6ae75caf0a3d219299f426095c511f09e52917ea26da99b3e3d36736a79359441f154dcdd0a5ac48e61c23902038476000d2486c03cab1a3eb579fbcc2e98c518912a31022e24e7d68f018635693d1a03e71ecd475571fbfe7c1871f7ce491813f126596c0532889824a024322a325a45daf99119188771c265002034bcd784b531a0dfcd3aebaeaf89181474bb12d063bd6102321e25e055b3ee0dcef604005d8119978582ffdfa70b0b63eda1ecf98d1ccb2a2a1219061dfbc061d39325664c00c397b8ba3011f19d6c78eb0f7de3331229a31a9432222d3b467063a2422313331317f112f60b8a3368e1e1d1dabedd8ba3f736e73ad08e7bbe8d78edef1d0e69f7cfca1e73dfd94744d526bb379c26f78e98b3ff5dbef48adf621b1a4d2d33d0c0848087b1935b6b399cd27e65c986ec06c7705bd9dd5b7cc0cac9f4676181f03dbcbefd899a4748751de49bbde35f8d180768ae1b2c86c362bcbb240ecda460ccf6c4d8f1f39e206fc6f7fec277ee6c77ed87d1e017ebe02b49fe77a03f5ff27efcd6225cbae2bb13d9d73ee10116fcacc9ab2c82a52a44452a2d890a5b65bb260b72d0bb6db761b1060b4014f68f8d336fce55ffdfbd3061a30fc63b8db2d1bb0e51ea4965a6a5a134991142591c51a5855ac212b2bab2a8737c670ef3d67efed8f1b11f9f2e57b254a2cc9a21cb8788888172f22debd679f3dadbd56101c516cb9c0bbf71ebcf1f6edfbc727c8e2c86a0e4c6a0e482eb137e8cf965cd1f1ab6fbef4f2eb932a3e7bfdc6b34f3d39a904620230403440558591d08c0891762635e1f138874180c440809145358166b42141695ade9b56b3e9a44a417c08e088408421502019af0a39ad6b0ac3a2467a6a3f1e4c0efaac0fcee61fdc3f9de79ec34484dc911c891b0477eda10c4d454f3e796367520576a6524719b338216284adac726fa589c1cc725e95ae9ba6f4d9e7af7df2e9e9fb87fdd1a23b9aaf5639b334918329e5a150c02bfbe74e803c2e6bd38cae4860f9f4fadef460e7a04d5c31445a45e158078e81018988c6a5bf5da05781edd00d1088a1845955ed4c67759dbaf9629d9ba00310808de90390553c59f65d29cbb60e7bbb752d691671675221688c12250801013263202622a68786cd48e3f49caa22854766d13771e2a4094d0a02c65ab45b448acbd56a6f67f71ffee6377ee8e37f6b669d7a306e59022fe77b7e760fd76cb9ebb71dc32b91f1fe55b8c6c737bef3c825bf1061f943d6037cb4fc7929390a22f67d1f88638cc330745da7aa318455d7f7eac76fdd7afee693b3e9ee97bffec73ffb935ff8c1abd28ffbe51642306423e6dbf7eebdf2ddb7ee9f2c0a0948451c44bbba8ac53ce72e842041ac6433c79eddd5282c3a7ff1ed5bafbdf7decd679e7af6d967f68180c1019083998d1bb1823d77f39917debebb44735794d187a8e6c172a9024c1a9a36cdce34ee4c521206d75a9a18e3287e2822e460560811948948bd14cb86804cde843ed3ee6cf6e4417b34cff78e57c7f35e41dc682896f3d193d7f7afefef3582812cb1c6c05594c044442232aeef8d9c2a4424002070403533cb25e79c22d64fed3ca3b37b27cb77ef1e9f2ce64e35530224bc4a9d16c40101c80d5c0bda105023e3c79f793245a922057446ad44aa20313070450eeb6ac276c77047beaa324cea86446ea1ad4253451629a5d0c809e8060888ec2320d451b306b32470bde51b7b752d1ad9ea8a2a6e42083146414044a675297af4049b8c00911ccd8dcd205cc02aad99708427ed24c6d8b6130224d5fd597bf2e01eb4fb5ffc8317ff839ffa040d1d8017e4d974e73ffddb3fffdfffd2171f92823c365b79a153f3b8bac943cab00d36d6b77feb40807e0e624c887e2e8d757f240a18df35e752c7e4ac23aea6aaaa528cc81c2cc6aacb435da7c56a494abfff8d6ffe401afc79084129c562fce397df7cfdbb6f1885d04ed1705053f06ab67ff7dee183a3c394eaa6c1b3b30781e5b9e73f262a5db734348a829e5643fffaadf7dfb9fbe0c73ffeec74d2ecb415910094716f75f783fd1d426058cf7d8f1d9f5c3ad6fedaf5eb379fd86b2b02ed026a2da1aa1a724829321122caf83d8d109130181a001713552da66a1610da24d766b3ebbbdec6c377fdf8f06cd115040a9f7ef6da7452d701c4872a609d6253a518a3c0c82a7b6ec181ba7bc4582c8fb1a13916c0142a11317362dea9ab590ab7ef9ede3d1b06438e95b95d716ec5cccd1d01d82d08cc9a386bd22c40089e04db94dabaaa62224030f30d04f842c7ce1f7dffedf301b957272224df32e42222f8dab123102012c8baece7fd6c12f6db6aa7a59d649590484c294d62b5a1d35d77b3ddd60418080884ee4e63b78fce8151b7ce9170b4fd48b15badd0e9e8f0ac6977a05f0cab6e6f3ae98b7ff5e5db3ff7339f9b105919e6593070b09252ca39abea7a4c7d430af438a3d9a5be7d1b058c9c7f5bf31e4dfd116a834b6672361ddb4db2000081c9c142087ddf775d87233091c90b0068123e3b3dd96d6f10f3dde3d31fec3e7cdff7a7a7a7bff2f5ef30736c7735e7f972d5b6adf6ddebafbcf1dd636b9a66d567b3c3d96ca6aadd72feeddb87504eaeed1f3cf3c413fb9349251cb962375fd98b2fbd7af399a7c273375d95705c3668ee9afb7eb5f42630b3839a190330d2c79f3b983629851c009b3ab5555da584c04c2e81c8415537482920c21e86f17a87c022c2454db021c2d275ab2e22fed0cd2776a7b36fbdf6862ef3def5eb7b8da5e855f03ac49db6ade29a9330ae2f93b996113040884840a54462237604754f89dcd8cc0873d77595f0f34f1e344d636fdf7df7b87724bcb20b8b003eca9646a13685839de6da5ed39856756c520c51188980c8c941c8d75a7ae30eb25de317c76f37db812015077644223405737a681b04a06bb8f8584177ad121ceca56b933091328952d7754c0d87baf26193dc398021d3387d508aad0ddc1dc0d6b8354020c77372575b6334eddbc92e4b6adae96ab8eb20751586c591101d67f9d2cb0ffec6cd38c35cc9d41063aca6d3e97c3eefba0ecef1ff8d967f15eae6d28de0223009e1f15f3d0cdaede2f830228cc62f21cce7f318d2482beab4661f03612f598beecfa6cbc599c044e4a281f32ffee22ffe3984e0eb55d0f7996594e51e176a0fe06eba8187a00114057e344971000737f78c34562a1c510132c0dd93c5ef7dfd8f6aa2a15ba566a65c9d66f9edafbff49ddb874b689cd81c9985594a517760890e6431cc8770e7deeace070f76779b180a232085a19edc9fcfdf7af3adcf7cfab9a11fdc5d52ec73fed49efffacbc787a5ad7455856ad577b59d7ef69337763ef600e8000020004944415427751ba54d71a76d674d53c528cc226c226e58dc1dd11081c0190cdd8d18283893a138220109414002abea6a14376dab349b54930a772a4c04933aeeb6ed4e53a720919891d0dd500cc91981d9c7e817108c40c0a19017f2c2e83856791120240e44688436a962db367dee1edc7b8fe344a2e492cd86b66222eb4bf618c157214819566579fcdc93d327f664a78249c0dd6953c798628c3c564d1cd1899c470c37584062421fcb1f1cc4fd12c42d62162b084e040e314a08c1498e4fe7afdd2faabd630981ca6082ac799e247fea999d498436e26e534f9bb6aa821093ab71cc66868442c05cccd4cd111dd9c77485088881d9898059d51dc8810019b73cd640ad602076a60f8eceee1cce1743ced99082635f433cb4d5dffce1e79cb4e45e3876aa9fd8adbffce22d0e6df2615072e4e0b9ac29f1c7781bb66cdd8860c80fc9f7cf61a70169fb24faf8cc588d324004043f771882a33afafae118d4203a80aa09c9186284108410dd340f041e6230f3625e0c767777d0f46ffdeb7fe3cfddc3aba9998510520aebe007703e3fc57a96189080d6c413caa53093e28697c7b7483104c4e42b00f29c8dc428bdfcfa9b7ffcd2eba99eae4c7132bb7b7af6c2ab6f9c76594162539f2e165b01f0c76bfde4461c8ad9d75e7cbd16fbd99ffa49184ac328ce3ffc233f5416ab46dc01978b65ac1a0a65393f9beded4e4b3c3cbc3f699b8fedefcd2aaeaa58c73469621342e42d6ed1ab5c1e42dbdd6103cec1008448c00468666c80ee5cdc888828302b011235214c9b441c225393aab60a4198d011d00991b076352fa66e386ea584c0c85c08c1d8d0473036112564472c251bda88f373a69d263ef7c47e1be4f69176791011e6943d837a8c11095545109c60e7fa4edba449856d152655aad346da09110d3664f0de5306767453a2317db1511cf58ad514c08190195155c0188cc10415ca00e6c49c8b8510a1e426cab5bdaa89dc54715687498a31b02019903b54d03bb982bb390008ad1bf866f93cc470fb93c82eedec168f464218745daf75618c8419a2673f3e3e550732138939e758553b07b394387b56cd48802c0e0e06e8049b59d76d9d1e00d075fb101f6bcb6d7930c03763b1b6eecf9f3fc67065dc46d0c6bee57a751193a181ae4504c6f828a54442cbe5b2146d9a46d04cb33f16d2fdb918fc880a1a1b1808567221e6c964b20528e6aca3b84971170c655820220183fb8842d99ea1e9748a3101c0b75f7efd3b6fdfaea67babe2a56adeb9fdee3befdd9d7703a706498aa924b9aa529a4255569985068e264db6e1b7befec2c7f6da7ff573377fe2afff68bfeceb260ddd00aeb33acee787c3eebe99713fb77256517efec9bd8f5d8bb5580ab1ae63932ae1b13332f6fc1dd0c6ad17819d109c9090017a5b2a40c1d12f02012614025cd11819020121635b89434dc2425c851883d01a4e02e884006659dd9c9044c0c9c76d7f3d3a4a00a0c000c6eae803a031ba103af248f63e4ba1b9befbec5e4b6f9ddcbafb60694431e6c101721429a5b009b83515dc7c62b70936a9c36e5dd75505a0a3074270e46d740ca263f310683365a823c9dc15a49a6e0a48806caea3248d111bf106adc079d01804b4db9df033d7a72de9b489b3ba4e81c6515c04840d41f598fc3b0212b9a3aa0af3e5e30cbe15547f1492cc8124208dc4db3e12f6821b319963b75c9d9ce6d90409591dc9bd6962885c8a33a002f938e624cc7a853eb4db39cbf5b1eee11b1018e0c35fe2ba39b7a6e81bb7a5f5a81c52711b9fa4d1ffbb8fbfb2112e4108b8de6bc77ebe0e5d0c49d84e4f4f6f3e7503d43effa39ffb8bcbe1d101c84c4d4200806118a240ee0669261438039c29fcc1b7de78ebd63b1e78033958d7e7228b88586c1627c74cd6a6300c4376103233fffab75f5daefa656fb19a9ad9084c4b315ab91c44b45a69444454245164a668dde9bff4a35ff8c91fdaab5c39a5b395d7755ccd57e0ddac0edfb9dbcff6afcf174bcbdd334f5dbbb69b70983bc5591d520acc80a6ee0e2488c800dac41147e1eee84ee863bed880b87b7653288e0c40666e6e4080e644840e4410034d203233913023d33a0772c3b1315bc29ae40c8408716cf23360b255617410454414301b2bbc3c469628451d0ca24093182bfadcf34faae6b71e2c4b2980c418d01cb5004428c3f5837a77c215f82ca52ac4885cc01e1590599bb7502422723053771f45b600205f01e23216440414235314a300240612d033a24140f09257d304d72669afe20aa9492185114d6588cc840a5078b28e6518197d2c190401077bdc8d23a2a1c363b21f8858a38a65576033218c2c0aa05a465745411e1c1dcea6534708210c43af947329008c446a5eb205000aa2b9874b99a7e1123dc9118ef04828b0bd7feee9111feae0a3a6d6b873e83ad5f5d1233ab9d9585a5a5f9fb16548168761109118240a4f4416f3e3bf08832feac2a896d1919855d5003956e02534d19cbefc07dffcf6abdf4d933de3d0a3440900bc9d2532c70e001517278beb7bd796f3c302443109224af8d60b2fbc7f9253aa912282e4bc4a21b21baa6d2fee45a00ec4024353a5f9e999e3aa8df80b3fffd3fff28fdc5ccd4fb1cfcc56a183c650d52e7038c03ffcd5df5874d1cb6ad2a6676f3e3d0d5081ed5ebb3141256600321c43e875c86566e440e8c21c091901ddc01d352a3843ce6386c958d44bb6086bfaa1512128124a0cdb4eef66729e9cc6e56288ce428149840202450823d65d6528b0d23218b89323030a20959247a03b6ec2475565849b0793f962ffb8d3fbf31c634d6836f44982668f586eecef47cc0d4b93921002ea06e1e2681be52941664657110422775e17c6891031e8e55d800858c66a9670148aacee3dd9cad0cc8a5910a6b25aecefcef6a729a14e9b498c1259ccca7a08099dc199d00184b00e1209093d100591aee4cb3be157d057c732a4400551c879bd2f204974b40d64dd81051491909100848089844d02462063470776b918896fa0abe91c1267e4b7351feb09eea8b60df53768663dbf6510a2998d4d544274da4cd78d2c800a64ce2c14080072ce25e735fc9e31422825efb44d24771dfedd9ffb37fe62427a1cbfc11a5a4c01110680bbc7e5cb5ffef283e393a669623b51cd25774d4ada9d8c57631c474396318a0ba4d26bebbd15a0500dc66fdfbe7bebc1e96cffa9aeebf29053acea540b62316524bdc2c3a4c022f5d1d9c94e132b2bffe1bff9339fb9b9ebab13d8dd3f3deb26c3bc8994876108937ffcdb2f7de3d5dbc35052d356213e75adaa03bb2e26b580136fe61198470133037304ac078d313655dd5455600acc6374b62ca49afbdc2dbbc5a0c5c09d8589475a6502444207e50dc0602cfcdaa6d24b8048ce40150e5553d7752d22042e04e0ea6a1967a59430f4abbeeb873c981a12000386d14ad66fecae6006c6c3fc89fdc9d327ddbc3f722a23b41343aa3ccfdab8db0629a54d551da2813a3922a139b8224010aa428c318628bb239e05d9cf235e3e84620fa0982307000b6466d69b3561ac45103944466cf88983666f5627d0982410232273301c076c80906a585631354d55d74988ace888426faafad242b7233f8e7b71f7121b20280646a248661990028382836be987aaaa549501755049619cf477b5a2c58989c88b6aee9dec1198dd2668472d17b601305b8b163d74f70fe380c7d0f26e6eee6eb00ed9d624f66ba0c13897652597528a9935a93a3838383838f8e6b75ff0a249e8e6334f74c70f266dfac4cda7fe8242faaeebaa2a22803a384001f8e55ff9cd93ce88a89aee66375347e118db54552fbdadcbe5f2f8ec74be5c966cc0c41299f9860c9ffdcca7bef0635ff8d6b7be1552aa77afbdfcd517ab9d27166747a96e636c8be63c941844488ae985e1bf6d38ca9ae739cff6f6f2e9ddbff36fffcdcf3ebdbb53853ec3f26ca8883ccc96486705ffaf5ffbf231b41a5ba63e6bd786b2d7868a6d565537769a155768bd9939e2c859e30e2c9824ecb0a4ba0aa951847ef0e365562322928a524a534829ca6ab5ea4bcebe1e110107644244378087b26e0a8fb01d7b1524c5b89328a45a51e6abbce8b2029a7bced999da1426693a4b6918fa55d7f76a05d010d1815d3703b760480e5c86c5acbd76e360f2de83c379df390a320de67b0d3df3e45e20ad536caa9a889d003063593b9c24a1ae6215a38830a063982fbb653f8cf414e3a808e29506af64bd1a0b123a7a194a310c1f2c4728b1a05940bb71b0b33ba9eac03557c2715cef6306e3e01228c6b82f55dd3624d5bccfa7cb6ede0df3e5b0e8562378fe5290dff9947e1b632bd7560677bffd6031188610d055a010112188acb156e0c44460a55821742164d3028511118b9acac55998edb66217768131d4daee0e38d6eb362f7838dcb9396c035b1c4faa21b839e03a921aba0e116314aa6229a55f7577de7de783f7ef34559cb61326383d7c3011fceffedbffa696bf9079782da5aae2186f5048bffda5afdcb977bcea4b2de04ef3458e935d4ce995efbefdf6bbef1387e2d10cccdd898923b098b9199ee4f6ad3fbef3cfbff1e6a46207ed0683502dfaa1aea383663547961410b1b883015dd566ceabd9cecee9d9d9cffcf88ffdf88fdc4cf3c562bef2babd0e67999a13e35ffdbd17eecdf3bc43f7b32777d3fb0f3a28b9697c9ab08e3889c85e786cbd3a1800031a3802c418dbbaaa22b084cef207c7cb5befddbff3e06c3e64073968f2d3d7ae3d77e3da6e1539d58178950b98aaeb568069937992bb8d502e1c071bcda350535793a6219195c29dfb67afbcf9deed7b2719c443324719ce6e3eb1ffe967af3fb55b8b4890a13880b991a02b594157033720e0802411482ad969ab2ac9bc5b444948b8ca56577ced60d796779bc92cc64a0b0081a1060aee2e8c2985a6aa93889be5dc7fe5eee2de8307f78e4e8b82b38cf0164652bf7c6ebc05e9732189880e965595eae6e86495c198041da0f4073bd703bbab490c22a19402e06392864c29a569d346892a78f7b4bcf8c6edefdeb977b8c8c7ab723c5f847339fca3083fbf400bbfc10548e9cfaa18205685ebba6ec97be80695c010e32ce4dc8bccac6855a5f9fcb46ec2ceb4a15849e68502108a870cc4852f37f8470527b638ffb56e9f9d2bf5a9adc713cfbbf79170dfdd36d07ad73589e598713493faecec6c395f8ccfe0deee98328cd029d0f2b1679ef92fffb3ff78da885b592fa78fc8e02d679510c60d8901863ea7184404bc008085f44bffec778e162bd6dc48e969a2c8b99a7ee90f5f3c3a5da5aa716a741838388cbd3a74043555596fcc8a8ece7496c7b225a3596234957178634d75e8e3c430110c8a113858f14a200fcb545506b8e4a15de9333bb3ffe45ff9429adf3be13009d3ae5b1591a14afffbafbef0c25b77a7d3e94e15c49cfa81d453593ef3c4b31263130257d5ca2159df631f2879010e9c2157915b82a7aa6a6efefe51f7d2bb27df7cfb83f78e561293100ecbd3976333bdf5de270e0e7fe499fda7aeedce264d13d4e7a72b58cfb8f625332013602991d1dd966ad254dae79d141ac2245c45b973022fbdf1ceb7dfba737f70255128d20f89018cef7cf7de8bb7cf3effc9673e7da37e7a3615982ffb6e187d0ab16fda32e480a68387e1ac7b76af593dd97ee9c1516e1bb2fc8976f8f8bef8e27456ef2187623d10902369cc3034755d93b4e413b6defcdbb74f5fb875efcdc3b3f3d1fab98dde2e15c379600300203eccb47d79e6ee80011873994f1a9c3681725fef565249d115122322682182c434619e2531c257ef1cfdfe2befbef6ee61af5e553154b28b11b8ba742c774b2cb365b6dfb0f71ac06c535f00f71e00a06acd3dc52e2f3d0b2e345dc7e3550e149b799603d1a3eec1c042c215a7d549998458288f45f84be6e1f09cc7deecee4e08804e1b9306770677302ba31af03ab15f576fd0b751ff3a9177f75c349f1d2fc7d129331b5b8f86860861b56853f82ffef3ffe8d39ff8986b26b0a22a1fa5c13b89602e39481893959482ab0d7919abc99b773ef8e2ef7eb900551c32c99927c7ea8f5e78f1c1e96aa5d6b43bdd5072ee767777cbd07d349105a0bb93a9bbba8b48749045bfda99ee5b5efc57fffe2fe4b2f450359e5664b3188f21fe935ffbc6bbf71612e2d962bedbec496cc1cab42da999d63124e1c84c808c8e88820c882e40086d089326ed4ea7f74fbb17de79f0ca776fbd79ef384b1b52a55a4ad11488bd48903bc7abf70fdf7e62fff0339f7cf66307ad08633f263a10d69d21004235404211b19253e018b86d5b8ce19dfb67bff3c2dbf78e1747abc125c558b91628d98a1b812401f6efbcf9e6f1fdf0b94f3cf5b11b33cc03396ea3c16da03dca423a6314ba76b077fddafcfdde340f4a43554dea98628c21088f1db0d1183cd5141be1a66d0bc357bef9e60bb73e98ab5ca982fa2769dd5d409b8f937668bebfb353c5d42046090810880dd705a024a14aa1994eb2c3ab6fdffba357dffaeebb471edbb6adbbaef3524235f1a217aaf417ee8f65cb7354a59733f038a072eaba6562681278894767dd74d250d1e79fbc7e135189cc2c84341c0c558cab6175e17d2ee8463efef3d236de79c4de556f75fe7daa2067676744349d4efbbe3f3c3cbc76eddae73ffff9e79fbdd62fcbac1104503322bae0debf6f834770f328c1ac10d1300c3156c894b87afdddbbbff78d1795aac4b6eafbd913cf7df985d76fdf79b92b5ad553efcbb21889b0c3b25b45c28fc4e01d2830b966300da15ef6da0f433bddd57ef9777fe1e79e8c7d66d0d0c2e043b7ea9c7fed9baf7fe3f5f72ccdb21a180cc50020867830adf727d39d496a22d5691c4167421763457372746bb04a2083c337deb8fd3b2fdf5f7503c49d50a59cb3db10449a54afba45f1b0d4d8153abc97177e478783cf3c7b1061594a31371ae51a8d8883ab1a6008d297be8931895008f797f08defbcf3cabdb3ac0ec84c085ac8d62d6f9768dd9cd8cffa7ea53adc7ac0557cb24e9aedbcea33ae8b440e6ee0ea560e76a64f5dbf7effdd5322af62481224b0300a026d982400314a95109b18b2c3d75ef9e00fde3e3c1a28eb309174f972908b4cf21738ae2e00cec72e84e5616f3613d414632076356414a40286e8294a15a3047efffefcabafde7eef70d16310966d3d18b8425d3ebec53cc2b1b51d93d80ca85c6af068f66039d429fed0b3d7e627a7a10a3299a2e5fd36fcd4e73f17233b4251600653100244f8fff0e60e66709e93b62b306b44b39ae7102300307fa42496234fcb396b8f068e80b71f9cfdded7fee0f06c251c55e4fac73ef52bffcfefdf7e3057244742c7519b8901894998e02322e1600e602aec045474201630b05c3ef9c4ec534de41658d3fdc3336ee4e9c9e4ab6fdeffed6f7eb76af6d0c1876ed2368bc542a62d00cc123d79b03315a823b429168740ec568492b21b7b0d30ab2aa2eab53bcb3f7aebfdc38e526c4260d381bc345542c493c5a28e5c1c4878d2ceb40c771e9cb6e23bbbb36b4d7ca8d6b6e12a53207343c448980285c8ab015e7af3ce2bef1e52daaddcc0154ac13c20a233213022b21ba8d6ed4e0fe1d6e172fff6fdfd4f5c2314403328e40fb9e2b654d036f4558cd33aa2e640b8d32624207844a91211113804491198ecadf71ffcfeb75e3b0dbbb18e92e7570cdd8d935e0f9dd4d68ddb7936bdad2922ba117a012bb336615e84c44ca4466e03322330b3a7104464d1c377dff9e0dda30ec2641238e76c4397982c889ac90658f3880d6fceed8510e3bceea43fea8dc533495d0c7c581decd4a7f30e2b807e79a6dcd6cdd884e51172c86b48295c8819cea712979082e24766eb1bb227a4b06ec4e42c1c198803ae299e2ebb7d5f064fc4e3ac52c999991dc8016ebdfbfe2fffd6d7ea08d3260e1edeba77f62b5fffcd2e9726555e37abd52af79d88901bb99b9740a1e84773221028e765aaa333cd57433b99b27bbf3afdbb7ffbdf99fa70b2ea23c5268698e4f6fdeeefffb3af70b56f0e43df3549084aee57e9605740856c52850839496066342002071ad957053c08a5103bc357dfba7d6f9e67ed6eee5765b59491c1cdc146421b0eec863a609785c5033f98f77ff8eaed9ff9e11b63c36c244bdb907c00029a590a4204c2f1def1e23b6fdfeea9f23e8b40185bb2044ee088ea8659274dadfdb2e4523822c4bb0fe6f776db1b7b2d002030c0562ac7111c084588cc5d35a279ee38499582100b63206422771dcb23842cc12486bee8db773e301241c98bc57ec3abfe72aec12dc3c479037b7c62fc614640880a932ace9a04676791301023b197de7d64bae3801688ef9fad6edd3d020945cdac04441604f062eab866fb7d3ca41f0328f047e75800c11e33f8d16178a9abb8383b493175abf9d075469324786a20ee98736422e6115d69a0cc72f530d29f6efafbaa41facb6b13b0cef62986b1c98f449c22bb832be09a220d49983f5283470433372d1202009dae8654c75ffde26f73bd5f8695935b4cdf78e98fa1de2db624d4c3a3c3b66d390607d5a1203313f47dcf527d24069f736e5255ca6028b16956cb6583c3cffff5bfb6abcbb9608393332b02be83f17ff9cddfe0d993c17c582d1203bae5ec2069b55a511ad5c0119989c81d9dc0cc115cc908881dc459811e2cfadb1fdc0b52d3b02070a902110d251755210ef5a4cf45d0050d3d830960580d7afbfed9dd83b0bfbf47e08c1488b3657445f7c49c2d13891914f7fbc727f71e9cf0c133b45cb012a2e31a5089e66ea6621ea42afd8088098d04cf96f9d6fdc5de2c8dd431e38b69ac7623aa7b402632766b524a518439a51084228b3033f888fc1cc74b6b81bee4c1c37b27672124038d8cc36231829d2fc3563e3205b519f95c0bea3c5e45574777dd99b475081645d6f2ec20c40511108591d185f1f87479bc52304518910c80e6a0866009c2965ef7e1a78c2cbd088f10d1f8e50a3cdb2c2384d0f50b66ee81561a52f210d13ae79086e2968b424823b90920009fdb2f1eddd1ce2bf0807f0f76441736a00fdf43d49591c7711404042275cb395731949c25046246924b2776e9fb33302522e6d8af0607a8eaf83ffecfff2b843ae860249a767fed77be0e92983c55613168dbb66a79be381d69e10ca90062881f5d5a632184ac36184a48a5e49d08ffd64f7f4ec091c3aa5f25f6d0b6fff84bafbfd7a7d3c572b598a7c0314675281828b5c767cbbecf4e3c94ac8e86d46775c31136838102610d21499599df3b392e80bbed2c6cb60635424e292504e897a7020aaa2184aade2569d6082b2df78f8ecb860291469a2807020fe4e463139d8afad97c39f672aa4a389011176075716372124061e8865e439214dbe8e2bd12dd3ee9567d1ec641da1126be917870a0a23a56d7daa69ad43533575512a4cd70179eafc085a2c4d59da3e1a4e7d5d013e67612879104f2b203c9cf1f8036dae555ca13a32f6a27b5db1099785302101e5fc0cc3ce288ef1f9ecc7b072d29704c9501174543620e1f22fcc0b03e04697b3020035c7a4068184062f57ffce6d717b19698d061a1c806968b1366b7a11477a4472d673df5b639e0ea5f5d7a5c68daffc9a92b9d9f40855cb2bba79800803800d0a6e70b1f312f7d083c7608535539c0177ff7abcd74770068873936d7ffcf7ff195c229b07b7742a176a98661a8aa0a9c107828439400c8837af51191654796d56a2522c061be5cedce26cf3fd548ee7b16ee7448daaa2eb4fa17afdf31ab43dd0588643a0c7da8a74571000e1c9783026236774437542bc8a4aa82bce857e09ca83285a270efe498480449760fce16f33e2b120198aa46c29422109442c56800ca4e2945d14e978b558f4862586c44bf83031a337b29630a4cc2c862886ddb9e69ee393b206040a918891c100a383b0cbd01d769183a872c085cb70f167d9f870451c58578434ce18c40241b02258c318a48ce9999110b138dac5500e08e4c4cccd82feae9ceed0f4e060f1400a09c2cbb309be2a0df4b20bb2de09de7293bbf0a4564182cc6504aa984880880146993df20118167553d3e3d5976fd641a57a5748311518a158f5c371bd77849488f78cea9e32515ef4753fac56075552d3dbcf4d6ddbbc7bffb777ef6b3b1c1584fa9efdd4d422caa7ddf73002512a22b23f7737584ef2d15c53f53028b63301f398cbdfa6c1a421879cc69ad53f7917a780040060ed4e7b20478f3ddf7fabe8f684793e7feefdffa1a3209e3a056b82a80ee3d33975246d24f117135740b7831d863440260c40b64a017023078547b6c8da0a2397b9b0d5a2463fdafffbd9fcb440b9608b4d3777399fca3afbfc3e8667729d7a805003844d3a1c12e95b38026a19a56a9092130237908c10c62a8dc31592c5c77028a5dd72d974370aa05ba5016fb6d6c93415e04f224410d0c50dd10552807e81274682b870cc27588e0cae031566a485c99a342767695da308af6047d41e9016b8460218244f400037907d039144705c44a504a1f9090a253ca4357d1703a28308ff30508604a82820e62053cbb3ba5504c8948dc2b28c23caacd2be0d8da7477b0d2911780c5b0ea4a0ea935a336a45a87ab95af2e391809dd1e52dbafa7820cdd92a903197805b94231af07c38a96eeae9a258cd6286640419cf2e014855bd296344029b977508eebc88218b6c1c578dfd11c0dc881dcd10cd4401ded828b45a2f14e8cd6694f90bbeae0d6a9ff4fbffed2efbef4c15146845255b157cd0aa96ee7fd6a301d46765fbc504b5b7323ae8ff1057f6e07329dbf3f6ae021c0f834e1251efefb3278771f07d424ca3ffda7ffbcef733399e602afbdf6dad8f6342bcc1c98ddd7e4c11fdea7fd5e9e7f5ca3eb5c0ea900e4a063a7b02c97abd56ad4ee32570a0d45fefab75e50f0b669520c97ca7d7dd8c7d1c8544944340ce574be5ce582128804cc9284aaaacccc4145646c84ae150598910372600ecc6c6b5ca16f85d0cf4b975d8a0cfdde6f3cca1b6c6a75745e7b7c2b8ae00ff74ada50293c84e83cba9f363104043447c4a25e0c2ed54b833fbdee1a00c458b9bba46adcc7474a792262a22dbb3b12efcda63bd3c9380c5b364ce1a370c872b97c846562738cfef772593872403b9f7a8c77442938b35b25dcd6a9d3fc872fbdf2f77ff9373ec8e92c9bd8b0d3f0c9f19cd2341743ebcc2e7629e02ffdedfbf7f0e44083c3e1f17155d7d96159ecdd3befa5a6de5ebcb1507b29cafd432cf9435e79155e5a352307006722057f7a7fc7c0c195cc5864e5e1977ffdabd3fd278c78b5ec35afceaf80c71731c116a731e29e1e8e0321b239f4b92cfb7e599c84dd3dc6585715ba0140884c0c304e893b9e633a70b3b582dda3ffa35fb0f9872bf37b1463047c74715fd0665bbfff4877bbfd5c660692ad90e3050b610ec360d76693260aa10749888c103e4417f171edca0f118d745711197a4db11e11a94430cafa6cf5d5ccd11b43a3e70000200049444154cc7666d3592d8c146355d72d02e741d1a18a69a79d9c377282732c33dfc38e73fe6100492411d0874e4b0f127a0a87c5ffb7dff86a4f111d2c772c580c629d566767b914df903bc10f82a0cbf76bf023574f57e0777ef72b1ce250743994fb275d9f35a67a44bbba16d7324a558fb1faf6e0d11d3db6ca1f196cdd1ce310c178d063078fac2f60c2c9407d3000fde9bff6d95857a0c68ee6459aea83e361a9059053aa85ed82b12122a0adcb579b2d00d0d081d147fa75dae0a229449434189ecc97591d390496401803133880498ca36f1f419d8138d086c091d65310a3f91138fd496bf1728b3a17486f1faef7894ddb6bfb703da80384e4b8e11d7024ddf0cfae27deb69feee0c825f737f6a6b38a510bb31087b536d596c1e9916b748958ea9ff07f012fe63d100193e166c264b455d391f46218863649cb23ad17d6751dabe408669687aeef9657bdff79fbbfb0175ca27cebd09b3a61141232324507964ad2ecc152ffde3ff8272b992e06df9fb53e2cbac50aabd9a045ed223ff7f75878fbc1f4f06e00e00c6fdc7ab75bf55a7c50fcce5bef186397872e0f883ec2fa5388887c55ecfa78f3761d6d9edb1aceef145745fb82e82000460ec8f0939ffda42190bb202dbb8523f41ab3e930746dd56c97ecb9d5e2e70bbcdb9f8fb87d02c6f5347b08c11157799877fd18ce11609ba230e67e2018a5af1cb480ab83021a9ac2064739b683b79e79249ce70d14760ccb37ea2e7661efdb7e737af4d4913fb431de04115b01b375d80cdb8880b4f8aa1f985146965e3044271c393cac57d05ca695ec568cdaafc908cdaf0ae3affa9e57bd7e14a258f67d3f584871f3570c840c4ee8069e1dfaac4d0cd7a6552d92fbc56a391f6b8dcda46d9aa649d5e3d9fb3a56bff835ecfc1587c7056aa364f4c1322206162102751f2c06d16aef7ff8a55f5bf0b45bac66c1dc4a09d5284d618f0a7ea2ff150ee9b518c0dbefdee7545388ed6c7ae7eefda345260ea3acd79a106da3837b696a7a69a87f55427be946fed0e0d98b1190471616bc9660be5c05c65274b23379f9f5c365a78c766d77e7e8f00ce88292b93f24247df8de2ef850f27de4c61f096abd64f3420c21cab21f46c9a4c0d8a42a31812b988f60d511d31208058109c6c15a5a7b0303b47326fa70bb79c4533d7a206c886b3fb472b6e642d9ec0b745ebe1ed6532e83ea62d56de3fc6d98b09e1b77743786e1fa6e3369a2db40e842408f7da53fc38100c48048e0323f5b8610461e6e22620a448408e330b2ba35496e5edfdb9fd68931101281ba0d6b7d25bd903e5c257a7feea15f7a5044643070454016228acc6d8a62034ab4c9137fef1ffca3126b55adaa6a18dc115475a44bbde0aefed2debe3f2c3d3300fce11f7d6bb9ea89c001dfbe75a79aee8215308d31825a92306819afca8714e1aed25dff53e5f00886cc088e865cb3ae061b61699272598e21465df17271bab77730d7256f18bf9d9c367efbe1ba415f63ce78cdc3491b64089213229a317814ee075d0e9d508c4110bc0a815094a08e09b5200230fb28bd5ac2c02e22cceb75096bedf4d1fd8e85d58727648d7845fc9062078d74b29b576e94aafddcfbf826afe76d3d0537c4ed435fb6b5890bed2ba36030d8b07cfa89fd0f96b6f8e0ccd185bd005f8a45ff53e683e89e4504527b787272f3fa0d7024201abb65e681595581d0d4c0f460561fde7be5fee1f164ef5a35db611baf40911873299742e8f131256c7c4cd0fa1111c87e251428d5236c5c731967cea2c8bcef3078d5eefff217bff60b3ff753ddc9695bd7ea584cc9618b68bb949afeaf9287370758f5993820b2aaaeba2117cf398ff8f061d81089ab6ec0a4f83d9e913fcb8953630a8858862cc236ac524a6656b211d1d1d1511d53e91629a0aa662d5794942e86129b8cd845c4ccac2823250984230b89a2709f73714304578b21ecce2693ba11466612c6401b740a91208410420822b29655b84a10e68a34f882cba22bca5100db4af5231aa65bb4fcf8e7e735152e36d2d73bc26a6736d9db998e41b2d03997b851507e2c39fa9e6eaaca1c524a8bf9cacc000cd7a3a3704e6888543d0f1d01fcd8673e7de3da1ea30fc330e8a0aa6bd4ff153ad84ca3520f123e72fff163fc554b9c105dad1b4a76e014531ba46625da99b4a4392b7e70aa2fdd3a6e27d350164e6866c5cd2f07c0fed5f3f014560586bc64ed5d9a6fbf79bfdebb313f7b1042023055e7188a83484444b74234d2fae2a6a0b446438e2a9c8f07f3bca605da2efd3557eb55f5578058ca699dd3b2d5a7dc3bac147bf638f2205b9285af38ee7959d4751e864a4089c81ddc89011cc605b1adbeac7743c391ce0ab8a8708d42d9415523714461270202d3bed33e684a01cd4b2989422677054059bb6157428f52d3c82b4d868e84e4e3e4d348604a1b79061f830b00d231b5bfb26e3af23a8e5e971cdd88c80d29a2c148843dd2c8734455604546c4000534a776f660f0455f7677dabc5aa93949c8a5444221671f0cd0a55e9e9edc9c317f6cf2daad3b77eedc93dd8f8d648f00e436c20d7893c35f06d02ae02c832b33b297520a7232e49070d99fa520274e874bdf4dd83461a199a110521e0af8e8c349dd55f5e641dcfbc2271f9c2ebff4d56f1c9ef6379fff74361f8601a15c6e699be116854714a3394eb59b4feb88242b10e24496c586cc61f481d5b82e6d941715275cace62945233ecefd575f7ced53cffee4240437640a80b8e856d3a6c6f580b07dffcdafbfac060ffefa1b6f0d59ab10a56adfbffbc66af0baae4bb1ab7cce4397757575e38234d78784008f91553a33270e3df830acc64a2f233880884c9bba8e71a56066c330884cf0a1ee376e998ccf65ceeb901e2f8b38c6f5fd30710637f0a118b3a18329642d19062f4e604454d0d195d474c8eac9d7880f22e0b54e2630fa850fb20f6f618e5b85e1da9333a0f9f71a1f6dc855dcd0ef1fcd67b31922d288838291c3c989b6713e04a1dde9f4b9679ed9d9d9b973bc322ba55fe4b2216c19cfd31524966a4a1cb2b9a167cf0418aad69dd4a88aa3ce2f9cad86e79e7efae4c1bd20021746f7361d873ab12adf3898fdc48f7fee68b104ae1f9c9c3928e1e5f32a88f2f09f3d0f09d7012b9b56ae0056dcd968441ef9e550114144214043273358ae5647274b910c41ae8284fd153578e4175e7c99435cf5cbe73ffec4d1f1efc9f4a0cf4b217ebc91be81735f9ebd5f5aa8bfaac17155d7c791c88ab18142b1c2297a5ea11727d6618804a0452444aed602a9e7bbd68070455cca0e4404fee8f7c4b1eee5a33b367433effaac06c26c0aa51410f3a20cece8ea0660a8c5fb5c4c8a19afeb63e3ccf038e6a104eb7a1e6eaaeb0c888f9f883543ea3a9c1fc7f8d047b1273cdfe6d8d4f8d670ac0b554f7753d5f78ee64f3f0d8d04576557614412555f37c31dc19cc09bc437f6a67bb366efa0ebfbbc5aad4a29e62320173f249a453314290604c6604daaaa66da177fed9d4309f134af5683bff3fee1b337f693c480defb25db2b11a1f6022642cfdfbcf16429ab2e5fdb4d5bbcfda59bdae806ce8db2002256b19e56a16debf70f97afdcbe7fb2ea152020f28675c32f8eb2981038b88289887a3e99afae1dd43f2846fe111a3cf443eeb3d675f3ee9df7dbe9ce12fcd222fcc3acf29c271d5d89afe3d28770948d70cf9fe0a62e31fb20a0b9f88020441123249332142071cb6d1dbc2c25d682a2600a703e75c54d8967d3b31a4b68a39b5df71836055ed81ad5b6f04644069ad50c8a1a99f9a88e0a4623dabb38302238abe7625a4cc55d1dc08d615d7aa7351a12091ef9bfae026d13a093218cfaea382a333e6c316c3021f8a87ceaf69b6fef9facfceed1d9279f98820e96070e95ba338751f50dd79ce71610804180649272a25c9316df0ec09aadd98a2febde1694a4e0e45611cda6d3aa9e1e9f2ddf7eff54b5041624fae0f0f89b2fbdfeaffdc467cbe208802fd41ad6ec37a10a1cfb3ca02bb1d795ec35c17dd4ecb82c7f663a2f0ebfbdcdaa6a77366d9b49c99046c56a446406bd62c9a913d2483d49c25ecae97c894fee8216f841bb7d5f06ef0ec0c2424329b76fdf3270228a2196413fbc20bf655fba10305fecd23faad1790e1e839742f190580c14415898f1c1c29e8ca19019420afcd4c1649ae2a9955e35061640b4737ffbd0e09dcf9120216e6a08e7002de7bf3e920312113388aaba4121432214f6b12b06e4e308242172211ad563601c5a0620079331ba37a775c76aec18230322393a5d7a3269749fe888048e84088a8e7eeecc3cf4f0bc51293e5ff31b453516eaef7cf0e0d9ebb336558be13402e6a2240f4914d6082233461536ca393239260f7e8110f60abfc04662ee4c9418775322c2b39cd1414b4eb146e63e5777ee9f1dcd870947211c49a9ce5f5f7767e418233a46b262aaaacc94733eff551fa9c9f3231e7bbbaeda40d154579dae7a728a92d441876e43ea7db17a0f4484632d510158558f4f4e8b3e8300ffff327800e8ba8eabb69baf428cee1e85ac94ed7cef8714de6953b2c3f377aea84e5f9a1d3c7ecba6b5a14520e441e185ffb7bd2ffd91e5baee3bcbbdb7aaba7bb6b79014174904294a8a69c9b22539b161d8806c5846622448027f0d903f21c897fc1ffe2023016201810d23ce062f8010789160c981e455f242caa4487327dfe37b6f667aabbaf79c930fb7babb7aa66a661e25c27e441f0c06353d3dddd535f5bbf72cbff33b2fbe74e3e9270cd980401be7ec68bf9a9db6da9f497455bbb24df5aae3f59229a24300cb6c90cd528367287aeb326c9b1e3330b4763ae0266830cc4e631e1847edb4f6dcb3a50884cc9443fb36af99bd89b675acd7c181553d1ddada1b1aa021c386bf4880d66e62d0366b6ef42108d1882881bb7b32bb3b5d16fb9e8b11070fb22094aca59b0b879bbe00d5a208b0e9b1b1de92d896276296100cc83b7206e3d1c880d1809d475982465175a172e4feeed5373efdcc47bd355996bf2535e629cb0679941a00946509005932a80ca543eadde15badd8eec523048051a0c20740ef28338c102cafb03434f2b9bdded6164aeb3a1a02a83d7098ff3e9b6714d1a6b3d3c964e29c3bd8db6f9aa5a5385846b2b3303ecfaebba010d5bb1c6c7b5eca84c90480a2e177befb521452623354896939ffd0cd1b8e39849061d9e166ac3842685dfe4697c19a536a9df3d47524bf9695df84f72604ca8884466084b0e1ff9ab4f41874eb36f53c1e14c978b5436f31edd8ce7f196996b4cf5e04d086c1d3a5e20f45b6ddba34b11774afbdf9ceedd3259595223a22bf6e2d250440d53ca419196855bcdc7c656e2e33f57e79479cd52c980120892455055362ef1d6ad4542b8072f1fa9de9ade34546756625afd71122f2de11a1736ced082accdf53e66f9ffb22ca8200ed19ae8f9749c4c088809c9989d68eb42a7deeed3bcfba35ca53faf207b7dc94e69886fdde0f28e0db7e0ce772bd7db19c3192f304e7dabc369536d8dee43bb5a5f39cf9219add1045cf39e7b06dba20f6efdc3b4672c0ac60ceb9e0e8b1471fc9fdea4dd378efcfb7730c51c16998149ce70aae5d5fe78991b20e1580121a23e6317198f376a6a046d6861099862daa627a41c3dc058cc3b3b1120ebe02f68937e63c387bf7c6ad5bb7eedc4bc64df67d206992bcb96dc6150318a1988ab5445d72cc0ecf485f9c55c2c8246222225452414126f2dc24313366ac0a8f88278b45547ee1d5379aa659c97e6df923499606420c491a91e4bd23c786804cc8448ef3c1fa4b318f855f0d7d59a78f824b6051928230a3674212d0e1b65f74809cbbfa37b45103f800bbf4751d8bc203408ce23d8381a92252e9a111132b1b4bb5a53a351597820a908194a7ce70cb945ce5ba36adc8f946345e1341d7b7a602e07a2aebea7bfe914c3bddcc798c0720209b4cd926100063025ece7459c2a4593af6a7528d0af7f441fdb1037aeed4d128f0e216f88969cd2e88a8a676e493370463c2009665e17cd464468ebde9dcc098d0239029133ac2dc1ab41159d27cc09947a0eb52b866a97252e206095ce14940000cc93b0293d4442a19922705e004ec4d0b4cc955d8e9c3dc02330a10625eb5d1104cd1c89c2081734280cc0020c93c3bd5e495442504ef48d56ac0a8a02128c65a981b77f0fcdbcb066f7dfc891bd7c62ecdee82730694d6c40730948634aa1baf32036000ba1ad2ca03a19cb063c751cc9239f44c4114129047b13c8f159449ab821b8357ef25fefb5b4f3ffea1c76f54589f366969ae6cc8cd1ba8d49c434622e7534ab15e109167b734ea2febe26a75c3ac09dfda5e12cf4909092d13a1cc30a6dab545fbb3218033b4826c393de4bd99fa29c092716c708a4408aaca8466eb0d8c1f78c0ab4251f8a64921b80dda890060b15c0297a4a2cd1241279349735a63a05e6af170264f3b7dfdab04dc657fdbb3716962cf665a3735fb2a14d59ffde50b9ffff8632e2d7cd8bf77ef7452864f3cf5c48b7ff2bdd3a54df68f2069eeac06680710ab4a94c4b020f384e8d039523423505263efc1580dc44c1444cd30b76e77ca3e5ba76a2dddad4d9e430ea803464c7303f1ce012368321306510d4860c48604046a140d92e8908cf72afdb1ce3b022908809386c4119833326234f06aa0297169cc16d800103c912748d6246202446616d15b774f462c7063efdaf8c0a55a1440240bf3202273a1ae74526f5df995925d6ec7ebbb7f1ae2020919cc81794304f656e734469be2587d04207bfdce4cf196823d7a342e43159b2548f481c1c64d8acbba21621f4a4724312d620c14b7ae89ad9d970ef265f38486039107ef0039aa89281104c6b41a07994f654d01000155056201cb8a6385e32635b0d1eac34d9fec076087270255f03e27314145323d4c90d1f940e4bcdb1b17f3e9491863e98b04b11f991b26499b3c6a73661dd6dd56020dfae3fc3384f36e9226250dce178553e265dd7cf58fbff5a3cf3e3d4a4d6d72fddadef4def1c71ebf79f417cf9b1fcdd4079b8b1a6acabc3fa2ec39421eb09e1b6e8ddab95e68909a1801d931052647440e21b5e3cffa3e2f03182922129212309a2111117b078406608e10595401c811d531129b6394dc1ee899d8137be88c70dafac8663927d78d1e332995998994c8013904cd2582061a516a2730227957152ee5ea5deeff30a2e3d9e2e5d89819f04307a12256ef4155a358525563356268faa38f81f0d5cc92a90121810248ee225cb5e9e03ae5b8824cf493374f6b7dfd8e213f7a34090e4816a8718e85f7219f4f4ac91489281415c9626041a42ec16e7d01a3513464a37ce73128013181e85a57bbe5779a99ae1a1390c8108dd423edef8d6b4d390570967b030f7ef3cc6aae1d80c172392bcb31ac6acb45393e3e39a9027ef8b1476fbef2eaf152c4a04bef8601a6cd56c5653bf36c9b5ff5e4e72f08682befc1ac8e8998c1b4288a26a514401b6bea850a0476ba38fe679f7af26bcfbff34e0365969e5c01368f3af7de270c097d224403551241470004152704f49ca3744150040505a4339e88ad4b7d0e2867b41c51ee2c25d0885eb950f2622c2b7ead118cc8c041600030ccba4ca0b9097ceb9fb1566e3be30159eb50287a450f04460e8893090208d2d84104f2440e04cc44a2aa301a1268526466ef048ae37af9cabbf385def9e463d73d7349e0083d4a14128504a630ea9ccb260bd89f24042076002c0a6006048441c02996f99fca06daa2040d8c010d7151cb6b774e9a24f3f9e1e3d7f70fcbbd824c92aa254b426081093c9aa29924f497b8a85d6937c4bde0084d4198b470ec1c8a4893049db3d579acb21b880046e6908c9c02b04368e6370f9eb07393e73f38317c8c123c0340532fca6a6c00512126f9edaf7cede474e6cbaa9ec793e5dba7a7a7428547c83521ea6ec8ad3a677f320f73e964d515866d32d1c0d6cf870e2707b0d31bba759e4d5355553b960514808e17cdaffcb7fffb1fffcd4f4f0a57d70d07ef159eb8b1fff0eb7767a7c8c000e2bccfed3d79920e01160663f607053322110b9a236042d4480aa82a4924491e7bea0873107b3e0021405dd57bd424577412a24b32621e39e70892e64e4c0013a9176cb9d6c7084068fe2caeb771bfa2df2858beb8f94a15a463c62aa06732c006d113b1214b0235df2e41201aa30a5072e070c53f6176c9cad3a52e6f9f9edcb9fbc88da38f7ce8fae138704a26b56760cf8b467bd3fe43a3a6989ca8453347c8841ea94ec629652169db00c7384bb8c6baf00ed4debd375bcc16ef1e9f3e76fdf0dadee860c481822b904025a69492909959e98bf3e7733637899bff0b6b8da0260c299a4aa693648d16b4cdc5c5b62c0988c648661c091c2a2c96d7f7f7d02b6c559d1e0c76ed95009f9df9c562515595012ca20ad26ffeafdf592ea322a12ffda87ae7d6bb2e14653149b306e04a0a6d9d2750e69959270ccb09e75ede1e747408b1b3a204cad00542334be8fdcd873f74673a9d6280e974e4912888d95e453ff713cffeca7fff3d181f81283823cc9bac212023ee911cb01d780c844698b2d68a49721570c9409ec4490c21388aa8c24403c9056b2be1a0943f10012355508f294d3c3984a4846c882629c672cc0ebccfe33dc0b3f344025b7a58dbf5ed95276c79ee447e03de7730f1563a74a84a18101d031940b16f82396fe03cfa10bc34de92599e780f9ada7a980188e271c2e3d76fbd79ebce630f1d3ef9e88dc3f118d4eaf962e4870627f4c7f00c26c4c2e808d9145100d46b2440c53c2483366137429599f9ce09d23cc697dfbe77fb78b1371a3d7a404707fb370ef7c6de2b21382e7d200288f519b76f557484732e7dbe926303010560a706aa0a4cce398dd1dade84b5be3d909980b1b1182a1823dedc1f4fbc438c2b09fcb6c9f20395a537b3b20c49458c13d097fecb97d957256131999c4c179af8d5b76e9fccea31f8bd106a902d48afd6595ecd35dc24d8c110515b6e29106c817c888137549c53558de88b103c4b6ce2623e554d465ffab5dffa0fffee175d3d5bc45837a972c098fee54fffe8ef7ffbd57a6998121053e6d203a2693179c85507cad6202051cc141ad50826440a20919682d13801abc482faf38bb9357ffdb91c2100ab73e3c34337de5710213023cdcc1af448040c092189224254488aa67846feb323bf93c72d6cb8be99065bfbc95ccbba96cc18150596dcee87b50146d0a63e5e2433f6e4a1591807424435355d1172c8cc8498d09d884edfbcf7caad931b07a3476e5cbb7e30ce4fc3f31cffa13e5f554397694ca8424a35a18431e209b65d03a6406b88aa52928428defba22c534ab368f393c59d13f16f9e1e8cfccdfdbd6b87937159602daaea7bca8dd9c7dc62d4eaead225441455d56903828e59c092360da2cff5959c87d376a22b9a092991733524b0f8cc479f0c00eb8975adc6fe07ac2c2722ce91a932c3977ff5d74239215f788954557ffa37cf4f8f9b5994fdc3438929363504d70bd7e1bc3d6db2f41d62e8d00d349c5c242466e6e57c111c8e46d5224a28c727cbc51b6fbefbc481e362e4b870d814961ebf36ba7e78747232ad6314b116900600f0dc1bc7b7e6896469298173468c68a052682d6e8c6adeeac6dcbdd9025d284380546f93ffad5b6bb02ed19588995fbcb74cafdeb166ee08c43001390444f451d181f34060a3f1d1f1b489c6e8cb9c94da5c37835e0e12adc2f8af7de79571e9b459a201baa00a88a63192ab810a06f3d2a01b4fa5a450901278ca1a924ced04d82c398c8882685800148de8f4cef2b5775f6193337d8e6b979e71286917819d66f26faa1d0770c59de92c547bd8c9f193ad0a69e49d53541391760c2e91182eb1c298a6f7eadbc7eff8d7dec6cc4e1658c7f0675268ba3daf7e1d519a006a5d95a1569c36e8ca8a0da1596642787e47b57666330018112a300732a5044f3ef1085bd4cbca4fff380dafd2b2df00b0085b5a98ffd5dffcdde3a504d292d2cb71f2fcf3cfcf66b3aaaa9a1489a82ccb18a3676f9d6ed34dbb09f29098e15072ae975e0234a87575864b93ff3005aee627ffe9dfff12cc4ecbb1c3e0a6a7cb49d88b085fffcb975eba3d13246be6a3329c2c6aac0e0810d59032bb333902449498a0a0f381220080b921aa4ceff1d0a81df35e9bfaa1c3038792524ac4776733702e7375baea5759c219987b6b7543ffd3dc3d7bbe3dd120419f1a8cf69594ad45420f95752067b7d58ebef5160364b5f3236857c7fdc398c9a0ffffd2c7321a7a233373084905910d40448d108145844d3170ddcc0a4b5ff8ec673e7273bf5e2e8a49b0656422e728701e4ba16dad04f9c1dee19b593d19174d84affed11fd78b1919bb6a5494072f7eeb6fe6f379166fc984ad6e13451794d60a5ad899821c21019801c33097fefc23368cae5e55bc52117df8dfbffff55ffa859f8465d469b35795d162a9f8b94f3ed1fcd54bafdc8bcb70508b8daa60cd49702e36828e9d0fe6282918901014dbdd51eb4f4a28bd09b5b5d37ba6dbb761eb3d7f6a12aa924502434da491b546555acf26b1b6f6c6987bf9125c94ceebe755f60c27d0a21ff054f78287365ef3d6af9a81bbc949df4289003a30941264089cfd8f74e7bd778e57cb59b73df6cc40785bff89812d3400a0236440302330a488603594753d9f8cca030f370e46713e9f8cfd723123f059ea7be5d11380c20720693719790378fddd93ef3cff42351a391792e1effec13766ca4494859fcc8c98db42f62a8db4f6b4d749f65e0cdb70ac0ebd6db09d915d2d0becfcc82edb24ff2af3c771f1c2ad3bbff7cdbff9e2e7fe495a4452495a5be283c27fe1334f7dfb953b7ffeeaf1dd9938c4bd109aa4c0044831c618231087101c5314dfebc229f40b180e6df52ef5036f41ead822054014c204b9179fa3c25a8e2adb6a209d5cd1abbcf8b746cdc00e1f06b6dffef9ea4337930c108786209db9eb3dbfda66d46d9ea33d12d1b9c67301e05b06de7a4c1542115024ffa86aa600a0262260cda40c36bff7852ffeccc4a2772c4ded91c48019fdead3a9ea99f97c0faa4b0f1697ea7ff9bffe0639cf96a2ca77dfb8f3fabd25affac3629e5be45cae79f24a919a88b2fbddae02ab963218ee99ef625efbd0debb995f3c6c80d56120b55859fac28f7dea273ffdd17abe006c341ce0723e0eb868e45e2abff9dcab2fbc7a6b7c70add6dc2002906ad0143ca169ac6bf57b03243fbd0a23707dac3c744f28035edf9f04c29c305fc4c60c67b159c7295b9ec5d018e62b437d7513602fe06d2026c701c4ba81944ba4cb9deda11060db21bfd27a71a977d0d3009b432dad1b49804c5c2893211bb826c5ebb03cdcaf7ef6273e99a68b60ca04a2e08a122479ef9d6b39f65dfeff830ef8f4b56f7ee72f5e786dd134a5a3871f7bfcd7ffcf57163c76d2aca52939f89cdbcb733f3b7a317471bcddab517b01a3ad790f00000ff549444154c87b9d85f3be43378c8f8a0793bdfae4643c2a4c17bff8733ff589470e8b144f9aa6280a48d159436089ab2915bffd877f751215009c73446e2dd8262205c4ded063d3a57a21d8ce70bcfb0a96c6a6555994ce13581e4a2522b398b20cd6fa7365978a2f7ba35e809d5f80ec2c2318ba652d1be6cff502bba7acab3f78c0778bedab199867ff4aafb022741f542edb8e5a89b9f7b6088e99bff8f94f04504acdd1ded840968d3602ecc37ec076321d3c1845f8ab023e01fcf27ffeb2fa7102421ffee06b5fc7721f7c65b1cebaab195ac9348f25b5a4eb592aedbe44b8126ce8d9e1adafea767e15581febf00edf7d91f596a8cef96425056358c8ec68bff8994f3dfb89471e3e2c16c94feecc9ad2e1b502d36206c5682a6e19f5b9175e7af9f5b72384e4470d141148c9075b0ce41aec5247ba7b5c49ff6dd1784415475c78f6ecf2f4bb949211c518730f99d17ac6ab31e055f6f3f583f96eeee94180656f13384279293edf834e6b2fd8ae9672c3de8d5aaff0fa17bcf2faa03626948acc695d58f3c8b5c9331f7dfce1870e978bf95e3962d39393136136760e43e9b974705edefb8300f86fbffcf657fff00f967503e56489e59ffdd577a7d36908c13b072b79f3cc5870ce39e740da3eca2ee6ad5545efc3f680930f97696040df24a3ee83191bcca46a400e9944224a3c28fd679f7df6c79f3c72e5081953328d4b07aa9a4665d9348d51f055512bbcf4e6c94bafbd7dbaa8818b2609dce7a6da0b7be17e58b2b46c4e4620a23238cf2e49e338a4949a14b303b56efca2812eb1a17310e84f920d25ed909a01b4502f78fc801e443db420de27e08792f97a5f19bee1c703a4ebd70e3ffae823378f420070d2784c843655f340f393d9e4f0080b379d9e5e9bec39d15c6538ef373df080ffad6ffcf58bcf7d9b2c2dacf8fa5fbf38b710c80a4a4918574d27b91d5d119aa6c954c77c6b6ea048785f801fccd875a4d4cfce12ebf01fba5dee45b2baa0055ae050880623216b303db53ffeb73fff53553a4550087b5305447420b97ce5f24206129c0780baae9d0befe5129f1faaa5d6fb9cb4b905cdcc1c63195c4a29b79dea4a575ed772ae9771c8cfbdfb50b94b075c7aee05c6d0e33a909ca373d9f88b4142d6efa29ff1a43a493ebce03cfb5ea73fe9c896d4da09b651db9123aa6a0150d0032bf1ddd3bb0f5f3f7269c108802520c203a57a7325c0ff8faf7cfd85375fa5a4756ddff8ee0bce8f3861e38d657bdac936c2ef6b7027f477c8f46fdd0ad6fbb746675dfd0c7b56e82dd1375cfb285ffcfce73ff3d4c3364f88b5715406b4110064a91bc5f51058f39d36cc4bafdb854b7e7fb69987c42ad1d8859c1c35204560e6342c2536fcaefd1eca60dd7e3b47d81184bc4f6534942b3af9ab5062e8f53be7d947ca32b375263f6bd45c703e3d9eff764fc4fa40ea7956f50babd921040fa29edd95cb72afbcf28aab3c813efdf433dffcdecb66d6d40d1725ae4685c3b682da7b08ea7a8bf0431dc6ebe0fc0ca85a1919586b3523aeeab267168efc8ec14aeff5ab7ffe97ffefcfe38ffdd0273ef7a9a7d000a37822c82a4a1ab39026132161d4d0ef55623fd47b199743a91db37682c9f9df8eb8bd2599591524a5a80a00ee3eafb40d791c5728e96d9df67d87ed979d68972cd4a7ca7df675567df43daf032bcc5ff4a170eb4d571f9088bb9182ad74478aa2c8fd8ead26da038bf6ab025ec10c21a5b45c2e0140623adadbbfd7cc3c873380cf9857845eaefb999bfe8284c7c559faf371feaa4cadbdd8a6ce50f4ae056325b7403d51fdeadfbdf4c77ffbdd27afddfc173ffbd9d9bc21064672ec094d3599c424d187fdb3fb21e63a73ff46649a063efec07536ebc269c31494f68a917700e09d3300e72846b9df951500acd38a6370a5f8f36c847cdf88b7ab6fef575a50b6b6f75c22e94401973badd65bab9355e8712642ac0a0658939f1e64b85fb53d56c50198d9dede9ec4e45c1563ec7a35b8bda0326056b58035fba51539b8bfa0f79c40fd6aceb9e9f96c5d2692f7f6d50ced638b7ac965a0aa308106c197a3efdd3efed2affffef53d7aecb1c79e7af223378e0201a4484c1646639b2f07e2a24b76f8336fbd1d6762ef42d07d5c809110104421c548442935288c749ff7de15f6f3abedd7fa83c37b0ba436ba06ecb9a05ba1907640b73ab6551a62a5f6b5fde4fe48f6ec469dbd8395ffd81538c5733ec68395a8bb6fc05f3f3c7aebf4ee887d53d74551a8a0c4e4471ed40c72a464b9d76cd592cdbdcd6d760e81971215fa77785bcd4b5e93ed6023627f01dacfa40946d726d3e9d4d55a204b238d4f5c945311107eebef5efbd6732f5545389c54a3aa20305079f6634ff602b21b7b771fd7412dba8b6e979ee91d44a3b228bc374990e2e1fea8f4150d4e767a0f081e7821a34b17a91f945db9bcc79d73e69e5cc356e582efebddd97587145c3d20f9c001fea11b37df3abe13bc7beeb9e7524aa630194fa6b26ce7905babf547edc8a64e9ffa19aef8768cbddef9152f873a9ccfe39d8bc9f3c9a06d4b919c131b5b9fd8e9e2b42c42458e0c14a8318d224a785ca373a50b550df6d6b4c19385732e787ee95b7fdb5b5a77d62fb67d8671b0f94e3684edde8fbc8fcd8d6b47fbe382cd0ab4bdbd8f900902f17deef0da91e2b920893870a7e3fb0c80fb7d55fe7e5fe0ea7f6c679f8288f60106bc239a542350a84221223e948bc5c28a6d018a0dd1bd7f271f4acb7735a1ae1ec37771be76c032b7f12ad9f2565d1a08cd96cba52366663371c0be2885b21a939981010173ca7ddc1c7a819da0dfa1e83a7e5bdfc17a05f9bb0ad3dd83a9a609b982824b0d9a42bbacdeb76c225abf93fd20baa65bebd8056bd6f7e7656c2b087e10f6fa2b01fea9a79e7afeef5f5a2eea6a74f0e4934fbef6fa3b46888e5156205c6df217f8ea5767aa6cb99aabd5a40d41dbd0209755a0053bb373c4cc4d6dbd7af5dd2473f7dc4af46a009e85a94ec93315ded7a753efb99d22492b1d6d3000f5b21c9a9cd17528d64d72dd187efbf9ba51efda4cb6030538f3783e48e81ba36808807e835505bbbfbbfc0706ec7f3cbbdb2618b9efabd19fe33fff51cd36de845d35cdf96003fec987c7dc2c6872745ca7c70ef6efbef1dad420e091da0211d1c43927602262c8446ee53ce28a54bb6e5ba23389fa750cbc0ec5bbeaa26cb49e05df62c07aaafd6028c924251f7845386f9bc6015b396d58a7173a8b88aed436412d1083418cc2a158e58270d555b212d06667db0930eb6bfb5b3fa8db8fc370496c3da5233b1559c626f34614c013f95a7c485ad132191a30500310fea1eeba7f44773bfd40cf8a2efdb00f7aaefe4a8b6208e1c31ffe706ceac23bcfa4aae36a349f9ee63b35294431332bcbd23bd78a20bfd725f0e234dec5bfb58ec1c04cabab6c77fdaa1b17e6d57a875e5f7a7cbe5368952276d9bf3062440edeedefef1745e1c0d05a3e1b03c1ce76f67e001e107fe29ffe38699466c1683ff2c39fd2d4ecef8d145001c9f95016a2502f635dd745080371e37bf439af58a587b50e4c27781e52c2bb20b03f931a84e16a426f82edd2a4637f54bf3da04e911469fddf59cca74826922ca68a3d00484af880ce3adad903007880eb87a3476f1e15accbc57c54169f7ce6e9c5f19d22cf48125105220a650100b9448fb655453b8fa28b817d71d67a68131eea8eba80de7bc1227231b7ff4cf5810119707d908fc9944c196cfd3d1f749fbcfe9e477bc09aa96e64abeac5a42c88d44c9cda43070700804cb403fccede3fc04f8f4ffff9cf7f61e4d9b149ac4765d89f14a99e1168197c4a4d8c11009c736b0190eedebeaa9669ffd790778dd9875024cbdfdbe184abc7cf3ee10a85fd2b7a161728f0c015065ac385437287169accd306a676b02c1999b2ea630fdf204726b517391a4f000069e7cfefec7d03bc2a1c1eeced57ee739ff9e1cabbe9f4d4b4f9d4c73fb65f16522f2c2d4dd43957d7758c310fd3dd3064ae9a8deffbba109fbd7bf59a66dff593af0ef22b7281cebd42d636b23cfa767d70b1637fde1381559798aaa209ab06b4ca41c15c37333539a8aa49709a49de3f30dacdce76803ff324029508d2fcc8b39fbc76705095a5c538a98a679e7ae2da5e19984de2b81ae51e83e962bee59c5fe678de9782c2c55be550d9ff7edff7825f5d654c7defe357e1ababaa2a80e4f1f2567ada1f15877b456a6a00adaae2f1471e863ca20db204f6ce76f63e003e19102310aac47ffdaf7e3ec6baaaaa937b772acf1f7fe6e9cf7cfa87cac24f672712535555ccfc7edc899766c8dedb767d29202fa6945e9a78effdc30b9c8e358fc87b3f2a8b834975fde080409d73defb2a3024131583ddf6beb3f784a3ab6cb0069062f49e9bba0e4595007ee37ffeceed7b27ca4e1544a1dadbe350bcfada1b2fbffc4a5555e88bf962e18a92d8a79488c8111303118988aa66f11b5b31f5983a5b62b7f10ed1009c732262082184d5746722a294d26854cee7f3c96492524244022faae85a9a84f75e9ae8980d536f404ee47a23f02c3bdd13841bf5b6eb754389330502e8d790b3de6c2259321366dedf9f5cdbdf23534dd103d58be3471fb9f9c42337478c0c79c4b5dbddbb3b7bbf009fc57c52ac9df722d628a2a33ffac69f7cfb6f9fe7e011b94ea24065354264450821dcbe7d67bea80d018199592dc5bab97efdba8864f4c24a95dc085d07f0d6f5c6cd10d17b1f45cccc3927a644945525cd6c7f7fd2344d5114f3f93ca534f2553265ef44246710358943ca73227a24746c403697ac7f0cde4a20a1b7de3e04f84b1d93f5bb4c462511419bcb346b1a47302aab5141d70ff6afef550e0041cd2c0f4bc0ddfdbbb3f769874780d82c7d0800d034894358367afbdef1b7fef4cfde7aebad508ed460de44f6018cf6c6c562d98829b9b661de21a925c256c17e4d59850e116dc3905f11e9d880887c0829a5bcb7ab2a3303103323da68349a4ea7de7b030d214814512d8a62191b55f5593c7725bdd4b3c343cf6ebc96d082619d7cb8b04c78a9ff3ff4b78bba29cae0105422aa38c44959ec4f2637ae1f548e0880379e3ca901ed10bfb3f703f0ab7b4bebe5b2284b00984ee793c9240188c2eddbeffef573cfbdf8bd976382bda3a3f97c8920c47926ab4b29a5948a109c2391ac13967b5d1c0000211149326636c2aded91d0196619dcec17640dfc3ce526232484309d4e7de0a2282693c97c3a53b0f178bc5c2ef3f399599324e817c9639401cdbcc19afc00e0f582fcdcd51566720b90a4c644f6c7c58dc383a38383d211b6c3f714360a2d3bc0efec7d037cb39a0f0fa0a62d3b3dc5885c1808534bf97ef195d74f66cbbbc7a7b3c53c45b97b721a93e60db3283c213631ae076e02a1699ba08a2aceb956fd1637e3387386df7b9f23fffc605114aa96cbfe6559e4256005600380c3c3c3e57c2122c8e4bd5f2e9749fb936a66d21fc393bb14f05b07241723fce2bce0daf6aa725496e3228cabe2605c38000248d27876eb745ffea03b6f7e67ef23e077b6b39d7d306c57cbddd9ce7680dfd9ce76b603fcce76b6b31de077b6b39ded00bfb39ded6c07f89ded6c673bc0ef6c673bdb017e673bdbd90ef03bdbd9ce7680dfd9ce76b603fcce76b6b31de077b6b31de077b6b39ded00bfb39ded6c07f89ded6c673bc0ef6c673bdb017e673bdbd93fa8fd7ffd41d12fd4b6e5390000000049454e44ae426082
);
INSERT INTO `foto` (`oid`, `fotoname`, `fototype`, `fotosize`, `fotocontent`) VALUES
(2, 'cabezal.png', 'image/png', 49668, 0x89504e470d0a1a0a0000000d49484452000005930000005e080600000056c48a3d00000006624b474400ff00ff00ffa0bda793000000097048597300000b1300000b1301009a9c180000000774494d4507de0c16162c2bd9447f910000001d69545874436f6d6d656e7400000000004372656174656420776974682047494d50642e6507000020004944415478daecbd7b9c1d459d07faadaa7e9ff7cce41dc81349206413203c12411e8bac72592f1041976710115658efd5ebcaaeeb2eeaaea26b10f52ab288e0ebaab00806919760e4b12cf2882142104212204c32492699338ff3ead7fda34ff5d4e9e93ee7cc64129250dfcfe77ce64c9feeeaaaeaeeaaae6f7debfb238542c187848484848484848484848484848484848484c43ec3bc79f3609a66c3364248ecff8410504a1bbef3bfd18fa228233eaaaa823106c61814450100f8be0fd775e1791e6ab51a6cdb46ad5683ebbae177dbb6e1ba2e5cd785effbf03c0fbeef8fc823ff887914f31e45dcb6b1604fd369767cb49c71bf8b1f000df513fd0d40788d1863505535bc3e9aa641d7f5f03bff4dac5b9ea6e779b1dfc5738e16ed1ce3791e8ac52214f9e84a4848484848484848484848484848484848ec5b188601c330c2ff9348d72831cb0949f13b278af94755d5f0c3c964fe9d310642083ccf83e779701c27248fabd52a6cdb46b55a85aaaa701c078ee384a433271da3f9628c35e40940f87f335239a9ece279dac168496571ff7688d4e83e51f296d765f41347eef2eb2592c8aaaa42d775689a167ef83ebc0ea3e47534fda4bfed94b1d5effc7ef17d5f92c9121212121212121212121212121212121212ef043889c7095811ed2a7ac56d71c4ad48e8c61d2b1e232a8cf95fc658984771ffe83e712ae9e8794643fab6b3ef5895c9a339ae19912c12ba9c70f73c2f54734749780e917cf77dbfa1cedba9abe8ef7164304f3baebc71eaf2666989e94832594242424242424242424242424242424242621f83939022e9174594108c539fc6d92d246d178f15f78b6e0702b2387a2c21a4813816f71595d149ea649e8678fe76486671bf768ee1fb247d6f270fe2f5896ee77f4515b2481cfbbe1f2abac58f585f51f238eedc71d74c54092711dd71d7b399fab85d75b6e779924c969090909090909090909090909090909090d8d71049c92462344a84b6b3bd956f6e3b7613510532273e45c258247655550dbd7e55556daab28dcb77d417b8199ad9664495b8cdd4b971c7c5ed97a4e2e56a64fee1962022c1cc2d44f86f2241cdeb48bc6e71f5112d471c49dccad222a94edbf55716cf21c96409090909090909090909090909090909098977004904619c5ab919799aa4524d522bb763a140080983f5016808e0c7bd7c29a5304d13e9741a9665c1300ce8ba1e92cd51e2b719613c1a7fe47648d056658c237093ae519490e6aa6d4e183b8e834aa5824aa58252a9844aa51292c88aa284dfb97a99a7d58e42b9ddb2b7a3441eab3a59bc8724992c2121212121212121212121212121212121b18f916473d1caf6423c36e97b929545745b12a92b7a25030849641ec44f5114e8ba8e743a8d7c3e8f42a1806c360bd334c3e071edaa82e3cad76c5bbb6ada76afc168f297740d6ddb46a552c1e0e020fafbfb512c16512c16512a9550ad564710cae279a39621d1f4a3961c491fb13cd14983e8efed10f249f7a42493252424242424242424242424242424242424f631e2ac09e28865711ff137cff31a08df762c2ef87e49a4adf8618cc1f77d30c61a6c2c344d836559e8ececc4c48913d1d1d1817c3e0fcbb24610c9ad48daa842583c7f3b2aeab1d67b333fe5b1a4e3791e6cdb46a954c2c0c00076efde8d9d3b7762c78e1de8ebeb43a5520909654e2ac75de366d7a999bf75f4b8b8fdc6e2a72c4e764832594242424242424242424242424242424242e21d86e779a1323549291b470ec65935448f69a550162112b93c3fdcea42d334689a16aa91bbbaba307dfa744c9a3409994c06a669425194d0b2a1ddc07ad17cb57bccfe0491c44fa7d3c8e572c8e7f3a1525b5555f4f6f6a2542a359491db5d885617629aedd874c4dd2349bf27e57db49064b28484848484848484848484848484848484c43e06f7dc4db2b988aa739348da246b8be8b9da81a84ae67fb91a9913c913274ec4f4e9d3316dda34e47239a8aadaa090e6e9887f93fc7b93bc8293f66db54fdc39c78b9c6e5587dc1a445555188601d334a1eb3a5455052104dbb76f47a9540afda6b97f729c7772b3b2350bc0379a407ba3f55ee6e79164b284c45eeb1480bd3199b637d26d2b4d1f68b3eb0121fe3ead93f1a983e47cef2ff753d0718c6baa00da4b50610a28a3ef48d929a5a8542ab25191909090909090909090907897f0097eac4d449ce540304e6cdc67b488da5bf0e07294d25095acaa2a4cd344a150c09429533075ea54e4f379689a0600a1ca56cc6752d992f2d06c9ffd814c4eaab7b8f32b8a82743a1dd6270fd0e7380e6cdb0eb7bbae1b7b0d926c42923c929bd571b3498656f74c5c1d4a325942627c9b7c70728e90610210c4077c024ec7faf0e1fb54d8a1a1351a6e48e027fd5c7fa8f78c00e5c7c7e5d50301830bca80ce8e3cd2a9340e39e41078e18e4159837c52789e8bcd9b37a166dbd8be7d171c8f82927afeea69ee8f443227b8837af4e1fba4e1727af0414040880755a148a78d864e72ef8208339314d3a6cfc28b2faedbe37a1c26f549fd6e1c2e6f507e0fd9b401d3d43169f224284cc1cc19872293c92476c4e31100218c8acb281445452a9546a1a3138b171f874b2fbb0c7d7d7db289919090909090909090909038f8988488ea34ee6f74df56c472ab406dcd207a252b8a02c618344d432a9542575717264e9c885c2e075dd701044472343f4944281f4f477f6b87106f77dc39565576b3f4e26c489254c2aeeb8664712a9582effb2897cb18181840ad56c3d0d0105cd70df7e3eae4560aeda4408bed12cbd1ef49561871bf49cf640989f16ffae1fbb44ed0f9804f03c2181e7c8fc0f529267628a856ab701d1f73e71e8aa38efa2bb8ae0b4aa30d2885a6e978e67fff079bdee8866968600ac3b69d3634e68774b58f80000d08610fed2a4c871b0280102f20127d5e0602d7a3c8a5084e79df49e8ecccc3b66d282c2086d39974445d3cbcfc85328623e6cd0600b89e878ece09f8cbfa9770cfaa4700a282d27a5e41803dc8f7788313e9bc4e836b166c2b64159c78e28948a70cb8ae1394931200046c2faa74833a26505515e94c065d1326219feb40263709575c793518f1f6e85e0d3a05a1ccbe0fcf23300de0e4934e40c632eae5f3a1e93a28a5f03c0f9e5b0625042023cb4ec6e7310265142ad3d059c862caf4433075ea2138e9e4f742abbfa44848484848484848484848481c344c428c354512a11c8c5f492c692b7e3831d9eabce25f9e765495cc18ab8f8329344d432e9743676767482473ab862432b3dd6080e2dff1b6a468b72eda4d4fac7f5ef628112c92e68c31a45229747676a2afaf0f838383705d370cc4c7eb1088f74e4ee632c8a814c7ed2a92dbb1cb9064b284c41e354e1185a7cfb77b007c280c78cfdca938ff231fc18eeddbc29927d7758219289581321a10ac75d07a437de69967425155504a61ea0674d3c4b7be752376f7d970fcba82363c1f1926459bb6390115ed0bc7c1f743bdb4ae02975c72013485a05a29a366db20f0e0ba1e5c00c56231e85c2264a2a228a0753f2542080c5d43ad328479f3e7e1dba7fd3556fffe61fcfa378f01a0f04040c2f3d37d6e2d315c47a20ad98757f7f1601498357b224e3bf554f8be8b6ab55a6f44f586469b12ba576d1f28a148a5339834792aa64c9d8e6c368781217b8fcbedfb14a893f89eef83c087ae01c72f3906f3de3313e5fab29bb09cf58e4c55d46046baeeedc40966dee1f119d5b174eed17bc94aa53169ca344c9a340dd95cbe5d7f15090909090909090909090989838067f09b064f8b23995b9182ad6c0c44429493c9a26f32a514baae2393c92097cbc1b22c30c6e0795e834d83a8508ea62fe63f9abfb8ed71fb37f35f1ecdb87334fbc49d53fc9ea42a16bf33c6609a2672b91c32994c18884fac9f766c3c4653deb1f867b7b35d92c912127b884655ab07cfa330351f73e7cc44674716272e3d11bb7a7bf1ea5f5e6e68501863a084868a5ef137febb6dd760db35504a51ab56c14a83f8e427af452695c1af57dd834ad5c51f9f5d079f28a0c46b492873b52b1064d8f701df0334d5c7cc43a760f6ac43f19ec30e43efae5e0c787c562c308e0ffc7259b8c485102a44760dd4d4beefc1f7877d923ccf43b552c1e64d1b306fde113871e94978e20fbfc7868d9bf1f25fde04211a0875c135adfbca4f39a8a3401d0df8f07c0285fa98336b3aba3a73983b77360801fafb7787e51489544551a028ea70f9697b84b23f0a6b0c422954554567e7044c9b7e283a3aba401943ffe0ce3d2b777065e07b04847a9833730a264feac2617366a36657d1572c86cb97544585a6ebd0340d8a12f86389647252073f964e4e4c43d374a433194c99321df942074cc3aadf189251969090909090909090909038b81047f42679e5c61d1b2594936c099a6d8b1f3f8e0c08a7691a2ccb8261185014251cf78b6a68cff3e0795e385e8e1bfbc5595f3423cfe3c69d49caea3d198fb63a67abb44452392e901e27e4f5fa389b07dfe39622a32d4b3b3ec7cdbeb74aabd9bd23c96409893135f880688ce07a80ebf958387f2a16fed502a42d0be54a199b5e7f1d94d19094e5041d630a544d0b978b34cc5e7172153e1cdb81e70f4776eddbbd1bc56211272e5d06c330b168d1423cf8e043d8f8561f182380ef352192877d913d9f80f82e0e9b3d05c71ebb08aaa2c0b66be8e9d906a628d0341d9aaa41370ca8aa0add30c12883a272229545bc9b01cf0b1a42d771c2e51e9eefc1755cd46c1b3d3ddb70d4c245587cccb1e8e9e9c1638ffd1eafbebe0d8c069620ed29abf7ec9af1b4097c783ee0bac0d4c9699c78dc31c8e732a8d62aa894cb0d7560982634dd80aee95035158aa2d6af1b0b3b8476d0ca67594c27b0b8d0d0d1d9858e8e2ea452e9502d3ce67287d71eb00c86bf3eed24e47319d8760da5d21098a2c0322d58a9144c2b055dd3a11b06342d883c1bdca7c10408a17b2b784140dca7331964b239988605aa50d9e048484848484848484848481cc4fc422389d82eb9dacceaa29582b71959cdd3e564321faf2a8a12f01aaa3ae2dc7ccc9ba4986ea6368e6e8bab9768de5a95652c0ae6d18f5fc9887345f322d613176ee9ba1efa50734e88abbca3e9b70a62d8ae8548d2bea30dc6c721c96409895137f4fc9b1790a03e30ff3d53b078d122280a43b55a45ffc000144581619a300c13ba6ec0304d98a6094d331a4cecebcd44f42c705d174e9d98b5ed1a5cd781633b70bdc02a636868101d8502aefcf815e8deda8dc71efb3d5ed9f076487407f611c2d2927aa035e2fb983c2185f3cef95b0c0d0da05caec0f63d50ca90b2d2302d0b8661c1344d18a60955d5ea79addb1b3016f8e646e0f93ebcba8d87effb701cbb1ea57438ef00e07b3ea64e9d864f7ce22a6c79eb4d7cf7fb3f80e3b25059cdeb78bc4965914cf77d1f2af371feb967219d523138388452b91c445bcda4609a16ac541aa6195c3b4dd3a1282a14355065b350954dc7f1be1aee382865505415e97406a66941d1d4fabde0ed41b97dc0f731ff3d3370f27b8f435f5f1faa954a709e5406a6950ace6759304d0bba6e046a744a05e29c8c6b99633ba5faa48baaa8a04aa3058c8484848484848484848484c4c1c52fb4a714dd13d56d333f639e7694188d2a6c59c34ae546f254fc9e1414b01d9b85a4fda3c470b3407dad821226a5ddec5ac48fb31b496d511cc655ca3c0f8aa284843127e3391fc4097bd1e73a4eb016a73e4faac3766c2e46732fc6955992c912126d37c622c1e9c1f3080a7903d75cfd717477bf85526908d58a0d455561180652a90c0cd384655a304c0bbaae076a64ca3d8758d3f37975ab09d7f3e0b96e03396bdb369cba517bb556c5d42953f1f12bae0408c1e73fffafa8d480407f2b84957383fc1eb3e8482c38721e7ab6f7c0751ca89a0ecbb490ce64904e6761a5d2f5652b7cc6918231da482226c99f1190a29ec78dffb9a97c9067af4e840340a552c1a4c95370fb0f7f8095fff955bcfafadb28959dc4e4f7f4baf941a4397800661d3a0927bff738d8b50a0607aaa12a379dc922954a239dc9c230cc40455e9f810df24531a60c8e7636b4be844855d5d0a3aae1fced9e9210f0490f46084e3bf5444c9b32017dbb7783320a43b790cb1790cde6ead7dd845e57ce87131d099e4f7beba5894f581022496409090909090909090909897703d7e037254c93c64fed585ab41a7fc58dc7a201e538a92c0ae2a2e426b7b788060e8ce6ad95d2b6551d88798bfbad55595bd9492429b693d4cf62d99960611a577eb12e451b8c567ecbadae555421deeada8f468d9cb45d92c912126d35ee82e7acefc37581c913749cfd7f9c858d1b37c0b16d30454126632193cdc2b238211990b2c14c141b49ca0eb702230847de10a908085ad7d5e1b84e03b1ccd5aabe0f94ca25a8aa8a7ffae7cfe21bff7923fa067d305a5f6ee203472d988de38f5d04df77b075db36504a904a057602b95c1ea974a6814c0c55a809cb4546d451f83b03a3f54a43a068d634ad9e772f50293b4ea8607eebcd3770f9159f407fb188cf7fe1cba04c6ba8f371b96e019f0ec7a338ede4c59871e814d46a15008065a590c9e690c96491c9e6c23ae0447a787df604a33c9ec590a9a3215787cb1d0415541582f79d74022674e5512a95a06a1ad2e90cf2858e8048b6d28d2472cc729dd1bc808cbd9a24812c21212121212121212121f16ee21afc96c1df4472354a524615b271c462f47cadf213552727119e7c7f4ea67232594c27aa121e0b991c372e15f39214c02e4e791d17f42f4aca8a8aece8f6b8eb1125df93f2215e534e28c7792befc978ba9d098568fe47738f4865b284c4a81ecaba4d8407b83e70c6a98b317bd62cf40ff403004ccb422693453edf816c2e0fc330c3a50b2221d9b48168e2e94308834259a890e524b26ddb7562d6a97fb7a12a2aaebffe5ff13f4f3d893beff91d28f131755206a79fb20c3d3d5be1b91e145545369bab938979a4d399ba958312e6336e46b155c315571e8552f8be02157ae8a76c3bb5c0b2c375e0791e2ae532745dc7afefbd1be72f5f8e9aa7d60f6fb4ea18d375f351f764f6f1d1e5674255192ae57203a19acf7720954a43d78dba27306ddb0b79ff7b19092c4eea3c322805feee82bf856d57e1792e2c2b85424727f2850e64323998a619dea744f0c3dad7e08af5869706ec7d5b0d090909090909090909090989777e1c3792806c461ec791a3491ff1f766e76f39c607624955d1d241fc3f2efd3812583c5712391b974eb4fc71dba3e78cab8b24cf63fe9713e5d13cc5a521fea594265a73b452448fc5966234131363bd473924992c21d1f46141e03dec0796110a7371e947ce81c208fa0706a0aa2a4cc344aed0815c2e8f4c2617463625746c4bf5db219c15550dfe2a4aa050761d30a684e4acebba38edf433e0ba1e9e7f612dce3de72cf4f46c83e7f921f1ddd1d9856c360fcb4a41d3f4bd4e247a5e4062f34652610a6cdb86ebba705d0794326cdef43a567e73253ef3997f44cda108bc9fc774e5e0fb14dc299ac0c7b18b8f84ae6bb0ed2a74c340369b4347471772f90252a974c335e39e45ef14c4a08c2299da4e9e08f1e0fb81bd49ca5071ea2927c2b66bf07d209dca2057e84047472732995c489e8b131def54d979c0810395c4979090909090909090909090183de7e08f2ae09e781cdf365675a978fe68fad1ef71e33442c80855729458e6e74852ec2689d744f2369aa724d294e747fc3f69bf38d2396ad9114d83072514cb192597a30a6ebe7f33c5701c693e9afb27695b330fea56794a4a4f2a9325245a3e945cd94ae0c383ef7ab8e4e2e5003c0c9586609a26b2d93c0a1d81ba97139294b5ff588d95380b1b36a640a50c8aa78051d6601f3130d08fc3e71d8609133bd1d3b30d841064d219143aba50e81856a5f2fc7212716f138984903aa91c587fb8ae03d70bf2c06810f0edea4f5c8695dfba1d4c5540309606352092e10394f838ef9c3391495b2897cb3074131d5d1390cf1790cf77847510bd16ef14a9291aef8fd633c9f7510f58e7c10770faa9ef453e9f82ebba48a733e8ea9a8842472752a974c3048258e67792d0a56160c3188f685fb6491212121212121212121212071befe02706db8b92ccad88c324efdc664ae156dbe2ac24a27917cf914426c7a5dbacec22ef21eedf8c6710f314e7411db5ef68560f22992c1e172d5f9ce5864828c7d58798e7b17a26b7ab346fc7377b34f7421854503eba1212710f5363d036d72758bae40850423054aa40d70d140a1de8e89c804c2607cb4a81d5a39a8aa6ebad1efeb11277d1633c42a05106c61c789e0bc70d02f539b60bd7b6a1aa2a0cc3442e5f40474757e00dac1b20f588a21cede67dbc3a4d5e5f8ee30c978d511cf557c7e2b39f6658f3e226fc7ef56a10b46b3b1cd86204360f41b0bdc3664e45369d42b95286a669287474d615c979988605a6280d046e523ef7a49c711d425243ceaf414347827688e4e172fb3ec129271f878e4206b66323954aa3a3b30b858e4ea4d319a89ade70ad7947c9efc7661deb7823aed31deea83cc12646b64b1212121212121212121212072b07114f80267d4f1a73361bc72511cbad8ee3bf25a9599b7da2bec33cff51923cea552c92b02231cbb7d33a97c1b991b8fc89fb4649ea24eb8c562473b4dce2b8565470c77945b7e31d1dcd47abb1f49e8ed9471b8c4f2a9325249a3e94e0d1f640888fd3962dc2e187bf07838303304d13b95c0113264e463e5f08159ed106aa9d0772bcc85bc6d87043ed311042c15805a669c2efe80400a4536964b239a4d35928aa3aaa73379b2d6cf65bb4538cee47290568e0d52ba64329c5e2a38fc771c79f82b7de7a13afbfbe09ed48537d9f06360fc1a5c35147cec509c72e0c8864554347d784409d5be80809554e68273598e3d1408f252a6b14cd261db81d8b5f5763cf3f7c360e9b7308aad52ad2e90c3a3b27a0a3b30b994c0eac1e8157ec24c5b4e3cabb2f02e335cec8f20e582a9325242424242424242424240e5eb46373118d6994a4364df2e78d1bdb35134dc511db71fc409438162d2f38e282f2c59585ff2fc6d2894b8307ad5314056a9dd710ad24c473b9ae0bc77162f315ad0b714cda2caf511154127710cd4b2b6e68acf74ef45ced723aa32591a3bf4b32594262c48302a06e15e079c0ec195d38f2c8f918181880a66ac86673e8ec9a886c2e0f8d7bce02b1d604636d0cc60a4a293c049ecaa9541a9452a4ed2c28a5304c13866e8cb07468e79ccdc8d02443fc6669443ba828991bcee6c1c1f5d77f012b565c11dba98c3c871ffa0583f8587ac2b1a8554ad0751d994ce0919ccf17a0e9c608f2bfd5cc603bfb35eb84e3ae7352473e1cd995b5493ef3fb16c8644c2cfaabf9a8d56ad07503d95c1e1d9d5d2326119a2d618a060dd8d3fb7a34e02f03f4000e82282121212121212121212121d1ee98314a262705916b166c8eafb66d167caf99b2383ae68b53128be78ea62faa86459b88a432268d9dc574c5b1abeffb5014058661c0300c689a168e1945a19c787ed77561db36aad52aaad56ab82d5a2631d05e12c12caaba93c6aa713ecfcd94e049a4f45879a124c278bc82ee899064b284c48807bade008140575d5c70c187b1b5fb6d504a912f14d0d13901f942074cd30a159ea3b14210976e441b2fb1e1e48ddc681efc689aaaaa0d5b05d47f1355cceda4c7cbc883e68150502a36a83e7ccf858f60e64fec445aa51ded38a2e7743d0fb94c1ae75f703e7ef6b35f8292567ece3e4000d7a338f3f413407c179451a45219e4f305e4f2c31300cdccf8a3b390e2f5e281e2f62682cea9ae2066a451a1dba2fc8b161e816cda82ebbac8647328143a914e67a16a5a5bf72b2f2f2fb3c21450c6031332f8beb7179f3d0ac62874c384a2a98d7994361712121212121212121212120729440bccb8f15a338b8b241fe3243ea19962b6dd717c2bc23a89bc6e3a9a8de48bdb6132c6a0eb3a2ccb826559d075bdad95e19ee741d3341886815aad864aa5824aa5825aadd690761c1f13a7424e22eaa3dc01bf9e49d7b21dffe7e80ae2b87ada9b909ec91212a37e685027463d7ce6ffb9063d3ddd608cc134ad7af0bace062239ae418f03575c2a4c01a10c8ac2c098024a0392cef35c789e0fd775e0382ee07b705ca761f6ac1df0bc288a92e8d7db6e7e154501a50a745d87a6695054150a53eadb833c3b8e03dbb1e1d467fc6cdb86e3d41af23d1a6259dc462945b1d8877ffadc6770ff6fee47b13800429af93811f83e70d8eca938eac8c3d1d7b71b9aa62397cb235fe88469a6a0d4bdad7919e33af128814e2883aaaae135db172a5da628b0cc1434556f7acdc2aaf57da82ac3dc3933e1ba2e4c2b855c2e8f4c360bdd309ace9e72f07d544585aae9d0751daaa605ffab1a08dbbbe526848080405154a8aa2abc4048265942424242424242424242e260e41fdab72a882a77e388dce8f676d38e2348a3bf45ad1ea2bfc705e18b12ddcdac35a2e9514aa1aa2a2ccb82699a300c231477890ae3e82a674e3433c6c231bfaeebe1a7542aa15c2ea356abc1b6ed58b159944c8e96378ea38913a4b54baac7f948b7337e1e0bb1dc8e1f74ab7b5592c91212e183c183bcf9f03c8265c72dc0ae5d3b01008661a250e8443edf111292bca1e2641cff2ece44890d99a60584ac6e9875724e85a2a95058a0c0745d17aee7c0b59d8090751d542b4103e7d4ff8ffafc4467bda2e00d67dcef2382f809f90d8864159695826198302c13866e425114b07a7e2925705d0fbe1f10cad56a15b56a05e54a19d54a19d56a158e63c3719c86652e629dc5a1c1bf17049a66e0e5975ec2b76efa262e5bf131e13a355c3d70c2d127c0d18b16607070108aa22293cd219bcbc3b21a89e4a47a11497f55d3619a2674dd806e1a5015ade50ce87881fb40811038b6dda40309ee5d0f04ef3bf94418860ad7f5904e6790cd0641065bdd272189acea300c1d86692165a5a1d79710052a69bacfac2e78a768db4ebdc3f2a567b284848484848484848484c441c843345a5488e3a166febdad6c1a47e3899b94af24c5735ca03b7185b2482647d38cf3738ecb17a5149aa621954ac1b22ca8aa0ad7754365311fdb8a41f83881ac695a5d00471b04808661845ecbaaaa62606000954a05767dbc1d0da2c7cb266e8b532e8bf52ea61327ee4bf2bc8e2acf9b5de738efecd15cd7e8f776c7f932009f84446c835b5779fa40c62238f28879f03c0faaaa85beb3bc11131b972879ccff7265afaa6a304d2bf858c15f55d51b54ae4183ebd66d227c789e132cc3a896512d57502e97502e9760dbb5909ce5e714f3d04e2792442cf24658d37498a685543d609f6158d034158c29600a83e77a42791d000a144585a619704c1366d3e712ab00002000494441542d854ab98c5279089572099572198eeb347432ede47778462f68d83219137ffbb76763d5aafb621ab5e1c07b87cd3a041d1d79f89e0bcbb290c96491c964a1e97acba0839c44364c0b562a85543a8d949586aaea50d5408d4d23c102f7c5cb45d91f02a52ce6b7e1fb76e182c370f86133e1380ed2996c48a0ab9a165edfa44e9a310655d590c9e690c96691b2d2300c138aa226fa66ed4d78def02c2f5f7e24c5c91212121212121212121212ef06c405cd6b16bc2e3a5e4b522cf331791c119ac41fb422aee382ef456d2f936c2492caaa28c1ea68d334a1691a3ccfc3c0c040e87d6cdb7643403e31ee8eaaaa81884fd7eb6238167e022b50353c863186a1a1210c0d0da15aad866ae8382feaa46b13574e91171a8d7ff5deb877daf96d2cf99064b284047f804040e0c3033077ee0c64b3590c9586609a66a0f0342da85ab2370f272ac3464a51a11b2652e934d2e94ca8f2e5041d65b4ee851b30d8aec3fd8c3d380e052141e3a6a93a54550353149486864049355429b7224779831635a417f3cb412985619848a533c8d58948d3b4a02881dd005328fafb07502e9503e2dbf35029956158267cd7856e18b0ac143ccf836959611d30a6a05c2e35a894db0d76374c28333046b170c11178e081078609467042d50fb5c973e6cc84c228c0282c2b5df70bd647a892a3e70a3aad60e63393c9229dc9c2302c1886215caf204fc5e2001cdbd9272f119ee7a166573038348872a914c97fbdfcd4c79147bc07beef43558332a452e911f6167175cbc9f34c9d804ea733d0343dd8d7f7a1a80ababbbbb17bd72ef40f0ca0b87bf73e21763d0ff03c078ee3e0edeeada8556bb2919290909090909090909090382811155d2511c87102ac76c8caa4207bd16392c6a571fb4609e42432593c57b3ff8315dd5a48242b8a825aad8652a98452a9845aad06d775636d2d3839cc95c7b55a0d9aa6851fb15e092121d9cc03f9f9be1f06e9731c27248493c8ef24c570f49a36f3b36e17a389cfd52cfd666ae9669c44dc7d23c9640989e127023e8206f18cd34fc7e0e00074dd402613d82418a615da5b2445dce40d93a6e981b2379343be5080ae1bd0741d9eebc1f7031278edda17f1939ffe04fdfd0308d8391ff07d1c7ae8a138fdb453b164c9b128951df8be02d3b24095a091ac9419cae50a1ca79638a3d86e43c48f0d0ced4de44205760aaaaa850da06dd770fb2d3fc2ea3fac4669a85427cc290606fa91c96460db35e89a81638f3d069ffad435a8945d68ba0eaad0b0812f95869ace8226752c01c9cb4029c3e9a79f86ef7cf766f4f7f70bbf0ffb065ba68e8547cdc3407f7f408ae70b48a5d250553531d8212fbfa2a8c8e6f2c86673c8660b2189ec382e7443c38e1d3b70d75d77e1b9e79e475fb108bb66efcb9b139ee7a1542a0b4108fdf0bec9a553489966b074c734914e676159a9c44083a21fb4695928143a052b10b57ecdabe8eede86ef7def7bf8cbabafa177672ffafa7663b06c0b46cdfb00f54783120f44aa9325242424242424242424240e3a2a62245119258e93c6f449e3e856debb49bec7cd8e8d12c9ad48e5a434e2cae2795e48041b860100a16a98fb1b8b44b2683dc1bf8b84b26ddbd0751d8ee38479e5aa64bebfa228304d33cc4b5f5f1f2a954a831a3989c0e79f381b52be9f4848c705f41bd5b0788cfec8e2b1d1fb2bfa7fb3fda3e9493259424280e7112c3a6a368040b96b9866a050d58d11cad624824ed37458560a858eceba22390da63038760d9eebe185b57fc2472eb8100a63703d77042f4708f0c3dbef80a66958f98daf63c99263e0ba1e1855601a5678ee4a25506e4667e646034a291865d07403996c16f94247a0a0364dd8350776ad8a17ffbc0e9ffef467d1ddfd764b0e71cd9ffe841ffff8a7f8dad7bf82e3961c03020255d5e0fb3e1cc70eff8e25cf8105878aa54b4fc4030f3c348258f47d82295326c0f77c288a02cbb4601826b4fa8c63d24c22b77848a5d2c8e50ac8647221915cad56a0aa1afeef4f7f06bfbafb9e06e2fa9d022f37b7f6804f605a267443036341b94dcb8222749471f72aa514aaa6219dce22972f209dce821002db0e14c0cf3ebf06175e781918f51acacc08f6bde584249125242424242424242424240e5a1ec21b41448a63b728a28ae438e570dc31cdc8e2389fdfa434a27ececd14ca3ced244255b4ab5014058aa2c0f33c542a15f4f7f763686828b4b588fa4a473d9139a1ec384ec38707ecd3342df44be679608cc134cd30a89eebba2897cbe16f71f51ce7a11c472c0736a68d71afc6cbdaa25df571fb3c034924fae3fe9764b284048609420fc0d2134f846ddb505515e97406a97406aa3632f05a5ca3ae281ad2e90c728502f2f90e689acecf80c79f78122b577e13ebfefc0a18055c8f471f8def4ccae50aaefee4ff85138e3b062b565c8aa3161e09dff7a03005ba61c2f7816ac58347bcc4fcb46a7c28a5505415b95c1eb97c01994c168aa2a23434846d3d3df8f11d3fc14f7ef653388e0b42d09632b452ade2539ffa0c56acb818279f7c12e6ce9e0500d074a3ee071d54b6ebbaadbd97ea8a5c4a192863281677e3da6b3e89071f7c48f00b0ea4ab043efee6fda7c3755d1886052b95866559e12440f45cc39d801210c98502f2f9020cd344ad5ac5d0d010d6bdf4123e71e5d5182a57031215d86fd4b184f841d9a98f0547ce432a65815286543a134e7e24751200a0aa1ad29960022195ca8010825a2df0a0facf9537e28e1ffd122af3f6ab324b48484848484848484848481c4ce044232794e354c949d6144941ddc46d9cec4dda2f9aa698b6489e2691c122712c92a7cd2c1e443296e78ddb5b707fe4a1a1210c0e0ea252a984dc01b7eae4e78f720aa27f72342f9c2816eb82efab280a2ccbaa8bdf82f855dc5a33c90a420c9a287a25f363f8ef5135b5783d9a8ff74953157392bf75d23ecdd2897e8fde5b71694932594262f8c9834a5c64b21954ca6558a954a0d2d58db0d16a36fba33005a9540ad97c1ed94c606d411945a55cc297bef475fcf8273f81e703b44d628e1000be87fffde37378fa99e771f55597e3924b2e84ebba7535ad0ad70d3c880385f3e87d7728556059296473f99048ae562b00802baeb812af6dd83c6a7b01aedebded873fc3dd77ff1ab7dcfc1d4c9c38a1deb8123046e17ba4ed1933cf75e1ba4ed0d03305e9540a1ffce007f19bfb1f00a9fb4dfb0066cd3a04857c06a57205866104fed4aa161b30afc1e6c1ac5b99640ad0341db66d435355fce01777e286afad04255e4824ef47b76a784d2880bf3aea08f89e0fddd4611826d4bae771d4da8343610a0cc3443a93412a9506a514d56a0594527cfa33ff88df3dfa2854269b84bdfdd228be64f1172ad1e35a42e24042ad56dba7c149f98b7fbb13a98e13ace4d993340e36f041150f7443e4cca1c418fa323130b4effbd0346dcc4b78dfb9f72abf8160e08485ec8f71405d43312057f8ceab2889f16ef626b81292135bb27d9518cdbddcceb6387297ef1ba740168f6b76ee389b8ce8ef51057412911cf51b4eb255e0a42ee75caad52afafbfb438f64dbb6c37738f19912c7ba51629bdb4b4449e4682042ae84e61fcbb290cfe7e1380efafbfb4704e513d5db62bdc7d53fcfa75817add4e371d739eeba8be58e4b87973f9ab738cbd366931649ca644aa924932524c2c6c4f771c1f2b3c34645d77598560a8ab00422098cb220d85e2a8d6c360fc330e0791eb6f56cc325975c860d1b5e0f1eba51be47100210040addfffaaf1fe0de5fdf876fddf875143a0af5175d050e7541d1deec56c3cb9540a4a6d319a8aa06db0e66e0fecf73cec5ebaf6f02256353a5120230e2a1bfbf1ffff4f92fe03fbe7c3df2f95ca030a60c847aa3cab3eb7a61a3552cf6e11ffee11aacbaef6150560f82e7131cfe9e39701c3730d1d7751886d1d4e39a51064555615a29a43319685ae015acaa0c3fbcedc7f8dad757eeb73ebd9cb0f77d82238f9a07c3d050a954a1690654556dfad2ca2883aae9304d0be95470dd6bb52a4cc3c427aeba1abf7bf451e92ab1175f0e7904e249932661debc7998387122d2e9344aa512b66edd8a975f7e1983838361a46139f890381060db36ce3befbc3088c9de06630c1b366cc0dab56be1ba6e4350d62492e898638ec1cc9933a1eb7a3879d3d3d383c71f7fbc6ef163bfabae59ad56c3b469d3b064c912ecd8b103cf3cf30c18632d570d49488883f0e9d3a763f1e2c5304d33143adc7ffffda8d56a615fb6bf9761707010b95c0e8b172fc6a44993a0aa2a76eedc89356bd660707070046921b17f5e47455170c61967209fcf878494e77978e18517d0dddd8d72b9bccfdef52a950a962c5982193366e0b5d75ec3abafbe2a2f9244cbfb26e9ff38556c54b93c5aab8be6e34cd2d671ed04e08b7a05279d9f13c4fcfda45c2e6368682854248b6989421c9e5fd1ee4214e97022994fee44f31f27ec618c219d4e8793a5954a05b55a2db088acdb48c6c5818a92cc71db923eadeabedd6bd42a985ef4b8a8e82e4ea1dcacef9364b284441d9a0264d2260808345d876906aae4909c8ba1d87cf8a08482294ae8af6c1a566819f0b9cf5d87575fdb04b687c206fe0c6fdbb61d5f5f79136ef8ca17eb8d17c018057c0ad22653edc3070181a2aa304c0b562a08b6e7791e18a3f8c8472fc486d7378f9af84ecaf7e6cd6fe2f22baec2fff7d33ba02842745a10303a3af92ba30c44d751ad56d151b0502cf6f3c470f861b350b36de8dab03a97ab4ae2ae1d530255762a9d866906161180877ff9fc17f1e39ffe028c7afbfd3debfb3e0e3d643ae003aaaa42d7756875257d52dd12caa0eb3aac540aba6e84440b28c1fd0fae968ae4bd38d0618ce1ca2bafc4a5975e8a050b16a05aad8633eda27a65eddab5f8d9cf7e86071e78200c002171f00d1a44051527600f54b2a25c2ee3fbdfff3e060606f6c9f91445c19ffffc677ce73bdfc1534f3d05dbb613eb4e51147ce94b5fc295575e89c1c1c1902c755d1777dd7517344dc3fdf7df1f0e62de2df7dfb9e79e8b9b6fbe19aeeba252a960cd9a35b8eebaebd0d3d3834aa5221f528996705d174b962cc10d37dc103e7fbaaee3f0c30fc70f7ef0030c0c0cecd76d9ae779983a752afefddfff1d679e792680400d27aadf56af5e8d2f7ff9cbd8be7dbb7c2ef6e3eb98cbe5f0c73ffe11d96c1683838361dfdadddd8d7befbd173ffff9cfdb2293c7a36f360c035ffbdad770c92597a0542aa152a9e0f6db6fc79d77de89b7df7e5bbed34934bdffa29609cd7c939b05b56fb65d3cb65db2392ebd24f2384afe466d36e2d2e62b333dcf43ad560b896471d55b94bc1655c2e22a127e1ec65898ef24523b4a4ef3950c9aa6219bcd86c4b66ddb701c275cb5229627ead79c44d8b6b2b6485200275dcba48079e277912c1e8dd545ab6d617dc9c7564222c09429933065ca54d84e10f553d70da87ca95e8256936f575535087c66a6eaf6091477dff71bacfec3ff4261e3f7d240898f179e5f83dffef6619c7aca7b1b02a37192b8150848d8589a86054d0f7c9d6dbb86fb7ef31bbcfaeaaba064fc962a130294ab2eeebafb1e5c7ad147306497007f7475e2fbc2b243c2e03a25cc9e33076b5ef813403cc0036cdb8161e875437d6d38526b429d30a640d374988605c614789e8bc1a141bcf4d2cba0e4c079d153150610025553a1d509f4661d0063b4aedc364129ad074624b8f8e24ba1304736047be1c550d3349c72ca29b8f1c61b316dda346cdfbe1d6fbcf146f85212dd7ffaf4e9b8e1861bf0894f7c0237de78239e79e619148b453900398870f2c927e397bffc6518a9faa4934ec2b66ddbb073e7ce039650561405bb76edda67f93ff4d043b168d1223cf5d4534d9fbf534f3d159ffce427f1e69b6fa25aad0a6d21c351471d85ef7ffffb61a4ef774b9bd4d5d5851ffde847d8b1630776efde0d00983b772efef11fff1137de7823366dda24db1b89b6ee25c618aad52a4aa55238c05cbe7c396ebdf5d6fd9eb0b9e0820b70cb2db7a052a960ebd6ada856ab2396f71e75d451b8ecb2cbf0ed6f7f5b92c9fbe9b5f47d1fbff8c52f90cbe5b079f3e6866b6818061cc7696be589effb38f1c41371f7dd77c334cdb0afdeb66d1b76ecd8d176dff6fef7bf1f575e7925366fde1cf6392b56acc08e1d3bf0e8a38f62cb962d52e52ed1f43e6c1c4793962ad366ca64f13989530627792ec7f91c8bff47c9d9a842997b0447d5bfd1fc03c32b0b38915c2e974302376a9311cd4f9c1a385aa6a8cd44b41ca29fb1effba190505555e4723954abd590e0e6938d625a22991c678711a7d26e75dd9b8de59bedd74cbd9e9466ab73c491d9e104807c64252402a82a83aaa9a08442d30c98a60945510235acd020448dd0290b2c2e0cd382a230b8ae8ded3b77e0ebdf580946dd71cf2721c0edb7ff081b366e826dd7e0d607c094d0c47c463f94b18070d53450c260db356cedeec68d37de04db1eff0135858b5ffef22e6c7ef30db882b79ed809247da21da05257814fe8ea02e0033e81ae51a8aa12da8d1886d1a0261f912e65d0340d8665425575789e8b6ab582f5eb5fc10b6b5e3c105e3500e2030458b8601e0821d075037a3df01e57258f78b120c1f21cd3b0a0aa7ab8cf1d3fba034f3cf164dd835a62bce0791e344dc3bffccbbf84c4e1abafbe8addbb773704d888ceac7b9e879d3b77a2a3a3035ffffad7f1f77ffff798316306545595957a90dc17d75e7b2d1cc7c12bafbc82f5ebd7e39bdffc260607070ff03e546dbb0f1a8f0f1f0024298a3dcfc3c4891371e38d3762f7eedda8d56ae1b19aa6e1f5d75fc7f5d75f8f72b9fcae21928160f9e879e79d07dff743f29f2bb5972c5982254b9640d775f9a04ab44d7c886aac60927aff7e9e5cd7c5d5575f8d5b6eb9055bb76ec5e6cd9bc325ccd1fe98528a2953a6c80bbd1ff7a7fff00fff80a38f3e1a5bb66c69b8868aa2e0b7bffd2deebbef3e0c0d0db595d6a73ef529789e17f6cddffef6b747d5372b8a8233cf3c137d7d7de1e4042104434343b8faeaab5128141203644bc8b63489388dee17dd9e444eb60ab217e79f1b471c27297aa3dbe282ddf17815495ecad17dabd52acae532aad56a437d340be8c78fb76d3b5cf1c93f8ee384134afc53abd550a95442d2ba52a984ff57abd586181b8661a050282097cb41d3b4f03c51b23c4aaa27d97fc4d57d335b8bb1c4c44adacefbb4d1be6f373b46b66612120008f161591a7c3f502b699a06556d1d40843f60aaa2864ba15cd7c3576ff81a76eed8b9d7fc76fb068670efbdabf0f7575dd1a01ee2c4773b0d0dcf3b6ffcd7ac598bb7bb7ba1b0bd51bf81c7efbdbffe2d2ebfecc2b0816694252edd88368ac3b37f14866e60d2a489e13e93274f82619a505535bc76cd826d3046a1a82a3455af4f00b8a8d56ab8e9a6ef80eca73ec98d1d0b09ef5ba628209e0755d5c2c001841080d0d872abaa06a6b0903cd8ddb71b6bd6ac918dc05ec20d37dc80152b56840a9568b008d1df9a3f8bfc99e02f40e79f7f3e66cd9a85952b5762f3e6cdfbccf74f62efa1abab0b7d7d7d613b3c34347440077a5214054f3cf10466cc983122c85d52bb1e5501066d54fb01afb8722449415b2e9771f3cd3763f2e4c9d8b87163c339366fde8c9b6fbe196fbef926fafafade75f71f8f8c1e57a73366cc80a2282dfb66098903118ee3e0c4134fc4a73ffd696cd9b2058383830d6d8ea228235678158bc586550d12fb0ff9b670e1425c7bedb5d8b97367435f60591656ad5a859ffdec67d8be7d7bdbcae40913268ce89b47db0e0e0c0c8cb05ef27d1fa9540ab367cfc6cb2fbf2cdb578996f76254559af497b753ad7c9393bc94a30a5f715b12d91c1d9f8b6429276245bb8bb8e302ce64989015c9e05aad1606768d12b4d134e2ca26063417c75c621b11575fa26d85effb0d01f972b91c4aa552685128beaf4689e1e87bac180830eafbdc4e10be38a15df4b7e8b51403ebedad7b5406e09390187e14316dea14f89e07b54e462aaa1a283c49f2c0965252b7555043ff9c6d3ddbb06eedbabd9b5bdfc3934f3e854b2efa686819a11b46cbfc36343cf586cd716a200458fd873f80ee459f604280752faec3ce1d3b914aa7c286b6dd3cf306922a140a18ccfaf2f08098e9846918758f231d4adde2222e5d4a09144585aaa8e180b9541a42d78489f89f675e847600d9664e9f3ab9de9931288a1a10cb2089e5a69481290a142550b80681f72cf4eeda5d37e696cae4f1826ddbf8e8473f8af3cf3f1f1b376e1c6169a1280aaad52a5e7ae925f4f6f6c2f33c74747460fefcf9e1b24c91145bb06001aeb8e20a7ce94b5f9264f241807c3e3f22d0d968d507fb132ccbc285175e887ffee77f86a6692dcb62db36ce3aebac867b9910828d1b3762d3a64d6df91733c6f0c61b6fc492c9838383f8c217be80534e39a58148068062b188db6ebb0debd6ad43b1587cd7dd7bbaaee3aebbeec2e73ef7b9f0bd451cf0ecdab54b5a5c481cd4f7ff873ffc61a8aa8adedede8641bfaaaa58bb762d5e7bed35944a250040a954c233cf3c232b6e3fbd96d75f7f3d0cc36868cb755dc7a38f3e8a3beeb803dbb66d43a552699b50c9e7f3b11664edc2711cdc77df7df8e0073f18f31e1e047da594b635e92af1ee82a85a6d367e6fa6428e5315b733be8eda4424a52d6e8baa6f45129913c922991c2d47b4dc3c0d311dbe3d4a24c791c0621ec5953222a11a47e0467f8f1ecf49e54c2683a1a1210c0e0ea256ab25f214d17a8b96214ea11cfdde2c5db1fee2c8e3e83e496d99e8391d775ddab96f1863924c969008c91da680320a4551a06a5a3898a5b4b96a9631054c550042e0ba1e7a7b7bf197d7c627805dd3c1b8a7a156adc2b6ab608a02a628c132e316796e6c08861bd5be6211d8cb79deb16327dedab20573e7ce6998d14bcaafe78d5cca13347c1487ce3814aaa2c0761c14f239e8ba0edf0f6c471863a0848e48d7f3f82c1dc2194bd7f56018267ef8c33bc0e881b3d4d947b0f486d4cbc90306881d41b4fcbcccbc4e6ddb06652a76efda1548c7a54862dc3061c2045c74d14528954a0dc43021047d7d7df8c52f7e81471f7d74c4522ec6184e3ef9645c75d555e8ecec6c08dad0d7d7d796ba4662ff47a150c0ce9d3b0faa320d0e0ee2baebae432e976b398029168bb8e8a28b4690c9cf3efb2c6eb9e596b68975fe7c44952ecb962dc335d75c83eeeeee90b40f02e3d670db6db7e1e9a79f46b1583ca009fc3d7a7fb06d9c7ffef9b8f5d65b1b2c2d5e7cf145ac5dbb36b404919038d8d0d5d585a38f3eba817ce49328fff11fff81bffce52fa8542a611036ae6693cae4fd0b8ee3e0f2cb2fc7e9a79f8e8d1b373690486bd7aec5adb7de8aeddbb78f6ad58feffbe8e8e8c0ae5dbbf6286f4f3cf104befbddefe2e28b2f6e08e077efbdf762dbb66d2326922524c47b90df2fe2b6a80a39eeb83812b9998f72f4f8389236e93c511fe4380b0b91b86d16788f9789ef13f52f6e9617f1bce2bba438592312ca712a6956e70dc43ae601d1555585ebbaa094c2344d64b359148bc5d01e8d07578f23f9a38ae866ded649d731e97a477f4bf2348e92c562dae2cad87608e53835b824932524044c9d3a1540a05ad5b5ba4d026dae8e0afd67ea4a501f1efef2ea068060af5b2510cf836d5761dbb5114b374683a0810b66a7e063af128a35db47efaedd9851abc1a93770c132653f965016b7795e63e7326bc60c589689e2407f48ea07c10f95c417479e1e13f6f17d179699c2eadfaf06210716b1e0c387a2a80139cec9f918129ddfab840c7b6bfb7ed0013385e2f54d5ba132d9068c2766ce9c89c58b1763c78e1de1365555b17efd7aac5cb912afbffe7a4824475f087ef5ab5fe1e1871fc635d75c8333ce380393264dc26bafbd867beeb947aa590e12e47239f4f4f41c74e5a294a2582cb61c28f7f7f7c7f6598c31388e3362e979ab7e5844a150c0bffddbbf8131162e51e63eae3ffad18ff0c0030f607070f05d3fa07fe9a59770c51557e0d24b2fc5c48913b163c70eac5ab50aafbcf28a9cb492386891cfe73163c68c0632d9f33cfcd77ffd17d6af5f8fdededed8b641927ffb0f6ab51a962d5b866f7ce31b78fbedb7434b094208de78e30ddc72cb2dd8b2650b8ac5e2a8eda3f2f93cb66fdfbe47f9638ce15bdffa16b66cd98233cf3c139665e195575ec1bdf7de1bda71c8fb4922765c1713382eaa3e6dd52e25594af0dfa27f9b59aeb4a35a4ef23f8ef30a8ecb237f778c2ab3a3560dd1bcc4a52f12b78de3df61bf60317d4a6920ac8a88b1b8e5a9d8b6288a82743a8d7c3e8fa1a121148bc5704c260a1bc4bcf0bf5195762b52399af72495719c22397a6f444972717b2b32b9593b25c9640989088e5c7014060606eb16171a14a6346da8b94296f01715df077c1f7f5af327907da0f2f4e9b072376c204040696b7f1c1206eb0b1a56cfdd47242af1f1d65b6fe1c8f9ef091a665585aeeb4df31c6d24296175d538815faf644a693da0050163c16c2265ace92c1dadfb353b8e834c2e87d58f3f014a0e34951a0165c1640627d19bd5a5d871b8ae0b5555b169d36619786fbcaf0a2158b06001745d0f55c98410ecdab50b77dc7107366edc88fefefe585f2d1e1cac5aad62e5ca9578e49147306fde3c6cdbb60d6fbef9a654471d0403051e259e07613cd8b0a7651297268ee5d8d34f3f1d73e7ce456f6f6fb862c3f33cfcf8c73fc6030f3c80a1a1a1117e96ef46288a82975f7e195ff8c217502814421fc0b8e59b1212070b745d473a9d0ed5a79c807cf3cd37b16bd7aed87e5962ffea430b85023efbd9cfa2bbbb1b954a25f4b9deb56b17befbddefe2e5975f467f7fffa8976c5b96356e7db3699af8f5af7f8d471f7d14e9743a54bbcb551f12eddc8bcd08dce8bee2f76681f35a29945bed9be4319c44eec6a986e3f2172580c5f4a3ea5e91708e0bc81767a921be9b8aa42f17197065b2f8ccd76a3554abd5b06de1abd5755d473e9fc7e0e02086868650a9541aecc2781ad109c938cfe4d110cae2189ed7453b447274fcdf8c486e455ac7dd7b924c969010a0aa2a0cc380a6eb411033ca402969fa708b0d4fd070006fbffd3640f681dfa01f0454f37ddab4736995ef6023f691cd818f72a502d7754008851f31ba6f8728608c415118a8c2497cdef071a37ddab42ec4dff98ce2e6cd6f1cb0842a4d98418c969bd527475ccf0d094ecff3501a1ada37f7ebbb0c3366cc40b95c6e786959b76e1d366cd880fefefec4ce397a2d5f7cf145fcf9cf7f6efb1989037fa98bcec68f95b003102e01e6a4545c9087b8f38e26c0da685ebcc5201ee24be358cfc555043c2d16333925968f082b2d5acde4777575ed93c1885827ede6ef601884ad5ebd1ab7df7e3b162e5c0842084aa5129e78e2093cfdf4d37b658971525d8fe6fee35e81fc390903e2138b910000200049444154a9c6dc977c20b2a7d794a7c5a399f349d9b1d6cd78d443bb791ecfb66c3c11cddf9eb6437c196dd087b3d8728a4b7dc53a69c777fc9d8678df27b5b351e5db9edef7866140519406bfc8bebebe71b3bd89eb8fc4fc8e25cfdc966cb4fd51ab7baf553f17bdb792ce9d5407a33dae1d1042303838882f7ef18bf8ec673f1b966ffbf6edb8f3ce3bb171e3c63179e18f77dfcc8387f5f7f7a3bfbf3fd68a6eb4cf4a5481399e7dfa78b75d12636b3b4452541c0b34531a37531f372329e388e5669ecb49e4673312396a2bd1cc62433c074f47b4fa8bf31a4e22b7e3b805f1dd84b77daeeb868432bfd77900f45aad16c6c5e2f9608c219d4e2397cba1afaf2f0c1428fa2b8bcf79d4f7398e484eba26cd381d5161dd6c0c29d66f1c999cb42d9a4e741bcfa7a228924c969010c936c614284c19319b94fcb046084a1f7bdfdf22e605835012db30346bb0c5b251b2ef5e1668a40289a02e6e7a1c6582628d82f8513f60d27051e2ea426c783d6f78c9c99c39730fe03b97bf508e8c1c9bf4e2e0f92e3c8fc2766a58b0e0c8fa84842494c7ed8a10826c36dbe0950c003b76ec404f4fcfa806acedaa13e2aeb5699a48a55258b06001162c5880891327425555148b45bcfefaeb78e18517d0dbdb1b2ef96f375d555571dd75d7219bcda256abe181071ec0faf5eb31303000cff3a0691a4cd3c482050bb068d1224c9e3c390c3cb376ed5abcf2ca2b28168ba8d56a631ebcfbbe1f9e67faf4e938f6d86371c82187840132babbbbf1dc73cfa1bbbb3b3cd768eafcf4d34f0f03e8ac59b3060f3ef86018a15d55556432192c5ab4080b172ec4840913303838886ddbb6e1b9e79ec3b66ddb502c16c3c1643001a6842fa853a64c893da76118b103031e8ca41d858aa228300c0313274ec4f1c71f8f993367229bcd62d7ae5de8eeeec6f3cf3f8f9e9e1e0c0c0c1c749629beefc3a807667deaa9a7e0380e264c98004a29264f9e8c3973e6a056aba1582ca2542aed1171c49f035ed7c71d775c58d7bb77ef464f4f0f5e7cf1456cd9b205fdfdfd2defbfc30f3f1c575d75153ccfc3c68d1b71f7dd77a3582c8681a34cd3443e9fc731c71c8379f3e6a15028a0582ce28d37dec01ffff847f4f5f5a1afaf6f54653ae9a49370ce39e784f75fb158c4f7bef73d0c0c0cb4fdbcf07bce34cdc47a58b76e5db8e47cb4aa67fefc1886815c2e87e38e3b0e73e7ce454747075cd7456f6f2f5e7ae925ac5fbf1e7d7d7d7b7c5dc70ac330c23661fefcf9983061026cdbc6f6eddbb166cd9a7035ca68da3c4a29aebcf24acc9d3b1784103cfef8e378fae9a7d1dfdf0fd775a1280a745dc7ac59b370ecb1c762c68c19608ca1b7b717afbcf20afef4a73f61606000954a65bff5063fe2882370c51557c0f77d6cd8b001f7dc73cf88fbbe5028e0d8638fc5e1871f8e7c3e8f62b188cd9b3787f77d2b123808ccac85edaaaeeb23965cf3b6d010023b8bf760a55269bbededeaeac2f1c71f8f59b366219fcfa35aad62fbf6ed58bb762d366edc88bebebe515d13dff7316bd62c5c7bedb5705d171b376ec43df7dc13a6a3280a52a914e6ce9d8be38f3f1e53a64c09cfb96edd3a6cdab429243da24bbfcf3efb6c9c7cf2c9a094e2f9e79fc7c30f3f1cde5fa66962ead4a958b66c1966cf9e0dd775f1c61b6fe099679e41777737060707479481520ac330306bd62c9c70c20998366d1a7cdf474f4f0f9e7cf249f4f4f4a0afaf6f8ffb1ed334f1f6db6fe3273ff909162f5e8c8e8e0ed46a351c77dc71f5558a68e88393c77d8d7df3e4c99313fbe6386b8aa4be59d7755c72c925983f7f7e18a0fdc9279fc41ffef007f4f6f68eeadaebba0ed334316bd62c1c7df4d198366d1a0cc3c08e1d3bd0dddd8d679f7d16c562b161c97dbb69f3eb954aa5c2676cc28409a856abe8e9e9c19a356bc2fba79d771089f1798f891bb726094aa2ead438723889186e76ce562a67be2d8944162727e208e02849c98fe3e5116d2144e5725ce0bab8bcc405a38b23bfa3847260bdd918089093c5fc99515515d96c16f97c1ee572390cd6ca278c38092eda73c4a9a89308e5d18a875a2993c5bc2491c9494ae5382259bcd7a4325942628f08232a3cf4ef1c1127cea4b5dbe0f000740ddbf781677234dfedaa34785d8b7916b964020aca2808095e0c5b91e3c31d5540f695ca2564b2690cf40fe24010ed11e2037521b95a1f2451a68051716695c6de97beefc3733cb870e0391e1cd741caa4a85601b9f26efc5e06b932665fabe56ab51abababaf0def7be17975d76194e3ae924e8ba8e4aa51206fae3561a9452bcf8e28bb8fffefbf1d8638f61fdfaf56d11d78c317cfce31f0f034f9c75d659f8e217bf88279f7c12871d76183ef2918fe0bcf3ce43369b0dcf2b921f3b77eec46f7ef31b3cf2c82378f2c9271bd477edbe682f5ebc18175e7821ce3aeb2c4c9e3c19b66da352a984043e27fade7aeb2dfcee77bfc36f7ffb5b3cf7dc732897cb2d95a984101c77dc71b8fcf2cbd1d7d78773cf3d170b162cc077bef31da4d369ac58b102175e7821b2d92c4aa5524890a975db9e0d1b36e0873ffc2156ad5a85ad5bb7e2fdef7f3f2ebae8a2d05aa1b3b3130303030de7ecececc44d37dd34a2ee5555c5238f3c82871f7e183b76ec183141215ef7238e3802e79e7b2e3efce10f63ce9c39705d370c1202200c04b47efd7afcea57bfc2934f3e89975f7ef98027955dd785655938e1841370c92597e08c33ce089715f37b9e0f9a070606f0c4134f60d5aa5578e28927d0dfdf3f6acb0bdbb671e4914762f9f2e558be7c3966ce9c39a2ae555585a22878f3cd37f1e8a38fe2a1871ec273cf3d8752a9348294f07d1f3367cec48a152bd0dbdb0b555571fcf1c7e3faebaf47b95cc6d2a54bb162c50a2c5dba1494d2d0a643bca6cf3efb2c7ef9cb5f62f5ead5d8b97367cb32f9be8fc58b17e3631ffb187a7b7b410841a552c1ce9d3b71c71d77b45d0f471c7144580fb366cd4aac87b7de7a2bac87679f7d36b61ea2a856ab9833670e3ef8c10fe2effeeeefb070e142f8be8f52a904dbb6e1795ed8a6944a253cfdf4d358b56a159e7aea29747777ef755fd25aad86499326e17def7b1f2ebbec322c5bb60c8c3194cbe590b8e3be8bdbb76fc7830f3e88871f7e184f3df5146cdb0e07a8cddaa1e5cb9763fefcf9f03c0f679f7d366ebae926fcea57bfc284091370f6d967e3c20b2fc48c1933c2f68f0f625555c5c0c000eebfff7edc77df7d78fae9a7c3c1f1feb43261f6ecd9b8fcf2cbc3fb7ec99225f8f297bf8c4aa512f6614b972e052124f6be7fe6996770e79d7762f5ead5e8eded8dbdef8f3efa685c77dd75a1bd402e976b687f3dcfc3bc79f3f095af7c25b67dddb56b17bef6b5af61dbb66db19321b66d63fefcf938f7dc73b17cf972cc9d3bb7e139e0f781699ad8b56b171e7ffcf1f09aecdebdbbad9512d3a64d0bdb074dd370d24927e15ffff55f512e97f1e10f7f181ffbd8c7307dfaf4b0cde3e4b6aeebd8be7d3b7efef39fe3a73ffd29babbbb1b5640bcef7defc325975c82a1a1217ce8431fc2dcb973f1f39fff1c471c7104aebaea2a2c5dba14aeeb62686828b4462384e081071ec07ffff77fe3f1c71f0f97834f9e3c19cb972fc715575c813973e6c0b66d94cbe5f03d41d334ac5fbf1e77dd751756ad5a851d3b76a05aadb67d3fd66a354c983001ef7ffffb71c10517e094534e01a534ec83c5e76dcb962db8fbeebbf1d0430f61ddba75b14bdf6ddbc65ffff55fe3e28b2f0e9f8b8e8e0e0c0e0e36ec97cbe5f0cd6f7e33b66fe66ddaf6eddb1bee1d5dd7f1a10f7d084b962c41ad5603a514471f7d3476eedc89c71f7fbc655979dbb664c9125c7cf1c5f89bbff99b9030af542a0def718410bcf4d24b78e8a187f0e0830fe2d5575f0ddbc7a4bab56d1b53a64cc1073ef0019c7ffef958b66c593016aab7adbc2e032bbc4db8ebaebbf0c8238fe095575e91713bf6f2d8214af426d90bc41dd38c6c6e87188e9287edd85c447f4f0ac017fd88760d7c7ccfdfd544a2572c4312119be49f1ce75dccffb6633bc1f320fa26f3b14c2a9542676727868686c2b6be81a3a83f43490aeea8f773521dc57338c9569671647df47b945c1e8de54574f24292c9121291c6d9f7bde1406fbe0742949644f23bf9624e2841a04ca57c435b7ec923ca0e7f1fd95cd4996c9098992eda94941fd1a88a7d262350153d6cd82078428b694607f05e3df060dfee5db8f8c28bf1ff7eef16b003c6f28134041c8c5bd21a77adbd3a894e1881e7b9007ccc9b370f6b5f5c0748efe4716b4bd6af5f0f4dd31a3add430e390453a64cc1e6cd9bf74af0af5aad86e38f3f1e9ffffce7b16cd9320c0d0d61ebd6ad4d7d96bbbaba70f5d557e3b4d34ec3638f3d86db6ebb2d247f9ba15c2e879e84aaaae2fcf3cfc7d9679f8d534f3d15a669a2b7b7175bb76e4d7c213afbecb371c20927e0c1071fc4adb7deda96aa86bf9c7df5ab5fc539e79c03c330b07bf76ebcfaeaab8903274a293ef0810f60e9d2a578f6d967f1d5ff9fbdeb8e8faa4adbcf2dd3d22b4948911621209d4028820a460559b17eeeaae05a502c6b43d0755d7115d1cfb22ab0fba9a82845111b82202a224540ba3141248df4de662653efccfdfe48cee1de3bf7ce4c20b4dd797fbff9259999dc7bce7b4f79cf739ef3bc8b16a1bdbd3d6082374110d0dcdc4c81b651a346e1befbeec395575e898c8c0cd4d5d5a1a6a646136cffc31ffe401999595959983a752a6a6b6b6920d6d0d020bbbfc160c0e8d1a355995ee9e9e9686c6cc4b7df7eabca20b1dbed78eaa9a770db6db7213939192d2d2d282c2cd46c63b1b1b178e0810770c5155760c58a15f8fefbef4fea58f0b9605eaf1769696958b870212ebffc72b8dd6e343535a1aaaa4ab3ed8d1c391243860cc1c18307f1fcf3cfa3b9b91956ab35a8a3db369b0d4f3ffd3466ce9c891e3d7aa0b9b9d9afaf398ec3f4e9d33169d2246cdebc19efbdf71e1a1b1b61b3d97c64b25c2e171a1a1ac0300c323333f1c8238fa05fbf7e18397224cc66338e1f3fae99182f232303f3e6cdc3c5175f8c575e790575757541b5718bc542efc9711cfaf4e9131403de66b3e16f7ffb1b66ce9c89a4a4a4a0fc70f5d557533fbcfbeebbaa7e20d76f6f6fc7fdf7df8fbbefbe1bfdfaf5434b4b0b8a8b8b35417286613068d020fa5c972f5f8e03070e7459373558733a9d983c7932e6cd9b87d1a347c362b1a0bcbc5c9379cdb22ca64d9b86b163c762e7ce9d78f9e597d1dede1e104c733a9d68686880c7e301c771b8e28a2b3078f0604c983001a9a9a9686c6cd4f43bc330c8cdcdc5881123b061c306fcfbdfffa60ccc73a9ff923a320c83010306e0d1471fc585175e88112346a0b5b515a5a5a59aedbe57af5e983f7f3e264c9880975f7e198d8d8d3eed3e252505975d7619aaaaaae8f8dbd2d222f357646424121212549f85dbedc64d37dd8437df7cd3a79d2ac7dee6e6661415156982f60cc360cc983118356a140e1c388037de7803e5e5e50113c5115083f8a977efdeb8fbeebb71d1451761d4a851686c6cd49c075996c5d4a953f1db6fbf75c8f029ead6dcdc4cfbc9b871e33072e4484c9830016d6d6d28292951f57d767636060c1880b7df7e1b6bd6acc1b061c3f0eaabaf62d4a851a8afafd76c939191919833670ec68e1d8b850b17a2aaaa0a168b25601fb5582cb8e69a6b3077ee5c0c1f3e1c56ab156565659afd8de338cc9a350b63c68cc18a152bf0cd37dff8b0c13d1e0f060c188069d3a6c9e6e6fafafaa0e7e68c8c0c34363662f3e6cd3ef30dd99c23f1577c7c3c22222202b20e8976f3a2458b70cd35d7806559b4b6b6fa4ddc171b1b8bdb6fbf1d975d7619d6ad5b87152b5668ea339bcd66fcf18f7fc4a38f3e8a810307c26c366b3e67029acf9e3d1b39393978efbdf7b063c70ed8edf650a07fda70085155a240faec830598fd01d6caf7a4ed3290beb212d89502a55a00a9f2fb5a1ac76ad750fa470a146b01c95216b31220557e5f8d354cae2165484ba537f47a3d62626260369bd1d6d606abd52abb9f526e28581d6ab567a54cc8a88667f8d33a567b6901cac1e8274b2d247311b290c91155d96e9634c91e091ca4839d9411cc5010f7ec81712792af05d6b8f239e6803307884bef2565d176fcceaa06d14a9d31d9c0d6094ef33c0f51944eb66c6750c9fb5ccbebf540f008f0b805b02c07739b1953a75d89c54bdf3e6f806400282a2e854ea703647a7cfe9f3f493a4826549bd58cd4d454fcf24b1e106226779b1516165220922c5cb2b3b3316dda347cfcf1c79a19e34fc5727272b072e54acacc91eeeafb0394cce68e36f0a73ffd09d1d1d178f7dd77515f5f1f10dc25e3872008183060007af6ec89baba3a596223ad7b5bad56984c265c77dd7500800f3ef8c0af04080192d7af5f8f912347a2a6a686de47393eab015f7abd1e13264cc0fbefbf8ffbeebb0f2ccbfa3090b4ea0700111111b8f5d65be172b95056561630fbb5288a94915457570787c301b3d9ac594eafd74bb5b49516111181f0f0709f0d2312e82e5fbe1c37dc70036a6b6b515151e19308440b444c4949c1bc79f3200802f6eedd8baaaaaaf34a1f51144524272763fdfaf5e8d1a307eaeaea64ac3bade7438eac0f1f3e1cefbefb2eeebefb6e300c1310d470b95c78fffdf771d34d3705ed6bafd78bf6f676f03c8f69d3a661c48811f8cb5ffe428fe7ab1d1f24c0ee94295328ab37505f2640c0e8d1a3b160c1023cf1c413f0783c542e20d8761e0cebcce572e1dd77dfc5cd37df8cbababa2ef981e3384c9d3a15c3870fc7430f3d44e539a465f4783c78eeb9e7f0d0430fa1a5a585f6377fd727c02bd0219db070e1423cfdf4d3c8cfcf475d5d5db703ca575c7105962d5b06afd78bf2f2729f45ab5a5bb55aad30180c983c79327af4e881bffffdef686868d0047194cfc7ebf5223d3d1d63c68c41737373507e696f6f47585818aebbee3ab02c8bfffbbfff437b7b7bd02741ce5844d359479ee771f9e59783655994959505f42bf15d4e4e0e9e7df6593cf9e4933eedde6ab5c2e572f9dd3022ac6dadb2252424f824911204818ebdc1f603e9f8336cd830fcfbdfffc6fdf7df8faaaa2a59bc10c84f0cc360ead4a9888c8c948d0f5af2661cc7a1bdbd9d1ec1d65a13242525212e2e0ee5e5e5b2b154adaf11290783c180071f7c10b1b1b1282e2ef6eb038fc7039bcd86acac2cbcf6da6b983d7b361d1bfc3de3a79e7a0a8f3df618dc6e37cacbcb35f56395f3696a6a2aeebefb6ed8ed766cdfbe9d1e4727e5ababab83d3e9ecf6b9596d6cf597184cfaac789ec7ba75eb3064c810d4d4d4c800737f73bad56a4542420266cd9a45d9f876bb9db637a2bbfdda6bafe1aebbee82cd66eb922f7bf7ee8d3973e6c066b3e1c081032140f934c736fe72e02813f4056212ab5d472b46f2a7e3abc5025602c75affa7752f2560ad2551190c3b59fabb9abfa4603201869580b1968c07f91f42a2311a8d888989a13242a4af92f1c09fbeb49684a19299acd4de576310fbcbeda5062293b1440b5496feafbf84b4e4b390aa7ac84226e9744ae9072930271d4c4e7446ce07903eb365269ac7eac9afd45ed2729f13fa570c1bb0bcd281cd67501345941d2f83cbe506dba9774df49395c758c87d800e866e871e9200b7db8584f838f09c07e793249847e46034997c127068f951ecdc30710b6e7afc521004f4ea951192b8e8662b2a2ac2f7df7fdf01f64b02febbeeba0b4b972ea53a7fdd01de89a288b8b8387cf6d967544642b960f47abd94a9e272b9e83148e9e7e458f5d5575f8d9e3d7b76697cf07abda8a8a8900123a47db9dd6e7aac5b2d70baf1c61b3161c204c4c5c569069c6eb71bebd7af47767636cacbcb7dd8d6d27b91fa497d4082c2848404bcf9e69b484c4ca447a5833183c100b3d9acaa4bab1610da6c36d4d6d642a7d3e1934f3ec1e1c3870326cae92ae0e5f57af18f7ffc0337dc7003cacbcbd1dedeeec3f020fe703a9d70b95c3e3e114511cf3df71cd2d2d290989878de68229220f6871f7e407272b2ecf8b614b82075773a9daa6d3e2a2a0a6fbcf10662626260301834efe776bbb168d122dc74d34da8a8a8f0f135e9dfd2f6272d0ff1757a7a3a1e79e491a0fabdc562a1c7e0d59ea9b28d933a0f1e3c18b366cd42545454b76f0eb8dd6e2c5cb81037df7c332a2a2a5475534fc50fa228e2d65b6fc5a38f3e8afafa7a98cd669feb9363a76ad7273ee2380e6fbcf106323232909090d06ded5a1445a4a5a561d5aa55f0783c686e6e560519a57d4e9aac94d47fe8d0a1b8e79e7b909898d8e57e5f5555a5cab294fa4459268ee3909b9b8b499326f96de7e782917156d95703b5fba1438762e6cc99888c8c94f9b4a0a0009b366d3aa5bea004773c1e0f5e78e1053af62afb41a0f988d449afd7e3fdf7df47525212a2a3a3836ea7a42c6a9bbe5a0045696969c0cd2241107c241b48db529b532323233177ee5cc4c6c6a2baba5ab34daa8dcdf1f1f178e6996768424435e3380e73e7cec582050bd0dada8a868606cdf180bc946dbf478f1e983e7d3afaf4e9237b867abd1e6bd7aec5810307ba7d6e3ed936e672b9b079f3660c1f3e1c1515153ecceb40ed8aac4f870c19825b6fbd55e62b9d4e87975f7e190f3ef8201a1a1a54b59b95be54b6e90b2eb800575f7d35d2d3d34341fe696a03fe642bfc3185fd5d2350123ee54f2d80d61fa81cec98a54ca82a05449589e2b4749cb5406b7fe5576a242b5f5ad883f27fc8c62391a2898a8a427c7c3c4c26935f36b59adf82f5b33f90589a2853fa22facf24b1a0f46f9ee7e98b7cc6f3bccfe7242933b986f23af4fba1ae1bb290a113783cb11bc3711c384970a30c423b162be7c65e0cc330f02a329b4b016fcdfa32ec5997e820f70fcca4f60f7cdbed0e80011df4e4cf559da9e1f576e80593bfcde656dc73ef3df8d7bfde39e7a52e449101c388604411df7dbf15d3aeca855714c14ab2d1aaf9b02358ecd0751258013cc74314455c3be35abcffde0770381da181a09bcc6ab5e2e38f3fc6c89123111e1e4efba7cd6643efdebdb166cd1a1c3e7c189b376f467e7e3e0a0b0bd1d0d0a0cac40f147c02c0f2e5cb613299505a5a2adb59763a9d282a2ac2a1438770e4c811ca028c8989c1b061c390939383f4f4747a1d87c381071e78802e909b9b9b832e8b34f8abaeaec691234770e8d021d4d4d4503dc2ecec6c646767232e2e4e761c6dd6ac593876ec18ac56ab0f9bd2ebf562debc79183972244a4b4b65c7fd589645797939f2f2f270f8f061d4d7d7c3e974223c3c1c999999c8c9c9c145175d049d4e47ef979a9a8a7befbd178b172fa67208818c2ce0a4e5aaa9a9417575353d7adca3470f242626c26030e0d0a14314082380f9dcb973919a9a8a949414646464c80071a7d389828202d54535cff3349990d488566b7575b54cd38d6559949494e0d0a1433870e0002a2b2b61b3d91015158541830661e2c4891833660c3d026bb7dbf1e28b2f62f6ecd9aafe3f17cde3f1e09d77de415252128a8b8b656d90f872efdebdc8cfcf474b4b0bbc5e2f7af4e881f1e3c763fcf8f15467160092939371c71d77e0dffffe371a1a1a54fbd825975c825b6eb905d5d5d5b2a3c33ccfa3bebe1e478e1cc1c18307515e5e0e8bc50283c1808c8c0c8c1933862669242742888e73f0b1090b4110505454845f7ef905f9f9f95463b677efde98346912d5e6043a98c3d75d771db66edd4a13b075974d9c3811b7de7a6b403f545454c06c36c36030203d3d1d393939183c78307af4e8e1d70f7dfaf4c1534f3d85e6e666593be4791e8d8d8d387af4280e1e3c88d2d252caf44d4a4ac2e8d1a3919d9d8dd8d8580aaeb4b7b7e3f5d75fc7ecd9b3d1dede7eca7e20ac41a2292d1d1bc951f4bcbc3cecddbb17c78e1d435b5b1b789e475a5a1a264d9a84091326202a2a8a2e4eafbcf24afcf2cb2fd8ba756b97c759e93d8f1d3b86fdfbf7a3b8b81876bb1d111111183a74282ebdf452a4a7a7d3761e1b1b8b2953a6a0a0a000a5a5a5e7414cded1ee0b0b0b69bb6f6e6e865eafa7ed7ed4a8519aed9e8cafd5d5d5983f7f3ee6cc9983d4d454444545212b2b8b322b198681d96c9631cc95fe261219c4264f9e8cdb6ebb0d555555b27981e779d4d4d4d07e50595949d9e16969691833660c8d09483b1504018b162dc2638f3d06bbdd1e90a94edaa294cdcb711ceaebeb51595989fafa7ab8dd6e242424a0478f1e888c8ca427a5baca483f72e40876edda85df7efb0d2e970bfdfaf5c38d37de889494143adf582c161a5710d65f717131d5e4b7dbed484848406e6e2e264c9820033f3333333171e2446cdab44915e8e6380e494949a8afaf87cd6693f9b9a1a18126122e2b2b437b7b3b4c2613860c1982a953a72226268682af175f7c317efcf147545555f9f4b51b6eb801f3e6cd435a5a1a92939371c10517c8e66697cb85fcfc7cbf737330cf2c9818e3a9a79ec2d0a143515252a21ae7fcfaebaf3874e810eaebeb695fcfccccc4d8b1633160c00044444450d08b68671333180ce8d1a307dd8c92fab2b6b696c689151515b0d96c888888c0f0e1c33175ea54984c26ea8b69d3a6e1871f7ea08986991013e5b480ca6ad206a713b856beef2f599cbf647cfee41a8205c1a560a9daf5a4b2114adfa8d54b4da2834847a9250f54037a092399bc4780d5b0b030c4c6c652b93492a8d25f42c3607c1a480b5b190b68adc7b498c84a46b2d609f040b93718860981c9210b99b4e370dc89dd1896e5c071ac860e2777e2289784bdcc9e6180992581a44c7203e079ae4bf53ea3745c462982df51e6400316cb7132c9118682ff224acb6ae070ba101f174f0745ad67271d9c85ce0090eb646a5f3ef9327cf9c597a8adad3ba799ba0c118c6618d81d4e188c26389d2ec9c4c2040c204451a4607a646418d6ad5f87c9974f03cf08a1c1a01b8ce779ecdab50b0b172ec43ffff94fca2423091c1a1a1ad0a74f1f3cfef8e3686a6ac2f1e3c751505080ad5bb762cf9e3d14a809b4d1e2703870c71d7760c28409282a2af201b457ac58811f7ef881b28da4bbeedf7df71d525252f0d8638f61e2c489744c73b95c78e8a187f0c0030f04a5eba96c5f5bb76ec5ba75eb909f9f4fd9b064a1f8d5575f61f0e0c178f9e5971113134381c18c8c0c4c993205d5d5d5b0dbedb240ba6fdfbeb8e5965b28d84e3e33180cf8fcf3cff1f5d75fe3f7df7fa7f722c1df8e1d3bb072e54a5c7ffdf5b8f7de7b65cc81091326e0a79f7e922d84bb02e2ac5bb70e9b376fc6b163c7644986525353111f1f8fcaca4ad902b3bdbd1dcf3cf30c1886c1dd77df8d679f7d16151515f4f9363535e1a1871ed2bcb7527f9be779cc9933073ccfcb16860cc360c3860df8eaabaff0fbefbfcb18e1a228e2f0e1c3f8e28b2f70cf3df7e09e7beea1c777a3a2a270cb2de20687210000200049444154b7e0b5d75ea3c98cce5573b95cc8cdcdc5b469d364922364b3e69d77dec1f6eddbd1d8d8485924a228a2a8a808bb76edc2975f7e89679f7d1659595914081a376e1cb66edd8ab6b6361f3698d168c49d77de0983c1203b8a6e341ab16bd72e7cf6d9673870e0006c369b6c41b267cf1eac5dbb16d9d9d9983e7d3a468f1e8dfcfc7cbcf7de7b41ebd6ea743a343737e3e38f3fc68e1d3b50515141338c7b3c1efcf4d34ff8f4d34fb178f1628c1a358ab639afd78bebaebb0ef9f9f9dde677bd5e8f3befbc1346a35106c8188d46ecdebd9bfa8124eb92fae1d34f3fc5a851a3307dfa748c19330605050578efbdf7647dc4ed7663ce9c39484e4e966d10e8743ae4e5e561eddab5d8b3670f5db049c7b22fbef80223478ec4534f3d855ebd7ac91200de73cf3d78fef9e7e172b94e49dec166b3e191471ec188112350585828eb7365656578efbdf7b077ef5e58ad56fa8c4451c4efbfff8ead5bb7e2e28b2fc63ffef10f0acc701c871b6fbc11070f1e84d96cee52622b8ee3909f9f8fb56bd7d27b1256a6d7ebc5d6ad5bf1e38f3fe2a5975e426c6c2c95c4193162047af5eae5333e9d6ba6d3e9d0d4d4848f3efa083b77ee446565a5acddefdcb9139f7efa29962c5982112346d0ba88a288193366e0c8912314102400efd34f3f0d93c9843163c6e0f3cf3f477171311d370a0b0bf1e28b2fa2aeae4e733e2763a25eafc7ecd9b3c1719c6c6e34180cd8b66d1bbefcf24b1c3c7890264494b6d3356bd6e0924b2ec1fcf9f3919898489f79525212aebffe7a2c5bb64c26df14cc7c64341af1fdf7df63fdfaf528282880d96ca6f7ebd1a307525252a89c43b0c014c77158bd7a35d6af5f4f01734110b06bd72eecddbb17ab56ad52d572154511df7fff3d56ac5881caca4aca4a1645119b366dc2bc79f3306bd62c9a0031222202393939d8bd7bb7aaef1d0e07de7cf34d646666a24f9f3ef45e3ffdf4134daa4bfa1b0180befbee3b141616e2c9279fa427c35c2e17a64e9d8a9f7ffed9474ec4e97462c1820560180677de79279e7bee39d9dcdcdadadaa5b9f964ccebf5222b2b0bb7dd769b4ff25083c1802fbffc12ebd7afc7b163c7643e2571ceead5ab71c9259760c68c19e8ddbb37b66cd982f5ebd7cbe6f1b6b6362c5ab4084b972e458f1e3d684c4092081e3a7408369b4d16276edcb811555555f8cb5ffe229b7f67cc9881bcbcbca063a7909d7e40594b0a231020a805eaaa25830b047efa630d075ae3937e20953652033709902cf58d12ccf637cf4baf4f0065324e4b59cae4a7b46cd2be41fe8fe77944444420262606168b856a272b99d85a0c65353ff8635afb4b9aa704dcd50065f23b611bfbd3480ee699310c1392b90859c8242355e78021caf490fd2daa7d3ada59587f4bcbda15005936109fa55820d809524dc2e3c4e71d9fe9757a703c0f960b0e48ef483e7722111f0024a7f440c679727c8b3cb6e6a666e8743aca940fc4e22675f64882efa6c646f44a4f45466a12f5a718cac577ca66341af1dd77dfe1861b6e00cbb232c90b8661e072b9d0dcdc0c9665919999896baeb9062fbffc32befdf65bfcf9cf7f467474b4df31481445180c06fce52f7f416d6dad4c8399e8636edcb811d5d5d570381c34109296a1baba1a73e7ce9569027a3c1e7a34d4683476692cfafcf3cff1f6db6fe3f0e1c31488568224fbf7efc7e2c58b7d92d5cc9831037abd5e760c9b61185c72c925484d4d9531934c26133ef9e4137cf4d147282828407b7b3b0553a40197cbe5c2ead5abb176ed5a3a568aa208a3d1882bafbc12f1f1f15d1a3bc9d1d80f3ffc10797979b0582c14c4763a9d282929c1fefdfb515f5fefa375c8f3bc4c9b5319281370824891485f04a023e5cfccccc455575d2563d2721c876fbffd161f7ffc318e1c3922cbcc2ebd87c3e1c092254b505252424f72783c1e8c1d3b16bd7af5d2d47f3c57cc6030e0965b6ea1f59302ec2fbdf412b66cd9829a9a1adae669c0db19481f3b760c1f7ffc31d5cf2447a1478e1c49d96c524b4949c1f4e9d3d1d8d8280397f6eddb8765cb96e1e79f7fa620b494954202f5bd7bf762d1a245b8fffefbf1af7ffd0b5555557e3542a5f7a8a8a8c03ffef10f6cdcb891324f493b27f770381c78eeb9e7284040ac4f9f3e484a4aea36299de4e4645c73cd353e7ed8bf7f3f962d5b863d7bf650ed40353fecdbb70f2fbef822eebfff7e2c5dba94b236092095909080bbeeba4b765a80611894949460d9b265d8b973275a5b5be998a24c70b37fff7e3cf8e083b27156144564656561d4a8519a47e983ad7f5454141e7ae8210af64841e6575f7d15bb77ef464b4b8b6ccc932ee4befbee3b6cdbb68dfe9f2008c8cacac2e0c183e94657b063d08e1d3bb068d122ecdcb993b2b8953e3974e810f6ecd943e57c481dfaf5eb87f0f0f07376c388e77994959561c18205d8b469134a4a4a54dbbddd6ec7c2850b65ed9e6118f4e9d3073d7af490b57b727457aa6ba9044fc9f8abf622f38b288ae8ddbb37aebaea2a593fd0e974d8bd7b37962f5f8e7dfbf6c9e6052500b075eb563cf3cc33b276ca711c468f1e8dcccccc2e8dbf068301df7df71dde79e71decdbb70f4d4d4d146c1404015555553870e000aaabab831a7300203c3c1c4b972ec5679f7d86b2b232d8ed76d9e6cce1c387919f9faf5ace929212ac5ab50a656565149894ce755f7cf1858ccd2a8a227af5ea859e3d7b6a0222d5d5d59833670eb66fdf8ee3c78fe3edb7dfc6bffef52fecd8b143d6df481be0380eebd6ad930141822060d8b061301a8d3ef25644ab9b483ba8ad97789e0f6a6e3e15bbe28a2b101b1b2bdb4c371a8df8f4d34fb162c50a1c397284ea7f4be31c9665e1743ab179f3663cf9e49378f8e187f1f1c71fa3b9b959561f9ee7515858883beeb8037bf7ee45515111162f5e8c65cb9661f7eeddaa63ab4ea7c3871f7e281b2fdc6e37b2b3b3a1d7eb656d3864e71aa4c1f8ac9f83596f07928990fead0542ab01d481f4efd5005c3550395099d5caaba67b2c95ac504a5e90f7d53e232fa9ac0e49c6171d1d4d4f4106a3112df58d52ef598d21ad06cc2be55909635a2979a194b1507e47fa5df2bb3f5059c66a0e75b790854c651086ef8e97d6ee90ac439f455036d084a01cc0640301c39e711c9c61581f01f960b582d43602aaaa6a54271d7f0331015549f064319bf1c083731066329cfb6d94011878f1e3b6edb05aac3ebb8a2ccba9be64c940bc5e783b1310141717e27f6eb81a4e4184088632b343a0f2a919c771282c2cc4942953b07bf76ed51d737214932483080f0fc7a38f3e8ab56bd762fcf8f194c1ab348fc7834b2eb9043d7af4902dd0789ec7ce9d3be9f1697f2c48927c62debc7932cd62411070e59557223c3c3ca80503c771f8e9a79fb07cf9720a5e6b6d16190c067cf2c9273200451445984c268c1f3f1e1c77e2e447585818aebaea2a99762acbb2282a2aa2ac197fc7d749bff8e8a38f505b5b4bdf77bbdd98306102e2e3e31116161614b0c2711c8e1c3982d5ab57a3a1a1c187e9a54cb673b20b00adeccac45c2e1766ce9c0987c3213b8e5b5d5d8dcd9b37e3f8f1e33e9ad2ca7b783c1e2c5dba14f1f1f1325f0f1932e49c661a89a2883e7dfae0a28b2e92b579bd5e8f2fbef802f9f9f9019347b22c8befbfff5ec6ae25accda8a828595b100401b7dd761bdd2c2066369bf1d5575fa1a8a808168bc56ffb21806f4949094a4a4a820275188641636323162f5e8ca3478fa2aaaa4ab34e2ccba2b1b1115555553280273c3c1c191919ddf23c0541c0cc9933e1f57a7dfcb07efd7a1416169e921f9c4e27fefce73fd3045de47da221faebafbf52c6a5567d388e435d5d1d162f5e8cc8c848fa7e5c5c1c468c18217bafabe676bb71c515572022220266b3999641a7d361e5ca953876ec988fceafd2743a1d962c5922dbb0f0783c54c73898318880f7afbdf61a6a6a6ad0d6d6a6794f8fc783e3c78fcb00258fc783fefdfb073de69d0d00a4bebe1e6fbef9260a0b0b515d5dedb7dd373434f8b4fbc8c8484d4dd740c04430fde0f6db6ff7197b9b9b9bb171e346141515a9eaa92bcb9d9797878d1b37d28d53afd78b7efdfaa16fdfbe888c8c0cead9b02c8baaaa2aac5cb99282c5caffebea7ca4d3e9f0f5d75f63c78e1d54ca46ed3bbb77eff6192b3d1e0fb66ddb868a8a0acdf9a7b5b5151515157463471445a4a4a4203e3ede6f3d1b1b1bf1f0c30fe3de7befc5175f7c81929212d86c364d5912bbdd8e9a9a1a1fa0252b2b4b165f74a56d0433379facc5c5c561f2e4c994b14dc6b3d2d2527cfae9a7a8adadf59bf08e94a5adad0dbffffe3b2a2b2b559f01c771a8a9a9c19c3973f0c0030f60e3c68d282d2d85dd6ed7f4497b7bbb8f2f398ec3800103284b3464e7c6d8a9d656959f070bccaa01c74ad907adf54cb0588512f8d6d253562618544bb6a7c6e69502c7caeb2b41632578acccf7a294c120b9110441a0dac9d1d1d1d0ebf5aafed1c2350201c85ad221d267a9a59bac0487a580b21430967e5fe9f360da4d084c0e59c8a4107227738b744e8ee3355f2489dd59d51df6096c582ad7e1afdc67b5ccf04d74d8119ca8979700a3d2c18d654f80df1da0aa88fd070e42a7d3f9d44dcb072ccbc1e3e93c2ae6f17424e473bb91d42311975f32028247842882be4e0cfc0cce0a055d0350f67844fcb46b4f273bf904f38a30afa546de233fa5477a9c4e27468f1e83110313e0f59063f1e706a0fc9f10abb6b4b4e0eebbefc6e38f3f8e9f7ffe19353535340851630e3a1c0ec4c7c763c99225983a752a1212127c98401e8f07c3860df3d1f2651806ab56ad82c3e1505d0caa0114bb76ed82c5629195c5643261d4a851418d1784b16bb7db83cef2fdedb7dfd223df24a81a3a74a8ac9e46a311175d7491ec9a2ccb62dbb66da8a9a991014ffec6c9e3c78fa3a2a2c22789d2a5975e1a34900300efbdf71e6c365b50f73d5de6f178909b9b8b96961659907dfcf871d4d7d723292909e9e9e97e5f19191914fc92b2a0faf7ef7f4a0cce336169696948484890315d4886f9b0b030a4a6a606acbbd168a4cc6402b6646565c16432c9ea2f0802264f9eec73f49ce8b85a2c962e9f0a0af67bc5c5c5282a2a4263636340763149802965f59b4c26bf204d574134353f141717e3d0a143a7ec07b7db8ddcdc5c343636ca3e6b6d6dc577df7d277b5681fcb06fdf3e1980eaf57a71e18517223939f994fadc98316364ac419665d1d2d28283070f223636166969697edb5daf5ebd28bb915c431004ca9a0e8641aed7ebb17efd7ad86cb6807aa50cc3c836d0882f7af6ec79ce320a89e4444949091a1a1a826af755555532b669585818e2e2e24ecbf82c08022ebbec32d9d80b806ad4b7b7b707751dabd58afcfc7c1950eef178909d9ded03d2fab30d1b3674ab762dcbb2282b2b434b4b8b4f5ca1fc8e924deff17870f8f061bf3187c3e180d96ca673bc288a888e8e86c9640a6a1e6f6868405d5d9da6448b147c696a6aa21214e77adb8f8c8c44bf7efd640030cbb2d8b871235a5b5b61b55abbbd3dd7d7d7fb245b54f62da92fa57192d7eb454a4aca391f2b9ccfc0b01610dcd57c2ac1ca2228014f7f520bfe7495c9cf60caa906ac2a13e0f923c76901deca6b29252cd4d8c96ac0b1143096de4709447bbd5e180c06444646c26834ca349dfd81c9c16a28ab81cccab6120c981ce8a5b629e12f6e2365098d02210b99cfe0e695ece67b2868ac04e5a4a02c39a6e94fabf6f49557d42c5f97504986e9c048cf4015bc5e2f38104d1f3688e231aa453e01508bd8b3771fe60877d1e026d0442695b9203f3d1e01f6361bee98fd10f4c6486cd8f81dec2e119000ca0ce3c5d9a2a08b227cb49c1906d8f8cdb79832e5521a249f08f83c01ebdff1bb08a7d309c123e089a71662e3d7ebf0d9e71b6073b2e0391180d8019f8b67a7de44d6fb7c96666359160683013ffef82376ecd881be7dfb62d8b061183a7428060e1c88cccc4c0090e9d59280e689279e4054541456af5e8df6f6761a68e9743a0c183040b620e5791e478e1c417d7d3dac566bd047dc753a1d7ef8e1074c9d3a95b263f47a3dfaf7ef8f6fbffd36a87148a7d3059dbc8dc8045c77dd7514a0f17abdc8cccc9425de484a4a424a4a0a0a0b0b650053717171979256e9f57a6cdfbe1d13264ca08c4841103062c4888099dc49794932bbd6d6d66e910e38d9b1333e3e1ebd7bf7c6b163c76473d0902143b078f1e2a0afa5d3e928604082ffd8d85884858505bd2170361659bd7af582d1689425700480b973e7d2e46ec1f8313535152e978b1e5f371a8d484c4ca45a784007bb77c89021545f95f44bc2803e5dac2c5114c1715cd0fad5a228c2e170c83662388e936dd69c4a59c2c3c33174e8d0d3e207511469d2b8eaea6a1958fbcb2fbfa0a9a929688d69a0437fbcbcbc1c2929299491949e9e8ed8d8589aa0b8ab663018d0bf7f7f99462e39defabffffbbf7e19d3ca7667341a65c7c92322221017174735e18319cb82ddcc226d42da57222222cedaf8158c91761f6cdb713a9db276cff37c97e499bad24e63636391999929d3cc26fda0a9a929e87e40e4766c361b05c23d1e0f060c1800a3d11814b05a5d5d8d5f7ffd154d4d4dddfa3c398ef3dbdf08f35709ca8aa2889a9a1abfa7420880a3042c080013a8fd4beb49c01eb7db0d8fc703bd5e8fa4a424848585816118444747cbda85288a888c8cf4d15c3d172c2929093d7bf6a45af1e4644a515151b73f5f2d5f12204d1004188d46992fc3c3c37dca10191919d24b3e8d718e1a90ac0410e5a7515919db3498971aa8a90461c9fba47f929f4a10574bf6c29f2eb0b23d92ef4b19b3c180acfed8bb6af753ca074ae521743a1d95aa20f1a4dbeda6eb04b25621e319210591782b3c3c1c06838126180e54267fc0b134d650d6556da340f9ec4999b592ee6981c5fea415d57e0f81c9210bd909445231b8c02f20a7ec8454e6e22cd228e5c181d6d140ce67023a9be52465f257de40e030c388309bedd8bbef002ebbec12086e4136e0a95d5b09b67b3c9d475c04012ded56fc61c6f5e8d72f132fbdf24fb8452358c60b46ec0455cf343399914e80bea06a4343238e9755a05fdf3e7492ec9890036f2878bd9e0e86b6b7f3588fcb85cb265f81cc7e17e2c081fdf874ddf7d0eb4d60182f9833596f460423329d7567f09f12b392051839eabd69d3262426262223230313274ec4d4a953111616264b2864b7dbf1c73ffe11555555d8b2650b05fac2c2c2101515255b78731c878282021ac804dbbfc9d1db1b6eb8811ee32609e5885e60770306252525f4b835e9df515151888888a0a07946460605d549f0d3d2d2e2a35b1a0c789d9f9f0f93c944d9631e8f07bd7bf786c16008b828625916a5a5a53e6cdeb30126a7a6a6d2dfa5cff764167756abd527e37b7878389a9b9bcfc9e3ab1cc7212121c1076cd2ebf55d064e957ade5eaf17b1b1b1d0e97454973223230340077396f85a10041c3d7af49492b9757561192ca0ac94deea0e169ed7eb45efdebd7dfce0f178f0db6fbf9db21f489b164551767d966571f8f061d5ece8feacadad0d4d4d4d484b4ba3ff1b1f1f8fe8e8e893069323222260341a7d189164f1d8155326db23ed8e6c6274251968306d420d18ff4f02814e57bb570342c9784024a9c8ef478f1eed926e2ec3303409220193493f080f0f976d0068596b6bab5ff99bd33d0f29630b02ecfb2bb71404919ad168a4390502992008b0d96c484848c0a5975e8ab163c762d0a041484b4ba347ccc9c6a83417049927ce454b4b4ba30c47e29bbaba3a3436369ed6e74b7c999c9c8ccb2ebb0c39393918387020657093e7151f1f8f9696161f5f86c0e4ee9feba55ab56a60b23ff050f95920105109286b690f2b014d7f5294cafeae05402ac76f2590292dab96848516587b62edef5565f22aef230802dd4473b95cd0e974e0799e7ee676bbe9df525f4a59ca1cc7c16834223232126161613ec9fad458bdfe00656999a56c6aa94c8f322f85da330fb4c1a0bc96d6f3f227d11102934316328ad775762e96031816a288f3e208cf097d5cd6072c0e06d0ed0ae0d4ad80b262574c2ad110eca2480632b05efceb5f6f63dad42bd1eeb1c9fe27d0b54f80cb27168aed562bd2d233b0e4cdd7f1c3f79bf0dd961db0b6bbe01501af78461b2618b1e3179663e0f1a830d1452f44af089663217a459f4d02fffa7d1c78be7342f07480c9767b3bc2232270f1c449983c2517bf1ffd153b76ecc2f1b24a085ee60cb40d000cd759361ebccee813c8fe27048d400773aca2a202151515d8b56b17962c5982871e7a08d75c738d6c1161329970cd35d7e0975f7e415555153d56253dc649fa75454585ec3876b0e5214719958b3c2900db9df56f6b6bf359f413160c096a1212127c001cabd58a8686862e819da47e46a351063eb02c8be8e868d4d4d4045cbc13b0eb6c2ea048b238b563be5d616f6a190998cf551d449665111313e3b3c0561e8d3c59df868585d16b93a470caeb124989b301e29c0d237e50f3797979f929fb411445c4c5c5f9b45f224fd3d5764d126329e31a921ca7ab7d98b40b35208a302d4fd54c2653d040f2c9ce35213bf5b1373131d1a73d92f1a02be30fc33054ba4039dec6c4c404b5b140e6a373cd4727c3fa0df6fb1cc761dcb87178ecb1c770c92597401004582c16d8ed76389d4e993f4872da73bdfd13c6bbb45d91f8a8a9a9e9b4dd57a7d361ca942978f4d1479193930397cb058bc50287c30187c321f3655d5d5d682c3943f18d3fc0570b34d602910301cbca76a896648f6c7028dfd3ca6fe48f89ab1c27489d94b98c0225eb53bb9696a48416082a058349ee18021a13263279265296b2d48fe47d29a3991062ac562b0441d02c9bf26f35f631c96f22bd9f16a0efef79abb52b699b524bd4180c904cd70da1ae1bb29049403bb6e33c3dcb3260d9c0da448c6207b1b3179e41408aed283308181e9ca692b4dc6703489626dfd39ad8542759aef37b0c0335a909c1e3c1d1a3c77041af0b3a064700a2cacea556b04b76e2798f00966560b339e170d8316af4388c1d3f11164b47228ac3870f744d46a4cbd16547f5588685c16840787814c223221115d3036fbdfd2e18c8172d66b30d5f6ffc06fd075cd801288b2220399e1438a988ae7361ec85a017e072751ce5edc8546d4152524fdc70c3f51d13e7695ebc900cd2d1b1f1484bcb407aafdec8ca1a8a410307a1a6b6e63f7eb16a369bf1e4934f62efdebd78e9a59728239864912747c101d06cdaca5d7792f1bbaba6268b613018e851c7ee0418c95159b5131e616161b4ced1d1d13e8b774110022639d25abcab8d7731313134983c1f169e46a35115d8abacaca4daab6a47dbb4580be4a7288aa8acac9425013a174dadcd373737c366b375b9ced2ef98cd66141717cb981aca453e79df62b19c3166f2b9027628db9c288ab05aaddd02e2474646fa5c9f611898cde6a0a54ba4e383123026f73859c62a61162937ee2a2b2be911e060fb9df2f396961694979787809af3a01fc4c4c474db784012b8aadd83e7f9939ac3ff138d6c9ce7e4e460c18205183e7c38cc66334a4b4be1743a55b53ecf378b8e8ef619ff3ae2eff66e8dbb3c1e0fc2c3c33171e2442c58b00003060c404b4b0b8a8b8b651b70a1b1e82cc1101222909686ad1214544ba246241b388e93316afd81ca6aa0a6f254108991b512c569018f6ae0afd6676aeb567ff20b5dd171d692879082c2045426be92b28fa57218521fb9dd6e0a26474444203232124d4d4daa3265a48cd2fa29817a35290b521ec24e969e8e51d34d567bc6fe709f6098e65a6d360426872c64646140b47b19065e6fe0a31b3e1d94393712b39dd411c9331937302c4eb89a09aacc7422f1f8f7afddeec0fa0d1bf1c003734e30af3b466ad9e0ad755f8ee341c8471d1aca5e88a21776bb0deded16086e01d1d1e1183f6e1cbca79395c6002cc721cc148e949e69484fef85d8b838149554e0ffde7ad7e77931f062f3775b71e30dd7a25fbf7e103c0244729c37c8050ecfeb6032319d1b291dc9276db676701c07a7d30187c3dea14d27b84f8b920b79142cc783d7eb11151583a8e8587a2f8ff7bf83054898b95bb76ec59e3d7b9093934301e5d8d858a4a4a440a7d3d100464d7e8268c07675414018c14a705799f8afbb1688313131ea63b12440524b2c74322c54c22e540ba2ced5a3af5aedc36ab5aa3ef3471e7944c690e079dee777e54fe9ef6eb71bbffcf24bb7b3d0bb1bd0512663e3791e5f7ffd35d6ad5ba75a7f2d3fe8f57ad9f7ababab515a5a2a0322dd6eb7aae61fe963ff2da6e607e958d31d6d5a6dcc3a19e625c7713e272c483f3f5990c4ed76fb807b269309afbdf61acacacafcf6357fed8ee7791416169ef6e3ec21eb9eb1b7adad4db5bd9fcca910b2197c3ecf476762bc8f8888c0fcf9f371fffdf7a3adad0d454545100441169b48134d1110c5e1709c171bc45ae32b91f7ea2ef3783c888f8fc733cf3c8359b366a1a9a9098585851494f2e74bbbdd7e56e5bdfe5b8c00c06aeb542d990a2d1099bca4cf539a8c4d4de6409a988ebcd4b47bd512e591cfa5bffb63b3aaf575b5c4724ab05bf93f6a8cde40921c520c47ca5026eb2ae23ba2874cc61ba90e3101a0499f21df25041c83c10087c3a19acc50893f68e9404bfd207dc6a4cf6a69454bbf2fadab7283c21f735b4bdb5afa5e48e62264215301f00863b60350d30650a48004e9ac1d97388313ad6c67323850563e319d00cf9933eb66cd72055a94325ce092eedeb317ff73d38d48493991b9dd0b80550ce2cae3782726161d388e07cb72d0f13a188c46186ded7038ecb0db6db0db6c6018f6b42efc5896858ed721213109e919bd101b9b004ec769de93613a00e53beff90b367ffdb92c3995171decec406d425af70efdd13044baa260b7dbe1723a60b7db21086ed5840add5a6f9d0eb1710948cfe885888848e8f586ffcae1c8ebf5a2b0b010975e7a29058c890ea05eafa7c73ad512de994ca62e279921baa2ca36a606a274d7229124c852d69b8085449a4209661320a62b6c6972945eaded1270f67c5828b12c8baaaa2a1f9f90e370d5d5d5aa3ed1aa9b1afbe35c66dc7abd5e343636cae6608661c0f33c9a9b9b7d80f040a732d4e620e23f9665d1d0d0a0ea6b93c9744ecb8174779b6b686850dd882163cda9b419c2ce558e59a2289e949ff57a3d95b3919a34796957cb67b55a554f52f03c8fdada5ab85c2ebf59d0fdb5bb53f55fc8ce54c8dd311f29e5ef4802b9ae1ad146566b6bff4d1b55fe4ca7d3e1c1071fc43df7dc838a8a0a1aef106047a7d3816559545757a3b8b818b5b5b5a8abab435d5d1d66cd9a857efdfa7559f2eb6c985a9ca3d3e9ba7563212c2c0c7ffbdbdf70f3cd37a3b4b4948e59c497e45e151515282929a17eacafafc77df7e87306eb0000200049444154dd879e3d7b9e17be3c9f4d0af6cad7ebf2752a993794c9d608604c12c249d9c96a40b214109402c82411a3144c9632929540ad16e0abf6bb3f56b2f25a4a0de94089f80225e653aefda549ff48124a02229317211728015a02404b8165a28f6c3299101616069bcd467da85c6f93fa2813184aaf4d4e3d294f3a4937d348d995f153304917839527d1fa497e0f81c9210b190539e5037457c085b339b97600e05d2f33a9eb9936515266028406043c58162cc306c16006ea1b5bb16dfb0edc7acb1fe1f178c0733c040894a1acdc1564a4dac292245f0683013ccf41a7d723cc140687d30197d301a7cb054fe7a4713a7ce8f57ac1eb78180d46f448ea89b8b844184da6cefbf95f60b0f062e7ce9f30f5eaa970394e3056458681d7e391d555e3e69d01ba1e3cafeb5c949b20081e0882cb6fb283ee5aace9f546444545213c22022653d86961c59e2f0b5702d448276dc26623e088c562f10910a2a2a260341a69a2be60c1d6a14387fa309ddd6e37ec767bb73f77b7db8d9c9c1c381c0e9f729023c30cc3a0bcbcdc87bda5d7ebe931b260cde3f1e0a28b2e5265902913629deb8b8de2e2625520342929098d8d8daa750c263bf3f9601e8f079595953efd222626069191913e607220264c205f171616aaea7ac7c4c4a0aaaaaa5b74aacf8736575454e4036c300c734a49eda471487575b5ea5c4a1222aa6d9a695974743492939365652220ddc96accb6b5b5f924f3f27abd484e4e86c964f2d970eb4abb0b01c9e787b12c8be3c78fab8e07d1d1d114840836ce4b4b4b435858986cce954abb840cb8e0820b3077ee5c545555c1e974cad639369b0d1b366cc0f6eddbd1d8d828d3fa6d6e6ec615575c810b2fbcf0bc88f54a4b4b7dda557727c31d31620466ce9c898a8a0ad9e91b9665d1d6d686afbefa0a3b77ee446b6b2b252bb85c2e343737e38f7ffc23d2d3d3430df20c8c318428a15c172b414229e0ac641fab01c95240590dd054329295cc64291356c99ef5c76c95d64309ecaa7d9f5c9f3080a56c5ce9e7fe18b5caef483592b5be4b8061a29b2c4dac47fc4a405c25082df517397d64329928ab594d6243ab0cfe8070e9ff4ba52ec8b321e5d2f2b396bfd598c8feb4a895b14c084c0e59c8e848270fec83d51d961f396181b3b8380f46c797008aa2e83d9b6e960d8a1d890f595506ad12006559160c58bf80eaaa551f63dab4a9888b8da19388e011e0817f450fe9bdb8ce015aa7d3c36b3421cc130eaf57a4ecdcd3bb78ef382a13191905a3c9448fa00763ab3e5a83d16346232e360ea2e805eb61e116dc81816449fd1989aff57ac3195decf23a1df43a3df43a4307907c9eb220944794baec079e474646860ce46418862e9688a67571713172727264605bdfbe7d7d12cd053251143161c204998400d17056ca0a748789a288c99327cbc070a27d6bb15868d96b6a6a505353230b92a2a2a290949484b2b2b2a0fba22008183b76ac0c9422bacdadadade715a8eaf57a71f0e04119939c6559646565e1e8d1a3fff140447d7d3d1a1b1be9e2c4ebf5223d3d1db1b1b1a8adadedd6457e6b6bab0f78cd711cfaf4e983df7efbedbf2334ea640eabf9a16fdfbea7ec07221f70ecd831444747d3bee8f57ad1bf7f7f1c3a74a84bd74b4c4c446a6aaa6c63411004b4b4b49c74024d866170f4e8510c1d3a5436d60e1a34085bb76e456b6b6b88b1f75f00f4343535a1a8a8081cc7c94e30f4e9d307478f1eedd2fc9799990993c9243b79e4743ad1dcdc7cdec8339c4ef3783cb8edb6db2008828c11ebf178b065cb16bcfffefb343eb1d96cb278c7e5729d579b34f5f5f568696991814cf1f1f1484c4c44454545b7b4ddfff99fffe93869d8c94826c0d9860d1bb072e54aea47421e385f7d793e1bcff354e3584b9691fc94ca922899c95286327949652f588a03f802c984914c98b64a309968f62a0165e5f8a6f61eb996f277e5f7089984d4433a1e2ac162255ea304b9a5f184b46c4a1f48df27ac649ee7659ac952bf2a415529f8cef33c8c4623dd08d7ea3ffe58d66abacfa4ae4a605b0a282bafa704ed95f7552b87d2776a3fa5f1371beaba210b9964c295ecf611708de538d5972f487ae6bb13cb3260950c353f659602e02ccb9d9d609581accc0400671846bdccd21d5a0423e321c266b7e36f4f3f03a3d1d879045a079ee37d8e05057ad172b02cf406238c2613c2c323101111795a5f61e1e1301a4de0749d3bc91c17a4840970fc78399e7df679b80537589603cfeba0e375b21d69ad0045abfe5c67522f8ee74feb8befdc91e7a4f53d0f8f7bbadd6ef4eddb17c3870f875eaff7093c82b1a8a8284c9a3449c65af27abd686969a1ef711c8783070fca0067411064f70db6bc43860c41fffefd698679f27e717171b72f24dc6e37b2b3b33168d0201f30393f3f5ff65da7d38903070ec83450e3e3e391969616f411505114919c9c8cf1e3c7a3bdbd5d168cfffaebaf27a5c17c364da7d361cd9a35888d8d9505c023468c38255dd8f305d82c2929417979f9095dfc4ed03126264655dee05417775f7ffd3522222264fe1f326448d0fdeb3fc1743a1d366cd820f303cff3183a74e84927b5939a5eafc7860d1b101515255bfc656767d3b92b58cbc9c991250c641806d5d5d5a7740281e7796cd9b24596fc511004e4e4e4c0603040afd787a409fe4bfac137df7c83c8c8c813ac2c9ec7e0c183bb341e984c268c1b374e76f28a6559141616c2e97486f4b33bfbfff8f1e3d1dada2a9bcb77efde8db7de7a0bb5b5b568686880cd66f3493625651a9e0f66369b91979747c7525114d1b3674f24272777cbd8c2300c468e1c298bb74451c437df7c83e5cb97a3b1b1118d8d8d14b457fa3204269fb9f18568a9fb7b91ef287f2ab5fac9df6aac6425b396483a48251ea420b39239aba5711c08b8540353d5c0642590adc6a4564b0228654ffb036dd598c5c4072e97ab234790025c27fe91ea994be5e1c867044c36180c3ea7e8d418dbca32fa03f8d55ed267a406282b81f6ee7849c1f310981cb29029804a356d19adc9f9ec072a27748f69563b3f808a961406c39cf9a1e0c44ea77ff90565b2c3e0ae0d14141cc186af3782d7ebc0f21d0c6383dee033290503441130570bf0eeee17c330f08a5e957a07e783a3c70a5150700446a3119c8e83de6880416f00c772aa49178269eb521f9c8e9772626559b6b35d9f5fc098cbe5c295575e890d1b3660d9b26578fdf5d7111d1d8db0b0b0a0027272cc75eedcb9888d8d95c940b4b5b5a1bebe9e2e34398ec3b66ddb6432145eaf17a9a9a918366c18a2a2a282baa7d3e9c4abafbe0a8bc5225bc47a3c1e14141404bd9008b63f01c0134f3ce1733f9d4e879f7efa49f69ecd66c30f3ffc20038ec3c2c23064c810a4a4a404754f8fc783ebaebb0e292929b2e3e87abd1e3ffffcb38fdee9f9b0e058bf7ebdcfb1dfacacac2e839c0e87036d6d6d7ed913e79a555555213f3f5fc634090f0fc7e5975f8e989898a09fa5d7eb85c56281d96cd64c24c7f33c56ac5821038f5896c5800103d0af5fbfa064784451447b7b3b3dbe7e3e828e3ccfe3c30f3f9481bdc40f7dfbf63d653f180c067cf8e18732b09ab037fbf7ef1f14604d34961f7ae821b4b4b4c840bab2b232d4d7d79f52fd7ffef96719f3ddebf5223e3e1eb9b9b930994c415f4b100498cd6658ad561f3dc5909dfbfd60d5aa55b2764ac6dedebd7b072dcbd5a74f1f4c9f3e5d964c95e779141414c83674ff9b8df467e93ce776bb71f0e041343535c166b3c9e279e97c1f15158581030776b96f9dadbed8d4d4843d7bf6c8c6b9f0f0708c183102494949415dc3e3f1c062b1c06ab5aa4ad2e9f57a59fc63b55a91979787e6e666381c0e555f0a8280f8f8786465658500e53314db6981c6ca5720c0590a266bc95b284154e94b0b50d69261f00716abb15d8389cf940c652d76b23fad643560550aa6ab69452bfda004d9a5ac6d6599c92616d964564a4ff893fa9082e44ab911a58eb3b44c52905bf99cfc3d3fb5cfd5a44e94e522c039b967084c0e59c8e882e304c8d615709561d8b3a23d4c316449123e2548aa3568b392fa9d0d46354972d8516e2ea8ef53d0bb0b41f61b6f2ec5e183bf74b093753ae80c7ae8757a0aaa2a273bad013fd077bafb752a0b0986016ced36cc9fff571c3a7418069d011ccb83d77706161caf9925f76cbed40289f3cdbc5e2fe2e2e2b07cf972e8743a088280912347e2b3cf3e437a7a3a222323035ec36ab5e26f7ffb1beebaeb2e593235c2ae2b2c2c94bde7743af1c1071fc898bb0e87038f3cf208c2c2c2648b5e35b3dbed78fae9a73172e448d4d6d6ca74d84a4b4b71e4c891a09e85288a484a4a0a5847511471fbedb763f2e4c9b2a45e449ff6d8b1633270571445fcfcf3cf329d60b7db8de9d3a7233535158989890183d3cccc4c3cf6d86332cd53924c69efdebd3ebacda77bec23521b5a0c05e9e2500b6c6a6d6dc5ca952b111e1e4edf4b4949416e6e2ed2d2d2829a93388ec3c30f3f8c828202dc7ffffd484c4cecd6843fa76fae66b17af56a99f48fd3e9c4b5d75e8b81030722363636e035dc6e37d2d3d3b17efd7aecd8b10393264d427474b4eaf32a2b2bc3962d5ba86f08c8396edc38242626066cef7abd1e1f7cf001b66fdf8e71e3c6212121e1bc0310897ef9b7df7e2bf343dfbe7d3161c284a0fca0d3e9b07cf972ecd8b103e3c78f97f9814808ac5ebd5a06cc464646e2c61b6f446c6c6cc036ed7038f0fefbef232e2e4ed6d7dd6e377efdf5573434349c72bb7bf1c5176527025c2e176ebdf556f4ebd74fd617b5cce57261e4c891d8b56b1756ae5c89418306c9362a42767ef483cd9b37cbc6ca0b2fbc30a87e40aeb174e952701c273b79d4dede8e03070ef86873ff37fbbabebede0770713a9d146452b3a8a8282c5fbe1cc3860deb92d6ba746ed65a830433379f6cecb86ddb36d96695cbe5c275d75d87a4a424c4c7c707d461cfcacac2962d5bb066cd1aa4a7a7cb36fe0860ad940370bbdd7e8fe0272626e28b2fbe407a7a7a977c19b2930793f57a3d0c06030523a5e071a0bfd5406465e23d0222ab01a75ae0a912580c26319bdafa5209324bff5663362bd9c95a7acf6a9acb4ab0576bbda904a195e0ba12b095beaf760fd29fc8f35182e0caff51b2a9b5b498d5c06465199580b2960eb6d64bf9ac95c918b598d12130396421ebe645ee9964526ae91e9f00c5199ff70074b05e394679b16089af67d7ba10bcb5b659f0f6b2f7c0321c44d10b9eeb0494f506f09c7cb756a93d74365f00c0294076b2bb192ca06c7738f08fe716a2b1a9a943fb99d7436f3042af3740a790fd3817ea4c99df9dc919cfd606cda998dbedc615575c018ee36481bbd168c49a356b70e79d77e2a28b2e4274743495be1045111cc7212a2a0afdfaf5c38a152bb060c102545555f9e804eedebd1b959595b27660341af1d65b6fa1a5a545c68a32994c58b26409b2b2b2e8a242a9fd151f1f8f279e7802f3e7cf474d4d8d4fc2aab56bd7d2202290399d4ecc9f3f1f175f7c31d2d3d365fa6a8220d0a4653367cec42bafbc82d6d656d9fd5896c5679f7d06a7d329d3fc25d2173ffdf4936c3c733a9d58ba7429860f1fee733fb2c80b0b0bc3a851a3b06edd3ac4c4c4c83454799ec7a64d9b60b55acfe822896118d4d6d6fa30d8789ea7c03fcbb2e8d7af1f3efae823ac5cb912a3468df2013add6e37befaea2b99cea22008b8fefaeb71cb2db760e8d0a1080b0bf3c9d24d92450d1b360c9b366dc20b2fbc80e8e8683cfae8a378f0c107111b1b7bce27bde4380e070e1cc0ae5dbb642c6c87c381575e790593274f46464606d5f593d65fafd723393919f7dd771ff6efdf8f499326212d2d0dcf3df71cc68c1923d3ec956ef07cf2c927b2f70441c0fdf7df8f2953a6e0c20b2ff4b997d7eba5ed2f2f2f0f37dd74132eb8e0022c5bb60c59595901c18173d1dadbdbb166cd1a595f11040173e6cc416e6eae5f3f8c1c3912797979b8f9e69b9191918177de790703070e94f981e3382c59b2848e1764f1959b9b8bdb6fbf1d595959d0ebf53ecf54a7d321353515ebd6ad436e6e2e2a2a2a64beadadadc5be7dfbba949054cdf47a3d366dda845f7ef94506249a4c263cfffcf3183f7e3c0513953e30994c484f4fc73ffff94f6cddba15fdfbf7c7c5175f8c679f7d16175c7001c2c3c34380f27962369b0dab56ad923d2f321ee4e6e622333353a6b549da80c16040dfbe7db165cb160c1d3a14757575b2f9efd75f7f45696929956d08ada758ecdfbf5f265da4d3e99095950583c1e0c32c341a8d183870203efbec33fce10f7f40595959974809fee666b251c4300cfaf4e983952b5762f5ead5183d7a34626262baa5ae7bf6ec414141814f9cf3ce3bef60c89021484d4d95497890f93c262606d75e7b2d76ecd881912347223b3b1bab56ad426a6a2a2db7288ac8cbcb93910ec2c3c3d1b76f5f555f868585213b3b1bebd7af474e4e4e97f25384ecd4c16429504c406529c01c480e83485d28e50d956c644110e072b9a8ac831a38299575d062fc06028ffd6d826811b8a4f7926a414bf5a1c95cab75f25a2de99dda7da5ef290165e21be9df52190c35767287bc66c733502ba7727c5703920301ca4ad0df1f20ec0f3856633b2bdb82b47da801eba1047c210b9904812320164b748703005a1dc01773d260e7a91799950d945230d2dfee3acbb0be7f330c20e28c02ca0c9880b2223e9f4b65100205688c8883877ec58205cf63f6ec3b909e9e0ebdce002fe705a7e320b83a07628fa0e9b3b3da24716a522ae59555b8f3ee7bf0ee3b6f2129a9c7894cbf3c07b7cbd51120305e7845ef2933a2bb03643bdfcd6030e0830f3e404a4a0a66cf9e2d939f703a9d9833670e6ebef9661c3d7a14656565686d6da540f2c08103316ad428984c261c3f7e1c4ea793b6719ee7f1fbefbfe39b6fbe81cbe5f2095e743a1deebcf34eac5ebd9a2e62818e4ce8afbffe3af6eddb87df7fff1dcdcdcd70bbdd30994ce8d7af1f264e9c88912347a2b2b252b680351a8dd8b871230e1e3c2803b40399200858b06001f2f3f371e8d021d4d6d6a2bdbd1d46a311bd7af5c2840913307efc78545757cb8ef7eaf57aecdcb9137bf7ee85c562f1e9db3ccfe3d5575fc590214390999949c16dbbdd8ed75f7f1d7bf6ecc191234750535303abd50aa3d188a4a42464676763dab469686d6d454d4d8d8c0579fcf871fcf8e38f686c6c3ca31b173ccfe3c081033e3e8d8a8ac29c3973505e5e8ed4d454cc9c3913515151686b6bc30b2fbc80279f7c12bffffe3b3d02cd300c0e1f3e8c77de79070f3ef820ad9bc562c1cd37df8c4b2fbd14fbf7ef474949099a9a9ae891e18c8c0c8c1e3d1a175f7c312c160b8a8a8aa83f73737371ecd831ac5dbbf69cd7ec8c8a8ac2c30f3f8c4f3ffd1403070ea41b022e970b4f3ffd347efbed37da06cd6633789e474c4c0cfaf7ef8f891327a26fdfbea8a9a981d96c06c330888d8dc5ac59b350525222d3e82463d3f7df7f8f49932661f2e4c9b47f915304070f1e445e5e1ecacbcbd1dede0e9d4e87e4e4648c1b370e53a64ca17e268ba2e79f7f1e73e6cc417373f379162231d8ba752b366dda84cb2fbf5ce687bffef5af3874e810f2f2f250565626f3c3d8b1633165ca1458ad567ab242cb0f95959578edb5d7f0f0c30fcbc6ce59b36621272707797979282d2d455b5b1b5896456464242ebae8224c9b360d5151512829299125d96359161b376e44616161b79c76090f0fc7ecd9b3b176ed5ac4c7c7537994a4a424bcf0c20b387cf830f2f3f3d1d8d8089bcd06bd5e8fc4c4440c1e3c18975e7a29a2a2a250565646c7d5010306e0cf7ffe335e78e18590bcc179d40fb66ddb862fbffc127ff8c31f68bb6a6f6fa7fd203f3f1f151515686b6b834ea7436c6c2c860d1b86e9d3a703004a4b4b659abe369b0d9b376fee96646bff4960f2ba75eb70c71d77d0f7388ec3c5175f0cbbdd8ebcbc3cb4b7b7c36432a167cf9e18376e1c66cc9801b7db4dfb7b57fa13cff33878f0a0cfff848787e3befbee435959197af6ec8999336722262606adadad746efeedb7df4eb9ffea743afcfdef7fc7279f7c82f8f878daae1c0e07162f5e8c5dbb76a1a0a000b5b5b570381c080b0b43af5ebd70e9a597223b3b1bb5b5b5686b6b03d0b1c1f5f8e38fe3af7ffd2b6c361bbc5e2fbef9e61bcc98314376bfdcdc5cb02c8b828202d8ed76848585212d2d0d13274ec4b469d360b3d9505c5c1c6a8c67184c565b1749d7c1d275be4fae1b094948099e4a014b2d10514bc6411004e8743a1f16ad74ed18cca95325d0ebcfa4c02c497ca7c6bc95c6f6d27eafc594562b8b54fb580a262b99c5e43e3a9d0e2e974bc60227ffeff57ac1b22c7d5fc9b2f607a6ab01caca76c0b2ac0c145682edd2bc304a72a13f8990604e634bfd208a6247a2c250d70d59c83a3ba8a83df89ccb0018c3b0018f4b6b955b144588f00267f09002619e06ebcb53dd0de7582f367dfb2d9a9b9bf0daabff8bf0888813c77e180e2ceb06e3ee4c32217abbf4fc4f877527a8cb324045450deebbff413cf9e43c8ccd190797cbd939f1f0700b2e08ae13bbabcafa9f493f68d59b39cf0ed0444444e08d37de407373331e7ffc7119f8dbd6d6068ee33062c408e4e4e4d0899e042fadadad54d793f842a7d3a1bebe1eafbffe3a1a1a1a6447fba58baed2d2523cfffcf358b060010d1edc6e37f47a3da64c9982dcdcdc0e6d2b96a549211c0e070513c9fd0c0603f2f2f2f0f1c71f536dd3ae3c7fa7d38941830661c48811b45d198d467aa4b7b8b858764d966551515181b56bd7d2fa29efc7b22cec763b1e7df451ac5cb912b1b1b1d40f168b05c3870fc79831636830643018c0f33cdc6e378e1f3feea389ec72b9b066cd1a1416169e51890bf25c2d160bbefaea2b8c1e3d9adedf6834e2da6bafa519a06d361b6503c5c5c5e19e7beec1934f3ee9c3ae5eb56a15faf4e983ebafbf9e2617b45aad888888c055575d45b33e4b59120e8703a5a5a5d4d7d220ba2b9b0767db789ec7dcb973b164c912f4eedd9b1e19b7dbedc8ccccc4e0c183699be0791e0683016eb71b4ea71345454534e0276d4cea5bb576bd68d1220c183000e9e9e9b40db7b6b6e2c20b2fc4902143e8bd743a1d8c46239c4e272a2a2a68fb238ba2b6b636ba0038dfe47c9c4e275e7ae9250c183000175c7081cc0f4a9f4bfd505959a9ea07b7db2df383d7ebc5dab56b3174e8504c9e3c999e52309bcd484b4b436666266557116696288ab0dbed148823ed373c3c1c5f7cf105366dda24dbd83b556b6b6bc3b3cf3e8b850b17522d7c52ce51a34661fcf8f1743386b43ba7d389f6f67634373753309db43bb50db4909ddbe6f178f0da6baf61c08001c8cacaa263a9b41f90766a3018a0d3e9e0f178d0d4d44437be94279776edda75caecf9ff34d0beb0b0109f7df619aebaea2ad86c3688a288d8d858fce94f7fc28d37de48e3998888080882809a9a1aaa0b4c009760fb16999bbffcf24b8c1d3b963e0b83c180193366c8e6e6e3c78fd3b2dc7befbd983f7fbedff92358f0bcb9b9190f3cf00056ad5a058ee3e838d2dede8ed1a347d3b18568b2f23c0fbbddae1a5799cd661a578ba28803070e60fbf6ed18356a145c2e17bc5e2f5252523073e64c0882008ee3a82f5d2e17aaaaaaa85e35c3305df265c84e3ea621a7add472c928c955fefe569356203fd5a41ca4bfab259823edd19f8cc4c9acfbb4d69a4ad0953092c9a94eadfc4727f2317935d7af6aec65e97dc89c4ec064a94408e95f24a626cf4c7ada947c87b0cbed76bbcc5f5a3aca6a921b6aa7cc495f579e2c963e7ba53e34f9a9062093fb7445f6530a2687468590852cc8412610c079e617e02265e99e4c32403ae89ce56120d8724b83982ed75514b177df7efce5e14751545c442702a3d108a3c904a3c90483c108bd4edf217fc172b281f76cfb83017bd2ed8b65bc282e2ec5638fcda78c48b2c00d0f8b405878048c61a613d21f92247d67c30fb260e83c9da2388ec3ca952b316fde3c59f2183276381c0e98cd66b4b4b4a0b9b919adadadb0582cb2e42824b02c2b2bc3b3cf3e8bf2f272bf5a8a0cc360fdfaf558b468914c26421445d86c3658ad56d8ed760a68343636d2442dd20548515111de7aeb2dcab2ec5ae24b8682b5168b05369b0d76bb1d2d2d2d686c6ca409f7a4816f5b5b1bde78e30d1c3972c4effd789e474d4d0d6ebcf146949797cb12d5b85c2e58ad567abfd6d65634363652b04a7a4d8ee3f0f6db6f63dbb66d674d9b926559bcf2ca2b3e41acd3e9445b5b1b65354a03c1c4c444180c061f29128fc783679e7906cb972ff7495e66b3d960b15860b7db61b15864cf5dbaf024fdedd5575fc5eeddbbcf7956b2d48fd5d5d578e49147505858f8ffed9d7b8c1cd59defbfd5d55d5dfd1acfdbe3b1673c367e045012405a44088978edde2bb21236af40fc0737383cb2125136466c4872898374b3974b1eda0dcb5e09f6460b110b812812892242202426c60f66309018bfc65c338feef13c7b7afa595d557dff68ff8e4f9fa9eee919f78c61efef239566a6a7abcea9734ebdbef53bdf5fc5f4e462b188b9b93964321964b359a452294c4c4c20994c0a61517e481a1818c0934f3e59d55757d775cccece62dbb66df8f0c30f2bc69f6ddb15e32f954a617c7c7cdef8f3fbfd181d1dc5eeddbb313333f3b16967af76b8e9a69b70ead4a925b7433c1ec7eeddbb914c262bda81a2cbbff18d6fe0b5d75eab9896ed380ed2e93432998c38874e4d4d617a7a7a9e08679a265e7ae9253cfdf4d362464623d9b3670f1e7ef8e10acf5b12db53a91432990c32998c489a3a3b3b2bbe4bed6018069e7ffe793cf7dc738b3ed732e7ffdc93c964b063c70ebcfdf6db15e3948e031aa7744e9f999999773f609a267ef6b39fe1c5175fc4ecec2c27395328140a78e2892730383858f1d293ce27743f35313181999919710f45824b5757d7a2dad4eff7e3d1471fad58a7d6b5d9755dcf6bf352090402181c1cc46db7dd86743a5df1dc532814c47d5c369bc5cccc0c26262644224f3900e1dd77dfc5e38f3f8e4c265311ccf0e31fff1893939315e754ba17a097c9e3e3e3482693152fff6cdbc6ead5ab797caec079857c8ee5685cd9fb588d40ae262057b346b02c0b8542c17351ed0c6a2562ab967caf5692b9859e1daa450dcbc2b0571bc8e5aadfa9eb19db6386b4ea2ded650b42bf5b965521c293d81d0c06110a852a8466b97e6a84b0ea9fec65774142773db616eaf7bc16d5e28416afcfe5cfe476603199616471569c58962a847d44f6a44602b91a2b2dbfc545496e2b1f3449b8ac475c5dea5bf172a2426060e03dfcc33f7c1b87de7917baee136fe243a170595036cf88aafeb2a84a4bbd6dddc844748d7cb0d43460763685bb76de8dfff88fe7118d44e1f7614b53cd000020004944415407e0f71b304d13e150a4bcff2113c1a089e01961993c8ceb6987468be81f777f369fcf875ffffad7b8f9e69b71e4c8918a68a47a2c5db2d92cde7cf34d7cf7bbdfc5891327303636b6e098d0751dcf3efb2ceebdf75e24120911f5574f79f97c1e6fbdf5161e79e4111c3e7c18c9647251896bd2e9348e1d3b06cbb2162cd3e7f3c1b66d1c3972444c0ba688bd85ca999e9ec68e1d3bf0f2cb2f0b6b8e7adad4711c2412093cf4d04378f9e597313d3d5d21bad773c3d9488e1c39826f7ffbdb150f6c5eede4ba2e8e1d3b866f7ef39b22e3bad7f71e7df4513cf0c003c28bba9e36a107d4f7df7f1ff7df7f3ff6eedd8b8989899aedd26861b2117cf0c107b8f3ce3bb177efdebac70445ad5194f8f7bfff7d1c3b764cd85ed46ab76ddbb6e185175e58f09896ff57281470e0c0017ceb5bdfc2c8c8c8bcc4524b79217cae6376a965caedf0f39fff7cd1ed70f0e041d10ea74f9ff61cfb8661e0fefbefc7eeddbb2b5e7a2dd437a55209a9540a3ffde94ff1d4534f09ab9d461fcfbaaee3d5575fc52db7dc82a1a1a1aac7b1571b148b454c4c4ce0273ff9099e79e6190c0d0d55f5c96d4424e0729fcbd448a8a55c2b577aff1ad126b4df5ffef297f1c4134f88c8d47ac7e9cccc0c1e7bec313cf7dc73989c9cac394b86ce598b9d85584fdb2fb5fd96d26f8b3de7689a86c1c1413cf8e08318191999273ca9a2155deb4f9d3a855dbb762197cbcdbbce2c543e952747e556bb369f3871020f3ef8a0e7b579e9cf2be5e4c777dc71070e1d3a54d73d952cacffe217bfc0c30f3f2c5e1ccbf579e79d77b06bd72e91d363a1b6b46d1b274e9cc0d7bffe758e4c3e0f788d3dafe85dea3b554c9485c05a02b22a12aa9ebcea76ab09c94b39be17fabe6aa7a18ac9ba62494a11cc5e11c5d58ee75a11ddd5bc8abd8455d922c4e7f3c1344d4422110483c19af7bb5e22b297a02c8bf95ebec75ec2bfd7ffbc120c564beee7b5c86dc036170c5371e5d7a4935039395955014d934e40ba76de224ae488424d2bfb3c5788af9a6f5ee23df50244fbb3529ec91a005df7c11f0820142a278493bd9eabe137ca53b4cf255ad5e773f1c107ff177ffff7bb70cf3d5fc1f6eddbb02ab60a3e9f1f7ebf81125cb8e2026cc12db9706d17aeeb548d226bb4e8a97a6235eae64dd300db76f0831ffe08838383d8b9f32e6cda74018a450d7e7f00c1a009dbb1e19eb9d094df7e16451b50b2c7e516797dbeb2a7f37f869bd6603088b1b131dc71c71db8fefaeb71fdf5d763e3c68d686f6f472c1613f60f14f9924aa530393989e1e161fce10f7fc081030744344abde79850288443870ee1e69b6fc6f6eddb4542bcd6d656442211312d9ca287a9bcbd7bf762cf9e3d623af662314d13bb76edc2a5975e8a1b6eb801ebd7af1765d2f4388a521e1d1dc5c18307f19bdffc46444dd79f5cb22c5c3ff0c003f8fce73f8f1b6eb8019b366d4247470762b11882c1a07808ca66b3989e9e462291c07befbd87dffef6b7989999412a95aa88e4f13aa60f1f3e8c89890984c36100e52842d98fba1162d48b2fbe88743a8d5b6fbd155bb66c417373b3f0614ba552181d1dc5db6fbf8d5ffef297989d9dc5ecec6cd53a070201bcf4d24ba2ef2fb9e412747777a3b9b919a15048f40145ca8d8d8d61646404fdfdfd3878f0a0e887c5fa4c9ecbb1f1ecb3cfe20b5ff88210afe9c160b111bbbaae2393c9e09e7beec1b5d75e3b6f4c18862144bc743a2dc6c4c99327b167cf1e0c0e0ec2b2acba2d3e7c3e1fbef7bdefe195575ec1f6eddbb165cb16714cd3f1251fcf434343d8b76f1ff6efdf8f743a3d2fe24cd33431463b3b3bc58388611875cf0a715d17fdfdfdd8ba752b42a1d0d9e98755cea33485fcd4a953686e6e86ebbad075bd22d2b89e767fe49147f0ca2bafe0a69b6ec2e6cd9bd1d1d18168342aea4eed30353525da61dfbe7d9eeda01e83e170182fbcf002f6efdf2fc6f49a356b2ac6b4ebba624c4f4c4c60707010bffffdef71f8f06164b3d90aeff946631806e2f1386ebae926dc78e38db8faeaabd1d7d787b6b63644a3d18a732d8d85783c8e63c78ee18f7ffc23e2f13872b99ca7b50ff5e9ebafbf8e3befbc53f48b699a75dba3f87c3e8c8e8ee2cf7ffe333efde94f8b6829d3341b7a1e3b79f2248e1f3f8e2d5bb688ed9aa659d7faa3a3a3387cf830dadbdbc5b8a776ab07dbb6d1dfdf8fcd9b37578cfb5ae2f6e9d3a7f1eebbefce3bd696da267ebf1f8f3ffe385e7ffd756cdfbe1d175d741156af5e8d55ab5689fea273efcccc0cc6c7c771f4e851fcee77bfc3a953a7904ea7ab8e01627c7c1c03030358b3668db8769d4b9de9181b1818c075d75d27fa4bbe27a935aec6c6c6b077ef5e6cdebc79512f1f8bc522fafbfb71d965975594592bc1742010c0fbefbf8f1d3b76e0fefbef17d736ba9fa16bfdcccc0ce2f1380e1d3a84575f7d15e3e3e3d8b16307bef39def60d3a64de21abe507d755dc74b2fbd846c368b2f7ef18bd8b2650b5a5a5a2aaecdf1785c5c9b93c9e4bc6bb365593878f020b66cd922f633140ad59d485bd7758c8c8c60e7ce9dd8be7d3baebffe7af4f6f656dc53512431cd003979f2245e7bed351c3d7a54cc4253cf7dc16010030303b8fdf6dbf1b5af7d0d9ffce427d1d5d585482422da8666af8d8e8ea2bfbf1fafbdf61a92c924eebcf34e3cf4d043e8eded15d3fa3f8eb36b3ecac81eb95e6d5bcd06427d1e955f0ec80262357154151ce5314a82ac2a5456139497f2bc5b6f14b39aa85efe4cf524a6ef503b567b79e22526cb51c3b4cf7299749fea25cad2392b10089467004722304d13f97cdeb35c5934f6b211a1bfe59984b4bf6a823e3931a7dc0e5e890d6581ba5a99d58212e5ef5b9605ada5a585d373320c80677efabf912b14d0d2d28a55cd2d300246cd1bd2f201adc31f08201289c0e7f343d735dcb5f31eec7d731f7ccbf83c5e2a0176c987a7ffed9f61150a88c66268ef588da0119c77e2952f52674fbc3a82a6895028044dd3e1d77db8fbbebfc39e3d7be1d3966f1a93eb02fff5bf5c83dbbf782b56ad6a465b5b3b0241033e4dafeb02524e4e50dec7bf1c3e8c9d3befc1dc5c6ad16d475c74e127f0a31fff00eb7bd7575cc86cdb41a9a45e80edb2a7b0b3fc960f9aa6c1e7f7c1080411344d68f0c1b62dbcd57f083bbf721f749f7b4e6347d300b7e4c38e3b6ec3eeddff1d8e335f4092db40f5543a9728a47a6e2cca5e5441e8ba0f8ee362edba1e7ce2139f107ec21f476cdb46301844575717babbbb2bc45d7af848a552387dfa3446464690cbe5c4cdc952c43deab3582c86dede5e747575a1b9b919a6698aa9e4f4104209f8e886a85679e4813c303080b9b9b98a9b965b6eb905838383686e6e465f5f9f107e645fd3c9c9498c8c8c607272524cb15aaa78495ec03d3d3d58bb76ed3cf13a93c9606a6a0af1787cd1e5d9b68d4b2fbd14175f7cb1b8798bc7e33878f0e0397b23ca589685d6d6566cd9b2056bd7ae452814826ddb48a552181a1a422291101124f5d49b6ef03a3a3ad0d3d383d5ab570b41951e3e6766669048243036362632522f759c9dcbb19ec964b06ddb36747474889b6512b8971a4d4a4962d6ad5b87b56bd7a2adad0de170587847d30b94d1d15161b140e7f8c59647c7744f4f0fd6ad5b87d6d6568442a18ae3797c7c1c434343c8e7f3358f67d775d1dbdb8b2bafbc527826cecece62fffefd989a9aaaf3459c0f575c71057a7b7b45191f7cf0010606063c0514d77571e18517e2924b2e110f8c53535378e38d37c454ee7adbc1300cf4f6f6d66c87e1e1e1259dd7684c777676a2a7a7079d9d9d624c53345e3299c4d8d81846474745c4d04abd18a1736d2412414f4f0fd6ac598396969633f757dabc733b25c4a2e47db55e6ad9b68debaebb0e3d3d3d705d177ebf1f478f1ec53befbc53d7b9ac582c62cd9a35b8f2ca2b850d4eb158c481030770ead4a98608ed966561c3860db8fcf2cb85a0ebf7fbf1e69b6fe2f8f1e30b4664f5f5f555e410482693d8bf7f7fdd092a755d17e39edaede4c9933874e890e7b8771c073d3d3db8eaaaab4499a9540a070f1e3ca77b0c122dbabbbbb16edd3ab4b7b78b970a74ee9d9e9ec6d8d818128984b81ed57b0c747777e3739ffb9cb867989b9bc3fefdfbab5af3d47bec5e7ef9e5d8ba75ab68cba1a121bcf5d65b35fd9b1dc7417b7b3baeb9e61a98a629c48537de7803a3a3a335c7b4a669f8cc673e83bebebe8a1712fdfdfd35ed68e8385bbf7e3d366cd880b6b636f162249bcd626a6aaae2de82c4b1bebe3e5c76d9658846a388c7e3e8efefaf3903c5ebdadcdddd8d70382caecdc3c3c388c7e355afcda55209baaee3b39ffd2c7a7a7ac431f1fefbefe3edb7dfaefbfe99ce01b1580c7d7d7de8eeeec6aa55ab2ace7db3b3b348241288c7e3759ffbe87970c3860de8ebeb434b4b0b4cd314c23cdda7d10c2e3a576ddebc199ffad4a7100a85303c3c8c818101b6e86920575f7d3562b1585dc26aad635316fc547b0a554c56ed10e87a2b0b9794ff201c0e23140a211c0ea3b3b3135bb76ec585175e888e8e0e140a054c4f4f0b7b1fdbb6cf06b279f82b7b3d4b569ba14a8106246ae7f379140a850a11d72b625a15c7abd99856f39c264b4cd91f99922486c361442211d11ee17058f8aad367e45d3e3c3c2c927db7b5b5a1b5b515a55209894402c78f1fc7d0d010d2e934f2f9bcd837cbb2e6d59d22aea90ef493ac2bc99f5f4e0a48d75fb92faa09c96a523eaf447d6a9f2693491693198678e1f97f87cf17405b5b3b42e148c514895a3745badf2f2c018012eebaeb6eecddb77ff9c564d787679f7e02baaea3adbd13b1a62604fc866764ab3a4d847c73fd7e033e9f064d03eebe7705c4e49286bfbde1aff1775fbd0fcdad2d8846622232b9be8706ff998ba58df7fe7c183b777e65d162b297b07cd75dff0ddb6ebc113d3debd0d4d454f146b87cf17545b24257fa5f2393e5a9db2c0be87ee8baef4c8457be216272e50da50f9ae6e2873f7c0c9b2eb8001b2fd88058ac098eed48170c67de8577b945a6f2b8d5e1ba0e8a451bdd6bd77decc5e46afb29ff7eaec92c56a23c2f31192847e9dd78e38d3875ea94b879a955e672be8cf0ba615c6a7210f51ab09cfee172fd1bd54e2bdd074b6963556c6a641bab6362258eb1a596d5a8f1a64ee55c287ad1cb2bf05cda66b9dbfca33ea61b7d6ef73a461613912adf532c751b8b1dbb7222a38fe2b85f8973fb728cd3461fab8d38072f655c9deb98ae759ea995d366a93940cee5da4cedb3986362a5cf7d8b6d4b35c293690c575d7595980db7d8674b55fcab668f50cd63975eaacb11be72d23bb26c088542088542e8e8e8c0d6ad5b71f1c517a3abab0bc5625188c934e3a656646bade767af88651246291a369fcfcff32a96a37bcf3ebf3b55a393d5dfd585046c6a0339e298da8184e3502804d334110c06cbf998ce2cf97c1ef1781c63636322a1766b6b2b5cd7c5c8c8088e1e3d8ae1e161a4d369140a05e472392194cb09f864719beaa18acaaac04c22fc62c4e47a0465b93f53a914db5c300cd1d6d609bfdf40341643281486df1fa8db38feec7492e28ad6391289a269553362b1558844a3084875a6c8e9b3376a956fb87c3e7acb67a3b452491534201030b06a5533a291723b1bc12034addee898121cdb81eb3646c0d5b4b2a0fcd4ff7906bffad5afd0b7be0f7ff3377f8daf7ef53e38ae83a265c3e7d311089c6dd385bc1b1b854ff7c175cafd522c5acb529ecfe7a25402763df0205635c570d14517e2f2bffa2bdc7adbadb8e0828db0ac221cdb77f66154f72da2afcef9f1147651fb4f3b8d6e3945adf3511eddb8d4ca92bc52fbd8482172251f969643105be93e584a1b2f67529f9514191b2114354a1c5acc361a3dc697bbcd3fea637a39c4f3460850cb799c798dddc55817adf4b85f8973fb728cd3e5a8f3b98eafa5acdb88f3fe62ce33e77a4e3a97f5d5647e1fc573df62db9205e4e581acb6bc7c7d171a0f34d654cb027976a9eaa7ab46237b4525979f157df3229ac9271828db3a50c239f20f27e157b59f50f7a9da8b1ed58b5f16a0291257fe1eed937ccca9dbaa96a7421593d575e4baa9f5a17da476a15980b4df814000e17018b1580c966509819a661d90685cad4fd4fa55137fd53e57add4a85d962226cbebca2fc568bf594c669833b4b777c0765c84c391b2b5409da259f9e076611757769a30341f9a9b5b1189c6108d3695a757185e7e87e588e952c9af5c94caf52e954ab0dde517ec4aa5723dfc7e3f82a6095d2fd7d5b11d00ce8253eb09c7b1cf9c601bf360a469800e0713135398989842ffc000fed7633fc07df7dd8bafecbc0ba61984eed3a1f97c0034f803fe72f2c02aa259236ff0cb63b004d7f52f9bef6359502f617636857dfb0e60fffe03f89727fe15b1580cf7dc7337be74c7ed686e6941d12a9c8964d6566ceab06d17cfde88f334ba8f3c814080a73b320cc3300cc330cc22902d5316f247569f9754fb01d933598d4e5623965531f2acb65112b30864b1d3b66de4f37964b359e1056c18e584ee994c46f8aeab3616b228aa3eefd24f39604b7db6a61925640526d755b63452d7955fe8a809f9aad5438e06f69a1122b7bb2a02abfb1b0e87b16ad52ae47239188621fa3a93c9c0b2ac7991d4aa5fb1da1f5e8b5c1fea4f751f6badaffa25d7f37d5a87c5648639433a938355b490cd15ca89f716a1898883cb2e9ef1fc2a0ba7cb0179de6a2520972b00d051b46df875a3acb7691a7c9a06b754024aa53349f73468d044e240944ae5da954a704b25941c1b563e8f4609b45e685a092801478e1ec3934ffd1bce25d39fa601d353d335b35f2f659b846ddbf8a77ffe57fce89f9e44dfba76f4aeefc5c5175f8c40c08f6bafbd06ae5b3a13f54dedb57cd1baaeeba0e438c85b050c0e9e58164d55dda6e338989949e21fffe70ff13ffef1c78006ac5fdb8935ddddcb1e91a046c9d3857c7a728a4f521f71584c6618866118866198c541c2ecd96733adeaef24acaa51e5aa985c2d12551691bd12aec962aa2a96168b45e47239cccece62666606994c4624c60d8542c2fb9c129cd27ab2205acd968e227755f1947227c9b97564f155b675903fa73692cb546745a8cf2d7244b65cae5cbe2caecb22bcdc7eb4cf866108ff7cda762693413299442e979bb72d352a59d6996a25c4f34a8ab890d548355fe48516791d169319e60c5fbe6be79993ebd2b7e1f3f9303c3c0c0dcb27b8d1394fd71c7cfd1bbb84488c12ce88c5e4cf4527a2caf5e65192ebbdbc681a70f8f051fce52fc71ab0314083bb6c01abbacf850e0ba3890446e209bcf9e64100c0bf3cf1e4f91ba41aa06b2b6349a29d294b47d90a83dae1fced7a8983933fc250640279b4330cc3300cc3300cb3308542c1d3a2c0cba2817e7a599f2c14495aebb3cae740cdd3ce41d33491f4321e8fa3bdbd5d7804373535a1542a2193c9886490f45ca0463ecb3f6b954d7f93b00b54261af412bce57c517294aefc77f5e7dfb3b9a75431d9cbcecf2b9298da4a7e3ea244d0a9540a13131348269322d11ed964a8c2bfdacff54626cb7f57f33897ebaefe7f21f15a1e132c26330cca82ebd1a3c71bb6bd9510bd34ad84c1c10f1abccd95a9b7a67d7cfcb68488a9fdffeb11561e172ce632f36fb664383299611886611886611647a1501062ad2a22cb760b5e760c0b8980d5bc70e5c85da25a423a5920751c07c96412f1781c2d2d2d88c562e8eaea42535393a85f3e9f17026f35afed6a62723d893429291f25e1230f62350adbab3d54bcc46c5548a6f2745d9f272ad3dfd446baae57e4fca1ffe7f3794c4c4c60626202b95caec2875a1694bd6c2ee47d57ebad7e566d1c78fdbf9a384dd44ac0c791c90c230e52ae37c3304c3d388e833ffde94fb8e28a2bc4cd926118222146ad2ccd0cc3300cc3300cc39cc5b2ac0a8b05d96ac22b4a96f0f227a6bfd54473f453b5c8a866614822aa0cf90467b3598c8f8f231a8d221289c0300cb4b5b5211a8dc2e7f3219fcf0b81bc1e31598da255cb94856d4a02288bc924e4aa51ca6a19d5bc88d57aa97d208bc97ebf1f7ebfbf425ca6769223b855313e9d4e63727212737373f312ef79254254a9471caeb6afeafa0b514b8c966131996118866198bad0340dc562113b77eec497bef425b4b4b4880881b9b93916921986611886611866115896e5291ed3bdb72cb8ca8b2cf6aa51caf473a1df6554df64a0ec512c6f9b6c24e6e6e6303a3a8a402020f6a1b9b9198661c0300c116d2b47e92e2464564b9047e55239d96c16baaea3582c8aed93d04d11caf236d5c52baa5bde6f7591fb84c4e4402080603028da47b6d290fb87f6a5582c229bcd564450cb62782dd1b6da33d962519fd3d4a484b5847fafcf594c6618866118a66e344d83dfefc773cf3d87402030efe6926118866118866198fa28168b22819c2c10cbd1ca14154bd1b05e3ebef55a21a89fc9eb568bd4a572a90eaeeb22954a61686808b66d2393c9a0bbbb1bededed0887c34270350ca3ee76a89684508ec0a6e864d50f19808816267b0d42f52256b75fed33394a99da9bfac1300cf8fd6539d5719c8a75e564829aa68976903da46b25bf53dba4563f7aed432dc15815dabdd62fd599448cc5648661188661160d25bd906fd41886611886611886a91f5918251b0a59c495c54bd962c1cb63d98b7a7c89e9f35ad1cbaa90ed380e52a9141cc7413a9dc6f4f43456af5e8d96961684c36151e76a75ab267ecbfb43cf1ab2cd05d968589605cbb284b84cfec9aa5733452dd7b2f5506d4154bb0bd9e6c2300c98a609c33010080444bfc87de4f3f9443e19d334118944100e87c53e794561d7aa9bd7ef5ec2bf1c4ded6579e23566e4efa9d629b5c4651693198661188661188661188661186685217151d77521e69170190c06456e92402020c44b39199c973ff262c465f97335719f1a45abfa1093905c281430373787f1f171c4623184c36184422121ac7aa146437b45d452f9d426b2304cc2322d854241585fa8d1c8b2ad44b5885c595cf51292c9da22180c22140a21140a21128920140ac1344d98a659d14f86619413d5f9fd884422e8ececc4d8d81892c92480f9c915178a0af68a9c96ebaefa3ccbfdaa0ac4846ccb21b7bbdacf5eb098cc300cc3300cc3300cc3300cc330e7111203494826e1927e57c564599004164e845d8f6f3189b872423b8afa95055efa3ed95e148b45a4d3e90a3155f614f612bceba90f099eb28f3489a5854201b95c0ed96c16b95c0e9665095f62b9ae547fd553b99ab5056d5fed0fd334110e87110e87118944108bc5108bc5108d462b3e0f8542d0344d445087c361747777636c6c0c894442589a54eb432abb9a1d47b5248df41242f6dd96db8122ca354d9b67cf417f574beaa8c26232c3300cc3300cc3300cc3300cc3ac3072d42f0997148d6c9aa68882a528e5402030cf37793162b2977029ff2421960458126169a1ff91282bd7a5582c229fcf0b1b0eb58ed52c14bc04cb5ae23389a6002aa293f3f9bc884e964571791f4814af26e0ca02b8fc59301844381c168235d96bc88ba669300c03a15048b481e338d0751d6d6d6d58b76e1d3efcf04364b359618541db92db94ca55a38fe5dfa90de4855e36c87edab2984c82b2aeeba2fe54473581a01aa1acc26232c3300cc3300cc3300cc3300cc39c07642199a27a49bc24cb081293493894a353bdb64754136965bcc4648a36a6048172a42b452bab7ecfb288ac46cf562bbb1aea7ec9c2a66ddb0020ac27483496ad2de4c85b5940a56dc862a9bc6d127f5531d6711c21fcd2a2466fabb61c2478bbae8b70388c356bd6a0afaf0fb3b3b3482693a2bf49d82d168b55fb5216ed5501598e06a7be92fb526e53b94f1cc781cfe7836ddbc293da4b68f7f26d66319961188661188661188661188661ce03b2184b022979f186422184c3616173e19580cf6b7bf52474f3421693654b0d004234a528641241e57d90a3915521793162f24249e30cc398279ecaeb92fd86aeeb701ca7c2fe814465f9fbd46624d6ca62b6fc7f6a032a8ffa4df529a69f2412bbae8bd6d6566cdebc19a74f9f462a951251cf7ebfdf53a057f75f8e4696ad44e4c48c3446d4be54ad4b687b727f1194f8908471353a5cd33416931986611886611886611886611866a551854212090dc31089dd4cd39c6771b1587196ca5a0839699d2c3252123bdbb6c5e7f4999a484f16936b89def47dd527b856342dfd1e080400008661c0755d5896252c2d48ec95856c8a380e0402a2ee7284b25ca697dd831c9dacda5cc8c90be5c865fabf6559c23b79eddab558bf7e3d128904666666449d6cdb162f0964a15af543a628645908d6751dc16010d16814d16814a150a8e23ba55209c56211d96c16e9741ad96c16f97c5ed4511593691ca851caf277584c6618866118866118866118866198f3801c99acda1790882c4725d33a4b29a7decfd5485539025616886541595ed76ba9a7de5e49f1641b102abf502808619b6c41643199c45f35b2571580bdfca355715c6e07d93f9aca927f168b4558968542a15011395d2814100c06118bc5b071e346241209643219148b450402012180932f35004f2199b649048341b4b7b7a3b3b3136d6d6d686a6a82699a15d1ca2472170a0564b359643219cccece62727212c964128542619eafb5bca836171c99cc300cc3300cc3300cc3300cc330e709553494c564d96a42f6f2ad6565b118bcfc70a94e14dd4b62a72c50aaa2a36cfdd0081159b599a0fa509d48b4252b0bc3302a12edc91eca6a7dc82f18989fe0cfcbb683c45bda4f5950a6a863cbb244f244fabb582cc2300c21e65a9685502884d5ab5763d3a64d88c7e348a7d30020ac2ee43ac8d615b45014b1aeeb88c562e8ededc5a64d9bb066cd1a44a351cf047ceabe398e835c2e87f1f1719c3c7912894402b95cae220a5d1594d53e62319961188661188661188661188661561835519decc12b5b19c89611aac8d7a87aa8a8e57ad921c8f521b152de2ff577afb2bc8471d5368322a0695dc77190cfe751281484a7b49c248f0451b29c90b7a5d6cdab6c59d0a57548a026113997cb094b124a90a846289ba629b64911c0e170183d3d3de8eaeac2d8d89810a269fb5ed627b217b2699ae8e9e9c1e6cd9bd1d7d787d6d6561886512106ab6345b50691ed5382c12086878791cd66c5fa729f92882ef71d8bc90cc3300cc3300cc3300cc3300c731e90854d55c095854f55445eac985ccbe662a1847e6a423d596c94976adb55cbf71273e5ffcbf60ae4772cef3389c9f97c1ed16814a6690adf6159885721b15e8ea856db924468d92642de67d932c2300c84c3e18a686535629912e5158b45f87c3e048341b4b6b662eddab5f8f0c30f71faf469944a25f8fd7eb17d001591ead416d168141b376ec445175d84bebe3e44a3c6337b09000001204944415451b8ae8b5c2e372faa9a6c3de4647b725f9aa689eeee6e21640f0d0dc1755d11892e8bf76affb098cc300cc3300cc3300cc3300cc3302b8c6c41e0380e2ccb423e9f175612249aca8222d1283159fe9f2a5e53246ea150403e9f17c9dbc817d8b22c11fdeb250a03a84884e75517af48651512761dc711961ba5524908aea6692297cb893a91cd042d721bcb823c45deaaed4adfa5bf293a9ad6218196a2865dd715ed93c964100e87118944108944100e87619a2602810082c12022918828c3eff78bb6a4fd233199047a12d39b9a9ad0d4d484969616944a258c8c8ca0582c5688f86a74bbdca77294b26118621dcbb284f5472a9512ed27f72fb5bbebba48a7d3d05a5a5a4a7cf8320cc3300cc3300cc3300cc3300cc330b5f87fadd1edf5c35b47fc0000000049454e44ae426082
);
INSERT INTO `foto` (`oid`, `fotoname`, `fototype`, `fotosize`, `fotocontent`) VALUES
(3, 'piedepagina1.png', 'image/png', 12002, 0x89504e470d0a1a0a0000000d494844520000044d0000004b08020000000aebee78000000097048597300000b1300000b1301009a9c180000000774494d4507de0c1a172e12ff6340940000001d69545874436f6d6d656e7400000000004372656174656420776974682047494d50642e6507000020004944415478daeddd695c13d7fe30f009901040ad107604441665b11070411645c50a888a80280a8a1b6de5225a77fa57ac0ba02d082a5a0b58f5caa26c6251a8b8942d8a1ac08b6c0181404864072d5bd89e17f33837371b11d12afebe2ff84c66ce9c73e6cc6fc29c3933131c89444200000000000000601c11832600000000000000403f0700000000000000a09f0300000000000000d0cf0100000000000000e8e7006172737357ac58817d74707078fcf831f6d1c3c3233b3b9bc9645654549c3f7f5e5959195baba5a5a5a5a5854ea7c7c4c460f351f9f9f92c164b565696b314def4464646341aada5a5a5aaaa6aeedcb9c24b0460ccd5d5d5999898601fb76edd9a9c9c3c62845b5a5adeb871e3e5cb970c068342a1040505a9abab73ae82a2d1689c59cd9e3d1b5b3d373797f3884b4f4f6730189595957ffcf1c7dab56bf91e177c0f2b4d4dcde8e8e88a8a8ababa3a0a85121c1c3c69d224d8ad4074bce127ca373f6f30b7f0e3e9e9292874f9162d2413f8bf0000807e0e18633e3e3e010101616161c6c6c6cecece93274ffee38f3f646464d0a5dedede6a6a6a565656626262172e5cc0d6323131919090a050289cdd27bee95fbc784126931104993f7f7e7e7efe882502f031f18d706767e7b8b8b8bffefa6bd1a2455f7ffdb59f9f9f9898d892254bb04346fb2d3333332cab070f1ef8fbfbf316e1e9e979f6ecd9e8e8683333332b2babb8b8b8cd9b37f31e177c0f2b1c0e979090d0d9d9f9cd37df90c9e403070e904824454545d87140447cc36fc4ef61bec18c857d4343c3a64d9bd0e9ebd7af0bfa8fc0b7684199c0ff050000f473c018939292dab367cfbe7dfb9293939b9b9b4b4a4abcbcbcc4c5c5d7ad5b8726181818e8ebebabafafbf75eb968a8a0ab6a28b8bcbf5ebd76363635d5d5d393314945ef41201f89878239640200405051d3e7cf8fcf9f3743abdadadede9d3a7fbf7effffdf7dfd1557a7a7a3adf7af3e60d96556464a4bebebea5a52567fe929292010101fff77fff979898d8d8d8d8d8d8181b1bbb7cf972be95e13dac545454a64d9b161c1c4ca7d35b5b5bb3b3b3b76ddb565555053b0e884250f88df83dcc3798b1b01f1a1aeaeeee46a7d96c36dfd0155434df4cc4c5c5e1ff020000fa3960f4222222aadfba78f1223ad3d8d8585a5afa8f3ffec092b1d9ecb4b4346b6bebff86889898a6a6a68787476a6a2a3667e5ca95d7af5fbf7dfbb6818181aaaaeaff84144f7a4ea29408c047fd12fcdf882593c9b2b2b2b1b1b15cc9868787790f252a958a25e8eeee0e0f0fe7ba0a6e62623269d2a4a4a424ce997d7d7d7cabc17b5835353531188cc0c0400b0b8b891327c2ce02ef4450f88df83dcc3798851c41bca12b7ae4c3ff0500c08723014df085387cf8f0fdfbf7d1691b1b9beddbb72308222727d7d6d6363030c099b2b1b1d1d4d4149dbe74e9123a71faf4e9e0e06074dad2d2b2a6a6864ea7230872ebd62d6767e773e7ce0949cf69c41247c7d0d050c4948d8d8d4a4a4a9ff8ce824a624a4a4a3e68febc118b86287a959a4020949797a309020303a3a2a2b80ea5c1c141aedcb66fdf6e6b6bdbd3d383057c7373339a9b707c0fab8181012727a7bd7bf74646462a2a2a5656564645456175fed0070b04ffe71efc82c24f94ef61de607ea7d0153df23fceff0588abcfae921ffacb1f403f078c2badadad757575e8744b4b0b3ad1d6d6262727272121c1f90f464949a9adad0d9ddebc79f3ad5bb7a2a3a367cf9e8dc3e1d04bdaaeaeae666666e813d84422b1a6a606ebe7f04dcf69c4123fc21722b6f99f32a8e4581918181013fbefc0b58484446f6f2ff6913762dbdada4824129148ecedededefefb7b1b14110243c3c5c525292f750e2d2d7d7171a1a7ae0c081808000744e7b7bfbe4c993f1787c7f7fbff07a0a3aac6a6b6b7d7c7c1004515050b0b1b109090961b158e9e9e9e3f8ec01827fac080a3f51be877983f99d4257f4c8ff68ff1720aebea84a028082fbd6be68cf9f3f7ffdfa35e7d3020402c1d1d191f355510882ecdab54b434363efdebd68020707075b5b5b1b1b1b1b1b1b7373736565653d3d3d41e94757220063a5b1b151535313fba8a5a5c56030b8d270466c6161616767e7c68d1b1104191e1eaeabababababe3ec1a091713134322911c1c1cb08067b3d95c0fe4108944aeb5041d5644221187c3a1699a9b9b131212a854ea8c193360b70211bfe1f9869f88dfc35cc1cc97a0d01531f2e1ff0200e08382f19c2f5a4f4f4f5050d0a953a7242424727272141515fdfdfd0706066262623893bd7efdfadb6fbf4d4d4d7dfcf8f1840913aaaaaab09b79100479f0e081abab6b606020dff4595959a3281180b1929c9cecefef5f5f5f5f5d5d6d6161b166cd9a4d9b3671a5e18ad843870efdf2cb2fd2d2d2a9a9a96d6d6d7a7a7ae84ba55113264c209148e8f4d0d0507b7b3b67566c36fb975f7ef9f9e79fabababd1800f0c0c3c7dfab4acac6c4646c6d0d0d082050b366ddab474e952ceb5962c59c2f7b0ba7bf7eed1a3477ffdf5d7fcfcfce1e1615b5b5b6363e383070fc26e05227ec30b0a3f51be87b982992f41a11b1818284ae4c3ff050000f473c00774e9d2a5fefefe1d3b76848787fffdf7dff7eedddbb1634757571757b2a74f9f9e3a75eae2c58b2f5fbebc7bf72ee7a23ffffcf3d0a1439cfd1ccef43e3e3ee8eb7ab3b3b3d7af5f9f9f9f2f6289008c89d3a74fe370b88b172f2a2929d1e9f443870e3d7cf890371916b10b162c888b8b6b6e6ef6f5f5ddb163077a1fceeddbb7535252d09467ce9cc1d6427b415c59c5c7c7fbf9f9611f232323dbdadab66fdf7ef4e8d1818181a2a2a28b172f1a1919a13fe3831e172e2e2e7c0fabf3e7cf5328943d7bf6686868e0f1782a95eae1e1c1794e0980707cc34ff46f7eae60e62528740303030515fd3eff890000e09de0b00b9300000000000000303ec0f33900000000000000e8e70000000000000000f4730000000000000000fa390000000000000000fd1c00000000f0201008bababa8e8e8ed01400802f8db8b4b434b40200e0cb242929b97bf7eec6c6c6f7fce575003e41727272cecece2b56ac603018d3a74f6f6d6dede8e8806601007c39603c0700f0e55ab56ad5b265cb6a6b6ba129c0f8b365cb1643434322913873e6cc67cf9ecd9f3f1fda040000fd1c30dee4e6e6ae58b1e253abd5a54b974e9c38c139e79b6fbea152a908823099cc050b16c08e03ef4f5353333a3abaa2a2a2aeae8e42a10407074f9a34095beae5e5e5e7e7373030807ea4d168464646ef5a445c5c9ca7a7e7981ca72d2d2d4d4d4d2f5fbe4c4b4b5bb56ad5fbe7f9e91c4ae8d6616eddba35e69b30563b62dcc0425d474787cd66f7f5f5e9ebebbf530e2dfc086ae4b2b23213139377ca5f5b5b9b46a3cd9d3b174190c3870f739672e3c60d34cdd4a953e3e3e36b6a6af2f3f3d7af5fcfb96e4242424949c97ffef39f93274f1208040441f0787c7272f2b163c744299144225dbb768dc160949595613f872a3c074195411064d6ac59090909b5b5b50d0d0d2a2a2abceb1e3870e0c993270d0d0d542ad5dbdb5b9475f1787c6868e8c993273913f36d28454545aedd54585888a6e7bb9900403f07800f2b292969e5ca956262ff0d42171797a4a4240441962c59f2ecd9336822f09e70385c4242426767e737df7c4326930f1c38402291141515b1047676764545459f4e85bdbdbdb5b4b4162d5a74f3e6cde0e0e03d7bf6bc67869fd4a1e4e3e3a3fd96bbbbfbe7b8099f978a8a0ace66acafafe70c7e11fb21a88686864d9b36a1d3d7af5f1fab1a9e3973263434343f3f1ffd78e3c60dac442f2f2f04410804c2f5ebd79f3d7ba6afafbf63c78e23478e2c5dba144d7ce5ca95870f1f1a1a1a5a5959191a1aa267f0fdfdfd5bb66c7173733335351db1c493274f0e0c0c90c9e475ebd66ddfbeddd6d656780e422a337bf6ec9898985bb76e595858cc9d3bb7b5b595b7682291b86bd72e6363e3fdfbf71f3e7cd8dada5af8ba7676764f9e3c59bd7a356f56bc0dd5dcdcaccd61dfbe7d4c26134dcc773301807e0e18e7984ca69696163a1d1212e2efef8f4ed368343f3fbf070f1ed0e9f4cb972fa317c91004515656be7af56a454545414101763d6fdbb66d542ab5aeae2e2b2b8bf7dbf3fcf9f3341a8dc964522814dea760333333a5a4a4e6cd9b877e949292b2b7b747fb39898989bababa820aa5d168dedede1919190d0d0dbababa63586130cea8a8a84c9b362d3838984ea7b7b6b66667676fdbb6adaaaa4a4870dad9d9e5e7e7d7d6d69e3d7b168d25be29353434121313d13122ec1ab9b6b6764a4a4a6d6d6d6e6eae9d9ddd28426e6060a0abab8b4ea7474545b9babaeed9b367ead4a98272a6d168bb76edcacacaaaafaf3f71e284a9a9697a7a3a83c1b879f3a6acac2c9a063b94041d26eaeaea4949494c26b3a0a0e0c58b17e891f2ae1be2e3e3831eb9d8f7c9f1e3c779b7aea7a7a7f3adaeae2e110f706c133ee68e181f9e3c79824d77757551a9d4acacac77ca01db5f434343dddddde8349bcde6bbe384fcafd9ba752bef7c6b6b6b5555d5a8a8286c0e9bcdc64aeceeee4610c4d4d4544141213434b4b7b7373f3fffead5ab680f595c5c5c5757f7f9f3e70882bc7efdbaa0a040434303cda4bdbd3d3c3c7cf7eeddc24b2410080e0e0e414141cdcdcd858585f1f1f1cececec27310541904410202028e1d3bf6ef7fff9bc96432180c369bcd5bfa912347f2f2f25a5a5aeeddbb575454840d1d0b5a372323834c26fffefbefbc59f136d4f0f030e71c1f1f9fd0d050e19b0900f473c017cac2c262fffefd7676765f7ffd357a31494c4c2c3636964aa51a1a1aae5ebddadfdfdfc8c868e6cc99bb77ef767373d3d3d33b74e8101e8fe7cae7b7df7eb3b4b49c3a75aa9f9f5f4444c4c48913b9bea9d3d2d25c5c5cb0f3cb9a9a1ace0b907c0b4517b9bbbb5fbd7ad5cdcdada1a1610c2b0cc699a6a6260683111818686161c1157e8282d3c0c0e0bbefbe737070303333434fce78538a89895db972a5b4b474ce9c391b376e449fedc6e3f1717171f7efdfd7d7d7dfb76fdfb973e7747575df27e48a8b8b5fbc78f1cd37dff0cd194d3377eedc9d3b77ae58b162cd9a35616161c1c1c1d6d6d613274edcb0618388c7f5d5ab57fff39fff181a1abababa3637378f6e43121212e6cd9ba7aaaa8a2088a4a4a49393536c6cecc8ff7e443ec005edb28fb3233e5f341a0dfb46ede8e8e8ebeb1b9bf306c13b8eafecec6ccefd8859b9726552521276d7288220cecece2f5ebc78f8f0e1bffef52f74cee4c993070707878686b02d427bfe838383172e5cb874e9929797979191d1e2c58b2f5ebc88e573e3c68d050b164c98304148891a1a1a0402a1aeae0e5d545555a5a3a3233c074195993469d2dcb973172c5850565646a7d32f5dba4422918434889494949e9e5e6969e928d615d4509c366cd8c062b1eedfbf3fe2660200fd1cf0253a76ecd8d3a74fcbcaca1e3e7c889e5191c9e4afbefa2a3c3c7c6060007d7860e1c285d2d2d292929253a64c191c1ccccece4e4f4fe7ca078fc71f3972242727e7f2e5cbd2d2d2e86910a7c4c444474747090909f48b3b2121817329df42d145bebebeb1b1b1797979e8a5acb1aa3018670606069c9c9cfafafa222323ababab2914cae6cd9b8507676868686161616969697474f4e2c58bf9a63432325255553d7af4e8ab57af2a2b2b592c168220666666d2d2d21111113d3d3d140a252d2dcdd5d5f53d43aeb1b1515e5e9e6fce6882e3c78f171616161616161515454646666565d5d4d46466666a6b6b8b725ccf9e3d5b5555f5f8f1e3edededd5d5d5af5ebd1add86343535656565b9b9b92108e2e0e0505353839ec371898888a87e6bca9429a21fe08276d947db119faf848484fefe7e04418c8d8d959595c7244f213b8eafb56bd7f26df059b366e5e4e4601ffffdef7f2f5ebcd8dedefee4c993dbb76fdfb2650b8220cf9e3d2312893e3e3e9292924a4a4a73e6ccc1d26764643099cc3973e6dcb973a7b2b2b2a6a6065bd4d2d2f2f2e54bde8785384b9492924210a4b7b717fdd8dddd2d2323233c0741959932650ada359a356b96b9b93989443a7dfab490063975ead4f3e7cfb3b3b347b1aea086e2ec44eddebd3b30305094cd0400fa39e08bd6d5d5855ef85457575756562e7ccbd1d171d2a449f9f9f9616161a1a1a1743a3d292969dab4699ceb6a6a6a262525151515d9d9d9191a1ab6b7b7733e8a83cacbcb63b3d93636365f7df5d5a2458b9293933997f22df4c355188c4bb5b5b53e3e3e868686060606a74f9f3e72e488bdbdbd28c1d9d4d4242727c737a5a6a6665d5d1de775680441545454582cd6f0f030fa91c964aaa8a8bc67c8292929353636f2cd992b654f4f8fb8b838368ddd9626fc30515151a9abab1b1c1c7cff0d898b8b5bbb762d8220eeeeee3131317ccb3d7cf8b0cd5baf5ebd7aa703fc9fdd119fafeeee6e6c776083e7ef6914dfcc7c29282830180cec634d4d4d6565657d7d7d4646466464247a6b624b4b8b8787879b9b5b4d4d4d6a6aaa8e8e4e7d7d3d82207272727171719b366ddabe7dbbb1b1f184091322222238336f6868e07d1889b3c49e9e1eb4f38c7e2412899c9d6abe3908aaccf0f0300e87bb7fff7e5757178bc50a0f0f17f26abb63c78e1919196dd9b2050dd1775a57484361befbeebbb2b2320a8522e2660200fd1c303eb1d96cf44a8f28582c1683c120bfa5afaf8fbe2a2d3c3cdcccccccc4c4a4a3a3232020807395d9b367979797474646b6b7b70bca766868282525c5d9d9d9d1d1b1a0a0007b6e5278a11fa8c260fc211289381c0e9d6e6e6e4e4848a052a93366cc102538d5d5d5e9743adf94adadadbca7502c164b5555152b4e5555151d5e1875c8191919191818a4a7a70bcaf9fd353737cbcbcb8fc986646464904824474747737373ae0b169ced56f7d6c0c0c03b1de0ffe08ef8dcd168b48c8c0c0441d4d4d456ae5cc9b9485555557897f89dbe5d4701eb8e72919191c17ed22a2b2b6bc18205aaaaaae6e6e6fdfdfdf7eedd4310444f4f4f4c4c0c7d237c7b7b7b7c7cfccc9933dfa9c4fafa7a369bada7a7877e9c3e7d7a7575f588abf3ad0c7ab100bb9b545c5c9cefef714948489c3d7b964c26af5cb9f2f5ebd7e84c11d71584b3a11004993c79f2bffef52f6c3067d49b0900f473c06766c28409a4b7d07b8e6934dafaf5ebb5b4b43c3d3d47fca9ec828282fefefe83070f9248243939395b5b5b6b6b6b1b1b9b0d1b369048a48e8e8eb6b636ae53463a9daea3a3636262a2aeae7ef4e851ecc1682e494949f6f6f6eeeeee898989a2142ae2f68ea2c260fc313232ba7dfbf68a152b949494141515d7ad5b676c6c9c9e9e2e2438d17b72ccccccbcbdbd636262f8a62c2828201289dbb76f9f366d9aa7a7e7ac59b31004a152a95d5d5dbebebe525252161616cb972f4f4c4c7cd790939090909696565757dfb4695362626260602093c9e49bf398b44f414181b4b4b49797979292929393137a97cee83684cd66272727878585a5a7a77776768efa201594f823ef8871263b3b1b7d27c1dcb9731d1d1db1d14b6d6ded0d1b362c5bb60c7b887f4c769cbbbb7b646424f6313e3ededede9e6f379bf366e653a74e5959592928282c5ebc78e3c68dd89b2d141515e5e4e4f4f4f4424242141414e2e2e21004292d2d65b3d9dbb76fc7e170323232cecece0f1e3ce0cc5c4d4d0d7dde4c50897d7d7d77efdefde1871fe4e5e567ce9ce9e6e6969a9a3a620e7c2bd3d5d5959a9a1a1818a8a0a0a0a4a4e4e7e7c7750f367a68c7c7c7cbc9c96ddcb871707050464606bdce28caba5c04351482207e7e7e8f1f3f467f9e41c4cd0400fa39603c3873e64cc55b172e5c4010c4dfdfdfd6d6363333d3c2c2e2d1a347c257efefef777777d7d5d5cdcbcb2b2828d8b973674f4f4f535393ababeb93274f4a4b4be5e5e5b97e70e0e9d3a7972f5f4e4949b973e7cedf7fff8d5dbee2525454d4d4d4646666c6fb7b1a7c0b15717b47516130fe5455555128943d7bf6e4e7e7171616ae5dbbd6c3c3a3bcbc5c48707a7a7a1617175fbe7cf9d2a54b9999997c53f6f6f66ed9b265c3860d77efde353737afacac44436eddba758b162d2a2f2ffff9e79f7d7c7c2a2b2bf9869ca5a5654b4bcb8c1933782bfcdb6fbfd1e9f4acacac152b56ecdab5ebdcb97382721e93f6e9e9e9d9b66ddbf7df7f5f5050e0e4e4845efa157d43b8c4c6c64e9e3c59d04d6b221ea482127f881df145b979f3e6d3a74f1104b1b0b0f0f5f535303050565666b3d94545451d1d1d4b962ce1db1519dd8ed3d2d2323737c73ece9f3f5f4d4d8d379f67cf9e59595971667be6cc99a2a2a2a0a0a013274edcb973079defe2e2525656969292323838e8e4e484be8eecf5ebd76bd6ac717070282b2bcbcbcbabaeae3e74e8109695bcbcbcb6b636f60332824adcbb77af9494d4f3e7cf131313a3a2a2b01285e4c0b7320882fcf0c30f0c068342a13c78f0203f3fff975f7ee15a514646c6c6c666e9d2a5151515743a9d4ea73f7cf850f8baab56ada2d1689b376ff6f0f0a0d168d88fe0096a286565e52d5bb604050571152d64330118f770a2bcd9030000c058d9bf7fbf9595d5f2e5cb3fb58afdf9e79fc78f1fe77c34fc9d585a5a9e3d7bd6cccc4cd0cd48e01fb76cd9324b4b4b749acd66130884969696e2e2e2c6c6c6a1a1a1172f5e7ccccacc9f3f3f3c3c7cce9c39e89b12c6d0f6eddbadacacd6ad5b37ea1205e50000f8bcc0780e00007c54d6d6d6972e5dfa442ab364c912030303595959171717757575ce3b5ede959797577c7c3c74723e65b76fdfbe7bf72e3a8d3e99232f2fbf70e1c2b56bd73636367ee4ca6467673399cc6ddbb68d6db6b2b2b27e7e7e212121a32e51480e0080cf8b04340100007c34442271ca94296969699f487d2c2c2c3c3d3d252525cbcaca366edc38ea77319148247b7bfba3478fc22efec4fdf5d75fadadadaeaeae9c3f22d4d7d7f78f3cb3b463c78e8c8c8c828282c78f1f8f4986783c3e3a3afac68d1b827aec239638620e0080cf08dcb7060000007c591414145c5c5cb0d70f24262616141440b30000c619716969696805000000e0cbd1ddddfdecd9b3cece4e1c0ef7ecd9b3b11a4e0100804f0a8ce700000000000000c61b780fc1972e3737177b5b2582200e0e0e637e618fc9642e58b060142b4e9d3a353e3ebea6a6263f3f7ffdfaf582929148a46bd7ae31188cb2b2323f3fbf11e78b0e8fc78786869e3c79f293aa15f812825f50f889183f1f28fc0e1c38f0e4c9938686062a95eaededfd89d40a000000e00bde43003eb8254b96a0bf5afd4e0804c2f5ebd7131212bcbcbc8c8d8daf5dbbd6d2d2f2e79f7ff2a63c79f2e4c0c000994c9e32654a7c7c7c494909fa1bd582e68bc8cece2e2828485e5e3e3636f6d3a915f812825f50f8891e3f1f28fc8844e2ae5dbb2a2a2a4c4c4c2e5fbe5c565686be84fa9fad15000000c0178ce78c7f3226ab47b7a2b6b6764a4a4a6d6d6d6e6eae9d9d1d3a9346a3797b7b676464343434e8eaea9e3f7f9e46a331994c0a85e2e8e8c8374d6262a2aeaeaea00c11046132995bb76ee52addd4d4544141213434b4b7b7373f3fffead5abeeeeeebcab100804070787a0a0a0e6e6e6c2c2c2f8f87867676721f3459791914126937ffffdf74faa56e04b087e41e1273c7e3e42f81d3972242f2fafa5a5e5debd7b4545454646469f42ad8020df7ffffd4f3ffdf4a3087efae927ce013a4eb9b9b92d1c6834da7bd60a1be42c2b2b333131f9679ba8b8b858c43a2c5dba343939392f2f2f2727e7fefdfb3e3e3e9c2fac0300403f07fc33ba8a12849fed45444454bf75f1e24574261e8f8f8b8bbb7fffbebebefebe7dfbce9d3b879eae2108e2eeee7ef5ea553737b7868686df7efbcdd2d272ead4a97e7e7e11111113274ee44d336286d9d9d95832cce4c993070707878686b0d3c7a953a7624bb1553434340804425d5d1d3abfaaaa4a474747c8fcf7f469d60a8cb3e0174478fc7cccf0939292d2d3d32b2d2dfda46a05b8d4d7d7e3f1781911e0f1f8a6a62641f9f8f8f868bf656666f69eb55ab264c9b367cf3eaf963c7cf8f0b66ddbf6eedd6b6969696d6dede4e4242d2d1d1b1b2b210137c50000fd1cf0699fed1d3e7cd8e6ad1f7ffc119d696666262d2d1d1111d1d3d343a150d2d2d25c5d5dd145bebebeb1b1b1797979dddddd783cfec89123393939972f5f969696565555e54d3362866bd7ae4d4f4fe7aad5b367cf8844a28f8f8fa4a4a49292d29c3973389762ab4849492108d2dbdb8bceefeeee96919111329f93b2b272dd5bd8860bf7116a0520f885f42e84c4cfc73c284e9d3af5fcf9f3ececec8f532b303a9d9d9d292929838383c2930d0f0fdfba75abb5b55550829e9e9eceb7debc79835de5d9b56b575656567d7dfd8913274c4d4dd3d3d3190cc6cd9b37656565d1347cc73cb1414e4e820636b76ddb46a552ebeaeab2b2b26c6d6d11c163adfbf7ef7ffcf8716d6dedf9f3e7252525111146656fdfbeadacac9c9696565757e7ebeb8bc6ffd5ab572b2a2a0a0a0a3c3d3dd155ececec8c8d8d57af5ebd6cd9b2172f5e3c7efc9842a1e4e4e450a9d44d9b3689de1aeaeaea3131312f5fbe2c2e2e3e7efc38df399c78970adaa2118be66a6a2d2d2d743a2424c4dfdf1fedca262525616942424278eb0300f473c06779b6d7dada8a9dd9b4b4b4a033555454582c16f6d3e64c26534545856b454d4dcda4a4a4a2a2223b3b3b4343c3f6f67631318111254a869c5a5a5a3c3c3cdcdcdc6a6a6a525353757474eaebebf9fe03461004bb7f804824a2279782e6736a6e6e5ef0d6850b174469c68f502b00c12fe4745394f8f9d007c5b163c78c8c8cb66cd9826ed147a815181d7b7b7b6565e56bd7ae0969d2fefefe9898184545457b7b7b416938873d397f3d73eedcb93b77ee5cb162c59a356bc2c2c2828383adadad274e9cb861c3063481a0314f5e7c073667ce9cb97bf76e3737373d3dbd43870ee1f1782143a3d3a74ffffefbef972d5b462693b76edd2acaa8ecead5ab5fbd7ae5e8e8a8a1a171f6ec593131b1d8d8582a956a6868b87af56a7f7f7ff4cecc6fbffd161dc97172729a376f9eb5b535fad0ddafbffeba72e54a115b434242222e2eaebcbcdcd4d4d4d9d979707090770ee7b6f32e15b24523ee8811252424cc9b370fbd58232929e9e4e4c4f56420009f291872fd52c898acee2a4a103d3d8bc5525555c5e170e8a98caaaa2a8bc5e24a337bf6ecf2f2f2c8c8c8b1ca904b565616f6aeaac4c444be8f26d7d7d7b3d96c3d3dbd172f5ea0ffeaaaabab85cce7343838585353f3ae2df9a16b0520f80511317e3edc4121212171faf4692d2dad952b57be7efdfaa3d50a8c4e6f6fefbc79f3242525e3e2e256ad5a252727c795a0b3b3332121c1cccc50def46d00000e4c494441548c4c26f7f5f509cae7f0e1c3f7efdfc722049b7ffcf87174af151515a5a6a66665652108929999a9adad8d2640c73c67cd9a3561c20474ccb3a2a2826f116bd7aee59d292d2d2d29293965ca94baba3a74f0d0dcdc1c1d1a1d1e1ec686468382821004090d0d452b131515b56cd9322a952a28a5afaf2f9a920b994cfeeaabafc2c3c3110479f9f2655a5adac2850b5fbc78a1a0a0505d5dbd6eddbac4c4447438ebefbfff4610a4a3a38348248ad81ab366cd9297970f0c0c1c1c1cececec0c0808303737e79ac35919bee9056dd1883b62444d4d4d5959596e6e6e6161610e0e0e353535e85da9007cee603c07cef3f8a352a95d5d5dbebebe525252161616cb972f4f4c4ce44a43a7d37574744c4c4cd4d5d58f1e3dca77885c940ce3e3e3f95e4a545454949393d3d3d30b0909515050888b8bc31661abf4f5f5ddbd7bf7871f7e9097979f3973a69b9b5b6a6aaa90f9efefd3ac151867c1cf97f0f8f9d0e1272121111f1f2f2727b771e3c6c1c141191919f496b37fb6564008f449425353d3d7af5fc7c6c6be7af58a736973737342420293c92493c95c1d182e9cc39e7c1f27ebe9e9111717c7a6090402f28e639e7ce5e7e7878585858686d2e9f4a4a4a469d3a6893234dad4d4242b2b3b8a41547575756565e5c2b71c1d1d274d9a8420089a09dfcaf3ed1cf26d0d3535350683c1d9c8bc7338f12e15658bf8162da2b8b838b4b7e9eeee1e131303870f807e0e18b7e7790882f4f7f7af5bb76ed1a245e5e5e53ffffcb38f8f4f656525579aa74f9f5ebe7c392525e5ce9d3b7ffffd37767df75d339c3f7fbe9a9a1aef2a2e2e2e656565e8fde54e4e4e6c361b5bc4b9cadebd7ba5a4a49e3f7f9e989818151575e7ce1de1f345b46ad52a1a8db679f3660f0f0f1a8d86fdd0ca3f5b2bf08504bfa0f013123f1f3afc6464646c6c6c962e5d5a515141a7d3e974fac3870ffff15a8111ddbd7b77f6ecd94e4e4e376edc282f2f4767565656a25dd0458b16a1bd6e1c0e37b6e562639eedededa3ce243c3cdcccccccc4c4a4a3a3232020001b1a4597f21d1ad5d4d4a4d3e9a2a4447b77580786c56231180cf25bfafafa274e9c4010a4b1b15143438342a1ac59b366ead4a9f3e7cfd7d7d747bb224c2653c40d6132996a6a6a9c9d25de39c2d38bb8452362b3d9e8150a2e1919192412c9d1d1d1dcdc3c3939190e1c303ec07d6be39ff0f33c2b2b2bce8f77eedcc14e32aaaaaa9c9c9cb8d2ebe9e9717e3c7af4e8d1a347d1e953a74ef14d83fe4b109421fa7dcdb76e172e5c10f48400e72acdcdcd9c2f771e71be885252525252523eb55a812f24f805859f90f8f9d0e1d7d9d9292f2fcf77d13f582b20048140e8eaeaeaeeeeb6b1b1211008ab57af4e4a4a6a6c6c2410080505052e2e2eaaaaaa8a8a8ac9c9c91d1d1d421e9e99306102894442a787868644ecb760639eadadaddbb66d133ee6191f1f7fe5ca15ae7772d8d8d8686868dcbe7dbba3a3a3adad0d8fc76343a3919191643279f9f2e5d8e3f86432b9bebe5e4f4fefdb6fbfddb56b9790949c1a1a1a162e5c48a3d12425250b0a0afafbfb0f1e3cf8db6fbf0d0f0f9b9a9af6f5f5e5e4e44445450504046cddbad5c0c0e0c2850b252525999999bebebee2e2e25c3fe32b04954a7df3e6cd810307ce9d3b3779f2e4f5ebd787848470cd090a0a7277775fb46811faf605aea5bffcf28b285bc417962d8220341a6dfdfaf55151515656568e8e8e57ae5cc1fa3fc9c9c9616161e9e9e99d9d9d70f880f101c67300000080f1f80f5e4cacadadcddcdc1cbd7f494545c5d3d3b3b1b191c160787878a05d503c1e6f6363d3d8d828643ce7cc9933156fe5e7e78b58fa3b8d79f21dd86c6a6a7275757df2e4496969a9bcbcfcb163c7840c8dae59b3e6f9f3e7515151616161f7eedd136554164190e0e0606cc8b4bfbfdfdddd5d5757372f2fafa0a060e7ce9de84b32323232aaaaaaae5cb972f7ee5d7b7bfb3d7bf6fcf8e38f1b376e5cbb766d6161a188adc166b3ddddddbffefaeba2a2a2f4f474191919de390882686969999b9bf34d2fe216f185658b2088bfbfbfadad6d6666a68585c5a3478f3893c5c6c64e9e3c196e5a03e3090ebb48030000008071233030f09dd2a3af18fe1cd16834676767be6f17182b9696965e5e5e1a1a1a381c8ecd666fdbb66d74b78d7dca2c2d2dcf9e3d6b6666863d0504c0e70eee5b03000000c6a19292123d3d3dce670891b7cfe1709dc8120884929212683121f2f2f2f2f2f2c6f7367a7979c5c7c7432707403f07000000009f34b80109888e4422d9dbdb63cf1c02303ec07d6b00000000000080f106de4300000000000000807e0e000000000000007cdae0f91c000000601c5ab76e9d8181c1c0c0c0f0f0300e871b1a1a1a1a1ac29e32979090a8aaaa8267780000e3188ce700000000e3909191514d4d0d8bc56a6e6e6e6c6ceceeee1e1c1c949090e8ebeb63b3d9c3c3c33366cc109ec3f2e5cb9f3e7d8a7d4c4d4d8d8e8e46a771385c6565a5b1b1b1a07569349a919111ec0500c03f08c673000000807168787898c562d5d6d6eae9e9959797cf9f3f5f4545059d83c3e1343535d19f0a158242a14c9d3a554949a9b1b19140204c9f3e5d4747075d3463c60c7171f1e2e262686700c0270bc6730000008071a8a7a7c7d4d4f4f5ebd70882bc79f3a6a9a94952529240204c983041464646424262686848780eadadada5a5a5161616088290c9e4a74f9fbe7efd5a5b5b1b419079f3e63d7af408cd415959f9ead5ab1515150505059e9e9ed8ea767676f9f9f9b5b5b567cf9e251008e84c2693b975eb56ae82dcdddd6fdfbe8d4e474747fff4d34fe8744949899191d1fb2ce52a8846a3eddab52b2b2babbebefec48913a6a6a6e9e9e90c06e3e6cd9bb2b2b2681a7575f5989898972f5f1617171f3f7e9cef1c4ebc4bb5b5b55352526a6b6b737373edecec442f9a1393c9d4d2d242a7434242d05f71f5f1f1494a4ac2d2848484f0d6070000fd1c000000603c1b1a1a929696d6d6d65655559d3d7bb69c9c1c8220626262e8833a226692939383f673d08ecda3478fb08fe8ef668a8989c5c6c652a9544343c3d5ab57fbfbfb63bd0b030383efbefbcec1c1c1cccc0cebdb646767373434709542a1504c4c4cf0783c1e8f9f3b77aebdbd3d8220aaaaaa4422b1b4b4f47d96f26ed1dcb97377eedcb962c58a356bd68485850507075b5b5b4f9c3871c3860d0882484848c4c5c59597979b9a9a3a3b3ba377fa71cde1cc8d77291e8f8f8b8bbb7fffbebebefebe7dfbce9d3ba7abab2b4ad1a248484898376f1e3a10272929e9e4e4141b1b0ba10e00f473000000802f080e871b1818983e7d3a9bcd969393c3e170d5d5d5bdbdbd4a4a4a0a0a0ad2d2d2a2f476727373b18e0d8542c9cbcb9b376f1e8220e6e6e6393939088290c9e4afbefa2a3c3c7c6060e0e5cb976969690b172e44d70d0d0d2d2c2c2c2d2d8d8e8e5ebc78313a73eddab5e9e9e95ca5d0e9f4e6e6662323230b0b8bbffefaaba7a767c68c196432f9c99327434343efb394778b8e1f3f5e5858585858585454141919999595555353939999898e53cd9a354b5e5e3e3030b0b3b3b3b2b232202080770e676ebc4bcdcccca4a5a52322227a7a7a28144a5a5a9aababab28458ba2a9a9292b2bcbcdcd0d41100707879a9a1abe7d3900c0ffbf12014d000000008c4b838383ededed3d3d3d121212c3c3c3d8797f7f7fbf9898d884091346cce1d1a347dadada0a0a0a060606c5c5c58d8d8d3ffef8a3a6a6a69494544949098220eaeaeacacaca858585687a229178edda35deb373743449080a85327bf6eca953a7a6a7a7d7d7d7dbdbdbcbc8c8a04346efb954909e9e1e7171716c1abdb34e4d4d8dc160700edaf0cee1c4bb147d080a7baf1d93c99c32658a28458b282e2ecedfdf3f2c2cccdddd1d5e97078070309e030000008cd3fff16262e80937fa57ec7fe170b81173e8ecec7cf1e285b7b7777979f9e0e0208bc51a1a1a5abd7a35f6700e8bc5623018e4b7f4f5f54f9c38c19589baba3a9d4e17a59f337ffefc870f1fdeb973c7c1c1814c263f7af4e8fd97be132693a9a6a626262626648ef0f42c164b5555156b5b555555168b358a9ab0d96c292929def91919192412c9d1d1d1dcdc3c393919821c00e8e7000000005f1c1c0e272b2b4b2291e4fe97828282949494a0310a2eb9b9b95bb76e7dfcf831d6a9f0f6f6c6464b0a0a0afafbfb0f1e3c8896626b6b6b6d6d8d2e426f69333333f3f6f6c6461ee2e3e3d14768784b59ba74299d4eefeeee2e2e2e269148868686cf9f3f7fffa5eeeeee91919122b618954a7df3e6cd810307264d9aa4a1a171f0e041de399c79f24ddfd5d5e5ebeb2b2525656161b17cf9f2c4c444114be7ac2a8d465bbf7ebd969696a7a7a7a3a32367ff273939392c2c2c3d3dbdb3b313821c00e8e7000000005f9cc1c1c1a6a626d6ff6a6c6c6432996fdebcc16e9d1ab19f3371e2440a8582f573e4e4e4727373d18ffdfdfdeeeeeebababa7979790505053b77eeece9e94117797a7a1617175fbe7cf9d2a54b999999e8ccf9f3e7aba9a9f196525b5bdbd1d1813dba939e9e5e5252323030f0fe4bb5b4b4cccdcd456c31369bedeeeefef5d75f171515a5a7a7cbc8c8f0cee1cc9377697f7fffba75eb162d5a545e5efef3cf3ffbf8f85456568a583a6755fdfdfd6d6d6d3333332d2c2cb8c6a6626363274f9e0c37ad0130f2b51e128904ad000000008c33fefefe323232d8e93e57ff073d470f0e0e8686faec585a5a9e3d7bd6cccc0c7b0a0800c017bc8700000000189f70381c1e8fe79af9e79f7fb2582c2291686565054df439f2f2f28a8f8f874e0e002382fbd6000000802fc5d0d0506161218d46d3d1d1292a2a8206f9ec9048247b7bfbb8b838680a004604e339000000c03844241279670e0f0f2b2b2b2f5dbaf4d5ab57d82337e033d2dadacafba26a00005fe2d2d2d2d00a000000c038a3aaaa3a69d2a4dede5e3687dede5e6565e5d2d252ecfd690000305ec17b0800000000000000e30d3c9f03000000000000807e0e00000000000000403f0700000000000000a09f0300000000000000d0cf0100000000000080b7fe1fcfc2e1cd66d883a10000000049454e44ae426082
);
INSERT INTO `foto` (`oid`, `fotoname`, `fototype`, `fotosize`, `fotocontent`) VALUES
(11, 'logo.png', 'image/png', 51009, 0x89504e470d0a1a0a0000000d4948445200000150000000ad0806000000e69327aa00000006624b474400ff00ff00ffa0bda793000000097048597300000ec400000ec401952b0e1b0000000774494d4507e00402042f1a6d6eb6a20000001d69545874436f6d6d656e7400000000004372656174656420776974682047494d50642e6507000020004944415478daecbd799c5d459dfffdfe569de52ebd26e92c2440089b242c867d714914e5c15dc7ee195111b73082e0388aa3a33edd3d2e3338fe86191818c9b8a0e332939e67464161449c0401114c448144c0801896ecdd9deebecb59aaeaf9e3dc5ed3e90404017fe7d3af7addbee7d63df79e3ae77ceee7bbd4b7c439478e1c3972e478ea50f910e4c89123474ea03972e4c89113688e1c3972e4049a23478e1c3981e6c89123478e9c4073e4c8912327d01c3972e4c80934478e1c397202cd9123478e9c4073e4c89123474ea03972e4c89113688e1c3972e4049a23478e1c3981e6785a10252222f42a01111059b9ce13c9cec5d8b6def173231974f6a44f6f5a26018812567ab26959906def55489f06f1413c0191f32f2b0b7892ed56b14ebc7564ede4d5e28b48f699d2ebe5e725478ea77137e7e5ec9ea381cfc8cbb989274032f2740edbe8a3c93a9989043c7ae6fa40758295d76d28f2b993849ebf3a84816db319d8dac19c8307191cf239f6d487b9e4b2ad7ceb0ab7aaf297697c06fa6b171067fb07e79c1511e59cb3d2d7a55de71a939f9d1c390e0cb9f2f8431367ef4acf75af4d9d73761221f62117729272ac4ffba42b234e1a7d041144036ad11af4199dc46bc00d8092e329f0e43fbc84f70dbe84d06bc63695e9386a1bf59122bea7b8ffa715de7ea6e3351fbc6ef5b5ee4920ba2e634fbd82c51e42e4704e44029c4bf233942347ae409fbf030ee2c08988aced413ff424b2ea6c2c9d6427621d8a5ea003c71a1c7dc8d86bc03a502bbb962ad66cb49cfed9c3d8f1e377d136a7407bab47180450d224b566bc708078a4843343d46a09a61663d361def5a9abb9e4b48a0383209b968abf74534f8aebb6b2ac37701bbbe3fc2ce5c89113e8f39640c9c853ade8ce4c75fa10e942587592b86b37344c68670584de959aeeb50d725de7bb952b2271684e7cd5f904e13194e76b82b247359a85f6eba83a38a51014a2624459526b1197e254c263f7ec66f99bff3f96bef3317a3f943ad797f676e175af71f128b9e76729478e9c409fa7263cca7563c74c7740708e2ca0278066f5408113da1d3fbbbfc80d9f5fc2e0c022b497d2d636c2939df762beba8256ef38dadb67a1c32662eb937ac38c5403c27a0c2268cfa03d8db145bc628443119486d9f3443f233b7d7e75f3e781d80969a68781359d92fb4073e4c809f48530f4ead2d7e05f751369c69f787c9b12fff6d1956c7d70292eed200cab04058d53012296c073ecdc5d23f49b689f1763cd02c2a2c56189eb90480d95f838a7d07e3d7bf43c248c49a312a22c818e48862aecd9fd18eb6fb89663e7faeefe9d55a4075cb7cdcf4b8e1c39813e7f075c500e1c6b44d189139ce3e27565d6f69ecef0c0991c7e7a2b2e2da015281d605d91d04fd181c1444dd8ea206843cd16203088b6d8c4a7e0525c2ae8661f911a260d70a9c39060d300bf388c890bb4b61749eb3bd9f5db2a7ee907dcf69dbbd7f68abe7523aa7b8dcbfd9f39723c05e479a07f68381c3d226c42374cf7123fbfee325a0f39974527b4a2cc6c3c554228e18c87b89838b6d4468438a9607c0fa34202cf1158859f788462714a4108264e48230f8c41c4e289260852c414f1b4209504572d5268d154aa6701c595a73abdaecf5904a1571492e58cb25a7c7ab39c5300e9234010a457f54997467ad5e41f0751bdbde265fd65521eeb782724f3cfa21b8f22a005028142e37519dbc706f1e9130d92f595b11e6abc2fc83a3c017fd267e5c8912bd03fb2015fd61bb88d3d29382b2bdf7704bb1e7b0fb30f2e10d53b2896b722ae63f4d44c201d37f1944d771a67ec2713fef7c4274d63123b4265a84ac7929fd3ff78801d72585be7faeb6fc5e0d84eca49a42b564a78d145245d5d40967aa5003bfe7b801399f0612e23b0be3ea4ab2beb3731d7751de201ac00e8c2d18706d80c720d8bd4157f715fc8f65f7af8038ea5cd291d454bf3518e477ea5d9f0584067d308db6eb34bfbaf727f7e2ad25c444e5d815d0a6e1db815b834bfca72e404fac73ae0209c7f59898bff5ef39e977f82d98b9b084b25441ca69aa9c84964283393e774fd6452bfc9ef7181c3d814214291222e86aa8fd20e9f841d4f3afc703bc5d26ff9d1376e0352c03844320fc4d805e3008b203dac53ddacb00d021d75558c72a79be0be904e27aaefbac53eb78df86c3954e17b8e5f76a4f3b6be48e650958def3cddb167ab22fc9d6364836270d8436bc7ac5332623ca3ee31eb78cb514b0c6c4ca97dcb5db07db3bc0b583107c5eb5c7586c1175c9e65902327d017ee808b8438e738e6e597d0be683ec5a6d98405b08926aa6a7418ed4d98fb52a35315e83e8873e276ab1b54a20cda8f91c4c35782884394a59e54119350db63a90cede013ffbd863733f8b51ef12f78078e2348fafa904d9b909e1eecf884800641d1db30a1bbdd44b212e9d3d0a757b1c6ada62fe46d698157ceb2bc788ee2d7bff6a976c0e1c7c67cf68386a8a6d04f3a9a4b86a6a2e58ccd863d46b8755ec0c6335316cef598b748d17290e67165b0365d7c57313e85b9b68f6529d3ccf09ae442c9912327d017b0023dfc15e7520ecea57d91427bad8858b029a9b5f84aa625cf6915e92466763312e7e8fb3ce7c009d66a9ce7505e024a486d96061a789a38b10485adf43f61499347b8e43bdf77e753e55af1386ac2fe576494dcd7875cd3810c3f84acbfb0c74c244f11144b7b3d86366a4c4d166f7d6ff02849ca475b1dafdd23a41b340f6ef54894e1c58b53ee3a4b536a76cc5f6279e4579a47eef0f103c7c147a4849be4a2cff7a4b7eeec606347271c759e30fb2c48fa85add72b7ef93b58b431a230dfd27aac65c3aad48d12e62889e6049a2327d01734811659f2925e0e7a518a62095e38823359a49c30c14ffd19c953c4e19cec9338652ac14e31ed750c4aa738a5b1c603af4e02a446b092522a14d07e4ca512e0927e86770f1336ddc14fbf7a9713ec75f48477f27d732daf37b86edbdb2bdec66edc4520bdc0da093e483979b5cff65b3c8e0b7c00fcd071e856e1b0fb92d3363cee07fd842f69823f594a65f79dc8976f26ec7bc9710e4f3b3ace4c89ea427558517ad2b2e821c7ef629f251d86c84a3b8f003090ceb1cc990d1c0d87cf577c6787502c5be62c306c684a98fb72cbba15c6e5c4992327d03f82015ff1de37531f3e95a6d973f03d9fa49e501b2910b45490c043255388732ff37c3f0a743fbed03812022f23622d426a04e3c7d886295ef0059304482141a851af8e30b045b8e7fa7fe27a146fc090059146f76d00333a3d15d723f46d147a966996ed08684f35712ccc3f5528141dac56ecde9976dcb7d9eeec9fe7387abde59f1609eb29f140d4cad05d01bfba632ebb1fe9c0a2699a3dc831cbb771f0e22a03db7c3effc67ec0b08594fffd47c7adff441b8f32d87c84e5ecb738ee497cc2d0f164c5502c3ab65acb86a68465dd8635d87ca6558e9c405fc803feb23ffd18ba7830a5b62624f188470c3115f0bc2c8733327b99eb1911ee43614e519afbf383562546a51a4f41b19012559b098a159c731971c696b01c11db300b6cc575b03ba80d0c120d57090b73496a508fea106cc52f3fc223cbb6b89ddd550039f7489f394b3cf62c52ccf71461e83876a130b0045a12c5b70e4df1db1d1d4ba1e3c9761efbdeb1b0fd2578ee18fcb08db414672e0dcfe09cc21a8dc6827598286060cf003a7c18555e4fa17523c5255b69af2754f67814e6789cf33bc3c000f40f2a1edc961d73bf321c7e6cca4fcaa95bbf2a2f9892e319435e8de999c66af1b9d0a5b2b4d7676377e2b2d8b3c01adb0bc22b9a16608ca63294e2050629593c0248c18cc4784aedc37c1fa54499d16c576587b51a2529d67859a0c814d03a4bb42f9922e29b8c1c531fbf50c51add30b12374c947a4408080b3685f615d2ba5a044d0e6686e76d4f7689c6ba33c6b1636389559434392bcfff68f2ef8f27d9cf06091438e1aa24b0a2ce84eb8f07d054e5894f2f9f7baafccf9aa7defed2ee07dd79fc2ce1b5ec5efd2a3293415d07323d2d850ad8e103a9d1d56ac1a135bc7a796ea30a6a56d164db33c9c390953d5d47f56a53eef66e61e79279f5ef19bb51f177fe5690f44ccc5f2838f78dcfe831a6fbf32e08c4b02767ccb97cd9b15471c611ca4e3c1a55e991af4ca912357a0cfcd902ae9eb143ad7c055977a9db75de5d67462e874460e7ae5a11cb3f82f302604e5e3058288c52119813a83a7652fbfe7f4cad34d6bbac78942e904ed25882922ca0082f662acd5782645c4912621c6f8689d643e51ab70082631886f083c484ce66f3526c1d563aca4386613b64694ca96e1c1022eda89b85ff0d62bee22688ff8ebd72b8e3fb9c0311fb298b61a5789e2d4f34bdcf275f8dc4f9671fbb72fc60b7d4ab342c226c12f08d6585cec48d29420f4671c5e4b1d9334a37548b9a59fea1e9fea80a136b29b62cb6ff8dc677ec007ce7ee2a2437f1c5dc3d298f013b379e93baa7ff12f22fff8e2350596fd6e98913906b92076dd591a566334f31b21474ea0cf3556ae136fdd0ae758b7ce67c58ac42172d54de84b3f7dad65f77587b3e4980bf74da0a47b45e167f27d4e254f11479a4638db202127289da2f133b3d877a45111bf50cda2ef8d3ed67880437b093ab558e361521fab407b09be4a885221ae7978c58896b91a630a0cf70f31b07113cbcbdfe7212fe09cbf11bef9ca84c33f2aacf858ca972e2cf3868b6bb8fe83b9e7bf3e42cba239683f400506ed67dfd9261e69a470a9c3da042f9cd92aaa57c00b0d7e2088d688f548138b4dc1388b8d12cab3eea7f0db6bf08e1de2c5cb845b563b2efb8ae7ee91aadcbfaac08067d838376653773a5abc3a478e9c409f0f032aa2713d0ebaa5077123e753fcfb6f1009cef0a2d34e62e1b16fc7d80024c00bc988cd0918302e1d57a007185ddf8b6075968ea494c966538e1264639bd71491c601260950daa0bd046b74a3f048027111edc7682f45b4e0ecb84b41c41127069b7ad4072daf79ef7570efd679fff3be68fbb679f0962ff9149a1c7f75c730475ed9c4aa0d25eebcfe1c6afdafa1d454c69980e26cc1246013c1398bb309a2c6496cecf3f60167b2ef2162314901bf60513ee8428ab53ef57e4b9cc4949a15cd4d7fcb9c07efe7e597973baf90da5d0fa1b69cd8e388b034edb06c9c1bb3acdbb835e415a872e404fafc185144e8f29d5b13d32b8addf8cc269187bf51e4d73f3a8c96e03d5813e054801f80289ba5258d12a8523393e73e8873f4795056a3359b49ac45c4a19cc13a87b30a5d53289da51a3927197935d2a29436ec8eea8de35038abf0c34692bd2f58a39933dfb1e39184134fff77bef8ee87e9fd6e919d4f68dcdc885b2f86151f0e39f90cc3892b023ef0811ee61e311bdf9f8ff6ea98911455d6380c2e36b8d465ea5b343af01151a46934e3f8ea00d2ba469162628df6628c2de0950d4a14c60885a2d0ffbb419ae6b523fe5dc4375fc1dbae8db8e244c3928ff9c455212839d416c39265864d172479843ec7d3411e447a16e05893f6ca3aaf1b674192d770898f7b67c299f79490a1d1d424d7080c39c6ea5f3454deb4c9f03345e427106cb51e63231fe30c5a5589aa21267194db7770d0710ff9dfb8ead122af7e6c0fff3070188483f41614b3accfd14631db6dfd87930aecdca6a9ee521c7a6c423b963bfadab9f7a6c3a83d7928ca1e4952bb0f3bf85b4434abbee871d02a58de2cec5ce778d32752d63f7c049ff8d45fb1f0b800133763cd08be97a04a45ea2399ebc0a682731ae509ca73286d1065d1fb53a069800e623029ce7938a3801aa69e625c91e67921038f0f509e330f9356d0de32ec295fa6c207987f4a42588e09cb8ea822d84334f23b61e975d0754142ae4473e40af47960c28375ce39043992d9cd9bddee0a50e0e4b7be8fb6f683b136c04980df30e19d13c43a526b262bd0195293f6d567c7a343983420286ee784d7dec647debd8514cb7537069c79669d5fae2af08e9756d953157ef8ab0281e738f9f0984a5d7864bbc7e76657510f380eda001bb6c2f1f384a3ff44e0104d7bdde7f2ee94cfdee0b1e32735feed8b1e1d1719be7ab5e5b675b3f8f88a61ceffe487b1b294f2bc668226c1d41c264ab051336139228aecb8fa952c695e94c589c21a6f92393f2d81aa18d24276bc3633e5e3d80731289d62134db9a38978643749145268b5a4f522f5ea201fffc407f8ca576b001ca234f59af0f090a5b02b65d391719ee2942327d0e7dc826f44754f5eedb1e1424336c0014b575e46ebfc907253312350e58f99f0a33ed0d4a5f84acd1840da9f39bff425ffccdcc5355425a5dd8ff8da07bca377de97be6631fe8b0ce642b52d65cf2f34d17645f948c34167a53c798747e5379ab65353ce313ee5aae1812d96f496f124f8a38f71b4b571c1d797739d7d8fa1a3cbe7e1a1f88c757f69ee6496e2e15f79fcd9db2ee145af7e19512d261e495012e01703828221ae403c1c51682b8ebb108ccea69026992fd45a872ecc1c8537510a4aa17d85d61aa71cca394cc6a72867a8570d85e600af04f11e9f7a7d806858d3ba50085acfe7c82453b9f59a10d585f4b7293f9e5d67537792cf58ca9113e8734ca0ae4b1467a35885119ccf8bdf7c19733ada094a0169cd9f62ae8f9af1d9ffb534f38316d0f8ca9162218819368ec878f87184b60105cfa1a4087a8842dbddec0cd7afbdfdeafe0f2edbc9638e60e843d7d639eaa8809d351fff8c846f5f5de6ec8b46e8393f211d511c5df679e38a988d1b7d2a2f726cfd9d62c1b14967fbc57a6000d204b97f27c66fc76c6d7fb5b06b8f66b8aabf7dda7dfabc6d57c62cbbc4e7075f8dbb1f79afe9fd92d37cf58b9f66f6a12731bc3d1a3b164905e72604a1ac7bce4bd096da132e3fffede7bd5bfc6a05f7dd7739f8586fca9aeec29a9d927476323c7a678cbfa957841e47c6aef90d33f31da000aeeb21b8e0f5994ba4f772a4bbcf2502b84621f12e90bebe4ee85c93f99ea57161bcc0b22272027d36d0279a6b1059eb1ca7fde9ebc19e4e535b8856c569fd9993cc71af4a1a17702ac63985721e9e680ac51a82078187849638a961d5cfa8edb8fd4dac4ebf5b7da5e6e3b7c0cf3f086e99a5fd0329d77dbe89eddb2af45f956ebc1a4548b0ecfacb13e6cc81172f103cab9933df32b004caed8efb1e115eb6a44888a344c23c0cdf43f3d0cd1eedda70e62b6bac3947b8e3e71ef3ca31477c5e71e63bcbfcc747df4373fbc9145ae620aa327e7ca980932c5004cf0b02ad8ec0a223bf7ddad09f5e7fd7d92ee6dd47065cfb9be0a4d552ddf0d6f3423ef1ed3dc064213a5a18255f746fff84b2ac37606377d28348f798d134b13ad668a1edc6a2895907d5c35ae966857da111681e447a86d1db2541f71a52d9f8359f7e0292e1d3693fd8e192109bfaa830de37790212b7e25c4c82c606119e35f83644d7ca143c435a2e501fd98077d00f967defe1818d9483effed445d8073dbe794291cdbf8a2890707f7bf0b5af0d0edcf80d9ad7fd16bbecdbb70b4bce8a38f3168fbf3abb0e685efbf625d4d2325276149b23766c2bf3ebd3ab0c3e1a50a9d79975dc36fefa833bdc875f9d5e7798141eb899f0f2cfb8663e44ff922e091e59526e66e36da7d132f72cc2a2878d87d0c5711faea82cf22fcf23ce49eb113bb7bcfbae63d7de4f79e76628191e1d76671468dbf0f16f55f8c9dd3e376d4e70a349f68d594a390e0c1bbb0da07aba96ea9ef6a2e3ec2516fa26b9a1ba64a9d787585ccf2859da6eb7e20539c6b9027de687d403970a1439e52d6fa3d0bc8ca0d08ea76a983ae882ec65b64f34e7033fc10509b5a8192b09ca5a248a317b34456fa8b0ed9b5fafbffdb19d3cb4a999c35e5da346c415c797397f85e1e4937d162e84abafe6a2dbff3bba70e7abdd09fcb0caf9df3f1d6fe04c6cf5185cb3a6ba7b01266a22f01d7ed308ce05e039d00951c50367c11992ba23ae0bce5529b56fa57dc1362a3b7771fc4beee637bfb0ecde720a8637d0b6a08cb131951d29851635763c32b642b3cafcbca305ed9f4344951aa5d905aabb2c2f5ef64edc9be28f7e49e48bdb5ccafbffaa831dbbf630f4ce9a5bbb22f3d34aaf1a5d6c2f57a04fcd9535518502acbe10bd6ab54b27d589edebd274aec988b46b99efd66c8c7302fdbf19abc597552ee5dbb4f1c5d75ec6ecc50582a099644423ec012f1857a0d3a426a54901bf18d3dc54a736d88630c0b6272d85b93fe1d62fad1d5829e12737925ef3ea2b15adbb4bb4ee4ad8f8cb985b873cdaffb236b0f8dd85f6b56e84bff9f14b797cf31ba90c2f474a55fa771648bdad04693bca4b1025784a7004a8201d8f8a076aac645e9622047602d957f7eca13edc8cefa584cd8ea01ca203c1441a74159cd7509f16651b17979346aeab3ce7049aa48aa0e028b5fa54765ccbfa5fdeeadef96f8332cf75b0edbadddc7163c859af89e8b9201aad30e5f29be48071d391123ef60aecaa6b33ff675f1fd209d089b00e642502a4a3633a56c1aba1f25f683f50f9a272cf30d66dc5d185e23c6a8858c270385363614262c2c62c1a37ad1f54c411b60ce14bccf0f6322ad9c5f0b61a2f7df775fcc3977e74aefc7553fb3b5de99a376c0ccedd7269e8aeeeddc3c207e0b88b6631786f9547ef6c6f3ffbbb2b59f99eebf9d9ad9731a40e276a01a38acc3f788859b288b02dc60b43b4df8cdf62293427281f2044f90a5b135c6a91d1247ccf510c1d614108031fbfd8ccdcc385f9c716685990f974e3a114f187f17c3f539a4ec655e7f30c7e29c69a907824c2796fe3c4d3ab323850e24c06b9f9df4b342d131ed19a95eb344c5ecf29c7fe71eebf62560d60331f670f5d9d0ee974562e3c496425d9ba5ace29e95a168888cad2fd7a5ce6077de12d08982bd067de7611e9415830d0ccd55d1fa465fe42caede0ac264d62fcc654cb7df9412554448310574770b587b9edbfd670f92d9aaff6f9781f1ae2951b5bb9b2b3ce4d1458fbf79a7275046f653bbfdc7836433bde4ceb8210528532168d23ad05a842446c03f06a78612b7e58c1538e340e31718804758408c4918ce8861ace54a3229bad243a33b35263089b044c19b109957e87902081e037f9599ad1e8cfb37563799dceaae78502b556139413a2dd058259117b06ae63d9fbeee8bcad63a06fc995e3b394ee3a2272bf39371a534980738d0a553966b8fe7b95b054709d90c55834e38b105aeebdd9e7f857478deb7e2c5ffa35e74a70e38d242fb4f1cd8348cf066e58ad59bfaace3fd605ed0de06c3b5e50030270662fb37d943c331fa85053355cfb7a667deb966572311bb9c670458f30efbbed1fae7e3279b08bf28d7d0c738d0bb8e9ea3f235afb066675842c38a844ad5ec7a914a315c6294cb10e4ef0fc0aa61e90ba01a2e110ad1d36ade3eb3dd407039c186c0ae5595ee6c2720625166b1c69a41beebf182ff0a9eef2f10b83987a8817c6a0148e1047f2bc17119694b42e14da23069f30041d5d5c3ee7bf971f497bdff2e36a1c926a366c10ced8e509c4134cca7cb9e40342b7e3751b0a9cf3e9796cb96339c9d052ac14d0c1305ef818b5dfd439f5c577b1e6866d8042b03891375df8c22cea929bf0cf307a9d48e7fa0b2d6030758d2e24880f69e29346112de500f114b18a895d82780a1d9470454b3548d8f9509d53ceb98adbafbe65d5f50799a5f4d1731a8a9f3f241cffd772e3df93dc78a98be8796239fffd897f27944e662ff2898dc7486d08eb2cce28c438c45a3c149e08d8001d802620f02d5a811f08e80285b2a258f229b77a90daac19c1a51a711e7e20f8bec20f0a8828bc82c111a20a643e5de521ca60a371753dea531d6bca66855346dd174fb7eddfa89ab19f6784a0a0a80e43798110a866ded8f3d68dfd442c5b010f7c4b3855a73cf65f3e7dbdbe64569a3be9240a005c2a21ebc4e35209b380a128e9ebd2922d44aac7d6b36f2857195dcb9ec6248b897fe3cc2ca3edd9f73189c73af1aeeb910288baf042199bb830f63d45d4f83651a30abcb7573c014fe8d2bdbde281785d5d1208886c22905e5ae4a4ce0ff258ef256cbbf75d04edc7d37eb8a6e34529ed8b9b28cf3d8eb9279fcc66b98465afeae5f857be43beb5a54d706ad5b53836e323489f74652b1f4aaf27d930aac60cbfdc84ffa3b7605e7364e86edc9c086b1597de77283fbffd3db4cef7b0690bd6c69442cb701daccd6ead66cfe18735868602ea71847fe51738db94397e77f4865f1f1d5dbf6e7e81abb60abf20fafb4b24bceca7aec0dbcfff28cd7316d17c5040d01460521f4f55896a1ae54fb75ed254b8494e87b16d8d22245355f1531a80096b368d9af06341a9446516dd1f0a537db0e2a80e0b2d8bea54762ab4684acd1edb1f7c88134feaa1fdbc94fbcfb1bcfecc84deab033aca69cf8a47d3ee6e2c7d089d38a1313160138aa50dd574159a4b481b6369660c844ca83f3a3140256385119edd208a8868e79c1111afe1e34d0584f9e794d8f6c32a63ee8aa9a6f438a95efa1afc2b6f24e9ed45f5743bc7bd1438ef656f23b11db42e2c66d373c5213ac0b802627df053b417a1c5e08c26ae182a4396da80a558fe19f7fef8c75cf87acfadbea126d96729e75c32e5bb7bce8dafb99513e81fa50b34cbe139e1048af7feca251cff8a0f32ebd0d984cd3e4a85d4860ce2197498e0ac8f9f0ac940c8c86085bb6ebe8296b7a4bce14f1296cd0a79ed8942f2b8e5918184ef6c0e09978654fff79f517e48d864115fa394074424159f38d204cdf10190e6047274fbaa3ffa7bf056639fdad9f185f0accab2bbf4fe09f840f67d20043e1deac930bad84c3c6c100cadedcdd4b6c7fce9872e5df08dd627b79e79639996d996278661f189298ffec2c36dcfc8e4c457257cedc31991fcfc51c791bf4b8faf3cae9a23d419a762bd7e82bf3be7c62af30f756c5a9ad28ddb8b1027ac4d3f35c2ff8748939a489659964157e0dc9a781f64eb655ceacc848d0aa08f4ee9726be093df99c30dfffa76dae66a74d04ad81e511f6923adcdc6a405c4cbc64be3e15476f29354e117b6216e88fa604a75c4920ed778f0eeffc38527c7eedaf549e3f343e75c34e1f17997119113e833fe0b8ff4b05277bb7508ce700b2d5cf6aa0fd37e88222cb61255417929bac990544a6835486d479597bde23f39b87d27c77c3759fcf5bef4d1cab5212c2fd2b7600f1c0c67bdeb3ce61df53ac2b676b41f531f290016a513b0262b55174418e71f08c5edd3fc55861915a8f30efc825176c22aa24e70a9423cfb8c90f38110f0747dd3c4a09b34f54a8a4496f6837cc484ec7ef44717573ef395ab5fba620f373c18504fd5a2a53bcd113b71c522ee8927f04646f01e39eba329bbb66644b0e0158ee19d8a91fb1c4dad960ddfd1acf8a2a5d0ec0817190e1bb4dc766e4adf78b0e4b9ae655e0c6f00002000494441547c2f72a1efdcb509409f74e94e9639e8768278ab56c1eadb7b848ddd99f2db80c74963be49a10fe5ba48843e85eb14deffe5f9dcf1cd4e662f0cf0cbb3c03513d75bb0f868f1b2a3f50d5ac667a489b6d824c049824b22d2c8a2f4a3d4068b5893f07737f7f2a52f047cef63236bfa4475768e2d8f3d8d2ace09f48f9240b3843691cbcea7f8c56fb83a5fdb368b7f79df07695e60696b8989d3366cda44aaaaecd99db0bcf3db1cf7e647b9586cf39b290e9ff275f8df4de98f365cceabf4c72ccbfd0fd33aeb14b42e932ad05e1593a4a491e0ac46948f5f8cc186286ba7f70bee25451abe4aa62c21b22fbfe2812ac40904395a58699c4005f1dcb37d49cf983ee5079a24f271de1e5ce4d0de1c0ab3eb3cf1eb47dccd9fb9583a2f6f212839161c6dd8faa0a6d2af387da9a3e320cbfd77fbe865114d559f4547294a4b151ec2220c0b71f4e3f88f7fc92a3c01440f597e319cd2b2ccb0a9c734d4dc734c029982bc6c3ec5837cec871f77f5096758011ed71370e5451d0c6d9f8db382d296b04de10586e4d7456e5b7b0fa7bdfed5d42a8751ec28d07e4895a1fe63d0bec6c4254419440c9ed258eb63cd68eeb3c18e2edbed049338880de87eb41bc6da1a8f3f34c263f75e0198ce2e516bd690aebe10bdea5a9788a09e6f2b08e404fa4c079164a5d74387cacc22519bfaf096753ac3265a39ef75efa7bdb540b994e297eb242ea6f5f8bb98f7decddcb0b9c2e6730afcf0e1947344befe09c2777dde5578e3a77a292c3805ca29b5e16174a4b1584c3dc43a28b4ed218d9b093c8d5748b1d10192cc3e94a99ec659ff54cc7aa7c78348ca4e50818d8a53fbf5813e93f33ea7f18116fd264cbc8bd81630ae19bf308c04d0ffe4081ffad4c57cebb288adbff3685f61e8e88052ab63f7ba12472f8ff9abcefa95ef90708bc27cb78d74f3e0473d8e9f0777ff56b8f39bc1ac854369ff1157425c15927af6d9d16f2c7ee8f8c5708a57767ca192d0d567a7354527cc7a7ab670d355129e7b1c865bb1f438273d08dd68b613f0a7af3f9eddf517a3d138ed6505b5cb59b530e53cac12829a875faa61a508be87d706269e8f91268cf308fc3a262960d306697a0646575d40204db0710bdaafe18c8f750936d6381b536a7a9cdd4f1a4c6d1b6b7efa658ea7dad3257ef71a52a40701e55c77ee03fd23d7a04a708e0d789c2ca973781f3f99d2e5ebdd080f52e293ff67219bbeb792d6d6210e3afa210ef9d46ff8c7b60ac7bfa59dcfbcaec60f3a4356b754b8c7857ce10bdd48e12c0876315c31b88243571dce6abca08e723e291e9eb6a830c1254598e8783fa02052c335c6e85af1bf5f9467623d643132d9c77a2004fa7b9b0033fb408bcd21db1f1aa6dca1689a5ba4be3b22ae289c2798e2860bf67cf06f1f05d6ad0396b4879cbda4cab64281ea418a763447bc54b3f81d314dc38ab537071cd15a5f707b57f2961ac57fbe85aaf4f668aa55a15613b62d1cff1e41c9315c730cfd4b4247c9d2b7c94cf62d36e6dd3fcb047ae185e2af5e8da3d339d600f752e0035d2ba8578fa4b9d9a28a3a53891e84be034fe3b41a5b6ac6d6eb546a6d84e518ed6946060ec50b0d628a10a458e38f5b34e2704ee1ac87b3d9898f534750a890d45bd0ca43a998b8524279838d2c8d4718da56e4a813bfc97f7fe17e87d8eb7a082ee875d1f371e1bf9c409f696c105f7070128e5eac9b253ee702df22916ee79ca0a41368faeb591c1ad6e8f92f0791a2e76d8e5367159ff8eda585855d2ee5fd57fd132973c1f5431ce0d9028124046d23a4b5262c9af1b58e536cea636d328d89ec66f4158e92cde873edf693daa6f773c1f8e30420e9e488be588753f2ac13e84cbed260166cf9799dd643a0e3d056861e3698ea30c58e120661d7939b79d12bee61ce317bd8f8bde331234de8724c1026c4d56ce69517c6a4754565109aca5bf0d4164cbc93cf7dec37f45c96727853f6d95b0e6ea8d08ac0acecf37ff9614b4b6858d462e8db948cf9461bf6cbb34da013a278c28bde781a121f4bb95ca6632e848580915a33695cc093ec5c1b5b464c091a91f5e1640f620302cf2294f194806bf83cad85c0e19cc659459ab8c9d5b8c41201d6f8f8a5184c084ed0a4244988484253eb2fe97f32a0ff919887377cce21165097be067dd54d2ec94df8ff6b7ca17b470c45445c0f22dd4e71c5e37ee78d07077d4b5641d7b5056ebf337964e04c7fc9bb9ce52ffebc9b252f2e62ec61d4534da2862056e8a48814d2e97d9aa35174736026b1cc10483ad020cdb3f2fefd59e5cff2f45033a60a655a97809efa0330e5fb14db9fc0f4ff94af5ffa9df5aba578f2673a63fe614dc013088f5c6a99fd0a45d4ac997bb8a12532bce7e891357da2baba5629dcb5b621d30ff89a9ae6bc0a0e275d68d6ac1358e11c988ca4bb7c716b2ca039e1ec3fa3d4d64aa12da030c792d6dba9d5da098360bc76c154ee1ddb3ee5f5a9db4649d3a9b1fea3e334f5f9981fbeb13daeefc2a6bbd8b32de6ac8ffe23df48071d9d367b4f9f40a7bd9093bd6bdd06038d94aa7793d2e9cc531eab9c405f88cc9acd30e7d4ae0eeced432c7dbb30f76545161d97f2854b6296bffc4d34cbd138bb1c1db4924a429ca688d1cc9abf9da1feb9d312d53346a0fb21c1e79a407f6fb7e87e08789f043a9a9aa564df3e5660706b44cb5c4b5a69a3d8f6038e7dc7d7ffe7176df2f04ee28b2f5aafe9fba8e1d04f384e5d2efccf0dc20ffec3ba6d3fac40564bd36dec8e67fc01decf0d2b82823e71aed30808671e5c703f7dac36f6fae7be7730dffbd2cb49ed6216bc681b56e653ab75a03d85494274c814b29b8e40d98b30a727d0d182da5308732aa94ef8df5413bce0318676445407b6b279fdb55c877001668235953dae5c27ac5b691dcef5b2526f3c639ddf7727d1847e8a6739709713e81f9a3fbbd0ac5b59e4a22f5ae4be806b3e18aef9e7caaeae4d8fb5c2dc83f8df8f7c8e798bfb71d111f8e51a692a3855416c192444bc685ad37ccc74356ebf84f674c9f3f7255091e7ffc5965afb7b11686a1de566105524aad5d9f5bb6154f89fbcf85db7bc61e7e1f6d1f5d87b47da3de69ce871d667aa3cfc658f274eabb3e1c2c03957994896e37c73e0376936db49b4c3195688669d33d28962e9869047bedfc4d6dfbe89246ea27d518da83e9f349a07da43540a4e61949b407e7b13e844029c4aa01349d739358158d5a4f19a4985faae867343d8fa0095dd3e2343094a2538a5d19488f5469cf528ccbb9f8ddfbd1b309cbc5adc8655293d22d2339eeed4184399f8fc9956a5f95cf867d974dfeba42ddd10cedab24ef537ad753caccb6c1f79f29ed36416e7e932dfff7f7b38ea8cc718debe0457348817612a21c696098a10142392e82910e233489e3392df53785f6a6756039edadff462b71f0539f3fe0f3c48e62693a84c33277e9a9429af240c3e29b87888f23c8f05272cc0d8f3b9f71ba75eff25f7777ceab298d737d560a3a1fb92b96c5abf8bae6b42962c8db314a3ecfb4f2cf7f6d46efc933de7307489a20fdbc7329f351b0d9c04277cf84f58704233614110df27195c80574cc04b314998e514a7fa80fdc96301bbb1fc7f19db9665bccae4b1dc4f8a19409236634d48d85ca139aca29bca280f8240b00aea7b96e15c9dfad062969cf2ff5038e8876cfade9d80eb92a5be73e3f5441b63e6269229cff044855c81fe01095444849eafb572d7e76a1cfc8a028bff3ce09e9b631e5fab39fecf97d3fff86578a688751a150e138f848889109d225e01f1c1a56e46327462f74f9acf8989ee0e90e07ebffa0cbfeffe53eba6177607f8fecaf030be2ae20721414b4c6c02e2ba20a602aec2114b3fcfdc25bbb9ff6f535eb3b84a5fa1c8d076cdc369e5c64bafe2dc4b5cf4b47c9f635fb4d75bb1b2c75b0b29eb70e0ac38024e39e71db4b636d17e9265d76f972012e2153377854d1b91f354b28912134cf07136da878a643ccf77e23839ab26f936c7d5aa9ad98477069b8052239834250886b1691be265636fd308630db888dab0a53698303258e7c94d9f259b466b46e7cd8f66398c57d37ae6c92e2f26f20724534073c6b909db16b4709fd12c5a5ee5e5eff038e9a30bd9bceefd10cf42077554384c9af8a8621d7c834b4bd8d44724252b0d963587c3899dd48cb558672635e32c66e29fb358ccb46dbf7feee9b66ceffb2b16b2bfcf9f54a0649ab6ff93e29e521b27ffacedef3875b18da6832c8556a1562952dd5da3b2730fe255f1bc83f8d90d7fc1478e0a683ba3c837b784cc7fc271e842c5a5e7f2fecb699a68ba3f2d6c5aa3d6ae255eb702a0c78943f1f25527d174904f385733bc6321a27c0813ac8e49e332492a38af4a32a3ffdceddbaa192be0e226f5130eecc77834e529fb1485f2214d668157a25e5d42626693ba7622d34e5c3f14ec0252b384b6f911ad0b135ae79558745c0fe04f204e3bed0f60ee037de190e694ff1510706e4f401cf91cfffa2af77d2fe0037f57e2ba4f7e84a0fd18ca2d427db0842bd6481ad5e075e25308ab10c454ab4d28499eb669bd57df03ce13fd4328d403c3fe14e07e15e87e1251d3a9ef7f8a51ff70768174c8602b09c62854739d242ee16a3e5af5a30a65f63cfe383fbcfc93f4f545dc724bc0d99f356cdc62f9cf73151b77d6013b31f0f1d41468567c400461bd533c3658e633efeaa2e3a0669a3a1c953d4b700eac6e4c76d0d93460491422825113668e3121283451614ee31f1d57a14cf2818e46e3a7fa3e2706a3c6f64996e5e048304911130b3aa8605c8022c8bada1a2e6943e998a85e26283c4c52df436d4050b285fbd7fdb373e32abe51b4f9590b22e50af40fc8ad80e2d78fc62c7eb9e3f17b34b75c1ef39f571c85d77424a5720bf17080b83a690d24a88106bf5cc7980251a50551f52ccba5d16434eb65b4d9bd4bbfeda5a6f6591a6e8a82d8876a9b9630f7b1cf6915ddef5bae6eff9ae0f7daff81ffc84c8fe1edc344c375ac75f82541fb458ab31ca5b684a0d444f3fc26c2e6365ef1de37b07ebdc7b6d4f2c8af34d1dd2187bc5a91e569aaa7ad44450067e959219c84f0f93f3b9d6259282f18a17fe7110480a73c4adaa2ac8f16089c872701fe3372994f579466cad23593aea709db7028d1685bc6f3520a4d359c6b462b8d88c5f7134c524215aa280fc2e63d24f543d0ba83726b82568b810e11294ea73e9f8d92787910e9194656b53c2b9b303a7142bad0acba21e4893b4256be5b9344869ffc4c5df976d4a5e1c16fa2fa6840351ec6b818fcc6655cf781942405884081273ed633339a52c64eb9f1dd6462507b99336eff0a71c2b6a90a6e52ffbd089419fd89d3ffa6ef4fc19a49ea67afe22149e67fd35e8a493db49760521f51364bee9ea2e0f7ba23c49bac3ca71cd354853b35c012f81ea34c6448a126501b7fbdbe6b8834d51cf2f2f3f1feec97eec3e196592b87ddc037d7186e0b1dabca05589030d013d1c8df74b8467ee7e401edebcbc672b4e046e3faf3412cdd3d06f0d853ef60b686e15db3813a787ec3dda0d0e5214cad0515a4689722d64799743cd632b160972353aed90a5959d9bb3125e9b26a1f53c745dc78dc68529069349eb3f754dbd42438d2ac7feaa174dcd817a489c6f70ce2344e04250a5d70a4510ba8412839969c72310fdffde9cd7255780497243867057c8474741c2fe464ef5ad6a78d6313113c1aa5f31cd357caca15e873899d08b501c12f38f45129b7ff006eeab1973ef9a9050c3c762461b94e3462f07c3da33fce3ab39792daa7c2dc9f729ca236f7ea7780aa7262bfa7ea8f7c3ab0269be532daacd163cdd96c413ca50dd6aac6a346e9c62a00ca3256e2789f6df2384e3a9669ccf9a9c7bb3f055b1d12e62e68a77f573f9b3ef997b2727d3cb0a3c39010f3a67e4b1866fd77aecbcc708783deeccb65a1103bda3a3b9de9ec7466e2b6eb0e43109742b7f0b11bdb686a2931773ef8329bb6590a637c7caf4612fb24d512e83a69dd10d504131932437aca799da01a6752ecd3fe3fe1fda3d7dc54253af57a9c29abc44a84331669f8d3b3f4ab00636711962b149b420e3bf5fd47ba4b525983c7396f3d9a935ff66a4e7bdd89b21691b5e8d56ebd950fdf59904e94f43a9c7371f6f320324d3169952bd0e7c00fda70666717c28e4d8a96ad0a056cf98ae2ee1fc66e3d917c5abd09f01171d4060d2d7334b5ea04b5a79eba89a965aadadbf7d4ce69f76566fe3c23e9de6432e14618ad44fff4076f7ff540ed04d32f5340133f73f435671b55f2d300e5c738eb8d11eb8c3e54cc84f49bec669ee4af9bfafda6aa613399009c9b7c0eb4aba19a0bf8db0437abcc071e7837177fe79bac3cd8e78ecb1d07bfd332b053f1f87f695600ac4871ddd6d19d55aeef45b1711f2a7e19ce3d3aa116c28faf5b4ab1093c5dc0a9267c144647c451113f4810bf4665b8095f45f8fe6ea2a139e816377629b86933b61a36d6041539a62a471566e3321b15cc6ee2fb272ad6d1eca64957e794cf778cf7059228406b416920d5682f22f5353a6aa2507204b313d2f8440e3ff993a4f5764a658fb000d501c505cb1d7114630db476fc88bedb7fc2f144b2acd763534fd2d3890724a3f76ee336969c409f1b7fe7f84d53bd5ba16a1abfe07868a75b36771d72924bf12f7f2985a62a49ad848bf7a0c266a496dd004ae429078926e72e3eb500d2b86238b040949b46853d3381a2fd07a12656b9c7aa4615f5d1daa36ab22f4e4f7679786a7f3391f63e96b18afad3b90cf617349bbaffd4234a46088b8e8aef1155ce654dff57cebd49829b8e7a83a386305f2b961b9f8db73ae95aa159331651d63cf9fd8076a03630750aa9e34910ae312cfda2e3a6a5c2c0f626f4ec983d4301983d68cfa27d1f111fe7142609281623acf130f12cfc720527c1de799f324e6ad94451269328139e375c376e92fb683a12653a769ef0a6bd4934db9705eab8d4072981064f1b62af48bdba04a52bb4cedf49a57f21deec0a85b2205aa3d380921ba13aacb05199247e3daf5ffe6ad2e8373cb1693574d32d243d1333b126e491eefb0ecca3f0cf287a11d52363e3ef32c5704d89ea633ea5364b7387e5ae8f3b0e3e6f21b34fbc9a784f824d2dc3dbfb99b5781e69359a9684f65544616fb292fd10a7390022de37118ee6b9efcb0ff9fb262a1f4845fa49cb84583d61fd258b75d90daa749a99ef2a33e347abe27b4aef8740ed94022b93973f9978c33c9579f9a37da338c5b33ef5429db94d4d0cc41af5f883778c7cf60b67fdcdee3aff7395a55a151ed8053fdf61d045c7ca4315f5aa10d585f0a89915f4fc07038a1d75ec2b2da72e17367784c803cdf4f5ac241a3a9af98b0a38d194db2a446911251a51161317f00b23c451b057447d5294bdc16a6e5264bd71dded67a692735367254d89ee4f50f59322f61322f571159417812ba27c8d88c9a2fca92249029ae6dec3c8ee66e29122d8269c2a224180a41ac730ca8dd032ef2106b7cda33eec11d72b8cec19e0d607ff96a564d54f9e421e694ea0cf0681d28323abaa2327aff659385840d5342f9a2b141638f855cacfe32eda8e3e8f6438221a4e1149089a0bb8c8ee456ccecde05394bd978c98911ca710a83cc5a4faf1e8f0f409e722fb4919f97d7da353549f9b60928b389cc90a4f683fc6243e3a88b3a59b95c11a0fd107ba24c8f8714e1c87b13cfba759d4c46a18d95ea530af153f4d51cd29d59d826afa1087bf0a8efccf8887fb2d0fec6abce1e8ec21748aa42e946765e31b57a704af4ad9177beb238a8ab1fc24126a2d298fa53e275f917018c24e02aa371cc51ddf5989536d04458d5f86a0a070b69805d96c3c4e60d3cc7b57a6e13a194d949f785e9c60f5cc89f6a3e76bda247b68ac8830febef129a1d9f67aacd1ca662e9a3042e1e3ac8ff66a1813528912883cc4a5d8b408be82b0866735ae1ea2483169111dee20a98738bb8d6ab546ad3f66ebaf3feda03ecd25afd9c79cfa3c88f40c632348b64402227d5d9add774e563cc190267c8b8fd77c12f12028aac45543b1a58c8ba229c11b07d86c6df5bd52726cd6c61cf3d9aa970e9b91e4689bf27cba40cfa46d76bc8da5464d68ced88ca4ac056b1acde2cce86b6ec2ffd3b4d4cdd83076e666cda4e7e2d2b18635634ad4358248ce2a449986af2e1bcb991a2a056570d846735897f9e61cfb08b24d6c13d3caa6a69859874f88841e6d18f6d40d613d24f6a1fac0e52c3c3ca6da28c47ce2218ed61596b0c931670e9cb4044e3fcb1196dd18614e6c0061d991bcd671e7029f63df5467d3b71cebce1ce65f8f0fd87ca7e1b60f0a8be66ee2f66f5fc9e2e56b8886b651db2954fb4348637c1d8f050665ca75385db0722ce034754c6670cf8c8df5d80aadb671edba2909f9e33ee889df431722f05308e26ce55923482ad8a880320e454818187c5a2894223c3fc626655c54420b581b225e82b5f3d07e89429b506e29d1d25160c9699f1391600a792aa6e4e5e604fa87c2a69d425ac96e08bfe088ea42f16586876f6d43ebc568bd9bb856c4a675cab385a436eaa39b10ad9c34c3639c28a72e193c16319d42b6a3e6ee7491f169a3e5fbc99b9c98bf37f5ffa9d1d49922d64f7726d1b83299a05ca6f14b5ae3658fff3f7b6f1e6ed95dd5797f7ec3defb4c77aa5bb7a6545542063291040890844112101f41f17d41936e4450e996b4a141056ca51fdba49ad6c6b6955790d8a2603bb54d628b2844785a4c440413880442026426a9d478a733eee137acfe639f3bd6adaad00e2f8f72f29ce756ee3d679f7df6b07e6b7dbfdff55d3ed9808dca69fe5bb9b94f8050d69155a7dcd7d3e84f9512f6ec6a72fc58ce5cb3cd42afc7b46a337be9040f7d608a564368b584f63850960345ffb866d969968f69e6e7a19fd7e6cc8b8bb0b8b8f6fff3f3f09109e16fb4e73fdf69387889e2aab7a63cbf1598bab5c94b27b9e983576a94d2bcecf287f9cc873ec865df761baaca8939b8bed970dd9d18d464134e796227d1492b994dd7c7ca42b71620e3a6edaedb8f75bf373ac1184b62854046108f49467519af04630b54cc4893124b13ad345a3cda8e5004a254ab58aa693adce842b49da1d9b2d87406d8a5946a6d3cf5272fd3bf55c2fffdd3476398fd80861b15d75fdf61f80ce1c947343cdfbfb97b5df3bd577dfce52c3cfcff50f42cd9b692629021b963629ba128c229cb5dfb14b4c04a3d056de7a69b7ef522f7a7d6899eb2d34709d69e66513e8ddd5e38b91fe696ccf7090fb335f6b87afcd453dfbfad30ce537d7f11b5a519ca06bcd8aea91524d67de15a4794092815f9aeebdfcc1fbfc9dd646f6e5c7a1672fbe3b8f72e5e01dff74bc244433375b9e72f3fd9e1e8e7f6d05dde45513528b3219845dccc40feebdb1f56cf4178fb1733cebbb426890f2f0a0ffe3e7ce243c2bfff74822272f69fb438f2b1ead0f5874697f11c7dfcc55ffd11d2f699e86dd04ac00f26410a86618477965435996839864e6f89716e55cecb563ea0ebce9f6cf218adef1fbdeef7b2017ff551d089dee4f4a4886e0d76897eec822f0689d4d5c75895e1bc25d3425406656a43f2182aaa5c611bf7528d1246f396afdff36370c0c95b6e4a783581abc59f4c17fa2d16fe1f3a9c6e7a6c3bc29063f79d092605ea52d336727ca9c7cca2ff8648a2135e1b4e11344f9639adc3584fbb9e9e8e708af20d1e924def7f2a0bfaa91684b831c07e23462a9b77f0e4fa594eaa09dd4c3a6dde8ef8f5fb16512bb3a24421683ef6be4b78f52f3e74d3bf7cdf2260f8022dee638a4fffc1b93cf89967d2feb3e79234846452916db3a42884807781e8a3faa55f9ae455fe10f7ffe93d7cf6e377713c7f8807ee18ecfc8e3bccd1d79c6bbeffad4afec7d2edf0039fcdaffad852b1e7f5cd6d7cf70d5dee9ffb0dee7dd36b38b6740951a7cc6c5fc4950df46802631d696348399a8564b0cab89f2053daeae49ec0b09fcc9569ed6feb2553ab32a64d19ef0a3b1f7d1c63ef6b2a803a6b8d88da4452a9800f09da828c6f14118388a6ec9d83d64be8e690abdf782977bcff6ff9c19be2ca54d29365a1df0aa07fff3492821b855bef5370bfde00f8bbbe3af0c64723777e702f33678c883e128a763d884d0053b28183d9527a644e43f29caa33484ecd6e3f85d9f0a70b48a725bb4e963d3ec5007abaef70ba20795a96ffb4d9ef535b4836eb3f4f0ca86b0143d6656147befa43fcc96f7e86bb2f7d8cc35fd9c1e213cf42997da433816de7466259d55d39a5c65511a3743d2a25065480ae2c90650de666ae622e5cc53e4ace7fc12347d577dec17b7eeace3dfb95dd35f3ca96e26dc3cfbeee959ae38ff439ff0bc25f7c6a9ab7bdeff7f8f7d7bf9eaadacff2e20c8d8948d60984a8109f90b69609deac8ae5647d97dbb8330951273471c93aade78a5e747da054eb8e97dac013ca2619d38a009f75efddfc1a35e6026ac253643dc9188846d00a18b7756a65b0e988cab7483b8f12a4c5935ffe76e09e5b1f215e7bf9e996f26f95f07fcf39673d5951dd7a9de1b357a50cbf9a726c8f62900bfd3384bf7973c5cbffc36f32b5b34139aa483a09362d191e4fc86c8b6847a70c505b95a0df48d05cefc7b9d5fbbe51b3906f48f3f914fc20bfe1ecf3296cefef3406e4eff0dead3e77a55b6a2b7c7515c4a82aaa51ddd56b9a0db46d93b56a88a5ea06628cf875dbb0a230d18057349b903b61e00b9c1ea153a1a92668656d9a69ca25b33f43e3fffd12effcec04e7ffa9bafa93bf16ee78ad8efcebb7e81baec1dfbc74a3e595af7d2526b990d6ec046953a362a41874b0aa8b13c366f310398523fd0925fe6623914daf55eb66c8afa0252b86242e082659831042251bb6113cabc6258822c615165f8faffdf1dc2f6d88cad65a6baf5014b8a24173ea7ef23ca3fba4e1e62fbc89ef26a72edde3c94af86f05d07f480cf4f58326cd9ee5d81e454964711bcc0d9f4dccdf4e7bc6e30615bad340251119e6b42722a3c27e83414a9e5296b59a5deaf80d96b49bde7f1a95523dc1e114196038758340fcfbe435ff2f82dfeaf73bc97bc59c7aeae7e6c3bbf9b5a2378d7adef4daacd324fa8aa25fe3778d464459458c500c325a1d532b03f0c41532d11974b068310c4c4e2bf13492800a1657669495a10c8253117a01dff35cf3ee9f7ef34f71fcbdefb8a2339ddd159e71d394faf46b3f11788917def082c815fff20db426cea433ab50b1833605a3ded874f9048de7d6b2a5f5417303eeb921906efc9b5a97c9ae687a3707d0b032ac6ec543741c38d7b6add7bd5faf06d13a80468cb64465d0717c2e834682053d80e431960f37f9edbbdeccd50cd70550b3618aeab74af87f84477751d3b4e00a45675f643168e6cecb28168e226e92524a5a0d87d88472a8c8fb1d48f3930498a7d687bc25f4b9ea91c0aa4de2c9304731a73613399d49d05a757a26fa20dc000020004944415492207a3ab3102d7fa7e0b831c0ff5f64bc6aed8b6c1928959cbab94f6d718e4ec06457f490b22933178c89a01c69023e2aaa3c19775c15846a80b3e08246822154e36015024a39ac8156aad00d61640cce24041b213acc50a1468a6dcf6ad33beaf9c84fbcf3bdef7cd7cff3b23b8f2e1fb8924f5ffbfac8de39c3cd2f4ff9eb5f2c79d93bfe888f1f7823d6b468b63c55a549b21131d855fc737d27d6569d485ba1369bcbfa0ddd4cb2f900ae2bd7c7ac7c7071f5382b2078613cfbe82400fb4ad95fcbfe4c0a48c0f815958a106960f488e03b881854ccf99b7b13aebe64fdfe7c4bc6f48ffbb85170a55a3587684cd43f0fdff36c6298424a4bf40a5736109fa154246d0d56e51d274a974e21ff592f631a6bf3d6e44e714dcea4e3e94bce9348704e67477782a6f224fbaa753ce5f3299b1d9f44c779c27674fc869ea7fc4e2bc7ef54c741c953b310dc2c331b7f767039be0faeac4d50d2a6c3a682ca14ca424c3ad8a445a39d30b15d3339a7e8ec84c69ca0673d6e72482e8ed1e148f5588079451212d2dd29ed8b32cac5842a1df1b4739a7cfec08dfce46f3c8beffb1be1868b3a7ceae7cdb9cf7fa0cffffa19cbd29e2e2f7ffbef303aa6d1be4f8c867c2c0d5b93ae6dd46f9e206bdb4ae6b4e9771b8837b576dda2e306d311b690ceb1726daffc5c2fb3daac2755016d4a2c091a0358442c22634ec1062419104a4bf4916377290179e31beb18f92d12e9ff8f87ab4e241292a6c6664228234639b4493169a4128594066c3865097e8259c726ec72cb0c6e7d27cd4acff856048762ad55f104ac71bcffead4c44c382d26741a16ff3432a3137ad14f92019ecc41e9b4fba74e5d82af77e7d9eaf3e3385391cdbdddeb9bac5783c5faf350e375d5a06e165058348aa4a94934100da54ea86400184cb41035416a6c2fea085989c9532023dd96a0d380d21e719ab21fd08bb0542cb27f6e1269b4b03b87341e7e0dffeb7f0e39543cc46f05e67f9826dd7bcbdd37bf8fc3bffa23cbbce247ff804fddfa6a74ead00d870a76a3e3c2e6fef7ad524e51ebc89e53b3f0abbdf69b08a6d520bcceb4247819dbfcb1aef97ee38ca655e24e07b4aa0085d181a023c4fa8d3e7a424cd1362738870f1eb3af04a55fb3e7d4b4e23fb90cf4b4a6a90794e680d2a0f40175cdea02a2aec3a8ebd618ee15e71b555f1a6aa51f56dd7f71aab8c61ea0de06ea80465d675068144609a803d4c6b8d9b335ed2b0d673edb51f415137fa5483343d977a8a6c74ec3703e100691c684a2acfc787085ac6a0aad5658a5d02240a031a5694cd737963109562b122b68a99fa10da10d920ada2ab4d6686548b421493469b4a4e250c12165edbf1810a2053de1713d0f2e47293ff6d554d82c6212402bbc716b4f1d0922f85807a640a4913568361426ad7bca5dd08811d216646d8515d0de20b9810a6288208a6034b1c9b8c3e9e4cfa0ea2015c4139d47f200aefe5b24aeea58a3866815318168d6ba8454b6e999aad567921994a57e1a85320a6dd9f09418315663338b69286c5a2101b4f524a9c6b980971a86d30d413704952a440b62222a059578a20ef531138895250909adc463a71476c66226212ac5a01b19754b46cb8eca95e860302a12a8a86245f0159063a9b095c26b83a840f4057ee40803219601633caae1d9b9a7416f2874177ba426b07dff0cf1cb3fce8ffdd01c6f3eae96f3ed81db451ffefe07b377fde55ecf23f9d708ed65ec8426e94608828a71dd73ac1df6a0c604a5529b1a3ef4c64c713d9cb1da1db65a79ad04e68dfe0a5e6aabbdf5dd5d9af50c7bfd795107a28e44bd920507940e4834a86a82183cbeb28844a2b784e0ebceba50b2ef191fa735dd647677c6877ef217d50fbf6fc735378e714fb53570f34f9a445ae7e527ebbe6f54eafa04deef57d2f2ebd573925fe7f31e518abb31dc0dbc71dc46b9b2d0dc014ca078ce0a907c9d825be2aa37cdad07123e7b9f915fbeb554dc04dca878d581099246dd6a5799c8e0339a89e7fe5bbcba8834cda88639be544ccc090a4dbe14d10d30d48619daaa55f630c41a070a7982361e1dc0bb88d615849add0d95904cd63ddfa0d13a60138d2416d182ce3cca59a2b7f8a8488c435b37f6d634281d7055c436234aab55cf4dbc218688b20545b7be58b5f2f5aabe72738c19ce34f58cfa6dbcb3e87641da12946a836a92a59aa0f2fa661183f27ab58c0d0ebcd7d8ecd4304318976a26aceb4632e380b73211726c2c5d8bd585e8437d8c2a4d54c5c9c9335128a74e9992c610485a063004a709b1241486b45320da62b20c2d166d342bdecc88228c17383d1ed5e2d188166c56a282c25719a148f0de639443998a88c3e2415598582f5618b3fa3d6304156affd395efe3633adeeb8dc9911983d75105c23090b47248e710b59d181c47beecf9f0077e66ff7354f9f87f7b3070a8b2fcda9b3447bf67c80f3ceb0c3effa11f62b233455e842d951022e36bc56e24c9368feed8306c6e8bb1c62bfbbd914052b850af4ae2f5ba8c5d1145366ccf8fa76f2aafd96056220a8242a781e833509e5035c69d490a574c9034e699d97b0ff38f9cc7a8df275faab8e2869fe3233f760ca5321129fed904d09339a82830dc8ae1dadaf70fa592db6f225e7d23f18e03e86bee13e196f18befc7f08983869fd85bb77f5d7cc0c8fd377ac60d814a1db05c7b8bbef696fbc3ad5c3bbe0e6e55b7de8a5c77ad44ae7b539b89cb15e570ed4219b81fc5d88b69b4538a7e452c22ed590dd1325caa684d4a6d0e2cbad6b3694f10284b8baf12dce111d61688a9485485b67d6c5621aec6fe521319f42789610249124833c464d002d32e491283318a24132c069c46bc275691906bd46cfdf97805ca2395c70f12828b683bc2548f636cc0a68e683c9a7a88dd0a7450f43cd11b542698664259edc08df692b432dad339154d925620b352bbbf3b8d54822f7c5d3a4d9c1a560a08f8714018170cab0621a626154ce28801864b0dca6e86c48266eb2853338b0c0fad60a375c60ce0c35ad0b6f6d4a49371601a826e2a467d43f03921e64c9ddb27d2200e5e84d2499d1d997a3ffd0a741a051958d2cc13d33a5b5c817aaa2138e7c9ca235855419aa3c411630053a142c48b902511276b25b0f1b2eab22f4163aa6443196d37b17edd5cd8b6e7eb9c71e18090ede3f083df8e4e27192c2bd2f4216e7ddbcff2fa9f9ce58a6b03d3cf73fc802ad93bd9e669affc5e769e7339c3a57c9d13965e5576d4e6d61695c40d016ef3fc24896643c0dc4af6b4b96bc945197f96aa03e88a8c49364d10957aa693f2fac4594c61fc1a3336dea642a2c1078536252124c4904130400f658e510d23bd05c3c5afbe894ffea743b205fcf44f32808e334fd952b7a554c24d570b37de1e00c3dd282e47f8330cfff68a3339efca33e82ecd6062829aa888de20c304b1258d7444da0b5c72d113fcd79b9e044ac0d5a75d29382b451ef307ae43df780b5e5db6b3c965af83b87f0d5658eabf09935dc8d49c62b414214036696a907e7948632243139028f8a1a11a29bcaf50698ea220f30f60134fac04898ae01dc60bd5b87455e3b9489dc98af66e4f6c34591e6c67b83c4759b5694fa69834a5d156240d033e25b80a89257ea84867eb6c5302b82e943d0885a7d15c6072e649068f7ba20b2813c760434439a11a1b79e8a2c2498aca3453db2bda3b3dc14cd23b7a2679770fc919016d52da9d844627a2348452e3cb80af2a54eb34b8fc3843349ad59b352a850a8aa8226108d530a3aa3426f4c93a4768da795ce1287b092c9763ecb1de7fa585d247aa7161d1b6f6e4041be086d0d80e5a47dcc090fb2eb6d1e58ce7f758383e49d37e1f512cca8c7586863558810885c664119d6aa2b79415947d4facfa885ea2d95dc0260e898e1003de0be2024e3c5a47fcb82963c518ba8a1eed6bb8240643235127e0b3895ec3cd435844170b5cfa92050eb9cbc89317d36cefc758c76021219877f39e1fbf87b75e63b8e6ed097ff457f1990ffd82bae7876e99e40b9fbb9ebc4856ad01834fea6c4f8775c151b69633ad06477df2a172abadb06b99a30fb24e67ba36a8ae5ef3d6de1be3467bbcd576cfb0592eb522aeaf17d2cad50b8e36052286e032b42950c6a19279864b81fe9370f48137d72bf73ff100bad55cf61599838844ee5096ab890a11de4d833bde71064f3e7436b8eda4ad7a1098d286460a663c375b4a451922a1821915704e58ee067c3160d87882edaf7942eeb876589f1c453d8ae126e1bcf77438efe5c079b07b9fd0385b78e2aed703cf63624ee1fa8a18228d8ec607c8070392ed4da4003710422f403e42c725b2660fab0b7ac70b6cea50518844121c7e9c4dc73252983aa8a489c22a4da3a5d14d4b1552d01954b3147e2769bb43633ba844235a116285482451163f549423d06e84b147487417c91df840355f6720c606a2787c5507ce2a7a94165a2a43eb488c1a5726282d34670249aa29f20c37b50ba566103d4336d9c44e064802c16b2aa749eda94b785528a2019b803211091a578eb58121501dd364cd2e49ba006e48352c08bd0a6205ae62a2152863c09888934811ea73ecc7f786310a8927cf426d80a4a1c9dad03b06b9ef317146979d57e57ced6f77b26bc76b08dea275825a97cd86083a0aa15921558b585a0c434c58c2f7738a5e8f44bac4919064152415b1f4388998107038b48910c2c6804e20ad841815c11b929913e5575a2bf4b842688c068ce6fb6cdfdfe7d8c43309e9cb98d93d8792162659e2d02343beffe7de79f3e794bfe1c1cb232ffda4e6892f38067f32c57df7be86ed17cdad6295c12763e827ae8338cc8919e869b34d56e7c5aff8b5cab8f77dbd5e54446f089cabfdef2bbf5b2fe85fd185fa2d7c4745114346c0d5b86948c7db332015a894248d386f51ea5106c77a24c987e581bffed43f2b167e1c3cd57a01acba06cdd5b703afd9cbe2f10bb0eded4c6eb3b4a6216945960e7a6c9a23c1522c25d8c461128f141a1535473b35e6a26735ba6a33d9bd98f83fcf533ff09983dcf8ce2740165727bb5cf4b830ea6bc8a11cc2fe65c3e8a2c758fcf225e8d042bcc28e1d80700a571afca246aa02550ed0659f2474497c9758945405e8468eb5152609500592cc93fb4818e83a2bec803691981bc2c8122b4d3a148c28a2c9d07199ac79842833b8853962368d6e1ab00a1f53ca23098df611a69bc7a9d4083f70e4fd1223153a1628ed302692a41ea71c8d4e2496b23ad171667b0d77b83c620710bca6bba019940921964c35734cf6245ee62816ce40f229ecb4c274026241f9d3ace889a08c424c5d06864ae18782cf1d2a543493af634289ee16542387f4022114882b30a122ce82739168234a0b5918ab1b629dd1374fa343ad6c84688856c8a614ba37a4998c98dc1df085ad3372bbe6daaeac207e8ccf1a70c1e04a872e4a823b4ed31c43fa39b65791c49ca25540ea68b42b4ae3b15a502ad01863ccc16bb41646e3c91d0d2da456e81742541a995ec33ecd3af7fd1588a5dd094cef1eb06b7f455e0e595c889455a4d35ea477748aa933e123fff1253f9ff2272fccee6e7c7a7921f2dcab2ddbaeeed17afdeff048fc49643c3b6a2d70aa0d26d41b06cd6dc1c0af77a65f7dfdaabeb6ce60eb8eb9353abe2ed7e3fac6f871d08be396cd4dfdf651d631fee3b7451049882a27ae94f83ad4d9a7b228625dea9b0a91065ae5886f63d348995f09fcf30aa0630c74fd7c6d8b88e6dbfef533397ef45c5adb34db773a4c53d02625d186a75d52307f7c07c5f22cc94449d61e512c4e605a8e7de73cc652e629e61b18a7494d440a61d46b727c782effeeedfbc89b5fe49c73fbfcea9bba4c5d1de10e70cdfae41623c5bee72fb0f0a54808a1ee5ad1204e2341204f288e07da93f334d283683ba458845ecfa1d29c46bb2016114c490875e637caebd4a98cf54d926491b6abf137dbb23867c84b4bf006651c8c864ced9d4765c7e9f58694fd3320d9866e36489a60d40358dfa71ce58c163c7158a0558e36239256c520f5441b89123149dd732c0d851dd465d4a2aeb03d4bac1454f50defc4c2644923f38c469a2c466cbb8b961ed5682f8a1d2895924d06f0a79131a5f541ab1d7704e702a17268df43498fc1428f0c8792922843b2c9218d7641456434823c53a8aca607b5ae6fda1815d94aa6586c214d5a97910ea3a34946aa23c60b2a0b64cd4873ba0ec8d18efd609410ad6015c424ae420f7100961ea15a44fa5d06832e7e5060128f699638334054c05711e29af9488c75666c6c4489602c4854942ee27dc4d8fafbb825214df4aa640d6a77a595c728a9587ea8c1f2a4703c9fa13d3b020b459ea2b32594ca7072c5c13f96ffdd788b1af20be728def6f90e4f3f5b263ff5bbbe77e65b4ba24f88c1ac6a63259ab560b5aed140640b19139b7b07d6055120481813816ab56fbe9686a98d8a7b59999fb485a94bac939c7a249fdaa08397e8504aa1b523868ce81bf522278ac48c013981184b7c398152cb681349d31dffac75a04aa90c98e4053ffc5d6813d87561853843a83a24d910d7371c397a2edb9e760e361b91cdd40edd2ae9d1d86e28070d8e1d7d06d51148dbf334669e04bb886f16782a4683c082532c7ef1628e1e3ec87b9fe3280e29383fd2e944a6e6a09168160f1bca51c6c4b4aa2dccfc0a181f880426f93a66d4472d978c068e104698a92169ab20b4043b0864e219e6115719caf1029117823691dd3143a2aab3aba6239df0543e609a1a63471c7f40e81f6e902e54ec7afa43642cd33b7c298918b66d5f62f9f812615822654192e7986617b3c25c47a1a066fd19d53775d3d4d986d61155084f3c1249b38ac94cd36c2b126b986e9554d30aef34838391f9e30d26860533bb1ea5958d18961a959f814d14c19c2603d5f5cd108cd4dd2452a1e3109b2cd0629e438c489302d48889c4e175a04a22832412b729ba4fac959bd9092a0d28e5e4767a312af2d4e34a4b6502591446c7846a3b347b714d836beb7dac653463ca24815009a62ac8f40221cc33981f50e4031a9d9cd69e8a89b303dd7b0a9c83582aa2d730827626abc1be08b60ea25a08e3bf8f6425c3141a15b862edfbb4534d746bff5f2c683a7343e6ae0a140f8e88dd26aaa5b026a7eca750785ab3d3fcf45fbee0a183affaccc7affdb07ce7e377163ceb72ddb33b4b1a9d3ee5b083c4c69ade5645d0b21a4cd7eb41d786cc6d0a682746d213461d9f300279257b3d49c7918fb2a59de0ca4614f514cfe03a602b94a9205aac5120357e9b989ad40bc11059448a16517b4ce6bf3902e8eaa85650ea1519725bb522d0acc760a80c88f7df8aba0fc275d711c72b91e600466e1ae37deb84e075b1744071eb7d0a11bbbae4c8ca40690cafbf6907777efc4a9e7ca4479676d87359606abf9034da2c3ef6624ca7c1f61d255a02b52bb54701de37504a48261c4a19a6762b627b1795de4d39cc99ccfe96cef222d1158419e18a9737288e3c9ddffdf973b8eb23b7f1ddef68e272e10d37547cf4fdf6bef35ff5a58b0fff6a4573cf4e161fee629a05ba6cd03b98d332f763cc085f958438c2b4fb281912714422562273fb2d83d2d2192a6259bb33c551448f9d9a8a26942e301f3d94a09c30a723d356336b35ce0ac9649faecf38f4a86662f7315ad93db8fe1c83878532afc8f4109df6404688778cc6c73c949ac9b32c4902e9a0bea9e3405164428c9a7e10f60dc12d417f29d21b45d276a0b14f33e92cad36cc5da2187ebd627858511d141a7347989a0d144361f9c8d94c9c5791a61e5f66c44aa14c1f150da25ab42785722942d3e1461983454bd33c8659e8e2fa23ba326432eb12a3a3d17294d613a3a2e80b89aea18d228bf8d2502c459695a36514e944ade99c9e83f9474fed477a814e511d436c29862a32ab214b149d6849ad2699f2f8458b7111239eca6604e3884b092a2f48fcc394a30a3f1c117597746a4813871b4516bf067bce4b70cba6c62ccb313e3bb49083b191d8a9af67c6193433a0c641431ba137301bcaf630366e5bc1400f3f58e24acd195f5198be4254812a3d55d2c04e47181886d988e1435730f7479f7c97557afa336fb2cbf7bcd0f1a21f6bf0893fff43f65d7a038d594fff604536dd24318ad17147d64a6a9e45d6dde9ebf5efd4f8f1465cf3d438a90b8241ad4e6c17b736c5ce6c785f4d40d5323485a815e2481356fe290a5129d8726d3d362521400c091253908835014c8a364dc40a8a45caa1f9a608a00aa55168117122b7956ba4cf8d72de796af2a1f749e03b28b996084411a5b843697e1bcb2b886abd3dd5dd63cdd8e500370ad7821c549a8278fcf7c93e26f81fbef8bec8bbdf712183f2994c3dbd643b4d3a1d41e20e961eb8049b4e621a8646b322549390e4a796d104f03e421a318965d87d1e5e72ce7cf6a7581c828a23fab967f7deafb1e31afbddfbef283f7a3791bcdbe275bf5c5df4daeb0b3ede6bb1edac1ea83e83a3db683607181ec70f7b98b4c03673321950c5112e56d895ccc6c1dd5f2d50ba96e190820455c3000dc544aad9ab054923cd2c5212094e53b940b710ba45a06334d646a2772499210621c8081bfb681569a515aa390237244d1c7114715e986c2862141efc628eed6a8cd14c680d09d0023f21e80961668f6534071da72997235a0b2689685da244b1f48845f534e5787c736b4e616297347b0cd71ee2abbd486890a41e9b05a448a822a46945888264a616433b4523e992ea21de0ec826862835406c8eb1119b443203a34a6834146525c4a0d8576438078517ac35e021ad34ca6962a5b8f4348d18cb8f3b92294825501e167acb9e6c10d08f07f2a39e627f1d30ac894495601287cf21ca882c99278c4664e98834f6b066808d1545f4a46332efb1bf2a90a8e82486b83287487992f1a489e2883e01d7d4ebbad0c23a49d60a19b6be3b6d629765ea3ccdd47323c73e63a846862860c41362459a25208a417f9a1d98fc49d472dce978e82f12defe23158dc7ba7c29965024486530a5601a4232ce0a83591971bc42006dec58aa47b0c39a3d839c60aa5c9b276fc45663dc6820b2fafef5db1741afcb4e83c878c40dab6d4d8a883535698b98712ba7a035882aeb2605df42b0183bc2bbbaa184ad89c57ff4003a26748252e33eb65bd06f7985b2b357101e7c90815adf1d75f181445d2c416ec15f77cdc5fa96ff7e9f7bdddb55abd946b2bb08fd1c35df472f0d303ea07c449d033cfc30fdb91ba5fc61305cf69d9761d4f96c3bab206b37d9b9cbb2f0e43ecae13eb2b443d609e844205a32e3a94ed13a58d77d255561d1126874529ab39a90271cfbfad5e4a3c739f2f85f71d747eee6ad1fc8f8c8fbf28feebb2e855b159f78776fef7def9e54af158dfc9c66301f181dd7b86141a7f3280d1ea7eae5949d9276cc89ed11a9786214aaaabe29126348862bdd8e0a3f214cb51555bbce3e84005fcf501826b425cd8441150912399a94e412b96cb25907b5aa26524c2948312406852922ca7824142469452511a3159d54e35af5551fbd4629c344a6986c46aa761dbc9386c237c6e445a7bed85a338624876e2f901f118643d079406b8f6ad698e9a027345b3da21d91c813888a94bdfde86d2d928988530936076d2a5c6929938029c0e4239a8d83848511260e707a99e6540e5dc682752104a169eac0d91c9fbea30d4f19224c41be380e362564d3061928764d9c5a075a3615490b9269d07d8d4935e97ec599e76ae6bfa09150eb714d121195a0938a7c19f4689966f624ddd19024e943d6a3194b06552de5d25a88119a3a419bb14eb5094da33056880d85af0cd6fb4d0cbb6cf8e9daeb83e789edbd9333c29ef323d3fbc67f0b63f3e1208803656b8cd9950deefad2d49d676ceb71fef72a9a671a7acb91b731e25f2947221613034a9bb1c3564d668d5c582378d4160cb8df82a15f276f5a9334b1c1d93e8471f9afe544172759913e45acd8d5ed7b891bda6411854d3408e3b29d754cbf06a508cea06c85168faf2631c9418a11a4135ffaa6c240859b14a278fff5e8f7dc569788d75c432a372a7effbf90bef63606ea3e7140a2ee618217bef439aaf7bd6771e76b6b612da210adb0c08c8e782ab4f5cc9da5d5f30fb591d7057c3544b752b246429ad62bc9d2d1e722ba45da6a619b55bdba483d0620daf2b4edadde3451be4255828e824d0d1028879ad93d9fffe9f6afdefbec5b5571dda512f889e5099eb825e79a9f685e7dfcff33ffedbb7017fcd497cf6172778f72d0a11868265a4f9256f31c7a54685a858a3d5ca7248981e5a26686cd647d13b81078d10586101483b20e0c4514a41f57c70d7707a33a83c912b2dca01a0a93425f69b23141d32b046323792e642a625d8577032a026622925401e502d9b825b26c2888b514e6e2975a7ca5d1469831202ad21b132fc31078f4af4a66e62c768f627ac2a05b7509699ce072185481a996229b05ddacaf3f57540c8723f450d877d97d1c7fc410cbb388cea20d842ce082e00a41dab5a2c1c4e3a4fd65968f17c4560fd52ee85231291691bac75c1b8841c13a8b553fe749a723dd2a125a1a098aa803a47560eaad675cb67874a6359d9660a7612668bc356cbf44b3f702b87787c6570ea513a4a1112ff8be45157d42394f3e5a221f16649d113a940c9663cdaa77ea739b18c3d35e66a97a116d844a45e228300c81baf7c96167c619d9d81650afc38cb5113ac989b8eeca6bb411eefa58ced1c39e62a0e80e023a7a4457686d5063630d6d026912e97e64964baee8d1ec285a2f76dcf587295fbcdf20cf4cc6922fc12a43c4a2ac432511e5cc6ab9ac56e648b17ee487dadaee6e0567d67aed3dab933c573aa9c6bf5bf10c551b2550a0316313e5006b23ac45e162fdbaba17b826218304560d7bc64495985ad6a4b4072a62acf0d500f2f63747097fcd1d566ebfda1fb8f626bbfb7ae48dbf4eb8fe7accfbb91c6effbce30ea55e7b1b953a20f0c12b5f80966730b5bfa433ad99de9ed1b41531addb09575bf9ac061ae361626de6ce39cee0d11695b31022cd86a7d9118e3c7629d3bb7661b2128d208910cb047449a8423d8dc59e22fb04744b63a3c5ea11d57287902bcad1907de7fc21ffe2f97ff3ae37fe82e5ba5e524fc1bcbae0ecdbe10def7677bceedd72c19fd2e257def11ae6ce6ee3fa0acb80d93d4f902ff7496cc1fecb9679ecde01a18a040b5a6b18678b8cdbc49dd38c5c448611b11edfada8fa81c18250967070a7d0ce0cdbfa81ed8d844c6bf6b461d258060dcba8ac56b3122542e80aa5af30da23311243a48c0ada6b194e1c775249502493823501a91c95f274171de150e4c8a38ea52ab6fa77ead1c18b13cedcd7c4ef4ab1538ae6aca23d07690bacb54ccf413207ded58bc0a0505485a1c803cf6c1ca4dfea10659aaad84edaa93044f24a23b6c439831f546cd3c7c80fd581b7353dc2572d2d0d3b00002000494441549e2487b411d0e3acb3bb50132d4a0b1d17c8b2c0d44342abe5397712425084a0288691348d1823e40377ca0bf8ebc72b8a8ec5cc0aa3438aa82af462c5a37d45f7dec8d4a51a099a18355a7946cb16dc22aa5ca43f5f6226fb2015ce5618abf1be66d4555107d1a20c9852e195274acef2724558882c0e1dbae59893c6d6fa545b2f5453dbc70bc5a6d956763c8be9ab775440c98e732c217a240b3576a82169d6321e6502da0482315cf172f88b369cbf4d58ea04b217422c222a8d041b700d574fbbac02315134945adfe9bef673a58ceeb0b1ac97f5238cd7b04eef6543895f7b79d6bace1366c64b1c67a171ec4911d12b7ea0e34d68556b486b7954a85ba581189371eb73cd9b18224155446f89b14bac2afc4871d6f33ef6cd9181de7e750078f1c5c4070ed52bd0fbdf28703902a4eaa39fdcc18ffedcf3e19a5926a746185321a56174c4629311bdae61a5f12766b5c140da5c7310aabc438b67d41b92594d6b36a26652caf24cb2c67ed24e0f5f3570312155252acd6b630a2ca880e17453db4a92a6272ccf40ecb2b450b0ef920f33ddfbca45bf72a5bdff09811ffdddc91ffdebd7bb9b5f7147bcf98f5478d33b25e15fbcfb7b28f3cb69b453ac2d51bea0911e46624e794c31ec0ef00d489280f76051d82c40ab66bc47557d257ceacb398d890ab3634472f68841743cb9cbc1c17aef5e725d8bfe5d86787746ff81165599920e35cd5d8a8946e0d842244645259124132815491a569a2441458237e8a051a616689712c972615005bef05b05fb77149c734eceeeb33cf2e5786ef6d1f0928b30f67ef4cd73b7c0a1fb2dfa90a39c6f32ec66c47ecac4b4464d43631f98a42674523316851bb095c13bcd973f14d8fbd2a374cb43c4aa832ac6dd2851933485a25f605d0f1b96993feef19d216596d3186aa6a2421ba19b0bb15fdf84b67484b4c0ce559c31e1de901f18cecfc3e71e241cbe7cf78a344901ecfdfad1b8efe0e92fe1cf1edba9b9f2df08333d7ee2828fcafc21f4eff65f6ee81723669f59c70a2f11e52a542934c251a25a22af4a667445a91ca911d26d01ed34c3aa2e6db5897ceee69cd214cced1e71e5644eeb11cffbefd6975e5adfab5fcaceaad3e9d29f0835645638f32deb2457ddfadff950e1aa5a46f7e35fb054cb70f61ecb9d7fa671ae6e6d44810407a166d1cbd2d2dc5df0c40386f630f0a977195efd22cfe2a10887041754ddbae9142a8d8853b891210debb3cacd3def8ab2948df8e5068c5456c8f0d592bff6345813caab75ad9c1b3f43aff6d24719e3b06331fd1a2c3c36dc8910a5b6b34334518d489a0b649d0546c72ec30505b1244b0fb2bcd8c015035ef9b38f6c9910fe637722a9da8a2a13a9d14675ebb58a8b6f315c84e1192f7e15ad9d53635fc73a9805a569a4824daa5acad3be0897b790629ae83b042cd680496bd795e38f1d6366ee7e2e7ce93ddcf3b14b99ddedd87369c94377be8ac654a0ea36485b655d6e448b8f095a5704f1a8003ad1278eea5de7ed58294793369d668f85470c3b2ff81d42f77ee67faf0bb31957ffca24c78e15a82ca77d89b077f704b7beedd5ecbe689a10f7520dc0d884e5478f23c30798dad167f9f11efdc78ff0cc1f6cf2c85d25c16b9a138acea4505691d857300a385bb63f7a4f6f38b3bfe005b7385e84e1e17e060fb488db0dd954e479d39efd942ce3f9a303294f3ed0a41132a6b627b426520e0f3c3128261a82368277b5305be775b0f45d21641a33b5d6bd1207915016b426cb99076fee2d7ddbb51557dd64f8dc631dbafd369d09830a9afe42c2f60b15cdd92113930396ff38f0f8bd298bfd26edc98cc9598b4f3479105c2fa0f375e4c6b6baf4bcef968a971d1016e2f91c3d78193aed609a0a51169b41efe851a68b83c863f3cc1fcd09fbbb64fb3c3b7343f3b0e2d044fd3de220e26cc9549ad39e2dd873a6e7f118e8bfc1f3d06f75682eb6684cc3f46e70434d740a9d08fd70ea1be2ccd2d0b0195367056414193e2e8451cac4d99ac7eedd4363e225446d705a0885c3748fd1a91e63b0b84c4f72667755c45007fa80277615fd4231113d9333151f78d72257edcd79cd1391c39fec70fcd0243eef30741de2529bcab8d56ae0a43799d9887d4a54abaf9fbdc0305aa8a10b91bda8f8746c739258096e5493597d1bd95e357970d72f73c9070ff3c44e81f3e09291b0fcb79a472f7c2bb661e97eddd1d99ea135e44b826da6ab8e4ceb99f4f56d9b5ead05c30d2ef6e35ef7f51d4621c889d9e67a339293b46eae88e9ebcea515d6be1ea1ec5c5adfdbdad7556cb4389f8dc7220712ebc0f6111f28fb96416fc0ab7eed46b9f9854bdf243226655682e72b5e41c26db7789e7ec98b31537bd05993c587736c43d1687598d95731bb4d182c5f40919f49e523265a446b54aa495b0a6d62ddd687461bcdfecba708e12cbe76d777d0981e30ea2b1ebab341d6014d9bcce6759942824e1499f144118cd8fad873ea56c2a20b133b7a54a349f69ef769deffe35fbc58ed70f74dceb5f9f9f7a89befbb6278c3fc0d8e1f78df14bffd4bafe02bad39a6cfea9077158df4082aeec28dc0f54784853eb472ca43038e7f2de025609248da3464a9862490a1c91126a62a262786c3d73e98f3ccef0bcc7ca2c103c776b2fce87e1c7b487587414bf1c70f2ed0dc7688d6eec758cc96393413682e380ad7a45dd6ad76eb09861591b8ee08d129088221d268d57f1f5420b662c7ec88675e3d58fa0fef8b3c41933fffd81e1ef9ea05b86a2f8dc984ceb4423535f7dde1694f1d64c7de0798bbf030db0e7bfa8f3994b1f480c67e4d9817161e8ef82342d36a1adb35132934b60bfd2315fb1b9e5c75b114f8bc836e55d84450aa898d39bb76cef3f5cf6b124634f6550c1a0672688f3476d6d501aae9993025b3978c30fb4aee7b4871f8b6846fbfe02a66658ea19943724be38842c60e4951077654a761e165483f6474ab880a821bd4c7d4cf47d2ed33a4654265eacaa8ecc1b45d40161cb95330e369ce78f281050759323eb70d61a65db2e7d97d6e385671ed9ce1a38feee0a12f5e8c1f9c83696cc3a6d3c4649a58ba137add376855435c676ebcd13e0e511cfe9aa75cb0a00ada93d0da6e495b39de45863964a9c1a481a6ada81e4f386fb7e7c8239a9ffdbd821fbfb4b3f72df71607fffb33bab4da9132f564aa8d55a0b4a76113fa266c2ac9378eff483cab38e686b94a63107fc5adc985b17653adb0f2e3006a39616cb2c8daa86363d69cfe435c331451635bbb898911ae98a02a5bab786f66eb45b7d6ad6a62105c05baf138ffeae6dfe03f5dd6bfe380b257df28ff18bdf04a2b8541c48f091955630eaf4885db2a04e18e1a6854578be1caebae234966181c0f0c1f1f3173714ab36368ef6830987f1ea267308d1263c72c641a367051b5cbf9da41abe76ecba6311427cb28371a132b25c461c4b60db629385184aa200c124c5aa08dc60e9b14db87347c97a7fdd87f7c4f9849def2ec6b0d9fbb61c0fd7fd038fff0fbcbaf7d55125ef3fe1fc45797904df4096597bc5b9b7018bb8be11168a8bb89fd45d24697567b44af080c862567f5328ece39168c62efeec0b65e823d9273cf57060f7e767afebcefb93a6566e70ea2be8830b80cd1bb495b1e11cfb09b32356be92ff4c9171ec1975f6562e630679cdb63fe7178f8f6367bcf69a24d9d7d9a32631802baf01c5c1664e889b962cfbe16173ed7f3c482617ebecb5f7f6ec4737797dcfee111ef620f5ff8cd8ba8169ec1e4f476b2a6c160f09527ba129f808f4dc8864cedfb12d13dc0e2a1431cfa4042125b9cf19c743570c775599436b5ae74e14860cf0be0691756fcf5879f8e4cbe80739e9fe046c2e2a347291e7b94862951699f862e7045b5812899a912a6b68f3852e6fcefdf1c5ebbaf97df7ae1472cbe7c06be7b09294f3b354918cd29cda963d05be2e32bc1c0261a4da058b054bd2526922f71f4c192d96489ce9939f9318d9ea88fffd210180506cb25cbb6cf777e479757fe90e683ffe3e92c2e5e89f3e7614c469214e022e5202199dc4cc76eb2db8beb6fc58836b5f1cbcafe3a2727354a01b03a61b894634c426be6437cc71b0f2e3da2aa994337569cfdc284996f4ff8b5effe2eb6356768753459db5025050bfd269519d1a9ec8976769be6c06f08b09c1864575e2bf1c419f0b26e68dcc66d6e7272922db052a83d1394202159753c5b63f261fe68c095432677fe168f7cfa815fbf5ed937fe3ae12daf2079cf6d52fec367a04a0494e7ba8b136eb9cfd5637e5122b795b7aaebcc1dd7dddabcf91619f2a2ef7d1a4f3eeb2534272ca65990b512765e3e417ba7a27bf859544767499226696704aa410c098dc9657cf10f9b351b5b4f1df42e127dbd52aad4a31a0aeddae86d5deca266eedfbd97ee7f49def2f4177afefc4525d79be655dfb634f8ec6fc824dff9b3afa43d730669c7e30713d8d632ed595feb01fb7d8c299198137c898b1515912a0f682d14dad34e204b15d1459c1b32b76bc0673eb07cde75bf35892d2fe1e0fd17909aa731b92ba139e98895a12a2d2a96e4c380b11edbd843945d94c5518ad15798dbf13807b5671484d6f862f2ca612da0854e0a436d6869216b78968f698c94a4bee215af1ff23337086ff9ed7d1c3efe6df830476267305691b42b5ca9a81c88d1a44663b5274438fec5f3a9fad3cc4edff77fd87bf3784bafb2cef7bb8677d8c3996b1e325606120284a0010449449b198536b9a0dc2ba282e285162e2d0ab7adc4f97ab9ea856ebac14685066953eaa71b5486561205c400018124103255524955aacea933eee11dd65acffd63edbdcf3ea74e55254aeee75ee0adcffad4deefd9c3fbee77bdbff50cbfe7f7f043af7e905b3f2c040903ab5708125d596bd6cb15f3a6230f8a96add8b967813599a7aa7751f50c75cfd0480ac8fa349a150d5dd3272a160d65e856262bfaad3e0faef6f8b595dea157a3b9eeb79f01ee0a9adbdaa8e4cc168336671133195f98c75ce461c58d52820b813a08597b116b0b12db436515de0ba5aa9948f568f1a893926dbb0aaeff9f7a40c2effec78b597ef04a547621533b148d892e553fa712457b5b4d556f12e1d8acbc3ff67ca8d0a4c63af5e971a3216cd95e807cda50f572fa4b25cfa777ef7f20b094ccd09c58e3f37706bafdfb69341e22d3864e57934cd6049d626c41dd6f6c09ccb24503bd53807033c88e27a2467c50b361f1d8f8b99bbe630bd1122705556f62d042678dbacaa87ab364ed052eff575fe56b6fbd9fd77f26e3ddcf5a1590371e417388f0ae8fe1def9ff8a0b3f90da909beea86ebc51d9830709d75e4bcacd078bebe4267f1daa78f7b53f7d219df02cda7b0cd57289f41aecbf6a95aaf74cba8bfbc89a39b65d805624a689d281da79a46ec2294ccd6ff10260152235be1f555e6cbb464b131532f2992e9d25c3339ef3be1fff959f3cf9a1172cc29b6e32dc7e0cfefe1fd2cfcdcca4dcf247cf47ebab489a1a48e21c55290ac8262bfa6b2769e78bf8d515a4ee61bb7d820da44d8f2c054e68c76cd730552a8ede23dc57f638f6b492255278e02afcf6ef676a7783ac01cd468daf137c4fa1744ddaeae1561b240d4b7b67131d72bcccd2596eb15c199ef8dcafb37a7f45bf6b41e948f6f68a5e533139156045d1dca3694dd6d48b193ef4e88492d7bd3ef08f6ce3c8835731357729e86460ad78aa02ea10f046e155a03da1e8ceeba8dc1e26684f3550498a6f7bf69cff08756aa82b4d18247cb416c2c00d724e53ae40790256ef53ecde7f1271c7f0fd6df82aa07481372513c6a12b47bf7644ad034555419a82bab0e2ae2f963c78b2c72c093ffb5fbf977ce26934f2197009159d335e7f3b5697bd65cb0e7d6a19e2c8520d504bac612ffb9ac93d4b540f38d2bca4a4a6df51a45ae88ba3612ca12334b392d65cc16bbecff133ff6517d5dad5e4cdddd8564692b9d131a44d4d3201d5a26c01158c9ae0e9314d0d113d6a28383aa7f110d516e75717824de7c89a7d3efcfa7bb8fa07db6f30f4f8b5b73bfee40f45def79a4575fefffc799ef7ea8a8328de85e50d78621706c741feff21ef76238a5bd0dcccc6cab3df23e7ddcf5ae5f21bcd4feeb821f9a39b23de8c6b6a3cae003a6cff390e9e37df4ca5b851c341e195afdfcd23fff42c92d62c9239b2ed86f39e38cffcc32fc266fb48260c4645c1dfe1317bf191aa807bfc63b489c75540ed101da0b2b13d8586aad650ddcbfb3f7d77eba31f73bce43ac723ffe7367e74b7e7ed6f3bca4f7fe86a4e3ef21ca6f6152825d485226d74283b2dd2463cf6aabbca74e311e617d6082b7dd44497acb6d4d4c89ac29fe398480c7bd0ac1eeef3c97b57f9ca7f0efcea172e42e92b499bb3346c89d135bea77165c0a43dac12cafe14daf44159121b68ce76707dc3cad13df8b24bf3c00ab6b3c0b211baab0988e0062e69daf44c4e18521f399edde58a465172f14ccdd768f0cd0f5e86b297d2da19f07541a8a128a02e23e93bb13109a39450551e29a0b5bb4f3e95512e9ec3838f2c92ef5ea46c0542097a41b35286f5445550042fa3ea993bbea0d9bebbc09cbb46d91524d468b54212848a405347da8ef79190afb550b4851d3335cd93e535070ec92d7ffc3bfb70d5d3d97bd92459a3a29c777096245118d0e308eb6edfc8ead4025e8f5b0ba75a5a5ae14bc1179027abdc7742a0533339ebd15386e22e45734af093427ad293344bf69cebf8302d8e7cf342a676eca5390b2aebe0ab846a4da375192b958261a449a9b6b0ecd4ba12bdb045d7d601bf728385b6c922f43d4763221082dd77bdd2476e65555d34db603b72c12d3fa5d5a56f9de0f042576e78b5700302aa6611383800e66bc7582cdbb700d3f9c1776e3f0dd05efe3803f01da8e1777cec21cc0b6e1c1cf71da83ffe02c94fcebdc7c99b5eebb9e306e11067ede8fa38b8c3c326f48437be90e4e28ba3a229a079c2b5e7d09c7a1959dac7156b342653b2a9f35838fa22b4cef1a1472b57f8ba415dc4d615c147e51ff11a4d0dc9e3ebc28b1e7043a5c46a85481b6dbbd4ce53cc67bcea6def7bd24ffc58f7bd9f7c418beb5e5bf13f3ed463db0315ffea93fb3872e7ab983be091a0a9fb294af5f0fd40d549684c94d43d47e83a74da41962a2a4a5a99a78f60fb016b15350a6384d54547ba525fc333ea5b5eb033e5f09f5c4cb3b11d9bae52761242df22de61ac205648ad26cd05931a5ce529d784aa2398d461b422d8391e3afc54d4ea67a2ead12ae89e607dac3c31b3312ea745a84b8df125f94aa0fdd38e7b3fb29de5c39730b92ba3d35728ef70a1c6977182255a618c423b43b7124cd6a1d73398ca925b8b6a0b9d851d2ce7db99e044b436a7058e8fb9ce5da12e85e90c26701ced06fc31859daca86b456a3c892908c6612b47293526f1989ec20c628aa615d8fd80e3bcdcddf2e9ef4b587ae05cb65da80989636531c1578b04d538e3f54f86ad4134635d1ed7bb92862ddcde71a0d2422c39a5439eadd25d04dfad48666b66dbc2fcaa221f64e1fbd43cfc899a9d3f54f3d55bb621ee1cf2068858424fe3424da83c1e706269885aef1b241bc17d08808630e2536eb694d964826c14e5888fab9e47277d8ac5d64fff19e51b5f457be7da627efc335dfd0bcf40deb8ebe519bff6834efde24fca0d870fbb8388e732a50f1d823b2fc71cbcf9ac46cebf08200f8d81da75d79dfdb30e6d02c1eda0aeb90c6eb913f582bb6301cf2d37a2afb989f06aa85e7de8758a43af335c87bcf76fd0b7ff11fa5d1fc39d2e55f42d0723114429f48dd76363859184f7beea5d937cf00d25fdc517b2ed5ccf526109b5a2b54d53f4aea2bd03aaaec5f703c5da2c36ed46956b174bab348288c74b1675301fc72df80495f4087d0b89266f5614fd06fda51e13fb3f71c94f5cbafad577fe4c93bfbca6c70f5713cccfb99b5ef78eb5eb9fffe69f60ee4a8f5135fdd5145f97282d884948db50f61a50c5c95da50add1a9453b615b3b962110d4bc2be60a85da0bbe6b953dc2dfb5ea8d8f6f939e64feea3b5af66e5440009a4daa19380b894e02d3e71245335e233a81d55dfe1bb82cd413701dbc0857d2cceef60cff98b681530dac716b88b097522548950374a1aab098d34504c69ced396fbbeb18d7e7f3b7b663d8bf381a03cc109042131835a7cd1844ad3f78eac55a30a1badd1a224f880d713e8e639a8c52552a02030db546803cb27026868340659e06d35c989c03db77b2ebbaa87592911e5c89392d2d5d459454339564f7a82578442b16d32d277dce7fa38329ef7b694bffac46e4cb3a6ea6b8abaa2bf94323575e69bce6dd52b49afb7e2555ece9805d7a9416a85cd5620ef63bca65745ea58df282ae5d0c6c626753d37d3ff5a583ac73bbe70649ab43d8debc666744a390c9ee085ca412a42a1ca332680625c765dac63c3dfced0f474bcbfbb496a8aa51c65fc0d48caf22f9b0b5abf5d72718b87ff10c5f7bdd4735f9672f92e7dc3b37fa0bae103947033708d00fe860d0079e316bfd1c14dbfef8dea31dd9fd71d0c8fedf59b40f6c66b0dd7dd1cd6bffba07010cfa1eb35effe4222371faedef84292775e47fddaf7e095c289886c166a7f1c5df8d7252252a3a89fa9f6e79f130a3ef886820b9efa06dadbfb2c9fa8c9db09137ba0b3f07c1a9380f318ed216961d32e3883f70d4cd2071d3046a345a394e7f1854f061d166b44b73099c1e67da493d35b5ee1d7fffd277ff823bfc4ef9cf70ca1faf306eafc92b7bf235cdf7fc78b993bb01b5f34a96a50f430a9839091b60c36ade82c4c23ce9135141429c94449c3685c9ad16f39b2d584c4d4ec6f0af72fd57cae28597d62c9bbae4bf8db3fd84d9a59f4d4325234d0ca2246a11243920b4a9788687af319b6e9a82ab0498dddedd02ac5570918a1ea41d9d9866115da157e413111026b21d07182eb05aaf99aaa36b42705b357b1a22c9d9589a89be93589ea235a13f25871a32d049141474c87f28e50e44c4e1b6c5a23bd82b2939067069deca35af8262def317949d28c4225b32dc5721127b59d51547d0bd42469607a7b97c5b2830909adb46039381afd4183362d1b32f949235cf08143febe7f77b3e2ceb0877cef14940de8385a691f49a609ba3c7312296c813061cc4a1b82e956f7b116b48ec4f456a34bf5b0a2e856b40b3f6ac1a1561dc6189209216ffa8979f412c0c987b79134db540534f292b4057569a96b45a2033af35ba2df66f819ea699e02f043da50387d2207a0390775ed69b4e1f9bf703d37fefea7ee7bd3074f729ce4ff30cf4df8ec72cdf211c3c53b14533ee1bdef15ae7a6dcd555bdd99071f85b579f0315aa49b01f731be3fb6f259074f80eb2f4fb87c47e0e6fb4b50ea5d1fa37ee7c0935effa9b6b641f5b7de027d4f7d485d6f5ec7d3ec3f7024ea485efaec1fa035d7a3dd4a38fa8d1a9a15dde51fa631dd44bca7b7a0084e48db2ad6a1a6352aad1023289dc6f6b219e82c7ddc63a04155b8b281d20e9b787cddc6a6cb24f67efeec7dee775ef0e38ab7ff83e7b56be6b537ff72e0b324eccf9f4b3dad695293e5bdd865b24ac9a76ad2a6226da4a42d85364d8c019b24e499a26828ca9e257586aa34f4ada25c6a317f3c6d3ee0144bb39a8a8cf2d876541a282899debf463ee9304d4b9a1b948999d6baaaf16a0d5ff6417b54d392b59b34261b34e73cd88adeaa4274836c56984835aed6acd512c534562069788e1e5594658c2d9ebb3d70e1f7088d4c93b5baf842d37019994fc9124b6346914c0598f484c4e11a15c60b56a74cb404e9a7540b09bee3c8db05369923f432bac6d03286240b345b8231c274ae69d6c2eeb9806f0ddc5123ccecad30a9433b48328f2e04b467b5f2a459545db269a03035ad29fff22e19afbea6cfb1a3fbc8a653b22c400161698aed3bea912bfe58c678d67ab46f9ce2a4d79f9b6470ac4945d1b33425b60fb14960ae1d4b63b5158c15d22c5cb01dc3edb71aca13d384ba89d67dbcb3f8b28d31298d89403ae5d169a4e88d94b8c6ddf3b1631415101506a81f40fb388c8bf5ddca83f20395224f945d5e1fdab648f2d84dd52597f12b6f7b05bda7cc72eba18c9f3ae4b67de853c23f7c29b0b0ade48ee3c2dffd5dc62bdea851379a41f53b671e37aaf571b6d76e350ecac6f1cff98cf83937a9ebb528904377d472c3cdfe2675bd560a23224181e23ad188a06e448f83e9e34ea4bf4e0ec975e0d5456f4ce19d16adcec7bb8cfe4ac9b35e3bc3c35f7d2e26151087d629c9e4a03d405fa192d89657a900a2d1da0d08b18252f5baf2f538e88d4470052d6640bee594d789a80dfd5b0605ba1b2a8db26983fbba509c53d3b60dba759796d7f2993f7e8f7afdadd33cef29d9d5cfffcbeebff9e887f9b19fbc3be5c37ff55282f3a87b33ba17076431b63248728fef6beab55eec58a83db6ed708b6bd4aa47d035795f0833352ed5a4fb2acaa38a7b5b25079e677bb3f764dcf71715b73f3947ef6f928aa23a117be434da8bd09da45737c95a35a401c4a23528af5095203d4fd55738332056f715790a2baa4fbd0cbd15cfe4fe8a935d43ebbc4058d24c9f001384f37fd0d0b49a4fbdd5f1e23a30b1c3e2fa19fdb20fe9a8a2358ed1523c485f6808ae606d492146615a91105dad2584b2a03d3349d29867f1414f5f093ba6a0b3c3103a35c5223c70bba331ad094ef1b2ef09acae59b40e14a563fe6481320ead85999db1677d556a16bb82778aa5fbf8f3f35080c5acb6109593cfd514ab09e2ba2c2d04f4c499c54242085b44edd4babd516ff48963c7c7f5d7ae762b4c926183e691db0bee5f2bb8e8659a094999ff833e215794270ce7ef2f5970ea96db705cb043d3ac03495f91cea50455d15b1db48cd6429094c40859e6a9fc9858c8062b53366a6c8e4c52b581ae14ec78e924a39acae17b6c5111544ee805765f965374cf414dbc9613c557989cf9d21c1f39b930f7ca826d558b47ced1bcf9ad3d7ee3e56d7e60b6af7ef04e27d75d36d05a18b78d6f54c8c1106fdcc7e8823f8edb75dce4373f1f349e8fd4859b0617f620723a43f75b6e812aa5d48dea9af8b977bfd371fe552f2690d198ec412361f1f0458316001a9c8e4def07c018a81f0dd1f451f3f4cefe3739158c6b0103da29c45448ade9acd937f1a6067943281f0ef3dca18e3c488f171c08cc7ff97bb01892bc8bb1e529dfb1a525b38177b8717fde178c8fad39521b38b99a527426d1d2206f0a2a0fd435882d49f322aae6d70a933808262ac3eb8032016d3db621240d41e71eada32bd9ada39a8f5f3a95c83eb4fc4e1b077c3416dbe67d7a9080114f291e3f8823ea8eb054840ddf97a681340b682bdcbf38e0e20eaebb9d18e88b8ebd7eb908e3bfa184219fd0f4a8eb9ada0f8f2976ca3cad75b9d99a1c1b8fd55ab5cd0a11c5ce731c32a06b654d8fd182b501a53d65a1f103a5df739f25e453f3132bc200002000494441544ba4cd15c4288233106c1424d12176e91445d96de2454e3bc2c03f3fe5fcc6ce4507413b303ece33ed05e5400f1e7795a3543575e50952606c9724e42cddf954fee0cdafbaeb973ebb9d1ffea1093efa8b8e89ff6b85b7ffe6143ffa1781a546f2a6872e374a6d8129f2ff1dd0fc566ffaf1f8d01bb82566de1f2205374b9217647942a3d5a458db4b96952815a5a4d4a046d507394dc64e1e65364fce088c8f0e748550196c36503b501ea30ca28e1e66193af7c2030f842ee7bab736deaa79d15b5ec6f4396b2499a6ac1b546b66bd2f8c8a9355e98d8032dccae5f54935ac09ef382149c2e0c60273bea0528d840ca99a245993348989a824afb08d7eacac00525d632410eca0cac3c4111b902a946802833aff7a930c9a15743702e808440784f3661ecfc185b0eeba9e69a88de7ec5538e586d6611d04cd4025c874e23e63846e7023f5a02161dd584f630e6c120173f83e6dd75d62e0e41a357ff2604a32b380d08bfa9ccd1a52419a72c605eeb4c3c40569787ee343e94de757c5c5ac9094d68525228a6c15922ad09d5414d360d34055688c11262ed52c1ed6cced5b41aa5544799256453ad94712a10a090e416705a6b5b21134097862bff8e17854203f1672d8fcb7cad454baa4f031e4e3ba86bc0113d31933d3b3dc7ee8c7b8fa2519f72ccaeb976ecbd8517b4a2cbffbd66ae1cf98e0c01b1335d675e2312789bed3015484c85afa3d729e7dd5f79336621f709b3651cd6d289d61b26a30013dbe56f85aa14288aa7ce20912f021d297e2f088f80daefa08f83603a5df3854900dcf431d0875c0d781e0c2e879709ee002e2047287f69a2228a861c7becf965cdfe7bee370bff3c7f998e283bfed50933b4969a213b0cd1255baf5e3f29bac3147dc570b69b50e56e91af4bc502e7a1a5d813528ab405dc66bb3efe2403e25d834c5f5a7503a216d0a92d48450a3554570011c640d37b23cb5892ea0373541d7b1fba00e2817e3715a0b5defd056e82dc7dfdb0cac506d056b053b5ed2a81e9d35c60030830a23f014c2c80a4d2a190bbd44306c74d74581bb898219985c128e0cf62b13b0a9c226862459b738579d1f812ec04a2d6b9397c2edffddb2fbe213b86295b227e8cc63f310550fdd40447830706c18678a818eac69bd2916390eaa3aca24f65c8b8909989b80e9528f2ce990469adad271813eec9f4878e01f538cead25ded125c5c74b2769fbc596155acd5af3a09e56ab6e5b18d3f1f82eab8551ac69e6fd837b84661303c42421699155221a5a32ae37ed3f6e43b1d696b8e7ffff33fc77f94c6bb1f7a5eca070e2e72f38d8a9ffde5fcbf6c7b4d9fcbba29d7dc6286f1b143dca1fed919f7ef480bf496c167be0205ea02aa90e3b5c13453427509a9d1e890a3420e3ef2ea0405a61ca9c86ca85b3f8da5b9d19258df3702de1022e886f87808c6670b030471a84c818bda8116e1156ff9c2c70e00478f074e58c7ce079aec44616633744870fd14d5eca2c2e0c60c9b6ec2b0d195ed7b8f32c25a1d0160589b3eca265781fe4094c1264292d718a350ae01751b6b86e1ad00ba0be2288a8450054215eba1ab22b642d795423ba84aa11a8b90b8e5f87d3d89eeb23142b11ccb496bf12355a26385270c8e3f66da391574c6016900989baf513d882d6a1dcf75f5a4df0084da082c83775181fd78c793644269629be05a19ca5e83acf674971cab0b1e96a3e5ea97027e31d058825dcfccb8e78861727a8dfeea32c5b2e0ab41195fd8dafbd80048673bbfcdefdf04a8669805af9bace98c1dd335ac026b606685e91c56173c6125d0d29a7d1726e83443b57bf8fe028e3ec562427fb1812f13b4f1b4a67a64ed1ee43d8c84e8860f86913072c7cdf882e0657d8c9fc326e01dbf1f9412dab9a2a113ac4be3b5343501471da0e7155a57cc1d301c7acb1bf8998fb7d8716dfe9a951bfa2c7e59f16fdf67e9ad69aefc72a2626512d77368fd475737c87701f4ec006a01cdcffffab934673c792bd09eece28a3d987c069d04c22013a1544049205204648b229133589aa30bbf7132a8d3bc6e241eb2e1755bc4c16a874a2d4620e80a239e17b280fdab361cad5958b4fcec44877ffda74f407413a93d697b816e6f06a3a3b534eec6e237ba7a8e30b23e7557e8041701d4c6f8980f8abcedd9218e3b1e091cbd4f41a1312190d81a714d944a30a2638bd6460f1f4a421501420587772efe1fea38bc03ef11e7f11eca7ad022a4a7c897d763a0460b27bb1e63059bcaf4f1d5b00e126a1d1c378f20b11c53081b168acd31503f688f573270c36b212c85d8b24202de05922c609268011ffeabb0eeaa4b4ad96dd10d6e7d9f9191e50c117ca7cfb1f815c5d2473d899bc7aa0ebea3101f08de6d79fcdfb221820983bef0393cfc709bf6644d2af1bc9234207b7d3c47275495666a52d17486c9668f2479086316f175890f354a024a0cae48a98b8ce0cc403c27b60dd91c625066d3fed1ef1fd62de401a8aa4d63b8bfa10256048d2635319ca574141916dda5bfe2c89ac29e2769fee8f53fc77b6fb68bab18ae787ac16ddd947d2dc543f7186ebad112c58ae46c54a0ef02e8f8f69241e6eace4f3f15a189561a6d15fdfeb934a68aa8bead063d6e0204558267d4dce9cc950a5b83e456c921f51862a023504510176f5eed05a78454fb67a8eb13b66bcd237704b63ddf71ac91a026060903e9532c3669ec5822357a3461950e6809eb499dd17e8942c59d3002ce561906b1bf1063a04ee16ac5dcfd42a802552538e751aa2231168a26c1198cf54808600aa4b58ad1254973159daf609a6be4cd35d2f60a696b05db5a25cd3a18eb489d2749a20afb2891d58ff25f5ac778e23006da6a0dc069cc0ddf3cb4096813d62db1c14d8d1664e0c68f331df2e2d48495a9a2156cb70b36118ef7d663b14a0be2353d9fc604d398c56e06f1cf21a8ee79b2a6ad359f7fafe6c0b947694e9c880b880ba8ca8d8e75c3f13e9671a610868e8aee2671243315bd476699c9612a832c0b74d2b83844ee6a8df78a4603ea4273e27ec83a2759f8c62c2b0f6ea35cccc94cd4c04d538fd6d06838b4663462c8850d03bd115047d76800ba23f0dd3486fb571785a2aa495a2559aba69918aca4e832c32f6568eba8fb195265cc9e17f80f6f7ed97ffb4fe2f9c29d2d3efbfe2e692ab47a96a52fdacdc9e5efc6401fcdf6fe81849db60d4c1e2091a8414903744208c9c0aaf1884f4774a2e063467edc3afc678460ffc5c75f7b20f5b196bdb668e5aee050ca395797f40acd7dcb96e924b070fb0c92f5e92d597065cc9cba6404961b120d432b4186937963c2664822a78056db51179a479635f72c7ac2ae127101ed15aa56a489207586118b12a1f20a9d56342697b1131d94388cf62871045513b42798c80574b5c3a635ed999a66cbd16c7a92348cacd0d3ce1223a775593783cad00282708a153a8ca10eb7ce402a5cafadef73755c3cbc57f8a030490468f4a03f94915182692b69b65633529b8edf9bf2a297ae30b37369e4da4ae536585d3a0474081badb1b32561ce02b0de1974c393344a3abd1655a561155a5da1ae3455aef14ed16a0de2f933502686876f4de874037b9ff835b2fc04a1f6d4658a2ed238b774e4a05acd19c790e7b999073ae4876e1ea3d70db8a15dd7a57425ce2b4472ac4d68363c6d5332a11d7966b08d158a6e40a509e9f69dbcebaf2fe3a5ff77f7f7e67fdef2843d8a6e57f39429cb75a83350febf2db66f3d0ff45dd4fc202d584d595bf1b4f728ccd41369d83ec5421b9d0c037186c47aa249eaa2f5572b82de183f336a93ab9e84d358a303510aef4fe3f60f6e70ab36baef2a8a660dc1ce34209cb4741a7df6ea26cb6bc7de7bc5bb35dffff7868fbcd9c3fb3cfa572ce7743575bf45b6cfb3f6882259ee13a652ea9e43eb98297746c5e31185096150773ef8e5a714f5a083f274aee94aa0d5d47cfd7ecff94f749cb35db86629a177d2e05485980a9d78300167414b46838cac555279c19796ba5132a1134a14a601d818179352a17c40a62afa26b0960127055508fd761881952da24a928dd6e7f2ea8c70fc49a08e0e626d05a8f4ecd6fd3039a3bd1006d7cbf8d8d2d6248122554c4ac0ac686804b2c9405f14d5f6c0fcdf575cf262c3c5cf34dcf17561feae9aa284a9dd25be3f47a85b5c7dde329ffefb049319cebd38d05b4be82c55f4ef71dcfd710f0dcd13ae53fce7df6c32b1ef213ac73c9dce45f47a7bd8fb0c87b69e3c0b60c11b850e06950ee6c0594ab957ab9abca148438ac653753dbeb6249342926b1a52e34e34699a923d7ba7482f9ae5dedb96d91f7a5c74418b95231eb2c0b17e82cfc03ca2d99b395c6628bc213ff210d37a89ba98e31b9ffa3e5463961d4f78883c4d708d94d0972d5594865b3576fc76538b66a314f90e4f67c1a05843674273aa816907aa6ea0bbe298984c868458bc2f06acc84123b71c12aba8565b2469492bd754619aa35fbe9ad7bff0ee87efa3e4f88e1472cf6c13165fd5527c704da2f71e62259584ef02e899d9a98adf7ee501b02969a3209970d46bbb30ed06c6f6912829bd2126a94e4b3b9298013cebea25a792e31f0d7569aba671444b38d125a2327c9d621ac2e17f12169ee8994071d1558e935f5ba35ee8e3a581048dab5272eb067aa803eab58a169b043658305a0b616d10071c6691dda99c5000930aae0695ac53509406137494c9138f5182d33a26b4aa04ad14de071481445942293817d0de608da1651485119234d068c790415568d24cd0e52034918e1d4bc9c658e856bfe7b0ad6e082090a047bcc41a8f93756fc71879d425b9be1eea5a6a82d79c2cb6112e5ac57c56387e323079a2a6a3144d2bf8698b1fcb14994ac87c4d6bfb327aea30edde0ab2b8871a4de92c41c51ee8d8080c4a6d4cb46d0950a5a1ce034992102a4de8257802cdd99a325324d6904d1704670849421d2678cbef1fe6b77e7a8a56bb86463c7fa301ade26f5f0bde455642abd3a72a0d9373272876dd8651336401eae539745721eecc0064d438cb4131ee3a3ba03b9fe01605618d64cae0738d4d158904d25a0682e46720088638c7b5039f08a23475bfc9873f36f3679fe624afd89a1bfeed18ff7c7c00f490042e7ce615b4a73dbe68b0e3dcfb997f70069d38a4ca10dc466ac806f01c5a86ea2cb1cf5363a18f36e6b9393c306e7d2a25e8445157018bc60508def2ca577bbe725071ef9f0ad7be44f1d1ff6a98b8a0a22a219f70248d0a5f687cb50e92121865df35030ad3b0146f98791fc41b575d4c2cf48b416c2b89e59536111a8b5029899e9601ef033ac43a741772c418b471b1eba0b7312ae315a23d5a296a31a0a3d0b517850d161d2c8d690f4b31def87088b4ae465fa11715fdd5403df004562ba19418d270e251ee2c621c262e8886f87d313918df8b40632cf6e97d18c42fc118466c009b0836159231aea28448305776379dc9a34cededf1a52f2bf48a67d7e58efc02a19718b23a50f6352b3ec675e59140daecd19a3e814a9798544bf4ea8c6eaf81ab2d2ad5314460a2307276b6a5ba149a4ad06271fd28846db31e93ed029565ac941791668e1026216db074723f9ffcc21164aee4ceafd65cf6946c14afdd007c36863d8e7e43a872477bcdb173d7fd64f6117a45831016b0c153f8331b13433940bb293ca775d42db5ba80e93576cc753929bb288a4b7132059858e9a7fd9876e8a9b5f252833817e79233e824d6f69f7864eaf081eb160015651c9bdf9680f9f803a8201c5019ca663427faac9cd84bdaea625413b71904b7a022c54743379e017d26ee35a8919ee386f61cc8638f836e7adf1058d344a87b1138432a481ef84564e7f31eac8e2f3c68e07f81858f28f65cda65695121b30e631d6262e76e546c17bc3e7f65741e68c18668e5692dd44e48978136d4f3b1e4511bc17605fa6396609f5175d190176942146c16af09a989ed5c2b0b3aa0b268016a0d462c413cba01496d30c1a24b8b0e0e3d6358e8033a66de8d81c56a90054f85fb96c2291c487f969e51358186577804718a900a3501a5344ec5c48d36c2f258fcb2aa65438c6c08a000593350f6d6399776668a07efdcc1feabef25ff10fcd33734cf39e069d686e6799e707704a3241382532c9c0c34bb356e154cdb31290ee926a87e82b706554894300c0a9f6a127716db784561f709b937f4d702ddd582ed7b57b9e0c9050fde378d71e752f50dc6d6346684eee24e3efb574fe6075e7f0b5f7ca6e5b2a744b680f76a44d94ab3e8050098bd25a1aae975337848d02e50647d26f2258aa058edad5b975b6d538da8ad3a941dcf8dda7067741f5ee5c2fdcbbcf4e7ba7ce02396a5b592341b70749378af9dd2ae640c8bab409c63283c2a3247ac62f5e4242ffddf84a35fe23b697b1cd498505cde049b7a824f2956f6d19cd3a0025a5538b167b6163759a343973c0269b4e6b604cf512b83d3c73fd98aeab4e9b5da0ace3992d4a0ad23493d7fb23c77dc1c7884038b860bdb9afbbe54f2437f798cf7fcbb354239890f19c6562871635538e316ee0080bc50058f1d565d15a027255e062b6803ad95313ee883b2c175363ef6add74980c4e3bdc1a3c8c4a28c60d2b8c82817f046d00351006b21e90b2a0bf8148256f85a039a4606db44b1d072b137ba82ac1530b95c7001fabed6dd8e4e4ba8c4937bc127672e95b52116e4a612fbb18b064dc029504e8d70d2188161426810763bdef73450d1fa4c8574704eba0c18eba995d06cb7587c783f175d7a84273fab4bf76f5a3cd2afa84e26ec9e0b1bfa15a479a0d1f69485a75af1643dc7fd4b01918ac2ae0b31c7909f1a596f675c207a826a6aba5178806ab5e4de6c99703270ff3727d8b157f07d436bae8c19e9d98c62750f0f9e98e1829775989c7638a7e875cce87718c49cf15ed16f7b5ad315c71f105c5991694bd535b88ea7df4be8d9ad17b0a14e695dad2b54258962656481c6ef58fa7297fbbf50f19560086d439207b24685d6867e6dd0f57a53b8f1ff8756a8d6d1c0f15ea32a2085e00c2bf3d32ceeb5f025f92e80fe4b375fe7e499a7d3c9b1f94000245404a7b78cb19c29932e12d09bacb98d40cba90de436bbe99bc17353e2683ca9a425a1e7fbcc8a464c40b7526ef9832bf8d28f2c70c5038afc7c7579714cdf51cf3bac5ac28556d4764c3c048bf61118061ad2982071eee98d31d095f9406a84ba16ccf230db3c888f253272956d1a1fabd162b12ea9a66590ac0a814429940861904936032b5885016d4a7b945708918fe88de07dbc69d1b07742935f1e3879bfc7a6825d113dec9fa32b598fed9e496b40c7634c8c8e9e42c4188c0822825742113c498837a9d1c25a08cc58c57225232b38199033aa0110683bb45c0369d3d23939c3d1876638ef1925dda667df01cfb17b20dc15c81b82ab14d6464b346f06f26620cb0365e1d97e5e09cd98edf74eb1d8dfe8467b7766105d391148534bb25d91d735c72bc7135fde65f2c58adbff51489a1edf15ea6eecd699cd3866bce3e8672fe2477ef52bdcf3e7355229bcc45043295049c08ba25f29a80cfd01b8ee3c2faa992d9f4830566853d077314b14361de7f0d962116f965ded8d2efcec40477a627b8f7ca264e7b5f0952fc47866a504136298093350aedf6c858e1ec4f932542309037a5be932b629c5bdc5b7860df31d4b6342297082ebb649f24e54cfc95608558aabd4ba65b9217934568a16c2c658e556154963bccd0d2ea63af5f34e89b79ec6f25cdf6d10f118b130909e3b7ef40a76bf28e169d76aca1975de0c09ef7eada5b5f35894bdcba278841bf23e87fc3b19d297065499c17e6d64544e69ec800c6e85ee11bf218193588185786e7aaccc90b0aeb033fc9b2f236734e0111f49e98410f7d51e5f055c19cfb954813017b0839863b64b319328269466e6a8502d78160700fe89558f9708fc81401638ed489dc2e8c8854d0c58a54834181dc30943377c18eb7c64f5f4e1807a503d34ac225a17ea086413867bbfbc8f05b6f1921f0fecdda5a99ce3d8d76a18b8b8db9a400b0aa5299486161cd79ebc11b09560eb48e437551c4908e84a68687fc6c1524dbff0d89e63622a5eafeefd16739fa5a134794363939a6a4da85635466b5a731a6df7f2e03ddb595b8ce0d89ef1b4263dd60a7ed0282ec903d3ad9a2404da89c32d2bca9ec658a12a35da0adb2bcbf6cab2adde7a581fd91ee3e7958410cfb51626cecd99bec4f0bd572a1adb88f3a86390d291e87e2cebd47ebd24743086ff926ca0ad6022658d209801b366c7de3353180e7dfb51991e0f0b7448ae7654658a4e418a0621f31871a338e13a106e6c378c4451583b261e2b847579accda223a3f79e664d18075b3570a7b7582187e06b8cc38b4382462b87c984badec1b66b842bace281bede394bc5c3b77aaef9b587f9e667ae2428832b2dd964892a75fc1e13e2e454632efc98053a9e9106e874c3881c7d4a163c9a9ba0852c65942956266043c06990c14d6805240482f5b8819abfa96396dfd742e561b50eb45d7491d344d177d10dcbdc7afd7b929f4e91e9cc2d2d363f96a165aa23a0ea2d5a054717568dfd1e0377349cdac9d12a459e79564fece1915e97e4e5abf80761722970e416e1a1ed9a6d4db093c20c0ae706d66d229c33a3c89cc17845312898d8332b540f0b6e60ed5a7316ebe918703ee48d4053052e2e0c971c35ecbfc0f3cd7640428a048f118f310ea572427f1233b1ccd12f1ea0736c99b9dd1513b31e970a650f9c53582b648d80fb8c259b83a909cfe272427f16b6edae508d0450d8c97583c26d612d27d5badc5d708ae9742cb66c85ab2f10baa965e1f342d6d35167821a532aaa35607a18361ac606369a2f9206740af8613be038af6b0f4b7cc76df69f6763be3013f9eb323e7e5d22f29e1a853ac4f55a1051f51505a19d92ef34a82c25a9322832d66c9fe6a02da95283f2b2417679284291d8b11eef6adc3f19b80c76ccddd61b815b6b0db530e410c59b598db9fe0aefc2084c37bbf64a0975e9d9d66cd3771dd235b0b9a56f73de4bbefd35cfeece7fef2fba3f7cfa97725e74658f83bf7f0fcb0fddcdf94fbd94f644e0a12f7b26f781548aa41163942e68b48942282ed4f816c8ce40090425342e82c672824f147baf76547fa67095a2b1eaaf7af8b3feb6ec9901b3cf23c1e18ae8fa8d2e4458ef71336cd7eb8327584da64d249ffb10db6f58c872cdcc4ccaee27c2095fb394839f80fd7b029dc5c0dd5f33ec7f57c2c9af7acc17eb7bb2ed811f3d68f9dc0d35292573b3f614f2fae6dee9ba84440f909cc8ff240836688a2c30911866b78358c3139e1d5f33af0ce7f46bcc57341f3f5aa38e1a164cf58cd6f1f0397597c6ec88b4ad6651d19917b2a661cf9529657d09b7fc4dcad39e7d17e77e62f12d2b6b93ef583d5422172bf6ff80259d8ed73e5f16eefa989cb77abb3abcf3609c3f93637e896ec589e46a85b6674e223de33243eb3c8fcf13d61614fe7247ebf99e550c533b2c55a74669459a9b98f45cf518fa34f314d7de8f293ddb2ebb87179cf3d053defb749d2c937f61e2aa9aab5f9db2b6afc9cd1fe9d3ea3adc859e8240e741cbf27d09d6067aa6e6ca17e4eb31de0d0b50dc462dab070beacef64690fdadb7af5d922dfbbb2e790d241704d224d06a81320a6f12aae00674a88d1c523bb8d76a71b495627941b136bb4c7ba6c574d2262bf237bc8ee25dbfb4b349cb06ba8b9a24f3dc881a75b4bc5ec2b79b736f1f3b78a245feba54eafa54e4a66a1c3cafe3a6e1aad4c005a8fb015b69ac11924607d54fd95c66a9ce284bb785a5e3c74075d34b830f5bb8e572063ad3a9c7106a85d69ec428a4ce20f3d866c59b7ff5a9f397ffe6e7a73ff936bffc133f57f3ebbf314d623becb9f01bacce5f48239f60eebc0e5e881a9d266006f4114f2c455475427197477f33e0ee0c74e61dd5b98e7497b0727f60e9a1c0d39fee69cd80187d5bbd53c154cceedb44a14ca01c17fc9553416c38edbd0a58054e058c96589ca284eec315473e25743fa778e8819a9d2df8fa4ef0c714dd073cdbaed0cca6c2aea72b767cafe269c0ad139ab44c402c4a5767a58929bdd150554aa8425c298fdee3e81d0e2c7d25d039ead8ff04c5e284f0f067eaf6ca3755e7c5ffc6f2940b143b76a9cfbdff3ca1be40d0047c1ae828456a024a117b6305c3dac9ed7ceac33d5a4fe01ddb5fbc88fd6c02a9a177183afb346912935297fc6b7f3879a5f0f53f75a758ffe3db89ee196ff1d6f4374db7799567e7c5c2f16ffaa9c3b7f9e6d20deed865bf281c3d5133f78cc83800d9d8814304eba04ef7b2f4b0e65341ffd38b3f7e94a50fadf1d98f267ce68fe1bc27d55cfc72f04b29fd5eceecbec0c5bb6a9a138e7b8e1856161afce37faa36d19336e9d96e5bffe1b73514f78c53a58cf0ec5f48ef0ab3053ffe02f8c0c7219449a48755015757a4da0ee850034f480da951c3925e08419366e030b1ad8b2a0852dec4bfb570e500680b852176c1fcae05ba611a84289a7aa81e808fba916bcd736eb8052e535a5d27f0c486e0aa8a503a54b6465536d14985cd0b70f60c9977a29cdc8618a56c70074795494a088151a679f41e7306b752ad27434e49448d78749ae02d622a6c5a62ad46a99ca35f7f0effeb87bf74fd89b7b9f7aefc93e2a6b79fe469d7b679d3cd77f3876f59a275a9a239dd62f55805ca2065b4fa8699696f4049603ad76ccb147a4251d79ad93945d587f68c62e5a8f0f5072d4f389a50384f2b09ec7d42e0c4c94048858470faf0c36073e231418115820e68d1f8e0f15aa175a091792652a852c5d3ce55d8dd421043af058bd6b17434232c069ac6b17bbbe16a8443135098806d065ce7f4df3d4a2212b07aecc609eb2e7892791ab9a79b06d2fd0175005a3de19ccba573db71cfdc9d0dea271bba4b86ed2fb4e4d3502535a8125f468b5b79833590a68aeeca14bdf23cc42a2efd0d705f5c61db5d01a8595ed6b027418b455faac9766976dce6476ebadd22a1e94f9ed946aabea9989bb068a3d83b272bd564583927d3ec6e1a1e6846aee5b064d707c10e68448ac848d8717e42b9ba9b8565cd03738a2b3f7094f9b756e7ddf43b5cb2709bfec4f7fe4642325b0335bd8e6669c172a456a4db02e75c5c31ffe9b310e9c7423f2b1eb637d406009dba2461627b4279c063da0aa90457fb4891f31e65cce8ba1a060b81898af546e991329732426a15895738a9694fac1e7fd642b489abdea95debd5f006fe4eb74041c1a1815b8c7ade9354f3135f951e0707bfce3d64249325ae9ac2aff699baf841d4f23c3085d2e6d40aa4d380a9525b67d1c74d1b850c62306a2c29756a3cf67460391e1b5d571beaa312a288b10a384f2c5b9b4e792ef6c4afa3f8e45fa41c6968eeef57fcef339aab7efe73d4bdef47cded40eb22f69357ebb13c9168d5daaca6b1df323365c9a62c6b8bc2e4b9b35af25400002000494441549a93c660561c4fbb94c97ffc9d62553f4768ced67cee21cfe58b8150468d491736569ab025674ff0c1614db44c83b8f5f0451598da9db0fb4acdc49ca676866c9ba13da748ba82ed2b6efe7c8fcb0cf8bfa93876091c43e815012a41ea2d78a09bac60a5d5698fcf1368edd74c6dd7c824cc6d53ecba10ea45cbc925e1394f36d39d1b7acbee7996b0ab66feaf1d53d7809406128df11e2b42501a8c90671e660d72720a9d9fc3b13b1becffbeaf935d5973cf9182fad38e3bdf5333f3ac04a30ce7d796dd3fa54fc95dd7c5c0edad1497ad9ef12eefcad302e9450a3569a816e08a1f82cb9f1698bac670e7fb353d1f01d4054fa275140a37317bedc4a39422a80652eee3c83d29c78f35d9fe230f1dfed1e72e1f7ec7f34a9eb7a70d5fd57ce5b0c2b9c04255b16f12e677281eb95771e0658ffe9e9d507ac322d16cc16a6f8d958f070ee41ab5ec09942489c33b43903402a71a359d1fcd5f63e23d9f8ac22b874e1589d658f174e661f7fe795ac736ba8559fe6d9f8d7fec31d0cb6f4c440e56205cff4cd5f8c43f485781e210299fff64c29ffed68b99569334da354b65467761923c4c607203d484018545e9613c444672762058a53690ed3782a7e087341db50e8ac2e0f9f0f2a93390edcda61af84d9bab1292ac1f338d211006091b934cf1e25f7edd7fdbfbce7770e0991dbef83f325ef46cffa4afdceabf9a768e50348ee3eb16da7a8203db748844bea58ae23bf82c70b25392cf75a9dd0a45eed13670d2187acee195ff9e4bb07fbbd60d9c58916b2e27bde5c4570cfa3c41993af24c73bd65ec7178ae41058268b49358df5deb289a2bb1adc58965c74ee9713ce9d15b74341b967de709ed2c30ff0d98ffb2e16f17fdcc53eed24bee64a0460845c0553555152b9ab6e6ae9d6a851a745449d7eb4989b5874b5cd5c57eb58fdf5673225764ab2995aaf99b2ffa5fda43f24bfffdd69a96ae79e870e0522f54a541e709cd125cea06555182d582c93dad6d96ca4f501639b7fee924133b1fe1b26b1ee0252f5de615a5e7b677293854f382039eec55a7f6851f0168a9684c9dd9c20b531ebd6c4824a1b7e808a5c76735b29c71f7fd9ebdbb62975265d586dfc6c5180add1585b125be01bdce1c2bab0d3af373e43b1ee6cd7fb7c86c7785ecd28acba6dc554b3748fac7d8c5af62eff2b38a208aedaf2c4e39a6b258ffaeba5c7fdc01f60f1af46599e0d7b8fcd6b7cb53cec17ce877df1fd8f76a68ce28d2b6226b10856483c20faef1b0845a29e1ff69efcc83edb8ea3bff39e7f472b7b76ab525db4216d896888c2d831ddb296413e2e0989004a430938d6c7680909a501093ad24553249551612a070c64c8a245448186926157026667370164c0c76f01824c03bb62c5b7a4f6fbd4b2f67993ffadefbeefedeb39dd40cea5fd5ab776ff7e973fbf6edf3eddffafd596bb318837158618895a0227d4cac593c0b977fff592ef8966be79dfa0547413b2ec60df7b99d8b007afc50da54c98bceb98600c1e1078b5cb94ff157bffa16ac2aa0364a4a930e235284d98c8746061136f1320b7b28593223c1b3ede413195048d1f103b78f11c3c1938c9c06c14aad7a4f5ea855594a929532a38a33025f39c6b7ce90d4a639f8ae0a7ffd7b50bbbfce2b7ec53e7c495d3136b9ccfb7efd73bceff6db98b810acce82f04258b468de94380486a71e5ee6dbdf58dc1b1f5bfeaf17e23e6b301fba1b71cb3ee41bc690bf9d92f2f3bfe831bbcbdcfbc4f198e75f5362ae96e0a4268d1dbe3403f32f5716eb0af7a6978a8c74d964e4bb56184e3ddca03e5bdb9f1c6b1cd804777e664bfc309719b8d75d7709c117f7de29d9f39c99ffb1e994936705fbb0d8428a8e534c33b020e4f085901a4bd0d442b31613592dbc1502e9c1c3f72de1be5e3df4da59f3b604fbbb1af3913b48eebc1577eb5b907ce870f4beb71d0af82934fb7fd970e461cdcca329d2a5d44d0a88ccff09c4cd88b3f20d455f90a218db3c81599ae0fe4f9cc7d7cbcff2e36f7d92431f5ce0d80978c7ade17bde70b85dec5e2eae7f41df348ffbad63bb92bbd91e1de08cfd1d4e885d670e5bf1e077c3e6c994ad6fca1e1ccaf4d495ebccda49e20602872c4030a6202a91d476909cd948bdb1cce38f9c62f38e79ae3e30fbe005876a5cf347307bcaf2c6df4f39807dd7cd3dc19da62c37b23b7aef15dd26d83317acbc1e9fc21df9da17f4f10bde23b8f4c73c4e69485dc6319005a3348dbac43941202506bbd26c4e80d5160f456c0d462a2459143f313354a7ab4c141c8b8836806e1cb76dcbf43b54c47a6bfc4516dde6e851e4c113fb05d35f504c13f26bfb7e017fdc50ab46ecd821f1fc71d2a224ad5e42c9bb082fac5135cb1464d85d8f2e1c4ed136eb9533034cf0158d52fab20b5cdbe6467b8c1859a9e4e889c2778e1319a9b3f4c1f30c92843452a489a25036e07b2c3c77863ffe93f7f3e39b35dff7bb0133cb2127ffb1c19ed728bc6ddb98addd48ac4384b47861a689a1c11887f41b48ef3e2e3bf02cd5271b989a65ef773ba6f189671c5ffeaa634fe0f3fafd8a14c35fcc591a272639f5f4b5087b01c9a24494e37e00eb2490480dd27910b46af205ce68d218742360eec92f73db07bec1573f9b503e65e026c9c5e709b6009f7b186edcabb80acb261c9f7fc2e3ca9d8e77fce96eeaf3af647a9b8f3780cca2f37c9266902bf024ce663ec1d4186c0c26556cdaf68fb07381335f33f805c7ce573b624f116ac333c761cf4db08990251c0f7e4e52f4a670d12b11b6ccc2a350de92e599c6c6b517be6cb2d6674cf00284254d1c66d992240d7c35cbf97b9fe7caef3dcbf127326694e2e4e01b7ffad1d10be0a1e38e0b7f4830312d09d07cf2b38e89dd966bb787fcd3c73613c6d7e339d9f41f820ab2ff8969551b81335e56971ea659796cec65168f7034aa2952a7902cc2d80c5bb7cd3171610dff7c476341323fb7c276a2a3fe008d5db04caaeeede540667dbe80f35feff1f4571cd1b264f6998b48eb9722bd0a26c9fa7489d28a12d202cf40caf66b9928e668108630252bc44b9a850d5fa471cd71def40178fa7ac9c9594350725cbd2be65d6f481c3821847438c7779823f48502a8720e2b6edba7b8f301c7d6ef7a3b639b158531cbf3a7222ebbc4122d5e4f4355088232d3e3166d148b8d887241f625c93bb5f25a0dea7bd4e1cb94856e006d6b9c2d00eecc1d7c01006ab1c8c0e17b86a8e6834e507e8254450a939af939cbe4c68fec7cf03dcf3e71fb1c4c4d059c38d238ef81c3f6b91bffb4c03736fc00cb73e3b834c48acce76bb5465bf0fd18e5cf3136719685272ccbcb82c909288d65ad88a31a3c7b3ce6bcab14b5c4317b22656247c0f4b6cb286da860754a63a1372fb367013983331255106d60735aa313878e7d0a7a814daff83667be253091c62b294ae31e69a211caf1e85cca8471941b9ab866d9764b9d538fedc0333bd9fc3247f53933520b6d5d535fad68abda696c04d648e244226d8de5b392d43a8a930e27256847a326094f5bfc8d90c486d967534a3b1ce7ed99626cca72e65b962d5b15ce0a749499f1b2c90fe094ca386585230c04b2bc52daa8170df5454d63d9a0dda0c0e10a609878741a935b1614ca20838c6daa360b9435954d2093692ae727784854b3805c85327ba8b434b99240a619a0da44b681d3f725c2931436782475477d36a531171337d2cc076e245a494ad35e575e6cdff58ffa8b63a45c69059e0a413ce7f06483c258883f51a132a589eb86a59350dcdcd470ede0c4eaa2f13863228ad3860de9384b67357b7ef3af38f2d165eebc5ff2cdd7a83680bee975110776a739807603688010c6bd0b4f78f749fefef0db284d4d102f27d44e47ec79fd0e164e5d824eca28bf81b0014258a41761ad8f93e98ad6d8042ea956b448e1b981e6fbc0161c035a7a0c32db3b7da22ddf69570ee8204015ae9dabda39f744c1e399a71afcc2477e975ffb19f891b311efb9c971f8a90a33bf57e5067cfeee03d75128bc8c305454670452d591769ce91d59c3b0a4d9bfc6088b8b24d6647ebd38514c7abd690fdd8bc416196dc20f082c75ee6fa4a3d390c6c704d50589bfe90ce9b246471ee3631e76698a520974e248ed4a7d7eda747e5a93699c85c2e81427e746a7b5c4daf504a5ba7f5fd910838367cd7955518e4821116d0d7958dd7b6247fb40fdc126741b74427f9534162b06feaeedf3b1a3c7a73de737ec7aba01450883ceb7739f7302ad35d264168c14026b1581ca4861ac56c4b1a11238cc7cc8f27cccf9fbef61d70f7e9bfbff5bc8afbf3ae6b7ff4e608b868b7dcdc95f4e388a6dd75f1f4370c099731b4037bfb3e2ce7cb82af61c0998ff9b1b09cb97529c0c289522acac512adf8c8ec7115223bd0867327e4aa952acf1712ac900b4870754ca16859666505339d1a45753bee8315bdd70f01c10c957882e00ed1c2b06d4c6b73e77e53c0dd19cc5e86ff2e90f1fe5673f6af9db8f087ef52d868dbb7c7ef3c99837bf7e9c273f731de50d1752992e628c23aad6a99e12201312e753081c7e49e30502a13cbc40605345b2188f5c0c2eb0abe6618e4a311a93de48b0ad5b4b9c3afcf1d3a49126adfb0442e1459314cb19e877058bcc0a48afab8dca6a37da100db7316507ee6f5da3e2bc1c0928a300d239c174108c3caf45a1d70460c34436833c83007ce85c43c0be0f843b6ad8870274cff8ae714e106e2a64fe5aabd1187424313a6b5c176b47f9fc103b1fe0a755966717b9f2e7efe1af3f91b0dfd599ff6c8199ab2d9b5eae39b03fe1c0eeb4b980dd4a14f11c275476efbca32ef8b060bbdec692fc2e8469206dd6882d9abf1929cb78ca22a40627b36427d1aa6fcf22efc6ad44cddbdb5d06a2add2b0e1dd37c560f0ecad8f1783b5d52c98d304483ae6e9dad6f1bf9537da6479aa890641618262b28bb7fee6ebf8a3dffaf4777df481f06b7ff3819437eff2d85116fc9f0f2e72f53df770fadff6e2cc15e04fe1871ae12f23644068417aa0639f78d9c70bc02f2408a55193fe4800ec2cbf1ab4bf4f1bed79bf60f448809a9645c254136ef0491c44738a30f4304b0e3f045ff9ed7e42ce669161acc0489395a9c6624d40b856c0ec957071150d74831ca9fdf9ab804abc0a257dc957a317c8084d0f32be8051e069fdd1da646a476b99be5c9b06dad63a6d37db92b031a6592de8b40024ca9328a0e01449153c59e5ccd38277dd710f473f5ee591cfa43cf225c5251b53cebb5ab13d362df06c8625e4d1a3880307f2283cdc82e2cf513cfe2f6fa6b2d5102d2a92aa8f1fbe82894d058c49b2fc492b50f80891115c604176b165673dc4dbb7a3b058c7d096ab6d4a3b3daa37bc4349398035a8873844405fbb96ce6da27f5bdb8414217eb981f07c66cfeee3677f7be16bee8e2f72f35238f5c8c7d3f90f39c17b0f86ef4f2e4edefda58d0ff1967f7d8a47ffe135d416773175914fe00759d2b2b33416206d68a44bd00d4754f599d8e106fa38dbdf47335283c4f40092e91eb7c95be5374f350a8332e059870a0cda19a4278984c08fca5899f1960a69b3e4142bb2924d04c92a41a6a11af31a2d3b5d714334d0e61d9dda5500aea36863c0692debd1003a6e19e902588d124f0bd5957db232bea9392603c0b5e32ba92e00ec9dc77578007add25aec76cefbd06d98bc5d31a69242accf804c62a16af209025818b154f3f7a96825ae073777c9edbfed0e3ee63c0fd92e9ab25affd0dcd5d771a962fd31d933a01e2e041ec77222bfdba4d78ee159ef8c9575fcbc49657a14491248ad8b2e33473cffd27c6b71689aabadd1c0e1c7e33e5c4d966127dbbe958066a9e74fda422237ca07d8b6f404a542f1f68e7b14af5bb0ffa28ee3a167666c27704b1021fafbcc8fc6c88a76a9879c1f537dfc5f7dff24daeb939e6a75f35ce15df1373f7c739907e5ceca9200efbefd0bce3c315deffc7b7502a8e37a3b31a19da763f1e211d2655d4e7e2e1e00990ae1190866cf38674476cfb1ab5238a0c41a1810e2274625181c16a894914a12cb75b6cb423e0228b883b27483a0889d7a2210f03a0619ab41362a409dfca535d0dc886ed9f08463b31179b4c2e6bf53df64ad0e343edd30cc508cdd18a36a976dfe7b7beff0086b561dae7a0f32d6f5e496bd2758949fcccf52563946778f50d9fe6e493095fbadbb2bc7d9e8bfe5290beb9c0d425017b5e6fb8f7b12536ed7799efb39995fd1ddcd2e38504913ccebff41799d82e29943d8a65c7c2e91b286d708415481a220350b112ed5622eb83245582b6a259ded6f237dae6187ae8ec6ccf226cce25c550edb4e7abf569a11901895c1780b6ceb1f5be58942c2729b18a088520a815f1d379c62fbe97e4fbbf79786c57fdf0cf38cb3df74fa31ad1e1ea0d0be7fd0be5dba67637b8e94b15ce2e16b8e78397525fdc41718bcf860b3c8a9b02c2824f5096344e27fd00d1f930306e5da670ef7599491b238fdf5c08482287f53452c610a604c52c752a6d7814424152f3896b01d648422590de4a60261da24a8e04fd8e7d56f7170a74be963362e85cce0aec26b72e505b0b89f2aa7ead4e1ff53a81b5cfd4f646047b6c4617380a20d7143c1a71ce854a0891a5516f503ba359782ec645cfb375e7231cfd8dd31c7977815bdebfcc55c200e3fcf00f2b2ebf4eb0f03acdc4ab227798a8b385f1772a70be70007dd391bd3cf4c9d7224481a9f361627b8146f54a745241aa04448a6c46ba9d93e0049e9408a91152d32a75f76487262833b09542609d190c642d8d55b10ef07443b5d5de887bbfd6db0dacad6092140647d3c4510e9728e2ba238d17d97ce5039c7ae07e0efe96e6f229c7d7973cbefcdf258ffc8b267e4412d6eb9c5c0ab8e257041fb83d6596908f7ee6653c73fc65383346610360835581672dc19a61405a09d448edb59a9a76fea63159a5945211842976c9438e6bd2c88328c0240a211d61c7a24f0630a6afe77cfd74b48fb1313d3a88e49d75a3c1c31fedbb5c36a3bbca4dfaa3bb2669ed5e14808e3ab71680aee6675dcffcbd73d5e61326379fe5a21b9fe2c68b67384dc2fff83dbdefd9dbcd0dbb117f70f181907f3b95f2a9474bfcc41f54f9d84f38defbf60abfff27734e1c74b8a3a6a575e6003ae880fdef7e23dffee78b49962d133b8a14cbd7519a2aa1e384b41a43c15be903de0cea08a9f1a4c49a00aba23688794a74b71416bc380015aecf64ef06cfee1621bde3bba2ee1d012e3ac899e71a315bca2195a0880b1296ea31759b50108aa20d3833f738b7def13f2f3922e2bd17208f5df740cae5db2b14ca8ef987348fce669f7926d52c3e2f297f5d50be34d33abffa4f219b5eaf5f925fb630d6fdc316a7b2f77fffe8e01fdc6beebf7947e6238d9604cc425c1394cf6aee1411078f7a1c3b916998b7ee93cc3704d54470e915925a2363542fbe6b7d37547943f7f8f16f65ef97e67b16763378c483cde38addc7d59ae94de51bdde0e35a9f7753cffeb3ddfbbf77e76892f1cf1f1dec636dd49a5900e5d1dfffb33bb2e375cff733cdf3542706109c3456c67eefa5ddc7453dc41d856bb2e3a3e5eeed71b559d259717ddb3ae553b71b36ed97849b0c5ed9b1f7329fb42198de05b519c9cf8582078a8efbce4f79ddf7498eff9729fee28f4f1ffd23210f6c070e1e8ee1d0775cbee74b07a0aff88137538976518d2db6183255bc1659d2349c61dc0b889da145e5d51911efd238fb34bec16d8e7bc1b32f3a3fa8245432346734d3727b13f1bb81d81762609ea8681284c4edbc9d15c0ed1c3b1e052cd452c6aefa34dff3b6afee7ce7e795e4375ce587ee974fbd0a161efd60b680c28b1c8571c7a545c9dccba1b120889704676fefd680a264ed26662158c38fb9b77f53e722fcd2cce868cec96396fd5f302c3f22d877ca67fc5ac16565c9e5d719eebbdba3bcf3c52d9cb9bbba016a7cbc7bbea5a5d1d7a335be735cadb6f2fa82b7be38937de317d73e76b9d6f310a80be2fdab5c9fb9eeb7d38f0d1f5aab0be67776fc8e354161fbe8f9a79a0fa07a471553d4f17af1c2e6bdd4f1802a743c143e7958110696f1b2213d9d70f576c3a1fdd671c8720ecafaa3f0f5d90b199fd00869d9b0eb31dc73af21232a0ec0336db073ae930fd1619c1d1ac05827e8f704937a35cd558e16767073b9568cc6da6e706d99f0b69b06afb75d48166c74d44b09829485875ecb277ee29a27deb8fd413ef5af5f6106cd27ee97ecdb96f2cc3330fb9864f693922763f043c7c4b4a158769cff27836dccb809085b9418a96d6eda31fc46aecd0bca536ee4fe3dcd0a9ae2f4e071b71fabd3bac8afbcdaf0e498e41f1e50cc9ccacefb6cf5c569d095e6dcbdc039d53cefa9a9b501746bdcfcbce89aebe9ea8bbbfbf47abe5ed803f80a4e2fae724c870763cbc31901fea8b1d109c199dd2df59a5569e1a30e2e927a8ff6da6808ccc6ecfe39d3d460e39a2039bd326ed7f69862e0987f4273e576cba1fd160e39ce51593f807a42e20981b31e69a3800768a3c09f27d593e0a57dda5f4b23d4cee24939d0df38cc3fd366956fc6f486f9d306058c7ab5cfc1c1a5e6f8a626e98ce80f2c7581a41de9d3ab93501c9354c68b6c288f837b2db7bcfdbb59f09ee4531ffa3b7ee7ae321744962de719667f54638e5b663f2df9b7bbc4a43fe32f6c7f6366ce2767073f09764ea8411fbaa2213e36fa0932bfda13a69a016867a8a9d061f6edfa678fa79605de98e3da5b5b609dd2080407f769feecb3fe8bba239f38dd7c715a749bc3a79a26fac4da176baff90e30bee3c53dc49796d6f8d93dda670bace64eaefdb3e44ccf866f422974b0a77bce68791d0a507d052cfb1ed2b160fe8995fb2b2839c2b26362ebca43f9c351f3ceb8ab33ffe99c05d0f59bf02fbff67d6c18d3cc55251b5f7d82e8f1b7a34a869a8d288922c2375d26796747cdee7249dba541b6c677f94507f840c550f3bfdb372a189ccaa4c4804aa44e3e502b33306d06b67ac1d24add7d5e3d25a071aa28170c93630e4f17705a13cb1a3367a19626e83367d9b4f359aeffcf8ff3decb6601cb3dcf7a2ccf187ee855095b6f1abdc0afdedeefa3ebe45d9cdab472b30f029b3e9fe0845bd7fef1b70a26ce734c357fb7bfbfc363ea42cb339ff0f85a9272f3ee9786817c3d40b91e00fd8f96466d9de7b01778b867dbc36b74c7ec1d317684dfb40b449be6fcc4b4a558769cb7c3b2e735969dbb1dfbb232986ecd63255529d3799ccb0174d401bbf6ff3c93722bcb699dcaa54f22667e1c1b48aada30190a4c9325be9586b4c29894018df33a8944fa83384aca557ca0ac0aa0c3c0b36b4c673e6a07202a219afbecc0e8be15662878024c1463a26a05e96984e7b046a1fcb4c913aa48d22a71e4a32389940d8458c0f7eae8c49236e082cbbedead85f4b49da8f6d412f7b6a548e36e80953daceb613c787b9f75d8b1bf736c50b06cdd5967cbe5318f7f45f0e57be0f3bfb3c0119c3b84150751ffae77ec8183a3f71f3bdafd7ee6de6eb0d8b4ff3f7681f77efe7a65ff91eef7bb9b695a2766b279ef3df4d29ce7f223d97c0f3cd7717ff59be6c3b4cd1c40d77ac0a53ff8260a4b7b594a17a038c654e91a6c2848d422252a5867dbc0a6245ddaa0929dcc4bfd1aa618d8adb31bacfa5d006e287ddda06aa4de7af73e37400f9148af8b60057819d8986e2c0d315e44641ba4d662a4a424038aca27f0240ddfa13c8d731aa32d2616a491437a1a498252857e32908ef791d5fddb3bf6cb9edafd5e320ea507e7570eedacd9932e74fab1252a1b6609dd124bcb86d3cfc47cec6fbfc5b11b2c87ef352f7601098ec8510bf8c5cab96c6ebe3488d1eab2d07f1d45bbe639f7810e97d46a029979e2b568e09cc25329912ea27c81a52325a8d3ac162e63eaee6cc9b14ef01cf9e3f46aa1c34a39bbb6af68c62dadd33939dc372b56a900128e148d338e3429608546290356104b4d10a428a79ae4ba199183f405610042784829094a7200687768800c26036957eaf454f2f495c61a3710385bdb3cc47060950eb75c61624b9562398627358d65c7ee4c3372ee25601d17873b8e3f3cc02deece6100fcf727e4e84c821f79dd3347db39ff305a3f805e78c3e33c7fd725c449017fac4179ea49a2643b9e1478ca91ea15adcc409b34a49b21690873cf5ab78dba034c473be4f65dc10afbbc15dde0291d525aa4c84a505382a1bd9a3a996e8669a10d11e12b49b10c81521813e2748a90313333214aa41960fb0ebf9580ee6544d058589e498736c41b08dabd20a76cd7febe317af0bcadf741d36c1838b77414a73c0ae359e554790c42d1997625075fbb756888ffde0029fe3fed12d955e229e4f0d62a2ff263dcb0eb2304c7105de02d106d85f41c7db0addf84172264d3cb7f81cd970afc421984259a7d25c15885e28684b42611c26252db4ca05738ab9aace1b64b2becee6dd404278fd535d08ec5edb54a3b9be6bb76668016da315e89be40542700faa4382bdbe67af6c0976dbf691f38f51c2f5bb5f37254dd7e37b8757e1fd993c8cf2aa6b518969235c484271d7d7c8a59a95e69d6dd8b667e692025c6a42cca3314c4029729c999470c3ff5fe7f7557a1853be63b0e24b99d9bcbb922f2051c93108e3d8d8e7dac8bb136cde6f16a248d527bf14acf437a3e3881f22210294ecb2e407b696c0eb7262d75ade39c134d32bb0eaec49ebccfb5a5520d3f8f815903ab68df2f043c879dd7a8e385706dba3a59b088202bd50c0b021138949730bd6389ca8698a8a6896b8e7d2d0ea003265f52b9e400ba8a8ecf953f791fd1a2255e5498d850dcfc103a9628b1845412a9249e6ff1bd0494421362758890fd64c1e645a9feeb6325eadb3e2027b455bfdfb49bfac057f41290f45534ad0ede6240f0498c98635450a70f3857193b0c3c856cf650ea20d90d424910ca2e4f4f6db6889f3ad265c5dca2a06a1b00b771950747f2004d2eb9093fda85840014db2e3f40106c21982852de5c4588691a7357509e8e704e22a59731d2a75ec6c2148519c972d38ccf5c6d2b266cbb2db1e706a71d0d31e9fd4e7a3ac84cf821e63ba2397e88f92e70b499f806e4888a016d3f0656257598f06258f5520f780ef377f682aac68c7e086846fa3007671674b02145200b20029799eccdbc78af19bdaf9d5966c3454fb3346ba97fdb529127f9e81f9e42b46e2567f365954bae818e92a92b2adcf073ff9b7ab58e8b34495581ab835e2259f6b089c59181a76dae40213546a743354febb228bdd30eabb3ff9d7f36cdfeaf66864b035283d0204d0688d2bae66b87d4cd31cd71ad3fd51ccb889ef12fc8bd304c535c2778b634c451e0d9da3f143c079d4a4fc45d7a06a92c051f5c214bd9f2b423492cf5c8b079dbb354024bfda4e0dbcfa414af996f6aeeb9f6994b0ea0abca41a49bffea82fbcb5faae2c9af90c40d74a382ad0944f830895e2202c062800000065d49444154ae2bd2aac05881941aab439cf5912aed0209e3dcbaa3ec5de067b2a8bbd4cde8fb5a5c700392f1fbf32eedc0b4a8d5fbdf0c07cc5eb37d3de0db057e29605c968e645c9612d57a9dd207ccab99fdbd7467ad9612ce66a4c93a72d423435c4f48eb0d5219d37822444492d978d1dd71a0c6831f9180e48617f840ce25977306408f623f729bf0f973029e3dfe1046ce2154232322d05542f5083a9ac3b8049d08f06b482fce00cb54fa00c4ae4373596f03afc1c0395a331403d89e56f35176793856fd12ab8f19067e19a3ff685feba88e9c6bb9bed64aac94441a74e430a9c59908299728d805164f7b10c344a141f2932701c1be664dfcbde4e67b2e39808e9407f16ebd13c7f5b85b6f131ecfdcff37d8d847f90d1af3e3247183f2e4b798d8f855848bb08d0adaa8acc5b1306dc6f9ff30590b70f68c1b084ae205769d5c0361ca5accfe61d549a332030682e72a00def9b090cae21752fc429d626191b16d6749962d6141531e4bddc903319cf0387644c111912fa75cce3579614124213ce73007af253c7a9f6b646d3ef6fe287eb0917245e23c89f01463930d8a154375e102eacb3b505e83a0e470a64450d0c8c0e2ac07c2e28446daac1ac85a9575f4f4ba0940c075f4d16d2d74ba3446e76c568baf34ce2a9410282fc1b92c3f354b5ab758e3e17b75d2b84450ac628d97554b85169c22f0572e8cf253a25821a446c5c323f3e090811dd811b45d29d4b07dc0dc3987f58710957401a31d587934aaa571eff8f6b636af69f63fa95a5c2c509e617c7289cac639a29aa5b6205972298b5f0fd9b6ed146f7ff7335cb7b1ee10eec811e4a1434ee7cb2997734dd65f897410e99c4b7fe966111ebd8f5808a1f8e5fb7cee7bf87ff1baefb99e7a752f8540e38294fad902715512949ea7b2f1390225f182847a7523da945091c0fa0e290c229144a94f61ec2c0885350aafa92d1921c181af04b6d5969756bd7d77aea6d6191bb62045491f87c0a251426504214dd0904250282de179017e2126ae9530dac3d83a3e1e928cfc239b5b23ad24b59a82d7dd97be937d1f409ba6bfb4c7f476b695f8df1da1ef62ef17ae1df5ee04c44149f90301749ded3fda9fdb11a09adc5045f91622b00d477416666714a6a1294c4794ce9be3fc57cc71ddc608b080d8bdbbc9c8730ed642e7926ba0ebd6401db89fbe4114feec0b24d91ac5c339c37b1f2e70e2631bf9c63fbe9ab8ba95702c4128491096080a260b5024cb3851440945505ee978995a8d48242ec8ead19d132bbd76da043f0ed95179d492d05bd916a506212d419862b4d7a6a7735622958100a43208ed2854ead4172b78418ad4023f4c681885708aa01823d302d64a3469bb96bf14c83e10eb34a3756207026b4b0b6df51b6a8d91b23f1fb3ebff10d37c1058cab544da871cd7daaee32263e59824d6c40b92fa92234a34c65519dbb6c4cffdd82c6fd8d500aceb724f382b8e20dda1dc0f9a4b0ea0c30f682f1221df781585bb1e206a8268c9395715e071378a5f7de346e61edb439c5c8c28f98c4f2ea329611b0ee55b425fa00a0e673cacf540c648a3714ae11028d98c8687d982f4655626a9a5422893816b4fa590afa0a67556221a2458ad9aed946d93b843b799e301bc2021ae56085c8a17c69427ab2cce97f084a4345123ae15303a2b4395ca10a59252c9eb06a21e335b2f9a8151f0d6361788819ae49a0134664d81a2de6d52bad5fdc080f22cd2b344738a465de0359698989ee547de39cf81dd51e646c1ba766d3470009703682e3980aee5803d470277fc50d25aa5f71e41ee6f2e9a5fba19ff4315a7d97383e0d0175a394592ab7fe062288e93a453109549f1c04e81e7b7d3831431ce59b001ce176d33dd57603cd16e2fec0b3fe3dac4619d6cf7306ad590a7d6b5fd96d6a8b6060ae0873156484c9ad9c9be1f410b589b621ad9798f6f5e6071b180d58ad073f8614a5c2fa0426f20e0b5b5b148c0007f646b9c2cdac1003a04487b5ffbcbab83652f48ca7544e2a5bf4c71b28e28d4d8beadc6a11f5e24cbceb7bb8f08fff82156ae550b400f3a9b33f3e49203682eb9e4924b2e6bd737f24b904b2eb9e49203682eb9e4924b0ea0b9e4924b2e3980e6924b2eb9e4009a4b2eb9e4924b0ea0b9e4924b2e3980e6924b2eb9e4009a4b2eb9e49203682eb9e4924b0ea0b9e4924b2eb9e4009a4b2eb9e49203682eb9e4924b0ea0b9e4924b2e3980e6924b2eb9e4009a4b2eb9e4924b0ea0b9e4924b2e3980e6924b2eb9fcbf2eff17747add7de1ce1ea70000000049454e44ae426082
);
INSERT INTO `foto` (`oid`, `fotoname`, `fototype`, `fotosize`, `fotocontent`) VALUES
(12, 'cabezaldepagina2.png', 'image/png', 39178, 0x89504e470d0a1a0a0000000d49484452000004080000004b0806000000fd1ab5cb000000097048597300000b1300000b1301009a9c180000000774494d4507de0c1b013a01f5646eb80000001d69545874436f6d6d656e7400000000004372656174656420776974682047494d50642e6507000020004944415478daec9d799c1d5599f7bfb5d7ddba6fef49272421102081040861519200b208b28b03232802ae2302f28ee3e8a0a2a220a228f02282f83a431c500709209b0c22bb21410820492010b291a4d374a7b7bbd6f6fe71efa9d4adaedbdd591088f5fb7c3ae9becba95367abf3fccef3fc1ea9a9a9c923468c183162c4881123468c183162ec72701c87bdf6da8bc6c646ff3549926a3e234912922421cb32008aa22049128aa220cb328aa2a0280a9aa6d5fce8baeebf0ee0ba2e8ee3502e9729954a94cbe59adf6ddbc6755d5cd7c5f3bc9aebcbb2ecd7235897a8ba6e0fb6e77b51df09d63bfcbaf811f7e7baaeff9ef81df0db54b4a1a669241209745dc7300c0cc3f0fb4096e59a32c3e58a9fb160b4cff5f5f5a1c65326468c183162c4881123468c1831765d188681699a789ee71be04108e33c68bc0a824051145455f57f84112b8c5b5dd75155154992705d17dbb629954a944a258ac522c562115555310c03cbb2701c2792185055155996fdbf1545a9212f4633e0c5bded0c82602c9f0d1adb41435d18f08ee3d4fc08033fd8cea25d4cd3f47fc4dfa23d24491a5676902c08d625fcbff8ee584802f1d99820881123468c183162c4881123468c5d18c2b00c1a9b41e35bbc173cad16bf874fafebfd2dca099f6a073d0204f110f44e08fe1dfc097a1308a35afc5f8fe888220bc2ff870d65d12641635a782f04af132604a2ca12c6bb204a447b5896856ddb3e51102647c2e486282378dd70db075f1b89b4180b39101c2331411023468c183162c4881123468c18bb3841103488a38c646198068981f029753d77f6a83283906519c7717c6f84e0ffc238d6340dc330fcb085a0011dbc4eb0fca0211f8591c881e07b23791f44b55bf89ae23de12d60dbb6ef2d21bc092ccba25c2e6359560d1111243dc2a44ab07ee1b60f862c8c8508182dbc40941f13043162c4881123468c183162c488f10f40124419d051866ed8688c3aa90e1307510676d0c8d775dd378a555545d334df8d3e9d4e93cd6649a7d3241209344d1b662487bd1fc204c0588cfc91888da876a9470e84ef3be88961db36e572997c3ecfe0e020434343be0683a669be16439024106d24088f701b47f555f8bec6ea2d309a17414c10c4881123468c183162c4881123c62e4e0e840dc32883537c36e8061f260946726faf4742a8aaea1bc3410d83643249737333edededb4b5b5d1d0d080699abefe403d04eb3e1a49305601bf7aedb6ade28622a42097cbd1d7d747777737ddddddf4f5f591cfe78791046162a09ef13f5a3f04eb1a2637c6421688efc604418c183162c4881123468c183162ece2044194d1193ca58f12ba1bc96b20fc7a94f061f0745c84110821be4c26c3f8f1e39932658a4f0ee8ba5e93bd6024e33c48626c6f668377928cb16d9b42a1c0b871e3d8b061036bd7aea5abab8b7c3e8fa228944a255fb05184548c446a44113cc13083d1be371249102411628220468c183162c4881123468c1831767108d1bba08119341e83444194ab7b984818c9e80c5e2398114128f4673219264e9cc81e7bec416767278944c2f732087f3fca75bedea97bf83323912561c1c31d215ea2ea2cee359d4e93c964482412288ac2faf5eb715d17c3307c21c3603b45093246b54530d5e168f51aed33c17110130480e741d4981871c04b12d218cbd991ba883a288a8c2cc948b270b7f1205003e1a2e27a2e52cd80daf13aeddcb6ae75637927ebe679a0282a8e63ef482935edbc35564842912b93d8344d145589feeaf6400a0e33194d5529148b0c0e0ec69335468c183162c4881123c64edb978f145b3f1683b21e2910cc5c200807115a904824686b6b63ca94294c983081542a35cce08daa5798e08832a2b79520184963607b10265600344da3b9b9195996715d977c3e8f6ddbd8b6ed8b3786b50cea1123c16c096331f6c762db86dbe01f9c20a8187f92546918dbf1f03c075d95b06d8796d62c4dd9565cd7d96ab549a0aa1a5d9b36d2df3f80a6a9144a2ebaaa216c77cf9390246f1b27a830e43d3c4fa26459649226132676224b2ed9c634d9c62c2d2d2dd501e18154210d645966ddbab5148a258a25977c2ecff2575f43d74d54557e9749170fd703c771c175e818d74e3a99001c12a68ea256953bbd9dd39b62629a66824422c1e429fbf0abffbc0d4d53b6f31e243c3c5cc7c3712c264ee8246168a452064dcd594cc364dcb80e4cc3888877da4e7e40929065093391a4adbd837df7dd9f279f5ec44f7ef2d3f8491623468c183162c4881163bbc880b091186520070d50916120fcfda8d8f7b0411e347483590b344df3430b3a3a3a48a55235e911839a07f54ecca34882ed55ed1feb67eb912be1360c1aee9224d5841064b359264c98c0962d5b28168be472399f28701cc70fc7a877bd7ae11da3f5f358dbe61f36c44018ae9e071212aee762db2e8e5366ef3d273377de5c06fbdfc6b26c52e904e954a662748a812849689aced0d02085a28569ea24120ddc71c77fd3db5744d1745445f1098591c9a80a4121fac7752bb12ab655e4d4534f269354686cc86039365407a1aa2a486c1d388a22a36a1a4d8dd3916499643289edd81c3d349fff7df86156ac5c836e9868aaf277f226a8101c6212db8e8be7daec39750233f7db9754d240d7b59a89b47532783b7a694022954e3361e224264e9c4c32ddcc2f7ef95f68da368e8f2a1964db2eae6bd394cd30e7c083e918d7e2b7beac54eaedb936b60db2ac54ff76471403a9d36ac1650e45d1696e6e62f2e429ec3669371a5e5e1e3fd962c4881123468c183162ec30493092c11b155a10240deae913843f2f8c672136182409b2d92cededed34343420cb728da27f38755ff8a47ca44c06e1cf8c653f3e5a78c15853038a6b092f873079203237343636d2d1d1414f4f0f8ee3502e9787a5361cab37431479b323e480a8ef3f1c412049c29074b11c89e64683dda74c64fe117319ecefc7b28aa4d3299fe9f15cb7120f53ed2859ae14d0d0d040636365d0ab9ac697bf7c1132320f3ef420cb56ac2657b051640f8f8ab519ee67713a2d4c43d7f148266466ccda9b030fd89f62a980eb3ae40b395455435335644541d334df109500a96a587b5e6542954a251445a13163f2a9733fcefaf5ebf9cba26779f16fabd0f584efd9f08e90059e8457f51a705cc83624d87df278664cdf1b45a9306852d5eb41d335145545d3b6e6391dad425e688118deb7128aaad2d1319e29bbef4936db44ff60619bc78757251b2cdb62ca6e1d4c993c81dda7ec46b95c06cf43330c744dc7304d74bd92ab55d374e400cb1ab5588d75c19665996432c584899368ef184fc24ce27a6efc548b1123468c183162c488b1dde44094f74094e27fbdcc0551c6f9684674902810de04e9749a743a8da669be515db11324ffffa8f2c3227ea3edb5eb29faef2899325a39e27a429c519024e2b56432493299647070d0bff7d1d2198ad7dd3af6d0b678178cf4f73f1441e0558f843dcf45924051750e3d682afbee3b03c7b1d9b87e3daa5631580dc3c43413a89a866e1828b25225060286302e56d9c2a3d290f95c0e499639e9e49339f6b8220f3cf020cb5fefc22a170197da38f68a21ea7a1e78601a3a7bed319e830e3a00cbb2181cec47d375128914c94402c334311349544545d3751445ad1994aeebe0380e4ed53dc5761c6ccb2297cfd3dedec1d9677f9ca3bb36f1e0438fb07acd661cd74596a59d4612d48413b82ea69160cfa9edcc39707f5ccfa65cb250149d542a4d3291c24c26318d04baa157739f0a418e91bd085c37fabdad7d23a1aa1a6ded1db4b6b6a169fa3613047854454534e61f7e0053264fa45c2e6195cb24124932990ca9740309338191486054955665594156764e38872c2b24cc244dcdada492692445defe7885183162c4881123468c1831181e7b1f76e7df5192a0de497e309381aaaa1846e580adb2bf7787790f8ce4a510369e4753fe1fa98edbeaf13b1ac2e91645d840f0354551304d13d3345155155555b12c0b55556b3416447b8d4602d433f8b7951c0842fdc7980c5b8dd7969606f6db672a932775e2213138388861983435b7540cd8549a642a89a12750540535648c0b384ed528776c2cab8c55b628db650a853cb22473f6c73f4e4fcfdbbcfccaab3cf1e42272f932b2bc55f0ce765c4c4365cfdd27f281c366635b65f2f93c8661d0dad64ea6a19154324d229940d78daa215da98b24070512b7bab308a2c0b62dcae55225bfa66561952dc68debe44b5ffc1796bef4120f3cf0305ddd7da8aa569d283b3a19aac6bb049327b4337fdea1a8aa44215fc04c24686d6ba2a1214b2a93a9a435d10d1445455614644906a912eeb1fdfdebfa6da1e93ae9741ac334ab03dfdba645d3b66df6d87d370e3a703ac9a449a9582495ced0d898addc433a8d6926d074bd221a59ad7f2504657befc11bd6a09aaea1a92a9222ed50dbc4881123468c183162c4881195deb0dea9fa58b2168c256da208590886140b1d82f0e9be200aea9d6a8f964121f8992089210cf4d1dceec577c31a02516d1695fd204cb688eb0609908a27f8d636a8d877328aa2d48426d42305eaf543bdf7461b07ff90048138d9962428166cda5a4c4e38763ebaae548c71dda4bdad9d86c626d2990c8691c0300c3f46a662b65644ea7ce182eaab2266dcf35c1cdba16c5bd85619ab5cc6b26d4ae532d96c331f3eee18f6d9672faebbee468a2515d350b16c9b49133bf8c021fb93c924c8e586506485e6e6569a9a9a2be4402abdd5fd3ec02049c2220f7574d571a55245cfc3b62d2cdbc2b66c6cc7c2b16df2850207cd9ecda4dd26b2f2f537b9f917b7d1dcdc8ceb6e1f49e0c7ebbbe0ba12f3e71ec0a4891d5856110983d6b68ecafd641a49a652e8ba5159247cbd819d63f80a4d06e1c223cb8adf4741bd8611efc18352a9c401b3a6b3ffcc6995173c89f68e7134b7b4d1d0d0886124d075ad4a0ad4c615795186fe76df8f5890a4680221468c183162c4881123468c6d2408a2bc06a28ce9b0f74094813ad6d3f7603683e04fb0fcca41a7eb0bfa05eb564f98308aa48832d6c3f7190c6108c7fed7d33608870cd4cbb6107c2f98b23198cd21f877d0b3222abde18e1041e1f495b10781df199508ff7cc162fe07f665e6bed3191c1ac2f3349a9b5b68696927dbd48c6926d0751db99a86400aa9474a110d2f4e752564e4aaebbfe725b16d0bdbb62ba7f7b6452e37445b6b2b575df95deebdf73e1e796c312d4d26679cf661366fde44215f209dc9d0d2da4e73732b994c03ba6ef8a4c058e265b6beb7356da0ae2868ba81eb38b8ae4bd92a61952d8ac5220d0d8d1c7ac8c14c9c30912baffa116632b55d067b254c021cc7e29cb34e42a232b91b1ab2b4b6b5d3dcdc56253a2afa0923e52addc94ba04fe48c3c812ad9263c0fca96c5d1471ec66e13db715d974cba81b68e713437b7924aa551d50ae3c728395777145b172d69eb3d4872fc548b1123468c183162c488b1c32441d8f08dfa3b6c5446851a449106f58ccf283b2668100b822048180cb3bd429f0f962b4edfc37bf428cf83a8d7c227f7e1f283f71df40c705dd737f0c33a0782ec88220704826104515911a2da2c2ce258cf736024bd82d1c687ba6b0e7e2a627c5535fd533f328fc9bb75d23f30403a9526dbdc424b4b1b8d8d590cc340ae134630ba415e6b2d4b80262b68ba8ba6e958b685552e61db369224f1b18f9d019ecb3efb4ca3bb7b33b2ac90cd36d1d6de4153530bc9aa212a06ccce5804244942a996a7a99a4f5c00ecb5d79e9c74d2b13cfcc85315aa63cc97f4f03c19cf73d13485b987ce41d7551cc7a6b1b189f6f6f134b7b490482491aafa0222efe73b4b0849553d828a9e818733e23525c9ad665cf03860e634769b380ef0686a6aa1ad7d1c2d2dad2412a98a3749201ee89dbc0f914ea646f322468c183162c4881123468c1db40bc62abc572fcde05852ed4519c2e19083b0912e8ced20511065f046d52d4c32d4fb7cd4eb51610be1f080a0f16fdbf6b07b139f091202c1bad5ab479850186bc6852802a6dedf515e1123113abb2c41b0d5655cc2726c0ede7f1f769f32918181019a9a9a696befa0a5a58d542a5371470f29cf8f991018b1e314744341ad6a18d8b685e33ae4860639e490d96cdedc85aeeb64b3cd748ceb249b6dc6344de4aac265bdcedb5ea31940d575145745a9d6c7033cd7e3f4534fa6b7b79f556b37f176f7e66a8ac6d1eeb1420e789ecbb11f9acbb88e164aa532adad1d748c1b4f53532b866900b26fe80a518eb1288e8ec4428e3e7902ee39157183fae30409dbb6993a75370e9e3d0bc7b56968cc327efc449aaa9e2552206e29282ee2bd03a2814121972ae7b4432e463162c4881123468c1831628c66288f6458d6f31c184bcac3289b24f8bde077c304413d6338eab43eeab5e075c2827f410f81a0f74130556114d911a53110959e311c361075f21f7e3f8a50d991fedd5e91c25d328b81486368db16b3f7df9bfd674d279fcf936d6c627ce76eb4b4b4a29b093fd5c6486210dbd349b59dab21c9957c9fb66d519072489242b6a999742a5d896d6f6cc2308c5107c658d43a474bfde1c93272d5dd5f08f8e9aac9e73f77011bbbfaf9f677ae189b68a1e4a2ca3207ec3f8396a646acb2456b5b079de327d0d8d4ec6b0d440dd4ed550b0d328fa3973172588620911cdb615c7b2b1f9a7f08aeeb90cd36d131ae9396965634dd40a9c60e855310be53e440f0f74a460729783b3162c4881123468c183162ec30511065fc46bd3792313992eb7ebd3246221b462208ea85428c54ffa0212f0401c3c481ebbad8b63d8cf0081311e1308120d1112655c4f583fa02db921a725bbd09467b6f2cd7dda53d08c4fd3a8ecbf8f606e61d7e08fdfdfda453693ac64fa0a5ad1dd34cd4b0483b729a3d160876499365320d8d28aa8ae7b92492291289644dcac2d172888e46588c3479830c8a1a8a83311329f6d9ab99cf7cf6027e76e34d7eda9191dad84c1a1c7cd0fe140a39329946c68deba4a9b91555d37daf817af5dc1686ac5e7ed6e0440cbb2dc9b252b1adbdfa2492eb79a8aacce11f9c8de7b9245369da3bc6d3dcdc866e98c3bc04a2844382ece0ce86a2c85bd327ca52cc11c4881123468c183162c4d82e044fc6a35cfca30cdc282f82910cfd301110e55a1fde3747091506c50a85213e9a169b389d17fb77d7755155956432492291f005e8c5fbe2c7711c2caba2d1562c162997cb919918c2de1241af00d1a6e1f0f0308120fe1e7e28286d93ad39d670811db16dd55d6bf083e37a98a6c2d9679f456f4f37c96492f671e3696deba8c6938f2dd03e4a5932d8b98ee38c3996474cc4642a4d229114a90822c534a2beaf282a8a22a3c80a4a552c4f96255c57d4c3c1b26c1cc71ec67ed563fac4ef4a553cd0f55c0effc021fce94f8fb3ea8dd7eb7b11481e1e0a279d70248e5d26954ad3dada46b6b9b992fa2fe284bd6622802f58a854c319b6f540be9eed2f4920cb6a551451c673ea6b05b88ecbbefb4ea3ad258b2ccbb4b4b4d1dcdc8a9948f8f50a2f965bfba3b2c0a8aa484f52c93481b773b409245941d3348c8419990335468c183162c4881123468ced3104a34882918882a8b0829152ee858dff91ea335af9633176c39f9765994422413a9d26954ad5a4548cfaaeebba2493494aa512f97c9e7c3e4fa954f2c38aeb1125e14c0ce1fa0a3b32a863301632201c721c45bcec2c6fe67ae11bbb9c07812c4b9c72e2710c0df6639a262dadedb4b58d23954ad7b87984ffdfcae254dc500cc3405134745d43d5341445c1711c1cdba65cb6701d9b52b9886dd938ae3326f717455190547544033a3878645946d70d0cc324914c923013689a8eaa56ea633b36b665512a152914f2148b05cae5129665d5100551466ed4f53bdadbf8c4d96772c5f7ae8a30c12b590e4a259bb91fd88ff1e3da191acad1d4d442734b3ba691f0077214e921cb158243d70d34ddc04c986881cc003b6b00c88a42c24ca06a1ae55231728c009886ceb43da7009069ccd2dcd24a2299f2178261c4862421570d77d34c904ca649241395fbd134a49d282a285545165545c171dd6ab8418c183162c4881123468c18db6f0c8e1442103c150f1ef88d64a88fc5cd7d24ef83b0a11e255428488d918c6bf1bbaeeba4d369d2e94a1635c77128954ac3ec131176200e2c1545f1ed3fc330181a1a6268688872b95c93fa317c5f411d02216018acb3283f7c483b9ad777bdf7b7951c1829bca09ef7c12e45105442ea3d2676b6926d4c01d0986da6bdbd420e8853e1a85374d1799aa69148a448a652a4d3190c3d81a6ab284a25b380e3544ee91ddba1542e912f0c51c8e5c9e77394cb256cc7f6edea289648746cd06d261cabbf35564627994cd2d090259d692491a8a4625494ad71f1c263c0b61d4aa53c854281dcd010f9fc108542be4268543d1d464a2fe2791eb224333838c8a187cee1c8238fe09147fe84aa064513253c5c9ab219664cdf8b5c2e4f436323cd2dad245329d408e2435c435554cc4482543a4326d3402291c230741445abd661678e846a1bbb0eae6387269be022640e3c705f3ac7b5a16a3a4d4d2d64d20dbe3745f83ec4c2914ca669cc66c9641a4926d3685a45f4b1423c55aebdf3c6734504b254b22bd797639220468c183162c4881123c68e910461e33f2cf2574fe53f4c2e4419fde1c3b57a5e06e1bd7630b4202a1bc168a9156559c6300c52a914c96412c771c8e572148b452ccbf23f2374080419609aa6bfcf571405c3305055155dd7515595c1c1410a8582df6e6a559f2c485844e9ad056dcea037f968a11b5164c1b618fddb42068c4420ed12048187848407b2c4de7b4d2161264082e6e656d2e90c6a9521aad7e88aa2609a491a1a1b2b06793a8dae57dcbbb77ed7c5b11d3ccfc5b22cb45285614a982974c36070608072a988edd8a3768212ca56103e71370c934c4323cdcd2d64320d1846a2e281a0caf4f5f56395cbd88e83552aa3e91a9aa6914c66fc7084caa0d6c8e773585679d4d89d60bd3cd7e1a823e6f1a73ffd6998616ddb2e7b4c9d4c2261a0c80a8d0d4d245369745d8f8cd1af94a9924e67c8669bc834647db2465115144566682847a950dae98b9fedd8e40b798a8562a0df2bf7a21b3233f7dd0b5951c8343454d25d9ac3ddf9c524d6548d54ba81e6e616b24d4d7e7f1886c1a6ae4dbcdddd4d6f6f2fc5429e9dc976b82eb8ae8d6118bcf9e6eaf8a91623468c183162c488116307f7976ecd9e7da4d082d14effeb19abf5bc0e46ca5e20c801f1ff588c5dcff35055d5270784513f303040b158f40f4a859d238c7c4dd3d0759d4422e113059583d8ca697f2291f03fd3d3d3432e971ba68b20488728b1c7f0df511e193b234ca09e006254dfd6230aa2fa70d7f020f03c5ccf23691a1c76e8a1f4f6f6d2dcdc4ab6a90523244a1836fe5455259d6ea8b8cab7b6621a268aa25228e479fd8dd5fcf1a13ff2f8134f2022df4dc360dedcb91c7df451b4b6b592482490e50a33941b1a249fcffb27fb6331ca831d5661bf4cb2d916da3bb67a3e94cb65de78e375aebbfe065efedbcbc8928c2429148bf9aaa0a0c461871eca45177d916422412295f2cb1e1a1a1cb13e510afd279cf061aefed1b50c0d0efaa7e39e07aa5231acf13cd299069a9a5b2aa900438b8cb8174dd568a8baef67b32d689a0648f46ce9e1f9bf3ecf6f7ef31bd6ac594ba954dee943a252258f72d9c2d46b0999a6860c09d344d32bf513e12751ec9daaa8641a1a686f1f4f43631655d5f03c9775ebd6f1f39fdfc2534f3fc3dbddddf4f50f60d9ee3b721f1ea0a90aa6a1c54fb5183162c4881123468c18db69320d57e91f2d14398a1ca86768d6f33818299ca01e493052dabee0750cc3209148e0ba2ebdbdbd0c0e0efae48040d093bca223562109cae5328944c2bfae5ed553139ee50d0d0d3e2130343484e33891768fb0a382044c30b45d6811d4136b1ca9af460b2b88eabf286f8528d22098ee31e84db2cb8418942c97534f3c8c42a1402299a2b1a99944d56da4be2640851c686befa89e222791248fc71f7f8c471e7d8ceb7efa531221d13acf8347fffc18dfbcfc3b7cfef39fe3f8e38e61ef7df642d70cdc848be741b198c7b2acbaaefd510340511474cda031db4c6b5b1bc9640a2458bb762dff73e79ddc70c3cf28958abe6b4bb89357ad5ac5430ffd917ffdca9739fcf00f904a24b16d03dbb129158bd8b6154912f86d8360d754f2f91ce79ff729aebefa87a4d369bf8ee33adac8a453e8ba4e2693219148568dfee1e5a9aa46baa18196d676b2d9662449a2582af2a73f3dca4faebb81e79f5b423299ac4ea6774e84afd2cf724d5b8fef1c879930308d24e9d4560f939ac92781222b2492299a9adb68cc36fb64c9237ffa333fbaf67a5e787e09a66156891d1dd38c1f3c3162c4881123468c1831de9be480888baf67708f950c88fa5cd8a81d49ec306cb08ab0e87a5e04c1ba078d5e71f26f59164343436cd9b2c50f0910190b2449c2b6ed1a2d015555b12c0bcbb2b06d1bc771b06d1bd33431ab1b7a4124343434f8d90e8686866a4292a30882b08de6ebd805c2be4712951f89a8d9519b68a4d78224c12e431058c51cfb4c9f4e219fa7b9a5854c35b4203c017ce34f5248a5d2b4b4b591cdb660264c1cdbe65b977f8785f7dcc396de3e9a9a9aea5cad52ee7fddb680fbee7b90b3ce3a837ff997cf60db168661f827f641e66ab40e9365957426435b5b3b994c23b66db37963179ffecce779edb5579124304da36e19aaaa303038c80faebe96c9932672d38dd7a1282aaaaa622b0aaeeb8c2a2422ea9bcbe739f1840ff39bdffc8eeeeecd48524585739f7df6249b6d44926452a90cba610c67b0f050658544224973731bd96c13922ce1da36dfbff26a6effefdbb16d8b6c36fb775e142ba7f1965562fee18722cb2aa9740633911c263e22a0eb068d4d599a9a2a04473a9de487d75ccb4d3fbf9981fe7ed2554f8d185bfbdfb66d7fb10dba7145693bc488111e3f85426198c8cfce2a5bc4158ef49952a944b95cf6d7444551482412effb4c22626e168b45df6532c6fb7ccf6359bef096e779a452a9772cedeef68eb772b9eca71a13a18831762e1cc7a15028f87f8b38ece0c1d6ce84ebba148b453f563bc6fb8f2010fd18dcfb8e2456578f301006fb686444941d16a57520eca6b00781a86f54fac58a98bb8ee7796cd9b2858181010a85829faa50e80a044ffcc5ef8ee3f8867bf8dae29a9aa6f93fd96c16dbb6b16dbb469320683f08ef82a0c65d78be868517475a47c3e1dbf54241c2c67dd8f80feb4c843d1dc2906579d720084aa532677cf423388e83aeeb55e3d5acfbb0942409c34cd0d898f55ddffbfafa38f1c49359bb761d8a22a3eba3378da62af4f76fe1965b7ec1ddf7dccb7ffef2e728aa8aaa6a58c9a24fef0000200049444154963de6f812599649261364b32da4d382a52a73caa9a7d3d3d33be6d48c8a22532e1558b1e2552efef2bf72cdd5df47966414451d535a46afeadea31b068e6df3c973cfe5aaab7e8069e8e8bac19449139124093361924c266b269d5f074941d574320d0d55b71c09c3d0f8ee0f7ec4adb7fe9284a98ff97e76ee86052ccb65c68c7dc8366628952d4cd3ac9bfa44533592c9148d0d4d689a8ea6a9fcdffffb337e7addf538b68da6a9f193a6660e5652c34c9c3891993367d2d6d646b15864d5aa552c5bb60cd775314d33de20c6a88b62b1c851471d456b6b2b8661ec34424918fecf3efb2cbdbdbd75098842a1c0ac59b3d87befbdfd38c4dede5e1e79e4117f13f17e9e9fcdcdcd7ce4231f61c58a15ac5ab52a4ea1fa3edfe4efbbefbe4c9f3eddcfedfd873ffcc12767df4db8aecbc0c000bbefbe3b3367ce24954ab176ed5a962e5dba359b533cee765a5b373737337ffe7c9ff41b1818e0b9e79ea3afaf6fa793f28ee390482438e6986358bf7e3dcb962d1b91748df1de2508c2bf4791044112a09e27c06873b95eb9e1f2a3bc0782764b94fd2284065dd7259fcfd3dfdf4f2e97c3b6ed9a70053146854d28bc0a1cc7419665df73c0b6ed61c6bb30e035ad92d6bca9a909dbb6d9b06103b95cce0f43087a04040dfb7048812833cafb6024b2662c6d5c2fcc40d899517d16e54922f0be9fd995f86c89d6e6462424cc6425059da1eba88a5ac7905648a652343436611a269ee7f2b9cf7d81556faec5d0956dec1800898d1bbbb8e2caabb9e2dbdff08d753c65d48ef6f02a2283a934a974ba9a665162defce3e87ebb074ddd76165851645e7ae96f7cf56bdfe007dfff0e22b5822cc9237af38b9a2ab282accb189a4cc2d4f13cd03495f1e3dbf15c974422856654c3374205ca8a423a9d21d3d088ae9b241206175e7831b7fdfa0eb28d29decd4364c771983279129e078669622692d50c01e1369650349d54ba12462118f3071f7e0cc7b6e2cd4d68a1cf66b37cf4a31fe5c20b2f64ead4a9be62ac605ff3f93cbffffdef59b06001ab56ada2582cc60df77746229160e2c40ac1b766cd1abf7fde4bc8e7f37cfffbdf67ca9429efc8dcbfedb6dbb8fefaebe9ebeb1b36871dc7e1a28b2ee2fbdfff3ef97cde1fbbf7df7f3fb95c8ec71f7ffc7d7bea6edb36471e7924bff9cd6f501485c1c141aebdf65a7ef7bbdfd1d7d7174f8ef7211cc7e1cc33cfe48b5ffc228542015555993c7932d75d77dd98431bdf096355d3343ef8c10f72cd35d7307dfa74f2f94ab8a5a2280c0c0c70d96597f1c8238f303434143f477702e977c82187f0e73fff997c3eef9f982e5bb68cabaeba8a458b16d5dd7fcab2cc6ebbed86a669e47239bababa467d1e789ec7b469d3f8ed6f7fcba4499328168bdc72cb2ddc7cf3cdbcfdf6db7187bccff66d63d11e18ab98dd4846fc687f073316881fe1ee2fc883b0a782a8b370f3b72c8b7c3e4f2e97a3542ad51010a2cc203920d62b216e28c802f15a98b808860998a6494b4b0bf97cdedfeb8633d489bf65591e965631784f517d12ee872852a45e4840303bc558099c7aebc3aee141e07934346418dfd989a22a241229cc84895c65a92359114d279d4a93482450558505bfbe83679ffd2ba6aeb0bdf6abaa483cb7e479eebbef418e38621e9eeb095b93910a95251955d548265255013c876b7f723d9b36756d1739e0d74755786de59b3cbb6409fbcf9a5991c41fc3a2e1792e92048aacd2d4942593696060a01f0917cff5300c13d330d154751839509940959486a691c4f35c7a7a7b59bcf8af64d209de5d0f730fd773e9686bc1932a0693aee915d244103d9edf2918ba4e3299429665cae51237df722b7f7dee2ff1a6268062b1c8e9a79fced7bef635a64f9f4e5757172b56ac885ce84e3ffd748e3ffe787efffbdff3dffffddfac5ebd7acc429e31767c2379f9e59773c925970070e38d3772e5955792cbe586b9bfbddb9065998d1b37eef471a1eb3a53a74e8d34f23dcf63d6ac595c7cf1c5ac5bb78e52a9e4b7cba64d9b78f9e597dfd72ef9e9749afff88ffff063335555e5fcf3cf67fdfaf53cf4d043ef39a228c6d8899feeee6e72b91c00871c72889fcaebdd3038128904fffeefffce97bffc65366fdecc8a152b6a3ea3691a1ffff8c779eaa9a7181a1a8a3b7007502e9799346912bffffdefd9b871a34ff4e9baceca952bd9b061435d72c0b22c8e38e208eebaeb2e5455e5f5d75fe70b5ff8024b972e1d9500faec673fcba4499378e38d3700f8d8c73ec6a64d9bf8f5af7fed87bbc4787f100461a33318171f749b0f7a0f8c257b41d0580d871708635d9ce0870df2a017419020087bef89537f11ca64db3683838335071f61ed82e07d86cb0b7a1204f7af61a144116e689a26ededed140a057a7a7a7c92204a7b21ec3d10f68aa847c48cc5b80f871e4485188c565e540604416eec12be419a56b919455630cd049aa6d78d2b178b68229944922496af58ce2f7ff92bc0c6db41b13ccb2ab3f00ff7b3d75e7bd01488b11f69132ec412855ec25b6fbdc55d0b1f4096777c839ccf0d72ff037f64af697ba2283212d2a8318a958102aaa2d2d252c9d2d0dfdf4f7b7b0bba61540882aa70e3b07224a99a322485a65558b905b7fd9a0d1bdf7ad70d11cf93705c87cece8eca383112687a35dca14a12e0676b50300c134dabc4356dd8b891071f7c10dbb25155e51ffee1224912fdfdfd5c74d1455c71c5150c0c0cb06ad52a9fbd0cba5c398e43b95c66cb962dc8b2cce9a79fce8c1933b8faeaab59b16245bca9f83b6d268f3df658de7aeb2d06070799376f1e8542e13d49ceac5ebd9a830e3aa84625386afc0d0d0dd5ac292224a0fe33428b7c5f9665f2f93c575f7d35a669faa489a228dc7befbddc7aebade4f3f9f7f54630954a619aa62face4791ee9749a430f3d943ffde94f148bc598a87b9ff6ad38e991248972b9fcaef6e3a73ef5293ef5a94fb172e54adfa81079c6c5ef42313cc68ef5bb6118fcf8c73fc6755dfafbfb916599c6c6461e7bec316ebcf146d6ad5b57d7e8701c87830f3e98dede5e9f301c3f7e3ccf3ffffca87bd5cece4eb66cd9e2ef236ddb66eedcb92c5cb8305e47de47e3a79e9a7dd8a3202a23413d4d82288d80a0a11e76dd8fd21f087a0f844982b06783f86cb95ca6542ad5a4330cd64718bbc1ef08288a12e95d102438823f412da34c26e37b120c0c0cd4780bd4f30610a10f22ec21d836f5bc35eae944d413dfaff7fb681910c27fef120481e779b4b5b620cb128669fa9d2749d19b455555d0751d55d5701c87a54b5f64ddbaf53b65619324895797afe0cd3757a34ddba392473399449614eafaf64b12b22257078dc58b2fbe48df969e9dd236aaaab06cd972de5aff16e33bc7553a5c519164a55e55b6c6ab680aa954d237f43b3bc7631806ba61a2e94665d04af2b0eb55e2f5351cc72693c9f0e8138b792f3c333ccfa3ada5095dd7d174034dd32ba105a171a22815c246aba63929168b28b2424fef96f7dc69ebbb8562b1c8dcb973f9d6b7be455757578d5b95a669747575b172e54acae5329d9d9decbdf7defe026b5916d3a64de35ffff55fb9f8e28be34dc5df69ec373737532814fcb4a9ef4564b3593efde94f73e59557a269da881bdc8f7ce42335a7902fbef822dddddd754902455158b3664dcdbd4b92444f4f0f3ff9c94f38e4904378e38d37fcb1b864c9127efdeb5f8fc9f5f6bd4ee66ddcb891458b1671fcf1c7fbf7a2280a434343c384a262c4d81eb4b6b672fae9a793cbe5fcf1a4691a6bd7aee5af7ffdabfffa0b2fbcf0bed6f2782fc0755d3ef1894f70d86187d1d3d3832ccb98a6c992254bf8d18f7ec4dab56bb16dbbee9cf63c8f4c26e3877e8813d5b1ac0177dd7517871d7658cd67cbe5f2bbae7b1163dbc64fd8b00c1aa3618d827a067abde74d54d842143911240a04191024078261065186aca893c842100e5508d64394132410c40196205883da0451248a681f613c2b8a42369b65606080a1a1216cdb1ea6c7112c4b882506ef39d896f5c2334613290c0b1986ed94e0f5eb110251fda828ca2ea241503d1dd2340ddd30aaaafbd10d20cb32aaa623c9329665f3fa1b6fd23f304832b173d4585d4fa154b2181c1c40370c74c34055b5513d023ccfc5f35c8672396c77e73c40254962a07f903756ada2adad0539a0e6191e1fae3b5c0db3a9a98996e666d6ac5d4b4b73934fbea8aa8a2cc948b2346c202b8a5c1d88324b96fc95756bde78cf8c934a6e5365eb3dd4c406490163a2327e3cafe215e2b81e5b7ab710efa12b300c83f3ce3b8f52a9e4672bf03c8f952b5772f3cd37b364c9921a06b8a5a5852f7ef18bcc9f3f9fa6a6266459e6adb7de8a4f92fe8e04416b6bab2f4cf75e36661dc7e14b5ffa12994c26728321bc078ac5222b56acf0c7de5d77ddc5c2850b87a55d0d9304c10769a954e2a4934ee2a28b2ee2cd37dff43ff3c61b6f70fdf5d7b37af5ea5d22f386a6697cf39bdf249bcdb2df7efba1aa2a4b972ee589279ea849fb1423c6f6ae2f53a64c61d2a4497eb883e7792c58b080fff7fffe1ff97cde3720154519d1788d317a5b4f9d3a952f7de94be4f379df6879fdf5d7b9f6da6bd9b87123a55269446f2ad775696c6cdce6b54d9665eebdf75ef6da6b2f3ef2918fa0691adddddd2c5cb8f03deb9116a3be111f3414a3c20ba28cfa91321a84df0f7f2eaacc602ac3a0406130f4202a5420585f41248c743df1b9b0e782f89e38fd0fea10f846b2aad6c4f70b4f284992482693343737b365cb16b66cd9e2676ca9d76ee1fb0bde673d57ffa8fe9365b986b409fe1d95b5204c2244951df6485055751720085c97dd769b54750937aa46e0f0f082ad8db5d50dc4b24abcfaeaca9dee366edb168e63e3b995cdaa70ed8f5aec2baf57e355dc4a9083b413f7a49e24b37ec3464aa562550051ab715bd9ba79ae8d31929048a55234363622495b4f8755b5eaaa1b11aa2055332654ca87d5abd7d0ddddf5de614e3d0f55dc83aa22c9726446054992902505cfabb0eabdbd7d6cd9324053533adea0781e6d6d6d1c70c001e472397f117de69967f8e10f7fc8faf5eb6b163c4992d8bc79335ffbdad738f2c82339e59453686c6ce48e3bee78d75d62ff5120d24ddab6fdbe88a54fa552758d084122041f8ec2ed4f3c80eb8da960fa21d77599387122dffbdef7e8eded45922432990c2fbdf4123ffad18f22b534decf1b42cbb2b8e4924b38fae8a3c966b32c5bb68c952b578ef9e430468c91d0d2d242369b65707010499258bd7a350b172e646060a066aec6e4c08e3d7b354de3f2cb2f67d2a4496cd8b001c330e8eaeae2a69b6ee2d5575f259fcf8f9ade50922a7bbbedf1e4705d976baeb986679f7d96cece4ede78e30d5e7ef9e5774dfb22c6f68fa570284130ed5d3d65fbd1c8805abb66f87b610d02f15ef0e45f780f84e3f5836330682887098fa0485ff0b43e9891202c1218f42010eb95f01250027a7622b5a73860d4348d8686065a5a5a181a1a229fcfd7ec4bc4f5443d832448b88e51611f415b362ac420b89686e77dd033222ac5e36864c1ae41105455d453a9748524502ba7c451835b743a80eb3894cb25de5cf5068abc731f58922cae23f9a7f5f55d72e4ad2441b51ede4eac8e2c4bbcfdf6db58e5325275e208326024540413bd8a6e9f27268f36ec142e6ac0baae4bb95c66ddfaf5948a450ce3bd6394c8d5be91ebdc83183b8eebf8c2236fad5f8fa2c6a76c02ededed2493497f21edebebe3fefbef67d3a64dc35ca2009f715dbc7831afbcf20a8661d49c2a8d36bf1dc7a1542af91b1ab1488f355d963875b66ddbcf6b1f5c4c6ddbf6bd21a4aa8e469068dc9e35295867a194bb2d75864adabd52a984a6692412899a8776b95caec9f35baf6ce1c151af9ee1761a0bc4bd89f612d7df9969f3eab5fd481a2a61c5e0a8ef067f2f140a3cf6d863cc9a350bd77559b9722577de79a72fd6b5bdf7e2791e9665512e97fd3ccb220ffc48f5731cc70f9b304db3266ffcb6f479bd7befe9e9e1b7bffd2db22c934824eaa678ad6718883e17d70f9ea4ec48fb08f279341d899db9672897cbbe5baaa254c20ec77a2f922491cbe57c176dd3346b5c4b83f343ac7f627ebc9b28168b148b45545525994cd6d4273ca7c57a355a9d83e25d22a65684168813b59136a251e589ac4162ed0c6ecac73ad64aa5929fdd2178af62ec09a573d1f751655b96e5e73a0fcec7701923f5afe779d8b64db158f4af679ae676ad95626fb564c9123a3a3a28954a747777b360c1029e7bee390a85c298faaba2139518555dbe5efd060606b8e79e7b7cb136a13131d67544cc3d61288af6dfd6f921d624414e8835364ea3c998e66c94311f65d8874fb6b7d57320480e8455fc83ff07090271e21f163a0c1bc9614f84e06b62fd0d971f2c334c2e88d7c55ed1b2ac9ab1592e9729168b356b86699ab4b6b6d2dbdbeb676d11f35ba4470e1afc515e12e1360b1339619220483c8ce43910260a827fd7db5389df354ddb35440a65596c5674dfc8aebfc10cc494b8ec3477fe9a6b5055fe5402ae1d751e1ea25f7c42819dbfb0499e8757956094aaff8e148251f951aada095bef49962591d77118331576f9515595e6a626e0bde6a25b4d3fa2c891c6c8d609e8e0ba0e966db1efbe3370ec58e95b406cb8c4823f3838c8aa55ab463d1d5214c5dfa48ef541964c26993c79321ffce007993c7932b22cb376ed5a9e7efa69d6af5fcfe0e0e0a86457a954e2dffeeddf98387122ebd6ade39e7bee61d3a64d388e836118ecbdf7decc9b378fc99327532e9779e9a59758bc78718558dbc6931149924824124c9a3489f9f3e73369d2245cd765eddab53cf5d453bcf5d65b7e3cee68e57cf2939f64debc79bcf2ca2bfcf6b7bf65707010d7757d81b9830f3e18d334d9b871234f3ef9246fbdf516434343fec64b55552ccb62b7dd761b56b6699a350f6cb1211e2ddede300cdadbdb39eaa8a3983a752a9665b166cd1a9e7aea29bababa28140aef936746e5417df7dd77333030406b6b2bf97c9ef6f6765a5b5be9ebebdbe610184104a5522966cc98c161871d465b5b1bddddddac58b182975e7a89b7df7e9b52a9346c9eb8aecbf8f1e3f9ea57bf4aa954e2a1871e62c99225fea6bfa1a181d9b3673367ce1c1a1b1b59bf7e3d4f3cf1041b376ef445c346da484f993285cf7ef6b398a68924492c5cb890bffef5afa38a840a83a2b9b999b973e7b2cf3efba0eb3a1b366ce0b9e79e63d5aa55f4f7f78f49a721d83eb367cf66f6ecd9b4b4b490cbe578e5955758b264093d3d3defa870a92ccb2493490e3cf0400e3ae8205a5a5ad8b2650b8b172f66d9b2650c0c0c8c4a5c964a253efbd9cf3273e64c366ddac4c2850b59b76e1d8ee3a0699a3ff7f7dc734fcae5322b56ace0e9a79f66cb962def5a589510883de1841378e38d37b8edb6db181818c0b22c1289041d1d1d1c79e491ecb1c71e140a05962f5fce5ffef217b66cd952770d14a17ac2800e8e8172b95c49279d4cd67ca75c2e8f38564cd3a4a3a3830f7de843ecbefbeebe86c65ffef21756ad5ae5af6fa3e1c31ffe30679c71066fbcf1060b162ca0afaf0fc77148a552ecb7df7ecc9f3f9f743a4d5757178b172fe6f5d75ff7d7574198cc9e3d9b33cf3c135996b9fffefb59b264099665914ea799356b16f3e7cfc7300c962f5fce534f3dc5e6cd9b6b4ee5c53a3b73e64ce6cd9b47269361d3a64dfceffffe2f9b366dda66f1533117efbdf75ef2f93cd3a64dc3b22ca64e9dca860d1b58bf7e3d9665d50dcd120498a228a4d3b5de909aa6f9c485d8cb45c580373535f195af7c8571e3c6a1691a0f3df4108f3df6d8a8cf7451f7868606e6cc99c301071c40269361c3860dbcf8e28bbcfaeaab6cd9b2654c5e0d42a4b1a1a181238f3c92e9d3a723cb32ebd7afe7e9a79f66cd9a353e211383110f074612af0b3f53460a35882215a28cdea8d084203110d41f08c7e987bd06c56b62cc8a755bd821e10c09c172c25e88e27baaaa0eab872082056110fc814ae8723a9da6b5b5d5d72310fbaf60085fb05e6171c2f041eb58f687c1b53dd8e641e15af1d9b03741d88b408ab0eb76090f82e103b8ae1c60e0b4defbbb3c90b71ae42377720d992149ef58f564792cccaaf07ef06aaa2149328aaa56dcf36505ea844cb8ae836d5bd8b682913030cc0478efbe712de12103ba5ed1855014154556fcb0132f5447d7a9c636590e1e2ee9b451195b3131cdd0d010a552095dd77dd7b4b038cb8ecc61c77198366d1aa79d761ae79d771ebbedb61b8ee3f8f9e9c509c8f2e5cbb9fbeebbb9efbefb78edb5d7223717e2b4f0bcf3cea3a5a5054dd3d86bafbdf8e52f7fc921871cc205175cc08c1933b06ddb8fe94ca7d3140a05eebbef3eeebcf34e9e79e6995137409665b1f7de7b73d65967f1894f7c82f1e3c7d79429eafcf2cb2f73d75d77f1c0030fb072e5ca11d78fe38f3f9ed34f3f9d62b1c8ac59b3b8f1c61b39f9e493b9f0c20bc964323e5b2dca7efef9e7b9f2ca2b79e8a18738fffcf3f9dce73e477f7f3fe974da77a31704c39d77de398cdc5bb06001f7dd77dfb01464e57299091326f0d18f7e94f3ce3bcf179d14448738417ae69967b8ebaebb78f0c107e9e9e9794f3e1f1cc761e6cc99fcf33fff33e79c730ecdcdcdbe02b224495c7ae9a50c0e0ef2c73ffe91dffdee7763ea7bd17e871e7a28e79e7b2ea79f7e3a89448252a9e4a74c4c26930c0d0df1d8638fb170e1421e7bec31fafbfb6bc492dadbdbb9e0820bd8b2650b279f7c32975d7619dddddd9c71c6197cfce31f27994c5228142897cb7e9fbff0c20b2c58b080071e7880eeeeeec8d4a1229ce2339ff90cf97c1e5555e9e8e8e0d24b2fad6b8c5b9645369be584134ee0820b2ee0b0c30e032097cb61db369aa6914c2659b3660df7df7f3f0b172ee4c5175ff43776c13a08c3f9f0c30fe7139ff804a79e7a2abaaefbf7225c9e2dcbe289279ee0ce3befe4d1471fa5b7b777a78479884de4a1871ecad9679fcd3ffdd33f619a26c5629172b98c2ccba45229366fdecc1ffef007eebcf34e5e78e185ba86b165597cec631f63ce9c39789ec78c1933b8e1861b983e7d3ae79f7f3e871e7a28aeebfa73df344d2ccbe2de7befe5f6db6f67f1e2c57f77a2409224e6ce9dcb39e79c43b15864ce9c39fce0073f60fffdf7e7d39ffe34071e78209ee7f9c2958944827c3ecf3df7dcc3edb7dfcef3cf3f5f439ce8bacea5975eca89279e48a9542293c9d0dddded8fe5238e3882c30e3b6c9821f2b39ffd8c071e78a066dc95cb65a64c99c219679cc1b9e79ecb1e7bece1af2f22a637954af1faebaf73f7dd77f3873ffc8165cb96d535262549e2e0830ff6ef75fefcf97ceb5bdf62eedcb95c72c925747474502814fcd3c16432c96bafbdc695575ec91ffff8474aa512aeeb3263c60cce3fff7ccae532471c71043ffef18fe9ecece4f39fff3c13274ef4c76f2291a0b7b797db6fbf9d5ffdea57ac5bb78ed6d6563ef9c94ff22ffff22fb4b7b7fb6b81aaaa5c71c5153cf5d453dc7aebad3cf6d863a3120596653171e2444e3ffd74ce39e71cf6dd775f5cd7f5fbca300c3ccfe391471ee18e3bee1866b05b96c5befbeecb0f7ff843df2d7ab7dd76f3f52200befce52ff3c52f7ed15fcf4cd364d1a245dc72cb2dac5dbbd67f3d9bcd72eeb9e792cd66715d97fdf6db8f152b56d4d5b711f53beaa8a3f8cc673ec3d1471f0de07b102a8a422a9562d3a64d3cfcf0c3dc79e79dbe3744d8782b954a74747470e28927f2c94f7e92830f3ed85f93c41a23c21d7ffdeb5ff3f0c30ffb212f31869303611d82e06b41e335ea9932d2497e54d9510671948741f827ca053f98e2508404045df7c3a7ec6177fea08bbff83f7c4818ae433815a3c89e20eaa0eb3a6d6d6df4f7f7fbcfc8e0897fb0bda2b416a2ae534f2360a47e081303418feff07bf58882e09e5edd5506bceb3abed05fc526977c6f81ca67aabf570dc2caefef486daae2fe124832925cfd91e448e35210165b8503bd9d5f2f5fe78000a3a40c234a864d14ff8e40d65474cda88429481517fd6099fee0af4ec052a9c401b3663269d254d6ae79fddd27082489c1a11c8aac043218043c3742d90c1cc7c6716c5cd741c265f29429ac5bb70e458a430dbababad8b2650bededed4892445b5b1b73e7ce65e5ca95be41b423eed9279e7822dff8c6379832650a5bb66ca989070fbabb1986c1b9e79ecb51471dc5cd37dfcc1ffff8c748b12411622098e0030f3c905b6eb985f1e3c753281422e3cd254962debc79ecb7df7efcd77ffd17bffbddef181818882cbbafaf8ff3cf3f9f6f7ce31b343737d3d7d7175967a8e4a4bfe0820b38eeb8e3b8f9e69bb9efbefbea9e88e57239366ddac4c0c000b366cde2a69b6e62f7dd77a7b7b797f5ebd70f7bb08b93ad871e7a88cece4e66ce9cc9dab56b91a44a989170df966599c993270f63ad4f39e5149e7aeaa99a4dd5e0e020c71c730c3ffce10f993c7932b95c8e575f7db5c6ad4d94b3c71e7b70e9a59772f8e18773e59557b261c386f754cc71a150e0c20b2fe4924b2ea1a9a989dede5ebababa6a485ab1067ef0831f64fffdf7e7aebbeee2faebafafeb5d21e2fbbffef5af73c105179048247ca1b0f07dcbb2ccecd9b399316306f3e7cfe7faebaff73f0bf89b8e0d1b36a0691a975c7209ededed343636d2d5d5352ce387d8b05f72c925cc9831831ffff8c76cdebc397243e7388e2fa4a4ebfa3017f3f009f9d4a953b9fefaeb993d7b36e572d927e0c2e5aaaaca69a79dc6a1871ecadd77dfcdadb7de5a231ae5ba2ea9548aef7ef7bb9c7cf2c998a6c9faf5eb23db479224a64f9fced7bffe758e39e618aebaea2a366cd8b0435a25c2adfb9bdffc26679d7516a9548a8d1b3746664f511485134e3881430e39845ffdea57dc7efbed7549825c2ec7c68d1b711c87bdf7de9b1b6fbc91cece4e1cc7896c2b61a04f9f3e9d9b6eba89bbefbefbef4e12944a257a7a7ae8eeee66dab469fcec673f63d2a449e4f3f99a391d1caf471f7d343367cee4861b6ee0de7befad71cd9d34691293264da2bfbfdf2770c5f71389040d0d0dc3c6ca29a79cc2030f3ce097d1dfdfcfc9279fcc55575dc5840913181818a8bbbe689ac6d9679fcd11471cc182050bf89ffff99fba59702ccba2afaf8f8d1b37d2d9d9c94f7ef213f6dc734f06060658be7cf9b00db7699acc993387471e79a4a68ceeee6eff99f21ffff11f747676d2d3d3c38a152b86dddb59679dc5f8f1e3b9f5d65bf9fad7bfce073ff841de7efbedc8eb4d9d3a956f7ffbdbdc71c71ddc70c30dc34eeac5e773b91c279d7412975d76197beeb927838383917d254912071d741053a64c61f7dd7767c182053ed12b9e0d071d7490ff1c10a11302adadadc354ce4f3bed349e7aea29d6ac595343b6f5f6f6fae12822b4a41e5455e59a6baee1e4934f186038850000200049444154c6b22c56ae5c19f94c501485638e3986030f3c90071f7c905ffce217bed707407f7f3f471d7514dffef6b799356b16b95caeee9ab4e79e7bf2d5af7e9569d3a671f3cd37d3d7d717b302119e0051e104c1d3e891b21084c981a0d1194e71183ebd0f7b0ed40b018812290c120951a10fe1eb85898228c35f3c1f8321d2612f8270b9e235e1312dcb32994cc60f35181818f0c3d7829e0ef5889228e226ea843f58c6480286c1707a411244791144e91104bd29760982400ab8e607d5272b9d4acd0650febb1878e25a92af2f10ecd860274852c540ad0c8a7728c4c02f53f2c982681796eaa011ed152014366ed8e80b3c56a213e490b64285e8701d1bdbb2701d87d696165249ed3d71f22e49127dfd43a4d2e9408c9a541d1fb50c9ce779d88e53d99822615b65c675b4b376cd1af80757fcae8836f6f2e73fff9973ce39c73f0538fffcf399316306dffbdef77c23655b6246c5dcdd6fbffdb8f1c61bc9e7f3bcf5d65bfe26234c3a88453797cbd1dadaca37bef10dcae5328f3ffe3843434391c68f784dc4930ae350d3b4616237b66d93cbe5304d932f7ce10b6cdab489471f7d945c2e5753767f7f3f175e7821575d75153d3d3dbe0e83aaaac3e2a905fb6c59166d6d6dfc9ffff37fd0348d7beeb927d2ad39b8c82712091289845fbea873f0a169db369b376fc6300ceeb8e30e3efde94fd7e4120e6e0844fc6cf88110ac73a954e2c8238fe43ffff33fb16ddbbfb6883f0f871809b7ce3973e670e5955772c92597d0d7d7f79e481398cbe5b8e8a28bf8f6b7bf4d7777371b366cf0f526a24e0f846bff99679e492693e1bbdffd6ea42be0d0d010dff9ce77b8f8e28b7d32479cb0061fc2e27b6263fea10f7d085dd7f9d6b7bee59ff605997e80c6c6469f309065b926d637a8f5224912c71d771c5d5d5ddc72cb2d7e1d46da3c84459a826374e2c489fcf297bf64ca942935848310820c9229e2d4b9a1a1810b2eb800dbb6f9c52f7ee18f37718f679c71063d3d3df4f5f50d6bf770fb00cc9933879ffffce79c7beeb9be9bfbf690048ee370d9659771fef9e7d3d3d3e3937ce2fa6157d85c2e473299e4c20b2fc4b66d7ef7bbdf451214412569c3a88823f7f4f4d4cc8f703d72b91ca9548acf7ffef36cdcb891a79e7aeaefea061dcef59d4ea7d9bc79b3bf5e85fb4368626432192ebef862bababa58b468911f83bf68d1220e3becb0614adce27ea394c483c6642e97e3e4934fe657bffa154343436cdcb8b146293c7caa25ead3d4d4c497bef4252449e2aebbee62686868c4cdb22ccb8c1f3f9eeeee6effa439bc76cab2cc860d1b861142c13929cb32ebd6ad1b36bf85c190cbe5983d7b3637dd7413adadadac5fbfdeff6cf87a627d39ebacb3181c1ce4d65b6f8d2439ce3fff7caeb9e61a9fec885a7fc5b370686808d33439fdf4d359bd7a350f3ffcb07fbf8b172fe685175e60e2c489357a3e02b66dd73c83442ac4a0827b54bbd623d0c43ee191471ee1031ff8001b376eacd1f8899a7ba2fe1ffbd8c7705d97ebaebbceafd739e79cc34f7ffa530a85029b366df2fb4cac894163511055a79d761aab57afe6eebbef7e47c396deaf0441d4c974901c08ff1f14178c3266a38cdc7a1e03e1d082a83d4294ba7ff820272acd5f707f18ce8010be7e145922d648b1b70d120642e45d100741e35b84e2b5b5b50dd32288f242106b64143112d54fe13d61b88f823fc13083e0ff61922078281cd63bd825d21c029513fb40dc844f068416e5618af5efd4b3590ae6a8a4265e3b924e90e4bfcb295b459f411ae58477b8078304f4f70f202b728dd1172518e6ba2e966da12a2a0383fd9c7df63ff38d6f5e01d8eff2a228a1eb1a0fffefa37ce2ec33b1abf94fc37d226ec7b12d6c5946d32aa76dc71c7d0c8b172f899f2ed5cddf1d77dcc1b1c71e4b4b4b8bbfb0cc9d3b97a79f7e9afbefbf9f871e7a88175f7c914d9b360d9b8bf51e5a922471db6db7f986bf185b1b376ee499679e61f1e2c5f4f5f5619a26071c7000c71e7bac1f7ee0791e575c71059ffef4a7f9dbdffe366a2ca3d8f4974a25fef6b7bff1c4134fb066cd1a745d67fffdf7e7d8638f65dcb871388e83aaaa5c72c9252c5fbe9c37df7cd337985cd765ce9c395c7ae9a5fe69a288f55cb3660dcf3cf30ccf3fff3c7d7d7d7edcf331c71c43676727aeeb924c26f9d4a73ec5ead5ab79fef9e7478c05741cc73fd997248975ebd6b17af56afafafa686b6ba3b3b39372b9ccca952bf13c8ff5ebd7f3d18f7e94cf7ffef34c9830813df7dcd3df945a96c573cf3de78788886b6cdab4a9c635b5bdbd9dcb2ebb0ccbb2fcfed0759d37df7c93279f7c92bffce52f747575914aa538ecb0c338f5d453193f7ebc7faafa852f7c819ffef4a7357df96e6d88e6cc99c357bef215366fdeec33feaeebf2d24b2ff1e8a38fb274e95272b91c2d2d2d7cf8c31fe6e8a38fa6a1a1c177973ee59453b8fbeebb6bfa48b81e5f7cf1c5351b60499278f5d55779f2c92779e59557181818a0b9b999c30f3f9c79f3e6f99e37994c6644f22428cad9ddddcda2458b58b468113d3d3db4b5b571ecb1c772f4d147fb2112e79d771e8f3efa282fbef8e27693329aa671c10517b0c71e7bf86122aaaa92cbe558ba74294f3ef9246bd6acc1711c264d9ae49ffa09c1c4743a5d734a22c20a04d120cb32b66dfb73eeb5d75ea35c2ed3dadacafcf9f399376f1ec96412dbb6696a6ae2da6bafe5a28b2ef2c331b6b5dfe7ce9dcbe73ef739df3894659972b9cc92254b78f4d14759b66c19a5528909132670d24927317ffe7cffd9fd99cf7c86152b56f0c20b2f8cda9ec21bc0711c5e7df5551e7ffc71962f5f8e6ddb4c9f3e9dd34e3b8d2953a6502a95686e6ee69ffee99f58be7c399b376f7ed7c40bc506d6f33c962e5dcad34f3fed9f76cf983183d34e3b8d09132660db36a9548ab3cf3e9be5cb97fb27bb0b162c4096653ef0810fd0dedeced4a953fd13fd9e9e1ed6ad5b5733ef555565f5ead5feda3979f2642ebffc72fafbfbfdef097d8b679f7d96c58b17b379f36692c924fbecb30fc71f7f3c53a64cf1d7df2f7ce10bac5ab58a679f7d7644dd08d775fdb553d334366cd8c0aa55abe8e9e9a1a9a989891327a2aa2acb962d1bb11c610cdbb6cda38f3eca9ffffc677a7b7b993d7b361ffff8c749a552feb3aebbbbdb6fdbe79f7f9e871e7a8875ebd6d1d2d2c299679ec901071ce09777fcf1c7fb73218c030e388062b1e8afa142005218fc7d7d7db4b4b470eaa9a7b2fffefbfb59868e3bee385e7cf145d6af5fefbb0b9f74d2495c71c5154c9e3c9969d3a6f9fa1100afbdf6dab0d032d334fd93d06d1da30303035c7ef9e51c7df4d17e3a5ad1ef4b972ee5f1c71fe7cd37dfa45028d0d9d9c951471dc521871c422693f1d3c7059f4dfbefbf3fb95cce27ff755de7adb7de62e9d2a5be5e502693e1b8e38e63debc79bee173d65967b168d1a21a2f8818d17bafedf9de48e440d8508f32f6830671f07351658ea47f10dccb875dee474bd3187e3d68fc073d08a2421544489d58df32990cededed747777d78410465d332aaca05ebdc2de0451fd152605fe7f7b5f1e274575b5fd545557f5ded33d3bb3b12f8220ab2018214814714d5ca2d1a06f8c3146342e9f121383e22e6af07d35a24611497001252a1081b02fb2a880c00c200c33c00cccbef65abdd4f747cfbddcaaa9ee99811945acf3fb953833dd55b76edd7beb9ee79cf33c89fe3f11ff801e1071d664100802d1b61774594c795ea00f8b30d5f342d7bc98f99648bc40d3d791909db9230cbfa7bc00706c8a0a079e4762d9459ea7e9f6711e85f8e70f979e8893419ac496170eaf0b2e288a8248cb8b8f8ff1187fd14fd0a37b2e0e1f2ed19514fceea2270a388e87cf1f80d5666f79e1222159a3a22888290ac291f826ea965b6ec4d62f7760fddad53f7a1e025114515c5c8c7befbd17afbdf61a3232322823797d7d3da64c99822953a6a0acac0cbb76edc28a152b2858a0074e711c87fafa7a3cf7dc73e8d5ab17f6eddb074110c0f33cb66cd982bffffdef3878f0a00a655dbd7a35e6cf9f8f193366e0673ffb1925bf9a366d1aa64f9f8edadada3609132b2a2a3077ee5cac5cb992d67346a351fce73fffc1bbefbe8bb973e7a2a0a000e17018393939b8fefaebf1d24b2f5167411445fce217bf80dd6e477d7d3d5d88972d5b86f9f3e7ab3806a2d12856ad5a85f7de7b0f4f3ef924468d1a85582c868282024c9e3c19478e1c69d359202fbb458b1661d1a245282929a12fa8ecec6cd8ed76545757d3cf7ff3cd37983a752a6ebcf146bcfbeebb3874e810044180cfe7c36db7ddd62aca4998cbc94b7cdcb87118326408ed4b8ee3b062c50acc9d3b17df7efbad2ab2f0d5575fe13ffff90ffef18f7f202323038aa260fcf8f1f8fcf3cf4fcb61ed2cbbfdf6db2149121a1b1b6959c07befbd870f3ffc10353535f4857df0e0416cdab4099b366dc2e38f3f0e9bcd068bc58249932661cb962d3871e284eabc8f3cf20865e5266bfc82050bf0e1871fd28c0b32ae962c5982810307e2baebae435e5e1ee6cd9ba79b6aaf7d1f6cd8b001f3e6cda375d7245b64e1c285983d7b366eb8e106ea644d9d3a150f3cf0c0296dfa1445417a7a3a6ebae9265a17cdf33ccacbcbf1c61b6f60cd9a3534c21f8bc5b075eb562c5cb8103ffde94f71edb5d7a2a2a202efbcf30e055f42a11066cc9841099d48caf4fcf9f3f1ef7fff1bf5f5f5aa8de1bffffd6f8c1f3f1e3367ce44666626a2d1287af6ec892baeb8021f7ef861870843c9339e3e7d3ac2e130056f9a9b9bf1f6db6fe3b3cf3ea31c1ab1580cdf7efb2dd6ae5d8b5b6fbd15f7de7b2f388e83c3e1c035d75c83fdfbf7abeab513ad2775757578fffdf7f1d9679fa1aeae8eded7ba75ebb072e54acc9f3f1f1e8f07a1500863c68c41cf9e3d55f3f5bb3641105059598977df7d17ab56ada2f33c128960ddba75d8bc7933e6cc990387c3014551d0af5f3ff4efdf1fdbb76fa7e3e5b5d75ec3ebafbf8e1b6fbc116fbef9268a8b8b218a22b66ddb86279e78a2d5f88e4b259ba8635c5050809a9a1a1adc59b76e1de6cd9b87ddbb77abd27f57af5e8df7de7b0f8f3efa282ebffc72048341582c16fcfad7bfc6a1438770e2c48936d74e4110b074e952bcfffefb3870e00055b1484f4f474a4a0aeaebeb938250269309f5f5f5f8bffffb3ffcf7bfff452010402c16c3c68d1bd1dcdc8c071f7c505507cd711cde7fff7dcc9d3b97127946a351ac59b306afbffe3a860d1b065996919d9d8de1c387a3b8b8b815c9e1534f3d85acac2c0c1e3c183e9f0fcb962dc3e2c58b515c5c4cdf8784bb63fefcf9c8c9c941381cc6a851a390939343b318481f3cf0c003c8cecec6471f7d44c17d009833670e56ad5ad52a0a4bb208f4a299892c1289a05fbf7ef8fdef7f8fe2e2621a8d0c040278f5d557b17cf972ea3c298a82eddbb7e3e38f3fc6e8d1a371c30d3720168be19d77de5195513cf7dc73c8c9c9c1c891232989eb471f7d84bd7bf7aaa4ec56ad5a85575e7905e79f7f3e62b118b2b3b33172e448032068031848f6bed073d893f92b89b809f4b80612d5dfeb6508e801126cf681d6976219fef5be93a8148f8c614232488000f65fd26eb28690b5cd62b120252505e9e9e994e3465b6e91a87c82ed1f3d4041af3440fb7b160c20fb1112d46dabac401724382b463a7d000a782e9e36ae87c89cec1ca5cbc90ad90c02edc3d63e7895d3d495ce271521e012b6856d7f2ca6b438fd710583683406a1e5e5ae9f4204a6de278a682c0a8e077efef3abce089d5c9349c037dfec86a9c5f93cb900f1ad0e8043b405398c442268a8afc3a861031189f15dfc907e182f1687c381a2a222dc7cf3cdd8b8712365b4e6791e0d0d0d686c6c84c7e3c1a44993f0b7bffd0d6fbcf106fef0873fc0e572b51a6f914804393939b8fdf6db515c5c4c3773e5e5e578e1851770e8d02195362d21966a6a6ac2ac59b3505e5e4e5f06fdfbf7c7c8912393caa5f13c8fbaba3a3cf5d45358b66c19ddfc93d4564258f6d8638fc1ed76038813334e9a340919191974aea6a6a6e2a28b2ea22f018ee3b06ddb36cc9d3b17870f1f562dd844aaf0f8f1e378f1c517e9356559c615575c818c8c0c953670a2762f5bb60cf3e6cd436969298da44892849a9a1a1c3d7a54954ac94ac7b12f439ee7e17038e8df595947124123f5c2ecb3dabb772fe6ce9d8b43870eb55abf2c160b0e1d3a84b973e75209ccd4d4548c1a35aad3082c4f75ace6e5e561fcf8f1141ce0791e2b56acc0bffef52fd4d4d4a8de0f44526de3c68df8f2cb9319437dfaf441bf7efd548a0fe79d771e2ebcf042545656d267fcef7fff1bfffce73f515151a1d2529624094ea71325252578e9a597f0e8a38f62fffefdba9c19ec4b7ff1e2c598356b160a0b0b55c44c66b3194ea7132fbffc329c4e27dd58141414202727e79423ca975c7209ad4b07e2646273e7cec5ead5ab29bf08998366b31966b319ebd7afc7f4e9d3f1eaabafd27af4402080f1e3c763d8b061b42f0060d9b26558b46891ea59107932bbdd8ecd9b37e3cd37dfa4ef11499270e18517222323a343f722cb32468e1c8971e3c6a900aec58b1763f1e2c594b19e3c77525ef3c9279fd0b91b8bc570de79e7a1478f1e6dcecbc6c6463cfffcf3f8e0830f505757477f6f3299e07038505252822fbffc9266ed489284c18307d394d5efc38e1e3d8a59b36661c99225a8ababa3fb10b256151616522010889724f4eedd9baead845c92ac1dec7d10124d2285490e928eef70383065ca14ba5ef13c8fc2c242bcfdf6db282c2cd45d3bbd5e2f9e7cf2499ae91589443062c4080c1e3c58150d4f0486ac5ebd1a6fbdf5160e1c3840cbb44894fcd8b1634941209ee7e1f57af1fcf3cf5370809d8b3b76ec5045e00541405151113ef8e0035af243d6814824822fbef842e558f4eddb17168ba5559b9b9a9a70df7df761d6ac5998397326056158d0c362b1e0d8b163b4642e168b21232303050505aa2c31f20cb5bf03409f237b98cd662aabdad135f78a2bae80288a344b451004bcf4d24b58ba74291a1b1b55ed379bcdb0d96cd8b163071e7bec313cfffcf3282f2f57bd8b42a110eeb9e71e3cf3cc3378e69967f0eaabafa2b0b090660a90bef57abdd8b76f1f1db3369b0dbd7bf786c56231540d4e212b201168dd56343b1110a0776823ecda76242b876353f5596040cf47d13ae66cb6000b1a90b216022cb33f9383fc8ee52960793932323260b7db5b654524024cd8eb2702509201024465811ce47d46caa9121d7a80037b8db3a6a03a1e0d164052e8e311c89307f999e61c102e82ae5833b8d68488daf69083e3b4a4805dc14170321b80cafce9b685539129723c07a5a56f43a1108a8b0fab36fbdaf3c407a7294eee178d21168da2b1be1e974dbe04430616c01f905bc01bb665dfdda26d12381c3b568e1d3bbfa1725f7a353c7145833838128b46e3f7d1d88871632f40df1e4ef85ba268ea7bf9f1bd48244942555515eebfff7e3cf4d043282a2a423018a41b43b268fafd7ee4e7e7e38e3beec0abafbe8a5ebd7ab5aa75ffc94f7e429d1400703a9d78e59557684ab8de4b8880085bb66ca1e7b3582c183e7c38ac566bc2b63b9d4ecc9b370f7bf6ec51d57fb366b158b06fdf3e949696d2483b296d200b765a5a1afaf5eb4737b9a150082b56ac4079797942449ea80d1c3e7c98a6b0b9dd6e5c74d1456d3a0b95959558bc7871abec8893eb0b9ff4a5aedd042423ac11451113264ca09bdb70388c2d5bb6a0baba1a4ea7136eb71b292929aa232b2b0b5bb76ea5f7415243dbdabc77a545a3510c183000e9e9e9348aedf57ab174e9520080cbe56a751f2929290080b2b232fa42cfc8c8407e7e3e5dff82c120aebefa6a343434d04d6a454505962e5d9a5476908068a44631d1e70441c0d1a347b168d1a284ea04a45ce4e8d1a39024893a005959591dced820d1fddb6ebb4d45dc585454848d1b3726ade1258e80dfefa7e9c8c1601077dd751755cf20fdfee1871f52474cefde2549c2c68d1ba9930d003d7af440af5ebdda3d86388e83dfefc7d5575f8da6a6265ada505b5b8b254b96c06c36eb3e73b7db0dbfdf4ff92948664ef7eedd9346cb9c4e27162c58806ddbb6e9ca7c92ba71c2064fa2f47dfbf6fd5eca0bc898deb0610395d24c04521d3d7a948e7993c9849c9c9c56e06b322721d15ae472b9306cd8300a44c9b28c8d1b375230385904fff3cf3f87d96ca6fc0fe3c68d53a5a4eb59757535962e5d8ae3c78fb71a7b5a59b044637ccf9e3d282a2a529131927311495c16342532917a75d3353535aa12a2cccc4c5d505b1004343636e2830f3ec0dab56ba973cdf639e9af63c78e5145035996d1af5f3fddb53761f6a8cefbe054b25a2549c2f9e79f4fcb22cc6633366dda84afbffe9af245e89d5714455a5ea82df52035e11f7ffc313efffc730aecb24e0e71d6d8f28e68348a6eddbab592db344c3f7b39d13a90082448f63d2dd78036a0a8551460ff9eac1d7a250c6ce68d1ecf41a21a7f2d21219ba147c00092214800015996298f070b2ac8b24c5571525252e070385af1fd683302b46dd1665310f0438f378205061201057a7f4ff4dcf4f6896791cc210720c674ba5a3520168b324e30d73260b92e6b89a228ccd9b9964d8ad0f617bb44e690534560134d68021eb05005f95828e8c7ce5ddfe0820bce4fbaa0c495244e4aa344a351f81beaf1d7194fe2f9e79fc59e7d8701859003c6babc36594b902849223e58f811ce3f7f2482c11083e24575115020d6e22045100c06f0ccb37fc3827fcdc3a74bd6801724705c2cfeb894ef8043823b335f32d168142b57aec4ca952b71eeb9e7e2273ff909468f1e8d61c38651e7922ce0e79e7b2e9e7efa69fcf9cf7fc6d1a347e98b63f0e0c1d461922409070f1ec4810307100c06db4c1dddb16307aebbee3afabbbe7dfbc26ab5268c08711c8740209090019b5db08b8a8a70f1c517536ddb9e3d7bd2bf171414c0e3f15079afcaca4aecdbb72fa9e34708c1962f5f8e0b2fbc10757575f0fbfd98387122e6ce9d9bf43b5f7cf1054a4b4bbbdcd98e46a3c8cece46666626ad1f35994cb8e9a69b70e38d3726753c48b6027966e9e9e970381c6da668779591ac12366a2649125e7cf1455da79bfd9ec7e38120089065192693896a7f130e83912347d29a588ee3505454849292920e6fce92b59d75ba13f5792010a03281447ef054fa890060070e1ca0ef5152df9b2c23477b3fa49fc78f1f4fb32b2449c2f6eddb71fcf8f136cb1f9a9b9b71e8d0218c193386a680e7e6e6b68a52b7350e870f1f4e494589bc279963c99e7b666626ad3b359bcdc8cacaa2e9a689d620599675c1012d0043fa31168bc1ed767fafdc1c64439bac0d7ebf9f66361149bad36db3a228c8c9c9417a7a3a05d39a9a9ab07bf7eea425370424d8b56b978a4366e4c8916d82ab454545d8b76fdf69973ae9294f701c477fcfee8bcacaca74c16de2c06b01696dc997160066d767e2b098cd66e4e4e420232303e9e9e9141020e3abbd73a633c12787c3813e7dfaa89ed137df7c43d782535d1bb5fd4052bc895c656e6e2e4451446e6eaeea7376bbfd7bcd623bd301023de93b3d008d056312fd3fbbfeeb39e827c9e3d5517cbd747b3d3e02bd1207f27e64afaf75b659e75fef9c89ae43c61cc9b624738f8dce936b11c05e144598cd66b85c2e2aa74b081ef5aeab079a90cf10494736bb548f538064c1b1590589320492295468ffffec9835944d9f6308026349506e25ce0f406405bb2c659c9d7068d5a6f8c31698cf745534412d5fc8f324e558bb600849cff1cd9e7da8ab6f40aa279599885195331e1f94270759241246341241241ac1fdf7df8fb7e7cec5de7d25a8afab851223c4895d372ee2671754128f151555a8aaae45aa2705b1680cd1580c82606aa56870125c89408945118b45e1f3fa71cdcfafc390c183f1d9b255a8acaa437d5d2d4272b8b39b0e8553c0c50924a81ce3996ae405bc77ef5eecdebd9bd6434e9a3409975d7619f2f2f2e80b7dc08001983a752a66cf9e8da6a62688a2486b73492461efdebdada498f44c1004949494a81cd38c8c8c4e8918288a82caca4a7a6f3ccfc3e3f1401445048341f4ecd993aa01104e0342ec96cc484aaad3e9446d6d2dc2e1307af7ee0db3d99c3052cbf33c0e1f3e4c9502bad2b1884422add2aac9bdb7c7d8545bb3d90c87c341a3d2dfb5f13c8fecec6c15f92249696d8fd34c325a62b118525252204912fc7e3f5c2e171c0e07fd3bc771d8bf7f7f5292b3ce88f4248ad2b0f77b2a191b914804bd7af5a21b2ee270eddab5abc351ee68344ab318c8e65d92246cd9b2a55df71a0804505555459d729bcd464b70daa36640a2fa168b45f53c6c361b1c0e47bbfa827c8f3c7793c9d4e6b5dbd32eed3a70a69b76ad49e4c49ecafa4222ee04202092ac6dadf7a5a5a514dc8b4422c8cfcf87dd6e4fb8f692759c44dfbf8b7e02a0ab16a01d2f2cc159b2be8d4422902409ddba75c379e79d8789132762e8d0a1c8cbcba3012f4992a84a080142bf0f73bbdd9024893e23afd78bb2b2b2368983dbbbb6984c2664666662d0a04198387122468c1881828202baee994c26343737abc0759e37e4a913012dda88b21ee3bd5e6a3a1b8dd6232bd72b2d207f63a3efed592ff5f807b4808456894a0b16240a0269010132ce088f8dc964a2602a01050830c546e749f0201c0e439224a4a4a4c0e572517e2ead2208db1f89d415c8b959ae85935c7a3c932dcfab3249f53281b4aa105a3047af8fcf12588d03c7732d047bbc2e3b3d331b007050c0caf3757e7be864621e64b24dacde24e8ec45816726b21ea24ab31ef8932512349b80e3507ce8200e1e2cc60517c449c8389dd439769211c789a0d87e7f00b7dc7c334a8a0fe0db4325a86ff0a1b4f430c29168e7dc648b83cf711c4ca2099264812735035bb77e094138791ffe401015272a909191869812a17536849fa175df99209981684c415896d1d4dc08578a07ff73eb4da8abadc6b7df1e44436333944e4c4ae13940b25891969681bc829ed8f1f53758be62f9193f13d914ec43870e61dfbe7df8e0830f70fbedb7e3d65b6f453018442412c1840913b062c50a6cd9b285d68292f143c8b312a5be6ac7757373b36a7e89a248ebb24f77d34798ff8991fa4cbfdf8f8c8c0c95d3d0d4d49450624ebbc9ada9a9a1e9a0e45a69696949bfafd518ee2a8bc562484f4f5745b90813385b7f9ca83c81bca0885345ea58bf8f6829c771548d8098565630d9fdb0f742c6aea228b0d96cad36f49595955d02107c17168944909595d56afc13a9d18e8e9fd4d45455f494a4d8ebe996eb39016ce61071d2494d747b1c7152e6c4be93483681de98d51e643327080282c160bbdadd51b0e7c75a134db234d8b533140a51d2cab6003fa248c09e816819d200002000494441542f2d2d8d4a109ec900cba9f415004c983001bffce52f71f1c517232d2d0d8d8d8df0fbfd546540cfd9fbbeeed76ab5aade99b22ca3babafab4c63b71f2860d1b865b6fbd15175f7c31727272d0dcdc0c9fcf079fcf97b41f0cfe01f51c4a962dc0fe9e75385980402f6d5d2f8a4f1c5b96278044fcf53801f4a2f97a60b89ea4617bc6909e8200eb78b36387b49338fd249390bc1fc8ef0928cf962688a20897cb05b7db8dbaba3a95da81b62d7aa50eecda41825f6ca602bbd76a0fb7402229cb64f383e3b8b347c580cd0ed05b1f4fd658b544b8551feabcc543219e26d7bec802c77c96d37caf13978478a4be8dba328e8b832c8825766c56af5e8bd1a3cf8760120005f11a7d86b5577bbf2693085880588bba41535303523c69183ad4895030847307f6eec44d755c85c166b523bfa0077af4e8056f20820d1b6f86553839d49b9a9af0c967cb3074e81028a6932f1f249199034cb05a2d009438b8a4003e9f17824942afdebd1109cb2d13fbf41e20e93a5132232faf007dfb9d83de7dfb431417fd2000022d4a2d08021a1a1a307bf66c0c1b360c43860c413018447a7a3a060d1a4475b5b52f77e28cb5c719d0027084f8a8334c1b8521d1089256aa255b6c6f9bf59456884cd69960da7babaeaec6238f3c42a343e420a97784848cfdbd288a282c2ca475e8dfe7bd10b3582cf8f0c30fb161c306d86cb656f7c0fe2b491225e38bc562d8b061034d813e59a60615f0f07dab359c2eb8a78df2912c9e8e3e3f92a2cfbe1348bd79478046325f6c365b9b650e5a9081bc57144581dbedc6e38f3f8edada5a582c96763ff7502884f5ebd7774af4d3b093ebbb767d61b375dab3de939af43371edec4c675b10043cf1c413b8f9e69ba9dc21510020993924fbc0e7f3b55992f75d39a0ec9a41144d4ed54927ced3238f3c823ffce10f9065195eaf974a0e1382429bcd46090db53c1186a9f7f15a2e312d38407e2640004b7e4708f0b44081966c907578d9b598050812d5e7eb45b313fd4e9b6a9f48f1805c8f8015dafa7e2d4040fa8a38fc042060c1010216102081ec7ba3d1282c160b5c2e17cc66b3ea5a7af7c7965bb07d42b2a4b4fc025a208cdd03272afb688f2ca5f6f7674d89012103e4795e3755ff64e7a981852e812b1849c1360bc7d9d202aeab5aa4ff924ea8bb2e2426cedab2753bcacb8ea34f9fdead88415a83315c0be1991497a1144c90cc66f8bccdf0f9bd80023835d1bdd301663800a24944b79c7cf41f3010296e0f8e1c6d1d01e33960f98aff62e488e1b8f28acbe20b1822494182f8b812e170985ad2a65df0fbfd08067cf0fbfd8844c29de61c088200bbc3899ebdfac2939a069e137ed01b5452c7bd63c70e8c1b378ed6fe676464d08d0d213422e3c762b1b46ba3a3280a525252549b014224d3191b9d8c8c0cd5739565992ed8240d9a756af49c2cbd0d4f666666abf1c2f6c1f7ed281e39724415218f46a3ba29f46d911a7d57590fc9c687b64cc4e7f3e1ebafbf6e976493f65e88d31b080474eb8893adab67fa1cadacac6ce5845bad569aaedb9179436acbd9cd4632e250edf8d392cef9fdfe76afaf8471dee7f351f503d2a6ad5bb7b64b5a58efb91bd67900414d4d8d6aed2451b8b638088038c1a17693dc11f0e987629148043367cec4ef7fff7b1c3e7c589561278a22aaabab51585888a3478fe2e0c183b8eaaaab307af4e8ef8def853c0b9fcf8770384c417a9ee74f0bb08f46a378f8e187f1fffedfffc39123475401298bc5828686066cdfbe1d478f1e45494909860e1d4aa5300dd35febb54e3d0b0ab060010b021089527288a2a822c163d3d809a11f49c5270e3289b46bb909d8efb60516e801047a65067aa9fb2cdf01fb337b1e12a567b3080838a0575a4022fc645f48ee9b2862d9edf65600991e5f020b12908cb5444a05a45dacafc5020489c08144ff6a7f47fae2acc9203839b893683c729c26db80ebc28592a7a0459b695f8ad2a5a8af42da8338174242e6518e03cff109dbcb711c7c01191f7cb8084f3f35334ecad4f2b118718ae2640bf14c0466b0091c07abcd065192e0b03b10080620a7862087c350888e297f9a048d5c7c939e9d9d07b7270d922422d1092551c0e6cd9b3065caa574b311e5a249534939255e9862e62d104509568b15e1881bd16858975fe274c68eddee408adb0d9bcd0e9368c277a9f6d05e54bf236396e338ca0e4fd1c916243a1c0eabf4c0498ab2cd666bb3cc20168b61c08001aacf84c36178bdded372d4c81c38efbcf354bc007ebf9fcabd1d3eac56f570381cb0dbed54c22dd179038100c68e1dab223553148532e27fdf2081288a3874e8906a2e489284ecec6c94959575a8d6fafb76961545414949892a9a95959505bbdd4e19b53b7a2f1cc7a1bebe1ecdcdcd484d4da57f4f4b4ba3f58a3f34932409fbf7ef6ff5fbcccc4c5455557578035a5353a3ea8768348a9c9c1cecddbb37e91a4b80841e3d7a50a08de779343535b5cb7924cfc7ebf5a2baba9a928a128e85afbffebadd650a67ca183edbcc643251395b76fca5a4a4a8de037a160e87d1b76f5f98cd6615d7899e5ac00f1d1ce8dfbf3f1e78e0017cfbedb7349ba0a1a1013b76ecc0c71f7f8c83070f22140ad1f7e7c0810371c105177cef6d27c4bb041410459112267614688bc562e8d3a70feeb8e30e1c397284f683d7ebc5debd7bf1f1c71f5335a270388ca6a6262ab36898febaa6056cf44aadb400813683808005a4269f8ddc938c010212907fc9fc24a47bda747aade3af0568950425cdc4c966c10a76fc242226d402085aa0807c8638fe841894f01290ff27d7659d7b525260369b61b7dbe9de4e1b3cd5235264f906b4e08d569d802584d72b654f4486a8e541d0ebf7b328834069f14b15ea00730939085afe4e8182ce2729a44eb842ca1f787d323eea48776ddd981a146899f80935d715fab9385783daac661356ac5885b1632fc0cfafb90acdcd5e705c186128ba1919dafa2693c9849824c16ab72316532838d059e8b52449b03b9d902433042131b19f2098b065eb97f8e73f17e0ce3bef40580e838b845b680c5a2f504acbb3e234f722b6681d76e6f38b732888104d121d4767d20b261a8d22353515d5d5d5b0582c49d9e0d9857adcb871d42926dae1b22c431004ecd9b387a668c9b28cf3ce3bafdd244ba3468d52391db5b5b5d4f93bd5cd7d241241efdebd71eeb9e75279b248248263c78ed1175c797939cacbcb29829c9d9d4deb4393f51fd9c0343434d07174e4c89133260a4658b6b76ddb8682820284422158ad569c73ce393876ec58bb7821ce14c78ae779141717a3baba9a825183070f86c3e1503918a7722f5f7ffd35faf4e94353a3070e1c485fdaed21b33b931c4f32efbefcf24b64646450b9a6e1c38763f7eedded5231d0d687fef7bfffc5cf7ef63378bd5ec8b28cb163c762f9f2e56d8ebd8c8c0cf4eddb97ae07c16090cac8b5679d251bb3356bd6e0c20b2f443018443018c4840913f0d1471fb5bb3f0c70a0ebc65a5555154a4a4a6844d1e572213f3f1f5555556d02c263c68c51492f9e387102cdcdcd67d5b30a0402983a752a1a1b1b2969687575355e7ef965ac5bb78e3a5dec586d4f79db77f16c09ffd0e8d1a3a97a48f7eedd6974b5adb5515b7a326edc38ea4c098280fafa7abcf9e69b58ba742964596e158566ebdd0d6b6da4948a0505b44001cb2ba0754c49f920c920209f6123e0ac4c60381c563d239265a9252f4cb4e6b27fd77baee4ef5a4e046dd680760f9b08202020b796149090144a92d48a7f80957666332848369c288a34d09448412051a6030bbceb9576903d87563eb1bd87161c2020522c16c35942ed1977f879c6d94e7880385c5db5902ae0d0e24812923f4e5f428480186af0a0eba41729689244d684f65112af54944c78f31f6fa3acbc1c66ab199268865934b7bda9e2e280032f081084f8a492cce64e3b44496216b5e45922e41df5de070b71fcf809389c0e4816332c9219022fe8927ae8a02ef16b09421c3ce884839c978ee72ee3a53875e7f19e7beec1279f7c82e79e7bae95048bde0bbfa1a101d75c730dce3bef3ceab80702019c3871824a35ad5ebd9a2e8a244ad4af5f3f0a4024b28282025c7ef9e534d380e3381c3a7428a9e41879a924db44343535e1af7ffd2b2528238bf5ce9d3b294a5b5d5d8dddbb77d3976d565616751213593018c445175d8461c3865107d5e17060f5ead56754f42b1289e0f3cf3fa7f766b1583066cc18b85cae3637884d4d4da8ababa38a0bdfa70982807dfbf6a1a4a4846e50bb77ef8e112346b4e9f42a8a82baba3ad4d5d5b5aa8fb6582c78efbdf7e076bbe9f8183870208d58b7e79c6752d91061fe7ff9e597d1ad5b379a2174fef9e723353535e9730c0402f4799379e57038f0c20b2f20373717b1580cb22c63f4e8d1e8d6ad5bd2711e894470c92597203d3d9d6e546a6b6b71ecd8b10e8d259bcd864f3ffd9496c4c8b28c73cf3d1743860c69f3bbd168f48c7c46679335343460ebd6adb0582c00e2650343860c490a0a13e93ee2389379f8e5975fb62b2be48764d16814797979686a6aa28ec0a64d9bb071e346ea146b2386ddbb776fb70c68575a2814c2860d1ba8b4acd96cc6e0c18391959595743e85c361d4d5d5c1ebf5aadef96eb79b82838aa2e09b6fbec1ba75eb683fb011ef68348a6eddba19b286498ce5102287d96ca607e16861ffa6fd9c161c20f393a4d713d67f599669e49d8d9093a3adf201d649d72b6366d706023eb16dd23adced01070818c56642c8b28c5028a4ba27729f6c09059ba9480004abd54ae782deb5b4ed64b32ec8f5d9eb919fd93e654b26f47e6ecfc17e9edcd3590110f04c7a4b9b52817431e952bc82120ef27c8bbb9da09e86a70e39c075e1e3e05b540cd8ba235d14a125e3a1ad0eaaaaaac2cb2fbf02afd70b51922059cc104da26a62b6855675f601742cd8cef31ce4908cdffdee2e1c2f3f018bd90ac9125f0c590997ae6cb3de3d9067d199650b9db569193468109e7efa69b85c2e4c9e3c199f7df619d2d2d2124a344522118c1d3b16b367cfc6891327a8d3565151813d7bf6d0c55c9665fcfdef7fa70e685353139e7cf249fab2d27344a3d1289e7bee39d86c36baf1f0fbfdf8eaabaf92020424ddd9e572e9bea042a110eeb8e30e5c79e595a8a8a8a0f5777bf6ecc1e1c387e998f07abd58b76e1dbd96cd66c394295390979797f0da6eb71b3367cea475a2247d7af5ead55d120122608c560f5b1bf1d593295abf7e3dcacbcb694461ecd8b118356a545239ae582c86871f7e187bf7eec525975cd2eebaf3aeb46030888f3efa48b599fef5af7f4da35a89eec3e17060c9922558bb766d2be0c76432a1b4b414cb962d83dd6e47241241f7eedd3179f2e4840e3521db5bb3660d5e79e5151414149c51f3db6432e1abafbec2ce9d3ba9e6f3b061c33076ecd8a435c4575e7925bef9e61b4c9932051e8f07b1580ca228e2c8912358b468119c4e27144581c7e3c12db7dc429d42bdb1daa74f1f4c9f3e5dc57b70f8f061d5bc6baf79bd5ebcf5d65bb4b489e779fcf6b7bf45666666c27345a351e4e6e662f3e6cd983f7f3ef2f2f20c89b42e305996f1dffffe973e678bc582891327a26fdfbe099f4d2814c29c3973e0703828204c9cd1b38d948ee77955b985a228f07abdadb206c8ffdf7ffffdf8c52f7e01bfdfdfe67865b5d6d973b0ff7f3a7dc9f33c366cd880b2b232f03c8f603088cb2fbf1cbd7bf7466a6aaa2ee01f8bc53074e850ecdcb913f7dc738f6a0d6d6e6e5691dc917202ad93c8711caebaea2a3cf4d043edce36fab199a2285435ca6ab5c262b1b40207082060b158e8fe8bfc8e9416b0d16c366b800506b4ce34ebd46a1d7f2541d66ea228b7deb8254e3f9b45c03aec5a150316a048a4a0a0759ad97b62ff255286daec17b2b64992a40252b46d610f1624d082032c50a1ed53f63b6c8987f6f7e43bda9fd9e7448eb3eacd171f1048eadc52ad0385e52ce84c44955313ddb5b4472f8d4769a969679f4202a5bdd35f18dad5724ed5415c121a474501d66fdc8ccd9bb7211c9621896658ac5698a51674911792ca7074c5c1711c045e50bda8daba738e036aebeaf0d433cfa2a1a10116c902b3c51a070b444985927ea70778f0bc7046cdaf70388c418306e1c489137431cccbcbc3c2850b71fbedb763d4a851e8debd3bb2b3b3919f9f8f51a346e1eebbefc6a79f7e0a00d451e5380e6bd6ac51e95ebb5c2ecc993307fbf6eda3ce88d56ac55b6fbd85b163c7222727076eb71b2e970b99999918316204de7df75d5c76d965544249100414151561e7ce9d49b30e9a9a9af0c73ffe1153a74ec5f0e1c3919e9e4e2569faf4e983fbeebb0fafbcf20acacacae838926519f3e7cf572dec9148042b56aca0f741e41befbbef3e4c983001d9d9d9540b372b2b0b175c7001e6ce9d8b912347d24c0a4992f0d9679fa1bebebe4b3635a4de97802c8aa2409224f4ebd70f191919183b762c56ac5881254b9660dcb871aad4c303070e60ddba751451cfcdcdc5fdf7df8f1b6fbc117dfaf4416a6a2a525252e0f178d0b3674f4c9e3c19cb962dc3d34f3f0db7db8d679e7906e3c78fffdea3391cc761e5ca95d8b76f1f4d693ee79c73f0d8638fe1d24b2f455e5e1e3c1e0f52525290969686810307e237bff90dbefcf24b4c9c3811bd7af5c2cc99333160c080569badd75f7f9d725278bd5edc79e79db8fdf6db3172e448d5b8ead1a307aebdf65a1c3e7c1863c68cc1af7ef52bdc79e79dc8c9c939a3a2d435353558bc78b12adaf4e0830fe2faebaf47af5ebde0f178e072b9909a9a8a214386e091471ec1c2850b919d9d8db7de7a0b13274e84dd6ea7e36cf6ecd9d46989c562b8fefaeb316dda340c1e3c98f64f4a4a0aba77ef8e2baeb802cb972fa79292c491dcbc79336a6a6a3a7c2f822060e1c285282929a1e0cec89123f1e8a38fe2273ff909b2b3b3e176bb919292828c8c0c0c1d3a140f3ef820b66fdf8e810307e2e28b2fc69ffffc67e4e4e41829cb5d60ebd7af4761612165001f3e7c38fef8c73fe2d24b2f456e6e2e5d3b333232307cf8702c58b00053a64cc1f1e3c7c1f33c2449c2b66ddbb07ffffeb3ce21942409ebd6ad83c3e1a0efb2112346a0a0a00076bb1d56ab15292929183c783066cc9881e79e7b0e9595956d8e53c2cfc1f2af701c87dcdc5c646666a267cf9e78f4d147b16ddb36fcee77bf5371ac74e49d73e0c0017cfae9a7905ab23a6b6b6bf1f6db6fe3ca2bafc4a04183909a9a4ad79101030660dab469d8b871230a0a0a307dfa74dc78e38d14d83b70e0000508789e47dfbe7dd1a74f1f381c0e58ad56389d4ef4efdf1f77df7d37162c584079820c4b3cb6b420000b0ee86511b00a2f6c9d3f2bed479c57bd48bbd69165c9f9d8ba7cdd60990ec8c17208689d6c92f6afe52448441248c6955e3090fd990509d87b0c0683940b44dba63849bb48e511d94c033da9461624d03af17a879ec39f0810d0033558d081bd27729c25793827a3de5a76ce56e8a6363aae74360781c200022d841f4938115a39e19d5e73ae1e8c847b410f6556148647a0cd3a63202ccb787ed68b385e5e86fbeefb2382c1607c62ca6184e510222d35fd7adaa55d3c1a5aca19dad7911cc761e3a62ff0c493cf60faf407d1b3474ff8fd7e98c438e158844c7c2447303b1dd0a1d73a33363e56ab150b162c80a22878e8a187e8c6cee57261dab46968686840737333adbd4a4f4f474646069a9a9aa806b92449d8b97327962c59a222312369c90f3cf000e6cc9983acac2cca0340363f84fccfe3f1203f3f1f1e8f0725252554f5a0aaaa0aefbefb6e9bce04297bb8e38e3b70cd35d7a0baba1a7ebf1f76bb1dd9d9d9282828c0f1e3c7691db6c964c2bc79f3505858a88af2f33c8fd2d252cc9a350b73e6cc81288a686c6cc4cf7ef6338c183102b5b5b5686c6c842008b4cd2e970bc78f1fa70ecce1c387f19ffffc073535351d9271ebc866a0a8a808870f1fa6723b168b05b366cd42241241bf7efd60369b110804f0e0830f62c68c19d8bb772f75ce5e79e515f4e9d307a3478f46434303f2f3f371df7df7e157bffa1505352c160bd2d3d3919f9f4f377404f4b9e0820bb07efdfa4e94323d3580a0aaaa0a4f3ef9245e79e515a4a5a521100860e8d0a1e8dfbf3faaababd1d8d888582c06a7d3898c8c0ce4e6e6c2e7f3e1e8d1a31004017dfbf645dfbe7d51545444d7005114b16bd72ebcf8e28b98316306fc7e3feaeaea70db6db761f2e4c9a8a9a981dfef6f214ecd46af5ebd50555515cfba1245fce217bfc0071f7c4081a833c162b1183efcf0438c1e3d1ac3870f872ccbf0783cf499d7d6d6221a8d22252505b9b9b9c8c9c941717131c2e1302449c2fffccfff60f5ead5f47cdf7efb2dfef18f7fe0aebbeea28ececd37dfacea1fabd58af4f474f4e9d3073e9f8f464eed763b56ae5c89952b579e12f123c771282f2fc7ac59b3f0ecb3cf5262acf1e3c763f8f0e1a8a9a9a1116cb7db8dcccc4c646767a3b6b616814000822060c48811e8debd3b8e1d3b6678169d3c271b1a1af0cc33cfe09d77de414a4a0a9a9a9a306edc380c1c3810757575a8afaf07cff3949fc0e3f1e0c89123d409adadadc5c2850b515151d1256be7f76966b319ab56adc2fefdfb919b9b8b603088010306e0b9e79e4379793955d8e9d1a307f2f2f2505656469d83b6fac2e7f361d7ae5de8d7af1f75586ebcf1464c9e3c19696969e8d3a70f9a9b9b71f7dd77231289e0dd77dfedd0fa4d64455f7df555646565e1965b6e414343036a6a6a307dfa74949797a3b6b616b22cc3e97422272707f9f9f928292941281482d96cc655575d854f3ef9040d0d0dd8b56b170a0b0b3164c81084c361f4ecd9137ffdeb5f69d9515a5a1aba77ef8e9e3d7ba2a2a28232c61b1904facf46144598cd66551fe9951b6ba503b5be159baa4f94a3b40e28495527e7237b40161c489459acb766682500b5fb7096ac90f00290747f2d18c0aa0068fb826d0fcb6d110e87110a85543c07643d62332c4451a4f74864b7f5c816b50a06da32012d879b960f422b67a8550ed2020f7aa5157acf81e3b83819e359f5d249122fa60f853be9347769435a1403dae3dc9de447e8428799f44e5baa8b7cdb12502723343c7c5e2fde9efb2e02c120eebd771a24c90c9349846c3221dc522f138dc6193d634aec3b030ae277dcfe0419493461fd860d282e3e847fce7f17d9d9599065b9450b5b46381c070aa2d128624aac1502d959f7a4288a4a49227e0fdc19f372319bcd58b46811aaababf1c20b2f50e237226bc4a6942b8a828a8a0ada3f66b319fbf6edc3fffeefff52b922ed3c282d2dc58c1933f0e28b2fc26eb75396d8bcbc3ce4e5e5d17e894422f4dc9224a1b9b9197ffbdbdfb07bf7ee368990c882eef7fbe1743a6959037961545555d154354992f0f1c71f63c99225ba3284168b05dbb76fc7cd37df8c050b16c06ab5c2e7f3c162b1203f3f1ff9f9f9b4cdb22c53467842e6f4faebaf63dfbe7d5d2aa366369bf1a73ffd09fff8c73f505151018ee3d0ad5b379afe49ca1d0a0a0ad0bb776fecd9b387920935343460dab46978e9a5973071e244d4d7d70300d2d3d3919e9eaeea8ffafa7afa52932409858585f8ecb3cfba041ce86844d76c36a3b0b010f7de7b2fe6cc990397cb4535c3bb75eb466be3c94b9ccd4a31994c58be7c3976eedcd9eabaa22862d1a245b0582c78e8a187100804e0f57a69649c5d1b2a2b2b69fabd288a983d7b36eaeaea4ef99ebaca716b6c6cc45d77dd85458b16a177efde74dc676666d2f47cd24fe5e5e514a0f3f97c78f6d967551911d168146fbef926d2d2d270ebadb7a2b9b919a15048b77faaaaaae8f8494949c1dab56bf1da6baf9d52f6003bcfd7ac5983c71e7b0c7ffbdbdfe2d2b4c1202449427e7ebe2a9a138bc5e89a42b4d5df7aeb2d1c3c78d0f02cba2892595c5c8c1b6eb8010b172e446a6a2abc5e2f2449424e4e0e72727254cf86ac9d8451fcb5d75ec38e1d3bce4a9e08523ef197bffc056fbcf106cdc0e9debd3b7af4e8d16a0e9248655a5a5a9baa239224e1edb7dfc6cf7ffe73fabb9494149a2d70e2c4099a0174e9a59762fefcf9a7e4704b9284175f7c111cc7e1baebae43201080dfefa7ef0ef69d4b407342e6f6faebafd375c7eff7e3b9e79ec3bc79f3e8e773737355fb01455128d02a49129c4ee769ad1b67b391771abbf6262a3161a5f3b44e270197f4d2ef43a1108d606b59f6b575f2da775f47de837ae392ecff58079acd78207b3b96e320911cb336d24fc006c22fc0921a6a332e083797200894878064c6e9c9316ac909c97d9076452211badfd6aa199036b28485da67c5f23068fb9e055ec8bfb22c9f2d25066cea484716e12e450854d7488494b5fa5e172434907670bc005e43c0d76a32308b427b8ce73944a311cc9fff019e79e679f87c5ecadc69773860b3db61b158e3c89a60529500e84df6d3addf3f1d094b9ee3505e7e02575c7935b66dfb1236ab0d926485dd6e87c3e184d56e83d56a83c56ca1f7428ecedc1890e775a626b5f23c8f952b57e2a73ffd290e1e3c48a37b84bd954d7522f7130c06b176ed5afce94f7fc2fefdfb1346040919d3f5d75f8fd2d2524a0ec7d65291542eb2601f3a74080f3ffc30bef8e20b5d275edbbfe17018bb77efa6247a6c8d1991a721a998f3e7cfc79b6fbe89b2b2b2842f2e411070e0c0015c71c515d8b56b57ab455edbe668348a23478e60dab469d8bc79739b848aa70b3e994c26ac5ab50ab367cfa62f26921a485edec16010fffad7bfb071e346d5fc1704017ebf1f77de79275e7ffd75c8b2dc4ace871ca47ffc7e3f56ac5881e9d3a763efdebdad08fe3a739e747463b467cf1e5c79e595d8bb772f6db3f6391187231a8da2a1a10173e7cec54b2fbda41be927ed78e38d37f0c8238f500045af8f48ffd4d4d4e0a5975ec2d2a54b69baf4e93ee776c9e9429fb55a6f0e46a3514c9a34091f7df4916aae259a83454545f8c31ffe807dfbf6a1a9a949750d93c9843ffff9cfb8efbefb10080412d65a120b854278efbdf7f0ecb3cfa2acacecb4c78fc964c292254b3079f2649a01c1324d6bef271289a0aaaa0a4f3cf104162c5880eaeaea84b2bf1d79667a29aedf35c0dbde71d2d17b4dc667d2d69cacaeaec6942953b076edda36e7642c16c3891327f0c8238f60d5aa552aae8a5369777be7547be5353bfa6c939ddb643261fbf6ed78e8a1872888abd72f8aa2a0bebe1ed3a64d433018546510e89d9be779949797e3aebbeea2e48eecbb9bb4e9e0c18378e0810768994047d761c27ff3c4134f60eedcb9f4ddcc12a391772e596fcbcacaf097bffc05ebd6ada3eb88288ad8b973277ef39bdfd07368fb818c9be6e6663cfdf4d3546dc7c822e8d83b43fbff5a093e96b02f140a211008a80ea218a3ad9567c90a59d9f8387c7b0000185049444154c3646b47523f25c1df5847574faa919d032c80a097f6af4772cf6624b0079b96cf961c906c5aa7d309bbddae5239d2e34fd046fbb5448389b804b4ca11ec67b4a51e7ae505dadf119e8fb344e690a30b89688a23378924f7381dadc84e77a0289a638260124fd692732d250e8c331c1fa03cfd3bd7d99408cccbc666b3c366b3c1649218967f4ddb4d2da92b1cdfc14710c1871f2e425959397efbdbdf60fc45e3214940341a412c164f3f0a47c28846a28845232ad299939b8cd38464b8b842c2e96d0a009fcf8fbba7dd835fdf720baebefa4a9c73ce4084c3322c162ba2d108229168fcdf6818d1480c4a2cdad276a5531e1d078e4106cfcc2967b55ad1dcdc8cabaeba0a975e7a29c68c19831e3d7a202d2d0d369b8d46a76b6a6a70e4c8117cf1c517d8ba752b95d04b36076d361b4e9c3881cb2fbf1c575e7925c68c1983eeddbb232d2d8d4acc343535a1acac0c7bf7eec59a356b505f5fdf6e266b8fc783c71f7f1c6eb71b53a64c41af5ebd909e9e0e511421cb32eaeaea505c5c8c8d1b3762fbf6ed6d2a1eb09baedb6ebb0d975d7619468d1a85828202783c1edae6c6c6461c3b760cbb77efc6aa55ab68ada45e9b63b11856ac5881fcfc7caa3d4c52274fd539983d7b364a4b4b3171e244e4e6e6421445343737a3a4a4049b366dc2faf5eb2900a0b7763ef3cc3358bd7a35264c9880be7dfb2223230356ab15b1580c5eaf17c78f1f4771713176ecd8816fbef98682109db94923d1ea175e780113264ca0eb487b15134c2613eaebebf1cb5ffe1253a64ca1632b3535953ea7fafa7a1c3b760c070f1ec4e6cd9b71e4c811bab14d742f369b0defbfff3ebefcf24b5c7df5d518387020b2b3b361b7db118bc5d0dcdc8c8a8a0a1c3870009b366d427171b16a1e10d9cc050b1660c0800134055b4fff99b570388cc58b17e3b2cb2ea3592ada7a6c9ee771f8f0612c5dba1483060da2e76eabced3e974e2e1871fc6d2a54b3169d224f4eedd9bcec170388cfafa7a94959561d7ae5dd8b871239a9a9a5a3928e45c1e8f079f7cf209b66fdf8eabaeba0a83060d424e4e0e9c4e270441a06b45696929b66edd8aafbefa0ac160906a509fee98b15aad282d2dc594295370f5d55763f8f0e1c8cfcf87dbed86c9142f25abadadc591234770e0c0016cdebc99663468fb5f9224bcf3ce3bb8e79e7b200802dd6025cb021204015f7ffd35366dda84dcdc5c9aa9713a51c0f5ebd7e3dc73cf85c7e301002a9baa17518fc562d8b87123860c1942d38bdb034c6ed8b001c3860da399237a72821cc7d175d8e572513e8e8eac555eaf1777df7d372ebef8628c1d3b96be4b4859545353138e1f3f8ec2c242ac59b30695959509d7164551b075eb565c70c105940fc3e7f375a83d822060fbf6edd8b2650bf2f2f268965a2249df4020807ffef39fb8f6da6be95e345966c3debd7bb165cb16f4eedd1b8220d01227bd28fcf2e5cb515656866bafbd1603060c80c7e3812008f0f97ca8a8a8c0fefdfbb176ed5a949494e0d24b2fc58c1933689940a235cb62b160c3860df8ed6f7f8b1b6eb801fdfbf787d3e94428144265652576edda854f3ffd145eaf97720701405d5d1d962d5b868b2eba883af5c9fa9538558f3ffe3856ad5a854b2eb9047dfaf441666626cc66337d2f9267bb76ed5a545757abd630455160b7dbb173e74efcfce73fc76db7dd86810307d277b6dfef475555150e1e3c880d1b36a0b0b010ebd7afc7a38f3e8a73ce39874a451a7632c29e68ad6a4b6294f82dda40905e6d3c0940b069fa44e2500b12b4357ef4dad856560b0b0ab0190cac2c220184f51c761624608104d65927917d56ed80dc3f2b8b68b7db61b7dbd1d4d4d42a38cc825d5a9505b2de9036b09f65fb325146027b3e1670d0961668cb0d62b1587c6feaf1787ed0ec3bb21cc69d77dc86499326222d2d1d0ea74b9572a18da6f382008bc5024110e1f77b71d5d5d7a2acac0c82d039a081d717c2138fff09fdfbf642466636dc9e543a1049ea389d6c2d8334ce2a1a4f41f96cc912fce5d19990439da3891e9223f8e9847178e8c1fbe1494d83d3e582493025764d39c06412210826f87c5efcf1be07b171e386766ec480705886c3e1c475d7fd02bfbae926f4ebd71766b39922e0f1011d452c163f1492e60205a753644106312ff0104d6688a2098082bd85fb70d5d5d7c16ae91816168b29f00702e8d3bb2faebefa72dc72f3cdc8caca84cd6e47c01f6899cc31280acb48da59e80ed7127913c17180d962c5db6fcfc5534f3d7586cec1782986cbe5a29aaf04e50f0402686e6e4630184c98c6956c631f0e87292193d56aa5a971a150083e9f8f4690129d575114f8fd7e6cdbb60d56ab95a62fdf74d34df8e28b2f60b7dbe176bb55e86e3018446363236d73c7c64d1c5db6dbed70b95caddacc124425da6cb22ff26eddbac166b3d1cd796d6ded298304e4e5e076bbe176bb210802055b88e4627b9eb5288a48494981c3e1a0e43be170183e9f0f4d4d4df4bebaaa0e9438c2bd7bf7a66b6b535313eaebebdbdd3704a8b2d96caab1a5284aabe7d49ecd0869178976bbdd6e389d4e3a17c2e130fc7e3f7504f4ce158d4669ea3b0124aaaaaa28637b32cbc9c9a104930d0d0d68686850fd3d1289c06ab5222727876e3448bd6e7be7b7769e68fba93d7d44362b2e970b2e978b925e45a3510483410a3274d5f821a095c3e180cbe582c562a1c495e4fa845431d9f50381000a0a0ae0743a01c4ebba2b2b2b935e3b1289c0ed76d3f46a92a99008246ccb22910832323268a946341a45454545c28c8b68348aacac2c381c0e00f14c9f9a9a9aa4c046381c467a7a3a3c1e0f4d7bafacac6ce57045a351783c1ea4a6a6d2cf55555575b8763d140ad139190f6698e81824eb4b7be663341aa57d43c0909a9a9a0e398ab22cc3ed76232b2b8bf6776565a56ef61bd9881714145092ddc6c646550991f6d9399d4e7a6e321f1365d69139e8f17860b7db291044fa847556525252909e9e4ec75722e09c3c27abd58ab4b434582c160aa690754abb569112a99c9c1cea7c112e8fb6c6b02ccb30994cf0783cf4dd41def15eaf97660c247bb604f0484b4ba36b51341a45201040535313bd5732c7b3b2b26879caa9bccfcf3623aa52640deac8dc644b0a1245b5b5127cac334e52ed4934dded76e39c73cec1c89123919d9d4de78bcfe7a39c017a875681499bed4078a3c89ecbeff7abf81012d5fc6b55cbb4d90494089de138202505369b0d0e87831e8448d466b3c16eb7533e9c23478e401004e4e4e4c06ab55280fdd8b163f0fbfdf0f97c3403830507480903219124ffcfaa4e1062463600ad070e68b908f4b809c8fa7516641028b03b9cc8cdcd87d3e582cde680209892468849fab9df8f4e0fd7933aed6eb979f078d2e17038352896d0b2d04655f52bf1d4ffaee91fd124212bab1b1c4e276cf6e4fd43fa281a89b62d19a9f33d4992100a85f0af7fbd8f556bd6e19cfe7d71e51557e037b7ff16cd4d0d90e5b0aa6d8ad2b9e996bcc04389292d2f9e5367b2e5790e0ebb0d274e9461ce9c37f0c9a79fe19c01fd30fea28b70cbaf6f415676369a1a1b100947e2592942e77305701c1009476846ca996a44e355cf316151d8b6106abdef11b9b5dadadaa4687747ce6b3299c0f33c8da411cdf3446deed8b8e1696424599bdbe3fc0882a0723a4e779e10049d3875a7725ee284363636ea46404ff55977748d25327aa7da37841323d9d8eae8f8221112202ecda597fe9c6c5c11c0a6a4a4a4c3e3b0bcbc3c695b4d2613c2e1304a4b4b3bdc67647eb7354fda733eb2b9f2fbfdf0fbfddff9f821bc22b22cab64e4b41bc1b6ae6fb55a515555a5e21469cfbaa31d17a793ed6632995a3d93b6226ad5d5d5f4bedb735d51145badeb7adf235170764d38953969b158108bc5128eb5f69e5b1004d4d6d6aae6f6a9d4d0fb7c3e1c3e7cb8cd7390529ab6e621fbecfc7e7fabb9ded61cd4de93760c994c26d5fa9e6c7c119e816834da0adc4a3407093071f4e8d10e8fe16400667be73dd94bb7b516112514b2de19e506278df00e259b4f5aa09d0507480601015bb5408196638038b804a06053dd494d3e515420b5fbac53cb46c6b5dc086c5b594e000214104947728f7ad90bd168b415d91f0b3a68cb0cd8687da25a7f42da4bae67b15868d0888df8b3a5426c3fb21c016ce683b66fd9efb1ed65db930c20d0030ad812d41f3c40a0280a5252dcf0a4a6c16cb1c26cb6b414702b09177240893b765d50e8ad0070b93cf078d2e172b961b15a55d909a459f107a966df248e6de7f64f7c511525098ac2410ec9e0387d4920050aed8f683402590e41513a4e9ec6b5944a549e38818ae3c7b17af51acc7ce249bcf0c22c8c193d2a5ebf6f8ab37d9a44532bb98fd38d2e2a9c024589818bf09d723e8e032a4e54e0c4f11358bd7a2d663c3613c3860dc3cc998fa17ffffe8884c30cb743e7124884c36158ac96ef9505be237df54339b7288a5ddeeece386f5745e0cfe467fd5db7e14ceae3aefedee9f21cfc10d68aefaa0da7f2fdcebeef5371c2cfc4b175a6ae515dd9bf5df52cbe8b71f97daf23dfc7983c1b8cf00d918cdef6f413cb35c39618681d54d60165b307d8883f713c239108cd40f3fbfde0388e12f9910c3596b78738fc7a6d26d2866c5b5920eea4bf7532c34f9bc1d95eb2422d48a0f545d9fbd3f22cd86c36a4a5a5c515d25acada08692f015f58702091ba83dedf585243b62d89780db419035ad0801c3f788080e7791c2e29c5e62fbe0040eaf9db760a154541c0e743f014d3fb1239e3a289c7c183c5904411264904cfb5d4c4f3f1010945414c69e129e098a4744501c7f328da5b8458b4f348bd2451c0dec2223c3cfdcf88c5141508d09693af280a8a8af69dc66244223226343636e19737dd0a97c38682823cf4e9d387ca64c57b2106a0f31cec78f94214272a2b219af84e5858c982144742bffe7a2726fc7432a044d1ad5b16dc1e4fe731d12b88031c2d7dc10b3c9a9b9a8db75b27828a922475391789618619669861861966d8990210b09c125a994396e15fab5ca0279ba72564d63aa0e4dce49c6c86413018445d5d1d2a2a2a9097970797cb05a7d3095996a9cc2cf93c89a0eb4911b2d17f725dad04a0b6dc81053748c6022bdfa8079cb0e0002963d7e326d0936224d7713a9db404a9a6a606d5d5d50806832a7080551b60fb5ffb2cb4f2847acf2ad1e71395166881851f3c40109799fa081f7fbcb8c32477f15ad65042c2be537122ad660973df79879e339e420f001ab20d6db93a07704a0bc216edbc6831cff3a8a8a84459d9f153fabec924744afff03c074f8a0d8aa2e0c8d1a328292955a5d174fe4a18ff0fcf73b098a52e1877023c6e3b80782da7d7e7eb323142057140a9b3c6e98fd188262e5944892ead618619669861861966d88fc148c45a9b56affd592fb3579b4da097ae9ec8b16653fa09c7406d6d2d4a4a4a909999490938817809892ccbaa72032da0a1cd0ed6aac4b0a0012973604b1c9295322433023e10a080f030694b21580948e2f4132e81868606949797a3a1a1817236907fd93ed5533a680b04d0963f24fbae1658d03ed7b342c5209e8a72ea4e7d673ba69148f8cc694f8b732e4967c6a3e6380e26810384b3277acbf31c7818cee699fb7c78ecdebd1b93264d822ccb70381cb0d96c7461348002c30c33cc30c30c33ec6c36424c49d2d1592940bdc838ebf8b20116d65167330e1249b813079d4de98f4422f07abd387af4283c1e0f5c2e17557e223c35445549ef7c7aa485acc34bda168944100c0661329954aa0b6c3683aad49b0139b4d766fb8a05084c2613e54f60d513c8b558b241421e4a084fb55285c902a77a99047a3feb813aedc948d0022da6b367e01bed31cc30c35abf10ad562ba64e9d8aa953a7c2e572514939f277c30c33cc30c30c33ccb0b3dd42a11025ca23d17012058fc562aaf47c3df2be64ce69a2a83e47a5df8556647a0d0d0df8f6db6f218a222291089507b6d96cd491d79e4fcf714ec41b40b80e886c2e719ab5e7d646d9b5a4875a1e02b6dc80553588abe409aa2c02162021ca5a814040456aa8951fd4eb47f6deda635a3087bd0f2d08a107b4988ce9629861869dede672b9f0fefbefd3b43249920c70c030c30c33cc30c30cfb511821ea6349ff5807d26432a924f348a49cfd9c5e949a050ab40e27eb6403681579270a39454545f0f97ce8d9b327727373e1743a6964be3d8e70a236927b219298846380550720dfd512006ac106f63e0980a0072a903e26b2a064df693299a83c212b43d81e902551bfb3ed63333eb4a51d89b230929901101866986167bd298a42e56e0c33cc30c30c33cc30c37e6c4625ec5a18fe490601715c25495281045a32be448eb8decfe47a7a24796c143e1a8da2bebe1ec16010353535b4ecc0e170504269adc39ec8e905a0aaf98fc562906519e17018c16010814000a15088a6f7b3a4802c79a1d658a084cdb020a08ac56281cd66a387c56281d56a85d96c86288a104511b1580c4ea713696969b0d96ca8a9a94998a9910800d0eb07b68441db2fecef0847811e98a057d660000486196698618619669861861966986167b1b1516a9235c03ab7168b0566b3591579d73a9389ce9bec7aa4c69efd9738e4c488ba417575352c160b2c160b445154c90a6a1516128120042020ce3dcff3088542f0f97c686e6e462010802ccb2af50592ee4f320058279c0507c879496981d56a85dd6e87c3e180cbe582dbed86dbed86c3e1a087cd66832008b05aadc8cccc445e5e1e8e1e3daaabbac05e5b0f1460c11572904c05964f812840907393bed6964c24e272300002c30c33cc30c30c33cc30c30c33ccb01f014040c001b3d9ac7270ad56abca31ef084040a2ec5a7e02e28013e75b966555149ffc4d10041ae9f7f97c2a07982544642d51549d757ac9bd0a82804020009fcf079fcf4725065970409665da2e6d6901390f9b4d208a226c361b9c4e2782c120051d5802449697201289c0e3f1a067cf9ef8f6db6fe1f57a6929852ccbadca1ed883053b485602e13e9024890217042020cf8f0005e41ef5401d032030cc30c30c33cc30c30c33cc30c30cfb119a3635de6ab5aa22dd242d9e2d31609d472d811feb986b9d4d16208844220885429414916416c8b24c9d62963c510b10b487378a75deb5bc01a228c26eb7ab4a1e8843cfde0bcb0d404c1004158f02cbb1a05546d0937e24bc0be4e7ecec6c0c1a3408b5b5b59065199224d17e603318c8bdb084880410d01ee4b990fbd1964344a3510ac2681522d8f203f23b032030cc30c30c33cc30c30c33cc30c30c3bcb8dd4ab8ba2a82a2f201904241add11c73cd96788a31c894428e8409c6a96d88f8d9ceb81037a4a0589000af61ce46f4ea71356abb5154120eb7cb3dc0c7ab2877ae48de4def4ee815c8bcd54080683703a9d38e79c73505a5a8a868606eaf8b3bc08da720202ea90cc015641c16eb7c36ab552f00500c2e1307c3e1ffc7e3f82c120bd2fd276d24e22c5c8f61dc771064060986186196698618619669861861976b68303c4d9248e262128349bcd2afe81b61cffb6000256de9038e22c38100a85e8ef0899a0365381759093b5470f3c606bf309084140025246404a1cc8e789da00e14a200e3f5b2ea1052c48dbd9f204966f21140a21180cc262b1c06432211008c06ab5223d3d1d03070ec4f1e3c771fcf87198cd664422115516000b5c489204b3d94c9f8d2449c8caca42b76edd909e9e0ebbdd0e51142948400082e6e666d4d7d7a3b2b2128d8d8daaf20936d3415bde60000486196698618619669861861966986167b969ebd8498a3a1b95d623c8eb2810a1fd9738a1249d9e554a200e38719059275f7bae44d7628909c97d2a8a42b30242a110951eb4582c2a879e44ecc9f548ed3e392f311620608114522a110a855407b957c24f60b158681681c3e140efdebd51525282caca4a44a351489244af41ee87803866b399663bb85c2ef4eddb1783060d425656162d31d0920e9223140ae1f8f1e3282c2c444545056459a6edd653970000c16ab53e6e4c17c30c33cc30c30c33cc30c30c33ccb0b3d708f33e292b20a5055aee01b646fd5441026dea3f714ad92c026dd45d2b8948a2da84cb806d97b68de4efec75d93a7c72df8430905531d0bb2e71d6f58c38efaabafd16d08038f5e45f02c0904c0dd2370e8703b22ce3c89123f07abd2aae03966f80641f582c16f4ead50b63c68cc1d0a143919696a62a6560dbce964e98cd66b85c2e389d4ec8b20cbfdfafe21fd0debb2ccb4606816186196698618619669861861966d8d96cacd3ce3aeca43e9e4dab270e787bcf9be8776ccd3b7b2dd631677fafe515d02a2824ca4e60ff3f168b51be00e2fc86422178bd5e389d4eaad2c0d6e4b39c03a40c839006b246000e56f29000098140008140809615900c0d56369238e024d53f353515ddba75437575356459862008adf811a2d128445144af5ebd3074e850e4e6e652d946726d727e161021264912388ea30a0a4d4d4d080683aaec0bf639442211701e8f4731a68c6186196698618619669861861966d8d90f12b0f5fded2523ec2c63a3d5ec71baf7d5d6dff40001ad2283161c49d4ae44a088d641d7122d6a411ac28f1089445a5d5b9b0941f808f4ca3fda73ffe4beb5991adafb8cc562f8fff86778b5f35a8ab40000000049454e44ae426082
);
INSERT INTO `foto` (`oid`, `fotoname`, `fototype`, `fotosize`, `fotocontent`) VALUES
(13, 'piedepagina2.png', 'image/png', 23900, 0x89504e470d0a1a0a0000000d49484452000002e20000003c08060000002348df49000000097048597300000b1300000b1301009a9c180000000774494d4507de0c1a172d36e84df7860000001d69545874436f6d6d656e7400000000004372656174656420776974682047494d50642e6507000020004944415478daec9d677895c5d6b0efdd7b1a0925094948420209109a342902820a91760414b021c88117044445908308a8884ab721d211548a14a9824a0b2d09904212427a23bdeeecbebf1fbefbf908c5d7763c7a7ceeebca45d9d9334f9935b366cd2a92468d1a391111111111111111111111f943918a8f40444444444444444444e48f472e3e82bb239148a8abab432e97a3542a01b0dbedd4d5d5e1e6e626fcbbbebe1e9bcd86442241a3d108bf5b555585c3e1f8f121cbe5e8f57a241289d07655551512890483c120f479eb77140a053a9d4eb80e8bc582542ac560302093c9b0582cd4d7d7e37038904aa568b55ae472f1758afc3964a7a2a202373737a45229128904b3d98cc562c16030603299a8afafc7e97422954a51abd5a8542ae1fb66b31993c9247cae52a950abd5d4d4d460b55a1bf4e5e9e989c3e1a0aaaa0a8d46835aad06c0643221954a057934994c98cd669c4e2732990cb55a2dc8af4b0e653219128984eaea6aec763b1e1e1e423f0e8783baba3aec763b12890485428156ab155fb6c8bf0587c381c964c262b120914890cbe568341a643219269349900fb95c8e56ab452a9562b55aa9adad45afd7a35028703a9d188d46b45a2d95959577f421954ad1ebf5c8e572aaabab71381cb8bbbb37f89ddbe546a150505f5f7f475b2a950aad56db40766fbd361111917b234ac83d301a8d2c59b284c71e7b0cbbdd8edd6e27242484c3870f63b55ab1d96c040606f2f9e79f939b9bcb850b1778f4d14771381c58ad56626262282d2da5a8a8881d3b76a052a9703a9d82c27de2c409f6eddb87d96c1694faf3e7cf0bdfd9b66d1b0a8502a552c9471f7d4466662689898904040450535343972e5df8f6db6fc9cfcfe7d8b163b46ddb56685f44e43f4955551571717104060662b7db319bcd0c1d3a94f5ebd7535e5eceb3cf3e4b7676362525252424243072e44861ec3a9d4ec68c19436c6c2cb9b9b91c3f7e9cb163c7525f5fcf279f7c42616121252525949494505a5a8a5c2ec7cfcf8fcccc4c264e9c88542a452e97f3c20b2f101d1d8dc3e1c066b3f1d4534f91909040666626bb76eda267cf9ecc9e3d9becec6c323333090f0fc766b3515959c98e1d3bb872e58aa080389d4ebcbdbdf9eaabafc8cece262e2e8e850b172293c9449913f9dd713a9d180c06de7efb6d323232484e4e66e5ca95f8fbfb535353c3b061c3888d8d252f2f8fcf3fff1c7777776c361b03070ea4a8a888fbefbf1f87c3815eaf67cd9a35180c062a2a2a282929212f2f8fbcbc3c8a8b8b494c4c24323292b2b232f6ecd9c3f9f3e731994cc275d8ed769e7efa69121212c8c8c860d7ae5d4c983081dada5a4a4a4ac8cccca4a8a888aaaa2a56ad5a85dd6ee789279ee0ca952be4e4e4b07efd7a341a8d28232222a222feeb27436f6f6ff47abd309128140afcfcfc703a9d346fde9ccf3efb8ca4a4247af5eac5c2850b59b4681103070ec4e974a2d56a1932640891919158ad56b66cd9426d6d2d369b8da8a828fcfdfda9ababe3befbeec36eb7e3743a51a9540c1e3c98366dda209148d8b46913a5a5a54c9a340983c1c0e0c183c9c8c8a06fdfbe6cdcb8914d9b36d1ad5b3776efdecdce9d3b69d6ac9938e989fcc7b1dbed040404085639a7d3894ea7a3499326381c0e9a356bc6ba75eb080f0f67c68c19cc9c3993a8a828ec763b3367ce64dab469cc9c3993eeddbbb364c912dab76f4f505010818181cc9d3b97888808a2a2a268d7ae1d369b0d854281d168e4a9a79e42a7d301e0e1e1814ea7c362b1307dfa74a64d9bc6f8f1e3e9d5ab17df7cf30d13274e64fefcf9545656327cf870d2d2d2904aa5848585111212426e6e2e0f3ffc30369b8dbaba3abefbee3be2e3e3e9debd3b93274f263030106f6f6f51de447e77743a1d2b57aec4cdcd8d471e798421438660341ae9dcb9334f3ffd342b57aee4d5575fe5fefbefa7b0b090b367cf0aa73a9999992c5ebc18abd58a542ac5c7c7074f4f4fdab469434444049f7ffe39ebd6ada343870e3cf8e0835cbb768dc8c848828383292c2ce4c1071fc46ab562b55a993e7d3afff33fffc3b3cf3e4befdebd3978f0200f3cf0007e7e7eb468d102a7d3c9f8f1e369d9b225afbcf20a13274e64d1a2454c9b368d3e7dfa60b55a3973e60c0e8743941311919f40f465f83f148ad0d0507af6ec09406060200a8502894442f7eeddc9caca122c01c78e1de3dd77df65f4e8d11c3b760c008bc54265652567cf9e65f4e8d1389d4e4c261393264d62f9f2e5389d4efaf5eb475c5c9ca0fc5b2c162a2a2a387bf62c43870efdf125fdafcb895c2e472693f1e4934fb272e54abefaea2b140a051f7cf0011d3a7460ca9429cc9e3dbbc131ffafd98088fc7d71b94ffdbbfbb0d96c984c26323333c9cdcd452a95e2e6e6c68c193304d9023871e204478f1e155cb6828383e9d0a1030a850280989818a45229b1b1b1180c06c68c19c3471f7d04fc78bcafd168983f7f3ef7dd771f696969a8542ab66eddcad6ad5b057731b95c2eb8cf8c1c3992eddbb7939191c1e8d1a3f9e69b6ff0f4f4c4cfcf8fafbefa8ababa3ae2e3e3193b762c7abdfe371fbb8bf226caceede321383898e0e060060f1e4c6d6d2d12898479f3e6219148484b4be389279ee0f4e9d3a8542aa64e9d4ad7ae5d79fef9e7292a2a62cf9e3d8c1e3d5a18bb2e5cee8dae1fa3d128ac47e3c68de3b3cf3ea3a8a88861c38671ecd8310c0603f3e6cde3befbee233d3d1da552c9962d5bd8ba752b0a8582baba3ac17da6b6b616b95cceebafbfcec30f3f4c5c5c1c2a958a679e7986ab57af326edc38366cd820b88d897222f29f5a7b4445fc2f88cd66a37dfbf6822fa89797174aa552b09c25272763b158041792b4b434c68e1d8b4c2643abd53277ee5c2a2b2bf1f0f060debc7968341a341a0d6ddbb665e6cc99848484307dfa743c3c3ca8acac1426bfaaaa2a3c3c3c983f7f3e5aadb6815facc160a04993265cbc7851f07fd56ab57cfffdf74c9a3409bbddfeabefd765adfcb9ca85cb6ffdafe29beef2ff757373fbcb4cec0e8703b3d98c56abfdb75fb3542aa5a4a4048bc5f26fedc76c36131d1d4d7070305aad96d8d858929292e8debd3b656565242525a1d3e998376f1e3a9d8ebcbc3c366edc88cd66a34b972ef8f8f82093c9003877ee1c006ab59ac58b17b37bf76e76edda25b893b56fdf9eeaea6a2e5fbe8c97971780e026e6720b732df45aad96b66ddb327bf66ccacaca78eaa9a7080c0c2437379777df7d97d5ab57535a5a4a6e6e2efbf7efe7d2a54bbf79d3ebefef2f6c32feecb253535383c160f8cbc8ce1f393f49a5520a0a0a7ef3b3713a9d44454571fdfa754a4b4b85131e85424160602052a99473e7cea1d168801fade7fbf7efa76bd7aeecdebd1bad56cbd4a953d9b66d1b274f9e14ae472291dcf103a0d7eb69d7ae1d13274ec46eb73362c4081a376e4c5858185555555cb972a581dcdcae344924129c4e27616161d86c362e5ebc885eaf17da3e70e0009d3b7766ddba75bfe9b9b8e4449cc3ff1859afabab43a3d1fc61cab1cd66e3e6cd9b7fdb78025111ff09542a15bb76ed62cb962d482412c2c3c3d9b06103f0a3b5fcf6095e2e970b6e26168b857dfbf6d1bb776f3c3d3d3979f2243a9d8e9e3d7bd2b2654b7ef8e107341a0d9e9e9eac5dbb96caca4a4c26137bf7ee65c08001b8bbbb73f2e4493c3d3d1b28e24ea7138944222822aeff53a95458add6df2438ae49eee7b6e10a5cfb2b098fd56abd6be0d29f15a7d389dd6effb72bc7b74e88bfd7e273fb82ed1ac772b99cc4c44476eddac5b265cb3876ec18151515582c16d46ab530beaf5cb9829b9b1b4f3df514870e1d422e97b361c306f6eedd8b52a914dc5e006432195959596cdbb68db7df7e9b8b172f0aeffb5659f9a9e71c1e1e4ec78e1dd9b3670f0e87037f7f7f3a74e840616121efbdf71e414141346edc98b66ddbb279f366468f1e4d7272f2af56f45c41ad7f25d9f92d1bfd3f9a3f7a7efabd94acbb8d59d72992eb44f6d63ed56ab5303fc8e5729292923876ec18d3a64dc36834fea48c464545d1b66d5bbef9e61ba45229cd9a3523222202a3d1f8b3e4e6d66b7619866ebfb6db03ac7f0db7ca893887ff31eb802b50f88fdaac881671919f1422d720714db476bb9df8f8789e7aea293c3c3ca8ababc3e974d2b16347727373b1dbedd86c36525353f9f2cb2f3977ee1c73e7cee5adb7de223a3a9a99336772e5ca15ac562b53a74ea563c78ea4a4a460b3d948494961e7ce9d5cba7489575f7d95152b5634b89eaaaa2a52525218387020972f5f1606f1e0c1833975ead42f9a3cefa618dc2d22febf8ddf6361f84f58f7fe2a48a5520a0b0b090a0a222525450874cecece462a952293c98400e759b366b170e1422e5cb8c0b973e750a9543cf4d0439c397386a3478fa2d3e9183e7cf81df278372bb256abe59d77de213131118944c2d75f7f4d5c5c1c32998cfefdfb73e1c205c16f5da55235b0880374eedc99cf3fff9cafbefa0aa7d349efdebde9d6ad1ba74f9fa6aeae8ef4f4746edcb8c1d1a347193870205dbb76e5ead5abbfc9e2fa538a92283b7f3fa45229972e5d62ca9429040505515c5c2c6c4c737272282a2a62f8f0e1ecdab50b854281c3e160f4e8d1bcfdf6db82bb964aa562f9f2e5ecdab58bcacaca7b6e109c4e273d7bf6e4830f3ee0d0a143381c0ea2a3a3e9d1a307cb962d43a150d0af5f3f2e5ebcd8406e6e9f8b643219a9a9a9949797336cd8300e1e3c28fcfe638f3dc6dcb973856bfbbde4449cc3fffb10157191bb4e523299ec0e6b8a6bd1bd70e1024f3cf104f3e7cfe7f0e1c38486863276ec5866cc9881d56a452e970bdf7decb1c7387bf62c252525180c06e2e3e3c9cbcbc36eb773e2c4099e7cf249bef8e20ba13f8944c2881123387dfa348989895cbb760d954a45cb962d292f2fe7d34f3f65d3a64dd86c36626363e9d3a70f818181bcf0c20b7758254444fe68d46a35ab56ad62c18205c08fee54a3478f66ecd8b1a8542aa452a9201b172f5ee4fcf9f3bcf8e28bcc99338779f3e6b16cd9323efae82332323268d9b2254aa512b3d98c4422213232928a8a0a61a13f7dfab460f5746d96df7cf34d3ef8e00376efde8dd3e9e4c5175fe4e38f3f66f9f2e5e4e7e7d3a953275ab76ecdd4a953d1ebf584868652565646bb76edd8bb772fd9d9d9482412626363e9dfbf3feddab563d1a2456cdcb8918c8c0cc2c3c3f1f7f7172cf32222bf277979799c3d7b96952b57b279f366ac562b83060de2871f7e60faf4e96cdcb891468d1a919595c5b871e328282860dbb66d3cfdf4d3825c9595950956f15b15f15b65cfd3d393b0b030366edc484e4e0e4ea79373e7ce3173e64c0c06032fbdf4121f7ffc312b56ac203f3f9fce9d3b131616c6934f3e29ac852ee54926933175ea5456af5e4de3c68d292a2ae2b9e79e233535955dbb7689a93e45447e029956ab5d203e86bba3d3e9484f4fa7b0b05008ea0284344f274f9ec4d7d7979e3d7b2297cb79f7dd77b97cf9b2909ff5d2a54b188d46aaaaaa484c4ce4fefbefe7f4e9d324242460b7db914aa5545454e0ebebcbf9f3e7d16ab55cba740993c94445458510d11e1212427e7e3ee1e1e1c4c7c7939999c9c9932769dbb62dddba75a3acac8c7ffdeb5f545454fcad7795227f92ddbd5c4e7c7c3c376fde64c08001f8fafaf2de7bef091669b55a4d414101393939d8ed7612121268d3a60d8989899c3d7b96d4d454ba75eb46e7ce9d71381cac5bb78ed4d454d46a35e1e1e1b46fdf9ec8c848dab66dcba1438784319f949484c3e1e0faf5ebc86432626262282f2fe7ca952ba4a5a5d1b3674fa2a2a2282e2ee6b3cf3ea367cf9ed86c36424343494e4e46229108b22d9148301a8db8bbbb73e1c205ae5fbf4ebb76ede8d2a50b128984575f7d959b376ffea613281191bb61b3d93875ea1400bd7bf7262424848b172ff2edb7df72edda352e5cb8408f1e3d68dfbe3df1f1f1cc993347c8995f515141565616369b8decec6ceaebeb3975ea9470fa73abecb962962e5cb820f8d3d7d4d4e0e5e545727232e7cf9f17e4a65dbb76dcbc79934f3ffd54b04ebb64a3baba1a8542416a6a2a972f5fa657af5eb46ddb96b367cff2da6bafdde14e232222d2108958e2fedeb85240b9165b97efb72b68c5e97462b55a05d715575613f831204da9540a1390cd661394ef5b2d09b7b679b7efb8da76f93bba3e77e52b77f98c2b140ab17082c89f4e7e5ce3562693091b59971fbaebdfaeb1ec1adb2e59718d6d975c592c963b5c52d46a354ea753f09f75c994d96c46a15008f2786b9b2e1974e51807502a9542dcc7adb269b55a51281442f0a7286f227f04ae31edf2c977c98f6b2db0d96cc2587629baaef179ab5cb9d6965bd71497ecb9fab8d798bf5d165d72e33a7d32994c77c8c1edbf2f2ae12222a2222e222222222222222222f2a74434e9888888888888fc0911f3678b88888ab8888888c8bf05976b9588884843e5dbe5aae5e7e7f797c8332f2222222ae22222227f212c160bd1d1d1346ad448543444446e41a7d3111d1d4dc78e1d79e49147fe52c56b4444447e39a28ff8bd1eccffe6d496c964bf3907eabf93fafa7a140a45835cc666b319b95c4e7d7d3d1a8d46ccec20f287e32a9fed0a3693cbe542d098d56aa5478f1ebcfffefb74ebd60da55289d16844a552fdaab15a5f5f8f52a9fccde3dc1580e6caf7eb0a4ebb3580fab7b45d53538356abfd5305799acde606f98d2512c9cfbe46a7d389d16844abd5fe9fcfc79585e6f6b94aa421369b8d575e79051f1f1f0a0a0a888b8bc3d7d7972d5bb6fca212f14ea7939a9a9a3bde8b5c2e178a66dd4e5d5dddcf7a9777db54bb728c9b4ca60639bee572b95051d26c360b0910d46ab530c6ec763b269349083475dda72bd8fba7eefbd6f5efd676944aa5905ad49505e95e55702d168b50bc46ad563798476c361b66b31987c3814ea7bbab5c984c26210856a55235d017ec763b66b359b88f5b3fbbdbfd59ad564c2693f06fa9548a4ea7c3e170505b5bdbe0dd28140a341a0d4ea793fafa7a21f0f65eef57e4cf8b6811bf07b5b5b56cd8b081f1e3c7df516df0ffb24edc5af1efb7fcffad9fddeb7bfbf7ef67c48811c2e4a752a958b56a15fdfaf5233333936eddba099fddde8eebef77fbf3e75ee74f5d9fc8df1357019f43870e515252425656165bb66c41afd70b0afaf3cf3fcfc4891391c964d4d4d4f0fdf7dfd3bd7b7721e3c2cf1dff76bb9d43870ed1a54b172193c42f95b15b1591a54b97623299a8a9a921232383f7df7f9f468d1afd645b77939bdb7fa7a2a2828a8a0a222222ee298fffd73dffdcfffbb9f3547d7d3d6fbdf516269309a3d188c964223b3b9ba0a0a09feccff5a7979717972f5fc6cbcbebff7c4f555555ecdebd9bd1a3470ba9f47ee9fdfc5d68d2a4094ea793a0a02061ed69d9b2e52f3a39f2f2f2c262b150575787d96c167ef6ecd983a7a7e71defcb6432919797878f8fcf2fea472693f1c61b6ff0faebaf63b7dbf9e8a38f309bcd188d46cc66333b77eea4baba1a83c1c09a356b282c2ce4f4e9d374edda15bbdd8ec3e1a057af5ec4c5c5919393c3f6eddb852ad193264d62fbf6ed77557e5d553b0f1f3ecc902143703a9d3cf4d043646464505252c26bafbd86c562c16432b172e54ae1ba6e47ad56337ffe7cf2f2f2b87cf9320f3ffcb0f0cca552294f3cf10449494998cd665ab66c79c7d854a9546cd9b2859b376f929b9bcbac59b304f9b6dbedf4eedd9b3367ce60369b79fae9a71be45d9f3c79329b376f168c1526938989132762369b85f776e5ca15d46a35515151582c168c46a3f06c376dda445555157abd9e03070e505d5dcd9123470808081017a1bf18a269e2272c38772be96cb3d9e8dfbf3fa74f9fc662b110121282542a252d2d8d162d5aa056ab69d4a8113a9d8ee4e464f2f3f305cb5ae7ce9d31180ca4a6a69295958542a1a075ebd6f8fafa5257574742420255555582b0cae5723a75ea84a7a727269389b8b8382a2b2b8589492291b06bd72efaf5ebc79e3d7b801f8ba7f8fbfb939a9aca4b2fbd446a6aaa50bab855ab56381c0ee2e3e3292d2da54b972e141717d3a2450baaabab811f2b770604042093c9b87cf932959595482412743a1d1d3a7440a552919090406161215aad96b66ddbe2e5e545656525090909188d467137fe37c76c3673e8d021e6cf9fcfa38f3e8ab7b7374f3ffd340683415878376dda84a7a727068381baba3a4c2613eeeeeef4eddb1787c3415c5c1c35353574ead489c68d1be37038484848a0a8a808005f5f5f222222046b974b4edbb76f4f93264d282828e0dab56b582c169a376f4e4444044ea793949414f2f3f3ef29f32a958aa953a7b27efd7a5ab468c1ac59b358b76e1da3468dc26ab5e2e7e747ebd6ad319bcdc4c5c551555545870e1da8a9a921202040b8cee0e0601a376ecc8d1b37b87efd3a3a9d8e2953a6088aae56abc5cbcb0bbd5edf609e50281474e9d2058d4683c562e1faf5ebe4e7e7e3efefdfa0dfeaea6a3c3d3d69d7ae1d3a9d8ec2c2425252521a6cc85bb66cc98d1b37841383d0d050b2b3b3a9abab6ba048bcf8e28b7cfae9a7425a3a578aba2e5dbae0eeee4e7a7a3a19191968341ae199376fde9c9898185e7bed352a2b2b090b0b23303010a9544a565616e9e9e9381c0eb45a2d9d3a75c262b1a0d56a858d56505010a1a1a1d4d5d5111717475d5d1d3e3e3eb46bd70ea552496e6e2ee9e9e9771841fe0eeb4e6c6c2cf7dd771f168b85fefdfbb37bf76ea180d5cfa5a2a2023737376a6a6a888d8d65c182051c3b764c3895ead0a1034d9a34e1e6cd9b24252509727b7b1f0e8783d6ad5be3743a494b4bbba39f962d5bd2a64d1b264d9a84cd66c36834f2e28b2fb27cf972341a0d4aa512bd5ecf2bafbc42595919fefefe0c18308079f3e6f1cf7ffe93cccc4cb66cd9c2238f3c424c4c0cdbb76f67debc79cc9a358b952b573260c000860f1fcef6eddb1b14cf9248243cf6d8635cbe7c9943870ee1e6e6c6ecd9b379eaa9a7484a4ae2cc9933c4c6c672f8f061264f9e4c7c7c3c0f3df410478f1e6dd0cec89123090808a065cb96848787b37efd7af2f2f2888b8b63fcf8f18c1e3d9a471e7984e2e2e2bba66274381c1c397284a953a7623018b878f1223131317cfffdf73cf2c8232c5ab48809132690949424a45a95cbe58c19338609132690909070c789c5c68d1b79f6d967050bba52a9e4ead5abb8b9b90927f5efbefb2ed5d5d558ad5656af5e4d4242028f3efaa8f05ce7cf9f7f87ee22222ae2ff35188d4676edda456060205555558c19334658cc860e1dcad0a1433973e60c5e5e5e8c1c399259b366515a5aca9b6fbe898787077979794c9e3c99891327121616c6f8f1e3b976ed1a3e3e3e28954a8e1c39224c1472b99ceeddbb63b158502a953cf3cc333cf1c4130d8ed8ce9f3fcfb061c3f0f7f7273f3f9fa8a8288c46234949491c3d7a94d1a347a3d7eb993d7b36b9b9b9a8d56a468c18c1f8f1e359b46811f5f5f5141717131313c3a04183904aa55cbb768d962d5b929090c0f2e5cbb1d96ccc9b370fabd54a75753553a64c61ecd8b1fce31fffa07dfbf6141515e1eeee8ec562e1d2a54be2d1f3df18a7d38942a140abd50a6e0b3939392c5cb8103737379a376f4ee7ce9d31994c180c06860f1fce840913b0582c8c1a358a8282024242423877ee1cab57afa6478f1ec864329c4e27cf3df71ce3c68dc3c7c78737de7843b0327b7979515f5fcf98316318306000a9a9a90c1f3e9cbd7bf772e6cc1956af5ecd993367707777a779f3e66cdcb8f127ef41ad56a352a9282c2c64ead4a95cbc7891be7dfb72e5ca155e7ffd75418e468e1cc9b3cf3ecbabafbe8a542a25252585d0d050a4522999999928140a264d9ac4d4a953494e4e66f5ead574efde9de8e868468e1cc9e9d3a7f1f2f262d4a851cc9a358b9292125e7ffd75020202484e4e66ecd8b1ac5ab58afdfbf7f3faebaf939797874aa562d4a8514c9f3e9d975e7a09abd58ac562a173e7ce5456569293932328d3b367cfe6830f3ee0dcb973f8f9f9f1faebaf336ddab4068ab8cbe0505757874c2613ac910b162cc0d7d797cccc4c264d9ac40b2fbc804c2663d7ae5d1c3e7c188542c1f5ebd799376f1ea74f9fa67dfbf6f8fafa62b158183e7c38ab56ad22313191f9f3e7e3e9e9495a5a1a7e7e7ed8ed76dab469c32bafbc42464606eeeeee0c1c3890458b16b168d122727373914aa5b46bd78e8d1b37525656f6b7921f994cc6a953a768dbb62d2a958ae2e2621212121ad4b3f8b9b8f2e8bbc68342a1c06ab5f2dc73cfd1b3674f929393193972245f7cf105070f1ebc6b1b56ab95b163c7e27038844ab9b79e7c0d1f3e9c93274f525c5c8c542a45abd5f2e4934fe2efef4f4c4c0c870e1dc2d3d393909010162d5a845aad26363696bcbc3c828282c8caca22333393c183079397978742a120313111994c8652a964ce9c39ecddbb97cd9b373750a0f57a3d9d3b7766c78e1dd86c369a356b86542ae5fcf9f3d86c36366edcc8e0c183d9bf7f3f1a8d86193366f0de7bef71e0c001a11d854241444404c78f1f170a817dfffdf774e8d081f3e7cfb360c102366edcc82bafbc424e4e0e9f7cf2c91da70556ab956ddbb6a1542a292828a0b4b4148d4683d56a65e6cc997cfffdf73cfdf4d3d4d7d7b37efd7a0a0b0bb1d96c7cf8e187141515111d1ddd60ee944824f4e9d387e5cb97939191c1ae5dbb04638342a1c066b3d1b16347a2a3a3e9debd3b1e1e1e74efde9de9d3a72393c9387cf8306fbcf10632994c54c44545fcbfdfe2e7129a5b8fbb9c4e27c78e1d63f5ead5e8f57a962e5d8a9f9f1f1e1e1e0c1e3c58b048af5ab54a384e0358b2640952a9143737b7063e6466b399c4c444c68d1b478b162d080f0fc7cbcb8b9a9a1ac12a5e525242555515a1a1a1646565316ad428f6efdf2f1c7501f4ebd78fa4a424962c59825aad66f3e6cdb46fdf1e8bc5c2e6cd9b397efc389595950c1830807dfbf6b177ef5edab76fcfc48913d16834040505111c1ccc830f3e08c0f6eddb79f4d147e9d0a103d9d9d92c59b204ad568b46a3119570f124098944226c42df7cf34d2a2b2bf9f0c30fd9b469132525251414143062c40802020290cbe5e8743a140a85a0e4b56ddb96575f7d950d1b3670faf469264e9c48444404515151f8fbfbd3a953274a4b4b993b772e1289844e9d3ae1ededcdb871e378e18517484f4fa75fbf7e44474753565646b76edd98306102b9b9b9787b7bffecfb706d00e2e3e3e9d4a9130683819b376fb260c102d46a351b376ea477efde58ad56f6eeddcbdebd7b79e081070405baaaaa8a65cb9611111141525212f5f5f5823bc0b163c758b56a153a9d8e77df7d173f3f3f41296fdbb62dd5d5d5b468d1029bcd469f3e7d0425ddd5ef238f3cc2fdf7dfcfe2c58bd9c123f8ea0000200049444154bb772f9e9e9e82d205505959c9e9d3a7e9d7af1fe7ce9da367cf9e242525919f9fdf608ea9abab63debc794c9b360da552c9279f7cc281030718356a1461616158ad56162f5e4c747434fbf6ed23313191458b165156568656abc562b12093c9387bf62c93264da24b972eb46fdf9ec3870f63369be9d3a70f3d7af4a0b6b6960e1d3aa0542a79e49147b870e1029f7cf2099e9e9eac5ab58a3e7dfad0a74f1fc68c19c3a953a7f0f6f6fedbbaa8141515f1dd77df3168d0205ab46881bbbb7b83cdd32f95c55b719d4e0d193284828202860d1bc6030f3cc0d1a347efda8652a964d9b26577fdcce170d0b3674f66cc9821588b77ecd8818787072a958a37df7c13b55acdfefdfb292d2d65f4e8d1bcfaeaabf8f9f9e1ebeb8b4c26432693f1fefbef337ffe7ca2a3a3319bcd7cf1c51782b129313111b3d94c444404191919c26644a7d309a735003e3e3e42bc834aa5a2b4b494ae5dbb0a8681d8d858e4723981818194949420954ab1d96ce4e5e5111d1dcdae5dbbd0ebf504040490969686afaf2f8d1a35223e3e9ec2c242a64d9bc63befbcc3d4a953eff03557a954545757f3e28b2f525d5dcdf1e3c7f1f1f1c1d7d7973d7bf670f5ea551e7cf041de7df75d9e7ffe79211ee676df77a552c9c99327c9cbcbc366b3317af46882828258ba7429369b4db086af59b38637de7843384592cbe55457572393c9a8ababc36030880bd15f0cd147fc574c6e3ff5597979b93021d4d6d622954a090d0d253838988c8c0cb2b3b319376e1c8d1b3766e7ce9d28954acacaca3878f0a0e043eb52ea3b74e8c092254b78f7dd77e9d0a103d5d5d5774c02555555c4c5c5d1a3470fbcbcbce8d7af1f7bf7ee151475a9544a505010afbdf61a595959a4a6a6d2ae5d3b2120aba0a080dada5a41d04b4a4a04df42d7b170ab56ade8d7af1fd9d9d9646767d3bf7f7f341a0d1f7cf00103070ea4baba9a0d1b36a05028c40c1822c8e572ce9d3bc7638f3d46870e1d983163068b172f66d0a0410c1d3a94c71f7f9c975f7e99eeddbbe370388460c8d2d2526c361b252525c864327c7d7df9e69b6fd8b3670ff7dd771f292929a8d56afcfcfcc8c8c8c06ab562b3d9b0d96c8255bcb4b414994c465151116e6e6e242727b37cf9722e5ebc486666265dbb76fdc516fec68d1b73f3e64d9a356b465e5e9e500d372b2b8be6cd9b0b1b629bcd467d7d3d656565d4d7d7e37038ee2ab31289848a8a8a3be689909010b2b2b204773097fc356dda54e8d76c369399998956ab65e1c285bcf9e69bd4d4d4307ffefc06beb432998c23478e3060c00024120943870e65fffefd77f8db6a341ad6ad5bc7c8912319316204dbb66d232828085f5f5fae5fbf4e76763653a64cc1d7d717a7d3495d5d1d6565658231c2e170a0d1683870e000376edce0a1871e62c58a15c864325ab76e4d464606d5d5d50daa38babbbb53505020281625252558ad56fef5af7fb171e3462a2b2b993c79f2dfb672a9d3e924262686d4d45464321963c78e6d1050fb5bdaf5f1f1c1c3c38373e7ce919393c3dab56b090a0abae7b396482454565652595979d7f69a3469425e5e1e52a954b0481f3972842fbffc920f3ffc9009132660341a79f3cd37090e0ea6bcbc9c1d3b762093c9a8aeae46afd7b362c50a7af7ee4d9f3e7dc8cdcde5d34f3fa5b6b656e83f3d3d5d70fbba758e91cbe582ac984c26e11a5c81a3f5f5f50dae352f2f8fa64d9b365863b76edd4a4545057979791c3d7a144f4f4f4a4b4b0583d2975f7e496c6c2c4b962ce11ffff8c75ddf83cd6663d4a8513cf7dc730c193244d860a8542abefcf24be2e3e359bf7e3d0101013f19782a93c9484d4de5d0a1431c397284952b5772df7df7091b67abd5ca638f3d86c964e2ebafbf46ad560bd5865dae4b0a85e277192b22a222fea79b146f0f6c311a8d3469d2048d46734720d7dd842b333393f4f474c2c3c3090a0a22303090f7df7f1fa954cad8b163d16834e4e4e4306fde3c8c4623f0e3b15f545414274e9ce0fcf9f3346bd6ecae479352a994989818222323f9e73fffc9c18307a9aeae16360c0e87839c9c1cde7efb6d42424268d1a205ddbb77e7dcb9733feba8d3e5ff7ee2c409e1fb2d5bb664c78e1d949595d1bf7f7fbcbcbc5028143cf7dc73e22420ca0b7abd9ef0f070f47a3d56ab95989818e2e2e2080c0c24282888ddbb7773e3c60dfcfdfd858513fe7fb9fa8080004a4a4a68dbb62d494949ecdebd1b0f0f0fe1f3b2b2329a376f8e42a1c0cbcb0b8d46231c09376dda149bcd86afaf2fb5b5b5429098979717f3e7cfe795575e11e4e25efec7ae72f672b99cdebd7bd3aa552b76eedc49595919818181822fb94b71fe3d62225cbed5cd9b3747a7d3a152a9d0ebf5001416160afdaad56a424343292929e1e2c58bb46ddb96f0f070a2a2a2e8d5ab97a0ac48a55212121228292961dab469787a7a72faf4e93b64dea568b936d935353564676793979747444404414141040404b078f1e2bb669d703a9d848686525151c1ba75eb904aa5346ad408a9544a767636818181e8743a0c06037abd1e9bcd46656525010101389d4e743a1d4d9b36a5bebe9ea3478f121a1a4acf9e3d79e8a187080f0fffdbca516d6d2d7bf7eea5b4b494909010860c19228c5b80d0d0507c7c7c50a9543fdbf8e1daec565454d0ab572fe1ddba02045def53abd512181828ac798d1a35bae73a67b7db0519763a9df8fbfb0b4a72484808376edc402e9773f3e64d468c1881c16060ca9429e4e5e5919595455050101289448813f9e69b6f080b0b13dc2a5ccae5bddc2c241289e00aa6d1688440c59e3d7b72e5ca9506e3dd9559e57643d63ffff94f0c060343870ec56834929090407e7e3e959595f4ead50b8bc542b366cdc8cacaba63c322954a193a7428cf3efb2c23468ca0aaaa0a80f2f272f2f3f3e9dfbf3f56ab153737372a2b2b85fe5d738cc3e110ea29381c0e9a366d8a46a3017e8c83292f2f17deaf56ab65c284092c59b2443881282b2ba3a0a0803e7dfa60b55a090b0ba3b0b0503488fdd58c57e223b8b742e15294dbb76f8f4c26a3b8b89865cb96b16ddb3656ac58c1952b57e8ddbb37274e9c007e3ca27229151289440856494b4be3d0a1434274b5c16060dfbe7d787a7ad2bf7f7ff2f3f3d16ab57cfef9e782ff9a4c2623262686279e7882d5ab5763b7db71777717aeedd689e0f2e5cbe8743a5e7ae925faf4e98346a3a1a6a606bd5e8f4422e1f8f1e3cc993387356bd6e07038309bcdac5ebdba416a43d704ecb204c86432c1d5e4ead5ab646666b261c306aaabab51abd5ecd9b387c8c8485ab56a456969297575759c3c7952744d11e506a552c982050b282b2ba3aaaa0a9d4e474d4d0d5f7ef9253d7af460ca9429b46edd1a954a854ea713c6db33cf3cc3430f3d445050103b77eee4e4c9934c9d3a9575ebd6515b5b2b2cdaa74f9f66e8d0a1ac5ab58adada5a9a376f4e5555151b366ce0b5d75e233333137f7f7f76ecd841d3a64d79e38d37c8cbcbc3dbdb5b70db8a8a8a223434947dfbf63558f4a55229a3468d223c3c1cad568bafaf2f73e6cca1aaaa8a1f7ef8813e7dfab074e952944a257979799c39738637de78a381dcdc6af572055c399d4e0c060352a9b481f2e452ae552a15b1b1b1c4c4c4b069d32672727268d3a60d274e9ce0871f7e60e0c0812c5dba148542417e7e3e57af5ee5e5975f162c7f376edc202d2dadc1a640a552b172e54af6eeddcbf3cf3f7fd78db7ab6f9735d1a5d4ecdcb993cd9b37535050809b9b1b870e1d222525059d4e27f421914830180c5cbf7e9dc2c2423efef863cacbcbe9d7af1f57ae5c21313191f4f474e17e222323713a9d7cf3cd37cc99338737df7c5308562d2828e0f5d75f1752c5b95c02feaec86432f2f2f2f8f2cb2f79fcf1c7e9dbb72f2a958a989818aaaaaa84e05dd7c9ccb56bd7ee995d44a7d32197cb05457cf3e6cdac58b1829c9c1cf47a3df1f1f16cdebc1983c180dd6ea7478f1e2c5bb68cd6ad5b033063c60c9c4e27f3e7cfbf4309cdc9c921242484b8b838ec763b8b162da2a2a242d8a0b95c9e02030319376e1c6e6e6e787878b065cb16617ef8f6db6ff9f8e38fc9cccca463c78e2c5cb810ad562b8cb1366dda909292d260fcba32a2787b7b535050404d4d0ddf7efb2d4b972ea5acac8c66cd9af1ca2baf089b47b95c4e707030d9d9d90d9e93b7b7374f3df5144d9a34a151a346ecd8b1831b376ea0502878f9e59759b06001b1b1b1444444083eeeb79f306cdab48943870ef1c20b2f08ebe5a79f7ecac2850b9933670e9d3b77a6499326ecdbb74f88c5888e8e66cc98318484843077ee5c76ecd8415a5a1ae3c78f1736a60101016cddba55306e3dfae8a3545656121f1f2fc8a0cb0568c68c19f4eddb97962d5bb261c306d13ffc2f869847fc1e381c0e828383f1f7f7177c2f8d4623f1f1f128140adab56b87c9641276f22e6b96c3e1a0a8a848f0472b2a2aa2b6b6168542416464246e6e6e188d4652535391cbe5848585a152a9282929213939b9c124219148080f0fc7dbdb9b9c9c1c1a376ecce5cb97b15aad0d165cbbdd4e686828cd9a35e3cc9933c2b176e7ce9d494d4da5aeae8ea64d9bd2a2450be47239a5a5a5a4a5a5d1ba756b727272a8a9a9c1e17010111141515111959595e8743a9a356b46767636369b0dad564bebd6ad05253f2d2d0d77777782828290cbe5141414909e9efeb73d4e166988afaf2fbebebe68341acc6633292929545656a252a968ddba35068381ecec6cbcbcbcb872e50aad5ab542ad56e3e6e646454505a9a9a9984c268283830908081036b0c9c9c9c222d5bc7973c1df33373797dada5a22222268d4a811c5c5c5a4a7a72397cb85fe8c4623c9c9c9984c26162f5e8cd96c66c18205c202ef7038042ba14c261352bae5e5e509f2d6a44913424242b0582c24252551535343646424858585545555e1e6e626c8abc3e120303090eaea6a8a8b8be9dab52b090909346ad408a7d34951511132998ca0a020619ed06834444545515656c61b6fbcc1c68d1b3978f0207e7e7e8486860afdd6d7d7d3a2450b9a356b86d3e9242b2b4bc8bc72ebbce0efefcfa953a768d1a2859015e5f639aebebe9ea2a2a2069f49a552222323717777c7643291969686d96ca6458b16a4a5a5e1703890cbe5b46ad58ac4c4447c7c7c080b0b13d220969494505a5a8aa7a727ad5bb7162c85454545949494101010406060a010586eb55a69d9b2253e3e3ed8ed76d2d3d3292929f9db6760b2dbed346bd68cb163c7e2ebeb4b717131d5d5d568b55a32323284f776e6cc99bb6eb4ec763beddbb72727274770e3703a9d8487870bcf3a2323839b376fd2a95327ae5cb9829b9b1ba1a1a19c3f7f5eb0be3b9d4e323333ef5823a74c9942a3468d98376f1e2a958ad0d0509a366d0a40666626f9f9f9c286ad4d9b3600e4e4e4909b9b2bc460e8f57a61635e5454445a5a1a32990c8bc542efdebd79ebadb7e8d2a58b7042043fba542d5cb89043870ef1edb7df22954ad1ebf5444646a25028484f4f272f2f0fb95c8ed96c66f8f0e13cf3cc330c1a34a8413b6ab59ac8c848d46a357979790dfcd09d4e27111111787b7b73f3e64d525353ef188f2a954af045bf356ecb950d252424047f7f7f6a6a6a842c4e128984e0e0609a356b2628cc898989545656e2e7e747f3e6cd059d203d3d5df81d3f3f3f24120979797977bc87c8c8487c7c7c282b2b2325254554c445455c444444e4cfcd9e3d7b9833670e4949497f9a825776bb9d810307929c9c8cc160e0abafbea27bf7eed4d5d5fd2a85d46834b278f162a45229f3e6cdfb45056144fe5c787979316edc387c7d7d85d314972ff4b265cb0485f73fb1e15eb972252fbef8a290b1e7f7c26c3673e6cc19e6cc99c39933671a9cb63a1c0ea2a3a319387020b367cfbe6b8e70d733b2d96c5cbf7e9da1438772edda35f1d456e44f8768be141111f9dbe0525eaaaaaab874e9d29faaeaacdd6ea76bd7ae1c3d7a94cf3efb8c2953a6fc6a25dc759f5dbb7665eddab5f7ac2a28f2d7a0b4b494cf3efb4cc8cfeecaea63341a292d2dfd8f9d1c14161672f0e041860f1ffebb2ab8369b8d09132670f2e449ce9e3d7b47db52a99483070fa25028e8ddbbf73d7da22d160b6fbdf5169f7cf20957ae5c119570913f25a2455c4444e46fa78cd7d6d6e2e6e6f6a74b9167341a85e36b578cc96fc19599427419fbebe37038904aa5f4ead58b8e1d3ba2d3e93870e00057af5efd8f5e97abacbc2bc8f0f7c26c3663b3d9843892bb515b5b7b4759f9060aceff0682c28f290fc50ad022a222fe5f84cd66c36c36a3d56a05bf3b577ed0df73d72d9148a8adad452e97ff6aab96d56ac568340a93e54fb553575727e406d6e97482c5d066b351575727a42bd36834bf6a52731520b97572753d3b8bc522e495be97e260b7dba9adadc5e170a056ab1b4cfef7ba76913f17f5f5f54290a24bc1706538f93d2d7bae34817abdfe37c9a42bfff7ad0bb94b99b7d96c28954a611eb89742e14aa5a6d56a85a0ee5fd2c6bdeeafbebe5e0870bcbd8d7bf57baf8d89cd6643a1503408c87495d5861ffd725d996b44feb36b4fe3c68d51a954dcbc7953cc902122f217473493fc4aeebbef3e3ef8e00321f043a7d30995f37e4f8c46239f7df6194f3ffdf4af5e001f78e001929393b971e3869087f46e8bb1dd6e67cd9a3554545470fcf87182838385493e2a2a4a48873675ea546171fea5168edebd7bf3d1471f098a912bcbc6dab56b292e2e66e7ce9d0dfc206f27343494b8b838cacbcb79f9e597857bb1dbed2c5bb68c8a8a0a4e9e3c49ab56adc480953f21168b85975e7a89a953a70aefdfcfcf8f23478efcee3ecc1515155cbf7e9d1e3d7afcaa52e94ea7939a9a1a66cd9ac5db6fbf2d8c79d786f6d0a143545757f3c1071fdc53d1773a9d3cf1c413e4e6e692999929a44873b98e1c387080eaea6a3ef9e4935fbcd1aeafaf67ca9429242727535a5a2a644272f53b7af468727272c8cccc64d8b061779559a7d32954ccacaeae66ebd6ad424110a7d3c9b061c3c8caca223b3b9b912347fe2ab917f97d91cbe5949797535050202ae12222a222fedffc64642091fee464e8b2eaba1464579a28d7e2e6e9e9899797578392ba7abd1e83c140a3468d90cbe5787878d0a851233c3c3c84efeaf57ad46a351e1e1ef8f8f8b06cd9328e1e3d2a94b9f5f4f4c4d3d353488be6743a717373bbe368d0955ee9fdf7df67dcb8710c1a3488f7df7f5fc839ea743a85eb33994ccc9e3d9b264d9a101e1ecec1830779e9a59784aa87efbcf30e6fbffd365dba7461d6ac59f4eddb17abd5fa8b1ee9942953d8b16307cd9b37172c6e56ab95356bd6505c5c4c707030172e5c60c18205c206c75500442291e0703858b972256bd7aea55dbb763cfffcf33cf4d043d4d4d4306dda3482828268ddba359f7ffe39b367cfc6cdcd4d1cc7ff0124b29f76a77059561d0e87e0efeae6e6268c09a552899797179e9e9e824ce8743a341a0deeeeeeb8bbbba3542af1f4f4a451a346b8b9b909b98c5df9c63d3d3d69dcb831cf3efbac50325bad56e3e5e58587874783cc082e19b87da3ebe9e9c9c68d1b993b776e8313a0baba3ad6ad5bc7b56bd768d1a205c1c1c13cfef8e3d86c36417e5de9d722232379e6996718366c18a3468d62f1e2c5444646525d5dcddab56bc9cccca4458b16346edc98b163c7fea2cda3d3e9c4dddd9df1e3c7d3a95327c2c3c379fcf1c7850c48e3c78f67c488118c1c3992458b1611191989dd6e17ee592e97535757c7071f7c4045450541414158ad56c68f1f0ffc98f161f2e4c98c193386a14387b268d122dab56b276e70ffcd68341aa152f14ffd68b55ab45aed3d37b0ae3cfbdededec2cfefe13ee2ea17c0c3c3e34f93d946a15008d7f5736447a954e2e1e121fc88a73d22ffb1cdb5f808ee2aa5c8dd9a21d57a62c9bf0a7799685c96bcc1830763329950a954f8fbfbe37038b0dbed4c9e3c994183060911db73e6cca16fdfbebcf6da6be4e6e6d2bc7973264d9ac4cc9933f1f4f4a4bebe9e4b972eb160c102bef8e20bcacbcbf1f5f5252525056f6f6f0e1c38c0debd7b993e7d3abd7af5c266b3919090c0ebafbf4e4d4d0ddf7cf30dc78f1fe79d77de11147fbbdd4ef7eeddc9cecee6e2c58ba8542a962f5fced8b1633971e204b5b5b51416163265ca14b66edd4afffefd9939732626938923478ed0ab572fc18fb6499326ecddbb1793c9c4471f7dc4e8d1a3397efc7803df3c9bcd2628e772b9fc0ebfbd55ab56111b1bcb8409138467d8b46953c2c2c29831630672b99cafbffe9a9e3d7be2e9e989d168a473e7ce2c5ebc9851a34661b15868d5aa155bb76e154afd8e1d3b96afbffe9abe7dfbb264c9128c4623274e9c60c08001f8f8f8fcaad2d022bf01870d855f7b1ca66aacc5a9486477fa6eda6c3622222218366c184ea7136f6f6f0c068390cbfee5975fa643870ed86c362e5cb8c0fcf9f359b162051e1e1e42618ceddbb7f3e4934fa256aba9adad65d7ae5d6cd9b285acac2c76efde4d5454146bd6ac61f1e2c53cfef8e3e4e5e5f1da6baf0929004f9c38c1871f7e48555515f9f9f9bcf0c20b6cdfbebd8155baa2a2823163c6307bf66c4242428431ab56ab79f8e18769dab429b5b5b5ac5bb78ee79e7b8ecf3efb0c8bc5c2dab56b292c2c64fefcf9848686929292c2f5ebd771381c1c3a7488debd7b939090c0a0418368debc397575756cd8b0817ffef39f7cf2c9270d5caa9c4ea7e0ca2393c91af88c6bb55ade7aeb2d140a0566b3999898189a34692228d1696969a4a5a561b7db3978f0200f3cf00029292958ad5652525218326408c78f1f67d0a041b469d386fafa7a3efffc735e7cf14556ae5c4960602079797924262662b55ad9b3670f03060c10363622bf3f369b8dc99327d3bc79734c26d34f2ab9ae9399ebd7afb37cf9f20663c3e974d2ab572f3efdf45332323284c23b1f7df4113b77ee14dc006d361b52a954a8c8e852505defd7e5f6e43202399d4e468c1801c0860d1b888f8fa773e7ce9496966232991a7cf7d66bb15aadd8ed762412094aa512a9542a54c675f52f93c9a8afaf47269309959e552a956084b9fdfa5c45715ca7b943860c61c08001cc9a354b58835c95775d8aba4c261352653ef1c413f8f8f808f777f3e64df6eddb474242024aa512a3d1f8b39e91dd6e170c5bae75efd67e5d058e6ee55edf71a52676b5efaa18eadafcba7ecf953ef4f635d6556554a15020954a85c2654aa552589b5ddfb1582c48a55231805554c4ffac263d09d6f22ce4761beae09ed4a77d8b4471a7b5d96030d0aa552bcc66334aa512777777ec763b0101014c9e3c996eddba515555c5be7dfb888e8ea6bcbc9ce2e262de7aeb2df2f2f2a8afaf67dbb66d74edda95b0b030a64c99c2d2a54b311a8d646767b378f1620a0b0b59b66c19168b85f0f070060d1ac4800103b0d96c1c3e7c98fefdfb73e0c001fef5af7f515e5ede40a8a45229376fde24383858a8d2a5d7ebf1f0f0c0e170a0d7eb79fcf1c7b976ed1a5e5e5ee8743acacbcb914aa5c2e6422693d1b469534c2613f5f5f5a8542af2f2f21a54f173f1e0830f326edc381c0e07fbf6ed63f7eedd0d1607b55a2d14197251575787cd6663c080016cddba158d4683a7a7a730c95dbb768dd9b3675359594958589850125ca3d1909f9fcfb061c3d06834e8f57acacbcb85fcb3b72b2d227fd449921c73f605944d5ba30eea8e29e3d41db2e37038f0f1f1110a86b8bbbb0bfecb2ecbeed0a143512a95ecdfbf9fc3870f535b5b4b5d5d1df3e7cfa7acac4c283815151545a74e9d18397224dbb76fa7aaaa8a2b57aef0f6db6f939b9bcbecd9b371381c0c1c3810bbddcea38f3e8ab7b7375bb66ce1d4a953c4c7c73366cc18525252eebaa8b92c93b75ebb2b4f724545056ab59a929212219fb74aa562c58a15c222d7a44913aaababb1dbed28140acacaca046bbd4422a1bcbc1c954a4569696983f2dbb75aeb972f5f8ec562212e2e8eb56bd736485fe7b2d4fbfbfbd3bd7b77215d6193264da8a9a9112a84969595e1eded2d281123478e24292989a64d9b0a9f2b140a2a2a2af0f6f606a059b3660d7cc78b8b8bf1f1f111ad86ff4ef1f9df026df5f5f5040707ffa44b9552a924292989ebd7afdf33a666c78e1dcc98314370e1d36834482412de7bef3d4e9d3ac5e0c18349484860cf9e3d4c9b360d3737373ef9e413411e5e7ef965c2c2c2a8aeaee6c30f3fe4dab56b94949408e9022b2b2b851883356bd6f0f5d75f73faf4e906b2a456ab193f7e3c5dbb76a5b0b090f5ebd7939191c13ffef10f1e7ae821b2b3b3f9f8e38f29282860f1e2c5c4c5c5f1f0c30f535a5a2a14ed0a080860dab46928140a3efef863121313b9fffefb69debc39fefefe8487872393c9e8d0a1035bb66c61c78e1decdbb78f6eddba3166cc189c4e271f7ffc31d7ae5da371e3c6bcf5d65bac5dbb9666cd9a11111121e4ae7feeb9e758ba7429d9d9d9bcfffefbc233ba7af52a5f7ffd352fbcf0020683818f3efa4828a0151212c2f3cf3f8f5eafe7db6fbf15d28e8e1d3b168003070e70e4c89106eb5158581813274e44a3d170ecd83176edda45fffefd19356a14151515ac59b386acac2ca64f9f4e464606fdfbf7c762b1b07efd7a1e7df451dab66dcbfefdfbd9b76f5f8335bfbebe9eb973e7f2f9e79f535050406868289d3b7766d7ae5d44474763b55ad9bf7f3f128984d1a347535d5dcde1c387ef19ec2af207c9bdf808eea18b4be5d8ab0b31679f4717f5184ebbe58e0933353595152b56b074e952962f5f4e7272324ea79376edda919e9e4e51511176bb9d13274e70fffdf7e37038484d4d25353595aaaa2a7af5eac5ac59b3888989e18d37dec0643209d52d4f9d3a456e6e6e8392f7ae0a63555555984c26befbee3bba75eb06406c6cec1d2578a55229f1f1f1ecddbb97d8d8582e5dba44b76edd2828281076f8df7fff3dc5c5c58292716bd53cd7827f7b01219755e176ae5fbfcef6eddbd9b163c7cfb29ab922dae7ce9dcbfffccfff909b9bcb9a356b90482442fb9595955cbc7851b09eb8aec3e5fee37207b87553e00a9e151586ff90ecc895584bd2b196a6a36ed90f1c0d95098542c1c9932759ba7429efbcf30e1f7ef821a5a5a542a191e4e464cc6633b5b5b59c3d7b968e1d3b229148888989213b3b1ba3d1c8b469d378f0c107d9bf7f3fcb972f172c3b32998cefbefb8ea2a2224159904aa50404049094942428d0898989c2e2fdfdf7df73f3e6cd9f9559c4552ccbf57757fbaeff93c964242525919e9e8e442211ac80b7cae4ff6befcca3ab28d2feffb9b7ef924b72934080202104821959121320109025832041417670400474401940445ee528fb6240590485a308e372244a4810082048588d124cd81192b066c1603612b27093bbfffe787fdde7e626619879c71966accf42a7062400001cba49444154391c72bbababababbbbabf55f5d4f3c8b3660de5e17a2e93c9445c5c1cf1f1f19c3871a2c167fadd77dfe5f0e1c34ad43d77f311d75135b55acdb163c7282f2fc7e170e0743aeb2d877b795cf310fc46134a0e07bd7af5e2cc99335cb9724579d7b9ff53abd55cb87081cccc4cfaf6ed5baf60b7582c8c193386acac2c6edebc494e4e0e53a64cc16432f1a73ffd89112346b073e74e860e1d4a7c7c3c67ce9c212f2f8fe9d3a72b0b93d3d3d379e79d77888b8b23212101abd54afbf6ed090909a913542e343494c68d1bd77a46552a1583070f26343494f7df7f5f31e71a366c1843860c61d3a64d949494b076ed5a1c0e0743870e65ecd8b1ecd9b38756ad5a3163c60c542a155bb66c212d2d8d5dbb76111f1f4fe3c68d090c0c2436361683c1c0b163c7484949212d2d8d77df7d97b4b4343a76ecc89c3973484c4c64cf9e3d6cd9b205bd5ecfb469d3d8bc7933168b853e7dfaf0c1071f101e1e4e6e6e2ec9c9c9f4efdf9feaea6ac68d1bc7b061c3f8e69b6f18316204dbb66de3f4e9d3e4e7e7337dfa74c54467c3860d9c3d7b96afbffe9adebd7b131c1ccc82050bd8bf7f3f7bf7eea557af5eb5ee8fb7b737ebd7af272d2d8deddbb7d3a74f1f3a75eac4cc9933f9eaabafb870e1025bb76e45ad56131d1dcdac59b33876ec182a958aa4a424aaababd9bd7bb712a1d8f5db67b15878f6d96769dcb8310e87037f7f7f7af5ea8556aba5b0b090193366a056ab319bcdac58b182ecececdf7dd02c3122fe90e374daf10c1b4dd5d96df5dabdca02d0fd0579fbf66d5ab66ca9d8adb66ad58a5f7ef945896ca752a950abd574e9d2857dfbf671f4e8d13a3d52f728782a958a5f7ffd95162d5a28db5bb56ac5d9b36751abd5e8743ac5e6d6fd65bc6cd932162f5e4c4d4d0dc9c9c91c3a7448399f5eafc76eb7535e5e4e7171319d3a75e2f6eddbf8f9f961b158b05aad646767a3d7eb79e49147282a2a223c3c9c2b57aed411daf9f9f94ad42f792afd6f3e801a0da74e9da27ffffe389d4e7af7eecd983163a8acac54ea4116dcd9d9d98aa8ba7dfb36e1e1e1646666525d5d4d6161211d3b76e4e6cd9bf8fafa2a53fa827f971a078f47ffc8bd73f1a8341e7504a6dc5ee4f6236f2f2e2e262c2c4cf9ddb2654bd2d3d3f9c31ffea0b41d5f5f5f5ab76ecd860d1bb87cf932eddbb7af2376e5b4723bada8a8a071e3c6ca7e3f3f3f4a4b4b6bb5810759f8a656abc9cfcfc76432d1b16347b2b2b278ecb1c76a459595db96dd6e27373797ae5dbbe2e9e989c562a16ddbb61c3f7e9c5f7ffd95aaaa2a3a75ea44464606212121caf3ed8ad96ce6d0a143b5eacef5fd23499232c2b87efd7ae5dc39393944454529e70d0e0e26252545c95fafd763b3d9282c2ca4a2a282b0b0302e5cb8409b366dc8cfcf57f2183c783046a391eaea6a424242387dfab47085f81b633018e8dfbf3f7bf7ee459224828282ea7c77323333b974e91243860c69d0b440abd572e8d021d6ac59a38c885754542049120e8783254b9670fdfa75faf5ebc7ad5bb7d8b163078f3ffe3873e6cc41afd763369bf1f7f7e7adb7dee291471ea165cb964a3448f70ea1b7b7374f3ffd74bd33918d1b37c6d7d797b2b23276eedc894aa522363696f8f8782e5ebc48767636ddba7523343414499258b97225191919949696b270e142c501c2e79f7f8e46a321393999891327525050c0e6cd9b59b76e9d32eb555e5ececd9b37b1d96c0c1b368cf3e7cf939e9eae7490bb75eb46ab56adf8e0830f983f7f3e3b77eee4dab56b1416160228515fe54ed1d2a54bb97efd3a03060c20272787c4c4443a77eeccecd9b3912489279e7882caca4abef8e20b3c3d3d494d4de5e5975fe6d4a9531c387000ad56cb91234714db75bbdd4e9f3e7d282929212e2e8e468d1a71e2c409a64f9fce891327f8f1c71f912489010306d0af5f3f244922363696d4d454ac562b3a9d8e6ddbb661b7db79eaa9a7080c0c242b2bab567dbbde1ff91dab56abc9cccc542201b76cd992ecec6cce9f3f2fd6520921fe308feae9f16813cdbdf33bea15e1f288b2eb8751a3d1a0d3e93871e204b76edd62debc79dcba758b5ebd7a3164c8107af4e851cb5bc8b56bd778eeb9e7282c2ca457af5eca284443f9a6a5a561b7db79f5d557a9aaaa223c3c9c37df7c13abd5cac71f7f4c6a6a2a9b376fae25ead56a354f3cf104fefefe444747535c5c4c5252125e5e5e94979773f9f265162f5eccce9d3b898b8b63e6cc99180c06faf4e94366662677efdec5e17070f2e449e6cc99c3a54b971838702063c78eadd7cbc3fd7ad7414141444444d0b2654b9e78e2092e5cb8404949096ddbb6e5f1c71fc7cfcf8f989818366dda447979399224111111c1ebafbfceac59b3282d2de5db6fbfe5edb7dfe6e4c9930c193284175e780183c14062622253a74e45a552d1ad5b3772737395917ec1bfb4f78adac31b7ddb27b877f66b54da467544842449b5c4836c7faa56ab494d4d65fcf8f14c9e3c19ad564b7070307bf6ec2126264611805555551417173376ec58ce9d3bc7c8912395fc743a5d2d012edbb57efffdf72c5cb890912347e2efef8f8f8f0f292929545555919696c6b265cbd8bb776f2d11a1d56a090909a15dbb76346dda946eddba71e3c60d4c2693627fbe6ddb36c68d1bc7ead5abd16ab5d4d4d4b066cd1a8a8a8a58bf7e3d57ae5cc1dbdb9be79f7f1e87c341bb76ed58b468113a9d4ec9232e2e8ef1e3c7f3d1471fd5db796da84d555757939e9e4e5555155bb66c61e8d0a198cd660e1f3eccb56bd7f0f4f464c28409d8ed761e7df451162d5a84244998cd66d2d3d379e5955748494961e3c68d2c5bb68ccf3efb8c091326b063c70e542a15393939389d4e264d9a84c96422343494e5cb970b9bd27fc1a8b8d168a44993261c3a7488a8a8282574bbc562e1d2a54b9c3a758ad6ad5be3e1e1d1605449d9bde5eddbb7959955d7011ef96fb3d9ac7c73e4cea8c3e1a07bf7eebcf1c61b0c1d3a94cb972f535c5c7cdf4e98dcb976ef2c7efdf5d7b468d1826fbef94689f6ea743a6b79bcaaa9a951ccace44ef4bd7bf7d0e974346dda94929212e59a64132979665792a45ac253febb59b3668c1f3f9ea8a828d46a35e5e5e5545454e07038d06ab5545757d3ac59333c3c3c301a8dca8cabc562a9f5fd54abd5582c16a58ee4592e797d4b717171adeb95d7b1b89b99c9b3497e7e7ecaf5c878797929db9c4e27e5e5e5f8f8f828837baeb35cf2cc94d56a55daa22cbeddebde559457575773f6ec59ba76edca88112358bb76ed3fddf7bbe01f430c6dd4ff4a41ede18339ef3434a02bf3f2f2d8b973a7f212b4582cecdebd9b9c9c1cb45a2dafbcf20a0e8783471f7d94175f7c91b2b232ae5fbfce8103079497dd77df7dc7debd7be9d0a103bb76ed62c58a15006cdbb68d9c9c1c45a024252591919181d56a65f6ecd9188d4602020218376e1cf7eedd43afd773f8f061323232eabc28552a15cd9a3523343494a3478f3275ea54c523895eaf27212181bcbc3c0c06033b76ec60f3e6cd74eedc99acac2c3efae8232c160b76bb9da54b9772e7ce1ddab76fcf5ffef2977a47c4ff166ddbb6c56834f2e38f3fd2b3674fc5565d9224c2c2c2f0f1f121363696a3478f2a79171515919c9c8cd96c46ad56b374e952727272080d0de5b5d75ee3e79f7fc66030b067cf1e3efef863c2c3c3c9cbcb63ddba75f5ba6914fcf66d47f2f6a73af3401d112e772a8f1f3fce8f3ffea83c9fe5e5e56cd9b2059bcd467171310b162c202020001f1f1fc68f1f8fc3e160dfbe7d6466662a1fca0d1b3650505040dbb66dd9b2650b717171e8743ad6ac5943595999121067c3860d141515919191c1dab56b69dfbe3d6ab59a975f7e199bcd86878707090909e4e6e6d6693b5aad96c8c848f2f3f33977ee1cbd7bf7563c56ac5ab58ae4e464222323d9b87123c78e1d5344416a6a2ae7cf9f47a552919797c78a152bf0f3f3a369d3a6bcfaeaab141414e0e9e9c99a356bf8f6db6f898c8ce4af7ffd2bc9c9c97fd768b35eafe79b6fbe2125258501030610151545d7ae5d01b875eb162b56ac50bc66cc9c3993828202a58cf1f1f1141515296e57131313e9d6ad1b3b76ec2029290980c2c242de79e71d8c46232d5ab460faf4e9e4e7e78b11f1df184992b871e3067e7e7ebcf0c20be4e6e672f8f0616edfbe4d727232bffefa2b13274e44a3d190979777df8e517db3b60fd2d173381c04040470edda356edcb841bf7efdf0f2f26ad034ca62b1307af4688283836b992fa9542a5ab76ecdba75eb94d99f3163c670ebd62d7af4e881c56221282888c0c04072737315016db3d9888989e1f2e5cb9c3d7b96f6eddb13101080c16060e8d0a1f5ce22db6c36c53446a3d170e9d22552525218316204fdfbf767eedcb9e4e5e5919a9aca0b2fbcc0975f7ec9c0810379fbedb7311a8d3cf3cc33fcf18f7fe4e2c58bf77dc6e5fa922489f3e7cf131e1eaeb8dcedd7af1f972f5f262a2a0a2f2f2f2449a277efde188d46860e1d8ac160e0c2850b444444e0efef8fd3e9243a3a9aacac2cba76edaaacefe8debd3b172e5ca8f58d75bf4f7267c06eb7131111c1800103b05aad94969612191989c160203232121f1f1fa57e52535319356a146ddab4a935332ef8370ffc8a803e0d8fecfdaf085735f882931742b94e09c9bd57a7d359cb6e54b60d954704dd7bacf27e7955b424494ac3937be0eef6a572be72236bc81c449e7657a954755eda56abb54e3e72cfdab50caed7237fccff5edca7ff351a4d8375d5503ddfaf1caef9cb790bfe1d6dc7715fd79faecf6f7df7d8f51997efa36b1ba8ef39903fbef22891ab7b4cd73cea7b3edcdb80fbc7ddd574c6b54db8b615d776e5de161b6ab3f7cbe341913d2fb8cf2efcadf3bad75343e5b85f1e827f3e56ab95b56bd772edda359a356b868f8f0f168b8573e7ce91959545787838a1a1a168b55a4a4a4a282c2ca443870ecc9933a7961b43a7d3c9934f3ec9575f7dc59d3b7714f38455ab56b17efd7a6c361b5dba74212b2b8b75ebd671e7ce1d56af5e4d5858180b172e64d6ac59389d4eb66edd8abfbf3f870e1de2e5975f26242484975e7a899a9a1ad6af5fcfad5bb7e8d6ad1b57af5ea5b2b292f9f3e7f3e9a79f2a6551a9548c183182d9b367a352a9c8cfcf57e23f2c5ebc98ce9d3b535252c2c68d1b898f8f27232383a2a222020202c8cece66debc796467673379f264c5ad66525212efbdf71e53a64ca155ab56ac5cb912bbdd4ef3e6cd59bd7a355dbb7665e9d2a5ecdebd9bd9b36713131383a7a7273ffffc334b962c513aa9369b8d2fbffc92caca4a1c0e074d9b36a5a6a6865f7ef985d2d252cc66339d3b77e6ca952b7cf8e187141616b27af56a22222258b060013367cea4b2b292a953a7f2dc73cfa1d168f8e9a79f983b772ef3e6cd63e0c081389d4ef6eddbc7ae5dbbd8b97327d1d1d1545555f1ca2baf307af4682449e2c489132c58b080458b16d1bf7f7faaaaaaf8ecb3cfd8b46913274f9e24363696efbfff9ea79e7a8a61c386316fde3c6c361bab57afe6e0c183242626b26ad52aba77ef4e8f1e3d183468101b366ca0baba9afcfc7cac562b3366ccc06432e1e5e5c5f6eddb397cf8306bd7ae15ed590871814020100804ae427cc3860d0d065dab6f704192245e7bedb53afec45dd749b8e6e13af023a793f7c9bf5dff967fcba62baee95df3913bc4eea3e6eeeb35dcf3963b7c77efdee5ead5ab4c9830812b57ae28f93594877bb9ddcbeb6ae6e27e6e87c3c1d34f3fcdd4a953f1f1f1419224c68e1d4b515191224effde3a722f9bfb36f7fcee778c6b3ad7f3b897c1f5b970352d721db070fddfcbcb8b4d9b36b170e142b2b3b345837b4810c67e02814020103c044892444a4a0a010101582c963a0bf6dded8ef57a3dd7ae5dab7736e57e9ea35c6726ddd3b89fc35dccb9ee77cda7a1c5ce0d95a3beedaeb362eef6cef72b6743e91a3ab75aad66dfbe7decdcb95349e3e5e5556b84f81fa9a3fb6d73cfef418e713fcf8396a1be7be1743ae9dcb933a5a5a5756cd40542880b04028140f0bb47ad56b37dfbf63a42b4216453c7ff8698097abd9eb8b838658dc76f8d56abfd5dd948cbeb6b92929214af648287e4de08d314814020100804ff6ea12847e714eb7b7e1be43569c2f3d1c385b81b02814020103c4462e96f8d88cb6ef5fe9b904d6d04bf1dff8893058110e20281402010fc6e44f8ecd9b3090c0c546cc45d2307cbbef17ffae92776ecd8d1a0498a4aa5c2683462b55a95c0664d9b36a5aaaa8a9a9a1a9c4e27fefefe94949434684fdca2450b8a8b8b1f28d0954020f8c711be6b0402814020780870381cb46edd9a13274e909999c9e9d3a7b973e70e66b399c2c242727373292b2b233030b096bf6e772449e27ffee77f983c79b2e216373d3d9d91234762b158f0f4f4e4e4c99368341a1c0e0766b31993c9a488f4aaaa2a2e5ebc88b7b737959595b5bcb858add67a8308c98179542a15369b4d49e37038947814f27ebbddaeec9783fbc8fbe563cc6673ad6034ee8b15e573d4d4d4603299b0d96c58ad56e53a5c3b15aed727bb11968f93afd9bd23e25e2772b46693c984d56a55d25557572b69e5eb34994cf596d9f57a65cc6633369b0d87c3516bbb6b1d098410170804028140f01b232fa8b3582ce4e6e6929e9e4e5151113a9d8e9292120a0a0aa8acac547cd53784d56a55a26fea743adab66d4b5555152d5ab400203a3a9ab4b4344a4a4ab0dbedcc983183f8f878e6cd9b87d16804a0a2a282c18307939090c09b6fbe89cd66c36eb73372e448a64d9b56c72f7dcf9e3d79e38d37a8acaca46fdfbe4c9c381187c34170703093274f2632329279f3e6613299080b0b63ca942968b55a8c462373e7ce252c2c4cf1911d1010c0b469d3f0f1f1c1e170f0eebbef2a91a765a1debb776fa64e9dcaca952bf9eaabafe8d9b32723478e24313191b973e72a3ef69d4e27afbffe3a090909ac58b182471e7904b55acda2458b484c4c64fefcf9787a7ad612ca4ea79337de788384840462636369d6ac197abd9e55ab56b17dfb769e7df659000c0603efbdf71e3367ce64c78e1d8c1e3d9a8e1d3bb275eb56de7ffffd3a2e2501bcbdbd59b2648912b173ca9429f4e9d387e0e060e6ce9dabcc803cf6d8634c9f3e5d04dd11425c2010080402c1bf0a87c3419b366d68d9b2259d3b77c6c3c3038d468346a351822a35e496d0959c9c1c7c7d7df1f0f060c08001ecd8b183468d1ae1e5e5c5a04183f8eebbefb0d96c2c5fbe9ca0a020e6cf9f8f56ab65f2e4c948928446a3a143870e6cdab489679e798649932661369bf1f5f5a579f3e6b53a026ab59afcfc7c5e7bed35cc663303060c60f1e2c5d8ed763a76ecc8a38f3eca8d1b3778fdf5d7319bcd444444b06cd932341a0dad5bb7262a2a8aab57af326dda349c4e274141412c5fbe1c3f3f3ffcfdfd19346810c5c5c5b57c67070606326bd62cce9e3dcb810307d8b66d1b1111117cf8e1878c1a358a21438670f7ee5dd6ad5b47707030cb972fe7f6eddb180c06befefa6b0c0603cb972f4792a45a82d96432b171e34602030359be7c39454545e8f57a12131329292961e5ca95cc983183ce9d3b2349122fbdf4121e1e1e6cd9b28555ab56317ffe7c3efbec3382838379f1c517b1582cb5ee8b5eafa77ffffecacc40646424c1c1c1dcb87183499326d1bc7973ec763b4f3df5145e5e5ef79df910fc77206cc405028140207888f0f5f5c562b12861e56fdcb881a7a727068381468d1a71f7eeddfb1eaf52a9f8e5975ff0f6f6c6c3c383fefdfb337ffe7cc68f1f8fb7b73703060c20363696d6ad5bd3bd7b77264d9a447575352929290c1f3e9c468d1a61b15858b3660db76fdfe6830f3e60dab4697cf2c9277cf1c517753c6f4892c4d5ab57b97bf72ed1d1d194959551545444444404414141646666929d9d4d4141017dfaf4c1c3c3839b376f121a1a4a48480867ce9ca1b0b0905bb76ed1bd7b77828282b870e102212121180c067ef8e1873ad7a7d56a898b8b63dfbe7d787878307cf870b66edd4a464606fbf7ef272c2c8c1f7ef8811e3d7ad0b76f5f6c361b999999040707131e1eceb3cf3e8ba7a7272b56ac5022e6ca9da0a8a828ba77ef0e40666626212121040707131b1b8b5eaf67fbf6ed8c1d3b96a54b97929999c9279f7c424949093ffffc339f7ffe39c9c9c9b469d386e8e8e83a42ba3e5fe700e5e5e5ecdfbf9f2953a6b066cd1a3a74e8c0d6ad5b95a8c20221c4050281402010fc0ba8aeaea6a0a04031ff90174c9acd66dab56bf740eee78a8a8a282f2f272c2c8cd6ad5b939a9aca73cf3d47efdebdb1582c5cb972858e1d3b623018f8f0c30f15817bf1e245453caad56a542a15151515b54c43ea43abd572e4c811c68d1bc7952b57d8b8712393274fa6a6a686e3c78fa3d7eb3970e000c3870fe7debd7bbcfffefb8c1a350a80ddbb77a3d56af9eebbef888989c1cbcb8bb56bd7d2af5f3f0c0603070f1e44abd5e27038b0dbed8af70f798640b64bd76ab58a798f5eafc76834525959a98859a7d34993264db873e74ead8894f2e8b4cd66c3dbdb9b8a8a0a1c0e079224e1743af1f5f5a5acac4ca9a3bb77efe2e3e3a3fc9624094992a8a9a941afd72b6627b25989d3e954cae72ac6ed76bb52d77abd9e2d5bb670f0e04112121230180c5cbd7a5588f0df01c23445201008048287089d4e87b7b73746a3116f6f6f7c7d7df1f5f5c5cfcf0f0f0f8f07324d013871e2046fbdf516478f1e45ad562be61ffbf7efc7c3c3838282024a4a4af8f4d34f79fae9a7193b762c7171718ac7165f5f5fd46a35cf3fff3cfbf6ed439224222222e8dbb76f1d81284912478e1c61f8f0e1949696929090404c4c0c414141646464a0d7ebd9b76f1fa3478fc66c3693989848af5ebde8d2a50b696969180c06befdf65b468d1a8556ab65d7ae5d74e8d081e8e8688e1f3f8e5aada65dbb768c1b374e592ce98a7b79542a15b9b9b968b55a62626230994cb46fdf9e3b77eee0e7e7c7934f3ec9bd7bf7e8dab52b3a9d8e6eddba111313c3d5ab57d1ebf50c1c38907bf7eed1a953278a8a8a68debc3951515158ad56060d1ac4c99327ebb890740f492f0bee264d9a3066cc18743a1d4ea7139d4e4740400061616144444460b7dbd168345cba7489ebd7af131b1b4b464606c5c5c5a23108212e1008040281e05f89d96ca6acac8cd2d252e59ffcbbbabafa817c884b92447a7a3a5dbb76e5c0810378787870f1e245222323d9bf7f3f5aad169bcdc6db6fbfcd840913b87af52aa74f9f66f0e0c1a8542a9a3469c2a64d9bc8cccc44a7d3b179f366341a0dcf3cf30c13274eac530649923877ee1c00d7ae5dc3643271faf4694c2613f7eedd43abd572ead4291a376ecce9d3a7b1582c9c3e7d9aeaea6acacbcbd16ab59c3973868080008e1f3f8ec3e1e0dcb973984c264a4b4bb1dbed74efde9df7de7b4f09fc23db76ab542a3c3d3d9532797878603018b0582ccc9a358b2953a6909191c1c68d1b31180cbcf8e28b2c59b284acac2c162c588046a361d4a851cc9831838a8a0a66cc98c1f4e9d3c9cccc64fdfaf5e8743afefce73fb361c306323333a9aeae262929098d4683d1685444b7a7a7275aad567133d9a851231c0e87626feee9e949656525478e1ce1e0c1832c5cb8501945773a9d346ad488952b57d2b3674f92929284dfefdf0922b2a640201008040f01369b4db15976f78c229b32389d4ef2f2f2f8e4934feaf5cae18ec3e1a8259add7fcb79bb8ee4caf6d28a50f8ffdbe4b4f2b6bf75befad2fe5ff6cbe5745db0eabaafa132cac7b95f87ebb6073946ae13f76df595d73d3ff774aee595d3d96c36faf6edcbbc79f3e8dbb72fdededea251fc0e1036e202814020103c44a8d5ea5a6eeb2449e2ecd9b31c3f7e9c962d5b121414f4c09135ddd3d5779cabb0bc5fbafb09f0fa8eab2fedff65bf6b39ebdbf720c735b4ed418ea9af4e1a2aeffd8ead2f6ff85f3790f3e7cf67e3c68d0fd4c91208212e1008040281e09f886c47ec2eea4a4a4a30180c040606929292f2400b3605ff39c8a629a9a9a91c387040f80fff1d21356ad46889a8068140201008febd381c0eba74e982d168ac651ae27038f0f2f242a552919292425959d9038f880bfe33903b5e870f1f46a3d1086f29bfa77b2f6cc405028140207838907d5a37141edd55a00b0482ff7cc4dc9640201008040f092292a240f0fb42cc6d09040281402010080442880b040281402010080442880b040281402010080482df88ff074dd0b5193652e7c40000000049454e44ae426082
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialautofactura`
--

CREATE TABLE `historialautofactura` (
  `codhistoria` int(6) NULL,
  `codautofactura` int(6) NULL,
  `diafacturacion` int(1) DEFAULT NULL,
  `semanafacturacion` int(2) DEFAULT NULL,
  `anio` int(4) DEFAULT NULL,
  `mes` int(2) DEFAULT NULL,
  `codfactura` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos`
--

CREATE TABLE `impuestos` (
  `codimpuesto` int(3) NULL,
  `fecha` date NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `valor` float NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tipos de impuestos';

--
-- Volcado de datos para la tabla `impuestos`
--

INSERT INTO `impuestos` (`codimpuesto`, `fecha`, `nombre`, `valor`, `borrado`) VALUES
(1, '2013-08-01', 'IVA', 22, '0'),
(2, '0000-00-00', 'Exento', 0, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `librodiario`
--

CREATE TABLE `librodiario` (
  `id` int(8) NULL,
  `fecha` date NULL DEFAULT '0000-00-00',
  `tipodocumento` varchar(1) NULL,
  `coddocumento` varchar(20) NULL,
  `codcomercial` int(5) NULL,
  `codformapago` int(2) NULL,
  `numpago` varchar(30) NULL,
  `moneda` int(1) NULL,
  `total` float NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Movimientos diarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `logid` int(6) NULL,
  `usuarioid` int(6) NULL,
  `logdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `loghace` int(11) NULL,
  `logip` varchar(20) COLLATE latin1_spanish_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Log';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `codmodelo` int(6) NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NULL,
  `texto` longtext COLLATE latin1_spanish_ci NULL,
  `fecha` date NULL,
  `orden` int(2) NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas`
--

CREATE TABLE `monedas` (
  `codmoneda` int(6) NULL,
  `moneda` varchar(3) NULL,
  `numerico` varchar(3) NULL DEFAULT '',
  `descripcion` varchar(50) DEFAULT NULL,
  `simbolo` varchar(15) DEFAULT NULL,
  `fraccion` varchar(20) DEFAULT NULL,
  `orden` int(1) NULL DEFAULT '10',
  `borrado` int(1) NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `monedas`
--

INSERT INTO `monedas` (`codmoneda`, `moneda`, `numerico`, `descripcion`, `simbolo`, `fraccion`, `orden`, `borrado`) VALUES
(1, 'MMK', '104', 'Kyat birmano', 'Ks', 'pya', 3, 0),
(2, 'BIF', '108', 'Franco burundés', 'Fr', 'centime', 3, 0),
(3, 'KHR', '116', 'Riel camboyano', '?', 'sen', 3, 0),
(4, 'DZD', '12', 'Dinar algerino', '?.?', 'Santeem', 3, 0),
(5, 'CAD', '124', 'Dólar canadiense', '$', 'cent', 3, 0),
(6, 'CVE', '132', 'Escudo caboverdiano', 'Esc or $', 'centavo', 3, 0),
(7, 'KYD', '136', 'Dólar caimano', '$', 'cent', 3, 0),
(8, 'LKR', '144', 'Rupia de Sri Lanka', 'Rs', 'cent', 3, 0),
(9, 'CLP', '152', 'Peso chileno', '$', 'centavo', 3, 0),
(10, 'CNY', '156', 'Yuan chino', '¥', 'fen', 3, 0),
(11, 'COP', '170', 'Peso colombiano', '$', 'centavo', 3, 0),
(12, 'KMF', '174', 'Franco comoriano', 'Fr', 'centime', 3, 0),
(13, 'CRC', '188', 'Colón costarricense', '?', 'cêntimo', 3, 0),
(14, 'HRK', '191', 'Kuna croata', 'kn', 'lipa', 3, 0),
(15, 'CUP', '192', 'Peso cubano', '$', 'centavo', 3, 0),
(16, 'CZK', '203', 'Koruna checa', 'K?', 'halé?', 3, 0),
(17, 'DKK', '208', 'Corona danesa', 'kr', 'øre', 3, 0),
(18, 'DOP', '214', 'Peso dominicano', '$', 'centavo', 3, 0),
(19, 'ETB', '230', 'Birr etíope', 'Br', 'santim', 3, 0),
(20, 'ERN', '232', 'Nakfa eritreo', 'Nfk', 'cent', 3, 0),
(21, 'FKP', '238', 'Libra malvinense', '£', 'penny', 3, 0),
(22, 'FJD', '242', 'Dólar fiyiano', '$', 'cent', 3, 0),
(23, 'DJF', '262', 'Franco yibutiano', 'Fr', 'centime', 3, 0),
(24, 'GMD', '270', 'Dalasi gambiano', 'D', 'butut', 3, 0),
(25, 'GIP', '292', 'Libra de Gibraltar', '£', 'penny', 3, 0),
(26, 'ARS', '32', 'Peso argentino', '$', 'centavo', 3, 0),
(27, 'GTQ', '320', 'Quetzal guatemalteco', 'Q', 'centavo', 3, 0),
(28, 'GNF', '324', 'Franco guineano', 'Fr', 'centime', 3, 0),
(29, 'GYD', '328', 'Dólar guyanés', '$', 'cent', 3, 0),
(30, 'HTG', '332', 'Gourde haitiano', 'G', 'centime', 3, 0),
(31, 'HNL', '340', 'Lempira hondureño', 'L', 'centavo', 3, 0),
(32, 'HKD', '344', 'Dólar de Hong Kong', '$', 'cent', 3, 0),
(33, 'HUF', '348', 'Forint húngaro', 'Ft', 'fillér', 3, 0),
(34, 'ISK', '352', 'Króna islandesa', 'kr', 'eyrir', 3, 0),
(35, 'INR', '356', 'Rupia india', '?', 'paisa', 3, 0),
(36, 'AUD', '36', 'Dólar australiano', '$', 'cent', 3, 0),
(37, 'IDR', '360', 'Rupiah indonesia', 'Rp', 'sen', 3, 0),
(38, 'IRR', '364', 'Rial iraní', '?', NULL, 3, 0),
(39, 'IQD', '368', 'Dinar iraquí', '?.?', 'fils', 3, 0),
(40, 'ILS', '376', 'Nuevo shéquel israelí', '?', 'agora', 3, 0),
(41, 'JMD', '388', 'Dólar jamaicano', '$', 'cent', 3, 0),
(42, 'JPY', '392', 'Yen japonés', '¥', 'sen', 3, 0),
(43, 'KZT', '398', 'Tenge kazajo', '?', 'tï?n', 3, 0),
(44, 'JOD', '400', 'Dinar jordano', '?.?', 'piastre', 3, 0),
(45, 'KES', '404', 'Chelín keniata', 'Sh', 'cent', 3, 0),
(46, 'KPW', '408', 'Won norcoreano', '?', 'chon', 3, 0),
(47, 'KRW', '410', 'Won surcoreano', '?', 'jeon', 3, 0),
(48, 'KWD', '414', 'Dinar kuwaití', '?.?', 'fils', 3, 0),
(49, 'KGS', '417', 'Som kirguís', '??', 'tyiyn', 3, 0),
(50, 'LAK', '418', 'Kip lao', '?', 'att', 3, 0),
(51, 'LBP', '422', 'Libra libanesa', '?.?', 'piastre', 3, 0),
(52, 'LSL', '426', 'Loti lesotense', 'L', 'sente', 3, 0),
(53, 'LRD', '430', 'Dólar liberiano', '$', 'cent', 3, 0),
(54, 'LYD', '434', 'Dinar libio', '?.?', 'dirham', 3, 0),
(55, 'BSD', '44', 'Dólar bahameño', '$', 'cent', 3, 0),
(56, 'LTL', '440', 'Litas lituano', 'Lt', 'centas', 3, 0),
(57, 'MOP', '446', 'Pataca de Macao', 'P', 'avo', 3, 0),
(58, 'MWK', '454', 'Kwacha malauí', 'MK', 'tambala', 3, 0),
(59, 'MYR', '458', 'Ringgit malayo', 'RM', 'sen', 3, 0),
(60, 'MVR', '462', 'Rufiyaa maldiva', '.?', 'laari', 3, 0),
(61, 'MRO', '478', 'Ouguiya mauritana', 'UM', 'khoums', 3, 0),
(62, 'BHD', '48', 'Dinar bahreiní', '.?.?', 'fils', 3, 0),
(63, 'MUR', '480', 'Rupia mauricia', '?', 'cent', 3, 0),
(64, 'MXN', '484', 'Peso mexicano', '$', 'centavo', 3, 0),
(65, 'MNT', '496', 'Tughrik mongol', '?', 'möngö', 3, 0),
(66, 'MDL', '498', 'Leu moldavo', 'L', 'ban', 3, 0),
(67, 'BDT', '50', 'Taka de Bangladesh', '?', 'paisa', 3, 0),
(68, 'MAD', '504', 'Dirham marroquí', '?.?.', 'centime', 3, 0),
(69, 'AMD', '51', 'Dram armenio', '??.', 'luma', 3, 0),
(70, 'OMR', '512', 'Rial omaní', '?.?.', 'Baisa', 3, 0),
(71, 'NAD', '516', 'Dólar namibio', '$', 'cent', 3, 0),
(72, 'BBD', '52', 'Dólar de Barbados', '$', 'cent', 3, 0),
(73, 'NPR', '524', 'Rupia nepalesa', '?', 'paisa', 3, 0),
(74, 'ANG', '532', 'Florín antillano neerlandés', 'ƒ', 'cent', 3, 0),
(75, 'AWG', '533', 'Florín arubeño', 'ƒ', 'cent', 3, 0),
(76, 'VUV', '548', 'Vatu vanuatense', 'Vt', NULL, 3, 0),
(77, 'NZD', '554', 'Dólar neozelandés', '$', 'cent', 3, 0),
(78, 'NIO', '558', 'Córdoba nicaragüense', 'C$', 'centavo', 3, 0),
(79, 'NGN', '566', 'Naira nigeriana', '?', 'kobo', 3, 0),
(80, 'NOK', '578', 'Corona noruega', 'kr', 'øre', 3, 0),
(81, 'PKR', '586', 'Rupia pakistaní', '?', 'paisa', 3, 0),
(82, 'PAB', '590', 'Balboa panameña', 'B/.', 'centésimo', 3, 0),
(83, 'PGK', '598', 'Kina de Papúa Nueva Guinea', 'K', 'toea', 3, 0),
(84, 'BMD', '60', 'Dólar bermudeño', '$', 'cent', 3, 0),
(85, 'PYG', '600', 'Guaraní paraguayo', '?', 'cêntimo', 3, 0),
(86, 'PEN', '604', 'Nuevo sol peruano', 'S/.', 'cêntimo', 3, 0),
(87, 'PHP', '608', 'Peso filipino', '?', 'centavo', 3, 0),
(88, 'QAR', '634', 'Rial qatarí', '?.?', 'dirham', 3, 0),
(89, 'BTN', '64', 'Ngultrum de Bután', 'Nu.', 'chetrum', 3, 0),
(90, 'RUB', '643', 'Rublo ruso', '???.', 'kopek', 3, 0),
(91, 'RWF', '646', 'Franco ruandés', 'Fr', 'centime', 3, 0),
(92, 'SHP', '654', 'Libra de Santa Helena', '£', 'penny', 3, 0),
(93, 'STD', '678', 'Dobra de Santo Tomé y Príncipe', 'Db', 'cêntimo', 3, 0),
(94, 'BOB', '68', 'Boliviano', 'Bs.', 'centavo', 3, 0),
(95, 'SAR', '682', 'Riyal saudí', '?.?', 'halala', 3, 0),
(96, 'SCR', '690', 'Rupia de Seychelles', '?', 'cent', 3, 0),
(97, 'SLL', '694', 'Leone de Sierra Leona', 'Le', 'cent', 3, 0),
(98, 'SGD', '702', 'Dólar de Singapur', '$', 'cent', 3, 0),
(99, 'VND', '704', 'Dong vietnamita', '?', 'hào', 3, 0),
(100, 'SOS', '706', 'Chelín somalí', 'Sh', 'cent', 3, 0),
(101, 'ZAR', '710', 'Rand sudafricano', 'R', 'cent', 3, 0),
(102, 'BWP', '72', 'Pula de Botsuana', 'P', 'thebe', 3, 0),
(103, 'SSP', '728', 'Libra', '£', 'piastre', 3, 0),
(104, 'SZL', '748', 'Lilangeni suazi', 'L', 'cent', 3, 0),
(105, 'SEK', '752', 'Corona sueca', 'kr', 'öre', 3, 0),
(106, 'CHF', '756', 'Franco suizo', 'Fr', 'rappen', 3, 0),
(107, 'SYP', '760', 'Libra siria', '?.?', 'piastre', 3, 0),
(108, 'THB', '764', 'Baht tailandés', '?', 'satang', 3, 0),
(109, 'TOP', '776', 'Pa\'anga tongano', 'T$', 'seniti', 3, 0),
(110, 'TTD', '780', 'Dólar de Trinidad y Tobago', '$', 'cent', 3, 0),
(111, 'AED', '784', 'Dirham de los Emiratos Árabes Unidos', '?.?', 'fils', 3, 0),
(112, 'TND', '788', 'Dinar tunecino', '?.?', 'millime', 3, 0),
(113, 'ALL', '8', 'Lek albanés', 'L', 'qindarkë', 3, 0),
(114, 'UGX', '800', 'Chelín ugandés', 'Sh', 'cent', 3, 0),
(115, 'MKD', '807', 'Denar macedonio', '???', 'deni', 3, 0),
(116, 'EGP', '818', 'Libra egipcia', '?.?', 'piaster', 3, 0),
(117, 'GBP', '826', 'Libra esterlina', '£', 'penny', 3, 0),
(118, 'TZS', '834', 'Chelín tanzano', 'Sh', 'cent', 3, 0),
(119, 'BZD', '84', 'Dólar de Belice', '$', 'cent', 3, 0),
(120, 'USD', '840', 'Dólar estadounidense', 'U$S', 'cent', 2, 0),
(121, 'UYU', '858', 'Peso uruguayo', '$', 'centésimo', 1, 0),
(122, 'UZS', '860', 'Som uzbeko', '??', 'tiyin', 3, 0),
(123, 'WST', '882', 'Tala samoana', 'T', 'sene', 3, 0),
(124, 'YER', '886', 'Rial yemení', '?', 'fils', 3, 0),
(125, 'SBD', '90', 'Dólar de las Islas Salomón', '$', 'cent', 3, 0),
(126, 'TWD', '901', 'Dólar taiwanés', '$', 'cent', 3, 0),
(127, 'CUC', '931', 'Peso cubano convertible', '$', 'centavo', 3, 0),
(128, 'TMT', '934', 'Manat turcomano', 'm', 'tennesi', 3, 0),
(129, 'GHS', '936', 'Cedi ghanés', '?', 'pesewa', 3, 0),
(130, 'VEF', '937', 'Bolívar fuerte venezolano', 'Bs F', 'cêntimo', 3, 0),
(131, 'SDG', '938', 'Dinar sudanés', '£', 'piastre', 3, 0),
(132, 'RSD', '941', 'Dinar', '???.', 'para', 3, 0),
(133, 'MZN', '943', 'Metical mozambiqueño', 'MT', 'centavo', 3, 0),
(134, 'AZN', '944', 'Manat azerbaiyano', 'm', 'q?pik', 3, 0),
(135, 'RON', '946', 'Leu rumano', 'L', 'ban', 3, 0),
(136, 'TRY', '949', 'Lira turca', NULL, 'kuru?', 3, 0),
(137, 'XAF', '950', 'Franco CFA de África central', 'Fr', 'centime', 3, 0),
(138, 'XCD', '951', 'Dólar del Caribe Oriental', '$', 'cent', 3, 0),
(139, 'XOF', '952', 'Franco CFA de África Occidental', 'Fr', 'centime', 3, 0),
(140, 'XPF', '953', 'Franco CFP', 'Fr', 'centime', 3, 0),
(141, 'BND', '96', 'Dólar de Brunéi', '$', 'sen', 3, 0),
(142, 'ZMW', '967', 'Kwacha zambiano', 'ZK', 'ngwee', 3, 0),
(143, 'SRD', '968', 'Dólar surinamés', '$', 'cent', 3, 0),
(144, 'MGA', '969', 'Ariary malgache', 'Ar', 'iraimbilanja', 3, 0),
(145, 'AFN', '971', 'Afgani afgano', '?', 'pul', 3, 0),
(146, 'TJS', '972', 'Somoni tayik', '??', 'diram', 3, 0),
(147, 'AOA', '973', 'Kwanza angoleño', 'Kz', 'cêntimo', 3, 0),
(148, 'BYR', '974', 'Rublo bielorruso', 'Br', 'kapyeyka', 3, 0),
(149, 'BGN', '975', 'Lev búlgaro', '??', 'stotinka', 3, 0),
(150, 'CDF', '976', 'Franco congoleño', 'Fr', 'centime', 3, 0),
(151, 'BAM', '977', 'Marco convertible de Bosnia-Herzegovina', '??', 'fening', 3, 0),
(152, 'EUR', '978', 'Euro', '€', 'cent', 3, 0),
(153, 'UAH', '980', 'Grivna ucraniana', '?', 'kopiyka', 3, 0),
(154, 'GEL', '981', 'lari georgiano', '?', 'tetri', 3, 0),
(155, 'PLN', '985', 'Zloty polaco', 'z?', 'grosz', 3, 0),
(156, 'BRL', '986', 'Real brasileño', 'R$', 'centavo', 3, 0),
(161, 'mcc', '990', 'mcc', 'm', '', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ncredito`
--

CREATE TABLE `ncredito` (
  `codncredito` int(11) NULL,
  `tipo` int(1) NULL,
  `fecha` date NULL,
  `iva` tinyint(4) NULL,
  `codcliente` int(5) NULL,
  `estado` varchar(1) NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `tipocambio` decimal(7,4) NULL,
  `total` float NULL,
  `observacion` varchar(350) NULL,
  `emitida` int(1) DEFAULT '0',
  `enviada` int(1) DEFAULT NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Nota de credito a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ncreditolinea`
--

CREATE TABLE `ncreditolinea` (
  `codncredito` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfactura` int(11) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `codservice` int(6) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL,
  `moneda` int(2) NULL,
  `precio` float NULL,
  `importe` float NULL,
  `comision` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de notas de credito a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ncreditolineatmp`
--

CREATE TABLE `ncreditolineatmp` (
  `codncredito` int(11) NULL,
  `numlinea` int(4) NULL,
  `codfactura` int(11) NULL,
  `codfamilia` int(3) NULL,
  `codigo` varchar(15) NULL,
  `codservice` int(6) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `cantidad` float NULL,
  `moneda` int(2) NULL,
  `precio` float NULL,
  `importe` float NULL,
  `comision` int(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='lineas de notas de credito a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagodgi`
--

CREATE TABLE `pagodgi` (
  `codpagodgi` int(11) NULL,
  `mes` int(2) NULL,
  `anio` int(4) NULL,
  `f108` decimal(10,0) DEFAULT '0',
  `f328` int(10) DEFAULT '0',
  `f546` decimal(10,0) DEFAULT '0',
  `f606` int(10) DEFAULT '0',
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Registro de Pagos DGI';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NULL,
  `codfactura` varchar(20) NULL,
  `codproveedor` int(5) NULL,
  `importe` float NULL,
  `codformapago` int(2) NULL,
  `moneda` int(2) NULL,
  `numdocumento` varchar(30) NULL,
  `fechapago` date DEFAULT '0000-00-00',
  `observaciones` text NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Pagos de facturas a proveedores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `codpais` int(3) NULL,
  `nombre` varchar(50) NULL,
  `nombrecorto` varchar(50) NULL,
  `codigoalpha2` varchar(4) NULL,
  `codigoalpha3` varchar(4) NULL,
  `referencia` int(4) NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Paises';

--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`codpais`, `nombre`, `nombrecorto`, `codigoalpha2`, `codigoalpha3`, `referencia`) VALUES
(1, 'Afghanistan', 'Afghanistan (l\')', 'AF', 'AFG', 4),
(2, 'Åland Islands', 'Åland(les Îles)', 'AX', 'ALA', 248),
(3, 'Albania', 'Albanie (l\')', 'AL', 'ALB', 8),
(4, 'Algeria', 'Algérie (l\')', 'DZ', 'DZA', 12),
(5, 'American Samoa', 'Samoa américaines (les)', 'AS', 'ASM', 16),
(6, 'Andorra', 'Andorre (l\')', 'AD', 'AND', 20),
(7, 'Angola', 'Angola (l\')', 'AO', 'AGO', 24),
(8, 'Anguilla', 'Anguilla', 'AI', 'AIA', 660),
(9, 'Antarctica', 'Antarctique (l\')', 'AQ', 'ATA', 10),
(10, 'Antigua and Barbuda', 'Antigua-et-Barbuda', 'AG', 'ATG', 28),
(11, 'Argentina', 'Argentine (l\')', 'AR', 'ARG', 32),
(12, 'Armenia', 'Arménie (l\')', 'AM', 'ARM', 51),
(13, 'Aruba', 'Aruba', 'AW', 'ABW', 533),
(14, 'Australia', 'Australie (l\')', 'AU', 'AUS', 36),
(15, 'Austria', 'Autriche (l\')', 'AT', 'AUT', 40),
(16, 'Azerbaijan', 'Azerbaïdjan (l\')', 'AZ', 'AZE', 31),
(17, 'Bahamas (the)', 'Bahamas (les)', 'BS', 'BHS', 44),
(18, 'Bahrain', 'Bahreïn', 'BH', 'BHR', 48),
(19, 'Bangladesh', 'Bangladesh (le)', 'BD', 'BGD', 50),
(20, 'Barbados', 'Barbade (la)', 'BB', 'BRB', 52),
(21, 'Belarus', 'Bélarus (le)', 'BY', 'BLR', 112),
(22, 'Belgium', 'Belgique (la)', 'BE', 'BEL', 56),
(23, 'Belize', 'Belize (le)', 'BZ', 'BLZ', 84),
(24, 'Benin', 'Bénin (le)', 'BJ', 'BEN', 204),
(25, 'Bermuda', 'Bermudes (les)', 'BM', 'BMU', 60),
(26, 'Bhutan', 'Bhoutan (le)', 'BT', 'BTN', 64),
(27, 'Bolivia (Plurinational State of)', 'Bolivie (État plurinational de)', 'BO', 'BOL', 68),
(28, 'Bonaire, Sint Eustatius and Saba', 'Bonaire, Saint-Eustache et Saba', 'BQ', 'BES', 535),
(29, 'Bosnia and Herzegovina', 'Bosnie-Herzégovine (la)', 'BA', 'BIH', 70),
(30, 'Botswana', 'Botswana (le)', 'BW', 'BWA', 72),
(31, 'Bouvet Island', 'Bouvet (l\'Île)', 'BV', 'BVT', 74),
(32, 'Brazil', 'Brésil (le)', 'BR', 'BRA', 76),
(33, 'British Indian Ocean Territory (the)', 'Indien (le Territoire britannique de l\'océan)', 'IO', 'IOT', 86),
(34, 'Brunei Darussalam', 'Brunéi Darussalam (le)', 'BN', 'BRN', 96),
(35, 'Bulgaria', 'Bulgarie (la)', 'BG', 'BGR', 100),
(36, 'Burkina Faso', 'Burkina Faso (le)', 'BF', 'BFA', 854),
(37, 'Burundi', 'Burundi (le)', 'BI', 'BDI', 108),
(38, 'Cabo Verde', 'Cabo Verde', 'CV', 'CPV', 132),
(39, 'Cambodia', 'Cambodge (le)', 'KH', 'KHM', 116),
(40, 'Cameroon', 'Cameroun (le)', 'CM', 'CMR', 120),
(41, 'Canada', 'Canada (le)', 'CA', 'CAN', 124),
(42, 'Cayman Islands (the)', 'Caïmans (les Îles)', 'KY', 'CYM', 136),
(43, 'Central African Republic (the)', 'République centrafricaine (la)', 'CF', 'CAF', 140),
(44, 'Chad', 'Tchad (le)', 'TD', 'TCD', 148),
(45, 'Chile', 'Chili (le)', 'CL', 'CHL', 152),
(46, 'China', 'Chine (la)', 'CN', 'CHN', 156),
(47, 'Christmas Island', 'Christmas (l\'Île)', 'CX', 'CXR', 162),
(48, 'Cocos (Keeling) Islands (the)', 'Cocos (les Îles)/ Keeling (les Îles)', 'CC', 'CCK', 166),
(49, 'Colombia', 'Colombie (la)', 'CO', 'COL', 170),
(50, 'Comoros (the)', 'Comores (les)', 'KM', 'COM', 174),
(51, 'Congo (the Democratic Republic of the)', 'Congo (la République démocratique du)', 'CD', 'COD', 180),
(52, 'Congo (the)', 'Congo (le)', 'CG', 'COG', 178),
(53, 'Cook Islands (the)', 'Cook (les Îles)', 'CK', 'COK', 184),
(54, 'Costa Rica', 'Costa Rica (le)', 'CR', 'CRI', 188),
(55, 'Côte d\'Ivoire', 'Côte d\'Ivoire (la)', 'CI', 'CIV', 384),
(56, 'Croatia', 'Croatie (la)', 'HR', 'HRV', 191),
(57, 'Cuba', 'Cuba', 'CU', 'CUB', 192),
(58, 'Curaçao', 'Curaçao', 'CW', 'CUW', 531),
(59, 'Cyprus', 'Chypre', 'CY', 'CYP', 196),
(60, 'Czechia', 'Tchéquie (la)', 'CZ', 'CZE', 203),
(61, 'Denmark', 'Danemark (le)', 'DK', 'DNK', 208),
(62, 'Djibouti', 'Djibouti', 'DJ', 'DJI', 262),
(63, 'Dominica', 'Dominique (la)', 'DM', 'DMA', 212),
(64, 'Dominican Republic (the)', 'dominicaine (la République)', 'DO', 'DOM', 214),
(65, 'Ecuador', 'Équateur (l\')', 'EC', 'ECU', 218),
(66, 'Egypt', 'Égypte (l\')', 'EG', 'EGY', 818),
(67, 'El Salvador', 'El Salvador', 'SV', 'SLV', 222),
(68, 'Equatorial Guinea', 'Guinée équatoriale (la)', 'GQ', 'GNQ', 226),
(69, 'Eritrea', 'Érythrée (l\')', 'ER', 'ERI', 232),
(70, 'Estonia', 'Estonie (l\')', 'EE', 'EST', 233),
(71, 'Ethiopia', 'Éthiopie (l\')', 'ET', 'ETH', 231),
(72, 'Falkland Islands (the) [Malvinas]', 'Falkland (les Îles)/Malouines (les Îles)', 'FK', 'FLK', 238),
(73, 'Faroe Islands (the)', 'Féroé (les Îles)', 'FO', 'FRO', 234),
(74, 'Fiji', 'Fidji (les)', 'FJ', 'FJI', 242),
(75, 'Finland', 'Finlande (la)', 'FI', 'FIN', 246),
(76, 'France', 'France (la)', 'FR', 'FRA', 250),
(77, 'French Guiana', 'Guyane française (la )', 'GF', 'GUF', 254),
(78, 'French Polynesia', 'Polynésie française (la)', 'PF', 'PYF', 258),
(79, 'French Southern Territories (the)', 'Terres australes françaises (les)', 'TF', 'ATF', 260),
(80, 'Gabon', 'Gabon (le)', 'GA', 'GAB', 266),
(81, 'Gambia (the)', 'Gambie (la)', 'GM', 'GMB', 270),
(82, 'Georgia', 'Géorgie (la)', 'GE', 'GEO', 268),
(83, 'Germany', 'Allemagne (l\')', 'DE', 'DEU', 276),
(84, 'Ghana', 'Ghana (le)', 'GH', 'GHA', 288),
(85, 'Gibraltar', 'Gibraltar', 'GI', 'GIB', 292),
(86, 'Greece', 'Grèce (la)', 'GR', 'GRC', 300),
(87, 'Greenland', 'Groenland (le)', 'GL', 'GRL', 304),
(88, 'Grenada', 'Grenade (la)', 'GD', 'GRD', 308),
(89, 'Guadeloupe', 'Guadeloupe (la)', 'GP', 'GLP', 312),
(90, 'Guam', 'Guam', 'GU', 'GUM', 316),
(91, 'Guatemala', 'Guatemala (le)', 'GT', 'GTM', 320),
(92, 'Guernsey', 'Guernesey', 'GG', 'GGY', 831),
(93, 'Guinea', 'Guinée (la)', 'GN', 'GIN', 324),
(94, 'Guinea-Bissau', 'Guinée-Bissau (la)', 'GW', 'GNB', 624),
(95, 'Guyana', 'Guyana (le)', 'GY', 'GUY', 328),
(96, 'Haiti', 'Haïti', 'HT', 'HTI', 332),
(97, 'Heard Island and McDonald Islands', 'Heard-et-Îles MacDonald (l\'Île)', 'HM', 'HMD', 334),
(98, 'Holy See (the)', 'Saint-Siège (le)', 'VA', 'VAT', 336),
(99, 'Honduras', 'Honduras (le)', 'HN', 'HND', 340),
(100, 'Hong Kong', 'Hong Kong', 'HK', 'HKG', 344),
(101, 'Hungary', 'Hongrie (la)', 'HU', 'HUN', 348),
(102, 'Iceland', 'Islande (l\')', 'IS', 'ISL', 352),
(103, 'India', 'Inde (l\')', 'IN', 'IND', 356),
(104, 'Indonesia', 'Indonésie (l\')', 'ID', 'IDN', 360),
(105, 'Iran (Islamic Republic of)', 'Iran (République Islamique d\')', 'IR', 'IRN', 364),
(106, 'Iraq', 'Iraq (l\')', 'IQ', 'IRQ', 368),
(107, 'Ireland', 'Irlande (l\')', 'IE', 'IRL', 372),
(108, 'Isle of Man', 'Île de Man', 'IM', 'IMN', 833),
(109, 'Israel', 'Israël', 'IL', 'ISR', 376),
(110, 'Italy', 'Italie (l\')', 'IT', 'ITA', 380),
(111, 'Jamaica', 'Jamaïque (la)', 'JM', 'JAM', 388),
(112, 'Japan', 'Japon (le)', 'JP', 'JPN', 392),
(113, 'Jersey', 'Jersey', 'JE', 'JEY', 832),
(114, 'Jordan', 'Jordanie (la)', 'JO', 'JOR', 400),
(115, 'Kazakhstan', 'Kazakhstan (le)', 'KZ', 'KAZ', 398),
(116, 'Kenya', 'Kenya (le)', 'KE', 'KEN', 404),
(117, 'Kiribati', 'Kiribati', 'KI', 'KIR', 296),
(118, 'Korea (the Democratic People\'s Republic of)', 'Corée (la République populaire démocratique de)', 'KP', 'PRK', 408),
(119, 'Korea (the Republic of)', 'Corée (la République de)', 'KR', 'KOR', 410),
(120, 'Kuwait', 'Koweït (le)', 'KW', 'KWT', 414),
(121, 'Kyrgyzstan', 'Kirghizistan (le)', 'KG', 'KGZ', 417),
(122, 'Lao People\'s Democratic Republic (the)', 'Lao, République démocratique populaire', 'LA', 'LAO', 418),
(123, 'Latvia', 'Lettonie (la)', 'LV', 'LVA', 428),
(124, 'Lebanon', 'Liban (le)', 'LB', 'LBN', 422),
(125, 'Lesotho', 'Lesotho (le)', 'LS', 'LSO', 426),
(126, 'Liberia', 'Libéria (le)', 'LR', 'LBR', 430),
(127, 'Libya', 'Libye (la)', 'LY', 'LBY', 434),
(128, 'Liechtenstein', 'Liechtenstein (le)', 'LI', 'LIE', 438),
(129, 'Lithuania', 'Lituanie (la)', 'LT', 'LTU', 440),
(130, 'Luxembourg', 'Luxembourg (le)', 'LU', 'LUX', 442),
(131, 'Macao', 'Macao', 'MO', 'MAC', 446),
(132, 'Macedonia (the former Yugoslav Republic of)', 'Macédoine (l\'ex?République yougoslave de)', 'MK', 'MKD', 807),
(133, 'Madagascar', 'Madagascar', 'MG', 'MDG', 450),
(134, 'Malawi', 'Malawi (le)', 'MW', 'MWI', 454),
(135, 'Malaysia', 'Malaisie (la)', 'MY', 'MYS', 458),
(136, 'Maldives', 'Maldives (les)', 'MV', 'MDV', 462),
(137, 'Mali', 'Mali (le)', 'ML', 'MLI', 466),
(138, 'Malta', 'Malte', 'MT', 'MLT', 470),
(139, 'Marshall Islands (the)', 'Marshall (Îles)', 'MH', 'MHL', 584),
(140, 'Martinique', 'Martinique (la)', 'MQ', 'MTQ', 474),
(141, 'Mauritania', 'Mauritanie (la)', 'MR', 'MRT', 478),
(142, 'Mauritius', 'Maurice', 'MU', 'MUS', 480),
(143, 'Mayotte', 'Mayotte', 'YT', 'MYT', 175),
(144, 'Mexico', 'Mexique (le)', 'MX', 'MEX', 484),
(145, 'Micronesia (Federated States of)', 'Micronésie (États fédérés de)', 'FM', 'FSM', 583),
(146, 'Moldova (the Republic of)', 'Moldova , République de', 'MD', 'MDA', 498),
(147, 'Monaco', 'Monaco', 'MC', 'MCO', 492),
(148, 'Mongolia', 'Mongolie (la)', 'MN', 'MNG', 496),
(149, 'Montenegro', 'Monténégro (le)', 'ME', 'MNE', 499),
(150, 'Montserrat', 'Montserrat', 'MS', 'MSR', 500),
(151, 'Morocco', 'Maroc (le)', 'MA', 'MAR', 504),
(152, 'Mozambique', 'Mozambique (le)', 'MZ', 'MOZ', 508),
(153, 'Myanmar', 'Myanmar (le)', 'MM', 'MMR', 104),
(154, 'Namibia', 'Namibie (la)', 'NA', 'NAM', 516),
(155, 'Nauru', 'Nauru', 'NR', 'NRU', 520),
(156, 'Nepal', 'Népal (le)', 'NP', 'NPL', 524),
(157, 'Netherlands (the)', 'Pays-Bas (les)', 'NL', 'NLD', 528),
(158, 'New Caledonia', 'Nouvelle-Calédonie (la)', 'NC', 'NCL', 540),
(159, 'New Zealand', 'Nouvelle-Zélande (la)', 'NZ', 'NZL', 554),
(160, 'Nicaragua', 'Nicaragua (le)', 'NI', 'NIC', 558),
(161, 'Niger (the)', 'Niger (le)', 'NE', 'NER', 562),
(162, 'Nigeria', 'Nigéria (le)', 'NG', 'NGA', 566),
(163, 'Niue', 'Niue', 'NU', 'NIU', 570),
(164, 'Norfolk Island', 'Norfolk (l\'Île)', 'NF', 'NFK', 574),
(165, 'Northern Mariana Islands (the)', 'Mariannes du Nord (les Îles)', 'MP', 'MNP', 580),
(166, 'Norway', 'Norvège (la)', 'NO', 'NOR', 578),
(167, 'Oman', 'Oman', 'OM', 'OMN', 512),
(168, 'Pakistan', 'Pakistan (le)', 'PK', 'PAK', 586),
(169, 'Palau', 'Palaos (les)', 'PW', 'PLW', 585),
(170, 'Palestine, State of', 'Palestine, État de', 'PS', 'PSE', 275),
(171, 'Panama', 'Panama (le)', 'PA', 'PAN', 591),
(172, 'Papua New Guinea', 'Papouasie-Nouvelle-Guinée (la)', 'PG', 'PNG', 598),
(173, 'Paraguay', 'Paraguay (le)', 'PY', 'PRY', 600),
(174, 'Peru', 'Pérou (le)', 'PE', 'PER', 604),
(175, 'Philippines (the)', 'Philippines (les)', 'PH', 'PHL', 608),
(176, 'Pitcairn', 'Pitcairn', 'PN', 'PCN', 612),
(177, 'Poland', 'Pologne (la)', 'PL', 'POL', 616),
(178, 'Portugal', 'Portugal (le)', 'PT', 'PRT', 620),
(179, 'Puerto Rico', 'Porto Rico', 'PR', 'PRI', 630),
(180, 'Qatar', 'Qatar (le)', 'QA', 'QAT', 634),
(181, 'Réunion', 'Réunion (La)', 'RE', 'REU', 638),
(182, 'Romania', 'Roumanie (la)', 'RO', 'ROU', 642),
(183, 'Russian Federation (the)', 'Russie (la Fédération de)', 'RU', 'RUS', 643),
(184, 'Rwanda', 'Rwanda (le)', 'RW', 'RWA', 646),
(185, 'Saint Barthélemy', 'Saint-Barthélemy', 'BL', 'BLM', 652),
(186, 'Saint Helena, Ascension and Tristan da Cunha', 'Sainte-Hélène, Ascension et Tristan da Cunha', 'SH', 'SHN', 654),
(187, 'Saint Kitts and Nevis', 'Saint-Kitts-et-Nevis', 'KN', 'KNA', 659),
(188, 'Saint Lucia', 'Sainte-Lucie', 'LC', 'LCA', 662),
(189, 'Saint Martin (French part)', 'Saint-Martin (partie française)', 'MF', 'MAF', 663),
(190, 'Saint Pierre and Miquelon', 'Saint-Pierre-et-Miquelon', 'PM', 'SPM', 666),
(191, 'Saint Vincent and the Grenadines', 'Saint-Vincent-et-les Grenadines', 'VC', 'VCT', 670),
(192, 'Samoa', 'Samoa (le)', 'WS', 'WSM', 882),
(193, 'San Marino', 'Saint-Marin', 'SM', 'SMR', 674),
(194, 'Sao Tome and Principe', 'Sao Tomé-et-Principe', 'ST', 'STP', 678),
(195, 'Saudi Arabia', 'Arabie saoudite (l\')', 'SA', 'SAU', 682),
(196, 'Senegal', 'Sénégal (le)', 'SN', 'SEN', 686),
(197, 'Serbia', 'Serbie (la)', 'RS', 'SRB', 688),
(198, 'Seychelles', 'Seychelles (les)', 'SC', 'SYC', 690),
(199, 'Sierra Leone', 'Sierra Leone (la)', 'SL', 'SLE', 694),
(200, 'Singapore', 'Singapour', 'SG', 'SGP', 702),
(201, 'Sint Maarten (Dutch part)', 'Saint-Martin (partie néerlandaise)', 'SX', 'SXM', 534),
(202, 'Slovakia', 'Slovaquie (la)', 'SK', 'SVK', 703),
(203, 'Slovenia', 'Slovénie (la)', 'SI', 'SVN', 705),
(204, 'Solomon Islands', 'Salomon (Îles)', 'SB', 'SLB', 90),
(205, 'Somalia', 'Somalie (la)', 'SO', 'SOM', 706),
(206, 'South Africa', 'Afrique du Sud (l\')', 'ZA', 'ZAF', 710),
(207, 'South Georgia and the South Sandwich Islands', 'Géorgie du Sud-et-les Îles Sandwich du Sud (la)', 'GS', 'SGS', 239),
(208, 'South Sudan', 'Soudan du Sud (le)', 'SS', 'SSD', 728),
(209, 'Spain', 'Espagne (l\')', 'ES', 'ESP', 724),
(210, 'Sri Lanka', 'Sri Lanka', 'LK', 'LKA', 144),
(211, 'Sudan (the)', 'Soudan (le)', 'SD', 'SDN', 729),
(212, 'Suriname', 'Suriname (le)', 'SR', 'SUR', 740),
(213, 'Svalbard and Jan Mayen', 'Svalbard et l\'Île Jan Mayen (le)', 'SJ', 'SJM', 744),
(214, 'Swaziland', 'Swaziland (le)', 'SZ', 'SWZ', 748),
(215, 'Sweden', 'Suède (la)', 'SE', 'SWE', 752),
(216, 'Switzerland', 'Suisse (la)', 'CH', 'CHE', 756),
(217, 'Syrian Arab Republic', 'République arabe syrienne (la)', 'SY', 'SYR', 760),
(218, 'Taiwan (Province of China)', 'Taïwan (Province de Chine)', 'TW', 'TWN', 158),
(219, 'Tajikistan', 'Tadjikistan (le)', 'TJ', 'TJK', 762),
(220, 'Tanzania, United Republic of', 'Tanzanie, République-Unie de', 'TZ', 'TZA', 834),
(221, 'Thailand', 'Thaïlande (la)', 'TH', 'THA', 764),
(222, 'Timor-Leste', 'Timor-Leste (le)', 'TL', 'TLS', 626),
(223, 'Togo', 'Togo (le)', 'TG', 'TGO', 768),
(224, 'Tokelau', 'Tokelau (les)', 'TK', 'TKL', 772),
(225, 'Tonga', 'Tonga (les)', 'TO', 'TON', 776),
(226, 'Trinidad and Tobago', 'Trinité-et-Tobago (la)', 'TT', 'TTO', 780),
(227, 'Tunisia', 'Tunisie (la)', 'TN', 'TUN', 788),
(228, 'Turkey', 'Turquie (la)', 'TR', 'TUR', 792),
(229, 'Turkmenistan', 'Turkménistan (le)', 'TM', 'TKM', 795),
(230, 'Turks and Caicos Islands (the)', 'Turks-et-Caïcos (les Îles)', 'TC', 'TCA', 796),
(231, 'Tuvalu', 'Tuvalu (les)', 'TV', 'TUV', 798),
(232, 'Uganda', 'Ouganda (l\')', 'UG', 'UGA', 800),
(233, 'Ukraine', 'Ukraine (l\')', 'UA', 'UKR', 804),
(234, 'United Arab Emirates (the)', 'Émirats arabes unis (les)', 'AE', 'ARE', 784),
(235, 'United Kingdom of Great Britain and Northern Irela', 'Royaume-Uni de Grande-Bretagne et d\'Irlande du Nor', 'GB', 'GBR', 826),
(236, 'United States Minor Outlying Islands (the)', 'Îles mineures éloignées des États-Unis (les)', 'UM', 'UMI', 581),
(237, 'United States of America (the)', 'États-Unis d\'Amérique (les)', 'US', 'USA', 840),
(238, 'Uruguay', 'Uruguay (l\')', 'UY', 'URY', 858),
(239, 'Uzbekistan', 'Ouzbékistan (l\')', 'UZ', 'UZB', 860),
(240, 'Vanuatu', 'Vanuatu (le)', 'VU', 'VUT', 548),
(241, 'Venezuela (Bolivarian Republic of)', 'Venezuela (République bolivarienne du)', 'VE', 'VEN', 862),
(242, 'Viet Nam', 'Viet Nam (le)', 'VN', 'VNM', 704),
(243, 'Virgin Islands (British)', 'Vierges britanniques (les Îles)', 'VG', 'VGB', 92),
(244, 'Virgin Islands (U.S.)', 'Vierges des États-Unis (les Îles)', 'VI', 'VIR', 850),
(245, 'Wallis and Futuna', 'Wallis-et-Futuna ', 'WF', 'WLF', 876),
(246, 'Western Sahara*', 'Sahara occidental (le)*', 'EH', 'ESH', 732),
(247, 'Yemen', 'Yémen (le)', 'YE', 'YEM', 887),
(248, 'Zambia', 'Zambie (la)', 'ZM', 'ZMB', 894),
(249, 'Zimbabwe', 'Zimbabwe (le)', 'ZW', 'ZWE', 716);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `codpermisos` int(6) NULL,
  `codusuarios` int(6) NULL,
  `seccion` varchar(50) COLLATE latin1_spanish_ci NULL,
  `leer` tinyint(1) NULL,
  `escribir` tinyint(1) NULL,
  `modificar` tinyint(1) NULL,
  `eliminar` tinyint(1) NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='control de accesos';

--
-- Volcado de datos para la tabla `permisos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phpjobscheduler`
--

CREATE TABLE `phpjobscheduler` (
  `id` int(11) NULL,
  `scriptpath` varchar(255) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `time_interval` int(11) DEFAULT NULL,
  `fire_time` int(11) NULL DEFAULT '0',
  `time_last_fired` int(11) DEFAULT NULL,
  `run_only_once` tinyint(1) NULL DEFAULT '0',
  `currently_running` tinyint(1) NULL DEFAULT '0',
  `paused` tinyint(1) NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phpjobscheduler_logs`
--

CREATE TABLE `phpjobscheduler_logs` (
  `id` int(11) NULL,
  `date_added` int(11) DEFAULT NULL,
  `script` varchar(128) DEFAULT NULL,
  `output` text,
  `execution_time` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plandecuentas`
--

CREATE TABLE `plandecuentas` (
  `codplan` int(6) NULL,
  `nombre` varchar(70) COLLATE latin1_spanish_ci DEFAULT NULL,
  `m` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `operaen` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `borrado` int(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presulinea`
--

CREATE TABLE `presulinea` (
  `codpresupuesto` int(11) NULL DEFAULT '0',
  `numlinea` int(4) NULL,
  `codfamilia` int(3) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `codigo` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `cantidad` float NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `precio_compra` float(10,2) NULL,
  `precio` float NULL DEFAULT '0',
  `importe` float NULL DEFAULT '0',
  `dcto` tinyint(4) NULL DEFAULT '0',
  `archivos` varchar(300) DEFAULT NULL,
  `codsector` int(6) NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presulineatmp`
--

CREATE TABLE `presulineatmp` (
  `codpresupuesto` int(11) NULL DEFAULT '0',
  `numlinea` int(4) NULL,
  `codfamilia` int(3) DEFAULT NULL,
  `detalles` varchar(300) NULL,
  `codigo` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `cantidad` float NULL DEFAULT '0',
  `moneda` int(2) NULL,
  `precio_compra` float(10,2) NULL,
  `precio` float NULL DEFAULT '0',
  `importe` float NULL DEFAULT '0',
  `dcto` tinyint(4) NULL DEFAULT '0',
  `archivos` varchar(300) DEFAULT NULL,
  `codsector` int(6) NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `codpresupuesto` int(11) NULL,
  `codfactura` int(11) DEFAULT '0',
  `solicitado` varchar(300) NULL,
  `lugarentrega` varchar(300) NULL,
  `fecha` date NULL DEFAULT '0000-00-00',
  `fechaini` date NULL,
  `fechaentrega` date NULL,
  `sector` varchar(50) NULL,
  `codformapago` int(2) NULL,
  `requerimientos` longtext CHARACTER SET latin1 COLLATE latin1_spanish_ci NULL,
  `observaciones` longtext NULL,
  `iva` tinyint(4) NULL DEFAULT '0',
  `codcliente` int(5) DEFAULT '0',
  `tipo` int(1) NULL,
  `estado` varchar(1) CHARACTER SET utf8 DEFAULT '1',
  `tipocambio` decimal(7,4) DEFAULT NULL,
  `moneda` int(2) NULL,
  `descuento` int(2) NULL,
  `totalpresupuesto` float NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestostmp`
--

CREATE TABLE `presupuestostmp` (
  `codpresupuesto` int(11) NULL,
  `fecha` date NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Temporal de presupuestos para controlar acceso simultaneo';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `codproveedor` int(5) NULL,
  `nombre` varchar(45) NULL,
  `nif` varchar(12) NULL,
  `plancuenta` varchar(13) NULL,
  `codpais` int(4) NULL,
  `direccion` varchar(50) NULL,
  `codprovincia` int(2) NULL,
  `localidad` varchar(35) NULL,
  `codentidad` int(2) NULL,
  `cuentabancaria` varchar(20) NULL,
  `codpostal` varchar(5) NULL,
  `telefono` varchar(14) NULL,
  `movil` varchar(14) NULL,
  `email` varchar(35) NULL,
  `web` varchar(45) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Proveedores';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `codprovincia` int(2) NULL,
  `nombreprovincia` varchar(40) NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Provincias';

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`codprovincia`, `nombreprovincia`) VALUES
(1, 'Montevideo'),
(2, 'Canelones'),
(3, 'Maldonado'),
(4, 'Rocha'),
(5, 'Treinta y Tres'),
(6, 'Cerro Largo'),
(7, 'Rivera'),
(8, 'Artigas'),
(9, 'Salto'),
(10, 'Paysandú'),
(11, 'Rio Negro'),
(12, 'Soriano'),
(13, 'Colonia'),
(14, 'San José'),
(15, 'Florida'),
(16, 'Lavalleja'),
(17, 'Durazno'),
(18, 'Tacuarembó'),
(19, 'Flores');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos`
--

CREATE TABLE `recibos` (
  `codrecibo` int(6) NULL,
  `codcliente` int(6) NULL,
  `fecha` date NULL,
  `moneda` int(2) NULL,
  `importe` float NULL,
  `emitido` int(1) DEFAULT NULL,
  `enviado` int(1) NULL,
  `borrado` int(1) NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibosfactura`
--

CREATE TABLE `recibosfactura` (
  `recibosfacturacod` int(6) NULL,
  `codrecibo` int(6) NULL,
  `codfactura` int(6) NULL,
  `totalfactura` float DEFAULT NULL,
  `pago` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibosfacturatmp`
--

CREATE TABLE `recibosfacturatmp` (
  `codrecibo` int(6) NULL,
  `codfactura` int(6) NULL,
  `totalfactura` float DEFAULT NULL,
  `pago` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibospago`
--

CREATE TABLE `recibospago` (
  `codrecibopago` int(6) NULL,
  `codrecibo` int(6) DEFAULT NULL,
  `tipo` int(2) DEFAULT NULL,
  `codentidad` int(6) DEFAULT NULL,
  `numeroserie` int(3) DEFAULT NULL,
  `numero` int(9) DEFAULT NULL,
  `monedapago` int(2) DEFAULT NULL,
  `tipocambio` decimal(7,4) NULL,
  `importedoc` float DEFAULT NULL,
  `importe` float DEFAULT NULL,
  `fechapago` date DEFAULT NULL,
  `observaciones` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibospagotmp`
--

CREATE TABLE `recibospagotmp` (
  `codrecibopago` int(6) NULL,
  `codrecibo` int(6) DEFAULT NULL,
  `tipo` int(2) DEFAULT NULL,
  `codentidad` int(6) DEFAULT NULL,
  `numeroserie` int(3) DEFAULT NULL,
  `numero` int(9) DEFAULT NULL,
  `monedapago` int(2) DEFAULT NULL,
  `tipocambio` decimal(7,4) NULL,
  `importedoc` float DEFAULT NULL,
  `importe` float DEFAULT NULL,
  `fechapago` date DEFAULT NULL,
  `observaciones` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recoverymail`
--

CREATE TABLE `recoverymail` (
  `oid` bigint(6) UNSIGNED ZEROFILL NULL,
  `usuario` bigint(20) NULL,
  `key` varchar(32) NULL,
  `expDate` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respaldospc`
--

CREATE TABLE `respaldospc` (
  `codrespaldos` int(6) NULL,
  `fecha` date NULL,
  `message` longtext COLLATE latin1_spanish_ci NULL,
  `tarea` varchar(250) COLLATE latin1_spanish_ci NULL,
  `errores` varchar(100) COLLATE latin1_spanish_ci NULL,
  `procesados` int(6) NULL,
  `respaldados` int(6) NULL,
  `tamano` varchar(30) COLLATE latin1_spanish_ci NULL,
  `codcliente` int(6) NULL,
  `usuario` varchar(250) COLLATE latin1_spanish_ci NULL,
  `version` varchar(50) COLLATE latin1_spanish_ci NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Respaldos archivos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sector`
--

CREATE TABLE `sector` (
  `codsector` int(6) NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NULL,
  `color` varchar(50) COLLATE latin1_spanish_ci NULL,
  `borrado` int(1) NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `service`
--

CREATE TABLE `service` (
  `codservice` int(6) NULL,
  `codcliente` int(6) NULL,
  `fecha` date NULL,
  `codequipo` int(6) NULL,
  `tipo` varchar(50) COLLATE latin1_spanish_ci NULL,
  `detalles` longtext COLLATE latin1_spanish_ci NULL,
  `realizado` longtext COLLATE latin1_spanish_ci NULL,
  `solicito` varchar(150) COLLATE latin1_spanish_ci NULL,
  `horas` decimal(5,2) NULL,
  `estado` int(1) NULL,
  `factura` int(6) NULL,
  `facturado` date NULL,
  `importe` int(6) NULL,
  `borrado` int(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='Service realizado a clientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicetmp`
--

CREATE TABLE `servicetmp` (
  `codservice` int(6) NULL,
  `fecha` date NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabbackup`
--

CREATE TABLE `tabbackup` (
  `id` int(6) NULL,
  `denominacion` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `fecha` date NULL,
  `hora` time NULL,
  `archivo` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocambio`
--

CREATE TABLE `tipocambio` (
  `codtipocambio` int(6) NULL,
  `fecha` date NULL,
  `valor` decimal(7,4) NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `codubicacion` int(3) NULL,
  `nombre` varchar(50) NULL,
  `borrado` varchar(1) NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ubicaciones';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `codusuarios` int(6) NULL,
  `tratamiento` varchar(5) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE latin1_spanish_ci NULL,
  `apellido` varchar(255) COLLATE latin1_spanish_ci NULL,
  `estado` int(2) DEFAULT NULL,
  `tipo` int(2) NULL,
  `telefono` varchar(50) COLLATE latin1_spanish_ci NULL,
  `celular` varchar(50) COLLATE latin1_spanish_ci NULL,
  `direccion` varchar(200) COLLATE latin1_spanish_ci DEFAULT NULL,
  `departamento` int(6) DEFAULT NULL,
  `email` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `usuario` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `contrasenia` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `sessionid` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `secQ` tinyint(4) NULL DEFAULT '0',
  `secA` varchar(30) COLLATE latin1_spanish_ci NULL,
  `sector` varchar(50) COLLATE latin1_spanish_ci NULL,
  `borrado` int(1) NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`codusuarios`, `tratamiento`, `nombre`, `apellido`, `estado`, `tipo`, `telefono`, `celular`, `direccion`, `departamento`, `email`, `usuario`, `contrasenia`, `sessionid`, `secQ`, `secA`, `sector`, `borrado`) VALUES
(1, '2', 'Fernando', 'Gámbaro', 0, 0, '', '', '', 0, 'soporte@mcc.com.uy', 'soporte@mcc.com.uy', 'Jq5Jtac4wNQnU1IZwaNFez8d6XxGSESJi9vdcUgp4Jc', '', 1, 'montevideo', '1-2-3', 0),
(2, '5', 'demo', 'demo', 0, 0, '', '', '', 0, 'fgambaro@adinet.com.uy', 'demo@mcc.com.uy', 'e4iTzdhO_DAHg-LdKk6dH58DPzEtwSqCK1towNfvXsU', '', 6, 'montevideo', '1-2', 0),

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `albalinea`
--
ALTER TABLE `albalinea`
  ADD PRIMARY KEY (`codalbaran`,`numlinea`);

--
-- Indices de la tabla `albalineap`
--
ALTER TABLE `albalineap`
  ADD PRIMARY KEY (`codalbaran`,`codproveedor`,`numlinea`);

--
-- Indices de la tabla `albalineaptmp`
--
ALTER TABLE `albalineaptmp`
  ADD PRIMARY KEY (`codalbaran`,`numlinea`);

--
-- Indices de la tabla `albalineatmp`
--
ALTER TABLE `albalineatmp`
  ADD PRIMARY KEY (`codalbaran`,`numlinea`);

--
-- Indices de la tabla `albaranes`
--
ALTER TABLE `albaranes`
  ADD PRIMARY KEY (`codalbaran`);

--
-- Indices de la tabla `albaranesp`
--
ALTER TABLE `albaranesp`
  ADD PRIMARY KEY (`codalbaran`,`codproveedor`);

--
-- Indices de la tabla `albaranesptmp`
--
ALTER TABLE `albaranesptmp`
  ADD PRIMARY KEY (`codalbaran`);

--
-- Indices de la tabla `albaranestmp`
--
ALTER TABLE `albaranestmp`
  ADD PRIMARY KEY (`codalbaran`);

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`codarticulo`);

--
-- Indices de la tabla `artiviaja`
--
ALTER TABLE `artiviaja`
  ADD PRIMARY KEY (`codartiviaja`);

--
-- Indices de la tabla `artiviajalinea`
--
ALTER TABLE `artiviajalinea`
  ADD PRIMARY KEY (`codartiviaja`,`numlinea`);

--
-- Indices de la tabla `artiviajalineatmp`
--
ALTER TABLE `artiviajalineatmp`
  ADD PRIMARY KEY (`codartiviaja`,`numlinea`);

--
-- Indices de la tabla `artiviajatmp`
--
ALTER TABLE `artiviajatmp`
  ADD PRIMARY KEY (`codartiviaja`);

--
-- Indices de la tabla `artpro`
--
ALTER TABLE `artpro`
  ADD PRIMARY KEY (`codarticulo`,`codfamilia`,`codproveedor`);

--
-- Indices de la tabla `autofactulinea`
--
ALTER TABLE `autofactulinea`
  ADD PRIMARY KEY (`codautofactura`,`numlinea`);

--
-- Indices de la tabla `autofactulineatmp`
--
ALTER TABLE `autofactulineatmp`
  ADD PRIMARY KEY (`codautofactura`,`numlinea`);

--
-- Indices de la tabla `autofacturas`
--
ALTER TABLE `autofacturas`
  ADD PRIMARY KEY (`codautofactura`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`codcliente`);

--
-- Indices de la tabla `cobros`
--
ALTER TABLE `cobros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD UNIQUE KEY `coddatos` (`coddatos`);

--
-- Indices de la tabla `elementos`
--
ALTER TABLE `elementos`
  ADD PRIMARY KEY (`codelemento`);

--
-- Indices de la tabla `embalajes`
--
ALTER TABLE `embalajes`
  ADD PRIMARY KEY (`codembalaje`);

--
-- Indices de la tabla `entidades`
--
ALTER TABLE `entidades`
  ADD PRIMARY KEY (`codentidad`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD UNIQUE KEY `codequipo` (`codequipo`);

--
-- Indices de la tabla `factulinea`
--
ALTER TABLE `factulinea`
  ADD PRIMARY KEY (`codfactura`,`numlinea`);

--
-- Indices de la tabla `factulineap`
--
ALTER TABLE `factulineap`
  ADD PRIMARY KEY (`codfactura`,`codproveedor`,`numlinea`);

--
-- Indices de la tabla `factulineaptmp`
--
ALTER TABLE `factulineaptmp`
  ADD PRIMARY KEY (`codfactura`,`numlinea`);

--
-- Indices de la tabla `factulineatmp`
--
ALTER TABLE `factulineatmp`
  ADD PRIMARY KEY (`codfactura`,`numlinea`);

--
-- Indices de la tabla `facturanota`
--
ALTER TABLE `facturanota`
  ADD UNIQUE KEY `facturanotaid` (`facturanotaid`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`codfactura`);

--
-- Indices de la tabla `facturasp`
--
ALTER TABLE `facturasp`
  ADD PRIMARY KEY (`codfactura`,`codproveedor`);

--
-- Indices de la tabla `facturasptmp`
--
ALTER TABLE `facturasptmp`
  ADD PRIMARY KEY (`codfactura`);

--
-- Indices de la tabla `facturastmp`
--
ALTER TABLE `facturastmp`
  ADD PRIMARY KEY (`codfactura`);

--
-- Indices de la tabla `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`codfamilia`);

--
-- Indices de la tabla `formapago`
--
ALTER TABLE `formapago`
  ADD PRIMARY KEY (`codformapago`);

--
-- Indices de la tabla `foto`
--
ALTER TABLE `foto`
  ADD UNIQUE KEY `CONTACTOSID` (`oid`);

--
-- Indices de la tabla `historialautofactura`
--
ALTER TABLE `historialautofactura`
  ADD PRIMARY KEY (`codhistoria`);

--
-- Indices de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  ADD PRIMARY KEY (`codimpuesto`);

--
-- Indices de la tabla `librodiario`
--
ALTER TABLE `librodiario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`logid`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`codmodelo`);

--
-- Indices de la tabla `monedas`
--
ALTER TABLE `monedas`
  ADD PRIMARY KEY (`numerico`),
  ADD UNIQUE KEY `codmoneda` (`codmoneda`);

--
-- Indices de la tabla `ncredito`
--
ALTER TABLE `ncredito`
  ADD PRIMARY KEY (`codncredito`);

--
-- Indices de la tabla `ncreditolinea`
--
ALTER TABLE `ncreditolinea`
  ADD PRIMARY KEY (`codncredito`,`numlinea`);

--
-- Indices de la tabla `ncreditolineatmp`
--
ALTER TABLE `ncreditolineatmp`
  ADD PRIMARY KEY (`codncredito`,`numlinea`);

--
-- Indices de la tabla `pagodgi`
--
ALTER TABLE `pagodgi`
  ADD PRIMARY KEY (`codpagodgi`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`codpais`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`codpermisos`);

--
-- Indices de la tabla `phpjobscheduler`
--
ALTER TABLE `phpjobscheduler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fire_time` (`fire_time`);

--
-- Indices de la tabla `phpjobscheduler_logs`
--
ALTER TABLE `phpjobscheduler_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `plandecuentas`
--
ALTER TABLE `plandecuentas`
  ADD PRIMARY KEY (`codplan`);

--
-- Indices de la tabla `presulinea`
--
ALTER TABLE `presulinea`
  ADD PRIMARY KEY (`codpresupuesto`,`numlinea`);

--
-- Indices de la tabla `presulineatmp`
--
ALTER TABLE `presulineatmp`
  ADD PRIMARY KEY (`codpresupuesto`,`numlinea`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`codpresupuesto`);

--
-- Indices de la tabla `presupuestostmp`
--
ALTER TABLE `presupuestostmp`
  ADD PRIMARY KEY (`codpresupuesto`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`codproveedor`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`codprovincia`);

--
-- Indices de la tabla `recibos`
--
ALTER TABLE `recibos`
  ADD UNIQUE KEY `codrecibo` (`codrecibo`);

--
-- Indices de la tabla `recibosfactura`
--
ALTER TABLE `recibosfactura`
  ADD PRIMARY KEY (`recibosfacturacod`),
  ADD KEY `codigo` (`codrecibo`);

--
-- Indices de la tabla `recibosfacturatmp`
--
ALTER TABLE `recibosfacturatmp`
  ADD KEY `codigo` (`codrecibo`);

--
-- Indices de la tabla `recibospago`
--
ALTER TABLE `recibospago`
  ADD KEY `codigo` (`codrecibopago`);

--
-- Indices de la tabla `recibospagotmp`
--
ALTER TABLE `recibospagotmp`
  ADD KEY `codigo` (`codrecibopago`);

--
-- Indices de la tabla `recoverymail`
--
ALTER TABLE `recoverymail`
  ADD PRIMARY KEY (`oid`);

--
-- Indices de la tabla `respaldospc`
--
ALTER TABLE `respaldospc`
  ADD PRIMARY KEY (`codrespaldos`);

--
-- Indices de la tabla `sector`
--
ALTER TABLE `sector`
  ADD PRIMARY KEY (`codsector`);

--
-- Indices de la tabla `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`codservice`);

--
-- Indices de la tabla `servicetmp`
--
ALTER TABLE `servicetmp`
  ADD PRIMARY KEY (`codservice`);

--
-- Indices de la tabla `tabbackup`
--
ALTER TABLE `tabbackup`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipocambio`
--
ALTER TABLE `tipocambio`
  ADD PRIMARY KEY (`codtipocambio`),
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`codubicacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`codusuarios`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `albalinea`
--
ALTER TABLE `albalinea`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `albalineap`
--
ALTER TABLE `albalineap`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `albalineaptmp`
--
ALTER TABLE `albalineaptmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `albalineatmp`
--
ALTER TABLE `albalineatmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `albaranes`
--
ALTER TABLE `albaranes`
  MODIFY `codalbaran` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `albaranesptmp`
--
ALTER TABLE `albaranesptmp`
  MODIFY `codalbaran` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `albaranestmp`
--
ALTER TABLE `albaranestmp`
  MODIFY `codalbaran` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `codarticulo` int(10) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `artiviajalinea`
--
ALTER TABLE `artiviajalinea`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `artiviajalineatmp`
--
ALTER TABLE `artiviajalineatmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `artiviajatmp`
--
ALTER TABLE `artiviajatmp`
  MODIFY `codartiviaja` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `autofactulinea`
--
ALTER TABLE `autofactulinea`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `autofactulineatmp`
--
ALTER TABLE `autofactulineatmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `codcliente` int(5) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `cobros`
--
ALTER TABLE `cobros`
  MODIFY `id` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `elementos`
--
ALTER TABLE `elementos`
  MODIFY `codelemento` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `embalajes`
--
ALTER TABLE `embalajes`
  MODIFY `codembalaje` int(3) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `entidades`
--
ALTER TABLE `entidades`
  MODIFY `codentidad` int(2) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `codequipo` int(5) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `factulinea`
--
ALTER TABLE `factulinea`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `factulineap`
--
ALTER TABLE `factulineap`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `factulineaptmp`
--
ALTER TABLE `factulineaptmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `factulineatmp`
--
ALTER TABLE `factulineatmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `facturanota`
--
ALTER TABLE `facturanota`
  MODIFY `facturanotaid` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `facturasptmp`
--
ALTER TABLE `facturasptmp`
  MODIFY `codfactura` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `facturastmp`
--
ALTER TABLE `facturastmp`
  MODIFY `codfactura` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `familias`
--
ALTER TABLE `familias`
  MODIFY `codfamilia` int(5) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `formapago`
--
ALTER TABLE `formapago`
  MODIFY `codformapago` int(2) NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `historialautofactura`
--
ALTER TABLE `historialautofactura`
  MODIFY `codhistoria` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  MODIFY `codimpuesto` int(3) NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `librodiario`
--
ALTER TABLE `librodiario`
  MODIFY `id` int(8) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `logid` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `monedas`
--
ALTER TABLE `monedas`
  MODIFY `codmoneda` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
--
-- AUTO_INCREMENT de la tabla `ncreditolinea`
--
ALTER TABLE `ncreditolinea`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ncreditolineatmp`
--
ALTER TABLE `ncreditolineatmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pagodgi`
--
ALTER TABLE `pagodgi`
  MODIFY `codpagodgi` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `codpais` int(3) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `codpermisos` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `phpjobscheduler`
--
ALTER TABLE `phpjobscheduler`
  MODIFY `id` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `phpjobscheduler_logs`
--
ALTER TABLE `phpjobscheduler_logs`
  MODIFY `id` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT de la tabla `presulinea`
--
ALTER TABLE `presulinea`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `presulineatmp`
--
ALTER TABLE `presulineatmp`
  MODIFY `numlinea` int(4) NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `codpresupuesto` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `presupuestostmp`
--
ALTER TABLE `presupuestostmp`
  MODIFY `codpresupuesto` int(11) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `codproveedor` int(5) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `provincias`
--
ALTER TABLE `provincias`
  MODIFY `codprovincia` int(2) NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT de la tabla `recibosfactura`
--
ALTER TABLE `recibosfactura`
  MODIFY `recibosfacturacod` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `recibospago`
--
ALTER TABLE `recibospago`
  MODIFY `codrecibopago` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `recibospagotmp`
--
ALTER TABLE `recibospagotmp`
  MODIFY `codrecibopago` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `recoverymail`
--
ALTER TABLE `recoverymail`
  MODIFY `oid` bigint(6) UNSIGNED ZEROFILL NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `respaldospc`
--
ALTER TABLE `respaldospc`
  MODIFY `codrespaldos` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `sector`
--
ALTER TABLE `sector`
  MODIFY `codsector` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `service`
--
ALTER TABLE `service`
  MODIFY `codservice` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `servicetmp`
--
ALTER TABLE `servicetmp`
  MODIFY `codservice` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `tabbackup`
--
ALTER TABLE `tabbackup`
  MODIFY `id` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `tipocambio`
--
ALTER TABLE `tipocambio`
  MODIFY `codtipocambio` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `codubicacion` int(3) NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `codusuarios` int(6) NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
e="Caja" ea