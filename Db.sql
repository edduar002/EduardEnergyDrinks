/*Eliminar Vistas*/

DROP VIEW SESSION_START;

DROP VIEW PRODUCT_DETAIL;

DROP VIEW PRODUCT_LIST;

DROP VIEW TRANSACTIONS_LIST;

DROP VIEW PRODUCT_LIST_MANAGEMENT;

DROP VIEW DIRECTIONS_LIST_MANAGEMENT;

DROP VIEW PAYS_LIST_MANAGEMENT;

DROP VIEW PRODUCT_DATA_PU;

/*Eliminar Tablas*/

DROP TABLE USERS;

DROP TABLE PRODUCTS;

DROP TABLE PAYS;

DROP TABLE DIRECTIONS;

DROP TABLE TRANSACTIONS;

DROP TABLE TRANSACTIONPRODUCT;

DROP TABLE CARS;

DROP TABLE CARPRODUCT;

DROP TABLE ADMINISTRATORS;

/*Eliminar Secuencias*/

DROP SEQUENCE USERS_SEQ;

DROP SEQUENCE PRODUCTS_SEQ;

DROP SEQUENCE DIRECTIONS_SEQ;

DROP SEQUENCE PAYS_SEQ;

DROP SEQUENCE TRANSACTIONS_SEQ;

DROP SEQUENCE TRPR_SEQ;

DROP SEQUENCE CARS_SEQ;

DROP SEQUENCE CARPR_SEQ;

DROP SEQUENCE ADMINISTRATORS_SEQ;

/*Crear Tablas*/

CREATE TABLE USERS (
    USER_ID          NUMBER NOT NULL,
    ACTIVE           NUMBER(1) NOT NULL,
    USER_LEVEL       NUMBER(1) NOT NULL,
    CODE             VARCHAR2(10) NOT NULL,
    NAME             VARCHAR2(30) NOT NULL,
    SURNAME          VARCHAR2(40) NOT NULL,
    BIRTHDATE        DATE NOT NULL,
    GENRE            VARCHAR2(10) NOT NULL,
    PHONE            NUMBER NOT NULL,
    EMAIL            VARCHAR2(30) NOT NULL,
    USER_PASSWORD    VARCHAR2(100) NOT NULL,
    IMAGE            VARCHAR2(100) NOT NULL,
    EARNINGS         NUMBER NOT NULL,
    HIGHER_USER_ID   NUMBER NULL,
    CREATED_AT       DATE NOT NULL,
    CONSTRAINT users_pk PRIMARY KEY (USER_ID),
    CONSTRAINT higher_fk FOREIGN KEY (HIGHER_USER_ID) REFERENCES USERS(USER_ID)
);

CREATE TABLE PRODUCTS (
    PRODUCT_ID             NUMBER NOT NULL,
    USER_ID         NUMBER NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    NAME            VARCHAR2(30) NOT NULL,
    PRICE           NUMBER NOT NULL,
    UNITS           NUMBER NOT NULL,
    CONTENT         VARCHAR2(10) NOT NULL,
    STOCK           NUMBER NOT NULL,
    DESCRIPTION     VARCHAR2(60) NOT NULL,
    IMAGE           VARCHAR2(100) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT products_pk PRIMARY KEY (PRODUCT_ID),
    CONSTRAINT products_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID)
);

CREATE TABLE PAYS (
    PAY_ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    ELECTION        VARCHAR2(20) NOT NULL,
    NUMBER_ELECTION NUMBER NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT pays_pk PRIMARY KEY (PAY_ID),
    CONSTRAINT pays_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID)
);

CREATE TABLE DIRECTIONS (
    DIRECTION_ID              NUMBER NOT NULL,
    USER_ID         NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    CARRER          VARCHAR2(15) NOT NULL,
    STREET          VARCHAR2(15) NOT NULL,
    POSTAL_CODE     NUMBER NOT NULL,
    DIRECTION       VARCHAR2(50) NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT directions_pk PRIMARY KEY (DIRECTION_ID),
    CONSTRAINT directions_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS(USER_ID)
);

CREATE TABLE CARS (
    CAR_ID                NUMBER NOT NULL,
    USER_ID           NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    CREATED_AT        DATE NOT NULL,
    CONSTRAINT cars_pk PRIMARY KEY (CAR_ID),
    CONSTRAINT c_user_fk FOREIGN KEY (USER_ID) REFERENCES USERS (USER_ID)
)

