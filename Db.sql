/*Eliminar Vistas*/

DROP VIEW SESSION_START;

DROP VIEW PRODUCT_DETAIL;

DROP VIEW PRODUCT_LIST;

DROP VIEW PRODUCT_LIST_MANAGEMENT;

/*Eliminar Tablas*/

DROP TABLE USERS;

DROP TABLE PRODUCTS;

DROP TABLE PAYS;

DROP TABLE DIRECTIONS;

/*Eliminar Triggers*/

DROP TRIGGER USERS_TRG;

DROP TRIGGER PRODUCTS_TRG;

DROP TRIGGER PAYS_TRG;

DROP TRIGGER DIRECTIONS_TRG;

/*Eliminar Secuencias*/

DROP SEQUENCE USERS_SEQ;

DROP SEQUENCE PRODUCTS_SEQ;

DROP SEQUENCE DIRECTIONS_SEQ;

DROP SEQUENCE PAYS_SEQ;

/*Crear Tablas*/

CREATE TABLE USERS (
    ID              NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    CODE            VARCHAR2(200) NOT NULL,
    NAME            VARCHAR2(200) NOT NULL,
    SURNAME         VARCHAR2(250) NOT NULL,
    BIRTHDATE       DATE NOT NULL,
    GENRE           VARCHAR2(200) NOT NULL,
    PHONE           NUMBER NOT NULL,
    EMAIL           VARCHAR2(200) NOT NULL,
    PASSWORD        VARCHAR2(200) NOT NULL,
    IMAGE           VARCHAR2(200) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT users_pk PRIMARY KEY (ID)
);

CREATE TABLE PRODUCTS (
    ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    NAME            VARCHAR2(200) NOT NULL,
    PRICE           NUMBER NOT NULL,
    UNITS           NUMBER NOT NULL,
    CONTENT         VARCHAR2(200) NOT NULL,
    STOCK           NUMBER NOT NULL,
    DESCRIPTION     VARCHAR2(200) NOT NULL,
    IMAGE           VARCHAR2(200) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT products_pk PRIMARY KEY (ID),
    CONSTRAINT products_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(ID)
);

CREATE TABLE PAYS (
    ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    ELECTION        VARCHAR2(200) NOT NULL,
    NUMBER_ELECTION NUMBER NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT pays_pk PRIMARY KEY (ID),
    CONSTRAINT pays_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(ID)
);

CREATE TABLE DIRECTIONS (
    ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    CARRER          VARCHAR2(200) NOT NULL,
    STREET          VARCHAR2(250) NOT NULL,
    POSTAL_CODE     NUMBER NOT NULL,
    DIRECTION       VARCHAR2(200) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT directions_pk PRIMARY KEY (ID),
    CONSTRAINT directions_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(ID)
);

/*Crear Secuencias*/

CREATE SEQUENCE USERS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE PRODUCTS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE PAYS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE DIRECTIONS_SEQ
START WITH 1
INCREMENT BY 1;

/*Crear Triggers*/

CREATE OR REPLACE TRIGGER USERS_TRG
BEFORE INSERT ON USERS
FOR EACH ROW
BEGIN
    IF :NEW.ID IS NULL THEN
        SELECT EDUARDED.USERS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PRODUCTS_TRG
BEFORE INSERT ON PRODUCTS
FOR EACH ROW
BEGIN
    IF :NEW.ID IS NULL THEN
        SELECT EDUARDED.PRODUCTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PAYS_TRG
BEFORE INSERT ON PAYS
FOR EACH ROW
BEGIN
    IF :NEW.ID IS NULL THEN
        SELECT EDUARDED.PAYS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER DIRECTIONS_TRG
BEFORE INSERT ON DIRECTIONS
FOR EACH ROW
BEGIN
    IF :NEW.ID IS NULL THEN
        SELECT EDUARDED.DIRECTIONS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
    END IF;
END;

/*Crear o Reemplazar Vistas*/

CREATE OR REPLACE VIEW SESSION_START AS
SELECT ID, ACTIVE, CODE, NAME, SURNAME, PHONE, EMAIL, PASSWORD, IMAGE
FROM users;

