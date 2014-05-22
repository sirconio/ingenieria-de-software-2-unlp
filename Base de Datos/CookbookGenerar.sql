SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `CookBook` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `CookBook` ;

-- -----------------------------------------------------
-- Table `CookBook`.`Autor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Autor` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Autor` (
  `Id_Autor` INT NOT NULL AUTO_INCREMENT,
  `NombreApellido` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id_Autor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Idioma`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Idioma` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Idioma` (
  `Id_Idioma` INT NOT NULL AUTO_INCREMENT,
  `Descripcion` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id_Idioma`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Disponibilidad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Disponibilidad` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Disponibilidad` (
  `Id_Disponibilidad` INT NOT NULL AUTO_INCREMENT,
  `Descripcion` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id_Disponibilidad`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Libro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Libro` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Libro` (
  `ISBN` INT NOT NULL,
  `Titulo` VARCHAR(45) NOT NULL,
  `Id_Autor` INT NOT NULL,
  `CantidadPaginas` INT NOT NULL,
  `Precio` FLOAT NOT NULL,
  `Id_Idioma` INT NOT NULL,
  `Fecha` DATE NOT NULL,
  `Id_Disponibilidad` INT NOT NULL,
  `Visible` TINYINT(1) NOT NULL,
  `Hojear` VARCHAR(255) NULL,
  PRIMARY KEY (`ISBN`),
  INDEX `Id_Autor_idx` (`Id_Autor` ASC),
  INDEX `Id_Idioma_idx` (`Id_Idioma` ASC),
  INDEX `Id_Disponibilidad_idx` (`Id_Disponibilidad` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Etiqueta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Etiqueta` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Etiqueta` (
  `Id_Etiqueta` INT NOT NULL AUTO_INCREMENT,
  `Descripcion` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id_Etiqueta`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Cliente` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Cliente` (
  `DNI` INT NOT NULL,
  `NombreApellido` VARCHAR(45) NOT NULL,
  `FechaAlta` DATE NOT NULL,
  `Telefono` VARCHAR(10) NULL,
  `Direccion` VARCHAR(30) NULL,
  `Contacto` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`DNI`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Estado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Estado` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Estado` (
  `Id_Estado` INT NOT NULL AUTO_INCREMENT,
  `Descripcion` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Id_Estado`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Pedidos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Pedidos` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Pedidos` (
  `ISBN` INT NOT NULL,
  `DNI` INT NOT NULL,
  `FechaPedido` DATE NOT NULL,
  `Id_Estado` INT NOT NULL,
  PRIMARY KEY (`ISBN`, `DNI`),
  INDEX `DNI_idx` (`DNI` ASC),
  INDEX `Id_Estado_idx` (`Id_Estado` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Usuario` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Usuario` (
  `Id_Usuario` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(10) NOT NULL,
  `Password` VARCHAR(45) NOT NULL,
  `Categoria` VARCHAR(45) NOT NULL,
  `DNI` INT NOT NULL,
  `Visible` TINYINT(1) NOT NULL,
  `CantCarrito` INT NOT NULL,
  PRIMARY KEY (`Id_Usuario`),
  INDEX `DNI_idx` (`DNI` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Etiqueta_Libro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Etiqueta_Libro` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Etiqueta_Libro` (
  `Id_EtiquetaLibro` INT NOT NULL AUTO_INCREMENT,
  `Id_Etiqueta` INT NOT NULL,
  `ISBN` INT NOT NULL,
  PRIMARY KEY (`Id_EtiquetaLibro`),
  INDEX `ISBN_idx` (`ISBN` ASC),
  INDEX `Id_Etiqueta_idx` (`Id_Etiqueta` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CookBook`.`Carrito`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CookBook`.`Carrito` ;

CREATE TABLE IF NOT EXISTS `CookBook`.`Carrito` (
  `Id_Carrito` INT NOT NULL AUTO_INCREMENT,
  `Id_Usuario` INT NOT NULL,
  `ISBN` INT NOT NULL,
  PRIMARY KEY (`Id_Carrito`),
  INDEX `ISBN_idx` (`ISBN` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `CookBook`.`Autor`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (1, 'Carmen Valldejuli');
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (2, 'Kristen Feola');
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (3, 'Mirta G. Carabajal');
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (4, 'Petrona C. de Gandulfo');
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (5, 'Christine Bailey');
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (6, 'Toni Rodriguez');
INSERT INTO `CookBook`.`Autor` (`Id_Autor`, `NombreApellido`) VALUES (7, 'Cecilia Fassardi');

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Idioma`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Idioma` (`Id_Idioma`, `Descripcion`) VALUES (1, 'Español');

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Disponibilidad`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Disponibilidad` (`Id_Disponibilidad`, `Descripcion`) VALUES (1, 'Disponoble');
INSERT INTO `CookBook`.`Disponibilidad` (`Id_Disponibilidad`, `Descripcion`) VALUES (2, 'Agotado');

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Libro`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (882894293, 'Cocina Criolla', 1, 87, 59.99, 1, '1983-03-31', 1, True, NULL);
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (123456789, 'La guia optima para el ayuno de Daniel', 2, 68, 69.00, 1, '2001-08-25', 1, True, NULL);
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (879548481, 'Las mejores recetas de rico y abundante', 3, 70, 87.45, 1, '2012-07-24', 1, True, NULL);
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (888444777, 'Cocina con calor de hogar - rustica', 4, 154, 152.21, 1, '2006-06-06', 1, True, NULL);
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (878987655, 'La dieta de los zumos', 5, 54, 99.99, 1, '1999-05-03', 1, True, NULL);
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (1478523698, 'Cupcakes veganos', 6, 55, 47.80, 1, '2011-01-02', 1, True, NULL);
INSERT INTO `CookBook`.`Libro` (`ISBN`, `Titulo`, `Id_Autor`, `CantidadPaginas`, `Precio`, `Id_Idioma`, `Fecha`, `Id_Disponibilidad`, `Visible`, `Hojear`) VALUES (8521479632, 'El libro de las viandas para pequeños', 7, 87, 79.84, 1, '2012-01-01', 1, True, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Etiqueta`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (1, 'Criolla');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (2, 'Guia');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (3, 'Recetas');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (4, 'Rustica');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (5, 'Zumos');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (6, 'Jugos');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (7, 'Cupcakes');
INSERT INTO `CookBook`.`Etiqueta` (`Id_Etiqueta`, `Descripcion`) VALUES (8, 'Viandas');

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Cliente`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (11454789, 'Carlos Sanchez', '1983-03-31', '4515151', '60 N512', 'carlos@hotmail.com');
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (10222333, 'Roberto Juarez', '2001-08-25', '4515151', '60 N512', 'roberto@hotmail.com');
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (30876961, 'Ariel Pasini', '2012-07-24', '4515151', '60 N512', 'ariel@hotmail.com');
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (2968741, 'Nicolas Galdamez', '2006-06-06', '4515151', '60 N512', 'nicolas@hotmail.com');
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (3478987, 'Sebastian Eguren', '1999-05-03', '4515151', '60 N512', 'sebastian@hotmail.com');
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (12547897, 'Maria Lopez', '2011-01-02', '4515151', '60 N512', 'maria@hotmail.com');
INSERT INTO `CookBook`.`Cliente` (`DNI`, `NombreApellido`, `FechaAlta`, `Telefono`, `Direccion`, `Contacto`) VALUES (14879564, 'Catalina Perez', '2012-01-01', '4515151', '60 N512', 'catalina@hotmail.com');

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Estado`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Estado` (`Id_Estado`, `Descripcion`) VALUES (1, 'Pendiente');
INSERT INTO `CookBook`.`Estado` (`Id_Estado`, `Descripcion`) VALUES (2, 'Enviado');
INSERT INTO `CookBook`.`Estado` (`Id_Estado`, `Descripcion`) VALUES (3, 'Entregado');

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Pedidos`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (882894293, 11454789, '2013-03-31', 1);
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (124356789, 10222333, '2013-08-25', 1);
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (879548481, 30876961, '2013-07-24', 1);
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (888444777, 2968741, '2013-06-06', 1);
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (878987655, 3478987, '2013-05-03', 1);
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (1478523698, 12547897, '2011-01-02', 3);
INSERT INTO `CookBook`.`Pedidos` (`ISBN`, `DNI`, `FechaPedido`, `Id_Estado`) VALUES (8521479632, 14879564, '2012-01-01', 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Usuario`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (1, 'Ruben', 'admin', 'Administrador', 0, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (2, 'Carlos', 'carlos', 'Normal', 11454789, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (3, 'Roberto', 'roberto', 'Normal', 10222333, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (4, 'Ariel', 'ariel', 'Normal', 30876961, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (5, 'Nicolas', 'nicolas', 'Normal', 2968741, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (6, 'Sebastian', 'sebastian', 'Normal', 3478987, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (7, 'Maria', 'maria', 'Normal', 12547897, True, 0);
INSERT INTO `CookBook`.`Usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) VALUES (8, 'Catalina', 'catalina', 'Normal', 14879564, True, 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `CookBook`.`Etiqueta_Libro`
-- -----------------------------------------------------
START TRANSACTION;
USE `CookBook`;
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (1, 1, 882894293);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (2, 2, 123456789);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (3, 3, 879548481);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (4, 4, 888444777);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (5, 5, 878987655);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (6, 6, 878987655);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (7, 7, 1478523698);
INSERT INTO `CookBook`.`Etiqueta_Libro` (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (8, 8, 8521479632);

COMMIT;