CREATE TABLE ADMINISTRATORS (
    ADMINISTRATOR_ID                NUMBER NOT NULL,
    ADMINISTRATOR_LEVEL           NUMBER(1) NOT NULL,
    EMAIL           VARCHAR2(30) NOT NULL,
    ADMINISTRATOR_PASSWORD        VARCHAR2(20) NOT NULL,
    CREATED_AT        DATE NOT NULL,
    CONSTRAINT administrators_pk PRIMARY KEY (ADMINISTRATOR_ID)
)

CREATE TABLE CARPRODUCT (
    CP_ID                  NUMBER NOT NULL,
    CAR_ID           NUMBER NOT NULL,
    PRODUCT_ID           NUMBER NOT NULL,
    ACTIVE          NUMBER(1) NOT NULL,
    UNITS            NUMBER NOT NULL,
    PRICE            NUMBER NOT NULL,
    CREATED_AT        DATE NOT NULL,
    CONSTRAINT carproduct_pk PRIMARY KEY (CP_ID),
    CONSTRAINT cp_car_fk FOREIGN KEY (CAR_ID) REFERENCES CARS (CAR_ID),
    CONSTRAINT cp_product_fk FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCTS (PRODUCT_ID)
)

CREATE TABLE TRANSACTIONS (
    TRANSACTION_ID                NUMBER NOT NULL,
    NUMBER_BILL     NUMBER NOT NULL,
    BUYER_ID       NUMBER NOT NULL,
    DIRECTION_ID           NUMBER NOT NULL,
    PAY_ID           NUMBER NOT NULL,
    TOTAL             NUMBER NOT NULL,
    DATE_TIME  DATE NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT shops_pk PRIMARY KEY (TRANSACTION_ID),
    CONSTRAINT tr_buyer_fk FOREIGN KEY (BUYER_ID) REFERENCES USERS (USER_ID),
    CONSTRAINT tr_direction_fk FOREIGN KEY (DIRECTION_ID) REFERENCES PAYS (PAY_ID),
    CONSTRAINT tr_pay_fk FOREIGN KEY (PAY_ID) REFERENCES DIRECTIONS (DIRECTION_ID)
);

CREATE TABLE TRANSACTIONPRODUCT (
    TP_ID                  NUMBER NOT NULL,
    TRANSACTION_ID      NUMBER NOT NULL,
    PRODUCT_ID       NUMBER NOT NULL,
    SELLER_ID        NUMBER NOT NULL,
    UNITS            NUMBER NOT NULL,
    CREATED_AT      DATE NOT NULL,
    CONSTRAINT trpr_pk PRIMARY KEY ( TP_ID ),
    CONSTRAINT trpr_transaction_fk FOREIGN KEY (TRANSACTION_ID) REFERENCES TRANSACTIONS (TRANSACTION_ID),
    CONSTRAINT trpr_product_fk FOREIGN KEY (PRODUCT_ID) REFERENCES PRODUCTS (PRODUCT_ID),
    CONSTRAINT trpr_user_fk FOREIGN KEY (SELLER_ID) REFERENCES USERS (USER_ID)
);

/*Crear administrador*/

INSERT INTO administrators values (1, 0, 'admin@gmail.com', '123', SYSDATE);

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

CREATE SEQUENCE TRANSACTIONS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE TRPR_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE CARS_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE CARPR_SEQ
START WITH 1
INCREMENT BY 1;

CREATE SEQUENCE ADMINISTRATORS_SEQ
START WITH 1
INCREMENT BY 1;

/*Crear Triggers*/

CREATE OR REPLACE TRIGGER USERS_TRG
BEFORE INSERT ON USERS
FOR EACH ROW
BEGIN
    IF :NEW.USER_ID IS NULL THEN
        SELECT EDUARDED.USERS_SEQ.NEXTVAL INTO :NEW.USER_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PRODUCTS_TRG
BEFORE INSERT ON PRODUCTS
FOR EACH ROW
BEGIN
    IF :NEW.PRODUCT_ID IS NULL THEN
        SELECT EDUARDED.PRODUCTS_SEQ.NEXTVAL INTO :NEW.PRODUCT_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER PAYS_TRG