CREATE OR REPLACE VIEW PRODUCT_DETAIL AS
SELECT ID, NAME, PRICE, UNITS, CONTENT, STOCK, DESCRIPTION, IMAGE
FROM products;

CREATE OR REPLACE VIEW PRODUCT_LIST AS
SELECT ID, ACTIVE, NAME, PRICE, IMAGE
FROM products;

CREATE OR REPLACE VIEW PRODUCT_LIST_MANAGEMENT AS
SELECT ID, ACTIVE, NAME, PRICE, UNITS, CONTENT, STOCK
FROM products;

CREATE OR REPLACE VIEW DIRECTIONS_LIST_MANAGEMENT AS
SELECT ID, ACTIVE, CARRER, STREET, POSTAL_CODE, DIRECTION 
FROM directions;

CREATE OR REPLACE VIEW PAYS_LIST_MANAGEMENT AS
SELECT ID, ACTIVE, ELECTION, NUMBER_ELECTION
FROM pays;

/*Crear o reemplazar funciones*/

create or replace FUNCTION DELETE_DIRECTION(d_direction_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE directions
    SET active = 0
    WHERE id = d_direction_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar la direccion';
END;

create or replace FUNCTION DELETE_PAY(p_pay_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE pays
    SET active = 0
    WHERE id = p_pay_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_PRODUCT(p_product_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE products
    SET active = 0
    WHERE id = p_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el producto';
END;

create or replace FUNCTION DELETE_USER(u_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET active = 0
    WHERE id = u_user_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el usuario';
END;

create or replace FUNCTION DETAIL_PRODUCT(p_product_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM PRODUCT_DETAIL 
        WHERE id = p_product_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_PRODUCT;

create or replace FUNCTION DETAIL_PRODUCT_LIST
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los productos
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCT_LIST;

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END DETAIL_PRODUCT_LIST;

create or replace FUNCTION DIRECTION_LIST_MANAGEMENT
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todas las direcciones donde active sea igual a 1
    OPEN v_cursor FOR
    SELECT *
    FROM DIRECTIONS_LIST_MANAGEMENT
    WHERE ACTIVE = 1;

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END DIRECTION_LIST_MANAGEMENT;

create or replace FUNCTION GET_DIRECTION(d_direction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM DIRECTIONS_LIST_MANAGEMENT 
        WHERE id = d_direction_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_DIRECTION;

create or replace FUNCTION GET_PASSWORD(p_email IN VARCHAR2)
RETURN VARCHAR2
IS
    v_password VARCHAR2(255);
BEGIN
    -- Asegúrate de usar un filtro que garantice una única fila
    SELECT password
    INTO v_password
    FROM USERS
    WHERE email = p_email
    AND ROWNUM = 1;  -- Garantiza que solo se obtenga una fila

    RETURN v_password;

EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN TOO_MANY_ROWS THEN
        -- Maneja el caso en que se devuelvan múltiples filas (opcional)
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PASSWORD;

create or replace FUNCTION GET_PAY(p_pay_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM PAYS_LIST_MANAGEMENT 
        WHERE id = p_pay_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PAY;

create or replace FUNCTION GET_PRODUCT(p_product_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM PRODUCT_DETAIL 
        WHERE id = p_product_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PRODUCT;

create or replace FUNCTION LOGIN(u_email IN VARCHAR2)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor con el registro del usuario que coincide por email y está activo
    OPEN v_cursor FOR
    SELECT *
    FROM SESSION_START
    WHERE email = u_email
    AND ACTIVE = 1;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        -- Manejo de excepción si no se encuentra el usuario
        RETURN NULL;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END LOGIN;

create or replace FUNCTION PAY_LIST_MANAGEMENT
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde active sea igual a 1
    OPEN v_cursor FOR
    SELECT *
    FROM PAYS_LIST_MANAGEMENT
    WHERE ACTIVE = 1;

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PAY_LIST_MANAGEMENT;

create or replace FUNCTION PRODUCTS_LIST
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los productos donde active sea igual a 1
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCT_LIST
    WHERE ACTIVE = 1;

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST;

create or replace FUNCTION PRODUCTS_LIST_MANAGEMENT
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los productos donde active sea igual a 1
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCT_LIST_MANAGEMENT
    WHERE ACTIVE = 1;

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_MANAGEMENT;

create or replace FUNCTION REGISTER_DIRECTION(
    d_user_id IN NUMBER,
    d_active IN NUMBER,
    d_carrer IN VARCHAR2,
    d_street IN VARCHAR2,
    d_postal_code IN NUMBER,
    d_direction IN VARCHAR2, 
    d_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO DIRECTIONS (user_id, active, carrer, street, postal_code, direction, created_at)
        VALUES (d_user_id, d_active, d_carrer, d_street, d_postal_code, d_direction, d_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar direccion: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_PAY(
    p_user_id IN NUMBER,
    p_active IN NUMBER,
    p_election IN VARCHAR2,
    p_number_election IN NUMBER,
    p_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO PAYS (user_id, active, election, number_election, created_at)
        VALUES (p_user_id, p_active, p_election, p_number_election, p_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar pago: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_PRODUCT(
    p_user_id IN NUMBER,
    p_active IN NUMBER,
    p_name IN VARCHAR2,
    p_price IN NUMBER,
    p_units IN NUMBER,
    p_content IN VARCHAR2,
    p_stock IN NUMBER,
    p_description IN VARCHAR2,
    p_image IN VARCHAR2,    
    p_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO PRODUCTS (user_id, active, name, price, units, content, stock, description, image, created_at)
        VALUES (p_user_id, p_active, p_name, p_price, p_units, p_content, p_stock, p_description, p_image, p_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar producto: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_USER(
    u_active IN NUMBER,
    u_code IN VARCHAR2,
    u_name IN VARCHAR2,
    u_surname IN VARCHAR2,
    u_birthdate IN DATE,
    u_genre IN VARCHAR2,
    u_phone IN NUMBER,
    u_email IN VARCHAR2,
    u_password IN VARCHAR2,
    u_image IN VARCHAR2,
    u_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO USERS (active, code, name, surname, birthdate, genre, phone, email, password, image, created_at)
        VALUES (u_active, u_code, u_name, u_surname, u_birthdate, u_genre, u_phone, u_email, u_password, u_image, u_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION UPDATE_DIRECTION(d_direction_id IN NUMBER, d_carrer IN VARCHAR2, d_street IN VARCHAR2, d_postal_code IN NUMBER, d_direction IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE directions
    SET carrer = d_carrer,
        street = d_street,
        postal_code = d_postal_code,
        direction = d_direction
    WHERE id = d_direction_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar la direccion';
END;

create or replace FUNCTION UPDATE_PAY(p_pay_id IN NUMBER, p_election IN VARCHAR2, p_number_election) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE pays
    SET election = p_election,
        number_election = p_number_election
    WHERE id = p_pay_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar la direccion';
END;

create or replace FUNCTION UPDATE_PRODUCT(p_product_id IN NUMBER, p_name IN VARCHAR2, p_price IN NUMBER , p_units IN NUMBER, p_content IN VARCHAR2, p_stock IN NUMBER, p_description IN VARCHAR2, p_image IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE products
    SET name = p_name,
        price = p_price,
        units = p_units,
        content = p_content,
        stock = p_stock,
        description = p_description,
        image = p_image
    WHERE id = p_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el producto';
END;

create or replace FUNCTION UPDATE_USER(u_user_id IN NUMBER, u_name IN VARCHAR2, u_surname IN VARCHAR2 , u_phone IN NUMBER, u_email IN VARCHAR2, u_image IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET name = u_name,
        surname = u_surname,
        phone = u_phone,
        email = u_email,
        image = u_image
    WHERE id = u_user_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el producto';
END;

create or replace FUNCTION VALIDATE_UNIQUE_EMAIL(u_email VARCHAR2)
RETURN NUMBER
IS
    email_existe NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO email_existe
    FROM users
    WHERE email = u_email;

    IF email_existe > 0 THEN
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
END VALIDATE_UNIQUE_EMAIL;