BEFORE INSERT ON PAYS
FOR EACH ROW
BEGIN
    IF :NEW.PAY_ID IS NULL THEN
        SELECT EDUARDED.PAYS_SEQ.NEXTVAL INTO :NEW.PAY_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER DIRECTIONS_TRG
BEFORE INSERT ON DIRECTIONS
FOR EACH ROW
BEGIN
    IF :NEW.DIRECTION_ID IS NULL THEN
        SELECT EDUARDED.DIRECTIONS_SEQ.NEXTVAL INTO :NEW.DIRECTION_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER TRANSACTIONS_TRG
BEFORE INSERT ON TRANSACTIONS
FOR EACH ROW
BEGIN
    IF :NEW.TRANSACTION_ID IS NULL THEN
        SELECT EDUARDED.TRANSACTIONS_SEQ.NEXTVAL INTO :NEW.TRANSACTION_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER TRPR_TRG
BEFORE INSERT ON TRANSACTIONPRODUCT
FOR EACH ROW
BEGIN
    IF :NEW.TP_ID IS NULL THEN
        SELECT EDUARDED.TRPR_SEQ.NEXTVAL INTO :NEW.TP_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER CARS_TRG
BEFORE INSERT ON CARS
FOR EACH ROW
BEGIN
    IF :NEW.CAR_ID IS NULL THEN
        SELECT EDUARDED.CARS_SEQ.NEXTVAL INTO :NEW.CAR_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER CARPR_TRG
BEFORE INSERT ON CARPRODUCT
FOR EACH ROW
BEGIN
    IF :NEW.CP_ID IS NULL THEN
        SELECT EDUARDED.CARPR_SEQ.NEXTVAL INTO :NEW.CP_ID FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER ADMINISTRATOR_TRG
BEFORE INSERT ON ADMINISTRATORS
FOR EACH ROW
BEGIN
    IF :NEW.ADMINISTRATOR_ID IS NULL THEN
        SELECT EDUARDED.ADMINISTRATORS_SEQ.NEXTVAL INTO :NEW.ADMINISTRATOR_ID FROM DUAL;
    END IF;
END;

/*Crear o Reemplazar Vistas*/

CREATE OR REPLACE VIEW SESSION_START AS
SELECT USER_ID, ACTIVE, CODE, NAME, SURNAME, PHONE, EMAIL, USER_PASSWORD, IMAGE
FROM users;

CREATE OR REPLACE VIEW PRODUCT_DETAIL AS
SELECT PRODUCT_ID, USER_ID, NAME, PRICE, UNITS, CONTENT, STOCK, DESCRIPTION, IMAGE
FROM products;

CREATE OR REPLACE VIEW PRODUCT_LIST AS
SELECT PRODUCT_ID, ACTIVE, NAME, PRICE, IMAGE
FROM products;

CREATE OR REPLACE VIEW TRANSACTIONS_LIST AS
SELECT TRANSACTION_ID, BUYER_ID, NUMBER_BILL, DATE_TIME
FROM transactions;

CREATE OR REPLACE VIEW PRODUCT_LIST_MANAGEMENT AS
SELECT PRODUCT_ID, ACTIVE, NAME, PRICE, UNITS, CONTENT, STOCK
FROM products;

CREATE OR REPLACE VIEW DIRECTIONS_LIST_MANAGEMENT AS
SELECT DIRECTION_ID, ACTIVE, CARRER, STREET, POSTAL_CODE, DIRECTION 
FROM directions;

CREATE OR REPLACE VIEW PAYS_LIST_MANAGEMENT AS
SELECT PAY_ID, ACTIVE, ELECTION, NUMBER_ELECTION
FROM pays;

CREATE OR REPLACE VIEW PRODUCT_DATA_PU AS
SELECT PRODUCT_ID, USER_ID, PRICE
FROM products;

/*Funciones*/

create or replace FUNCTION ADD_USER(userId IN NUMBER, userCode IN VARCHAR2) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET HIGHER_USER_ID = userId
    WHERE code = userCode;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DECREASE_INVENTORY(p_product_id IN NUMBER, t_cantidad IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE products
    SET stock = stock - t_cantidad
    WHERE product_id = p_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DECREASE_QUANTITY(cp_product_id IN NUMBER, c_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE carproduct
    SET units = units - 1 WHERE car_id IN (SELECT car_id FROM cars WHERE user_id = c_user_id)
    AND product_id = cp_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_CAR(user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE cars
    SET active = 0
    WHERE user_id = user_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_DIRECTION(d_direction_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE directions
    SET active = 0
    WHERE direction_id = d_direction_id;
    
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
    WHERE pay_id = p_pay_id;
    
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
    WHERE product_id = p_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el producto';
END;

create or replace FUNCTION DELETE_PRODUCT_CAR(cp_product_id IN NUMBER, c_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE carproduct
    SET active = 0 WHERE car_id IN (SELECT car_id FROM cars WHERE user_id = c_user_id)
    AND product_id = cp_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION DELETE_USER(u_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET active = 0
    WHERE user_id = u_user_id;
    
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
        WHERE product_id = p_product_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_PRODUCT;

create or replace FUNCTION DETAIL_SALE(t_transaction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT u.name AS USER_NAME, u.surname, u.email, u.phone, d.carrer, d.street, d.postal_code, d.direction, 
        pa.election, pa.number_election, p.name AS PRODUCT_NAME, p.price, p.content
        FROM TRANSACTIONS t
        INNER JOIN TRANSACTIONPRODUCT tp ON t.transaction_id = tp.transaction_id
        INNER JOIN products p ON p.product_id = tp.transaction_id
        INNER JOIN users u ON u.user_id = t.buyer_id
        INNER JOIN pays pa ON pa.pay_id = t.pay_id
        INNER JOIN directions d ON d.direction_id = t.direction_id
        WHERE t.transaction_id = t_transaction_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_SALE;

create or replace FUNCTION DETAIL_SHOP(t_transaction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT u.name AS USER_NAME, u.surname, u.email, u.phone, d.carrer, d.street, d.postal_code, 
        d.direction, pa.election, pa.number_election, p.name AS PRODUCT_NAME, p.price, p.content
        FROM TRANSACTIONS t
        INNER JOIN TRANSACTIONPRODUCT tp ON t.transaction_id = tp.transaction_id
        INNER JOIN products p ON p.product_id = tp.transaction_id
        INNER JOIN users u ON u.user_id = tp.seller_id
        INNER JOIN pays pa ON pa.pay_id = t.pay_id
        INNER JOIN directions d ON d.direction_id = t.direction_id
        WHERE t.transaction_id = t_transaction_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END DETAIL_SHOP;

create or replace FUNCTION DIRECTION_LIST_MANAGEMENT(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM DIRECTIONS
    WHERE ACTIVE = 1
    AND USER_ID = p_user_id;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END DIRECTION_LIST_MANAGEMENT;

create or replace FUNCTION GET_DATA_PRODUCT_P(p_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor con el registro del usuario que coincide por email y está activo
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCT_DATA_PU
    WHERE product_id = p_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        -- Manejo de excepción si no se encuentra el usuario
        RETURN NULL;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_DATA_PRODUCT_P;

create or replace FUNCTION GET_DIRECTION(d_direction_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    OPEN v_cursor FOR
        SELECT * 
        FROM DIRECTIONS_LIST_MANAGEMENT 
        WHERE direction_id = d_direction_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_DIRECTION;

create or replace FUNCTION GET_LAST_CAR
RETURN SYS_REFCURSOR
IS
    cur SYS_REFCURSOR;
BEGIN
    OPEN cur FOR
    SELECT NVL(MAX(CAR_ID), 0) AS ID
    FROM CARS;
    
    RETURN cur;
END;

create or replace FUNCTION GET_LAST_TRANSACTION
RETURN SYS_REFCURSOR
IS
    cur SYS_REFCURSOR;
BEGIN
    OPEN cur FOR
    SELECT NVL(MAX(transaction_id), 0) AS ID
    FROM TRANSACTIONS_LIST;
    
    RETURN cur;
END;

create or replace FUNCTION GET_PASSWORD(p_email IN VARCHAR2)
RETURN VARCHAR2
IS
    v_password VARCHAR2(255);
BEGIN
    -- Asegúrate de usar un filtro que garantice una única fila
    SELECT user_password
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
        WHERE pay_id = p_pay_id;

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
        WHERE product_id = p_product_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN NULL;
    WHEN OTHERS THEN
        RAISE;
END GET_PRODUCT;

create or replace FUNCTION GET_USER(u_id IN NUMBER)
RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor con el registro del usuario que coincide por email y está activo
    OPEN v_cursor FOR
    SELECT *
    FROM SESSION_START
    WHERE user_id = u_id;

    RETURN v_cursor;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        -- Manejo de excepción si no se encuentra el usuario
        RETURN NULL;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_USER;

create or replace FUNCTION INCREASE_PROFITS(t_id_seller IN NUMBER, t_total IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE users
    SET earnings = earnings + t_total
    WHERE user_id = t_id_seller;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

create or replace FUNCTION INCREASE_QUANTITY(cp_product_id IN NUMBER, c_user_id IN NUMBER) 
RETURN VARCHAR2 AS
BEGIN
    -- Actualizar el campo activo a 0 para el producto con el ID especificado
    UPDATE carproduct
    SET units = units + 1 WHERE car_id IN (SELECT car_id FROM cars WHERE user_id = c_user_id)
    AND product_id = cp_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 1;
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al eliminar el pago';
END;

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

CREATE OR REPLACE FUNCTION LOGINA(a_email IN VARCHAR2, a_password IN VARCHAR2)
RETURN NUMBER
IS
    v_count NUMBER;
BEGIN
    -- Contar los registros que coinciden con el email
    SELECT COUNT(*)
    INTO v_count
    FROM ADMINISTRATORS
    WHERE email = a_email
    AND administrator_password = a_password;

    -- Retornar 1 si el usuario existe, 0 si no
    IF v_count > 0 THEN
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 0;
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END LOGINA;

create or replace FUNCTION PAY_LIST_MANAGEMENT(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM PAYS
    WHERE ACTIVE = 1
    AND USER_ID = p_user_id;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PAY_LIST_MANAGEMENT;

create or replace FUNCTION PRODUCTS_LIST(
    p_user_id NUMBER := NULL  -- Parámetro opcional, por defecto NULL
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar productos activos, con stock disponible y del superior
    OPEN v_cursor FOR
    SELECT P.*
    FROM PRODUCTS P
    JOIN USERS U ON P.USER_ID = U.USER_ID
    WHERE P.ACTIVE = 1 
    AND P.STOCK > 0
    AND (p_user_id IS NULL 
         OR U.HIGHER_USER_ID = p_user_id); -- Selecciona los productos del usuario superior si p_user_id no es NULL.

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST;

create or replace FUNCTION PRODUCTS_LIST_CAR(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT p.product_id AS PRODUCT_ID, cp.cp_id, p.image, p.name, p.price, p.units, cp.units AS AMOUNT, p.stock
    FROM CARS c
    INNER JOIN carproduct cp ON c.car_id = cp.car_id
    INNER JOIN products p ON p.product_id = cp.product_id
    WHERE c.USER_ID = p_user_id
    AND cp.active = 1 AND c.active = 1;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_CAR;

create or replace FUNCTION PRODUCTS_LIST_CAR_P(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT u.name AS NAME_SELLER, u.surname, u.surname, u.email, u.phone, cp.cp_id, p.image, p.name AS NAME_PRODUCT, p.price, p.units, p.content, cp.units AS AMOUNT
    FROM CARS c
    INNER JOIN carproduct cp ON c.car_id = cp.car_id
    INNER JOIN products p ON p.product_id = cp.product_id
    INNER JOIN users u ON u.user_id = p.user_id
    WHERE c.USER_ID = p_user_id
    AND cp.active = 1 AND c.active = 1;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_CAR_P;

create or replace FUNCTION PRODUCTS_LIST_MANAGEMENT(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM PRODUCTS
    WHERE ACTIVE = 1
    AND USER_ID = p_user_id;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END PRODUCTS_LIST_MANAGEMENT;

create or replace FUNCTION REGISTER_CAR(
    c_user_id IN NUMBER,
    c_active IN NUMBER,
    c_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO CARS (user_id, active, created_at)
        VALUES (c_user_id, c_active, c_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_CP(
    cp_id_car IN NUMBER,
    cp_id_product IN NUMBER,
    cp_active IN NUMBER,
    cp_units IN NUMBER,
    cp_price IN NUMBER,
    cp_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO carproduct (car_id, product_id, active, units, price, created_at)
        VALUES (cp_id_car, cp_id_product, cp_active, cp_units, cp_price, cp_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

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
) RETURN NUMBER
AS
    v_count NUMBER;
BEGIN
    -- Verificar si el producto con el mismo nombre ya existe para ese usuario
    SELECT COUNT(*) INTO v_count
    FROM PRODUCTS
    WHERE user_id = p_user_id AND name = p_name;

    IF v_count > 0 THEN
        -- Si existe, actualiza el stock
        UPDATE PRODUCTS
        SET stock = stock + p_stock
        WHERE user_id = p_user_id AND name = p_name;
        COMMIT;
        RETURN 1; -- Retorna 1 si se actualizó exitosamente
    ELSE
        -- Si no existe, inserta el nuevo producto
        INSERT INTO PRODUCTS (user_id, active, name, price, units, content, stock, description, image, created_at)
        VALUES (p_user_id, p_active, p_name, p_price, p_units, p_content, p_stock, p_description, p_image, p_created_at);
        COMMIT;
        RETURN 2; -- Retorna 2 si se insertó exitosamente
    END IF;

EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RETURN -1; -- Retorna -1 en caso de error
END;

create or replace FUNCTION REGISTER_TP(
    tp_id_transaction IN NUMBER,
    tp_id_product IN NUMBER,
    tp_units IN NUMBER,
    tp_price IN NUMBER,
    tp_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO TRANSACTIONPRODUCT (transaction_id, product_id, seller_id, units, created_at)
        VALUES (tp_id_transaction, tp_id_product, tp_units, tp_price, tp_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_TRANSACTION(
    t_number_bill IN NUMBER,
    t_id_buyer IN NUMBER,
    t_id_direction IN NUMBER,
    t_id_pay IN NUMBER,
    t_total IN NUMBER,
    t_date_time IN DATE,
    t_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO TRANSACTIONS (number_bill, buyer_id, direction_id, pay_id, total, date_time, created_at)
        VALUES (t_number_bill, t_id_buyer, t_id_direction, t_id_pay, t_total, t_date_time, t_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION REGISTER_USER(
    u_active IN NUMBER,
    u_user_level IN NUMBER,
    u_code IN VARCHAR2,
    u_name IN VARCHAR2,
    u_surname IN VARCHAR2,
    u_birthdate IN DATE,
    u_genre IN VARCHAR2,
    u_phone IN NUMBER,
    u_email IN VARCHAR2,
    u_password IN VARCHAR2,
    u_image IN VARCHAR2,
    u_earnings IN NUMBER,
    u_higuer_user_level IN NUMBER,
    u_created_at IN DATE
) RETURN VARCHAR2
AS
    v_resultado VARCHAR2(100);
BEGIN
    BEGIN
        INSERT INTO USERS (active, user_level, code, name, surname, birthdate, genre, phone, email, user_password, image, earnings, higher_user_id, created_at)
        VALUES (u_active, u_user_level, u_code, u_name, u_surname, u_birthdate, u_genre, u_phone, u_email, u_password, u_image, u_earnings, u_higuer_user_level, u_created_at);

        COMMIT;
        v_resultado := 1;
    EXCEPTION
        WHEN OTHERS THEN
            v_resultado := 'Error al registrar usuario: ' || SQLERRM;
            ROLLBACK;
    END;

    RETURN v_resultado;
END;

create or replace FUNCTION SALES_LIST(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM TRANSACTIONS_LIST tl
    INNER JOIN TRANSACTIONPRODUCT tp ON tp.transaction_id = tl.transaction_id
    WHERE tp.seller_id = p_user_id;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END SALES_LIST;

create or replace FUNCTION SHOPPING_LIST(
    p_user_id NUMBER  -- El ID del usuario, que se recibirá siempre
) RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT *
    FROM TRANSACTIONS_LIST
    WHERE buyer_id = p_user_id;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END SHOPPING_LIST;

create or replace FUNCTION UNIQUE_CP(c_id_user IN NUMBER, cp_id_product IN NUMBER)
RETURN NUMBER
IS
  v_count NUMBER;
BEGIN
  SELECT COUNT(*)
  INTO v_count
  FROM cars 
  WHERE active = 1 
  AND car_id IN (
    SELECT car_id 
    FROM carproduct 
    WHERE user_id = c_id_user 
    AND product_id = cp_id_product 
    AND active = 1
  );
  
  IF v_count > 0 THEN
    RETURN 1;
  ELSE
    RETURN 0;
  END IF;
END;

create or replace FUNCTION UPDATE_DIRECTION(
    d_direction_id IN NUMBER,
    d_carrer IN VARCHAR2 DEFAULT NULL,
    d_street IN VARCHAR2 DEFAULT NULL,
    d_postal_code IN NUMBER DEFAULT NULL,
    d_direction IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE directions
    SET 
        carrer = NVL(d_carrer, carrer),
        street = NVL(d_street, street),
        postal_code = NVL(d_postal_code, postal_code),
        direction = NVL(d_direction, direction)
    WHERE direction_id = d_direction_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar la direccion';
END;

create or replace FUNCTION UPDATE_PAY(
    p_pay_id IN NUMBER,
    p_election IN VARCHAR2 DEFAULT NULL,
    p_election_number IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE pays
    SET 
        election = NVL(p_election, election),
        number_election = NVL(p_election_number, number_election)
    WHERE pay_id = p_pay_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el pago';
END;

create or replace FUNCTION UPDATE_PRODUCT(
    p_product_id IN NUMBER,
    p_name IN VARCHAR2 DEFAULT NULL,
    p_price IN NUMBER DEFAULT NULL,
    p_units IN NUMBER DEFAULT NULL,
    p_content IN VARCHAR2 DEFAULT NULL,
    p_stock IN NUMBER DEFAULT NULL,
    p_description IN VARCHAR2 DEFAULT NULL,
    p_image IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE products
    SET 
        name = NVL(p_name, name),
        price = NVL(p_price, price),
        units = NVL(p_units, units),
        content = NVL(p_content, content),
        stock = NVL(p_stock, stock),
        description = NVL(p_description, description),      
        image = NVL(p_image, image)
    WHERE product_id = p_product_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el producto';
END;

create or replace FUNCTION UPDATE_USER(
    u_user_id IN NUMBER,
    u_name IN VARCHAR2 DEFAULT NULL,
    u_surname IN VARCHAR2 DEFAULT NULL,
    u_phone IN NUMBER DEFAULT NULL,
    u_email IN VARCHAR2 DEFAULT NULL,
    u_image IN VARCHAR2 DEFAULT NULL
) 
RETURN VARCHAR2 AS
BEGIN
    -- Construir la consulta dinámica para actualizar solo los campos no nulos
    UPDATE users
    SET 
        name = NVL(u_name, name),
        surname = NVL(u_surname, surname),
        phone = NVL(u_phone, phone),
        email = NVL(u_email, email),
        image = NVL(u_image, image)
    WHERE user_id = u_user_id;
    
    -- Confirmar la transacción
    COMMIT;
    
    -- Retornar un mensaje de éxito
    RETURN 'Actualización exitosa';
EXCEPTION
    WHEN OTHERS THEN
        -- En caso de error, devolver un mensaje
        RETURN 'Error al actualizar el usuario';
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

create or replace FUNCTION GET_PRODUCTS RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT p.product_id, p.name, p.price, p.stock, p.units, p.content, u.name AS USER_NAME, u.user_id
    FROM PRODUCTS p
    INNER JOIN USERS u ON u.user_id = p.user_id;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_PRODUCTS;

create or replace FUNCTION GET_PRODUCTS_ADMIN RETURN SYS_REFCURSOR
IS
    v_cursor SYS_REFCURSOR;
BEGIN
    -- Abrir un cursor para seleccionar todos los pagos donde ACTIVE sea igual a 1
    -- y el USER_ID sea el dueño del pago
    OPEN v_cursor FOR
    SELECT p.product_id, p.name, p.price, p.stock, p.units, p.content
    FROM PRODUCTS p
    where user_id is null;  -- Compara si el ID que llega es del dueño del pago

    RETURN v_cursor; -- Retornar el cursor con los registros
EXCEPTION
    WHEN OTHERS THEN
        -- Manejo de otras excepciones
        RAISE;
END GET_PRODUCTS_ADMIN;